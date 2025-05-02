<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\AccountSettings;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\PaymentInvoice;
use App\Models\Invoice;
use App\Models\PaymentCustomerItems;
use App\Models\UsagePoints;
use Carbon\Carbon;
use App\Models\COA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Admin\JournalController;
use App\Models\Jurnal;
use App\Models\JurnalItem;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentExport;
use Log;

class PaymentController extends Controller
{
    protected $jurnalController;

    public function __construct(JournalController $jurnalController)
    {
        $this->jurnalController = $jurnalController;
    }

    public function index()
    {
        $listPayment = DB::table('tbl_payment_customer as pc')
            ->join('tbl_coa as coa', 'pc.payment_method_id', '=', 'coa.id')
            ->select('coa.name as payment_method')
            ->groupBy('coa.name')
            ->get();

        return view('customer.payment.indexpayment', [
            'listpayment' => $listPayment,

        ]);
    }

    public function addPayment()
    {

        $companyId = session('active_company_id');
        $savedPaymentAccounts = DB::table('tbl_payment_account')
        ->join('tbl_coa', 'tbl_payment_account.coa_id', '=', 'tbl_coa.id')
        ->select('tbl_payment_account.coa_id', 'tbl_coa.code_account_id', 'tbl_coa.name')
        ->get();

        $kuotaid = AccountSettings::query()
        ->select('purchase_profit_rate_account_id')
        ->first()
        ->purchase_profit_rate_account_id;

        $coas = COA::whereNotNull('parent_id')->get();

        $listInvoice = DB::select("SELECT no_invoice FROM tbl_invoice
                                    WHERE status_bayar = 'Belum lunas'");

        $listMarking = DB::select("SELECT id, nama_pembeli, marking FROM tbl_pembeli WHERE tbl_pembeli.company_id = $companyId");

        return view('customer.payment.buatpayment', [
            'listInvoice' => $listInvoice,
            'savedPaymentAccounts' => $savedPaymentAccounts,
            'listMarking' => $listMarking,
            'coas' => $coas,
            'kuotaid' => $kuotaid
        ]);
    }

    public function getPaymentData(Request $request)
    {
        $companyId = session('active_company_id');
        $query = DB::table('tbl_payment_customer as a')
            ->join('tbl_payment_invoice as f', 'f.payment_id', '=', 'a.id')
            ->join('tbl_invoice as b', 'f.invoice_id', '=', 'b.id')
            ->join('tbl_coa as c', 'a.payment_method_id', '=', 'c.id')
            ->join('tbl_pembeli as d', 'b.pembeli_id', '=', 'd.id')
            ->select(
                'a.id',
                'a.kode_pembayaran',
                'd.marking',
                'a.payment_buat',
                'a.payment_date',
                DB::raw("DATE_FORMAT(a.payment_buat, '%d %M %Y %H:%i:%s') as tanggal_buat"),
                DB::raw("DATE_FORMAT(a.payment_date, '%d %M %Y %H:%i:%s') as tanggal_payment"),
                'c.name as payment_method',
                DB::raw('SUM(f.amount) + IFNULL(a.discount, 0) as total_amount'),
                'a.discount',
                DB::raw("CONCAT(DATE_FORMAT(a.created_at, '%d %M %Y %H:%i:%s'), ' (', a.createdby, ')') as createdby"),
                DB::raw("CONCAT(DATE_FORMAT(a.updated_at, '%d %M %Y %H:%i:%s'), ' (', a.updateby, ')') as updateby")
            )
            ->where('a.company_id', $companyId)
            ->groupBy(
                'a.id',
                'a.kode_pembayaran',
                'd.marking',
                'a.payment_buat',
                'a.payment_date',
                DB::raw("DATE_FORMAT(a.payment_buat, '%d %M %Y %H:%i:%s')"),
                DB::raw("DATE_FORMAT(a.payment_date, '%d %M %Y %H:%i:%s')"),
                'c.name',
                'a.discount',
                DB::raw("CONCAT(DATE_FORMAT(a.created_at, '%d %M %Y %H:%i:%s'), ' (', a.createdby, ')')"),
                DB::raw("CONCAT(DATE_FORMAT(a.updated_at, '%d %M %Y %H:%i:%s'), ' (', a.updateby, ')')")
            );


        if (!empty($request->status)) {
            $query->where('c.name', $request->status);
        }

        if (!empty($request->startDate) && !empty($request->endDate)) {
            $startDate = date('Y-m-d', strtotime($request->startDate));
            $endDate = date('Y-m-d', strtotime($request->endDate));
            $query->whereBetween('a.payment_date', [$startDate, $endDate]);
        }

        $query->orderBy('a.id', 'desc');

        $payments = $query->get();

        return DataTables::of($payments)
            ->editColumn('payment_date', function ($row) {
                return $row->tanggal_buat;
            })
            ->addColumn('action', function ($row) {
                $periodStatus = DB::table('tbl_periode')
                    ->whereDate('periode_start', '<=', $row->payment_buat)
                    ->whereDate('periode_end', '>=', $row->payment_buat)
                    ->value('status');

                $btnDetail = '<a class="btn btnDetailPaymet btn-primary btn-sm mr-1" data-id="' . $row->id . '">
                                <i class="fas fa-eye text-white"></i>
                                <span class="text-white"> Detail</span>
                              </a>';

                if ($periodStatus == 'Closed') {
                    return $btnDetail;
                }

                $btnEdit = '<a class="btn btnEditPayment btn-sm btn-secondary text-white mr-1" data-id="' . $row->id . '">
                              <i class="fas fa-edit"></i>
                            </a>';

                $btnDelete = '<a class="btn btnDeletePayment btn-sm btn-danger text-white" data-id="' . $row->id . '">
                                <i class="fas fa-trash"></i>
                              </a>';

                return $btnDetail . $btnEdit . $btnDelete;
            })
            ->make(true);
    }





    public function getInvoiceAmount(Request $request)
    {
        $invoiceSelect = $request->no_invoice;
        if (!is_array($invoiceSelect)) {
            $invoiceSelect = [$invoiceSelect];
        }

        $invoices = DB::select("
            SELECT
                a.id,
                a.no_invoice,
                DATE_FORMAT(a.tanggal_invoice, '%d %M %Y') AS tanggal_bayar,
                FORMAT(a.total_harga, 0) AS total_harga,
                FORMAT(a.total_bayar, 0) AS total_bayar,
                FORMAT(a.total_harga - a.total_bayar, 0) AS sisa_bayar,
                (
                    SELECT SUM(r.berat)
                    FROM tbl_resi AS r
                    LEFT JOIN tbl_credit_note_item cni ON r.no_resi = cni.no_resi
                    LEFT JOIN tbl_credit_note cn ON cn.id = cni.credit_note_id
                    WHERE r.invoice_id = a.id
                    AND (cn.invoice_id IS NULL OR cn.invoice_id != a.id)
                ) AS total_berat,
                (
                    SELECT SUM(r.panjang * r.lebar * r.tinggi)
                    FROM tbl_resi AS r
                    WHERE r.invoice_id = a.id
                ) AS total_dimensi
            FROM
                tbl_invoice AS a
            WHERE
                a.no_invoice IN (" . implode(',', array_fill(0, count($invoiceSelect), '?')) . ")
        ", $invoiceSelect);


        // Jika tidak ada hasil, kembalikan error
        if (empty($invoices)) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice tidak ditemukan'
            ]);
        }

