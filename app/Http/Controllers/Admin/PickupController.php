<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PickupController extends Controller
{
    public function index()
    {
        $listInvoice = DB::table('tbl_pengantaran_detail as a')
       ->join('tbl_invoice as b', 'b.id', '=', 'a.invoice_id')
       ->join('tbl_pengantaran as c', 'c.id', '=', 'a.pengantaran_id')
       ->join('tbl_pembeli as e', 'e.id', '=', 'b.pembeli_id')
       ->select(
           'a.invoice_id',
           'b.metode_pengiriman',
           'b.no_invoice',
           'e.marking',
           'e.nama_pembeli'
       )
       ->whereNull('a.bukti_pengantaran')
       ->whereNull('a.tanda_tangan')
       ->where('b.metode_pengiriman', 'Pickup')
       ->get();
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


    public function checkPassword(Request $request)
    {
        // Validasi input dengan pesan bahasa Indonesia
        $request->validate([
            'password' => 'required'
        ], [
            'password.required' => 'Kolom password wajib diisi.'
        ]);

        // Ambil data user yang sedang login
        $user = Auth::user();

        // Jika user tidak ditemukan (belum login)
        if (!$user) {
            return response()->json([
                'valid' => false,
                'message' => 'Pengguna tidak ditemukan. Silakan login terlebih dahulu.'
            ], 401);
        }

        // Cek apakah password cocok
        if (Hash::check($request->password, $user->password)) {
            return response()->json([
                'valid' => true,
                'message' => 'Password sesuai.'
            ]);
        } else {
            return response()->json([
                'valid' => false,
                'message' => 'Password yang Anda masukkan salah.'
            ]);
        }
    }



}
