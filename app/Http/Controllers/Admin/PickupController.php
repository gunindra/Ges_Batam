<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PickupController extends Controller
{
    public function index()
    {
        $listInvoice = DB::table('tbl_pengantaran_detail as a')
            ->join('tbl_invoice as b', 'b.id', '=', 'a.invoice_id')
            ->join('tbl_pengantaran as c', 'c.id', '=', 'a.pengantaran_id')
            ->join('tbl_supir as d', 'd.id', '=', 'c.supir_id')
            ->join('tbl_pembeli as e', 'e.id', '=', 'b.pembeli_id')
            ->select(
                'a.invoice_id',
                'b.metode_pengiriman',
                'b.no_invoice',
                'd.nama_supir',
                'e.marking',
                'e.nama_pembeli'
            )
            ->whereNull('a.bukti_pengantaran')
            ->whereNull('a.tanda_tangan')
            ->where('b.metode_pengiriman', 'Pickup')
            ->get();

        dd($listInvoice); 
        return view('pickup.indexpickup', [
            'listInvoice' => $listInvoice
        ]);
    }

    public function jumlahresipickup(Request $request)
    {
        $invoiceIds = $request->input('invoice_ids');
        $count = DB::table('tbl_resi')
            ->whereIn('invoice_id', $invoiceIds)
            ->count();
        return response()->json(['count' => $count]);
    }


}
