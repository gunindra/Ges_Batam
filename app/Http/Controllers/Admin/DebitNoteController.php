<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\COA;
use App\Models\DebitNote;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Admin\JournalController;
use App\Models\Jurnal;
use App\Models\JurnalItem;

class DebitNoteController extends Controller
{

    protected $jurnalController;

    public function __construct(JournalController $jurnalController)
    {
        $this->jurnalController = $jurnalController;
    }


    public function index()
    {
        $listStatus = DB::select("SELECT b.name
            FROM tbl_debit_note AS a
            JOIN tbl_coa AS b ON b.id = a.account_id
            GROUP BY a.account_id, b.name");

        return view('vendor.debitnote.indexdebitnote', [
            'listStatus' => $listStatus
        ]);
    }

    public function addDebitNote()
    {
        $coas = COA::all();
        $listCurrency = DB::select("SELECT id, nama_matauang, singkatan_matauang FROM tbl_matauang");
        $listInvoice = DB::select("SELECT id, invoice_no FROM tbl_sup_invoice");

        return view('vendor.debitnote.buatdebitnote', [
            'listCurrency' => $listCurrency,
            'coas' => $coas,
            'listInvoice' => $listInvoice
        ]);
    }

    public function getDebitNotes(Request $request)
    {
        if ($request->ajax()) {
            $debitNotes = DB::table('tbl_debit_note AS dn')
                ->join('tbl_sup_invoice AS inv', 'dn.invoice_id', '=', 'inv.id')
                ->join('tbl_coa AS coa', 'dn.account_id', '=', 'coa.id')
                ->join('tbl_matauang AS mu', 'dn.matauang_id', '=', 'mu.id')
                ->select('dn.id', 'dn.no_debitnote', 'inv.invoice_no', 'coa.name as coa_name', 'mu.singkatan_matauang as currency_short', 'dn.created_at')
                ->orderBy('dn.id', 'desc');

            if ($request->status) {
                $debitNotes->where('coa.name', $request->status);
            }

            if ($request->startDate && $request->endDate) {
                $startDate = Carbon::createFromFormat('d M Y', $request->startDate)->startOfDay();
                $endDate = Carbon::createFromFormat('d M Y', $request->endDate)->endOfDay();
                $debitNotes->whereBetween('dn.created_at', [$startDate, $endDate]);
            }

            return DataTables::of($debitNotes)
                ->addIndexColumn()
                ->addColumn('no_debitnote', function ($row) {
                    return $row->no_debitnote;
                })
                ->addColumn('invoice', function ($row) {
                    return $row->invoice_no;
                })
                ->addColumn('coa_name', function ($row) {
                    return $row->coa_name;
                })
                ->addColumn('currency', function ($row) {
                    return $row->currency_short;
                })
                ->addColumn('tanggal', function ($row) {
                    return Carbon::parse($row->created_at)->translatedFormat('d F Y');
                })
                ->addColumn('action', function ($row) {
                    $btn = ' <a href="#" data-id="' . $row->id . '" class="btn btnedit btn-primary btn-sm"><i class="fas fa-list-ul"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'invoiceDebit' => 'required|string|max:255',
            'accountDebit' => 'required|integer',
            'currencyDebit' => 'required|integer',
            'rateCurrency' => 'nullable|numeric',
            'noteDebit' => 'nullable|string',
            'items' => 'required|array',
            'items.*.noresi' => 'required|string|max:255',
            'items.*.deskripsi' => 'required|string|max:255',
            'items.*.harga' => 'required|numeric',
            'items.*.jumlah' => 'required|numeric',
            'items.*.total' => 'required|numeric',
            'totalKeseluruhan' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            // Ambil ID akun pengembalian pembelian dari pengaturan akun
            $accountSettings = DB::table('tbl_account_settings')->first();
            $supplierPurchaseReturnAccountId = $accountSettings->supplier_purchase_return_account_id;

            // Validasi jika supplier_purchase_return_account_id valid
            if (!DB::table('tbl_coa')->where('id', $supplierPurchaseReturnAccountId)->exists()) {
                return response()->json(['error' => 'Akun pengembalian pembelian tidak valid.'], 400);
            }

            $codeType = "DN";
            $currentYear = date('y');

            // Ambil data invoice
            $invoice = SupInvoice::where('id', $request->invoiceDebit)->firstOrFail();
            $invoice_id = $invoice->invoice_no;

            // Cek apakah debitNoteId ada, jika ada lakukan update, jika tidak buat baru
            if ($request->has('debitNoteId')) {
                // Update debit note
                $debitNote = DebitNote::findOrFail($request->debitNoteId);

                // Ambil jurnal terkait debit note ini jika sudah ada
                $jurnal = Jurnal::where('no_ref', $invoice_id)->first();
            } else {
                // Buat debit note baru
                $lastDebitNote = DebitNote::where('no_debitnote', 'like', $codeType . $currentYear . '%')
                    ->orderBy('no_debitnote', 'desc')
                    ->first();

                $newSequence = $lastDebitNote ? intval(substr($lastDebitNote->no_debitnote, -4)) + 1 : 1;
                $newNoDebitNote = $codeType . $currentYear . str_pad($newSequence, 4, '0', STR_PAD_LEFT);

                $debitNote = new DebitNote();
                $debitNote->no_debitnote = $newNoDebitNote;

                // Generate nomor jurnal baru untuk create
                $request->merge(['code_type' => 'DN']);
                $noJournal = $this->jurnalController->generateNoJurnal($request)->getData()->no_journal;

                // Buat jurnal baru
                $jurnal = new Jurnal();
                $jurnal->no_journal = $noJournal;
            }

            // Jika currencyDebit == 1, kosongkan rateCurrency
            if ($request->currencyDebit == 1) {
                $request->rateCurrency = null;
            }

            // Simpan atau update data debit note
            $debitNote->invoice_id = $request->invoiceDebit;
            $debitNote->account_id = $request->accountDebit;
            $debitNote->matauang_id = $request->currencyDebit;
            $debitNote->rate_currency = $request->rateCurrency;
            $debitNote->note = $request->noteDebit;
            $debitNote->total_keseluruhan = $request->totalKeseluruhan;
            $debitNote->save();

            // Simpan atau update debit note items
            foreach ($request->items as $item) {
                $debitNote->items()->updateOrCreate(
                    ['no_resi' => $item['noresi']],
                    [
                        'deskripsi' => $item['deskripsi'],
                        'harga' => $item['harga'],
                        'jumlah' => $item['jumlah'],
                        'total' => $item['total'],
                    ]
                );
            }

            // Update atau buat jurnal jika perlu
            $jurnal->tipe_kode = 'DN';
            $jurnal->tanggal = now();
            $jurnal->no_ref = $invoice_id;
            $jurnal->status = 'Approve';
            $jurnal->description = "Jurnal untuk Invoice {$invoice_id}";
            $jurnal->totaldebit = $request->totalKeseluruhan;
            $jurnal->totalcredit = $request->totalKeseluruhan;
            $jurnal->save();

            // Simpan item jurnal (debit)
            JurnalItem::updateOrCreate(
                ['jurnal_id' => $jurnal->id, 'code_account' => $request->accountDebit],
                [
                    'description' => "Debit untuk Invoice {$invoice_id}",
                    'debit' => $request->totalKeseluruhan,
                    'credit' => 0,
                ]
            );

            // Simpan item jurnal (kredit)
            JurnalItem::updateOrCreate(
                ['jurnal_id' => $jurnal->id, 'code_account' => $supplierPurchaseReturnAccountId],
                [
                    'description' => "Kredit untuk Invoice {$invoice_id}",
                    'debit' => 0,
                    'credit' => $request->totalKeseluruhan,
                ]
            );

            DB::commit();
            return response()->json(['message' => 'Debit note berhasil disimpan!'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }





    public function updatepage($id)
    {
        $debitNote = DebitNote::with('items')->find($id);
        $coas = COA::all();
        $listCurrency = DB::select("SELECT id, nama_matauang, singkatan_matauang FROM tbl_matauang");
        $listInvoice = DB::select("SELECT id, invoice_no FROM tbl_sup_invoice");

        return view('vendor.debitnote.updatedebit', [
            'listCurrency' => $listCurrency,
            'coas' => $coas,
            'listInvoice' => $listInvoice,
            'debitNote' => $debitNote
        ]);
    }



}
