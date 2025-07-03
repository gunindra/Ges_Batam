<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Resi;
use App\Models\Retur;
use App\Models\ReturItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Admin\JournalController;
use App\Models\Jurnal;
use App\Models\JurnalItem;
use Illuminate\Support\Facades\Log;

class ReturController extends Controller
{

    protected $jurnalController;

    public function __construct(JournalController $jurnalController)
    {
        $this->jurnalController = $jurnalController;
    }

    public function index()
    {
        $listStatus = DB::select("SELECT b.name
            FROM tbl_credit_note AS a
            JOIN tbl_coa AS b ON b.id = a.account_id
            GROUP BY a.account_id, b.name");

        return view('customer.retur.indexretur',  [
            'listStatus' => $listStatus]);
    }

    public function getListRetur()
    {
        $companyId = session('active_company_id');

        $query = DB::table('tbl_retur as r')
            ->join('tbl_invoice as i', 'r.invoice_id', '=', 'i.id')
            // ->join('tbl_matauang as m', 'r.currency_id', '=', 'm.id')
            ->join('tbl_coa as a', 'r.account_id', '=', 'a.id')
            ->where('i.company_id', $companyId)
            ->select([
                'r.id',
                'i.no_invoice',
                // 'm.singkatan_matauang as mata_uang',
                'a.name as nama_akun',
                'r.total_nominal',
                'r.deskripsi',
                'r.created_at'
            ]);

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('total_nominal', function ($row) {
                return 'Rp ' . number_format($row->total_nominal, 0, ',', '.');
            })
            ->editColumn('created_at', function ($row) {
                return \Carbon\Carbon::parse($row->created_at)->format('d M Y');
            })
            ->addColumn('action', function ($row) {
                return '
                    <button class="btn btn-sm btn-primary btn-lihat-retur" data-id="'.$row->id.'" title="Lihat">
                        <i class="fas fa-eye"></i>
                    </button>
                    <a href="'.route('retur.editRetur', $row->id).'" class="btn btn-sm btn-secondary" title="Edit">
                        <i class="fas fa-pen"></i>
                    </a>
                ';
            })

            ->rawColumns(['action'])
            ->make(true);
    }


    public function show($id)
    {
        $retur = Retur::with([
            'invoice:id,no_invoice',
            // 'currency:id,singkatan_matauang',
            'account:id,name',
            'items.resi:id,no_resi'
        ])->findOrFail($id);


        return response()->json([
            'invoice' => $retur->invoice,
            'matauang' => $retur->currency,
            'akun' => $retur->account,
            'total_nominal' => $retur->total_nominal,
            'deskripsi' => $retur->deskripsi,
            'items' => $retur->items->map(function ($item) {
                return ['no_resi' => $item->resi->no_resi];
            })
        ]);
    }

    public function tambahRetur()
    {
        $companyId = session('active_company_id');
        $savedPaymentAccounts = DB::table('tbl_payment_account')
        ->join('tbl_coa', 'tbl_payment_account.coa_id', '=', 'tbl_coa.id')
        ->select('tbl_payment_account.coa_id', 'tbl_coa.code_account_id', 'tbl_coa.name')
        ->get();

        // $listCurrency = DB::select("SELECT id, nama_matauang, singkatan_matauang FROM tbl_matauang");
        $listInvoice = DB::select("SELECT
                                                tbl_invoice.id,
                                                tbl_invoice.no_invoice,
                                                tbl_pembeli.marking,
                                                tbl_pembeli.nama_pembeli
                                            FROM tbl_invoice
                                            JOIN tbl_pembeli ON tbl_invoice.pembeli_id = tbl_pembeli.id
                                            WHERE tbl_invoice.company_id = $companyId
                                            AND tbl_invoice.status_bayar = 'Lunas'
                                        ");

        return view('customer.retur.buatretur', [
            // 'listCurrency' => $listCurrency,
            'savedPaymentAccounts' => $savedPaymentAccounts,
            'listInvoice' => $listInvoice
        ]);
    }



    public function editRetur($id)
    {
        $companyId = session('active_company_id');

        $retur = Retur::with('items')->findOrFail($id);
        $savedPaymentAccounts = DB::table('tbl_payment_account')
        ->join('tbl_coa', 'tbl_payment_account.coa_id', '=', 'tbl_coa.id')
        ->select('tbl_payment_account.coa_id', 'tbl_coa.code_account_id', 'tbl_coa.name')
        ->get();
        // $listCurrency = DB::select("SELECT id, nama_matauang, singkatan_matauang FROM tbl_matauang");

        $listInvoice = DB::select("SELECT
                                        tbl_invoice.id,
                                        tbl_invoice.no_invoice,
                                        tbl_pembeli.marking,
                                        tbl_pembeli.nama_pembeli
                                    FROM tbl_invoice
                                    JOIN tbl_pembeli ON tbl_invoice.pembeli_id = tbl_pembeli.id
                                    WHERE tbl_invoice.company_id = $companyId
                                ");

        return view('customer.retur.editRetur', [
            'returData' => $retur,
            'savedPaymentAccounts' => $savedPaymentAccounts,
            // 'listCurrency' => $listCurrency,
            'listInvoice' => $listInvoice,
        ]);
    }



    public function listresi(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:tbl_invoice,id',
        ]);

        $invoiceId = $request->input('invoice_id');
        $search = $request->input('search');

        // Ambil semua resi yang termasuk invoice tersebut dan belum pernah diretur
        $query = Resi::where('invoice_id', $invoiceId)
            ->whereDoesntHave('returItem') // hanya resi yang belum pernah diretur
            ->when($search, function ($q) use ($search) {
                $q->where('no_resi', 'like', '%' . $search . '%');
            })
            ->select('id', 'no_resi', 'harga')
            ->get();

        return response()->json($query);
    }

    public function store(Request $request)
    {
        $companyId = session('active_company_id');

        $validated = $request->validate([
            'invoice_id' => 'required|exists:tbl_invoice,id',
            'account_id' => 'required|exists:tbl_coa,id',
            'account_name' => 'required|string',
            'deskripsi' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.resi_id' => 'required|exists:tbl_resi,id',
        ]);

        $accountName = strtoupper($request->input('account_name')); // e.g., 'KUOTA'
        $resiIds = collect($validated['items'])->pluck('resi_id');

        $usedResi = ReturItem::whereIn('resi_id', $resiIds)->pluck('resi_id')->toArray();
        if (count($usedResi)) {
            return response()->json([
                'error' => 'Resi berikut sudah digunakan di retur lain.',
                'resi_id' => $usedResi,
            ], 422);
        }

        $noResis = Resi::whereIn('id', $resiIds)->pluck('no_resi')->toArray();
        $accountSettings = DB::table('tbl_account_settings')->first();

        if (!$accountSettings || is_null($accountSettings->customer_sales_return_account_id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Silakan cek Account setting untuk mengatur pemilihan Account.',
            ], 400);
        }

        $debitAccount = $accountSettings->customer_sales_return_account_id;
        $creditAccount = $validated['account_id'];

        DB::beginTransaction();
        try {
            $resiData = Resi::whereIn('id', $resiIds)->get();
            $totalHargaResi = $resiData->sum('harga');
            $totalBeratResi = $resiData->sum('berat');

            $invoice = Invoice::findOrFail($validated['invoice_id']);

            // // Update invoice
            // $invoice->total_bayar = max(0, $invoice->total_bayar - $totalHargaResi);
            // $invoice->total_harga = max(0, $invoice->total_harga - $totalHargaResi);
            // $invoice->status_bayar = $invoice->total_bayar >= $invoice->total_harga ? 'Lunas' : 'Belum Lunas';
            // $invoice->save();

            $retur = Retur::create([
                'invoice_id' => $invoice->id,
                'account_id' => $validated['account_id'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'total_nominal' => $totalHargaResi,
            ]);

            foreach ($resiIds as $resiId) {
                ReturItem::create([
                    'retur_id' => $retur->id,
                    'resi_id' => $resiId,
                ]);
            }

            if ($accountName === 'KUOTA') {
                $usagePoints = DB::table('tbl_usage_points')
                    ->join('tbl_payment_invoice', 'tbl_payment_invoice.payment_id', '=', 'tbl_usage_points.payment_id')
                    ->where('tbl_payment_invoice.invoice_id', $invoice->id)
                    ->select('tbl_usage_points.*')
                    ->orderBy('tbl_usage_points.id', 'asc')
                    ->get();

                $sisaRefundBerat = $totalBeratResi;

               foreach ($usagePoints as $point) {
                        if ($sisaRefundBerat <= 0) break;

                        $topup = DB::table('tbl_history_topup')->where('id', $point->history_topup_id)->first();

                        if (!$topup) continue;

                        $maxRefund = max(0, $topup->remaining_points - $topup->balance);
                        $refund = min($point->used_points, $sisaRefundBerat, $maxRefund);

                        if ($refund <= 0) continue;

                        // Refund aman
                        DB::table('tbl_history_topup')
                            ->where('id', $topup->id)
                            ->increment('balance', $refund);

                        DB::table('tbl_usage_points')
                            ->where('id', $point->id)
                            ->decrement('used_points', $refund);

                            $sisaRefundBerat -= $refund;
                    }

            }

            // Buat jurnal
                $request->merge(['code_type' => 'JU']);
                $noJournal = $this->jurnalController->generateNoJurnal($request)->getData()->no_journal;

                $jurnal = new Jurnal();
                $jurnal->no_journal = $noJournal;
                $jurnal->tanggal = now();
                $jurnal->no_ref = $invoice->no_invoice;
                $jurnal->tipe_kode = 'JU';
                $jurnal->status = 'Approve';
                $jurnal->description = "Retur sebagian invoice {$invoice->no_invoice}";
                $jurnal->totaldebit = $totalHargaResi;
                $jurnal->totalcredit = $totalHargaResi;
                $jurnal->company_id = $companyId;
                $jurnal->retur_id = $retur->id;
                $jurnal->save();

                JurnalItem::create([
                    'jurnal_id' => $jurnal->id,
                    'code_account' => $debitAccount,
                    'description' => "Retur sebagian invoice {$invoice->no_invoice}",
                    'debit' => $totalHargaResi,
                    'credit' => 0,
                    'memo' => null,
                ]);

                JurnalItem::create([
                    'jurnal_id' => $jurnal->id,
                    'code_account' => $creditAccount,
                    'description' => "Retur sebagian invoice {$invoice->no_invoice}",
                    'debit' => 0,
                    'credit' => $totalHargaResi,
                    'memo' => null,
                ]);

                // Update status tracking
                DB::table('tbl_tracking')
                    ->whereIn('no_resi', $noResis)
                    ->update(['status' => 'Dalam Perjalanan']);

                DB::commit();
                return response()->json(['message' => 'Retur berhasil disimpan.']);
            } catch (\Throwable $e) {
                DB::rollBack();
                return response()->json([
                    'error' => 'Gagal menyimpan retur.',
                    'message' => $e->getMessage(),
                ], 500);
            }
    }



    // UPDATE
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:tbl_coa,id',
            'deskripsi' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $retur = Retur::findOrFail($id);

            // Simpan account lama dan baru
            $oldAccountName = DB::table('tbl_coa')->where('id', $retur->account_id)->value('account_name');
            $newAccountName = DB::table('tbl_coa')->where('id', $validated['account_id'])->value('account_name');

            // Update retur
            $retur->update([
                'account_id' => $validated['account_id'],
                'deskripsi' => $validated['deskripsi'] ?? null,
            ]);

            // Update jurnal jika ada invoice terkait
            if ($retur->invoice_id) {
                $invoice = Invoice::find($retur->invoice_id);
                if (!$invoice) {
                    throw new \Exception("Invoice dengan ID {$retur->invoice_id} tidak ditemukan.");
                }

                $jurnal = Jurnal::where('retur_id', $retur->id)->first();
                if (!$jurnal) {
                    throw new \Exception("Jurnal untuk Retur ID {$retur->id} tidak ditemukan.");
                }

                // Update deskripsi jurnal
                $jurnal->update([
                    'description' => $validated['deskripsi'] ?? $jurnal->description
                ]);

                // Ambil jurnal item dengan credit > 0
                $jurnalItems = JurnalItem::where('jurnal_id', $jurnal->id)
                    ->where('credit', '>', 0)
                    ->get();

                if ($jurnalItems->isEmpty()) {
                    throw new \Exception("Tidak ditemukan jurnal item dengan credit > 0 untuk jurnal ID {$jurnal->id}.");
                }

                // Logging dan update jurnal account credit
                foreach ($jurnalItems as $item) {
                    Log::info("Sebelum update - JurnalItem ID: {$item->id}, code_account: {$item->code_account}");

                    $item->update([
                        'code_account' => $validated['account_id']
                    ]);

                    Log::info("Setelah update - JurnalItem ID: {$item->id}, code_account: {$item->code_account}");
                }
            }

            // === Tambahkan refund jika account berubah menjadi KUOTA ===
            if (strtoupper($oldAccountName) !== 'KUOTA' && strtoupper($newAccountName) === 'KUOTA') {
                $returItems = ReturItem::where('retur_id', $retur->id)->pluck('resi_id');
                $resiData = Resi::whereIn('id', $returItems)->get();
                $totalBeratResi = $resiData->sum('berat');

                $usagePoints = DB::table('tbl_usage_points')
                    ->join('tbl_payment_invoice', 'tbl_payment_invoice.payment_id', '=', 'tbl_usage_points.payment_id')
                    ->where('tbl_payment_invoice.invoice_id', $retur->invoice_id)
                    ->select('tbl_usage_points.*')
                    ->orderBy('tbl_usage_points.id', 'asc')
                    ->get();

                $sisaRefundBerat = $totalBeratResi;

                foreach ($usagePoints as $point) {
                    if ($sisaRefundBerat <= 0) break;

                    $topup = DB::table('tbl_history_topup')->where('id', $point->history_topup_id)->first();

                    if (!$topup) continue;

                    $maxRefund = max(0, $topup->remaining_points - $topup->balance);
                    $refund = min($point->used_points, $sisaRefundBerat, $maxRefund);

                    if ($refund <= 0) continue;

                    DB::table('tbl_history_topup')
                        ->where('id', $topup->id)
                        ->increment('balance', $refund);

                    DB::table('tbl_usage_points')
                        ->where('id', $point->id)
                        ->decrement('used_points', $refund);

                    $sisaRefundBerat -= $refund;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Retur berhasil diperbarui',
                'data' => $retur
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Gagal update retur: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui retur: ' . $e->getMessage(),
                'error' => $e->getTrace()
            ], 500);
        }
    }







}
