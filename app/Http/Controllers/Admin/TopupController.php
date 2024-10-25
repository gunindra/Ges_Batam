<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\COA;
use App\Models\Customer;
use App\Models\HistoryTopup;
use App\Http\Controllers\Admin\JournalController;
use App\Models\Jurnal;
use App\Models\JurnalItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\PricePoin;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TopupController extends Controller
{

    protected $jurnalController;

    public function __construct(JournalController $jurnalController)
    {
        $this->jurnalController = $jurnalController;
    }

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
                    $total = $row->topup_amount;
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
            'remaining_points' => 'required|numeric|min:1', // gunakan remaining_points untuk jumlah poin
            'new_price' => 'nullable|numeric|min:1',
            'effective_date' => 'nullable|date',
            'price_per_kg_id' => 'nullable|exists:tbl_price_poin,id',
            'coa_id' => 'required|exists:tbl_coa,id',
        ]);

        DB::beginTransaction();

        try {
            $customer = Customer::find($request->customer_id);

            // Cek apakah ada harga baru yang dimasukkan
            if ($request->new_price && $request->effective_date) {
                $price = PricePoin::create([
                    'price_per_kg' => $request->new_price,
                    'effective_date' => $request->effective_date,
                ]);
                $priceId = $price->id;
                $pricePerKg = $price->price_per_kg;
            } else {
                $priceId = $request->price_per_kg_id;
                $pricePerKg = PricePoin::find($priceId)->price_per_kg; // Ambil harga per kg dari database
            }

            // Hitung nominal yang dibayarkan (topup_amount) berdasarkan remaining_points dan harga per kg
            $topupAmount = $request->remaining_points * $pricePerKg;

            // Simpan riwayat top-up ke database
            $topup = HistoryTopup::create([
                'customer_id' => $request->customer_id,
                'customer_name' => $customer->nama_pembeli,
                'topup_amount' => $topupAmount,
                'remaining_points' => $request->remaining_points,
                'price_per_kg_id' => $priceId,
                'date' => now(),
                'account_id' => $request->coa_id,
            ]);

            // Update kolom sisa_poin dengan hasil penjumlahan sebagai string
            $newSisaPoin = (string)(((int)$customer->sisa_poin ?? 0) + $request->remaining_points);
            $customer->sisa_poin = $newSisaPoin;
            $customer->save();

            $updatedPembeli = DB::table('tbl_pembeli')
                ->where('id', $request->customer_id)
                ->update(['transaksi_terakhir' => now()]);

            if (!$updatedPembeli) {
                throw new \Exception("Gagal memperbarui transaksi terakhir di tbl_pembeli");
            }

            try {
                // Ambil account_id untuk kredit dari tbl_account_settings
                $creditAccount = DB::table('tbl_account_settings')
                    ->value('purchase_profit_rate_account_id');

                if (!$creditAccount) {
                    throw new \Exception("Account setting purchase_profit_rate_account_id tidak ditemukan.");
                }

                // Generate Jurnal
                $request->merge(['code_type' => 'TU']);
                $noJournal = $this->jurnalController->generateNoJurnal($request)->getData()->no_journal;
                $totalHarga = $topupAmount;

                // Buat Jurnal Utama
                $jurnal = new Jurnal();
                $jurnal->no_journal = $noJournal;
                $jurnal->tipe_kode = 'TU';
                $jurnal->tanggal = now();
                $jurnal->no_ref = $topup->id;
                $jurnal->status = 'Approve';
                $jurnal->description = "Jurnal untuk Top-up Customer {$customer->nama_pembeli}";
                $jurnal->totaldebit = $totalHarga;
                $jurnal->totalcredit = $totalHarga;
                $jurnal->save();

                // Buat Jurnal Item Debit
                $jurnalItemDebit = new JurnalItem();
                $jurnalItemDebit->jurnal_id = $jurnal->id;
                $jurnalItemDebit->code_account = $request->coa_id;
                $jurnalItemDebit->description = "Debit untuk Top-up Customer {$customer->nama_pembeli}";
                $jurnalItemDebit->debit = $totalHarga;
                $jurnalItemDebit->credit = 0;
                $jurnalItemDebit->save();

                // Buat Jurnal Item Credit
                $jurnalItemCredit = new JurnalItem();
                $jurnalItemCredit->jurnal_id = $jurnal->id;
                $jurnalItemCredit->code_account = $creditAccount;
                $jurnalItemCredit->description = "Kredit untuk Top-up Customer {$customer->nama_pembeli}";
                $jurnalItemCredit->debit = 0;
                $jurnalItemCredit->credit = $totalHarga;
                $jurnalItemCredit->save();

            } catch (\Exception $e) {
                throw new \Exception('Gagal menambahkan jurnal: ' . $e->getMessage());
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Top-up berhasil disimpan dan jurnal diperbarui', 'data' => $topup]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan top-up: ' . $e->getMessage()], 500);
        }
    }







}
