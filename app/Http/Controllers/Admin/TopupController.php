<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\HistoryTopup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\PricePoin;

class TopupController extends Controller
{
    public function index(Request $request)
    {
        return view('topup.indextopup');
    }

    public function getPricePoints()
    {
        $prices = PricePoin::all();
        return response()->json($prices);
    }

    public function getCustomers()
    {
        // Ambil semua pengguna dari tabel tbl_pembeli
        $customers = Customer::select('id', 'nama_pembeli', 'marking')->get();

        // Kembalikan data dalam bentuk JSON
        return response()->json($customers);
    }

    public function storeTopup(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:tbl_pembeli,id',
            'topup_amount' => 'required|numeric|min:1',
            'new_price' => 'nullable|numeric|min:1',
            'effective_date' => 'nullable|date',
            'price_per_kg_id' => 'nullable|exists:tbl_price_poin,id',
        ]);

        // Ambil data pelanggan dari tbl_pembeli
        $customer = Customer::find($request->customer_id); // Ambil customer berdasarkan ID

        // Jika ada harga baru, simpan ke tbl_price_poin
        if ($request->new_price && $request->effective_date) {
            $price = PricePoin::create([
                'price_per_kg' => $request->new_price,
                'effective_date' => $request->effective_date,
            ]);
            $priceId = $price->id;
        } else {
            $priceId = $request->price_per_kg_id;
        }

        // Simpan riwayat top-up ke database dengan customer_name
        $topup = HistoryTopup::create([
            'customer_id' => $request->customer_id,
            'customer_name' => $customer->nama_pembeli, // Ambil nama dari model Customer
            'topup_amount' => $request->topup_amount,
            'remaining_points' => $request->topup_amount,
            'price_per_kg_id' => $priceId,
            'status' => 'lunas',
            'date' => now(),
            'point_status' => 'masuk',
        ]);

        return response()->json(['success' => true, 'message' => 'Top-up berhasil disimpan', 'data' => $topup]);
    }

}
