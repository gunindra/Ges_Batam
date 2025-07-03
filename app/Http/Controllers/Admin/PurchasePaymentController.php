<?php

namespace App\Http\Controllers\Admin;
use App\Exports\PaymentSupExport;
use App\Http\Controllers\Controller;
use App\Models\COA;
use App\Models\Jurnal;
use App\Models\JurnalItem;
use App\Models\PaymentSup;
use App\Models\PaymentSupInvoice;
use App\Models\SupInvoice;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Admin\JournalController;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class PurchasePaymentController extends Controller
{

    protected $jurnalController;

    public function __construct(JournalController $jurnalController)
    {
        $this->jurnalController = $jurnalController;
    }

    public function index()
    {
        $listPayment = DB::table('tbl_payment_sup as pc')
        ->join('tbl_coa as coa', 'pc.payment_method_id', '=', 'coa.id')
        ->select('coa.name as payment_method')
        ->groupBy('coa.name')
        ->get();

        return view('vendor.purchasepayment.indexpurchasepayment', [
            'listPayment' => $listPayment,
        ]);
    }


    public function getPaymentSupData(Request $request)
    {
        $companyId = session('active_company_id');
        $search = strtolower($request->search['value']);
        $query = DB::table('tbl_payment_sup as a')
        ->where('a.company_id', $companyId)
        ->join('tbl_payment_invoice_sup as d', 'a.id', '=', 'd.payment_id')
        ->join('tbl_sup_invoice as b', 'd.invoice_id', '=', 'b.id')
        ->join('tbl_coa as c', 'a.payment_method_id', '=', 'c.id')
        ->select([
            'a.id',
            'a.kode_pembayaran',
            'a.payment_date',
            'a.tanggal_buat',
            DB::raw("DATE_FORMAT(a.payment_date, '%d %M %Y') as tanggal_bayar"),
            DB::raw("DATE_FORMAT(a.tanggal_buat, '%d %M %Y') as tgl_buat"),
            'c.name as payment_method',
           DB::raw("SUM(d.amount) as total_amount")
        ])
        ->where(function ($query) use ($search) {
            $query->whereRaw("LOWER(a.kode_pembayaran) LIKE ?", ["%$search%"])
                ->orWhereRaw("LOWER(c.name) LIKE ?", ["%$search%"])
                ->orWhereRaw("DATE_FORMAT(a.payment_date, '%d %M %Y') LIKE ?", ["%$search%"]);
        })
        ->groupBy('a.id', 'a.kode_pembayaran', 'a.payment_date','a.tanggal_buat', 'c.name')
        ->havingRaw("CAST(SUM(d.amount) AS CHAR) LIKE ?", ["%$search%"]);


        if (!empty($request->status)) {
            $query->where('c.name', $request->status);
        }

        if (!empty($request->startDate) && !empty($request->endDate)) {
         $startDate = date('Y-m-d', strtotime($request->startDate));
         $endDate = date('Y-m-d', strtotime($request->endDate));
         $query->whereBetween('a.payment_date', [$startDate, $endDate]);
        }


        if (!$request->has('order')) {
            $query->orderBy('id', 'desc');
        } else {
            $order = $request->order[0];
            $column = $request->columns[$order['column']]['data'];
            $direction = $order['dir'];

            $query->orderBy($column, $direction);
        }


        return DataTables::of($query)
        ->addColumn('action', function ($row) {
            $periodStatus = DB::table('tbl_periode')
                ->whereDate('periode_start', '<=', $row->payment_date)
                ->whereDate('periode_end', '>=', $row->payment_date)
                ->value('status');

            $btnDetail = '<a class="btn btnviewPaymentDetails btn-primary btn-sm mr-1" data-id="' . $row->id . '">
                            <i class="fas fa-eye text-white"></i>
                            <span class="text-white ml-1">Detail</span>
                          </a>';

            if ($periodStatus == 'Closed') {
                return $btnDetail;
            }

            $btnEdit = '<a class="btn btnEditPaymentPurchase btn-sm btn-secondary text-white" data-id="' . $row->id . '">
                          <i class="fas fa-edit"></i>
                        </a>';

            $btnDelete = '<a class="btn btnDeletePurchasePayment btn-sm btn-danger ml-1 text-white" data-id="' . $row->id . '">
                            <i class="fas fa-trash"></i>
                        </a>';

            return $btnDetail . $btnEdit . $btnDelete;
        })
        ->rawColumns(['action'])
        ->make(true);
    }




    public function addPurchasePayment()
    {
        $companyId = session('active_company_id');
        $coas = COA::whereNotNull('parent_id')
            ->where('set_as_group', 0)
            ->get();

        $listInvoice = SupInvoice::where('status_bayar', 'Belum lunas')
            ->select('invoice_no')
            ->get();

        $listVendor = Vendor::where('company_id', $companyId)
            ->pluck('name', 'id');

        return view('vendor.purchasepayment.buatpurchasepayment', [
            'listInvoice' => $listInvoice,
            'coas' => $coas,
            'listVendor' => $listVendor
        ]);
    }


    public function getSupInvoiceAmount(Request $request)
    {
        $invoiceSelect = $request->input('no_invoice');

        if (!$invoiceSelect || !is_array($invoiceSelect)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or missing invoice numbers',
            ], 400);
        }

        $invoices = DB::table('tbl_sup_invoice')
            ->select([
                'invoice_no',
                DB::raw("FORMAT(total_harga, 0) AS total_harga"),
                DB::raw("FORMAT(total_bayar, 0) AS total_bayar"),
                DB::raw("FORMAT(total_harga - total_bayar, 0) AS sisa_bayar"),
            ])
            ->whereIn('invoice_no', $invoiceSelect)
            ->get();

        if ($invoices->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Invoices not found',
            ]);
        }

        $invoiceNumbers = $invoices->pluck('invoice_no')->implode(';');
        $totalHarga = $invoices->sum(fn($item) => str_replace(',', '', $item->total_harga));
        $totalBayar = $invoices->sum(fn($item) => str_replace(',', '', $item->total_bayar));
        $sisaBayar = $invoices->sum(fn($item) => str_replace(',', '', $item->sisa_bayar));

        return response()->json([
            'success' => true,
            'data' => [
                'invoice_numbers' => $invoiceNumbers,
                'total_harga' => number_format($totalHarga, 0, ',', '.'),
                'total_bayar' => number_format($totalBayar, 0, ',', '.'),
                'sisa_bayar' => number_format($sisaBayar, 0, ',', '.'),
            ],
        ]);
    }


    public function getInoviceByVendor(Request $request)
    {
        // // Get all request data
        // dd($request->all());

        $idVendor = $request->input('idVendor');
        $invoiceIds = $request->input('invoiceIds');
        // dd($invoiceIds);// Assuming invoiceIds is passed as an array

        if (!$idVendor) {
            return response()->json([
                'success' => false,
                'message' => 'Vendor ID is required',
            ], 400);
        }

        if ($invoiceIds && count($invoiceIds) > 0) {
            $invoiceIds = PaymentSupInvoice::whereIn('invoice_id', $invoiceIds)
                ->pluck('invoice_id');

            $invoices = SupInvoice::whereIn('id', $invoiceIds)
                ->get(['id', 'invoice_no']);

        } else {
            $invoices = SupInvoice::where('vendor_id', $idVendor)
                ->where('status_bayar', 'Belum lunas')
                ->get(['invoice_no']);
        }

        if ($invoices->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No invoices found for this vendor',
            ]);
        }

        return response()->json([
            'success' => true,
            'invoices' => $invoices,
        ]);
    }




    public function store(Request $request)
    {

        //   dd($request->all());
        $companyId = session('active_company_id');
        // Validasi input
        $validated = $request->validate([
            'invoice' => 'required|array',
            // 'invoice.*' => 'required|string',
            'tanggalPayment' => 'required|date',
            'tanggalPaymentBuat' => 'required|date',
            'paymentAmount' => 'required|numeric',
            'paymentMethod' => 'required|integer',
            'keteranganPaymentSup' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.account' => 'required|integer',
            'items.*.item_desc' => 'required|string',
            'items.*.debit' => 'required|numeric',
            'items.*.tipeAccount' => 'required',
        ]);


        DB::beginTransaction();

        try {
            $invoice = SupInvoice::where('invoice_no', $request->invoice)->firstOrFail();

            $vendor = Vendor::findOrFail($invoice->vendor_id);
            $vendorAccountId = $vendor->account_id;

            if (!$vendorAccountId) {
                throw new \Exception('Akun vendor tidak ditemukan.');
            }

            $tanggalPayment = Carbon::createFromFormat('d F Y H:i', $request->tanggalPayment);
            $date = Carbon::createFromFormat('d F Y H:i', $request->tanggalPaymentBuat);
            $formattedDateTime = $date->format('Y-m-d H:i:s');
            $codeType = "VP";
            $currentYear = date('y');
            $lastPayment = PaymentSup::where('kode_pembayaran', 'like', $codeType . $currentYear . '%')
                ->orderBy('kode_pembayaran', 'desc')
                ->first();

            $currentYear = date('y');
            $lastSequence = PaymentSup::where('kode_pembayaran', 'like', $codeType . $currentYear . '%')
                ->max(DB::raw('CAST(SUBSTR(kode_pembayaran, -4) AS UNSIGNED)'));

            $newSequence = $lastSequence ? $lastSequence + 1 : 1;
            $newKodePembayaran = sprintf('%s%s%04d', $codeType, $currentYear, $newSequence);

            $payment = new PaymentSup();
            $payment->payment_date = $tanggalPayment;
            $payment->tanggal_buat = $formattedDateTime;
            $payment->payment_method_id = $request->paymentMethod;
            $payment->kode_pembayaran = $newKodePembayaran;
            $payment->Keterangan = $request->keteranganPaymentSup;
            $payment->company_id = $companyId;

            Log::info('Saving PaymentSup data', [
                'payment_date' => $tanggalPayment,
                'payment_method_id' => $request->paymentMethod,
                'kode_pembayaran' => $newKodePembayaran,
                'keterangan' => $request->keteranganPaymentSup,
            ]);

            $payment->save();
            Log::info('PaymentSup data saved successfully', [
                'id' => $payment->id,
                'kode_pembayaran' => $payment->kode_pembayaran,
            ]);


            $totalPayment = $request->paymentAmount;
            $invoiceList = [];
            foreach ($request->invoice as $noInvoice) {
                $invoice = SupInvoice::where('invoice_no', $noInvoice)->firstOrFail();
                $remainingAmount = $invoice->total_harga - $invoice->total_bayar;

                if ($remainingAmount <= 0) {
                    Log::info("Invoice {$noInvoice} sudah lunas.");
                    continue;
                }

                $allocatedAmount = min($totalPayment, $remainingAmount);
                DB::table('tbl_payment_invoice_sup')->insert([
                    'payment_id' => $payment->id,
                    'invoice_id' => $invoice->id,
                    'amount' => $allocatedAmount,
                ]);

                $invoice->total_bayar += $allocatedAmount;
                $invoice->status_bayar = $invoice->total_bayar >= $invoice->total_harga ? 'Lunas' : 'Belum lunas';
                $invoice->save();

                $totalPayment -= $allocatedAmount;
                $invoiceList[] = $noInvoice;

                if ($totalPayment <= 0) {
                    break;
                }
            }

            if ($totalPayment > 0) {
                // throw new \Exception("Sisa dana pembayaran tidak teralokasi: {$totalPayment}");
                return response()->json([
                    'status' => 'error',
                    'message' => 'Total pembayaran melebihi total harga invoice.'
                ], 400);
            }

            $noRef = implode(', ', $request->invoice);

                $request->merge(['code_type' => 'BKK']);
                $noJournal = $this->jurnalController->generateNoJurnal($request)->getData()->no_journal;

                $jurnal = new Jurnal();
                $jurnal->no_journal = $noJournal;
                $jurnal->payment_id_sup = $payment->id;
                $jurnal->tipe_kode = 'BKK';
                $jurnal->tanggal = $formattedDateTime;
                $jurnal->tanggal_payment = $tanggalPayment;
                $jurnal->no_ref = $noRef;
                $jurnal->status = 'Approve';
                $jurnal->description = "Jurnal untuk Invoice {$noRef}";
                $totalJurnalAmount = $request->paymentAmount;
                $jurnal->totaldebit = $request->paymentAmount;
                $jurnal->totalcredit = $request->paymentAmount;
                $jurnal->company_id = $companyId;
                Log::info('Payment id data:', [
                    'id' => $payment->id,
                ]);

                $jurnal->save();

                $jurnalItemDebit = new JurnalItem();
                $jurnalItemDebit->jurnal_id = $jurnal->id;
                $jurnalItemDebit->code_account = $vendorAccountId;
                $jurnalItemDebit->description = "Debit untuk Invoice {$noRef}";
                $jurnalItemDebit->debit = $totalJurnalAmount;
                $jurnalItemDebit->credit = 0;
                $jurnalItemDebit->save();

                $jurnalItemCredit = new JurnalItem();
                $jurnalItemCredit->jurnal_id = $jurnal->id;
                $jurnalItemCredit->code_account = $request->paymentMethod;
                $jurnalItemCredit->description = "Kredit untuk Invoice {$noRef}";
                $jurnalItemCredit->debit = 0;
                $jurnalItemCredit->credit = $totalJurnalAmount;
                $jurnalItemCredit->save();

                if ($request->has('items') && is_array($request->items)) {
                    $items = $request->input('items');

                    $totalAdditionalDebit = 0;
                    $totalAdditionalCredit = 0;

                    foreach ($items as $item) {
                        $jurnalItem = new JurnalItem();
                        $jurnalItem->jurnal_id = $jurnal->id;
                        $jurnalItem->code_account = $item['account'];
                        $jurnalItem->description = $item['item_desc'];

                        if ($item['tipeAccount'] === 'Debit') {
                            $jurnalItem->debit = $item['debit'];
                            $jurnalItem->credit = 0;
                            $totalAdditionalDebit += $item['debit'];

                        } elseif ($item['tipeAccount'] === 'Credit') {
                            $jurnalItem->debit = 0;
                            $jurnalItem->credit = $item['debit'];
                            $totalAdditionalCredit += $item['debit'];
                        }

                        $jurnalItem->save();

                        DB::table('tbl_payment_sup_items')->insert([
                            'payment_id' => $payment->id,
                            'coa_id' => $item['account'],
                            'description' => $item['item_desc'],
                            'nominal' => $item['debit'],
                            'tipeAccount' => $item['tipeAccount'],
                        ]);
                    }

                    // Hitung selisih setelah menambahkan item baru
                    $currentDebit = $jurnalItemDebit->debit + $totalAdditionalDebit;
                    $currentCredit = $jurnalItemCredit->credit + $totalAdditionalCredit;
                    $balanceDifference = $currentDebit - $currentCredit;

                    // Sesuaikan balance
                    if ($balanceDifference > 0) {
                        // Jika debit lebih besar, tambahkan ke kredit utama
                        $jurnalItemCredit->credit += $balanceDifference;
                        $jurnalItemCredit->save();
                    } elseif ($balanceDifference < 0) {
                        // Jika kredit lebih besar, tambahkan ke debit utama
                        $jurnalItemDebit->debit += abs($balanceDifference);
                        $jurnalItemDebit->save();
                    }

                    // Update total di jurnal utama
                    $jurnal->totaldebit = $jurnalItemDebit->debit + $totalAdditionalDebit;
                    $jurnal->totalcredit = $jurnalItemCredit->credit + $totalAdditionalCredit;
                    $jurnal->save();
                }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Payment successfully created and invoice updated']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Terjadi Kesalahan', 'error' => $e->getMessage()]);
        }
    }




    public function export(Request $request)
    {
        return Excel::download(new PaymentSupExport(
            $request->status,
            $request->startDate,
            $request->endDate
        ), 'Payment_Suppiler.xlsx');
    }

    public function getInvoiceSupDetail(Request $request)
    {
        $id = $request->id;

        $paymentSup = PaymentSup::select(
            'pc.id AS payment_sup_id',
            'pc.Keterangan',
            'pc.kode_pembayaran',
            DB::raw("GROUP_CONCAT(DISTINCT CONCAT(inv.invoice_no, '(', pi.amount, ')') SEPARATOR '; ') AS invoice_details"),
            DB::raw("GROUP_CONCAT(DISTINCT CONCAT(coa.name, '(', pitems.nominal, ') - ', pitems.description) SEPARATOR '; ') AS item_details")
        )
            ->from('tbl_payment_sup as pc')
            ->where('pc.id', $id)
            ->leftJoin('tbl_payment_invoice_sup as pi', 'pc.id', '=', 'pi.payment_id')
            ->leftJoin('tbl_sup_invoice as inv', 'pi.invoice_id', '=', 'inv.id')
            ->leftJoin('tbl_payment_sup_items as pitems', 'pc.id', '=', 'pitems.payment_id')
            ->leftJoin('tbl_coa as coa', 'pitems.coa_id', '=', 'coa.id')
            ->groupBy(
                'pc.id',
                'pc.Keterangan',
                'pc.kode_pembayaran'
            )
            ->first();

        if (!$paymentSup) {
            return response()->json([
                'success' => false,
                'message' => 'Payment Vendor not found.',
            ], 404);
        }

        // Jika payment ditemukan, kirimkan response dengan datanya
        return response()->json([
            'success' => true,
            'data' => $paymentSup
        ]);
    }
    public function editpurchasepayment($id)
    {
        $payment = PaymentSup::with(['paymentInvoicesSup', 'paymentSupItem'])->findOrFail($id);
        $coas = COA::whereNotNull('parent_id')
            ->where('set_as_group', 0)
            ->get();
        $listInvoice = SupInvoice::where('status_bayar', 'Belum lunas')
            ->select('invoice_no')
            ->get();
        $selectedVendorId = $payment->selectVendor;

        $listVendor = Vendor::select('id', 'name')
            ->get();


        return view('vendor.purchasepayment.editpurchasepayment', [
            'payment' => $payment,
            'coas' => $coas,
            'listInvoice' => $listInvoice,
            'listVendor' => $listVendor,
            'selectedVendorId' => $selectedVendorId
        ]);
    }


    public function update(Request $request)
    {
        // Debug the incoming request
        // dd($request->all());

        $validated = $request->validate([
            'paymentId' => 'required|integer',
            'invoice' => 'required|array',
            'invoice.*' => 'string',
            'tanggalPayment' => 'required|date_format:d F Y',
            'paymentAmount' => 'required|numeric',
            'paymentMethod' => 'required|integer',
            'keteranganPaymentSup' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.account' => 'required|integer',
            'items.*.item_desc' => 'required|string',
            'items.*.debit' => 'required|numeric',
            'items.*.tipeAccount' => 'required|in:Debit,Credit', // Added validation
            'totalAmmount' => 'required|numeric'
        ]);

        DB::beginTransaction();

        try {
            $invoice = SupInvoice::where('invoice_no', $request->invoice[0])->firstOrFail();
            $vendor = Vendor::findOrFail($invoice->vendor_id);
            $vendorAccountId = $vendor->account_id;

            $payment = PaymentSup::findOrFail($request->paymentId);
            $oldInvoices = DB::table('tbl_payment_invoice_sup')->where('payment_id', $payment->id)->get();

            // Rollback previous payments
            foreach ($oldInvoices as $oldInvoice) {
                $invoice = SupInvoice::findOrFail($oldInvoice->invoice_id);
                $invoice->total_bayar -= $oldInvoice->amount;
                $invoice->status_bayar = $invoice->total_bayar >= $invoice->total_harga ? 'Lunas' : 'Belum lunas';
                $invoice->save();
            }

            DB::table('tbl_payment_invoice_sup')->where('payment_id', $payment->id)->delete();

            // Fix date parsing (without time)
            $tanggalPayment = Carbon::createFromFormat('d F Y', $request->tanggalPayment);

            $payment->payment_date = $tanggalPayment;
            $payment->payment_method_id = $request->paymentMethod;
            $payment->Keterangan = $request->keteranganPaymentSup;
            $payment->save();

            $totalPayment = (float)$request->paymentAmount;
            foreach ($request->invoice as $noInvoice) {
                $invoice = SupInvoice::where('invoice_no', $noInvoice)->firstOrFail();
                $remainingAmount = $invoice->total_harga - $invoice->total_bayar;

                if ($remainingAmount <= 0) {
                    Log::info("Invoice {$noInvoice} sudah lunas.");
                    continue;
                }

                $allocatedAmount = min($totalPayment, $remainingAmount);
                DB::table('tbl_payment_invoice_sup')->insert([
                    'payment_id' => $payment->id,
                    'invoice_id' => $invoice->id,
                    'amount' => $allocatedAmount,
                ]);

                $invoice->total_bayar += $allocatedAmount;
                $invoice->status_bayar = $invoice->total_bayar >= $invoice->total_harga ? 'Lunas' : 'Belum lunas';
                $invoice->save();

                $totalPayment -= $allocatedAmount;

                if ($totalPayment <= 0) {
                    break;
                }
            }

            if ($totalPayment > 0) {
                throw new \Exception("Sisa dana pembayaran tidak teralokasi: {$totalPayment}");
            }

            $noRef = implode(', ', $request->invoice);

            // Update journal
            $jurnal = Jurnal::where('payment_id_sup', $payment->id)->firstOrFail();
            $jurnal->update([
                'tanggal' => $tanggalPayment,
                'no_ref' => $noRef,
                'totaldebit' => (float)$request->totalAmmount,
                'totalcredit' => (float)$request->totalAmmount,
            ]);

            $totalJurnalAmount = (float)$request->paymentAmount;

            JurnalItem::where('jurnal_id', $jurnal->id)->delete();
            DB::table('tbl_payment_sup_items')->where('payment_id', $payment->id)->delete();

            // Vendor entry (Debit)
            $jurnalItemDebit = new JurnalItem();
            $jurnalItemDebit->jurnal_id = $jurnal->id;
            $jurnalItemDebit->code_account = $vendorAccountId;
            $jurnalItemDebit->description = "Debit untuk Invoice {$noRef}";
            $jurnalItemDebit->debit = $totalJurnalAmount;
            $jurnalItemDebit->credit = 0;
            $jurnalItemDebit->save();

            // Payment method entry (Credit)
            $jurnalItemCredit = new JurnalItem();
            $jurnalItemCredit->jurnal_id = $jurnal->id;
            $jurnalItemCredit->code_account = $request->paymentMethod;
            $jurnalItemCredit->description = "Kredit untuk Invoice {$noRef}";
            $jurnalItemCredit->debit = 0;
            $jurnalItemCredit->credit = $totalJurnalAmount;
            $jurnalItemCredit->save();

            // Additional items handling

            if ($request->has('items') && is_array($request->items)) {
                $items = $request->input('items');

                $totalAdditionalDebit = 0;
                $totalAdditionalCredit = 0;

                foreach ($items as $item) {
                    $jurnalItem = new JurnalItem();
                    $jurnalItem->jurnal_id = $jurnal->id;
                    $jurnalItem->code_account = $item['account'];
                    $jurnalItem->description = $item['item_desc'];

                    if ($item['tipeAccount'] === 'Debit') {
                        $jurnalItem->debit = $item['debit'];
                        $jurnalItem->credit = 0;
                        $totalAdditionalDebit += $item['debit'];

                    } elseif ($item['tipeAccount'] === 'Credit') {
                        $jurnalItem->debit = 0;
                        $jurnalItem->credit = $item['debit'];
                        $totalAdditionalCredit += $item['debit'];
                    }

                    $jurnalItem->save();

                    DB::table('tbl_payment_sup_items')->insert([
                        'payment_id' => $payment->id,
                        'coa_id' => $item['account'],
                        'description' => $item['item_desc'],
                        'nominal' => $item['debit'],
                        'tipeAccount' => $item['tipeAccount'],
                    ]);
                }

                // Hitung selisih setelah menambahkan item baru
                $currentDebit = $jurnalItemDebit->debit + $totalAdditionalDebit;
                $currentCredit = $jurnalItemCredit->credit + $totalAdditionalCredit;
                $balanceDifference = $currentDebit - $currentCredit;

                // Sesuaikan balance
                if ($balanceDifference > 0) {
                    // Jika debit lebih besar, tambahkan ke kredit utama
                    $jurnalItemCredit->credit += $balanceDifference;
                    $jurnalItemCredit->save();
                } elseif ($balanceDifference < 0) {
                    // Jika kredit lebih besar, tambahkan ke debit utama
                    $jurnalItemDebit->debit += abs($balanceDifference);
                    $jurnalItemDebit->save();
                }

                // Update total di jurnal utama
                $jurnal->totaldebit = $jurnalItemDebit->debit + $totalAdditionalDebit;
                $jurnal->totalcredit = $jurnalItemCredit->credit + $totalAdditionalCredit;
                $jurnal->save();
            }


            DB::commit();
            return response()->json(['success' => true, 'message' => 'Payment successfully updated']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Payment update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi Kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $paymentId = $id;

        DB::beginTransaction();

        try {
            $payment = PaymentSup::findOrFail($paymentId);

            $paymentInvoices = DB::table('tbl_payment_invoice_sup')
                ->where('payment_id', $paymentId)
                ->get();

            foreach ($paymentInvoices as $paymentInvoice) {
                $invoice = SupInvoice::findOrFail($paymentInvoice->invoice_id);
                $invoice->total_bayar -= $paymentInvoice->amount;
                $invoice->status_bayar = $invoice->total_bayar >= $invoice->total_harga ? 'Lunas' : 'Belum lunas';
                $invoice->save();
            }

            DB::table('tbl_payment_invoice_sup')->where('payment_id', $paymentId)->delete();

            JurnalItem::whereIn('jurnal_id', function ($query) use ($paymentId) {
                $query->select('id')->from('tbl_jurnal')->where('payment_id_sup', $paymentId);
            })->delete();

            Jurnal::where('payment_id_sup', $paymentId)->delete();

            $payment->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Payment successfully deleted and invoice updated'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error deleting payment',
                'error' => $e->getMessage()
            ], 500); // Pastikan ini ada, agar AJAX menangkapnya sebagai error
        }
    }



}
