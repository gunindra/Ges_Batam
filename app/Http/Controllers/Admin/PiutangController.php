<?php

namespace App\Http\Controllers\Admin;
use App\Exports\PiutangReportExport;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;
use Str;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class PiutangController extends Controller
{
    public function index()
    {
        $companyId = session('active_company_id');
        $customers = DB::table('tbl_pembeli as pembeli')
            ->select(
                'pembeli.id',
                'pembeli.nama_pembeli',
                'pembeli.marking',
                DB::raw("MAX(invoice_data.tanggal_buat) AS tanggal_buat"),
                DB::raw("
                    CASE
                        WHEN MAX(CASE WHEN invoice_data.bell_priority = 1 THEN 1 ELSE 0 END) = 1 THEN 'red'
                        WHEN MAX(CASE WHEN invoice_data.bell_priority = 2 THEN 1 ELSE 0 END) = 1 THEN 'yellow'
                        ELSE 'green'
                    END AS bell_color
                ")
            )
            ->leftJoinSub(
                DB::table('tbl_invoice as invoice')
                    ->select(
                        'invoice.pembeli_id',
                        'invoice.tanggal_buat',
                        DB::raw("
                            CASE
                                WHEN DATEDIFF(CURDATE(), invoice.tanggal_buat) >= 60 THEN 1 -- red priority
                                WHEN DATEDIFF(CURDATE(), invoice.tanggal_buat) >= 30 THEN 2 -- yellow priority
                                ELSE 3 -- green priority
                            END AS bell_priority
                        ")
                    )
                    ->where('invoice.status_bayar', 'Belum lunas'),
                'invoice_data',
                'pembeli.id',
                '=',
                'invoice_data.pembeli_id'
            )
            ->where('pembeli.company_id', $companyId)
            ->where('pembeli.status', '=', 1)
            ->groupBy('pembeli.id', 'pembeli.nama_pembeli', 'pembeli.marking')
            ->get();

        return view('Report.Piutang.indexpiutang', [
            'customers' => $customers,
        ]);

    }
    public function getpiutang(Request $request)
    {
        $companyId = session('active_company_id');
        $query = DB::table('tbl_invoice as invoice')
            ->select(
                'invoice.id',
                'invoice.no_invoice',
                DB::raw("DATE_FORMAT(invoice.tanggal_buat, '%d %M %Y') AS tanggal_buat"),
                DB::raw("
                CASE
                    WHEN DATEDIFF(CURDATE(), invoice.tanggal_buat) >= 60 THEN 'red'
                    WHEN DATEDIFF(CURDATE(), invoice.tanggal_buat) >= 30 THEN 'yellow'
                    ELSE 'green'
                END AS bell_color
            "),
                'pembeli.nama_pembeli',
                DB::raw("
                CASE
                    WHEN CURDATE() < invoice.tanggal_buat THEN '-'
                    WHEN TIMESTAMPDIFF(YEAR, invoice.tanggal_buat, CURDATE()) > 0 THEN
                        CONCAT(
                            TIMESTAMPDIFF(YEAR, invoice.tanggal_buat, CURDATE()), ' tahun ',
                            MOD(DATEDIFF(CURDATE(), invoice.tanggal_buat), 365), ' hari'
                        )
                    WHEN TIMESTAMPDIFF(MONTH, invoice.tanggal_buat, CURDATE()) > 0 THEN
                        CONCAT(
                            TIMESTAMPDIFF(MONTH, invoice.tanggal_buat, CURDATE()), ' bulan ',
                            MOD(DATEDIFF(CURDATE(), invoice.tanggal_buat), 30), ' hari'
                        )
                    ELSE
                        CONCAT(DATEDIFF(CURDATE(), invoice.tanggal_buat), ' hari')
                END AS umur
            ")
            )
            ->where('invoice.company_id', $companyId)
            ->join('tbl_pembeli as pembeli', 'invoice.pembeli_id', '=', 'pembeli.id')
            ->where('invoice.status_bayar', '=', 'Belum lunas');
            

        $query->orderBy('invoice.tanggal_invoice', 'desc');

        if ($request->customer) {
            $query->where('pembeli.id', '=', $request->customer);
        }

        if ($request->startDate && $request->endDate) {
            $startDate = Carbon::createFromFormat('d M Y', $request->startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('d M Y', $request->endDate)->endOfDay();
            $query->whereBetween('invoice.tanggal_buat', [$startDate, $endDate]);
        }

        $data = $query->get();
        return DataTables::of($data)
            ->make(true);
    }
    public function exportPiutangReport(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $customer = $request->nama_pembeli ?? '-';

        return Excel::download(new PiutangReportExport($customer, $startDate, $endDate), 'Piutang.xlsx');
    }
    public function exportPiutangPdf(Request $request)
    {
        $companyId = session('active_company_id');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $customer = $request->nama_pembeli ?? '-';

        try {
            $query = DB::table('tbl_invoice as invoice')->select(
                'invoice.id',
                'invoice.no_invoice',
                DB::raw("DATE_FORMAT(invoice.tanggal_buat, '%d %M %Y') AS tanggal_buat"),
                'pembeli.nama_pembeli',
                DB::raw("CASE WHEN CURDATE() < invoice.tanggal_buat THEN '-'
                    WHEN TIMESTAMPDIFF(YEAR, invoice.tanggal_buat, CURDATE()) > 0 THEN
                        CONCAT(
                            TIMESTAMPDIFF(YEAR, invoice.tanggal_buat, CURDATE()), ' tahun ',
                            MOD(DATEDIFF(CURDATE(), invoice.tanggal_buat), 365), ' hari'
                        )
                    WHEN TIMESTAMPDIFF(MONTH, invoice.tanggal_buat, CURDATE()) > 0 THEN
                        CONCAT(
                            TIMESTAMPDIFF(MONTH, invoice.tanggal_buat, CURDATE()), ' bulan ',
                            MOD(DATEDIFF(CURDATE(), invoice.tanggal_buat), 30), ' hari'
                        )
                    ELSE
                        CONCAT(DATEDIFF(CURDATE(), invoice.tanggal_buat), ' hari')
                END AS umur
            ")
            )
            ->where('invoice.company_id', $companyId)
                ->where('invoice.status_bayar', '=', 'Belum lunas')
                ->join('tbl_pembeli as pembeli', 'invoice.pembeli_id', '=', 'pembeli.id');

            $query->orderBy('invoice.tanggal_invoice', 'desc');

            if ($customer !== '-') {
                $query->where('pembeli.id', '=', $customer);
            }

            if ($request->startDate && $request->endDate) {
                $startDateCarbon = Carbon::createFromFormat('d M Y', $request->startDate)->startOfDay();
                $endDateCarbon = Carbon::createFromFormat('d M Y', $request->endDate)->endOfDay();
                $query->whereBetween('invoice.tanggal_buat', [$startDateCarbon, $endDateCarbon]);

                $startDate = $startDateCarbon->format('d F Y');
                $endDate = $endDateCarbon->format('d F Y');
            } else {
                $startDate = '-';
                $endDate = '-';
            }

            $piutang = $query->get();

            $customerName = '-';
            if ($customer !== '-') {
                $customerData = DB::table('tbl_pembeli')->where('id', $customer)->first();
                $customerName = $customerData ? $customerData->nama_pembeli : 'Unknown';
            }

            if ($piutang->isEmpty()) {
                return response()->json(['error' => 'No Piutang report found'], 404);
            }

            try {
                $pdf = pdf::loadView('exportPDF.piutang', [
                    'piutang' => $piutang,
                    'customer' => $customerName,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                ])
                    ->setPaper('A4', 'portrait')
                    ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                    ->setWarnings(false);
            } catch (\Exception $e) {
                Log::error('Error generating piutang invoice PDF: ' . $e->getMessage(), ['exception' => $e]);
                return response()->json(['error' => 'Failed to generate PDF'], 500);
            }
            try {
                $folderPath = storage_path('app/public/piutang');

                if (!file_exists($folderPath)) {
                    mkdir($folderPath, 0777, true);
                }

                $fileName = 'piutang_report' . (string) Str::uuid() . '.pdf';
                $filePath = $folderPath . '/' . $fileName;

                $pdf->save($filePath);
            } catch (\Exception $e) {
                Log::error('Error saving PDF: ' . $e->getMessage(), ['exception' => $e]);
                return response()->json(['error' => 'Failed to save PDF'], 500);
            }

            $url = asset('storage/piutang/' . $fileName);
            return response()->json(['url' => $url]);

        } catch (\Exception $e) {
            Log::error('Error generating piutang report PDF: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'An error occurred while generating the piutang report PDF'], 500);
    }
}
}
