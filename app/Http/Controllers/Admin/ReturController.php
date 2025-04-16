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
use App\Models\COA;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Admin\JournalController;
use App\Models\Jurnal;
use App\Models\JurnalItem;


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
            ->join('tbl_matauang as m', 'r.currency_id', '=', 'm.id')
            ->join('tbl_coa as a', 'r.account_id', '=', 'a.id')
            ->where('i.company_id', $companyId)
            ->select([
                'r.id',
                'i.no_invoice',
                'm.singkatan_matauang as mata_uang',
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
            'currency:id,singkatan_matauang',
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
        $coas = COA::all();
        $listCurrency = DB::select("SELECT id, nama_matauang, singkatan_matauang FROM tbl_matauang");
        $listInvoice = DB::select("SELECT
                                                tbl_invoice.id,
                                                tbl_invoice.no_invoice,
                                                tbl_pembeli.marking,
                                                tbl_pembeli.nama_pembeli
                                            FROM tbl_invoice
                                            JOIN tbl_pembeli ON tbl_invoice.pembeli_id = tbl_pembeli.id
                                            WHERE tbl_invoice.company_id = $companyId
                                        ");

        return view('customer.retur.buatretur', [
            'listCurrency' => $listCurrency,
            'coas' => $coas,
            'listInvoice' => $listInvoice
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
        // Ambil ID perusahaan dari session
        $companyId = session('active_company_id');

        // Validasi input
        $validated = $request->validate([
            'invoice_id' => 'required|exists:tbl_invoice,id',
            'currency_id' => 'required|exists:tbl_matauang,id',
            'account_id' => 'required|exists:tbl_coa,id',
            'deskripsi' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.resi_id' => 'required|exists:tbl_resi,id',
        ]);

        $resiIds = collect($validated['items'])->pluck('resi_id');

        // Cek apakah resi sudah digunakan sebelumnya
        $usedResi = ReturItem::whereIn('resi_id', $resiIds)->pluck('resi_id')->toArray();
        if (count($usedResi)) {
            return response()->json([
                'error' => 'Resi berikut sudah digunakan di retur lain.',
                'resi_id' => $usedResi,
            ], 422);
        }

        // Ambil no_resi dari tabel Resi berdasarkan resi_id
        $noResis = Resi::whereIn('id', $resiIds)->pluck('no_resi')->toArray();

        // Ambil setting account
        $accountSettings = DB::table('tbl_account_settings')->first();
        if (!$accountSettings || is_null($accountSettings->customer_sales_return_account_id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Silakan cek Account setting untuk mengatur pemilihan Account.',
            ], 400);
        }

        $debitaccount = $accountSettings->customer_sales_return_account_id;
        $creditaccount = $request->account_id;

        DB::beginTransaction();
        try {
            // Hitung total nominal retur berdasarkan harga dari Resi
            $totalNominal = Resi::whereIn('id', $resiIds)->sum('harga');

            // Simpan retur
            $retur = Retur::create([
                'invoice_id' => $validated['invoice_id'],
                'currency_id' => $validated['currency_id'],
                'account_id' => $validated['account_id'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'total_nominal' => $totalNominal,
            ]);

            // Simpan item retur
            foreach ($resiIds as $resiId) {
                ReturItem::create([
                    'retur_id' => $retur->id,
                    'resi_id' => $resiId,
                ]);
            }

            // Buat jurnal
            $invoice = Invoice::where('id', $request->invoice_id)->firstOrFail();
            $codeType = "JU";
            $request->merge(['code_type' => 'JU']);
            $noJournal = $this->jurnalController->generateNoJurnal($request)->getData()->no_journal;

            $jurnal = new Jurnal();
            $jurnal->no_journal = $noJournal;
            $jurnal->tanggal = now();
            $jurnal->no_ref = $invoice->no_invoice;
            $jurnal->tipe_kode = $codeType;
            $jurnal->status = 'Approve';
            $jurnal->description = "Jurnal untuk Invoice {$invoice->no_invoice}";
            $jurnal->totaldebit = $totalNominal;
            $jurnal->totalcredit = $totalNominal;
            $jurnal->company_id = $companyId;
            $jurnal->save();

            // Tambahkan dua jurnal item: debit & credit
            JurnalItem::create([
                'jurnal_id' => $jurnal->id,
                'code_account' => $debitaccount,
                'description' => "Retur penjualan untuk invoice {$invoice->no_invoice}",
                'debit' => $totalNominal,
                'credit' => 0,
                'memo' => null,
            ]);

            JurnalItem::create([
                'jurnal_id' => $jurnal->id,
                'code_account' => $creditaccount,
                'description' => "Retur penjualan untuk invoice {$invoice->no_invoice}",
                'debit' => 0,
                'credit' => $totalNominal,
                'memo' => null,
            ]);

            // Update status tracking berdasarkan no_resi
            DB::table('tbl_tracking')
                ->whereIn('no_resi', $noResis)
                ->update(['status' => 'Dalam Perjalanan']);

            DB::commit();
            return response()->json(['message' => 'Retur berhasil disimpan.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Gagal menyimpan retur.',
                'message' => $e->getMessage() // opsional untuk debug
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
            $oldAccountId = $retur->account_id;

            // Update retur
            $retur->update([
                'account_id' => $validated['account_id'],
                'deskripsi' => $validated['deskripsi'] ?? null,
            ]);

            // Update jurnal
            $invoice = Invoice::find($retur->invoice_id);
            $jurnal = Jurnal::where('no_ref', $invoice->no_invoice)->first();

            if ($jurnal) {
                // Update jurnal description jika perlu
                if (!empty($validated['deskripsi'])) {
                    $jurnal->description = $validated['deskripsi'];
                    $jurnal->save();
                }

                // Update jurnal item credit (account_id yang bisa diubah user)
                $jurnalCreditItem = JurnalItem::where('jurnal_id', $jurnal->id)
                    ->where('credit', '>', 0)
                    ->first();

                if ($jurnalCreditItem) {
                    $jurnalCreditItem->update([
                        'code_account' => $validated['account_id'],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('retur.index')->with('success', 'Retur dan jurnal berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memperbarui retur. ' . $e->getMessage()]);
        }
    }






}
