<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\COA;
use App\Models\Customer;
use App\Models\HistoryTopup;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\PricePoin;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TopupController extends Controller
{
    public function index(Request $request)
    {
        $coas = COA::all();

        return view('topup.indextopup', [
            'coas' => $coas,
        ]);
    }

    public function getPricePoints()
    {
        $prices = PricePoin::all();
        return response()->json($prices);
    }

    public function getCustomers()
    {
        $customers = Customer::select('id', 'nama_pembeli', 'marking')->get();
        return response()->json($customers);
    }

    public function getData(Request $request)
    {
        $query = HistoryTopup::with(['customer', 'pricePerKg', 'account'])
                    ->select(['customer_id', 'customer_name', 'topup_amount', 'price_per_kg_id', 'account_id', 'date'])
                    ->orderBy('id', 'desc');
        // if ($request->has('status') && !is_null($request->status)) {
        //     $query->where('status', $request->status);
        // }

        if ($request->has('startDate') && $request->has('endDate')) {
            $startDate = Carbon::parse($request->startDate)->startOfDay();
            $endDate = Carbon::parse($request->endDate)->endOfDay();
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        return DataTables::of($query)
            ->editColumn('customer_id', function ($row) {
                return $row->customer ? $row->customer->marking : 'Marking tidak tersedia';
            })
            ->editColumn('topup_amount', function ($row) {
                if ($row->pricePerKg) {
                    $total = $row->topup_amount * $row->pricePerKg->price_per_kg;
                    return 'Rp ' . number_format($total, 2);
                }
                return 'Total tidak tersedia';
            })
            ->editColumn('price_per_kg_id', function ($row) {
                if ($row->pricePerKg) {
                    return 'Rp ' . number_format($row->pricePerKg->price_per_kg, 2);
                }
                return 'Harga tidak tersedia';
            })
            ->editColumn('date', function ($row) {
                return $row->date ? Carbon::parse($row->date)->format('d F Y') : 'Tanggal tidak tersedia';
            })
            ->make(true);
    }



    public function storeTopup(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:tbl_pembeli,id',
            'topup_amount' => 'required|numeric|min:1',
            'new_price' => 'nullable|numeric|min:1',
            'effective_date' => 'nullable|date',
            'price_per_kg_id' => 'nullable|exists:tbl_price_poin,id',
            'coa_id' => 'required|exists:tbl_coa,id',
        ]);

        DB::beginTransaction();

        try {
            $customer = Customer::find($request->customer_id);
            if ($request->new_price && $request->effective_date) {
                $price = PricePoin::create([
                    'price_per_kg' => $request->new_price,
                    'effective_date' => $request->effective_date,
                ]);
                $priceId = $price->id;
            } else {
                $priceId = $request->price_per_kg_id;
            }
            $topup = HistoryTopup::create([
                'customer_id' => $request->customer_id,
                'customer_name' => $customer->nama_pembeli,
                'topup_amount' => $request->topup_amount,
                'remaining_points' => $request->topup_amount,
                'price_per_kg_id' => $priceId,
                'date' => now(),
                'account_id' => $request->coa_id,
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Top-up berhasil disimpan', 'data' => $topup]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan top-up: ' . $e->getMessage()], 500);
        }
    }



}
