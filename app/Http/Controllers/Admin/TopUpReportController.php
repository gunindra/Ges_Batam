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

        $customers = Customer::where('status', '=', 1)->get();
        return view('Report.TopUpReport.indextopupreport', compact('customers'));
    }

    public function getTopUpReport(Request $request)
    {
        $companyId = session('active_company_id');
        $customer = $request->customer;
        $startDate = $request->startDate
            ? Carbon::parse($request->startDate)->format('Y-m-d')
            : Carbon::now()->startOfMonth()->format('Y-m-d');

        $endDate = $request->endDate
            ? Carbon::parse($request->endDate)->format('Y-m-d')
            : Carbon::now()->endOfMonth()->format('Y-m-d');

        $isCustomerRole = auth()->user() && auth()->user()->role === 'customer';
        $userIdCondition = $isCustomerRole ? "AND tp.user_id = ?" : "";

        $query = "
            WITH combined_data AS (
                SELECT
                    date,
                    marking,
                    CASE WHEN type = 'IN' THEN points ELSE 0 END AS in_points,
                    CASE WHEN type = 'OUT' THEN points ELSE 0 END AS out_points,
                    points * price_per_kg AS value,
                    type AS status
                FROM (
                    SELECT
                        tup.usage_date AS date,
                        tp.marking,
                        tup.used_points AS points,
                        tup.price_per_kg,
                        'OUT' AS type
                    FROM tbl_usage_points tup
                    JOIN tbl_pembeli tp ON tup.customer_id = tp.id
                    WHERE tup.usage_date BETWEEN ? AND ?
                    AND tp.company_id = ?
                    " . ($customer ? "AND tp.id = ?" : "") . "
                    " . $userIdCondition . "

                    UNION ALL

                    SELECT
                        tht.date,
                        tp.marking,
                        tht.remaining_points AS points,
                        tht.price_per_kg,
                        'IN' AS type
                    FROM tbl_history_topup tht
                    JOIN tbl_pembeli tp ON tht.customer_id = tp.id
                    WHERE tht.status != 'canceled'
                    AND tht.date BETWEEN ? AND ?
                    AND tp.company_id = ?
                    " . ($customer ? "AND tp.id = ?" : "") . "
                    " . $userIdCondition . "
                ) AS raw_data
            ),
            calculated_data AS (
                SELECT
                    date,
                    marking,
                    in_points,
                    out_points,
                    value,
                    status,
                    SUM(in_points - out_points) OVER (PARTITION BY marking ORDER BY date, in_points DESC) AS saldo
                FROM combined_data
            )
            SELECT
                date,
                marking,
                in_points,
                out_points,
                saldo,
                value,
                status
            FROM calculated_data
            ORDER BY marking, date;
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

        $data = DB::select($query, $params);
        $output = '
            <h5 style="text-align:center; width:100%">'
                . Carbon::parse($startDate)->format('d M Y') . ' - '
                . Carbon::parse($endDate)->format('d M Y') .
            '</h5>
            <div class="card-body">
            <table class="table" width="100%">
            <thead>
                <th width="15%" style="text-align:center;">Date</th>
                <th width="25%" style="text-align:center;">Marking</th>
                <th width="10%" style="text-align:center;">In (Kg)</th>
                <th width="10%" style="text-align:center;">Out (Kg)</th>
                <th width="15%" style="text-align:center;">Saldo (Kg)</th>';

        if (!$isCustomerRole) {
            $output .= '<th width="20%" style="text-align:center;">Value (Rp)</th>';
        }

        $output .= '<th width="10%" style="text-align:center;">Status</th>
            </thead>
            <tbody>';

        foreach ($data as $row) {
            $output .= '<tr>
                <td style="text-align:center;">' . Carbon::parse($row->date)->format('d M Y') . '</td>
                <td style="text-align:center;">' . $row->marking . '</td>
                <td style="text-align:center;">' . number_format($row->in_points, 2) . '</td>
                <td style="text-align:center;">' . number_format($row->out_points, 2) . '</td>
                <td style="text-align:center;">' . number_format($row->saldo, 2) . '</td>';

            if (!$isCustomerRole) {
                $output .= '<td style="text-align:center;"> Rp. ' . number_format($row->value, 2) . '</td>';
            }

            $output .= '<td style="text-align:center;">' . strtoupper($row->status) . '</td>
            </tr>';
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
                    marking,
                    CASE WHEN type = 'IN' THEN points ELSE 0 END AS in_points,
                    CASE WHEN type = 'OUT' THEN points ELSE 0 END AS out_points,
                    points * price_per_kg AS value,
                    type AS status
                FROM (
                    SELECT
                        tup.usage_date AS date,
                        tp.marking,
                        tup.used_points AS points,
                        tup.price_per_kg,
                        'OUT' AS type
                    FROM tbl_usage_points tup
                    JOIN tbl_pembeli tp ON tup.customer_id = tp.id
                    WHERE tup.usage_date BETWEEN ? AND ?
                    AND tp.company_id = ?
                    " . ($customer ? "AND tp.id = ?" : "") . "
                    " . $userIdCondition . "

                    UNION ALL

                    SELECT
                        tht.date,
                        tp.marking,
                        tht.remaining_points AS points,
                        tht.price_per_kg,
                        'IN' AS type
                    FROM tbl_history_topup tht
                    JOIN tbl_pembeli tp ON tht.customer_id = tp.id
                    WHERE tht.status != 'canceled'
                    AND tht.date BETWEEN ? AND ?
                    AND tp.company_id = ?
                    " . ($customer ? "AND tp.id = ?" : "") . "
                    " . $userIdCondition . "
                ) AS raw_data
            ),
            calculated_data AS (
                SELECT
                    date,
                    marking,
                    in_points,
                    out_points,
                    value,
                    status,
                    SUM(in_points - out_points) OVER (PARTITION BY marking ORDER BY date, in_points DESC) AS saldo
                FROM combined_data
            )
            SELECT
                date,
                marking,
                in_points,
                out_points,
                saldo,
                value,
                status
            FROM calculated_data
            ORDER BY marking, date;
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
