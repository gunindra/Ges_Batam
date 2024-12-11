<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use DB;
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
        $request->validate([
            'password' => 'required',
            'username' => 'required',
        ], [
            'password.required' => 'Kolom password wajib diisi.',
            'username.required' => 'Kolom username wajib diisi.',
        ]);

        // Cek pengguna berdasarkan username
        $user = User::where('name', $request->username)
                    ->whereIn('role', ['superadmin', 'admin'])
                    ->first();

        if (!$user) {
            return response()->json([
                'valid' => false,
                'message' => 'Username tidak ditemukan atau role tidak valid.',
            ], 404);
        }

        if (Hash::check($request->password, $user->password)) {
            return response()->json([
                'valid' => true,
                'message' => 'Password sesuai.',
            ]);
        } else {
            return response()->json([
                'valid' => false,
                'message' => 'Password yang Anda masukkan salah.',
            ]);
        }
    }




}
