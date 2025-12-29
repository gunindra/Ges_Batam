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
        $customer = $request->customer;
        $startDate = $request->startDate !== null && $request->startDate !== ''
        ? Carbon::parse($request->startDate)->format('Y-m-d')
        : '2025-01-01';


        $endDate = filled($request->endDate)
        ? Carbon::parse($request->endDate)->format('Y-m-d')
        : Carbon::now()->endOfMonth()->format('Y-m-d');
        
        $isCustomerRole = auth()->user() && auth()->user()->role === 'customer';
        $userIdCondition = $isCustomerRole ? "AND tp.user_id = ?" : "";

        // Query untuk mendapatkan saldo awal sebelum tanggal mulai
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

            if ($customer) {
                $initialParams[] = $customer;
            }

            if ($isCustomerRole) {
                $initialParams[] = auth()->user()->id;
            }
        }


        $initialBalances = DB::select($initialBalanceQuery, $initialParams);
        $initialBalanceMap = [];
        foreach ($initialBalances as $balance) {
            $initialBalanceMap[$balance->marking] = [
                'balance' => $balance->initial_balance,
                'price_per_kg' => $balance->price_per_kg
            ];
        }
        
        $query = "
               WITH combined_data AS (
                SELECT
                    date,
                    created_at,
                    marking,
                    CASE
                        WHEN type = 'IN' THEN points
                        WHEN type = 'IN (Retur)' THEN points
                        ELSE 0
                    END AS in_points,
                    CASE
                        WHEN type = 'OUT' THEN points
                        WHEN type = 'OUT (expired)' THEN expired_amount
                        ELSE 0
                    END AS out_points,
                    CASE
                        WHEN type = 'OUT (expired)' THEN expired_amount * price_per_kg
                        WHEN type = 'IN (Retur)' THEN points * price_per_kg
                        WHEN type = 'OUT' THEN points * price_per_kg
                        WHEN type = 'IN' THEN points * price_per_kg
                        ELSE 0
                    END AS value,
                    price_per_kg,
                    type AS status,
                    no_invoice
                FROM (
                    -- Transaksi OUT (penggunaan points)
                    SELECT
                        MIN(tup.usage_date) AS date,
                        MIN(tup.created_at) AS created_at,
                        tp.marking,
                        SUM(tup.used_points) AS points,
                        MAX(tup.price_per_kg) AS price_per_kg,
                        NULL AS expired_amount,
                        'OUT' AS type,
                        (SELECT GROUP_CONCAT(ti.no_invoice SEPARATOR ', ')
                            FROM tbl_payment_invoice tpi
                            JOIN tbl_invoice ti ON tpi.invoice_id = ti.id
                            WHERE tup.payment_id = tpi.payment_id) AS no_invoice
                    FROM tbl_usage_points tup
                    JOIN tbl_pembeli tp ON tup.customer_id = tp.id
                    WHERE tup.usage_date BETWEEN ? AND ?
                    AND tp.company_id = ?
                    " . ($customer ? "AND tp.id = ?" : "") . "
                    " . $userIdCondition . "
                    GROUP BY tup.payment_id, tp.marking

                    UNION ALL

                    -- Transaksi IN (topup)
                    SELECT
                        tht.date,
                        tht.created_at,
                        tp.marking,
                        tht.remaining_points AS points,
                        tht.price_per_kg,
                        NULL AS expired_amount,
                        'IN' AS type,
                        '-' AS no_invoice
                    FROM tbl_history_topup tht
                    JOIN tbl_pembeli tp ON tht.customer_id = tp.id
                    WHERE tht.status != 'canceled'
                    AND tht.date BETWEEN ? AND ?
                    AND tp.company_id = ?
                    " . ($customer ? "AND tp.id = ?" : "") . "
                    " . $userIdCondition . "

                    UNION ALL

                    -- Transaksi OUT (expired)
                    SELECT
                        tht.expired_date AS date,
                        tht.created_at,
                        tp.marking,
                        0 AS points,  -- Tidak menggunakan points untuk expired
                        tht.price_per_kg,
                        tht.expired_amount AS expired_amount,
                        'OUT (expired)' AS type,
                        '-' AS no_invoice
                    FROM tbl_history_topup tht
                    JOIN tbl_pembeli tp ON tht.customer_id = tp.id
                    WHERE tht.status = 'expired'
                    AND tht.expired_date BETWEEN ? AND ?
                    AND tp.company_id = ?
                    " . ($customer ? "AND tp.id = ?" : "") . "
                    " . $userIdCondition . "

                    UNION ALL

                    -- Transaksi IN (Retur)
                    SELECT
                        tr.created_at AS date,
                        tr.created_at,
                        tp.marking,
                        resi.berat AS points,
                        resi.harga/berat AS price_per_kg,
                        Null AS expired_amount,
                        'IN (Retur)' AS type,
                        ti.no_invoice AS no_invoice
                    FROM tbl_retur_item tri
                    JOIN tbl_resi resi ON tri.resi_id = resi.id
                    JOIN tbl_retur tr ON tri.retur_id = tr.id
                    JOIN tbl_invoice ti ON tr.invoice_id = ti.id
                    JOIN tbl_pembeli tp ON ti.pembeli_id = tp.id
                    WHERE tr.account_id = 159
                    AND DATE(tr.created_at) BETWEEN ? AND ?
                    AND tp.company_id = ?
                    " . ($customer ? "AND tp.id = ?" : "") . "
                    " . $userIdCondition . "

                ) AS raw_data
            ),
            calculated_data AS (
                SELECT
                    date,
                    created_at,
                    marking,
                    in_points,
                    out_points,
                    value,
                    status,
                    SUM(in_points - out_points) OVER (PARTITION BY marking ORDER BY date, created_at) AS saldo,
                    price_per_kg,
                    no_invoice
                FROM combined_data
            )
            SELECT
                date,
                created_at,
                marking,
                in_points,
                out_points,
                saldo,
                saldo * price_per_kg AS saldo_value,
                value,
                status,
                no_invoice,
                price_per_kg
            FROM calculated_data
            ORDER BY marking, date, created_at;
        ";

        $params = [];

        $params = array_merge($params, [$startDate, $endDate, $companyId]);
        if ($customer) {
            $params[] = $customer;
        }
        if ($isCustomerRole) {
            $params[] = auth()->user()->id;
        }

        $params = array_merge($params, [$startDate, $endDate, $companyId]);
        if ($customer) {
            $params[] = $customer;
        }
        if ($isCustomerRole) {
            $params[] = auth()->user()->id;
        }

        $params = array_merge($params, [$startDate, $endDate, $companyId]);
        if ($customer) {
            $params[] = $customer;
        }
        if ($isCustomerRole) {
            $params[] = auth()->user()->id;
        }

        $params = array_merge($params, [$startDate, $endDate, $companyId]);
        if ($customer) {
            $params[] = $customer;
        }
        if ($isCustomerRole) {
            $params[] = auth()->user()->id;
        }

        $data = DB::select($query, $params);

        $groupedData = [];
        foreach ($data as $row) {
            $groupedData[$row->marking][] = $row;
        }
        
        $output = '
            <h5 style="text-align:center; width:100%">'
                . Carbon::parse($startDate)->format('d M Y') . ' - '
                . Carbon::parse($endDate)->format('d M Y') .
            '</h5>
            <div class="card-body">
            <table class="table" width="100%">
            <thead>
                <th width="15%" style="text-align:center;">Date</th>
                <th width="10%" style="text-align:center;">Marking</th>
                <th width="10%" style="text-align:center;">Invoice</th>
                <th width="10%" style="text-align:center;">In (Kg)</th>
                <th width="10%" style="text-align:center;">Out (Kg)</th>
                <th width="10%" style="text-align:center;">Saldo (Kg)</th>';

        if (!$isCustomerRole) {
            $output .= '<th width="15%" style="text-align:center;">Value (Rp)</th>
                        <th width="15%" style="text-align:center;">Saldo Value (Rp)</th>';
        }

        $output .= '<th width="5%" style="text-align:center;">Status</th>
            </thead>
            <tbody>';

        // Add initial balance row for each customer
        $output = '
            <h5 style="text-align:center; width:100%">'
                . Carbon::parse($startDate)->format('d M Y') . ' - '
                . Carbon::parse($endDate)->format('d M Y') .
            '</h5>
            <div class="card-body">
            <table class="table" width="100%">
            <thead>
                <th width="15%" style="text-align:center;">Date</th>
                <th width="10%" style="text-align:center;">Marking</th>
                <th width="10%" style="text-align:center;">Invoice</th>
                <th width="10%" style="text-align:center;">In (Kg)</th>
                <th width="10%" style="text-align:center;">Out (Kg)</th>
                <th width="10%" style="text-align:center;">Saldo (Kg)</th>';

        if (!$isCustomerRole) {
            $output .= '<th width="15%" style="text-align:center;">Value (Rp)</th>
                        <th width="15%" style="text-align:center;">Saldo Value (Rp)</th>';
        }

        $output .= '<th width="5%" style="text-align:center;">Status</th>
            </thead>
            <tbody>';

        // Loop setiap marking
        foreach ($initialBalanceMap as $marking => $balanceData) {
            $initialBalance = $balanceData['balance'];
            $pricePerKg = $balanceData['price_per_kg'] ?? null;

            // Abaikan jika saldo awal kosong dan tidak ada transaksi
            $hasTransactions = isset($groupedData[$marking]) && count($groupedData[$marking]) > 0;
            if (!$hasTransactions && $initialBalance == 0) {
                continue;
            }

            // Tampilkan baris saldo awal
            $initialValue = $pricePerKg ? $initialBalance * $pricePerKg : 0;
            $output .= '<tr style="background-color: #f8f9fa; font-weight: bold;">
                            <td style="text-align:center;">' . Carbon::parse($startDate)->format('d M Y') . ' (Awal)</td>
                            <td style="text-align:center;">' . $marking . '</td>
                            <td style="text-align:center;">-</td>
                            <td style="text-align:center;">-</td>
                            <td style="text-align:center;">-</td>
                            <td style="text-align:center;">' . number_format($initialBalance, 2) . '</td>';

            if (!$isCustomerRole) {
                $output .= '<td style="text-align:center;">-</td>';
                $output .= '<td style="text-align:center;"> Rp. ' . number_format($initialValue, 2) . '</td>';
            }

            $output .= '<td style="text-align:center;">SALDO AWAL</td>
                        </tr>';

            // Mulai hitung saldo berjalan dari initial balance
            $currentSaldo = $initialBalance;
            $pricePerKg = $balanceData['price_per_kg'] ?? 0;
            $currentSaldoValue = $currentSaldo * $pricePerKg;

            if (isset($groupedData[$marking])) {
            foreach ($groupedData[$marking] as $row) {
                    // Gunakan harga dari transaksi jika tersedia
                    
                    $trxValue = $row->value ?? 0;

                    if ($row->status === 'IN' || $row->status === 'IN (Retur)') {
                        $currentSaldo      += $row->in_points;
                        $currentSaldoValue += $trxValue;
                    } elseif ($row->status === 'OUT') {
                        $currentSaldo      -= $row->out_points;
                        $currentSaldoValue -= $trxValue;
                    } elseif ($row->status === 'OUT (expired)') {
                        // Kurangi saldo dalam KG juga
                        $expiredAmount = $row->out_points > 0 ? $row->out_points : ($row->expired_amount ?? 0);
                        $currentSaldo -= $expiredAmount;
                        $currentSaldoValue -= $trxValue;
                    }

                    $output .= '<tr>
                        <td style="text-align:center;">' . Carbon::parse($row->date)->format('d M Y') . '</td>
                        <td style="text-align:center;">' . $row->marking . '</td>
                        <td style="text-align:center;">' . $row->no_invoice . '</td>
                        <td style="text-align:center;">' . number_format($row->in_points, 2) . '</td>
                        <td style="text-align:center;">' . number_format($row->out_points, 2) . '</td>
                        <td style="text-align:center;">' . number_format($currentSaldo, 2) . '</td>';

                    if (!$isCustomerRole) {
                        $output .= '<td style="text-align:center;"> Rp. ' . number_format($row->value ?? 0, 2) . '</td>';
                        $output .= '<td style="text-align:center;"> Rp. ' . number_format($currentSaldoValue, 2) . '</td>';
                    }

                    $output .= '<td style="text-align:center;">' . strtoupper($row->status) . '</td>
                        </tr>';
                }

            }
        }

        $output .= '</tbody></table></div>';
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
