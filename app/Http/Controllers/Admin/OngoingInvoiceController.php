<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;


class OngoingInvoiceController extends Controller
{
    public function index()
    {
        return view('Report.OngoingInvoice.indexongoinginvoice');
    }


    public function getlistOngoing(Request $request)
    {


        $query = DB::table('tbl_pengantaran')
            ->select(
                'tbl_invoice.no_invoice',
                'tbl_resi.no_do',
                'tbl_supir.nama_supir',
                DB::raw("DATE_FORMAT(tbl_pengantaran.tanggal_pengantaran, '%d %M %Y') AS tanggal_pengantaran"),
                DB::raw("DATE_FORMAT(tbl_invoice.tanggal_buat, '%d %M %Y') AS tanggal_buat"),
                'tbl_invoice.alamat',
                'tbl_pembeli.nama_pembeli AS nama_pembeli',
                'tbl_status.status_name AS status_transaksi'
            )
            ->leftJoin('tbl_supir', 'tbl_pengantaran.supir_id', '=', 'tbl_supir.id')
            ->join('tbl_pengantaran_detail', 'tbl_pengantaran.id', '=', 'tbl_pengantaran_detail.pengantaran_id')
            ->join('tbl_invoice', 'tbl_pengantaran_detail.invoice_id', '=', 'tbl_invoice.id')
            ->join('tbl_pembeli', 'tbl_invoice.pembeli_id', '=', 'tbl_pembeli.id')
            ->join('tbl_status', 'tbl_invoice.status_id', '=', 'tbl_status.id')
            ->join('tbl_resi', 'tbl_resi.invoice_id', '=', 'tbl_invoice.id')
            ->whereIn('tbl_status.id', [1, 4]);

        if ($request->status) {
            $query->where('tbl_status.status', $request->status);
        }

        $query->orderBy('tbl_pengantaran.id', 'desc');

        $data = $query->get();

        return DataTables::of($data)
            ->editColumn('status_transaksi', function ($row) {
                $statusBadgeClass = '';
                switch ($row->status_transaksi) {
                    case 'Dalam Perjalanan':
                        $statusBadgeClass = 'badge-success';
                        break;
                    case 'Batam / Sortir':
                        $statusBadgeClass = 'badge-primary';
                        break;
                    case 'Delivering':
                        $statusBadgeClass = 'badge-success';
                        break;
                    case 'Ready For Pickup':
                        $statusBadgeClass = 'badge-warning';
                        break;
                    default:
                        $statusBadgeClass = 'badge-secondary';
                        break;
                }

                return '<span class="badge ' . $statusBadgeClass . '">' . $row->status_transaksi . '</span>';
            })
            // ->addColumn('action', function ($row) {
            //     return '<a href="#" class="btn btnUpdateTracking btn-sm btn-secondary" data-id="' . $row->no_invoice . '"><i class="fas fa-edit"></i></a>' .
            //         '<a href="#" class="btn btnDestroyTracking btn-sm btn-danger ml-2" data-id="' . $row->no_invoice . '"><i class="fas fa-trash"></i></a>';
            // })
            ->rawColumns(['status_transaksi', 'action'])
            ->make(true);
    }

}
