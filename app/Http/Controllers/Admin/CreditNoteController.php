<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\COA;
use App\Models\CreditNote;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Admin\JournalController;
use App\Models\Jurnal;
use App\Models\JurnalItem;

class CreditNoteController extends Controller
{

    protected $jurnalController;

    public function __construct(JournalController $jurnalController)
    {
        $this->jurnalController = $jurnalController;
    }

    public function index()
    {
            $listStatus = DB::select("     SELECT b.name
        FROM tbl_credit_note AS a
        JOIN tbl_coa AS b ON b.id = a.account_id
        GROUP BY a.account_id, b.name") ;


        return view('customer.creditnote.indexcreditnote',  [
            'listStatus' => $listStatus]);
    }



    public function addCreditNote()
    {

        $coas = COA::all();
        $listCurrency = DB::select("SELECT id, nama_matauang, singkatan_matauang FROM tbl_matauang");
        $listInvoice = DB::select("SELECT id, no_invoice FROM tbl_invoice");


        return view('customer.creditnote.buatcreditnote', [
            'listCurrency' => $listCurrency,
            'coas' => $coas,
            'listInvoice' => $listInvoice
        ]);
    }

    public function getCreditNotes(Request $request)
    {
        if ($request->ajax()) {
            $creditNotes = DB::table('tbl_credit_note AS cn')
                ->join('tbl_invoice AS inv', 'cn.invoice_id', '=', 'inv.id')
                ->join('tbl_coa AS coa', 'cn.account_id', '=', 'coa.id')
                ->join('tbl_matauang AS mu', 'cn.matauang_id', '=', 'mu.id')
                ->select( 'cn.id','cn.no_creditnote', 'inv.no_invoice', 'coa.name as coa_name', 'mu.singkatan_matauang as currency_short', 'cn.created_at')
                ->orderBy('cn.id', 'desc');

            if ($request->status) {
                $creditNotes->where('coa.name', $request->status);
            }

            if ($request->startDate && $request->endDate) {
                $startDate = Carbon::createFromFormat('d M Y', $request->startDate)->startOfDay();
                $endDate = Carbon::createFromFormat('d M Y', $request->endDate)->endOfDay();
                $creditNotes->whereBetween('cn.created_at', [$startDate, $endDate]);
            }

            return DataTables::of($creditNotes)
                ->addIndexColumn()
                ->addColumn('no_creditnote', function ($row) {
                    return $row->no_creditnote;
                })
                ->addColumn('invoice', function ($row) {
                    return $row->no_invoice;
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
                    // $btn .= ' <a href="#" data-id="' . $row->id . '" class="btn btndelete btn-danger btn-sm"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })

                ->rawColumns(['action'])
                ->make(true);
        }
    }


    public function store(Request $request)
    {
        $request->validate([
            'invoiceCredit' => 'required|string|max:255',
            'accountCredit' => 'required|string|max:255',
            'currencyCredit' => 'required|string|max:10',
            'rateCurrency' => 'nullable|numeric',
            'noteCredit' => 'nullable|string',
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
            $salesAccountId = $accountSettings->customer_sales_return_account_id;
            $receivableSalesAccountId = $accountSettings->receivable_sales_account_id;

            $codeType = "CN";
            $currentYear = date('y');

            // Cari data invoice berdasarkan request->invoiceCredit
            $invoice = Invoice::where('id', $request->invoiceCredit)->firstOrFail();
            $invoice_id = $invoice->no_invoice;  // Inisialisasi variabel $invoice_id

            // Jika creditNoteId ada, lakukan update; jika tidak ada, buat credit note baru
            if ($request->has('creditNoteId')) {
                $creditNote = CreditNote::findOrFail($request->creditNoteId);  // Ambil data yang ada
            } else {
                // Membuat no_creditnote baru jika creditNoteId tidak ada
                $lastCreditNote = CreditNote::where('no_creditnote', 'like', $codeType . $currentYear . '%')
                    ->orderBy('no_creditnote', 'desc')
                    ->first();

                $newSequence = 1;
                if ($lastCreditNote) {
                    $lastSequence = intval(substr($lastCreditNote->no_creditnote, -4));
                    $newSequence = $lastSequence + 1;
                }
                $newNoCreditNote = $codeType . $currentYear . str_pad($newSequence, 4, '0', STR_PAD_LEFT);

                $creditNote = new CreditNote();  // Inisialisasi objek baru
                $creditNote->no_creditnote = $newNoCreditNote;  // Buat nomor credit note baru
            }

            // Update atau buat data credit note
            $creditNote->invoice_id = $request->invoiceCredit;
            $creditNote->account_id = $request->accountCredit;
            $creditNote->matauang_id = $request->currencyCredit;
            $creditNote->rate_currency = $request->rateCurrency;
            $creditNote->note = $request->noteCredit;
            $creditNote->total_keseluruhan = $request->totalKeseluruhan;
            $creditNote->save();

            // Update or create credit note items
            foreach ($request->items as $item) {
                $creditNote->items()->updateOrCreate(
                    ['no_resi' => $item['noresi']],
                    [
                        'deskripsi' => $item['deskripsi'],
                        'harga' => $item['harga'],
                        'jumlah' => $item['jumlah'],
                        'total' => $item['total'],
                    ]
                );
            }

            // Generate journal number
            $request->merge(['code_type' => 'CN']);
            $noJournal = $this->jurnalController->generateNoJurnal($request)->getData()->no_journal;

            // Update or create journal
            $jurnal = Jurnal::updateOrCreate(
                ['no_journal' => $noJournal],
                [
                    'tipe_kode' => 'CN',
                    'tanggal' => now(),
                    'no_ref' => $invoice_id,  // Gunakan $invoice_id di sini
                    'status' => 'Approve',
                    'description' => "Jurnal untuk Invoice {$invoice_id}",
                    'totaldebit' => $request->totalKeseluruhan,
                    'totalcredit' => $request->totalKeseluruhan,
                ]
            );

            // Update or create journal items
            JurnalItem::updateOrCreate(
                ['jurnal_id' => $jurnal->id, 'code_account' => $receivableSalesAccountId],
                [
                    'description' => "Debit untuk Invoice {$invoice_id}",
                    'debit' => 0,
                    'credit' => $request->totalKeseluruhan,
                ]
            );

            JurnalItem::updateOrCreate(
                ['jurnal_id' => $jurnal->id, 'code_account' => $salesAccountId],
                [
                    'description' => "Kredit untuk Invoice {$invoice_id}",
                    'debit' => $request->totalKeseluruhan,
                    'credit' => 0,
                ]
            );

            DB::commit();
            return response()->json(['message' => 'Credit note berhasil disimpan!'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }





    public function updatepage($id)
    {
        $creditNote = CreditNote::with('items')->find($id);
        $coas = COA::all();
        $listCurrency = DB::select("SELECT id, nama_matauang, singkatan_matauang FROM tbl_matauang");
        $listInvoice = DB::select("SELECT id, no_invoice FROM tbl_invoice");

        return view('customer.creditnote.updatecredit', [
            'listCurrency' => $listCurrency,
            'coas' => $coas,
            'listInvoice' => $listInvoice,
            'creditNote' => $creditNote
        ]);
    }

    public function update(Request $request, $id )
    {

    }


}