        $totalHarga = 0;
        $totalBayar = 0;
        $totalBerat = 0;
        $totalDimensi = 0;
        $noInvoices = [];

        foreach ($invoices as $invoice) {
            $totalHarga += str_replace(',', '', $invoice->total_harga);
            $totalBayar += str_replace(',', '', $invoice->total_bayar ?? 0);
            $totalBerat += $invoice->total_berat ?? 0;
            $totalDimensi += $invoice->total_dimensi ?? 0;
            $noInvoices[] = $invoice->no_invoice;
        }

        $sisaBayar = $totalHarga - $totalBayar;

        return response()->json([
            'success' => true,
            'summary' => [
                'total_harga' => number_format($totalHarga, 0, ',', '.'),
                'total_bayar' => number_format($totalBayar, 0, ',', '.'),
                'sisa_bayar' => number_format($sisaBayar, 0, ',', '.'),
                'total_berat' => $totalBerat,
                'total_dimensi' => $totalDimensi,
                'no_invoice' => implode(';', $noInvoices),
            ]
        ]);
    }


    public function getInvoiceDetail(Request $request)
    {
        $id = $request->id;
        $paymentCustomer = Payment::select(
                'pc.id AS payment_customer_id',
                'pc.kode_pembayaran',
                'pc.payment_date',
                'pc.payment_buat',
                'pc.payment_method_id',
                'pc.discount',
                'pc.Keterangan',
                DB::raw("GROUP_CONCAT(DISTINCT CONCAT(inv.no_invoice, '(', pi.amount, ')') SEPARATOR '; ') AS invoice_details"),
                DB::raw("GROUP_CONCAT(DISTINCT CONCAT(coa.name, '(', pitems.nominal, ') - ', pitems.description) SEPARATOR '; ') AS item_details")
            )
            ->from('tbl_payment_customer as pc')
            ->leftJoin('tbl_payment_invoice as pi', 'pc.id', '=', 'pi.payment_id')
            ->leftJoin('tbl_invoice as inv', 'pi.invoice_id', '=', 'inv.id')
            ->leftJoin('tbl_payment_items as pitems', 'pc.id', '=', 'pitems.payment_id')
            ->leftJoin('tbl_coa as coa', 'pitems.coa_id', '=', 'coa.id')
            ->where('pc.id', $id)
            ->groupBy(
                'pc.id',
                'pc.kode_pembayaran',
                'pc.payment_date',
                'pc.payment_buat',
                'pc.payment_method_id',
                'pc.discount',
                'pc.Keterangan'
            )
            ->first();

        // Cek jika data ditemukan
        if (!$paymentCustomer) {
            return response()->json([
                'success' => false,
                'message' => 'Payment customer not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $paymentCustomer,
        ]);
    }


    public function amountPoin(Request $request)
    {
        $invoiceNo = $request->invoiceNo;
        $amountPoin = $request->amountPoin;

        $invoice = DB::table('tbl_invoice')->where('no_invoice', $invoiceNo)->first();

        if (!$invoice) {
            return response()->json(['error' => 'Invoice tidak ditemukan'], 404);
        }

        $customerId = $invoice->pembeli_id;

        $topupRecords = DB::table('tbl_history_topup')
            ->where('customer_id', $customerId)
            ->where('status', 'active')
            ->where('balance', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        if ($topupRecords->isEmpty()) {
            return response()->json(['error' => 'Tidak ada saldo poin yang tersedia'], 400);
        }

        $remainingPoin = $amountPoin;
        $totalNominal = 0;

        foreach ($topupRecords as $topup) {
            if ($remainingPoin <= 0) {
                break;
            }

            $availablePoin = $topup->balance;

            if ($availablePoin >= $remainingPoin) {
                $totalNominal += $remainingPoin * $topup->price_per_kg;
                $remainingPoin = 0;
            } else {
                $totalNominal += $availablePoin * $topup->price_per_kg;
                $remainingPoin -= $availablePoin;
            }
        }

        if ($remainingPoin > 0) {
            return response()->json(['error' => 'Saldo poin tidak mencukupi'], 400);
        }

        return response()->json([
            'message' => 'Nominal berhasil dihitung.',
            'total_nominal' => $totalNominal
        ]);
    }






    public function store(Request $request)
    {
        $companyId = session('active_company_id');


        $validated = $request->validate([
            'invoice' => 'required|array',
            'kode' => 'required|string',
            'tanggalPayment' => 'required',
            'tanggalPaymentBuat' => 'required',
            'paymentAmount' => 'required|numeric',
            'discountPayment' => 'nullable|numeric',
            'paymentMethod' => 'required|integer',
            'amountPoin' => 'nullable|numeric',
            'keterangan' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.account' => 'required|integer',
            'items.*.item_desc' => 'required|string',
            'items.*.nominal' => 'required|numeric',
            'items.*.tipeAccount' => 'required',
        ]);

        // dd($request->all());

        if (
            $request->amountPoin === null
        ) {
            return $this->processNormalPayment($request);
        }

        DB::beginTransaction();
        try {
            Log::info("Memulai ketika ada kondisi poin atau discount dengan jurnal manual");


            $accountSettings = DB::table('tbl_account_settings')->first();
            if (!$accountSettings) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Silakan cek Account setting untuk mengatur pemilihan Account.',
                ], 400);
            }

            $salesAccountId = $accountSettings->receivable_sales_account_id;
            $paymentMethodId = $request->paymentMethod;
            $receivableSalesAccount = COA::find($paymentMethodId);
            $poinMarginAccount = $accountSettings->discount_sales_account_id;
            $idMarking = isset($request->marking) ? explode(';', $request->marking)[1] : null;
            $currentPointPrice = DB::select("SELECT nilai_rate FROM tbl_rate WHERE rate_for = 'Topup'");

            if (empty($currentPointPrice)) {
                Log::error("Rate for 'Topup' not found.");
                return response()->json(['status' => 'error', 'message' => 'Rate for Topup not found.']);
            }

            $currentPointPrice = $currentPointPrice[0]->nilai_rate;
            if (is_null($salesAccountId) || is_null($receivableSalesAccount) || is_null($poinMarginAccount)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Silakan cek Account setting untuk mengatur pemilihan Account.',
                ], 400);
            }


            $noRef = implode(', ', $request->invoice);
            $tanggalPayment = Carbon::createFromFormat('d F Y H:i', $request->tanggalPayment);
            $date = Carbon::createFromFormat('d F Y H:i', $request->tanggalPaymentBuat);
            $formattedDateTime = $date->format('Y-m-d H:i:s');
            $payment = new Payment();
            $payment->kode_pembayaran = $request->kode;
            $payment->pembeli_id =  $idMarking;
            $payment->payment_date = $tanggalPayment;
            $payment->payment_buat = $formattedDateTime;
            $payment->payment_method_id = $paymentMethodId;
            $payment->Keterangan = $request->keterangan;
            $payment->discount = 0;
            $payment->createdby = Auth::user()->name;
            $payment->company_id = $companyId;
            $payment->save();

            $invoiceList = Invoice::whereIn('no_invoice', $request->invoice)->get();
            $totalSisaTagihan = $invoiceList->sum(fn($invoice) => max(0, $invoice->total_harga - $invoice->total_bayar));

            $nilaiPayment = $request->paymentAmount;
            $nilaiPoin = $request->amountPoin;

            // **STEP 1: Ambil saldo poin pelanggan dari top-up history**
            $topups = DB::table('tbl_history_topup')
                ->where('customer_id', $invoiceList->first()->pembeli_id)
                ->where('balance', '>', 0)
                ->orderBy('created_at', 'asc')
                ->get();

            $totalUsedPoin = 0;
            $totalNominal = 0;
            $remainingPoin = $nilaiPoin;

            foreach ($topups as $topup) {
                if ($remainingPoin <= 0) break;

                if ($topup->balance >= $remainingPoin) {
                    $nominal = $remainingPoin * $topup->price_per_kg;
                    $totalNominal += $nominal;
                    $totalUsedPoin += $remainingPoin;

                    DB::table('tbl_history_topup')->where('id', $topup->id)->decrement('balance', $remainingPoin);

                    UsagePoints::create([
                        'customer_id' => $invoiceList->first()->pembeli_id,
                        'history_topup_id' => $topup->id,
                        'used_points' => $remainingPoin,
                        'price_per_kg' => $topup->price_per_kg,
                        'payment_id'=> $payment->id,
                        'usage_date' => now(),
                    ]);

                    $remainingPoin = 0;
                } else {
                    $nominal = $topup->balance * $topup->price_per_kg;
                    $totalNominal += $nominal;
                    $totalUsedPoin += $topup->balance;

                    DB::table('tbl_history_topup')->where('id', $topup->id)->update(['balance' => 0]);

                    UsagePoints::create([
                        'customer_id' => $invoiceList->first()->pembeli_id,
                        'history_topup_id' => $topup->id,
                        'used_points' => $topup->balance,
                        'price_per_kg' => $topup->price_per_kg,
                        'payment_id'=> $payment->id,
                        'usage_date' => now(),
                    ]);

                    $remainingPoin -= $topup->balance;
                }
            }

            $remainingPoin = $totalUsedPoin;
            $remainingPayment = $nilaiPayment;
            $totalPaid = 0;

            foreach ($invoiceList as $invoice) {
                $paymentInvoice = new PaymentInvoice();
                $paymentInvoice->payment_id = $payment->id;
                $paymentInvoice->invoice_id = $invoice->id;

                $sisaTagihan = max(0, $invoice->total_harga - $invoice->total_bayar);
                if ($sisaTagihan <= 0) continue;

                $proporsi = $sisaTagihan / $totalSisaTagihan;

                $allocatedPoin = min($remainingPoin, $proporsi * $totalUsedPoin);
                $remainingPoin -= $allocatedPoin;

                $allocatedPayment = min($remainingPayment, $sisaTagihan - $allocatedPoin);
                $remainingPayment -= $allocatedPayment;

                $allocatedAmount = $allocatedPoin + $allocatedPayment;
                $totalPaid += $allocatedAmount;

                $paymentInvoice->amount = $allocatedAmount;
                $paymentInvoice->kuota = $allocatedPoin;
                $paymentInvoice->save();

                $invoice->total_bayar += $allocatedAmount;

                $totalBeratInvoice = DB::table('tbl_resi')
                    ->where('invoice_id', $invoice->id)
                    ->sum('berat');

                $totalTagihanInvoice = $invoice->total_harga;

                $totalBeratDibayar = $totalUsedPoin;
                $totalNominalDibayar = $nilaiPayment;

                $beratLunas = ($totalBeratDibayar >= $totalBeratInvoice);
                $uangLunas = ($invoice->total_bayar >= $totalTagihanInvoice);

                if ($beratLunas || $uangLunas) {
                    $invoice->total_bayar = $invoice->total_harga;
                    $invoice->status_bayar = 'Lunas';
                } else {
                    $invoice->status_bayar = 'Belum Lunas';
                }
                $invoice->save();
            }

            // **STEP 3: Hitung margin error**
            // $expectedTotalPayment = $totalUsedPoin + $nilaiPayment;
            $marginError = $totalSisaTagihan - $nilaiPayment;

            $invoiceNumbers = is_array($request->invoice) ? implode(', ', $request->invoice) : $request->invoice;

            $request->merge(['code_type' => 'BKM']);
            $noJournal = $this->jurnalController->generateNoJurnal($request)->getData()->no_journal;

            Log::info("Generated Journal Number", ['noJournal' => $noJournal]);
            $jurnal = new Jurnal();
            $jurnal->no_journal = $noJournal;
            $jurnal->payment_id = $payment->id;
            $jurnal->tipe_kode = 'BKM';
            $jurnal->tanggal = $formattedDateTime;
            $jurnal->tanggal_payment = $tanggalPayment;
            $jurnal->no_ref = $payment->kode_pembayaran;
            $jurnal->status = 'Approve';
            $jurnal->description = "Jurnal untuk Payment " . $payment->kode_pembayaran;
            $jurnal->totaldebit = $totalSisaTagihan;
            $jurnal->totalcredit = $totalSisaTagihan;
            $jurnal->company_id = $companyId;
            $jurnal->save();

            $journalItems = [];

            $journalItems = [];

            if ($marginError > 0) {
                $journalItems[] = [
                    'code_account' => $salesAccountId,
                    'description' => "Kredit untuk Payment " . $payment->kode_pembayaran,
                    'credit' => $totalSisaTagihan,
                    'debit' => 0,
                ];
                $journalItems[] = [
                    'code_account' => $paymentMethodId,
                    'description' => "Debit untuk Payment " . $payment->kode_pembayaran,
                    'credit' => 0,
                    'debit' => $nilaiPayment,
                ];
                $journalItems[] = [
                    'code_account' => $poinMarginAccount,
                    'description' => "Margin untuk Payment " . $payment->kode_pembayaran,
                    'credit' => 0,
                    'debit' => $marginError,
                ];
            } elseif ($marginError < 0) {
                $journalItems[] = [
                    'code_account' => $salesAccountId,
                    'description' => "Kredit untuk Payment " . $payment->kode_pembayaran,
                    'credit' => $totalSisaTagihan,
                    'debit' => 0,
                ];
                $journalItems[] = [
                    'code_account' => $paymentMethodId,
                    'description' => "Debit untuk Payment " . $payment->kode_pembayaran,
                    'credit' => 0,
                    'debit' => $nilaiPayment,
                ];
                $journalItems[] = [
                    'code_account' => $poinMarginAccount,
                    'description' => "Lebih Bayar (Diskon) untuk Payment " . $payment->kode_pembayaran,
                    'credit' => abs($marginError),
                    'debit' => 0,
                ];
            } else {
                // Jika margin error 0 (pembayaran pas)
                Log::info("Case: Point Margin is Zero");
                $journalItems[] = [
                    'code_account' => $salesAccountId,
                    'description' => "Debit untuk Payment " .  $payment->kode_pembayaran,
                    'debit' => $totalSisaTagihan,
                    'credit' => 0,
                ];
                $journalItems[] = [
                    'code_account' => $paymentMethodId,
                    'description' => "Kredit untuk Payment " . $payment->kode_pembayaran,
                    'debit' => 0,
                    'credit' => $nilaiPayment,
                ];
            }

            // Simpan item jurnal ke database
            foreach ($journalItems as $item) {
                JurnalItem::create([
                    'jurnal_id' => $jurnal->id,
                    'code_account' => $item['code_account'],
                    'description' => $item['description'],
                    'debit' => $item['debit'],
                    'credit' => $item['credit'],

                ]);
            }

                // $finalTotal = $totalNominal + $totalPayment + $poinMargin;

                // $invoice = Invoice::where('no_invoice', $request->invoice)->firstOrFail();
                // if ($finalTotal > $invoice->total_harga) {
                //     Log::warning("Total bayar melebihi total harga untuk invoice {$invoice->no_invoice}");
                //     DB::rollBack();
                //     return response()->json([
                //         'status' => 'error',
                //         'message' => 'Total bayar tidak dapat melebihi total harga.'
                //     ], 400);
                // }
                // $invoice->total_bayar = $finalTotal;
                // $invoice->status_bayar = ($invoice->total_bayar == $invoice->total_harga) ? 'Lunas' : 'Belum Lunas';
                // $invoice->save();
                DB::table('tbl_pembeli')->where('id', $invoice->pembeli_id)->decrement('sisa_poin', $totalUsedPoin);

                if ($request->has('items') && is_array($request->items)) {
                    $items = $request->input('items');

                    $totalDebit = 0;
                    $totalCredit = 0;

                    foreach ($items as $item) {
                        if ($item['tipeAccount'] == 'Debit') {
                            $totalDebit += $item['nominal'];
                        } elseif ($item['tipeAccount'] == 'Credit') {
                            $totalCredit += $item['nominal'];
                        }
                    }

                    foreach ($items as $item) {
                        $jurnalItem = new JurnalItem();
                        $jurnalItem->jurnal_id = $jurnal->id;
                        $jurnalItem->code_account = $item['account'];
                        $jurnalItem->description = $item['item_desc'];

                        if ($item['tipeAccount'] === 'Debit') {
                            $jurnalItem->debit = $item['nominal'];
                            $jurnalItem->credit = 0;
                            $totalJurnalAmount -= $item['nominal'];

                        } elseif ($item['tipeAccount'] === 'Credit') {
                            $jurnalItem->debit = 0;
                            $jurnalItem->credit = $item['nominal'];
                            $totalJurnalAmount += $item['nominal'];

                        }

                        $jurnalItemDebit->debit = $totalJurnalAmount;
                        $jurnal->totaldebit = $totalJurnalAmount + ($request->discountPayment ?? 0) +  $totalDebit ;
                        $jurnal->totalcredit = $totalJurnalAmount + ($request->discountPayment ?? 0) +  $totalDebit;
                        $jurnal->save();
                        $jurnalItemDebit->save();
                        $jurnalItem->save();

                        PaymentCustomerItems::create([
                            'payment_id' => $payment->id,
                            'coa_id' => $item['account'],
                            'description' => $item['item_desc'],
                            'nominal' => $item['nominal'],
                            'tipe' => $item['tipeAccount'],
                            'jurnal_item_id' => $jurnalItem->id,
                        ]);
                    }
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran berhasil diproses.'
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Terjadi kesalahan saat memproses pembayaran', ['error' => $e->getMessage()]);
                throw new \Exception('Terjadi kesalahan saat memproses pembayaran');
            }
    }

    private function processNormalPayment($request)
    {

        $companyId = session('active_company_id');

        Log::info('Mulai proses pembayaran normal', ['request' => $request->all()]);

        $accountSettings = DB::table(table: 'tbl_account_settings')->first();

        if (!$accountSettings) {
            Log::error('Account settings tidak ditemukan.');
            return response()->json([
                'status' => 'error',
                'message' => 'Silakan cek Account setting untuk mengatur pemilihan Account.',
            ], 400);
        }

        $salesAccountId = $accountSettings->receivable_sales_account_id;
        $paymentMethodId = $request->paymentMethod;
        $receivableSalesAccount = COA::find($paymentMethodId);
        $paymentDiscountAccount = $accountSettings->sales_profit_rate_account_id;

        //   dd($request->all());

        if (is_null($salesAccountId) || is_null($receivableSalesAccount) ||  is_null($paymentDiscountAccount) ) {
            Log::error('Akun pengaturan tidak lengkap.');
            return response()->json([
                'status' => 'error',
                'message' => 'Silakan cek Account setting untuk mengatur pemilihan Account.',
            ], 400);
        }

        DB::beginTransaction();
        try {

            $idMarking = isset($request->marking) ? explode(';', $request->marking)[1] : null;
            $tanggalPayment = Carbon::createFromFormat('d F Y H:i', $request->tanggalPayment);
            $totalPayment = $request->totalAmmount;
            $fullPaymentAmount = $request->paymentAmount ?? $totalPayment;
            $date = Carbon::createFromFormat('d F Y H:i', $request->tanggalPaymentBuat);
            $formattedDateTime = $date->format('Y-m-d H:i:s');
            $hasDiscount = isset($request->discountPayment) && $request->discountPayment > 0;

            // Buat payment record
            $payment = new Payment();
            $payment->kode_pembayaran = $request->kode;
            $payment->pembeli_id = $idMarking;
            $payment->payment_date = $tanggalPayment;
            $payment->payment_buat = $formattedDateTime;
            $payment->payment_method_id = $paymentMethodId;
            $payment->discount = $request->discountPayment ?? 0;
            $payment->Keterangan = $request->keterangan;
            $payment->company_id = $companyId;
            $payment->createdby = Auth::user()->name;
            $payment->save();

            $invoiceList = [];
            $remainingPayment = $totalPayment;
            $remainingFullAmount = $fullPaymentAmount;

            foreach ($request->invoice as $noInvoice) {
                $invoice = Invoice::where('no_invoice', $noInvoice)->firstOrFail();
                $invoiceUnpaidAmount = $invoice->total_harga - $invoice->total_bayar;
                $invoiceDiscount = 0;
                if ($hasDiscount) {
                    $invoiceRatio = $invoice->total_harga / array_sum(array_map(function($inv) {
                        return Invoice::where('no_invoice', $inv)->first()->total_harga;
                    }, $request->invoice));
                    $invoiceDiscount = $request->discountPayment * $invoiceRatio;
                }

                $allocatedAmount = min($remainingPayment, ($invoiceUnpaidAmount - $invoiceDiscount));
                $fullAllocatedAmount = $allocatedAmount + $invoiceDiscount;

                if ($allocatedAmount <= 0) {
                    Log::info("Invoice {$noInvoice} sudah lunas.");
                    continue;
                }
                $paymentInvoice = new PaymentInvoice();
                $paymentInvoice->payment_id = $payment->id;
                $paymentInvoice->invoice_id = $invoice->id;
                $paymentInvoice->amount = $allocatedAmount;
                $paymentInvoice->kuota = 0.00;
                $paymentInvoice->save();
                $invoice->total_bayar += $fullAllocatedAmount;
                $invoice->status_bayar = $invoice->total_bayar >= $invoice->total_harga ? 'Lunas' : 'Belum lunas';
                $invoice->save();

                $remainingPayment -= $allocatedAmount;
                $remainingFullAmount -= $fullAllocatedAmount;
                $invoiceList[] = $invoice->no_invoice;

                if ($remainingPayment <= 0) {
                    break;
                }
            }

            if ($remainingPayment > 0.01) { // Anggap sisa < 1 sen tidak penting
                Log::warning("Sisa dana pembayaran tidak teralokasi: {$remainingPayment}");
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ada sisa pembayaran yang tidak teralokasi.'
                ], 400);
            }


            $noRef = implode(', ', $request->invoice);

            if ($totalPayment > 0) {
                Log::warning("Sisa dana pembayaran tidak teralokasi: {$totalPayment}");
            }

            $request->merge(['code_type' => 'BKM']);
            $noJournal = $this->jurnalController->generateNoJurnal($request)->getData()->no_journal;

            $jurnal = new Jurnal();
            $jurnal->no_journal = $noJournal;
            $jurnal->payment_id = $payment->id;
            $jurnal->tipe_kode = 'BKM';
            $jurnal->tanggal = $formattedDateTime;
            $jurnal->tanggal_payment = $tanggalPayment;
            $jurnal->no_ref = $payment->kode_pembayaran;
            $jurnal->status = 'Approve';
            $jurnal->description = "Jurnal untuk payment: " . $payment->kode_pembayaran;
            $totalJurnalAmount = $request->totalAmmount;
            $jurnal->totaldebit = $request->paymentAmount;
            $jurnal->totalcredit = $request->paymentAmount;
            $jurnal->company_id = $companyId;
            $jurnal->save();

            $jurnalItemDebit = new JurnalItem();
            $jurnalItemDebit->jurnal_id = $jurnal->id;
            $jurnalItemDebit->code_account = $receivableSalesAccount->id;
            $jurnalItemDebit->description = "Debit untuk payment: " . $payment->kode_pembayaran;
            $jurnalItemDebit->debit = $totalJurnalAmount;
            $jurnalItemDebit->credit = 0;
            $jurnalItemDebit->save();

            Log::info('Jurnal item debit berhasil ditambahkan.');

            $jurnalItemCredit = new JurnalItem();
            $jurnalItemCredit->jurnal_id = $jurnal->id;
            $jurnalItemCredit->code_account = $salesAccountId;
            $jurnalItemCredit->description = "Kredit untuk payment: " . $payment->kode_pembayaran;
            $jurnalItemCredit->debit = 0;
            $jurnalItemCredit->credit = $request->paymentAmount;
            $jurnalItemCredit->save();

            Log::info('Jurnal item kredit berhasil ditambahkan.');

            if ($request->discountPayment) {
                $jurnalItemDiscount = new JurnalItem();
                $jurnalItemDiscount->jurnal_id = $jurnal->id;
                $jurnalItemDiscount->code_account = $paymentDiscountAccount;
                $jurnalItemDiscount->description = "Diskon untuk payment: " . $payment->kode_pembayaran;
                $jurnalItemDiscount->debit = $request->discountPayment;
                $jurnalItemDiscount->credit = 0;
                $jurnalItemDiscount->save();
                Log::info('Jurnal item diskon berhasil ditambahkan.');
            }

            if ($request->has('items') && is_array($request->items)) {
                $items = $request->input('items');

                $totalDebit = 0;
                $totalCredit = 0;

                foreach ($items as $item) {
                    if ($item['tipeAccount'] == 'Debit') {
                        $totalDebit += $item['nominal'];
                    } elseif ($item['tipeAccount'] == 'Credit') {
                        $totalCredit += $item['nominal'];
                    }
                }

                foreach ($items as $item) {
                    $jurnalItem = new JurnalItem();
                    $jurnalItem->jurnal_id = $jurnal->id;
                    $jurnalItem->code_account = $item['account'];
                    $jurnalItem->description = $item['item_desc'];

                    if ($item['tipeAccount'] === 'Debit') {
                        $jurnalItem->debit = $item['nominal'];
                        $jurnalItem->credit = 0;
                        $totalJurnalAmount -= $item['nominal'];

                    } elseif ($item['tipeAccount'] === 'Credit') {
                        $jurnalItem->debit = 0;
                        $jurnalItem->credit = $item['nominal'];
                        $totalJurnalAmount += $item['nominal'];

                    }

                    $jurnalItemDebit->debit = $totalJurnalAmount;
                    $jurnal->totaldebit = $totalJurnalAmount + ($request->discountPayment ?? 0) +  $totalDebit ;
                    $jurnal->totalcredit = $totalJurnalAmount + ($request->discountPayment ?? 0) +  $totalDebit;
                    $jurnal->save();
                    $jurnalItemDebit->save();
                    $jurnalItem->save();

                    PaymentCustomerItems::create([
                        'payment_id' => $payment->id,
                        'coa_id' => $item['account'],
                        'description' => $item['item_desc'],
                        'nominal' => $item['nominal'],
                        'tipe' => $item['tipeAccount'],
                        'jurnal_item_id' => $jurnalItem->id,
                    ]);
                }
            }


            Log::info('Jurnal item list berhasil ditambahkan.');

            DB::commit();
            Log::info('Pembayaran berhasil diproses.');
            return response()->json(['success' => true, 'message' => 'Payments successfully created and invoices updated']);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Terjadi kesalahan saat memproses pembayaran', ['error' => $e->getMessage()]);
            throw new \Exception('Terjadi kesalahan saat memproses pembayaran');
        }
    }



    public function export(Request $request)
    {
        return Excel::download(new PaymentExport(
            $request->status,
            $request->startDate,
            $request->endDate
        ), 'Payment Customers.xlsx');
    }



    public function generateKodePembayaran()
    {
        $codeType = "BO";
        $currentYear = date('y');

        $lastPayment = Payment::where('kode_pembayaran', 'like', $codeType . $currentYear . '%')
            ->orderBy('kode_pembayaran', 'desc')
            ->first();

        $newSequence = 1;
        if ($lastPayment) {
            $lastSequence = intval(substr($lastPayment->kode_pembayaran, -4));
            $newSequence = $lastSequence + 1;
        }

        $kode_pembayaran = $codeType . $currentYear . str_pad($newSequence, 4, '0', STR_PAD_LEFT);

        return response()->json([
            'status' => 'success',
            'kode_pembayaran' => $kode_pembayaran
        ]);
    }


    public function getInvoiceByMarking(Request $request)
    {
        $marking = $request->input('marking');
        $id = $request->input('id');
        $invoices = Invoice::join('tbl_pembeli', 'tbl_pembeli.id', '=', 'tbl_invoice.pembeli_id')
            ->where('tbl_pembeli.marking', $marking)
            ->where('tbl_invoice.status_bayar', 'Belum lunas')
            ->select('tbl_invoice.no_invoice')
            ->get();

        if ($invoices->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No invoices found for this marking.']);
        }

        return response()->json([
            'success' => true,
            'invoices' => $invoices
        ]);
    }
    public function editpayment($id)
    {
        // Load payment data with related invoices and customer items
        $payment = Payment::with([
            'paymentInvoices',
            'paymentCustomerItems',
            'paymentMethod',
            'pembeli' => function ($query) {
                $query->withTrashed();
            }
        ])->findOrFail($id);

        // Fetch saved payment accounts with related COA information
        $savedPaymentAccounts = DB::table('tbl_payment_account')
            ->join('tbl_coa', 'tbl_payment_account.coa_id', '=', 'tbl_coa.id')
            ->select('tbl_payment_account.coa_id', 'tbl_coa.code_account_id', 'tbl_coa.name')
            ->get();

        $kuotaid = AccountSettings::query()
            ->select('purchase_profit_rate_account_id')
            ->first()
            ->purchase_profit_rate_account_id;

        // Fetch list of unpaid invoices
        $listInvoice = Invoice::where('status_bayar', 'Belum lunas')->pluck('no_invoice');

        // Fetch list of buyers and markings
        $listMarking = DB::table('tbl_pembeli')->select('nama_pembeli', 'marking')->get();

        // Fetch all COA records
        $coas = COA::whereNotNull('parent_id')->get();

        return view('customer.payment.editpayment', [
            'payment' => $payment,
            'listInvoice' => $listInvoice,
            'savedPaymentAccounts' => $savedPaymentAccounts,
            'listMarking' => $listMarking,
            'coas' => $coas,
            'kuotaid' => $kuotaid,
        ]);
    }



    public function getInvoiceByMarkingEdit(Request $request)
    {
        $marking = $request->input('marking');
        $invoiceIds = $request->input('invoiceIds');

        $noInvoices = Invoice::whereIn('id', $invoiceIds)
        ->pluck('no_invoice');
        if ($noInvoices->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'invoices' => $noInvoices
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No invoices found.'
        ]);
    }


    public function update(Request $request)
    {


        //   dd($request->all());
        $request->validate([
            'paymentId' => 'required|integer|exists:tbl_jurnal,payment_id',
            'invoice' => 'required|array|min:1',
            'invoice.*' => 'string|distinct',
            // 'marking' => 'required|string|max:4',
            'tanggalPayment' => 'required|',
            'tanggalPaymentBuat' => 'required',
            'paymentAmount' => 'required|numeric|min:0',
            'discountPayment' => 'nullable|numeric|min:0',
            'paymentMethod' => 'required|integer',
            'amountPoin' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
            'totalAmmount' => 'required|numeric|min:0',
            'items' => 'nullable|array|min:1',
            'items.*.account' => 'required|integer',
            'items.*.tipeAccount' => 'required|in:Debit,Credit',
            'items.*.item_desc' => 'required|string|max:255',
            'items.*.nominal' => 'required|numeric|min:0',
            '_token' => 'required|string',
        ]);
        Log::info('Mulai proses pembayaran normal.', ['request' => $request->all()]);

        try {
            $accountSettings = DB::table('tbl_account_settings')->first();
            Log::info('Berhasil mendapatkan account settings.', ['accountSettings' => $accountSettings]);

            if (!$accountSettings) {
                Log::error('Account settings tidak ditemukan.');
                return response()->json([
                    'status' => 'error',
                    'message' => 'Silakan cek Account setting untuk mengatur pemilihan Account.',
                ], 400);
            }

            $salesAccountId = $accountSettings->receivable_sales_account_id;
            $paymentMethodId = $request->paymentMethod;
            $receivableSalesAccount = COA::find($paymentMethodId);
            $paymentDiscountAccount = $accountSettings->sales_profit_rate_account_id;
            $poinMarginAccount = $accountSettings->discount_sales_account_id;

            Log::info('Cek akun pengaturan.', [
                'salesAccountId' => $salesAccountId,
                'paymentMethodId' => $paymentMethodId,
                'receivableSalesAccount' => $receivableSalesAccount,
                'paymentDiscountAccount' => $paymentDiscountAccount,
            ]);

            if (is_null($salesAccountId) || is_null($receivableSalesAccount) || is_null($paymentDiscountAccount)) {
                Log::error('Akun pengaturan tidak lengkap.');
                return response()->json([
                    'status' => 'error',
                    'message' => 'Silakan cek Account setting untuk mengatur pemilihan Account.',
                ], 400);
            }

            $currentPointPrice = DB::select("SELECT nilai_rate FROM tbl_rate WHERE rate_for = 'Topup'");

            if (empty($currentPointPrice)) {
                Log::error("Rate for 'Topup' not found.");
                return response()->json(['status' => 'error', 'message' => 'Rate for Topup not found.']);
            }

            $currentPointPrice = $currentPointPrice[0]->nilai_rate;
            if (is_null($salesAccountId) || is_null($receivableSalesAccount) || is_null($poinMarginAccount)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Silakan cek Account setting untuk mengatur pemilihan Account.',
                ], 400);
            }

            DB::beginTransaction();

            $payment = Payment::findOrFail($request->paymentId);
            Log::info('Berhasil mendapatkan data payment.', ['payment' => $payment]);

            $tanggalPayment = Carbon::createFromFormat('d F Y H:i', $request->tanggalPayment);
            Log::info('Tanggal payment berhasil diformat.', ['tanggalPayment' => $tanggalPayment]);

            $tanggalPaymentBuat = Carbon::createFromFormat('d F Y H:i', $request->tanggalPaymentBuat);

            $payment->update([
                'kode_pembayaran' => $request->kode,
                'payment_date' => $tanggalPayment,
                'payment_buat' => $tanggalPaymentBuat,
                'payment_method_id' => $request->paymentMethod,
                'discount' => $request->discountPayment ?? 0,
                'Keterangan' => $request->keterangan,
                'updateby' => Auth::user()->name,
            ]);

            $payment->updateby = Auth::user()->name;
            $payment->save();
            Log::info('Payment berhasil diperbarui.', ['payment' => $payment]);

            $oldPaymentInvoices = PaymentInvoice::where('payment_id', $payment->id)->get();
            Log::info('Berhasil mendapatkan PaymentInvoices lama.', ['oldPaymentInvoices' => $oldPaymentInvoices]);

            foreach ($oldPaymentInvoices as $oldPaymentInvoice) {
                $oldInvoice = Invoice::findOrFail($oldPaymentInvoice->invoice_id);
                $oldInvoice->total_bayar -= $oldPaymentInvoice->amount;
                $oldInvoice->status_bayar = $oldInvoice->total_bayar >= $oldInvoice->total_harga ? 'Lunas' : 'Belum lunas';
                $oldInvoice->save();
                Log::info('Invoice lama berhasil diperbarui.', ['oldInvoice' => $oldInvoice]);
            }

            // PaymentInvoice::where('payment_id', $payment->id)->delete();
            // Log::info('PaymentInvoice lama berhasil dihapus.');

            $totalPayment = $request->paymentAmount - ($request->discountPayment ?? 0);
            Log::info('Proses alokasi payment dimulai.', ['totalPayment' => $totalPayment]);

            foreach ($request->invoice as $noInvoice) {
                $invoice = Invoice::where('no_invoice', $noInvoice)->firstOrFail();
                Log::info('Berhasil mendapatkan data invoice.', ['invoice' => $invoice]);

                $remainingAmount = $invoice->total_harga - $invoice->total_bayar;
                $allocatedAmount = min($totalPayment, $remainingAmount);

                if ($allocatedAmount <= 0) continue;
                $totalTagihanInvoice = $remainingAmount;
                $kuota = 0;
                if (!is_null($request->amountPoin)) {
                    $kuota = $allocatedAmount / $currentPointPrice;
                }
                PaymentInvoice::updateOrCreate(
                    ['payment_id' => $payment->id, 'invoice_id' => $invoice->id],
                    [
                        'amount' => $allocatedAmount,
                       'kuota' => $kuota
                    ]
                );

                $invoice->total_bayar += $allocatedAmount;
                $invoice->status_bayar = $invoice->total_bayar >= $invoice->total_harga ? 'Lunas' : 'Belum lunas';
                $invoice->save();
                Log::info('Invoice berhasil diperbarui.', ['invoice' => $invoice, 'allocatedAmount' => $allocatedAmount]);

                $totalPayment -= $allocatedAmount;
            }
            if (abs($totalPayment) > 0.00001) {
                Log::error('Sisa dana melebihi jumlah yang harus dibayar.', ['sisaDana' => $totalPayment]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pembayaran tidak dapat diproses karena sisa dana melebihi jumlah yang harus dibayar.',
                ], 400);
            }

            $noRef = implode(', ', $request->invoice);
            $jurnal = Jurnal::where('payment_id', $request->paymentId)->firstOrFail();

            $jurnal->update([
                'tanggal' => $tanggalPayment,
                'no_ref' => "Jurnal untuk Payment " . $payment->kode_pembayaran,
                'description' => "Jurnal untuk Payment " . $payment->kode_pembayaran,
                'totaldebit' => $request->totalAmmount,
                'totalcredit' => $request->totalAmmount,
            ]);
            $totalJurnalAmount = $request->totalAmmount;

            DB::table('tbl_payment_items')->where('payment_id', $payment->id)->delete();
            JurnalItem::where('jurnal_id', $jurnal->id)->delete();
            if ($request->amountPoin) {
                $invoice = Invoice::where('no_invoice', $request->invoice)->firstOrFail();
                $paymentMethodId = $request->paymentMethod;

                $topups = DB::table('tbl_history_topup')
                    ->where('customer_id', $invoice->pembeli_id)
                    ->where('balance', '>', 0)
                    ->orderBy('created_at', 'asc')
                    ->get();

                $totalUsedPoin = 0;
                $totalNominal = 0;
                $remainingPoin = $request->amountPoin;



                foreach ($topups as $topup) {
                    if ($remainingPoin <= 0) break;

                    if ($topup->balance >= $remainingPoin) {
                        $nominal = $remainingPoin * $topup->price_per_kg;
                        $totalNominal += $nominal;
                        $totalUsedPoin += $remainingPoin;

                        DB::table('tbl_history_topup')->where('id', $topup->id)->decrement('balance', $remainingPoin);

                        UsagePoints::create([
                            'customer_id' => $invoice->pembeli_id,
                            'history_topup_id' => $topup->id,
                            'used_points' => $remainingPoin,
                            'price_per_kg' => $topup->price_per_kg,
                            'usage_date' => now(),
                        ]);

                        $remainingPoin = 0;
                    } else {
                        $nominal = $topup->balance * $topup->price_per_kg;
                        $totalNominal += $nominal;
                        $totalUsedPoin += $topup->balance;

                        DB::table('tbl_history_topup')->where('id', $topup->id)->update(['balance' => 0]);

                        UsagePoints::create([
                            'customer_id' => $invoice->pembeli_id,
                            'history_topup_id' => $topup->id,
                            'used_points' => $topup->balance,
                            'price_per_kg' => $topup->price_per_kg,
                            'usage_date' => now(),
                        ]);

                        $remainingPoin -= $topup->balance;
                    }
                }

                if ($remainingPoin > 0) {
                    Log::error("Remaining points after topup insufficient", ['remainingPoin' => $remainingPoin]);
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Poin tidak mencukupi untuk pembayaran ini.'
                    ], 400);
                }

                $newNominal = $totalUsedPoin * $currentPointPrice;

                $poinMargin = $newNominal - $totalNominal;

                Log::info("Margin Poin Calculated", [
                    'totalUsedPoin' => $totalUsedPoin,
                    'currentPointPrice' => $currentPointPrice,
                    'newNominal' => $newNominal,
                    'totalNominal' => $totalNominal,
                    'poinMargin' => $poinMargin,
                ]);

                $invoiceNumbers = is_array($request->invoice) ? implode(', ', $request->invoice) : $request->invoice;

                $journalItems = [];

                if ($poinMargin > 0) {
                    $journalItems[] = [
                        'code_account' => $salesAccountId,
                        'description' => "Debit untuk Invoice " . $invoiceNumbers,
                        'debit' => $totalTagihanInvoice,
                        'credit' => 0,
                    ];
                    $journalItems[] = [
                        'code_account' => $paymentMethodId,
                        'description' => "Kredit untuk Invoice " . $invoiceNumbers,
                        'debit' => 0,
                        'credit' => $newNominal,
                    ];
                    $journalItems[] = [
                        'code_account' => $accountSettings->discount_sales_account_id,
                        'description' => "Margin Poin Positif untuk Invoice " . $invoiceNumbers,
                        'debit' => $poinMargin,
                        'credit' => 0,
                    ];
                } elseif ($poinMargin < 0) {
                    $journalItems[] = [
                        'code_account' => $salesAccountId,
                        'description' => "Debit untuk Invoice " . $invoiceNumbers,
                        'debit' => $totalTagihanInvoice + abs($poinMargin),
                        'credit' => 0,
                    ];
                    $journalItems[] = [
                        'code_account' => $paymentMethodId,
                        'description' => "Kredit untuk Invoice " . $invoiceNumbers,
                        'debit' => 0,
                        'credit' => $newNominal,
                    ];
                } elseif ($poinMargin == 0) {
                    Log::info("Case: Point Margin is Zero");
                    $journalItems[] = [
                        'code_account' => $salesAccountId,
                        'description' => "Debit untuk Invoice " .  $invoiceNumbers,
                        'debit' => $totalNominal,
                        'credit' => 0,
                    ];
                    $journalItems[] = [
                        'code_account' => $paymentMethodId,
                        'description' => "Kredit untuk Invoice " . $invoiceNumbers,
                        'debit' => 0,
                        'credit' => $totalNominal,
                    ];
                }

                // Simpan item jurnal
                foreach ($journalItems as $item) {
                    JurnalItem::create([
                        'jurnal_id' => $jurnal->id,
                        'code_account' => $item['code_account'],
                        'description' => $item['description'],
                        'debit' => $item['debit'],
                        'credit' => $item['credit']
                    ]);
                }

                // $finalTotal = $totalNominal + $totalPayment + $poinMargin;

                // $invoice = Invoice::where('no_invoice', $request->invoice)->firstOrFail();
                // if ($finalTotal > $invoice->total_harga) {
                //     Log::warning("Total bayar melebihi total harga untuk invoice {$invoice->no_invoice}");
                //     DB::rollBack();
                //     return response()->json([
                //         'status' => 'error',
                //         'message' => 'Total bayar tidak dapat melebihi total harga.'
                //     ], 400);
                // }
                // $invoice->total_bayar = $finalTotal;
                // $invoice->status_bayar = ($invoice->total_bayar == $invoice->total_harga) ? 'Lunas' : 'Belum Lunas';
                // $invoice->save();
                DB::table('tbl_pembeli')->where('id', $invoice->pembeli_id)->decrement('sisa_poin', $totalUsedPoin);
            }



            $jurnalItemDebit = new JurnalItem();
            $jurnalItemDebit->jurnal_id = $jurnal->id;
            $jurnalItemDebit->code_account = $receivableSalesAccount->id;
            $jurnalItemDebit->description = "Debit untuk Invoices: " . $noRef;
            $jurnalItemDebit->debit = $totalJurnalAmount;
            $jurnalItemDebit->credit = 0;
            $jurnalItemDebit->save();

            Log::info('Jurnal item debit berhasil ditambahkan.');

            $jurnalItemCredit = new JurnalItem();
            $jurnalItemCredit->jurnal_id = $jurnal->id;
            $jurnalItemCredit->code_account = $salesAccountId;
            $jurnalItemCredit->description = "Kredit untuk Invoices: " . $noRef;
            $jurnalItemCredit->debit = 0;
            $jurnalItemCredit->credit = $request->paymentAmount;
            $jurnalItemCredit->save();

            Log::info('Jurnal item kredit berhasil ditambahkan.');

            if ($request->discountPayment) {
                $jurnalItemDiscount = new JurnalItem();
                $jurnalItemDiscount->jurnal_id = $jurnal->id;
                $jurnalItemDiscount->code_account = $paymentDiscountAccount;
                $jurnalItemDiscount->description = "Diskon untuk Invoices: " . $noRef;
                $jurnalItemDiscount->debit = $request->discountPayment;
                $jurnalItemDiscount->credit = 0;
                $jurnalItemDiscount->save();
                Log::info('Jurnal item diskon berhasil ditambahkan.');
            }

            if ($request->has('items') && is_array($request->items)) {
                $items = $request->input('items');

                $totalDebit = 0;
                $totalCredit = 0;

                foreach ($items as $item) {
                    if ($item['tipeAccount'] == 'Debit') {
                        $totalDebit += $item['nominal'];
                    } elseif ($item['tipeAccount'] == 'Credit') {
                        $totalCredit += $item['nominal'];
                    }
                }

                foreach ($items as $item) {
                    $jurnalItem = new JurnalItem();
                    $jurnalItem->jurnal_id = $jurnal->id;
                    $jurnalItem->code_account = $item['account'];
                    $jurnalItem->description = $item['item_desc'];

                    if ($item['tipeAccount'] === 'Debit') {
                        $jurnalItem->debit = $item['nominal'];
                        $jurnalItem->credit = 0;
                        $totalJurnalAmount -= $item['nominal'];

                    } elseif ($item['tipeAccount'] === 'Credit') {
                        $jurnalItem->debit = 0;
                        $jurnalItem->credit = $item['nominal'];
                        $totalJurnalAmount += $item['nominal'];

                    }

                    $jurnalItemDebit->debit = $totalJurnalAmount;
                    $jurnal->totaldebit = $totalJurnalAmount + ($request->discountPayment ?? 0) +  $totalDebit ;
                    $jurnal->totalcredit = $totalJurnalAmount + ($request->discountPayment ?? 0) +  $totalDebit;
                    $jurnal->save();
                    $jurnalItemDebit->save();
                    $jurnalItem->save();

                    PaymentCustomerItems::create([
                        'payment_id' => $payment->id,
                        'coa_id' => $item['account'],
                        'description' => $item['item_desc'],
                        'nominal' => $item['nominal'],
                        'tipe' => $item['tipeAccount'],
                        'jurnal_item_id' => $jurnalItem->id,
                    ]);
                }
            }

            DB::commit();
            Log::info('Proses update payment selesai.');
            return response()->json(['success' => true, 'message' => 'Payment berhasil diperbarui.']);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error saat mengupdate payment.', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan saat mengupdate payment.']);
        }
    }

    public function deletePayment($id)
    {
        $payment = Payment::find($id);
        $paymentId = $id;

        if (!$payment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment tidak ditemukan.'
            ], 404);
        }

        DB::beginTransaction();

        try {
            $paymentInvoices = PaymentInvoice::where('payment_id', $id)->get();

            foreach ($paymentInvoices as $paymentInvoice) {
                $invoice = Invoice::find($paymentInvoice->invoice_id);

                if ($invoice) {
                    $invoice->total_bayar -= $paymentInvoice->amount;
                    // Jika ada diskon, kurangi juga dari total_bayar
                    if (!empty($payment->discount)) {
                        $invoice->total_bayar -= $payment->discount;
                    }

                    // Pastikan total_bayar tidak menjadi negatif
                    if ($invoice->total_bayar < 0) {
                        $invoice->total_bayar = 0;
                    }
                    if ($invoice->total_bayar < 0) {
                        $invoice->total_bayar = 0;
                    }
                    $invoice->status_bayar = ($invoice->total_bayar >= $invoice->total_harga) ? 'Lunas' : 'Belum Lunas';
                    $invoice->save();

                    $customerId = $invoice->pembeli_id;
                }

                if ($paymentInvoice->kuota > 0) {
                    if (isset($customerId)) {
                        $usagePoints = UsagePoints::where('payment_id', $paymentId)->get();

                        foreach ($usagePoints as $usagePoint) {
                            // Kembalikan balance di tbl_history_topup berdasarkan usagePoint yang digunakan
                            DB::table('tbl_history_topup')
                                ->where('id', $usagePoint->history_topup_id)
                                ->increment('balance', $usagePoint->used_points);

                            Log::info("Balance dikembalikan: " . $usagePoint->used_points . " untuk history_topup_id: " . $usagePoint->history_topup_id);
                        }

                        UsagePoints::where('payment_id', $paymentId)->delete();
                    }

                    DB::table('tbl_pembeli')
                        ->where('id', $customerId)
                        ->increment('sisa_poin', $paymentInvoice->kuota);
                }
            }

            PaymentInvoice::where('payment_id', $id)->delete();

            $jurnalIds = Jurnal::where('payment_id', $id)->pluck('id');
            JurnalItem::whereIn('jurnal_id', $jurnalIds)->delete();
            Jurnal::where('payment_id', $id)->delete();

            PaymentCustomerItems::where('payment_id', $id)->delete();
            $payment->delete();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Payment berhasil dihapus dan status invoice diperbarui.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus payment: ' . $e->getMessage()
            ], 500);
        }
    }





}
