<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\COA;
use App\Models\DebitNote;
use App\Models\Vendor;
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
        $companyId = session('active_company_id');
        $coas = COA::all();
        $listCurrency = DB::select("SELECT id, nama_matauang, singkatan_matauang FROM tbl_matauang");
        $listInvoice = DB::select("SELECT id, invoice_no FROM tbl_sup_invoice WHERE tbl_sup_invoice.company_id = $companyId");
        $listVendor = DB::select("SELECT name FROM tbl_vendors WHERE tbl_vendors.company_id = $companyId");

        return view('vendor.debitnote.buatdebitnote', [
            'listCurrency' => $listCurrency,
            'coas' => $coas,
            'listInvoice' => $listInvoice,
            'listVendor' => $listVendor,
        ]);
    }

    public function getDebitNotes(Request $request)
    {
        $companyId = session('active_company_id');
        if ($request->ajax()) {
            $debitNotes = DebitNote::with(['invoice', 'coa', 'matauang', 'items'])
                ->where('tbl_debit_note.company_id', $companyId);

            if ($request->startDate && $request->endDate) {
                $startDate = Carbon::createFromFormat('d M Y', $request->startDate)->startOfDay();
                $endDate = Carbon::createFromFormat('d M Y', $request->endDate)->endOfDay();
                $debitNotes->whereBetween('created_at', [$startDate, $endDate]);
            }

            if ($request->has('order')) {
                $order = $request->input('order')[0];
                $column = $request->input('columns')[$order['column']]['data'];

                if ($column == 'tanggal') {
                    $column = 'created_at';
                } elseif ($column == 'invoice') {
                    $column = 'tbl_invoice.no_invoice';
                    $debitNotes = $debitNotes->join('tbl_invoice', 'tbl_debit_note.invoice_id', '=', 'tbl_invoice.id');
                }

                $direction = $order['dir'];
                $debitNotes->orderBy($column, $direction);
            }

            $debitNotes = $debitNotes->get();

            return DataTables::of($debitNotes)
                ->addIndexColumn()
                ->addColumn('no_debitnote', function ($row) {
                    return $row->no_debitnote;
                })
                ->addColumn('invoice', function ($row) {
                    return $row->invoice ? $row->invoice->invoice_no : '-';
                })
                ->addColumn('coa_name', function ($row) {
                    return $row->coa ? $row->coa->name : '-';
                })
                ->addColumn('currency', function ($row) {
                    return $row->matauang ? $row->matauang->singkatan_matauang : '-';
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
        // Validate the input
        $request->validate([
            'invoiceDebit' => 'required|string',
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

            $invoice = SupInvoice::where('invoice_no', $request->invoiceDebit)->firstOrFail();

            $invoice_id = $invoice->id;

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

            $debitNote->invoice_id = $invoice_id;
            $debitNote->account_id = $request->accountDebit;
            $debitNote->matauang_id = $request->currencyDebit;
            $debitNote->rate_currency = $request->rateCurrency;
            $debitNote->note = $request->noteDebit;
            $debitNote->total_keseluruhan = $request->totalKeseluruhan;
            $debitNote->company_id = $companyId;
            $debitNote->save();

            $debitNote->items()->delete();

            // Kemudian buat item-item baru
            foreach ($request->items as $item) {
                $debitNote->items()->create([
                    'no_resi' => $item['noresi'],
                    'deskripsi' => $item['deskripsi'],
                    'harga' => $item['harga'],
                    'jumlah' => $item['jumlah'],
                    'total' => $item['total'],
                ]);
            }

            $jurnal->tipe_kode = 'DN';
            $jurnal->tanggal = now();
            $jurnal->no_ref = $invoice_id;
            $jurnal->status = 'Approve';
            $jurnal->description = "Jurnal untuk Invoice {$invoice_id}";
            $jurnal->totaldebit = $request->totalKeseluruhan;
            $jurnal->totalcredit = $request->totalKeseluruhan;
            $jurnal->company_id = $companyId;
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

        $vendorName = SupInvoice::join('tbl_vendors', 'tbl_sup_invoice.vendor_id', '=', 'tbl_vendors.id')
            ->where('tbl_sup_invoice.id', $debitNote->invoice_id)
            ->pluck('tbl_vendors.name')
            ->first();

        $invoiceNo = SupInvoice::where('id', $debitNote->invoice_id)
            ->value('invoice_no');


        $coas = COA::all();
        $invoices = SupInvoice::all();

        $listVendor = DB::table('tbl_vendors')->select('id', 'name')->get();
        $listCurrency = DB::table('tbl_matauang')->select('id', 'nama_matauang', 'singkatan_matauang')->get();

        // Return the view with the data
        return view('vendor.debitnote.updatedebit', [
            'listVendor' => $listVendor,
            'listCurrency' => $listCurrency,
            'coas' => $coas,
            'debitNote' => $debitNote,
            'vendorName' => $vendorName,
            'invoiceNo' => $invoiceNo,
            'invoices' => $invoices,
        ]);
    }
    public function getInvoiceByVendor(Request $request)
    {
        $vendorName = $request->get('vendor_name');

        $SupInvoice = SupInvoice::join('tbl_vendors', 'tbl_vendors.id', '=', 'tbl_sup_invoice.vendor_id')
            ->where('tbl_vendors.name', $vendorName)
            ->select('tbl_sup_invoice.id', 'tbl_sup_invoice.invoice_no')
            ->get();

        if ($SupInvoice->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No invoice found for this vendor.'], 404);
        }


        return response()->json([
            'success' => true,
            'SupInvoice' => $SupInvoice
        ]);
    }
    public function GetInvoiceUpdate(Request $request)
    {
        try {
            $vendorId = $request->vendor_id;
            $selectedInvoice = $request->selectedinvoice;

            if (is_null($selectedInvoice)) {
                return response()->json(['success' => false, 'message' => 'No invoice selected.']);
            }

            $invoiceSelect = DebitNote::where('invoice_id', $selectedInvoice)->first();

            if ($invoiceSelect) {
                $invoiceNo = $invoiceSelect->invoice->invoice_no;
            } else {
                $invoiceNo = null;
            }

            if (!$vendorId) {
                return response()->json(['success' => false, 'message' => 'Vendor not found.']);
            }

            $invoices = SupInvoice::where('vendor_id', $vendorId)->get(['id', 'invoice_no']);

            if ($invoices->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No invoices found for this vendor.']);
            }

            return response()->json([
                'success' => true,
                'invoiceNo' => $invoiceNo,
                'SupInvoice' => $invoices
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    // public function update(Request $request, $id)
    // {
    //     // Validate input data
    //     $request->validate([
    //         'invoiceDebit' => 'required|string',
    //         'accountDebit' => 'required|integer',
    //         'currencyDebit' => 'required|integer',
    //         'rateCurrency' => 'nullable|numeric',
    //         'noteDebit' => 'nullable|string',
    //         'items' => 'required|array',
    //         'items.*.noresi' => 'required|string|max:255',
    //         'items.*.deskripsi' => 'required|string|max:255',
    //         'items.*.harga' => 'required|numeric',
    //         'items.*.jumlah' => 'required|numeric',
    //         'items.*.total' => 'required|numeric',
    //         'totalKeseluruhan' => 'required|numeric',
    //     ]);

    //     DB::beginTransaction();

    //     try {
    //         // Ambil data debit note berdasarkan id yang ada
    //         $debitNote = DebitNote::findOrFail($id);

    //         // Simpan id invoice lama
    //         $invoice_id = $debitNote->invoice_id;

    //         // Cek apakah invoice_no pada request berbeda dengan yang ada pada debit note
    //         if ($request->invoiceDebit != $debitNote->invoice->invoice_no) {
    //             // Cari invoice baru berdasarkan invoice_no yang diberikan
    //             $invoice = SupInvoice::where('invoice_no', $request->invoiceDebit)->first();

    //             // Jika invoice baru tidak ditemukan, beri pesan error
    //             if (!$invoice) {
    //                 return response()->json([
    //                     'status' => 'error',
    //                     'message' => "Invoice dengan nomor {$request->invoiceDebit} tidak ditemukan.",
    //                 ], 400);
    //             }

    //             // Jika ditemukan invoice, update invoice_id
    //             $invoice_id = $invoice->id;
    //         }

    //         // Ambil pengaturan akun untuk supplier purchase return
    //         $accountSettings = DB::table('tbl_account_settings')->first();
    //         if (!$accountSettings) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Silakan cek Account setting untuk mengatur pemilihan Account.',
    //             ], 400);
    //         }

    //         // Ambil akun pengembalian pembelian (supplier purchase return account id)
    //         $supplierPurchaseReturnAccountId = $accountSettings->supplier_purchase_return_account_id;

    //         if (is_null($supplierPurchaseReturnAccountId)) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Silakan cek Account setting untuk mengatur pemilihan Account.',
    //             ], 400);
    //         }

    //         // Cek apakah akun pengembalian pembelian valid
    //         if (!DB::table('tbl_coa')->where('id', $supplierPurchaseReturnAccountId)->exists()) {
    //             return response()->json(['error' => 'Akun pengembalian pembelian tidak valid.'], 400);
    //         }

    //         // Update debit note dengan invoice_id yang baru atau yang lama
    //         $debitNote->invoice_id = $invoice_id;
    //         $debitNote->account_id = $request->accountDebit;
    //         $debitNote->matauang_id = $request->currencyDebit;
    //         $debitNote->rate_currency = $request->rateCurrency;
    //         $debitNote->note = $request->noteDebit;
    //         $debitNote->total_keseluruhan = $request->totalKeseluruhan;
    //         $debitNote->save();

    //         $debitNote->items()->delete();

    //         // Kemudian buat item-item baru
    //         foreach ($request->items as $item) {
    //             $debitNote->items()->create([
    //                 'no_resi' => $item['noresi'],
    //                 'deskripsi' => $item['deskripsi'],
    //                 'harga' => $item['harga'],
    //                 'jumlah' => $item['jumlah'],
    //                 'total' => $item['total'],
    //             ]);
    //         }

    //         // Proses jurnal
    //         $jurnal = Jurnal::where('no_ref', $invoice_id)->first();
    //         if (!$jurnal) {
    //             $codeType = "DN";
    //             $currentYear = date('y');
    //             $lastDebitNote = DebitNote::where('no_debitnote', 'like', $codeType . $currentYear . '%')
    //                 ->orderBy('no_debitnote', 'desc')
    //                 ->first();

    //             $newSequence = $lastDebitNote ? intval(substr($lastDebitNote->no_debitnote, -4)) + 1 : 1;
    //             $newNoDebitNote = $codeType . $currentYear . str_pad($newSequence, 4, '0', STR_PAD_LEFT);

    //             $jurnal = new Jurnal();
    //             $jurnal->no_journal = $this->jurnalController->generateNoJurnal($request)->getData()->no_journal;
    //         }

    //         $jurnal->tipe_kode = 'DN';
    //         $jurnal->tanggal = now();
    //         $jurnal->no_ref = $invoice_id; // Menggunakan invoice_id yang sesuai
    //         $jurnal->status = 'Approve';
    //         $jurnal->description = "Jurnal untuk Invoice {$invoice_id}";
    //         $jurnal->totaldebit = $request->totalKeseluruhan;
    //         $jurnal->totalcredit = $request->totalKeseluruhan;
    //         $jurnal->save();

    //         // Update jurnal item untuk debit dan kredit
    //         JurnalItem::updateOrCreate(
    //             ['jurnal_id' => $jurnal->id, 'code_account' => $request->accountDebit],
    //             [
    //                 'description' => "Debit untuk Invoice {$invoice_id}",
    //                 'debit' => $request->totalKeseluruhan,
    //                 'credit' => 0,
    //             ]
    //         );

    //         JurnalItem::updateOrCreate(
    //             ['jurnal_id' => $jurnal->id, 'code_account' => $supplierPurchaseReturnAccountId],
    //             [
    //                 'description' => "Kredit untuk Invoice {$invoice_id}",
    //                 'debit' => 0,
    //                 'credit' => $request->totalKeseluruhan,
    //             ]
    //         );


    //         DB::commit();
    //         return response()->json(['message' => 'Debit note berhasil diperbarui!'], 200);

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json(['error' => 'Gagal memperbarui data: ' . $e->getMessage()], 500);
    //     }
    // }



}
