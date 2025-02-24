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
        $listStatus = DB::select("SELECT b.name
            FROM tbl_credit_note AS a
            JOIN tbl_coa AS b ON b.id = a.account_id
            GROUP BY a.account_id, b.name");

        return view('customer.creditnote.indexcreditnote',  [
            'listStatus' => $listStatus]);
    }

    public function addCreditNote()
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

        return view('customer.creditnote.buatcreditnote', [
            'listCurrency' => $listCurrency,
            'coas' => $coas,
            'listInvoice' => $listInvoice
        ]);
    }

    public function getCreditNotes(Request $request)
    {
        $companyId = session('active_company_id');
        if ($request->ajax()) {
            $creditNotes = DB::table('tbl_credit_note AS cn')
                ->where('cn.company_id', $companyId)
                ->join('tbl_invoice AS inv', 'cn.invoice_id', '=', 'inv.id')
                ->join('tbl_coa AS coa', 'cn.account_id', '=', 'coa.id')
                ->join('tbl_matauang AS mu', 'cn.matauang_id', '=', 'mu.id')
                ->select('cn.id','cn.no_creditnote', 'inv.no_invoice', 'coa.name as coa_name', 'mu.singkatan_matauang as currency_short', 'cn.created_at')
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
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $companyId = session('active_company_id');
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

            $salesAccountId = $accountSettings->customer_sales_return_account_id;
            $receivableSalesAccountId = $accountSettings->receivable_sales_account_id;

            if (is_null($salesAccountId) || is_null($receivableSalesAccountId)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Silakan cek Account setting untuk mengatur pemilihan Account.',
                ], 400);
            }

            $codeType = "CN";
            $currentYear = date('y');

            $invoice = Invoice::where('id', $request->invoiceCredit)->firstOrFail();
            $invoice_id = $invoice->no_invoice;

            if ($request->has('creditNoteId')) {
                $creditNote = CreditNote::findOrFail($request->creditNoteId);
                $creditNote->items()->delete();
                $jurnal = Jurnal::where('no_ref', $invoice_id)->first();
            } else {

                $lastCreditNote = CreditNote::where('no_creditnote', 'like', $codeType . $currentYear . '%')
                    ->orderBy('no_creditnote', 'desc')
                    ->first();

                $newSequence = 1;
                if ($lastCreditNote) {
                    $lastSequence = intval(substr($lastCreditNote->no_creditnote, -4));
                    $newSequence = $lastSequence + 1;
                }
                $newNoCreditNote = $codeType . $currentYear . str_pad($newSequence, 4, '0', STR_PAD_LEFT);

                $creditNote = new CreditNote();
                $creditNote->no_creditnote = $newNoCreditNote;

                $request->merge(['code_type' => 'CN']);
                $noJournal = $this->jurnalController->generateNoJurnal($request)->getData()->no_journal;

                $jurnal = new Jurnal();
                $jurnal->no_journal = $noJournal;
            }

            $creditNote->invoice_id = $request->invoiceCredit;
            $creditNote->account_id = $request->accountCredit;
            $creditNote->matauang_id = $request->currencyCredit;
            $creditNote->rate_currency = $request->rateCurrency;
            $creditNote->note = $request->noteCredit;
            $creditNote->total_keseluruhan = $request->totalKeseluruhan;
            $creditNote->company_id = $companyId;
            $creditNote->save();


            $jurnal->tanggal = now();
            $jurnal->no_ref = $invoice_id;
            $jurnal->tipe_kode = $codeType;
            $jurnal->status = 'Approve';
            $jurnal->description = "Jurnal untuk Invoice {$invoice_id}";
            $jurnal->totaldebit = $request->totalKeseluruhan;
            $jurnal->totalcredit = $request->totalKeseluruhan;
            $jurnal->company_id = $companyId;
            $jurnal->save();

            JurnalItem::updateOrCreate(
                ['jurnal_id' => $jurnal->id, 'code_account' => $request->accountCredit],
                [
                    'description' => "Kredit untuk Invoice {$invoice_id}",
                    'debit' => 0,
                    'credit' => $request->totalKeseluruhan,
                ]
            );

            JurnalItem::updateOrCreate(
                ['jurnal_id' => $jurnal->id, 'code_account' => $salesAccountId],
                [
                    'description' => "Debit untuk Invoice {$invoice_id}",
                    'debit' => $request->totalKeseluruhan,
                    'credit' => 0,
                ]
            );

            foreach ($request->items as $item) {
                $creditNote->items()->create([
                    'no_resi' => $item['noresi'],
                    'deskripsi' => $item['deskripsi'],
                    'harga' => $item['harga'],
                ]);
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Credit Note has been saved successfully.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'There was an error while saving the data.',
                'error' => $e->getMessage(),
            ], 500);
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
