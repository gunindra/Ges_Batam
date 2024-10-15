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

    public function index() {


        return view('customer.creditnote.indexcreditnote');
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
                ->select( 'cn.id','cn.no_creditnote', 'inv.no_invoice', 'coa.name as coa_name', 'mu.singkatan_matauang as currency_short', 'cn.status_bayar', 'cn.created_at')
                ->orderBy('cn.id', 'desc');

            // Filter berdasarkan status
            if ($request->status) {
                $creditNotes->where('cn.status_bayar', $request->status);
            }

            // Filter berdasarkan rentang tanggal
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
                ->addColumn('status_bayar', function ($row) {
                    return $row->status_bayar;
                })
                ->addColumn('tanggal', function ($row) {
                    return Carbon::parse($row->created_at)->translatedFormat('d F Y');
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="#" data-id="' . $row->id . '" class="view btn btn-secondary btn-sm">View</a>';
                    $btn .= ' <a href="#" data-id="' . $row->id . '" class="edit btn btn-primary btn-sm">Edit</a>';
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
            $lastCreditNote = CreditNote::where('no_creditnote', 'like', $codeType . $currentYear . '%')
                ->orderBy('no_creditnote', 'desc')
                ->first();

                $invoice = Invoice::where('id',$request->invoiceCredit)->firstOrFail();
                $invoice_id = $invoice->no_invoice;

            $newSequence = 1;
            if ($lastCreditNote) {
                $lastSequence = intval(substr($lastCreditNote->no_creditnote, -4));
                $newSequence = $lastSequence + 1;
            }
            $newNoCreditNote = $codeType . $currentYear . str_pad($newSequence, 4, '0', STR_PAD_LEFT);

            $creditNote = new CreditNote();
            $creditNote->no_creditnote = $newNoCreditNote;
            $creditNote->invoice_id = $request->invoiceCredit;
            $creditNote->account_id = $request->accountCredit;
            $creditNote->matauang_id = $request->currencyCredit;
            $creditNote->rate_currency = $request->rateCurrency;
            $creditNote->note = $request->noteCredit;
            $creditNote->total_keseluruhan = $request->totalKeseluruhan;
            $creditNote->save();

            foreach ($request->items as $item) {
                $creditNote->items()->create([
                    'no_resi' => $item['noresi'],
                    'deskripsi' => $item['deskripsi'],
                    'harga' => $item['harga'],
                    'jumlah' => $item['jumlah'],
                    'total' => $item['total'],
                ]);
            }

            $request->merge(['code_type' => 'CN']);
            $noJournal = $this->jurnalController->generateNoJurnal($request)->getData()->no_journal;

            $jurnal = new Jurnal();
            $jurnal->no_journal = $noJournal;
            $jurnal->tipe_kode = 'CN';
            $jurnal->tanggal = now();
            $jurnal->no_ref = $invoice_id;
            $jurnal->status = 'Approve';
            $jurnal->description = "Jurnal untuk Invoice {$invoice_id}";
            $jurnal->totaldebit = $request->totalKeseluruhan;
            $jurnal->totalcredit = $request->totalKeseluruhan;
            $jurnal->save();

            $jurnalItemDebit = new JurnalItem();
            $jurnalItemDebit->jurnal_id = $jurnal->id;
            $jurnalItemDebit->code_account = $receivableSalesAccountId;
            $jurnalItemDebit->description = "Debit untuk Invoice {$invoice_id}";
            $jurnalItemDebit->debit = 0;
            $jurnalItemDebit->credit = $request->totalKeseluruhan;
            $jurnalItemDebit->save();

            $jurnalItemCredit = new JurnalItem();
            $jurnalItemCredit->jurnal_id = $jurnal->id;
            $jurnalItemCredit->code_account = $salesAccountId;
            $jurnalItemCredit->description = "Kredit untuk Invoice {$invoice_id}";
            $jurnalItemCredit->debit = $request->totalKeseluruhan;
            $jurnalItemCredit->credit = 0;
            $jurnalItemCredit->save();

            DB::commit();
            return response()->json(['message' => 'Credit note berhasil disimpan!'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }


}
