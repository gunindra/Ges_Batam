<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;
use Illuminate\Http\Request;
use App\Exports\SalesExport;
use Log;
use Str;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class SalesController extends Controller
{
    public function index()
    {

        $listDo = DB::table('tbl_pengantaran')
            ->join('tbl_pengantaran_detail', 'tbl_pengantaran.id', '=', 'tbl_pengantaran_detail.pengantaran_id')
            ->join('tbl_invoice', 'tbl_pengantaran_detail.invoice_id', '=', 'tbl_invoice.id')
            ->join('tbl_resi', 'tbl_resi.invoice_id', '=', 'tbl_invoice.id')
            ->join('tbl_status', 'tbl_invoice.status_id', '=', 'tbl_status.id')
            ->select('tbl_resi.no_do')
            ->distinct()
            ->get();

        $listCustomer = DB::table('tbl_pengantaran')
            ->join('tbl_pengantaran_detail', 'tbl_pengantaran.id', '=', 'tbl_pengantaran_detail.pengantaran_id')
            ->join('tbl_invoice', 'tbl_pengantaran_detail.invoice_id', '=', 'tbl_invoice.id')
            ->join('tbl_pembeli', 'tbl_invoice.pembeli_id', '=', 'tbl_pembeli.id')
            ->join('tbl_status', 'tbl_invoice.status_id', '=', 'tbl_status.id')
            ->select('tbl_pembeli.nama_pembeli')
            ->distinct()
            ->get();

        return view('Report.Sales.indexSales', [
            'listCustomer' => $listCustomer,
            'listDo' => $listDo,
        ]);
    }

    public function getListSales(Request $request)
    {

        $companyId = session('active_company_id');
        $NoDo = $request->no_do;
        $Customer = $request->nama_pembeli;

        $query = DB::table('tbl_invoice')
            ->select(
                'tbl_invoice.no_invoice',
                DB::raw("DATE_FORMAT(tbl_invoice.tanggal_buat, '%d %M %Y') AS tanggal_buat"),
                'tbl_resi.no_do',
                'tbl_pembeli.nama_pembeli AS customer',
                'tbl_invoice.metode_pengiriman',
                'tbl_status.status_name AS status_transaksi',
                'tbl_invoice.total_harga'
            )
            ->join('tbl_pembeli', 'tbl_invoice.pembeli_id', '=', 'tbl_pembeli.id')
            ->join('tbl_status', 'tbl_invoice.status_id', '=', 'tbl_status.id')
            ->join('tbl_resi', 'tbl_resi.invoice_id', '=', 'tbl_invoice.id')
            ->where('tbl_invoice.company_id', $companyId)
            ->whereIn('tbl_invoice.metode_pengiriman', ['Delivery', 'Pickup']);

        if ($Customer) {
            $query->where('tbl_pembeli.nama_pembeli', 'LIKE', '%' . $Customer . '%');
        }

        if ($NoDo) {
            $query->where('tbl_resi.no_do', 'LIKE', '%' . $NoDo . '%');
        }

        $query->orderBy('tbl_invoice.tanggal_buat', 'desc');

        $data = $query->get();

        return DataTables::of($data)
            ->editColumn('status_transaksi', function ($row) {
                $statusBadgeClass = match ($row->status_transaksi) {
                    'Dalam Perjalanan', 'Delivering' => 'badge-success',
                    'Batam / Sortir' => 'badge-primary',
                    'Ready For Pickup' => 'badge-warning',
                    default => 'badge-secondary',
                };

                return '<span class="badge ' . $statusBadgeClass . '">' . $row->status_transaksi . '</span>';
            })
            ->rawColumns(['status_transaksi'])
            ->make(true);
    }
    public function export(Request $request)
    {
        $NoDo = $request->no_do;
        $Customer = $request->nama_pembeli;

        return Excel::download(new SalesExport($NoDo, $Customer), 'Sales.xlsx');
    }
    public function exportSalesPdf(Request $request)
    {
        $NoDo = $request->no_do;
        $Customer = $request->nama_pembeli;
        $companyId = session('active_company_id');

        try {
            $query = DB::table('tbl_invoice')
                ->select(
                    'tbl_invoice.no_invoice',
                    DB::raw("DATE_FORMAT(tbl_invoice.tanggal_buat, '%d %M %Y') AS tanggal_buat"),
                    'tbl_resi.no_do',
                    'tbl_pembeli.nama_pembeli AS customer',
                    'tbl_invoice.metode_pengiriman',
                    'tbl_status.status_name AS status_transaksi',
                    'tbl_invoice.total_harga'
                )
                ->join('tbl_pembeli', 'tbl_invoice.pembeli_id', '=', 'tbl_pembeli.id')
                ->join('tbl_status', 'tbl_invoice.status_id', '=', 'tbl_status.id')
                ->join('tbl_resi', 'tbl_resi.invoice_id', '=', 'tbl_invoice.id')
                ->where('tbl_invoice.company_id', $companyId)
                ->whereIn('tbl_invoice.metode_pengiriman', ['Delivery', 'Pickup']);

            if ($Customer) {
                $query->where('tbl_pembeli.nama_pembeli', 'LIKE', '%' . $Customer . '%');
            }

            if ($NoDo) {
                $query->where('tbl_resi.no_do', 'LIKE', '%' . $NoDo . '%');
            }

            $query->orderBy('tbl_invoice.tanggal_buat', 'desc');

            $salesdata = $query->get();

            if ($salesdata->isEmpty()) {
                return response()->json(['error' => 'sales invoices found'], 404);
            }

            try {
                $pdf = Pdf::loadView('exportPDF.salesPdf', [
                    'salesdata' => $salesdata,
                    'NoDo' => $NoDo,
                    'Customer' => $Customer,
                ])
                    ->setPaper('A4', 'portrait')
                    ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                    ->setWarnings(false);
            } catch (\Exception $e) {
                Log::error('Error generating sales PDF: ' . $e->getMessage(), ['exception' => $e]);
                return response()->json(['error' => 'Failed to generate PDF'], 500);
            }
            try {
                $folderPath = storage_path('app/public/sales');

                // Cek apakah folder sudah ada, jika belum maka buat folder
                if (!file_exists($folderPath)) {
                    mkdir($folderPath, 0777, true); // 0777 memberikan izin penuh untuk folder dan subfolder
                }

                $fileName = 'sales_' . (string) Str::uuid() . '.pdf';
                $filePath = $folderPath . '/' . $fileName;

                // Save the PDF
                $pdf->save($filePath);
            } catch (\Exception $e) {
                Log::error('Error saving PDF: ' . $e->getMessage(), ['exception' => $e]);
                return response()->json(['error' => 'Failed to save PDF'], 500);
            }
            // Return the URL of the saved PDF
            $url = asset('storage/sales/' . $fileName);
            return response()->json(['url' => $url]);

        } catch (\Exception $e) {
            Log::error('Error generating sales invoice PDF: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'An error occurred while generating the sales invoice PDF'], 500);
        }
    }

}
