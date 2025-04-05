<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;
use Illuminate\Http\Request;
use App\Exports\SalesExport;
use Illuminate\Support\Carbon;
use Log;
use Str;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class SalesController extends Controller
{
    public function index()
    {

        $companyId = session('active_company_id');

        $listDo = DB::table('tbl_invoice')
            ->join('tbl_resi', 'tbl_resi.invoice_id', '=', 'tbl_invoice.id')
            ->where('tbl_invoice.company_id', $companyId)
            ->select('tbl_resi.no_do')
            ->distinct()
            ->get();

        $listCustomer = DB::table('tbl_pembeli')
            ->where('tbl_pembeli.company_id', $companyId)
            ->select('tbl_pembeli.marking')
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
            'tbl_resi.no_resi',
            'tbl_pembeli.nama_pembeli AS customer',
            'tbl_invoice.metode_pengiriman',
            'tbl_status.status_name AS status_transaksi',
            DB::raw("CEIL(tbl_resi.harga / 1000) * 1000 AS total_harga"),
            'tbl_pembeli.marking',
            DB::raw("IFNULL(
                IF(tbl_resi.berat IS NOT NULL,
                    CONCAT(tbl_resi.berat, ' Kg'),
                    CONCAT(tbl_resi.panjang * tbl_resi.lebar * tbl_resi.tinggi / 1000000, ' m³')
                ), '') AS berat_volume"),
            DB::raw("SUM(CEIL(tbl_resi.harga / 1000) * 1000) OVER () AS total_sum")
        )
        ->join('tbl_pembeli', 'tbl_invoice.pembeli_id', '=', 'tbl_pembeli.id')
        ->join('tbl_status', 'tbl_invoice.status_id', '=', 'tbl_status.id')
        ->join('tbl_resi', 'tbl_resi.invoice_id', '=', 'tbl_invoice.id')
        ->where('tbl_invoice.company_id', $companyId)
        ->whereIn('tbl_invoice.metode_pengiriman', ['Delivery', 'Pickup'])
        ->groupBy(
            'tbl_invoice.no_invoice',
            'tbl_invoice.tanggal_buat',
            'tbl_resi.no_do',
            'tbl_resi.no_resi',
            'tbl_pembeli.nama_pembeli',
            'tbl_invoice.metode_pengiriman',
            'tbl_status.status_name',
            'tbl_pembeli.marking',
            'tbl_resi.harga',
            'tbl_resi.berat',
            'tbl_resi.panjang',
            'tbl_resi.lebar',
            'tbl_resi.tinggi'
        );



        if ($Customer) {
            $query->where('tbl_pembeli.marking', 'LIKE', '%' . $Customer . '%');
        }

        if ($NoDo) {
            $query->where('tbl_resi.no_do', 'LIKE', '%' . $NoDo . '%');
        }

        if ($request->startDate && $request->endDate) {
            $startDate = Carbon::createFromFormat('d M Y', $request->startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('d M Y', $request->endDate)->endOfDay();
            $query->whereBetween('tbl_invoice.tanggal_buat', [$startDate, $endDate]);
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

        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $NoDo = $request->input('no_do');
        $Customer = $request->input('nama_pembeli');

        return Excel::download(new SalesExport($NoDo, $Customer, $startDate, $endDate), 'Sales.xlsx');
    }


    public function exportSalesPdf(Request $request)
    {
        $NoDo = $request->no_do === "null" ? null : trim($request->no_do);
        $Customer = $request->nama_pembeli === "null" ? null : trim($request->nama_pembeli);
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $companyId = session('active_company_id');

        try {
            $query = DB::table('tbl_invoice')
                ->select(
                    'tbl_invoice.no_invoice',
                    DB::raw("DATE_FORMAT(tbl_invoice.tanggal_buat, '%d %M %Y') AS tanggal_buat"),
                    'tbl_resi.no_do',
                    'tbl_resi.no_resi',
                    'tbl_pembeli.nama_pembeli AS customer',
                    'tbl_invoice.metode_pengiriman',
                    'tbl_status.status_name AS status_transaksi',
                    DB::raw("CEIL(tbl_resi.harga / 1000) * 1000 AS total_harga"),
                    'tbl_pembeli.marking',
                    DB::raw("IFNULL(
                        IF(tbl_resi.berat IS NOT NULL,
                            CONCAT(tbl_resi.berat, ' Kg'),
                            CONCAT(tbl_resi.panjang * tbl_resi.lebar * tbl_resi.tinggi / 1000000, ' m³')
                        ), '') AS berat_volume")
                )
                ->join('tbl_pembeli', 'tbl_invoice.pembeli_id', '=', 'tbl_pembeli.id')
                ->join('tbl_status', 'tbl_invoice.status_id', '=', 'tbl_status.id')
                ->join('tbl_resi', 'tbl_resi.invoice_id', '=', 'tbl_invoice.id')
                ->where('tbl_invoice.company_id', $companyId)
                ->whereIn('tbl_invoice.metode_pengiriman', ['Delivery', 'Pickup'])
                ->when($Customer, fn($q) => $q->where('tbl_pembeli.marking', 'LIKE', '%' . $Customer . '%'))
                ->when($NoDo, fn($q) => $q->where('tbl_resi.no_do', 'LIKE', '%' . $NoDo . '%'))
                ->groupBy(
                    'tbl_invoice.no_invoice',
                    'tbl_invoice.tanggal_buat',
                    'tbl_resi.no_do',
                    'tbl_resi.no_resi',
                    'tbl_pembeli.nama_pembeli',
                    'tbl_invoice.metode_pengiriman',
                    'tbl_status.status_name',
                    'tbl_pembeli.marking',
                    'tbl_resi.harga',
                    'tbl_resi.berat',
                    'tbl_resi.panjang',
                    'tbl_resi.lebar',
                    'tbl_resi.tinggi'
                )
                ->orderBy('tbl_invoice.tanggal_buat', 'desc');

                if ($request->startDate && $request->endDate) {
                    $startDateCarbon = Carbon::createFromFormat('d M Y', $request->startDate)->startOfDay();
                    $endDateCarbon = Carbon::createFromFormat('d M Y', $request->endDate)->endOfDay();
                    $query->whereBetween('tbl_invoice.tanggal_buat', [$startDateCarbon, $endDateCarbon]);

                    $startDate = $startDateCarbon->format('d F Y');
                    $endDate = $endDateCarbon->format('d F Y');
                } else {
                    $startDate = '-';
                    $endDate = '-';
                }

            $salesdata = $query->get();

            if ($salesdata->isEmpty()) {
                return response()->json(['error' => 'Sales invoices not found'], 404);
            }

            $journalQuery = DB::table('tbl_jurnal_items AS ji')
                            ->select(
                                'ji.id AS items_id',
                                'ji.jurnal_id AS jurnal_id',
                                'ji.code_account AS account_id',
                                'ji.debit AS debit',
                                'ji.credit AS credit',
                                'ji.description AS items_description',
                                'ji.memo AS memo',
                                'ju.tanggal AS tanggal',
                                'ju.tanggal_payment AS tanggal_payment',
                                'ju.no_journal AS no_journal',
                                'pem_inv.marking AS pembeli_invoice',
                                'pem_pay.marking AS pembeli_payment'
                            )
                            ->leftJoin('tbl_jurnal AS ju', 'ju.id', '=', 'ji.jurnal_id')
                            ->leftJoin('tbl_invoice AS inv', 'ju.invoice_id', '=', 'inv.id')
                            ->leftJoin('tbl_payment_customer AS pc', 'ju.payment_id', '=', 'pc.id')
                            ->leftJoin('tbl_pembeli AS pem_inv', 'inv.pembeli_id', '=', 'pem_inv.id')
                            ->leftJoin('tbl_pembeli AS pem_pay', 'pc.pembeli_id', '=', 'pem_pay.id')
                            ->where('ji.code_account', 84)
                            ->orderBy('ju.tanggal', 'ASC');

            if ($request->startDate && $request->endDate) {
                $startDateCarbon = Carbon::createFromFormat('d M Y', $request->startDate)->startOfDay();
                $endDateCarbon = Carbon::createFromFormat('d M Y', $request->endDate)->endOfDay();

                $journalQuery->whereBetween('ju.tanggal', [$startDateCarbon, $endDateCarbon]);

                $startDate = $startDateCarbon->format('d F Y');
                $endDate = $endDateCarbon->format('d F Y');
            } else {
                $startDate = '-';
                $endDate = '-';
            }

            $journalResults = $journalQuery->get();

            $journalTotal = $journalResults->sum('credit') - $journalResults->sum('debit');

            $pdf = Pdf::loadView('exportPDF.salesPdf', [
                'salesdata' => $salesdata,
                'NoDo' => $NoDo,
                'Customer' => $Customer,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'journalTotal' => $journalTotal
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
