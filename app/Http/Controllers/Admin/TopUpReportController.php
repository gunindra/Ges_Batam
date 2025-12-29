<?php

namespace App\Http\Controllers\Admin;
use App\Exports\AssetReportExport;
use App\Exports\TopupReportExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\PaymentInvoice;
use App\Models\Asset;
use App\Models\HistoryTopup;
use App\Traits\WhatsappTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use Str;

class TopUpReportController extends Controller
{
    use WhatsappTrait;

    public function index() {

        $customers = Customer::where('company_id', '=', 1)->get();
        return view('Report.TopUpReport.indextopupreport', compact('customers'));
    }

    public function getTopUpReport(Request $request)
{
    $companyId = session('active_company_id');
    $customer  = $request->customer;

    $startDate = filled($request->startDate)
        ? Carbon::parse($request->startDate)->format('Y-m-d')
        : '2025-01-01';

    $endDate = filled($request->endDate)
        ? Carbon::parse($request->endDate)->format('Y-m-d')
        : Carbon::now()->endOfMonth()->format('Y-m-d');

    $isCustomerRole  = auth()->user() && auth()->user()->role === 'customer';
    $userIdCondition = $isCustomerRole ? "AND tp.user_id = ?" : "";

    /*
    |--------------------------------------------------------------------------
    | SALDO AWAL
    |--------------------------------------------------------------------------
    */
    $initialBalanceQuery = "
        WITH initial_data AS (
            -- IN (TOPUP)
            SELECT
                tp.marking,
                SUM(tht.remaining_points) AS in_points,
                0 AS out_points,
                0 AS expired_points,
                0 AS retur_points,
                MAX(tht.price_per_kg) AS price_per_kg
            FROM tbl_history_topup tht
            JOIN tbl_pembeli tp ON tht.customer_id = tp.id
            WHERE tht.status != 'canceled'
              AND tht.date < ?
              AND tp.company_id = ?
              " . ($customer ? "AND tp.id = ?" : "") . "
              $userIdCondition
            GROUP BY tp.marking

            UNION ALL

            -- OUT (USAGE)
            SELECT
                tp.marking,
                0,
                SUM(tup.used_points),
                0,
                0,
                NULL
            FROM tbl_usage_points tup
            JOIN tbl_pembeli tp ON tup.customer_id = tp.id
            WHERE tup.usage_date < ?
              AND tp.company_id = ?
              " . ($customer ? "AND tp.id = ?" : "") . "
              $userIdCondition
            GROUP BY tp.marking

            UNION ALL

            -- OUT (EXPIRED)
            SELECT
                tp.marking,
                0,
                0,
                SUM(tht.expired_amount),
                0,
                NULL
            FROM tbl_history_topup tht
            JOIN tbl_pembeli tp ON tht.customer_id = tp.id
            WHERE tht.status = 'expired'
              AND tht.expired_date < ?
              AND tp.company_id = ?
              " . ($customer ? "AND tp.id = ?" : "") . "
              $userIdCondition
            GROUP BY tp.marking

            UNION ALL

            -- IN (RETUR)
            SELECT
                tp.marking,
                0,
                0,
                0,
                SUM(resi.berat),
                NULL
            FROM tbl_retur_item tri
            JOIN tbl_resi resi ON tri.resi_id = resi.id
            JOIN tbl_retur tr ON tri.retur_id = tr.id
            JOIN tbl_invoice ti ON tr.invoice_id = ti.id
            JOIN tbl_pembeli tp ON ti.pembeli_id = tp.id
            WHERE tr.account_id = 159
              AND DATE(tr.created_at) < ?
              AND tp.company_id = ?
              " . ($customer ? "AND tp.id = ?" : "") . "
              $userIdCondition
            GROUP BY tp.marking
        )
        SELECT
            marking,
            SUM(in_points + retur_points - out_points - expired_points) AS initial_balance,
            MAX(price_per_kg) AS price_per_kg
        FROM initial_data
        GROUP BY marking
    ";

    $initialParams = [];
    for ($i = 0; $i < 4; $i++) {
        $initialParams[] = $startDate;
        $initialParams[] = $companyId;
        if ($customer) $initialParams[] = $customer;
        if ($isCustomerRole) $initialParams[] = auth()->user()->id;
    }

    $initialBalances = DB::select($initialBalanceQuery, $initialParams);

    $initialBalanceMap = [];
    foreach ($initialBalances as $row) {
        $initialBalanceMap[$row->marking] = [
            'balance'      => (float) $row->initial_balance,
            'price_per_kg' => (float) ($row->price_per_kg ?? 0),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | TRANSAKSI PERIODE
    |--------------------------------------------------------------------------
    */
    $query = "
        SELECT * FROM (
            -- OUT (USAGE)
            SELECT
                MIN(tup.usage_date) AS date,
                MIN(tup.created_at) AS created_at,
                tp.marking,
                0 AS in_points,
                SUM(tup.used_points) AS out_points,
                SUM(tup.used_points * tup.price_per_kg) AS value,
                'OUT' AS status,
                MAX(tup.price_per_kg) AS price_per_kg,
                (
                    SELECT GROUP_CONCAT(ti.no_invoice SEPARATOR ', ')
                    FROM tbl_payment_invoice tpi
                    JOIN tbl_invoice ti ON tpi.invoice_id = ti.id
                    WHERE tpi.payment_id = tup.payment_id
                ) AS no_invoice
            FROM tbl_usage_points tup
            JOIN tbl_pembeli tp ON tup.customer_id = tp.id
            WHERE tup.usage_date BETWEEN ? AND ?
              AND tp.company_id = ?
              " . ($customer ? "AND tp.id = ?" : "") . "
              $userIdCondition
            GROUP BY tup.payment_id, tp.marking

            UNION ALL

            -- IN (TOPUP)
            SELECT
                tht.date,
                tht.created_at,
                tp.marking,
                tht.remaining_points AS in_points,
                0 AS out_points,
                tht.remaining_points * tht.price_per_kg AS value,
                'IN' AS status,
                tht.price_per_kg,
                '-' AS no_invoice
            FROM tbl_history_topup tht
            JOIN tbl_pembeli tp ON tht.customer_id = tp.id
            WHERE tht.status != 'canceled'
              AND tht.date BETWEEN ? AND ?
              AND tp.company_id = ?
              " . ($customer ? "AND tp.id = ?" : "") . "
              $userIdCondition

            UNION ALL

            -- OUT (EXPIRED)
            SELECT
                tht.expired_date,
                tht.created_at,
                tp.marking,
                0,
                tht.expired_amount,
                tht.expired_amount * tht.price_per_kg,
                'OUT (expired)',
                tht.price_per_kg,
                '-'
            FROM tbl_history_topup tht
            JOIN tbl_pembeli tp ON tht.customer_id = tp.id
            WHERE tht.status = 'expired'
              AND tht.expired_date BETWEEN ? AND ?
              AND tp.company_id = ?
              " . ($customer ? "AND tp.id = ?" : "") . "
              $userIdCondition

            UNION ALL

            -- IN (RETUR)
            SELECT
                tr.created_at,
                tr.created_at,
                tp.marking,
                resi.berat,
                0,
                resi.berat * (resi.harga / resi.berat),
                'IN (Retur)',
                (resi.harga / resi.berat),
                ti.no_invoice
            FROM tbl_retur_item tri
            JOIN tbl_resi resi ON tri.resi_id = resi.id
            JOIN tbl_retur tr ON tri.retur_id = tr.id
            JOIN tbl_invoice ti ON tr.invoice_id = ti.id
            JOIN tbl_pembeli tp ON ti.pembeli_id = tp.id
            WHERE tr.account_id = 159
              AND DATE(tr.created_at) BETWEEN ? AND ?
              AND tp.company_id = ?
              " . ($customer ? "AND tp.id = ?" : "") . "
              $userIdCondition
        ) x
        ORDER BY marking, date, created_at
    ";

    $params = [];
    for ($i = 0; $i < 4; $i++) {
        $params[] = $startDate;
        $params[] = $endDate;
        $params[] = $companyId;
        if ($customer) $params[] = $customer;
        if ($isCustomerRole) $params[] = auth()->user()->id;
    }

    $rows = DB::select($query, $params);

    /*
    |--------------------------------------------------------------------------
    | GROUP DATA
    |--------------------------------------------------------------------------
    */
    $groupedData = [];
    foreach ($rows as $row) {
        $groupedData[$row->marking][] = $row;
    }

    $allMarkings = array_unique(array_merge(
        array_keys($initialBalanceMap),
        array_keys($groupedData)
    ));

    /*
    |--------------------------------------------------------------------------
    | OUTPUT HTML
    |--------------------------------------------------------------------------
    */
    $output = '<h5 style="text-align:center">'
        . Carbon::parse($startDate)->format('d M Y') . ' - '
        . Carbon::parse($endDate)->format('d M Y')
        . '</h5>
        <table class="table" width="100%">
        <thead>
            <th>Date</th>
            <th>Marking</th>
            <th>Invoice</th>
            <th>In (Kg)</th>
            <th>Out (Kg)</th>
            <th>Saldo (Kg)</th>';

    if (!$isCustomerRole) {
        $output .= '<th>Value</th><th>Saldo Value</th>';
    }

    $output .= '<th>Status</th></thead><tbody>';

    foreach ($allMarkings as $marking) {
        $initialBalance = $initialBalanceMap[$marking]['balance'] ?? 0;
        $pricePerKg     = $initialBalanceMap[$marking]['price_per_kg'] ?? 0;

        if (!isset($groupedData[$marking]) && $initialBalance == 0) continue;

        $currentSaldo = $initialBalance;
        $currentValue = $currentSaldo * $pricePerKg;

        // SALDO AWAL
        $output .= '<tr style="background:#f8f9fa;font-weight:bold">
            <td>' . Carbon::parse($startDate)->format('d M Y') . ' (Awal)</td>
            <td>' . $marking . '</td>
            <td>-</td><td>-</td><td>-</td>
            <td>' . number_format($currentSaldo, 2) . '</td>';

        if (!$isCustomerRole) {
            $output .= '<td>-</td><td>' . number_format($currentValue, 2) . '</td>';
        }

        $output .= '<td>SALDO AWAL</td></tr>';

        foreach ($groupedData[$marking] ?? [] as $row) {
            if (str_starts_with($row->status, 'IN')) {
                $currentSaldo += $row->in_points;
                $currentValue += $row->value;
            } else {
                $currentSaldo -= $row->out_points;
                $currentValue -= $row->value;
            }

            $output .= '<tr>
                <td>' . Carbon::parse($row->date)->format('d M Y') . '</td>
                <td>' . $marking . '</td>
                <td>' . ($row->no_invoice ?: '-') . '</td>
                <td>' . number_format($row->in_points, 2) . '</td>
                <td>' . number_format($row->out_points, 2) . '</td>
                <td>' . number_format($currentSaldo, 2) . '</td>';

            if (!$isCustomerRole) {
                $output .= '<td>' . number_format($row->value, 2) . '</td>
                            <td>' . number_format($currentValue, 2) . '</td>';
            }

            $output .= '<td>' . strtoupper($row->status) . '</td></tr>';
        }
    }

    $output .= '</tbody></table>';

    return $output;
}




    public function generatePdf(Request $request)
    {
        $request->validate([
            'startDate' => 'nullable|date',
            'endDate'   => 'nullable|date|after_or_equal:startDate',
            'nama_pembeli' => 'nullable|exists:tbl_pembeli,id',
        ]);

        $companyId = session('active_company_id');
        $startDate = filled($request->startDate)
            ? Carbon::parse($request->startDate)->format('Y-m-d')
            : '2025-01-01';

        $endDate = filled($request->endDate)
            ? Carbon::parse($request->endDate)->format('Y-m-d')
            : Carbon::now()->endOfMonth()->format('Y-m-d');

        $customerId     = $request->nama_pembeli;
        $isCustomerRole = auth()->user() && auth()->user()->role === 'customer';
        $userCond       = $isCustomerRole ? "AND tp.user_id = ?" : "";

        try {

            /*
            |--------------------------------------------------------------------------
            | 1. SALDO AWAL
            |--------------------------------------------------------------------------
            */
            $initialQuery = "
                WITH data AS (
                    -- IN
                    SELECT tp.marking, SUM(tht.remaining_points) AS in_points, 0 out_points, 0 expired_points
                    FROM tbl_history_topup tht
                    JOIN tbl_pembeli tp ON tht.customer_id = tp.id
                    WHERE tht.status != 'canceled'
                    AND tht.date < ?
                    AND tp.company_id = ?
                    " . ($customerId ? "AND tp.id = ?" : "") . "
                    $userCond
                    GROUP BY tp.marking

                    UNION ALL

                    -- OUT
                    SELECT tp.marking, 0, SUM(tup.used_points), 0
                    FROM tbl_usage_points tup
                    JOIN tbl_pembeli tp ON tup.customer_id = tp.id
                    WHERE tup.usage_date < ?
                    AND tp.company_id = ?
                    " . ($customerId ? "AND tp.id = ?" : "") . "
                    $userCond
                    GROUP BY tp.marking

                    UNION ALL

                    -- EXPIRED
                    SELECT tp.marking, 0, 0, SUM(tht.expired_amount)
                    FROM tbl_history_topup tht
                    JOIN tbl_pembeli tp ON tht.customer_id = tp.id
                    WHERE tht.status = 'expired'
                    AND tht.expired_date < ?
                    AND tp.company_id = ?
                    " . ($customerId ? "AND tp.id = ?" : "") . "
                    $userCond
                    GROUP BY tp.marking
                )
                SELECT marking,
                    SUM(in_points - out_points - expired_points) AS saldo_awal
                FROM data
                GROUP BY marking
            ";

            $initParams = [];
            for ($i = 0; $i < 3; $i++) {
                $initParams[] = $startDate;
                $initParams[] = $companyId;
                if ($customerId) $initParams[] = $customerId;
                if ($isCustomerRole) $initParams[] = auth()->id();
            }

            $initialBalances = DB::select($initialQuery, $initParams);
            $initialMap = collect($initialBalances)->pluck('saldo_awal', 'marking')->toArray();

            /*
            |--------------------------------------------------------------------------
            | 2. TRANSAKSI PERIODE
            |--------------------------------------------------------------------------
            */
            $trxQuery = "
                SELECT * FROM (
                    -- OUT
                    SELECT
                        MIN(tup.usage_date) AS date,
                        MIN(tup.created_at) AS created_at,
                        tp.marking,
                        0 AS in_points,
                        SUM(tup.used_points) AS out_points,
                        SUM(tup.used_points * tup.price_per_kg) AS value,
                        'OUT' AS status,
                        (
                            SELECT GROUP_CONCAT(ti.no_invoice SEPARATOR ', ')
                            FROM tbl_payment_invoice tpi
                            JOIN tbl_invoice ti ON tpi.invoice_id = ti.id
                            WHERE tpi.payment_id = tup.payment_id
                        ) AS no_invoice
                    FROM tbl_usage_points tup
                    JOIN tbl_pembeli tp ON tup.customer_id = tp.id
                    WHERE tup.usage_date BETWEEN ? AND ?
                    AND tp.company_id = ?
                    " . ($customerId ? "AND tp.id = ?" : "") . "
                    $userCond
                    GROUP BY tup.payment_id, tp.marking

                    UNION ALL

                    -- IN
                    SELECT
                        tht.date,
                        tht.created_at,
                        tp.marking,
                        tht.remaining_points,
                        0,
                        tht.remaining_points * tht.price_per_kg,
                        'IN',
                        '-'
                    FROM tbl_history_topup tht
                    JOIN tbl_pembeli tp ON tht.customer_id = tp.id
                    WHERE tht.status != 'canceled'
                    AND tht.date BETWEEN ? AND ?
                    AND tp.company_id = ?
                    " . ($customerId ? "AND tp.id = ?" : "") . "
                    $userCond
                ) x
                ORDER BY marking, date, created_at
            ";

            $trxParams = [];
            for ($i = 0; $i < 2; $i++) {
                $trxParams[] = $startDate;
                $trxParams[] = $endDate;
                $trxParams[] = $companyId;
                if ($customerId) $trxParams[] = $customerId;
                if ($isCustomerRole) $trxParams[] = auth()->id();
            }

            $rows = DB::select($trxQuery, $trxParams);

            /*
            |--------------------------------------------------------------------------
            | 3. HITUNG SALDO BERJALAN (PHP)
            |--------------------------------------------------------------------------
            */
            $final = [];
            $grouped = collect($rows)->groupBy('marking');

            $allMarkings = array_unique(array_merge(
                array_keys($initialMap),
                $grouped->keys()->toArray()
            ));

            foreach ($allMarkings as $marking) {
                $saldo = $initialMap[$marking] ?? 0;

                // SALDO AWAL
                $final[] = (object)[
                    'date' => $startDate,
                    'marking' => $marking,
                    'in_points' => null,
                    'out_points' => null,
                    'saldo' => $saldo,
                    'status' => 'SALDO AWAL',
                    'no_invoice' => '-'
                ];

                foreach ($grouped[$marking] ?? [] as $row) {
                    $saldo += ($row->in_points - $row->out_points);

                    $final[] = (object)[
                        'date' => $row->date,
                        'marking' => $marking,
                        'in_points' => $row->in_points,
                        'out_points' => $row->out_points,
                        'saldo' => $saldo,
                        'status' => $row->status,
                        'no_invoice' => $row->no_invoice
                    ];
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 4. GENERATE PDF
            |--------------------------------------------------------------------------
            */
            $customer = $customerId ? Customer::find($customerId) : null;

            $pdf = PDF::loadView('exportPDF.topupreport', [
                'combined'  => $final,
                'marking'   => $customer->marking ?? 'ALL',
                'startDate' => Carbon::parse($startDate)->format('d M Y'),
                'endDate'   => Carbon::parse($endDate)->format('d M Y'),
            ])->setPaper('A4', 'portrait');

            return $pdf->stream('topup_report.pdf');

        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['error' => 'Failed generate PDF'], 500);
        }
    }



    public function exportTopupReport(Request $request)
    {
        $startDate = $request->input('startDate');
        $customer = $request->nama_pembeli ?? '-';
        $endDate = $request->input('endDate');

        return Excel::download(new TopupReportExport($customer, $startDate, $endDate), 'topup_report.xlsx');
    }
}
