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
        $listRateVolume = DB::select("SELECT id, nilai_rate, rate_for FROM tbl_rate");


        return view('topup.indextopup', [
            'coas' => $coas,
            'listRateVolume' => $listRateVolume,
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
        $query = HistoryTopup::with(['customer', 'account']) // Menghapus relasi `pricePerKg`
                    ->select(['customer_id', 'customer_name', 'topup_amount', 'price_per_kg', 'account_id', 'date'])
                    ->orderBy('id', 'desc');

        // Filter berdasarkan tanggal hanya jika `startDate` dan `endDate` tersedia
        if ($request->has('startDate') && $request->has('endDate') && $request->startDate && $request->endDate) {
            $startDate = Carbon::parse($request->startDate)->startOfDay();
            $endDate = Carbon::parse($request->endDate)->endOfDay();
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        return DataTables::of($query)
            ->editColumn('customer_id', function ($row) {
                return $row->customer ? $row->customer->marking : 'Marking tidak tersedia';
            })
            ->editColumn('topup_amount', function ($row) {
                $total = $row->topup_amount;
                return 'Rp ' . number_format($total, 2);
            })
            ->editColumn('price_per_kg', function ($row) {
                return 'Rp ' . number_format($row->price_per_kg, 2);
            })
            ->editColumn('date', function ($row) {
                return $row->date ? Carbon::parse($row->date)->format('d F Y') : 'Tanggal tidak tersedia';
            })
            ->make(true);
    }


    public function storeTopup(Request $request)
    {
        // dd($request->all());
        // Validasi input
        $request->validate([
            'customer_id' => 'required|exists:tbl_pembeli,id',
            'remaining_points' => 'required|numeric|min:1',
            'price_per_kg' => 'required|numeric|min:0.01',
            'coa_id' => 'required|exists:tbl_coa,id',
        ]);

        DB::beginTransaction();

        try {
            // Ambil data customer
            $customer = Customer::find($request->customer_id);

            // Hitung total top-up amount
            $topupAmount = $request->remaining_points * $request->price_per_kg;

            // Buat entri top-up di `tbl_history_topup`
            $topup = HistoryTopup::create([
                'customer_id' => $request->customer_id,
                'customer_name' => $customer->nama_pembeli,
                'topup_amount' => $topupAmount,
                'remaining_points' => $request->remaining_points,
                'price_per_kg' => $request->price_per_kg,
                'balance' => $request->remaining_points, // Set `balance` sebagai poin yang ditop-up
                'date' => now(),
                'account_id' => $request->coa_id,
            ]);

            // Update kolom `sisa_poin` di `tbl_pembeli`
            $customer->sisa_poin = (string)(((int)$customer->sisa_poin ?? 0) + $request->remaining_points);
            $customer->transaksi_terakhir = now();
            $customer->save();

            // Buat jurnal utama dan item jurnal (logika tetap sama)
            $creditAccount = DB::table('tbl_account_settings')->value('purchase_profit_rate_account_id');
            if (!$creditAccount) {
                throw new \Exception("Pengaturan akun belum lengkap. Silakan periksa pengaturan akun di Account Setting.");
            }

            $request->merge(['code_type' => 'TU']);
            $noJournal = $this->jurnalController->generateNoJurnal($request)->getData()->no_journal;

            $jurnal = new Jurnal();
            $jurnal->no_journal = $noJournal;
            $jurnal->tipe_kode = 'TU';
            $jurnal->tanggal = now();
            $jurnal->no_ref = $topup->id;
            $jurnal->status = 'Approve';
            $jurnal->description = "Jurnal untuk Top-up Customer {$customer->nama_pembeli}";
            $jurnal->totaldebit = $topupAmount;
            $jurnal->totalcredit = $topupAmount;
            $jurnal->save();

            // Buat Jurnal Item Debit
            $jurnalItemDebit = new JurnalItem();
            $jurnalItemDebit->jurnal_id = $jurnal->id;
            $jurnalItemDebit->code_account = $request->coa_id;
            $jurnalItemDebit->description = "Debit untuk Top-up Customer {$customer->nama_pembeli}";
            $jurnalItemDebit->debit = $topupAmount;
            $jurnalItemDebit->credit = 0;
            $jurnalItemDebit->save();

            // Buat Jurnal Item Credit
            $jurnalItemCredit = new JurnalItem();
            $jurnalItemCredit->jurnal_id = $jurnal->id;
            $jurnalItemCredit->code_account = $creditAccount;
            $jurnalItemCredit->description = "Kredit untuk Top-up Customer {$customer->nama_pembeli}";
            $jurnalItemCredit->debit = 0;
            $jurnalItemCredit->credit = $topupAmount;
            $jurnalItemCredit->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Top-up berhasil disimpan dan jurnal diperbarui', 'data' => $topup]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan top-up: ' . $e->getMessage()], 500);
        }
    }








}
