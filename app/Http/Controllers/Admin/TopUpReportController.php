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
        $startDate = $request->startDate
            ? Carbon::parse($request->startDate)->format('Y-m-d')
            : Carbon::create(2025, 1, 1)->format('Y-m-d');

        $endDate = $request->endDate
            ? Carbon::parse($request->endDate)->format('Y-m-d')
            : Carbon::now()->endOfMonth()->format('Y-m-d');

        $isCustomerRole = auth()->user() && auth()->user()->role === 'customer';
        $userIdCondition = $isCustomerRole ? "AND tp.user_id = ?" : "";

        // Query untuk mendapatkan saldo awal sebelum tanggal mulai
        $initialBalanceQuery = "
            SELECT
                tp.marking,
                COALESCE(SUM(
                    CASE
                        WHEN tht.status != 'canceled' THEN tht.remaining_points
                        ELSE 0
                    END
                ), 0) - COALESCE(SUM(tup.used_points), 0) AS initial_balance,
                MAX(tht.price_per_kg) AS price_per_kg
            FROM tbl_pembeli tp
            LEFT JOIN tbl_history_topup tht ON tht.customer_id = tp.id
                AND tht.date < ?
                AND tht.status != 'canceled'
            LEFT JOIN tbl_usage_points tup ON tup.customer_id = tp.id
                AND tup.usage_date < ?
            WHERE tp.company_id = ?
            " . ($customer ? "AND tp.id = ?" : "") . "
            " . $userIdCondition . "
            GROUP BY tp.marking
        ";

        $initialParams = [$startDate, $startDate, $companyId];
        if ($customer) {
            $initialParams[] = $customer;
        }
        if ($isCustomerRole) {
            $initialParams[] = auth()->user()->id;
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
            'endDate' => 'nullable|date|after_or_equal:startDate',
            'nama_pembeli' => 'nullable|exists:tbl_pembeli,id',
        ]);

        $companyId = session('active_company_id');
        $startDate = $request->input('startDate')
            ? Carbon::parse($request->input('startDate'))->format('Y-m-d')
            : Carbon::now()->startOfMonth()->format('Y-m-d');

        $endDate = $request->input('endDate')
            ? Carbon::parse($request->input('endDate'))->format('Y-m-d')
            : Carbon::now()->endOfMonth()->format('Y-m-d');
        $customerId = $request->input('nama_pembeli') ?? null;
        $isCustomerRole = auth()->user() && auth()->user()->role === 'customer';
        $userIdCondition = $isCustomerRole ? "AND tp.user_id = ?" : "";


        try {
            $customer = Customer::find($customerId);

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

        $params = [$startDate, $endDate, $companyId];
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

          // Untuk OUT expired
        $params = array_merge($params, [
            $startDate, $endDate, $companyId,
        ]);
        if ($customer) {
            $params[] = $customer;
        }

        $params = array_merge($params, [
            $startDate, $endDate, $companyId,
        ]);
        if ($customer) {
            $params[] = $customer;
        }

        $data = DB::select($query, $params);

            // Generate PDF
            $pdf = PDF::loadView('exportPDF.topupreport', [
                'marking' => $customer->marking ?? '-',
                'combined' => $data,
                'startDate' => $startDate ? Carbon::parse($startDate)->format('d M Y') : '-',
                'endDate' => $endDate ? Carbon::parse($endDate)->format('d M Y') : '-',
            ])
            ->setPaper('A4', 'portrait')
            ->setWarnings(false);

            // Simpan PDF ke Storage Private
            $fileName = 'topup_report_' . now()->format('YmdHis') . '.pdf';
            $filePath = 'public/topupreports/' . $fileName;
            Storage::put($filePath, $pdf->output());

            // Kembalikan URL yang Bisa Diakses
            return response()->json(['url' => Storage::url($filePath)]);

        } catch (\Exception $e) {
            Log::error('Error generating Topup Report PDF: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'An error occurred while generating the PDF'], 500);
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
