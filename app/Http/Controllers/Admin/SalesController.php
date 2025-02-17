<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SalesController extends Controller
{
    public function index()
    {
        return view('Report.Sales.indexSales');
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

}
