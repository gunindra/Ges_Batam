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
            $debitNotes = DebitNote::with(['invoice', 'coa', 'matauang', 'items']);

            if ($request->startDate && $request->endDate) {
                $startDate = Carbon::createFromFormat('d M Y', $request->startDate)->startOfDay();
                $endDate = Carbon::createFromFormat('d M Y', $request->endDate)->endOfDay();
                $debitNotes->whereBetween('created_at', [$startDate, $endDate]);
            }

            $debitNotes = $debitNotes->get();

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
            $accountSettings = DB::table('tbl_account_settings')->first();

            if (!$accountSettings) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Silakan cek Account setting untuk mengatur pemilihan Account.',
                ], 400);
            }
            $supplierPurchaseReturnAccountId = $accountSettings->supplier_purchase_return_account_id;


            if (is_null($supplierPurchaseReturnAccountId)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Silakan cek Account setting untuk mengatur pemilihan Account.',
                ], 400);
            }

            if (!DB::table('tbl_coa')->where('id', $supplierPurchaseReturnAccountId)->exists()) {
                return response()->json(['error' => 'Akun pengembalian pembelian tidak valid.'], 400);
            }

            $codeType = "DN";
            $currentYear = date('y');


            $invoice = SupInvoice::where('id', $request->invoiceDebit)->firstOrFail();
            $invoice_id = $invoice->invoice_no;


            if ($request->has('debitNoteId')) {

                $debitNote = DebitNote::findOrFail($request->debitNoteId);
                $jurnal = Jurnal::where('no_ref', $invoice_id)->first();
            } else {
                $lastDebitNote = DebitNote::where('no_debitnote', 'like', $codeType . $currentYear . '%')
                    ->orderBy('no_debitnote', 'desc')
                    ->first();

                $newSequence = $lastDebitNote ? intval(substr($lastDebitNote->no_debitnote, -4)) + 1 : 1;
                $newNoDebitNote = $codeType . $currentYear . str_pad($newSequence, 4, '0', STR_PAD_LEFT);

                $debitNote = new DebitNote();
                $debitNote->no_debitnote = $newNoDebitNote;

                $request->merge(['code_type' => 'DN']);
                $noJournal = $this->jurnalController->generateNoJurnal($request)->getData()->no_journal;

                $jurnal = new Jurnal();
                $jurnal->no_journal = $noJournal;
            }
            if ($request->currencyDebit == 1) {
                $request->rateCurrency = null;
            }

            $debitNote->invoice_id = $request->invoiceDebit;
            $debitNote->account_id = $request->accountDebit;
            $debitNote->matauang_id = $request->currencyDebit;
            $debitNote->rate_currency = $request->rateCurrency;
            $debitNote->note = $request->noteDebit;
            $debitNote->total_keseluruhan = $request->totalKeseluruhan;
            $debitNote->save();

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

            $jurnal->tipe_kode = 'DN';
            $jurnal->tanggal = now();
            $jurnal->no_ref = $invoice_id;
            $jurnal->status = 'Approve';
            $jurnal->description = "Jurnal untuk Invoice {$invoice_id}";
            $jurnal->totaldebit = $request->totalKeseluruhan;
            $jurnal->totalcredit = $request->totalKeseluruhan;
            $jurnal->save();

            JurnalItem::updateOrCreate(
                ['jurnal_id' => $jurnal->id, 'code_account' => $request->accountDebit],
                [
                    'description' => "Debit untuk Invoice {$invoice_id}",
                    'debit' => $request->totalKeseluruhan,
                    'credit' => 0,
                ]
            );

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
