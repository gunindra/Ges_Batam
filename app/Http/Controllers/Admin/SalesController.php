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
            DB::raw("MIN(tbl_resi.no_do) AS no_do"), // Ambil no_do pertama
            'tbl_resi.no_resi',
            'tbl_pembeli.nama_pembeli AS customer',
            'tbl_invoice.metode_pengiriman',
            'tbl_status.status_name AS status_transaksi',
            DB::raw("SUM(tbl_resi.harga) AS total_harga"), // Ambil total dari tbl_resi.harga
            'tbl_pembeli.marking',
            DB::raw("IFNULL(
                IF(MIN(tbl_resi.berat) IS NOT NULL,
                    CONCAT(MIN(tbl_resi.berat), ' Kg'),
                    CONCAT(MIN(tbl_resi.panjang) * MIN(tbl_resi.lebar) * MIN(tbl_resi.tinggi) / 1000000, ' m³')
                ), '') AS berat_volume"),
            DB::raw("SUM(SUM(tbl_resi.harga)) OVER () AS total_sum") // Total keseluruhan dari tbl_resi.harga
        )
        ->join('tbl_pembeli', 'tbl_invoice.pembeli_id', '=', 'tbl_pembeli.id')
        ->join('tbl_status', 'tbl_invoice.status_id', '=', 'tbl_status.id')
        ->join('tbl_resi', 'tbl_resi.invoice_id', '=', 'tbl_invoice.id')
        ->where('tbl_invoice.company_id', $companyId)
        ->whereIn('tbl_invoice.metode_pengiriman', ['Delivery', 'Pickup'])
        ->groupBy(
            'tbl_invoice.no_invoice',
            'tbl_invoice.tanggal_buat',
            'tbl_resi.no_resi',
            'tbl_pembeli.nama_pembeli',
            'tbl_invoice.metode_pengiriman',
            'tbl_status.status_name',
            'tbl_pembeli.marking'
        );


        if ($Customer) {
            $query->where('tbl_pembeli.nama_pembeli', 'LIKE', '%' . $Customer . '%');
        }

        if ($NoDo) {
            $query->where('tbl_resi.no_do', 'LIKE', '%' . $NoDo . '%');
        }

        $query->groupBy(
                'tbl_invoice.no_invoice',
                'tbl_invoice.tanggal_buat',
                'tbl_pembeli.nama_pembeli',
                'tbl_invoice.metode_pengiriman',
                'tbl_status.status_name',
                'tbl_invoice.total_harga',
                'tbl_pembeli.marking',
                'tbl_resi.no_resi'
            )
            ->orderBy('tbl_invoice.tanggal_buat', 'desc');

        $data = $query->get();

        // Ambil total sum dari salah satu baris karena sudah ada di dalam query
        $totalSum = $data->isNotEmpty() ? $data->first()->total_sum : 0;

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
            ->with('total_sum', $totalSum) // Kirim total sum ke frontend
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
        $NoDo = $request->no_do === "null" ? null : trim($request->no_do);
        $Customer = $request->nama_pembeli === "null" ? null : trim($request->nama_pembeli);
        $companyId = session('active_company_id');

        try {
            $query = DB::table('tbl_invoice')
                ->select(
                    'tbl_invoice.no_invoice',
                    DB::raw("DATE_FORMAT(tbl_invoice.tanggal_buat, '%d %M %Y') AS tanggal_buat"),
                    DB::raw("MIN(tbl_resi.no_do) AS no_do"),
                    'tbl_resi.no_resi',
                    'tbl_pembeli.nama_pembeli AS customer',
                    'tbl_invoice.metode_pengiriman',
                    'tbl_status.status_name AS status_transaksi',
                    'tbl_invoice.total_harga',
                    'tbl_pembeli.marking',
                    DB::raw("GROUP_CONCAT(tbl_resi.harga SEPARATOR '; ') AS harga_resi"),
                    DB::raw("IFNULL(
                        IF(MIN(tbl_resi.berat) IS NOT NULL,
                            CONCAT(MIN(tbl_resi.berat), ' Kg'),
                            CONCAT(MIN(tbl_resi.panjang) * MIN(tbl_resi.lebar) * MIN(tbl_resi.tinggi) / 1000000, ' m³')
                        ), '') AS berat_volume")
                )
                ->join('tbl_pembeli', 'tbl_invoice.pembeli_id', '=', 'tbl_pembeli.id')
                ->join('tbl_status', 'tbl_invoice.status_id', '=', 'tbl_status.id')
                ->join('tbl_resi', 'tbl_resi.invoice_id', '=', 'tbl_invoice.id')
                ->where('tbl_invoice.company_id', $companyId)
                ->whereIn('tbl_invoice.metode_pengiriman', ['Delivery', 'Pickup'])
                ->when(!empty($Customer), function ($q) use ($Customer) {
                    return $q->where('tbl_pembeli.nama_pembeli', 'LIKE', '%' . $Customer . '%');
                })
                ->when(!empty($NoDo), function ($q) use ($NoDo) {
                    return $q->where('tbl_resi.no_do', 'LIKE', '%' . $NoDo . '%');
                })
                ->groupBy(
                    'tbl_invoice.id',
                    'tbl_invoice.no_invoice',
                    'tbl_invoice.tanggal_buat',
                    'tbl_pembeli.nama_pembeli',
                    'tbl_invoice.metode_pengiriman',
                    'tbl_status.status_name',
                    'tbl_invoice.total_harga',
                    'tbl_pembeli.marking',
                      'tbl_resi.no_resi'
                )
                ->orderBy('tbl_invoice.tanggal_buat', 'desc');

            $salesdata = $query->get();

            if ($salesdata->isEmpty()) {
                return response()->json(['error' => 'Sales invoices not found'], 404);
            }

            $pdf = Pdf::loadView('exportPDF.salesPdf', [
                'salesdata' => $salesdata,
                'NoDo' => $NoDo,
                'Customer' => $Customer,
            ])
                ->setPaper('A4', 'portrait')
                ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                ->setWarnings(false);

            return $pdf->stream('sales_invoice.pdf');

        } catch (\Exception $e) {
            Log::error('Error generating sales invoice PDF: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'An error occurred while generating the sales invoice PDF'], 500);
        }
    }



}
