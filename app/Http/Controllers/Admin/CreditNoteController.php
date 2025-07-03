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

   public function getNoResiByInvoice(Request $request)
    {
        $invoiceId = $request->invoice_id;
        $search = $request->q;

        $resis = DB::table('tbl_resi')
            ->where('invoice_id', $invoiceId)
            ->when($search, function ($query, $search) {
                return $query->where('no_resi', 'like', '%' . $search . '%');
            })
            ->select('no_resi', 'harga') // ambil harga juga
            ->get();

        return response()->json($resis);
    }


    public function addCreditNote()
    {
        $companyId = session('active_company_id');
        $coas = COA::whereNotNull('parent_id')
            ->where('set_as_group', 0)
            ->get();
        $listCurrency = DB::select("SELECT id, nama_matauang, singkatan_matauang FROM tbl_matauang");
        $listInvoice = DB::select("SELECT
                                                tbl_invoice.id,
                                                tbl_invoice.no_invoice,
                                                tbl_pembeli.marking,
                                                tbl_pembeli.nama_pembeli
                                            FROM tbl_invoice
                                            JOIN tbl_pembeli ON tbl_invoice.pembeli_id = tbl_pembeli.id
                                            WHERE tbl_invoice.company_id = $companyId
                                            AND tbl_invoice.status_bayar = 'Belum lunas'
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
                    $editBtn = '<a href="#" data-id="' . $row->id . '" class="btn btnedit btn-primary btn-sm"><i class="fas fa-list-ul"></i></a>';
                    $deleteBtn = '<button data-id="' . $row->id . '" class="btn btndelete btn-danger btn-sm ml-1"><i class="fas fa-trash-alt"></i></button>';
                    return $editBtn . ' ' . $deleteBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {

        // dd($request->all());
        $companyId = session('active_company_id');
        $request->validate([
            'invoiceCredit' => 'required|string|max:255',
            'tanggalCreditNote' => 'required|date',
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


        $noResis = collect($request->items)->pluck('noresi')->unique();
        $existingResis = DB::table('tbl_tracking')
            ->whereIn('no_resi', $noResis)
            ->pluck('no_resi')
            ->all();

        $notFoundResis = $noResis->diff($existingResis);

        if ($notFoundResis->isNotEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Beberapa resi tidak ditemukan di sistem.',
                'missing_resi' => $notFoundResis->values(),
            ], 400);
        }


        DB::beginTransaction();

        try {
            // Validasi account settings
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

            // Get invoice data
            $invoice = Invoice::where('id', $request->invoiceCredit)->firstOrFail();
            $invoice_id = $invoice->no_invoice;

            // Validasi invoice sudah lunas
            if (!$request->has('creditNoteId') && $invoice->total_bayar >= $invoice->total_harga) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak dapat membuat Credit Note karena invoice sudah lunas.',
                ], 400);
            }

            // Validasi sisa invoice cukup untuk credit note
            $sisaInvoice = $invoice->total_harga - $invoice->total_bayar;
            if (!$request->has('creditNoteId') && $request->totalKeseluruhan > $sisaInvoice) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Nilai Credit Note melebihi sisa tagihan invoice. Sisa tagihan: ' . number_format($sisaInvoice, 2),
                ], 400);
            }


            $closedPeriod = DB::table('tbl_periode')
                ->whereDate('periode_start', '<=', $request->tanggalCreditNote)
                ->whereDate('periode_end', '>=', $request->tanggalCreditNote)
                ->where('status', 'Closed')
                ->first();

            if ($closedPeriod) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak dapat membuat Credit Note karena tanggal tersebut berada di dalam periode yang sudah ditutup: ' . $closedPeriod->periode,
                ], 400);
            }

            if ($request->has('creditNoteId')) {
                $creditNote = CreditNote::findOrFail($request->creditNoteId);

                // Kembalikan total_bayar ke nilai sebelum credit note
                $invoice->total_bayar = $request->totalKeseluruhan;

                $invoice->save();
                $creditNote->items()->delete();
                $jurnal = Jurnal::where('credit_note_id', $creditNote->id)->first();

                if (!$jurnal) {
                    $jurnal = Jurnal::where('no_ref', $invoice->no_invoice)
                                    ->where('tipe_kode', 'CN')
                                    ->first();
                }
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

                $invoice->total_bayar += $request->totalKeseluruhan;

                if ($invoice->total_bayar > $invoice->total_harga) {
                    $invoice->total_bayar = $invoice->total_harga;
                }

                $invoice->save();
            }

            $tanggal = $request->tanggalCreditNote;

            $creditNote->tanggal = $tanggal;
            $creditNote->invoice_id = $request->invoiceCredit;
            $creditNote->account_id = $request->accountCredit;
            $creditNote->matauang_id = $request->currencyCredit;
            $creditNote->rate_currency = $request->rateCurrency;
            $creditNote->note = $request->noteCredit;
            $creditNote->total_keseluruhan = $request->totalKeseluruhan;
            $creditNote->company_id = $companyId;
            $creditNote->save();

            $jurnal->tanggal = $tanggal;
            $jurnal->no_ref = $invoice_id;
            $jurnal->tipe_kode = $codeType;
            $jurnal->status = 'Approve';
            $jurnal->description = "Jurnal untuk Invoice {$invoice_id}";
            $jurnal->totaldebit = $request->totalKeseluruhan;
            $jurnal->totalcredit = $request->totalKeseluruhan;
            $jurnal->company_id = $companyId;
            $jurnal->credit_note_id = $creditNote->id;
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

                DB::table('tbl_tracking')
                ->where('no_resi', $item['noresi'])
                ->update(['status' => 'Return']);
            }

            if($invoice->total_harga - $invoice->total_bayar == 0 ){
                $invoice->status_bayar = 'Lunas';
                $invoice->save();
            } else {
                $invoice->status_bayar = 'Belum lunas';
                $invoice->save();
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
        $companyId = session('active_company_id');
        $creditNote = CreditNote::with('items')->find($id);
        $coas = COA::whereNotNull('parent_id')
            ->where('set_as_group', 0)
            ->get();
        $listCurrency = DB::select("SELECT id, nama_matauang, singkatan_matauang FROM tbl_matauang");
        $listInvoice = DB::select("SELECT
                                                tbl_invoice.id,
                                                tbl_invoice.no_invoice,
                                                tbl_pembeli.marking,
                                                tbl_pembeli.nama_pembeli
                                            FROM tbl_invoice
                                            JOIN tbl_pembeli ON tbl_invoice.pembeli_id = tbl_pembeli.id
                                            WHERE tbl_invoice.company_id = $companyId
                                            AND tbl_invoice.status_bayar = 'Belum lunas'
                                        ");

        return view('customer.creditnote.updatecredit', [
            'listCurrency' => $listCurrency,
            'coas' => $coas,
            'listInvoice' => $listInvoice,
            'creditNote' => $creditNote
        ]);
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $creditNote = CreditNote::with('items')->findOrFail($id);
            $invoice = Invoice::findOrFail($creditNote->invoice_id);

            $invoice->total_bayar -= $creditNote->total_keseluruhan;
            if ($invoice->total_bayar < 0) {
                $invoice->total_bayar = 0;
            }
            if ($invoice->total_bayar >= $invoice->total_harga) {
                $invoice->status_bayar = 'Lunas';
            } else {
                $invoice->status_bayar = 'Belum Lunas';
            }

            $invoice->save();
            $jurnal = Jurnal::where('credit_note_id', $creditNote->id)->first();

            if (!$jurnal) {
                $jurnal = Jurnal::where('no_ref', $invoice->no_invoice)
                                ->where('tipe_kode', 'CN')
                                ->first();
            }

            if ($jurnal) {
                JurnalItem::where('jurnal_id', $jurnal->id)->delete();
                $jurnal->delete();
            }
            $creditNote->items()->delete();
            $creditNote->delete();
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Credit Note berhasil dihapus.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus Credit Note.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



}
