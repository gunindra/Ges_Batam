<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\PaymentInvoice;
use App\Models\Invoice;
use App\Models\PaymentCustomerItems;
use App\Models\UsagePoints;
use Carbon\Carbon;
use App\Models\COA;
use Illuminate\Http\Request;
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
        $savedPaymentAccounts = DB::table('tbl_payment_account')
        ->join('tbl_coa', 'tbl_payment_account.coa_id', '=', 'tbl_coa.id')
        ->select('tbl_payment_account.coa_id', 'tbl_coa.code_account_id', 'tbl_coa.name')
        ->get();

        $coas = COA::all();

        $listInvoice = DB::select("SELECT no_invoice FROM tbl_invoice
                                    WHERE status_bayar = 'Belum Lunas'");

        $listMarking = DB::select("SELECT nama_pembeli, marking FROM tbl_pembeli");

        return view('customer.payment.buatpayment', [
            'listInvoice' => $listInvoice,
            'savedPaymentAccounts' => $savedPaymentAccounts,
            'listMarking' => $listMarking,
            'coas' => $coas
        ]);
    }

    public function getPaymentData(Request $request)
    {
            $query = DB::table('tbl_payment_customer as a')
            ->join('tbl_payment_invoice as f', 'f.payment_id', '=', 'a.id')
            ->join('tbl_invoice as b', 'f.invoice_id', '=', 'b.id')
            ->join('tbl_coa as c', 'a.payment_method_id', '=', 'c.id')
            ->join('tbl_pembeli as d', 'b.pembeli_id', '=', 'd.id')
            ->select(
                'a.id',
                'a.kode_pembayaran',
                'd.marking',
                DB::raw("DATE_FORMAT(a.payment_buat, '%d %M %Y %H:%i:%s') as tanggal_buat"),
                'c.name as payment_method',
                DB::raw('SUM(f.amount) as total_amount'),
                'a.discount'
            )
            ->groupBy(
                'a.id',
                'a.kode_pembayaran',
                'd.marking',
                DB::raw("DATE_FORMAT(a.payment_buat, '%d %M %Y %H:%i:%s')"),
                'c.name',
                'a.discount'
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
                return '
                    <a class="btn btnDetailPaymet btn-primary btn-sm" data-id="' . $row->id . '"><i class="fas fa-eye text-white"></i><span class="text-white"> Detail</span></a>
                ';
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
                (SELECT SUM(r.berat) FROM tbl_resi AS r WHERE r.invoice_id = a.id) AS total_berat,
                (SELECT SUM(r.panjang * r.lebar * r.tinggi) FROM tbl_resi AS r WHERE r.invoice_id = a.id) AS total_dimensi
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

        $topups = DB::table('tbl_history_topup')
            ->where('customer_id', $customerId)
            ->where('balance', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        $remainingPoin = $amountPoin;
        $totalNominal = 0;

        foreach ($topups as $topup) {
            if ($remainingPoin <= 0) {
                break;
            }

            if ($topup->balance >= $remainingPoin) {
                $nominal = $remainingPoin * $topup->price_per_kg;
                $totalNominal += $nominal;

                $remainingPoin = 0;
            } else {
                $nominal = $topup->balance * $topup->price_per_kg;
                $totalNominal += $nominal;
                $remainingPoin -= $topup->balance;
            }
        }
        if ($remainingPoin > 0) {
            return response()->json(['error' => 'Poin tidak mencukupi'], 400);
        }
        return response()->json([
            'message' => 'Nominal berhasil dihitung.',
            'total_nominal' => $totalNominal
        ]);
    }


    public function store(Request $request)
    {

        $validated = $request->validate([
            'invoice' => 'required|array',
            'kode' => 'required|string',
            'tanggalPayment' => 'required|date',
            'tanggalPaymentBuat' => 'required',
            'paymentAmount' => 'required|numeric',
            'discountPayment' => 'nullable|numeric',
            'paymentMethod' => 'required|integer',
            'amountPoin' => 'nullable|numeric',
            'keterangan' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.account' => 'required|integer',
            'items.*.item_desc' => 'required|string',
            'items.*.debit' => 'required|numeric',
        ]);

        // dd($request->all());


        if (
            $request->amountPoin === null &&
            ($request->discountPayment === null || $request->discountPayment == 0) &&
            (empty($request->items) || count($request->items) === 0)
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

            $salesAccountId = $accountSettings->sales_account_id;
            $paymentMethodId = $request->paymentMethod;
            $receivableSalesAccount = COA::find($paymentMethodId);
            $poinMarginAccount = $accountSettings->discount_sales_account_id;
            $paymentDiscountAccount = $accountSettings->sales_profit_rate_account_id;

            $currentPointPrice = DB::select("SELECT nilai_rate FROM tbl_rate WHERE rate_for = 'Topup'");

            if (empty($currentPointPrice)) {
                Log::error("Rate for 'Topup' not found.");
                return response()->json(['status' => 'error', 'message' => 'Rate for Topup not found.']);
            }

            $currentPointPrice = $currentPointPrice[0]->nilai_rate;
            if (is_null($salesAccountId) || is_null($receivableSalesAccount) || is_null($poinMarginAccount) || is_null($paymentDiscountAccount)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Silakan cek Account setting untuk mengatur pemilihan Account.',
                ], 400);
            }

            $noRef = implode(', ', $request->invoice);

            $tanggalPayment = Carbon::createFromFormat('d F Y', $request->tanggalPayment)->format('Y-m-d');


            $tanggalPayment = Carbon::createFromFormat('d F Y', $request->tanggalPayment)->format('Y-m-d');

            $date = Carbon::createFromFormat('d F Y H:i', $request->tanggalPaymentBuat);

            $formattedDateTime = $date->format('Y-m-d H:i:s');

            $totalPayment = $request->paymentAmount;
            $discount = $request->discountPayment ?? 0;
            $totalEffectivePayment = $totalPayment + $discount;

            $payment = new Payment();
            $payment->kode_pembayaran = $request->kode;
            $payment->pembeli_id = $request->marking;
            $payment->payment_date = $tanggalPayment;
            $payment->payment_buat = $formattedDateTime;
            $payment->payment_method_id = $paymentMethodId;
            $payment->discount = $discount;
            $payment->Keterangan = $request->keterangan;
            $payment->save();

            $invoiceList = [];
            foreach ($request->invoice as $noInvoice) {
                $invoice = Invoice::where('no_invoice', $noInvoice)->firstOrFail();

                $remainingAmount = $invoice->total_harga - $invoice->total_bayar;
                $allocatedAmount = min($totalPayment, $remainingAmount);

                if ($allocatedAmount > $remainingAmount) {
                    Log::warning("Pembayaran melebihi jumlah yang tersisa untuk invoice {$noInvoice}. Pembayaran dibatalkan.");

                    return response()->json([
                        'status' => 'error',
                        'message' => 'Pembayaran melebihi jumlah yang tersisa. Pembayaran dibatalkan.'
                    ], 400);
                }

                if ($allocatedAmount <= 0 && $totalEffectivePayment < $remainingAmount) {
                    Log::info("Invoice {$noInvoice} sudah lunas atau pembayaran tidak mencukupi.");
                    continue;
                }

                $totalTagihanInvoice = $remainingAmount;
                $kuota = $allocatedAmount / $currentPointPrice;
                $paymentInvoice = new PaymentInvoice();
                $paymentInvoice->payment_id = $payment->id;
                $paymentInvoice->invoice_id = $invoice->id;
                $paymentInvoice->amount = $allocatedAmount;
                $paymentInvoice->kuota = $kuota;
                $paymentInvoice->save();

                $invoice->total_bayar += $allocatedAmount;


                $totalUsedPoin = $request->amountPoin;
                $newNominal = $totalUsedPoin * $currentPointPrice;

                $poinMargin = $newNominal - $totalPayment;


                if ($poinMargin > 0) {
                    $invoice->total_bayar += $poinMargin;
                } elseif ($poinMargin < 0) {
                    $invoice->total_bayar -= abs($poinMargin);
                }

                if ($invoice->total_bayar >= $invoice->total_harga) {
                    $invoice->status_bayar = 'Lunas';
                } else {
                    $invoice->status_bayar = 'Belum Lunas';
                }

                $invoice->save();

                $totalPayment -= $allocatedAmount;
                $totalEffectivePayment -= $remainingAmount;

                if ($totalPayment <= 0 && $totalEffectivePayment <= 0) {
                    break;
                }

                $invoiceList[] = $invoice->no_invoice;
            }


            if ($totalPayment > 0) {
                Log::warning("Sisa dana pembayaran tidak teralokasi: {$totalPayment}");
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pembayaran tidak dapat diproses karena sisa dana melebihi jumlah yang harus dibayar.'
                ], 400);
            }

            if ($request->amountPoin) {
                // Ambil data invoice
                $invoice = Invoice::where('no_invoice', $request->invoice)->firstOrFail();
                $paymentMethodId = $request->paymentMethod;

                // Ambil data topup poin pelanggan
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
                        // Poin yang digunakan cukup dari topup ini
                        $nominal = $remainingPoin * $topup->price_per_kg;
                        $totalNominal += $nominal;
                        $totalUsedPoin += $remainingPoin;

                        // Kurangi saldo poin di history topup
                        DB::table('tbl_history_topup')->where('id', $topup->id)->decrement('balance', $remainingPoin);

                        // Catat penggunaan poin
                        UsagePoints::create([
                            'customer_id' => $invoice->pembeli_id,
                            'history_topup_id' => $topup->id,
                            'used_points' => $remainingPoin,
                            'price_per_kg' => $topup->price_per_kg,
                            'usage_date' => now(),
                        ]);

                        // Sisa poin yang digunakan selesai
                        $remainingPoin = 0;
                    } else {
                        // Jika poin yang tersedia tidak cukup untuk digunakan seluruhnya
                        $nominal = $topup->balance * $topup->price_per_kg;
                        $totalNominal += $nominal;
                        $totalUsedPoin += $topup->balance;

                        // Kurangi saldo poin di history topup
                        DB::table('tbl_history_topup')->where('id', $topup->id)->update(['balance' => 0]);

                        // Catat penggunaan poin
                        UsagePoints::create([
                            'customer_id' => $invoice->pembeli_id,
                            'history_topup_id' => $topup->id,
                            'used_points' => $topup->balance,
                            'price_per_kg' => $topup->price_per_kg,
                            'usage_date' => now(),
                        ]);

                        // Kurangi poin yang masih harus digunakan
                        $remainingPoin -= $topup->balance;
                    }
                }

                // Validasi jika poin yang digunakan masih kurang dari yang dibutuhkan
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


                // Log informasi margin
                Log::info("Margin Poin Calculated", [
                    'totalUsedPoin' => $totalUsedPoin,
                    'currentPointPrice' => $currentPointPrice,
                    'newNominal' => $newNominal,
                    'totalNominal' => $totalNominal,
                    'poinMargin' => $poinMargin,
                ]);

                $invoiceNumbers = is_array($request->invoice) ? implode(', ', $request->invoice) : $request->invoice;

                $request->merge(['code_type' => 'BKM']);
                $noJournal = $this->jurnalController->generateNoJurnal($request)->getData()->no_journal;

                Log::info("Generated Journal Number", ['noJournal' => $noJournal]);

                $jurnal = new Jurnal();
                $jurnal->no_journal = $noJournal;
                $jurnal->tipe_kode = 'BKM';
                $jurnal->tanggal = now();
                $jurnal->no_ref = $invoiceNumbers;
                $jurnal->status = 'Approve';
                $jurnal->description = "Jurnal untuk Invoice " . $invoiceNumbers;
                $jurnal->totaldebit = $totalNominal;
                $jurnal->totalcredit = $totalNominal;
                $jurnal->save();

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

            if ($request->discountPayment) {
                $request->merge(['code_type' => 'BKM']);
                $noJournal = $this->jurnalController->generateNoJurnal($request)->getData()->no_journal;

                $jurnal = new Jurnal();
                $jurnal->no_journal = $noJournal;
                $jurnal->tipe_kode = 'BKM';
                $jurnal->tanggal = $tanggalPayment;
                $jurnal->no_ref = $noRef;
                $jurnal->status = 'Approve';
                $jurnal->description = "Jurnal untuk Invoice: " . $noRef;
                $jurnal->totaldebit = $request->paymentAmount;
                $jurnal->totalcredit = $request->paymentAmount;
                $jurnal->save();

                Log::info('Entri jurnal berhasil dibuat', ['jurnalId' => $jurnal->id]);

                $jurnalItemDebit = new JurnalItem();
                $jurnalItemDebit->jurnal_id = $jurnal->id;
                $jurnalItemDebit->code_account = $receivableSalesAccount->id;
                $jurnalItemDebit->description = "Debit untuk Invoices: " . $noRef;
                $jurnalItemDebit->debit = $request->paymentAmount;
                $jurnalItemDebit->credit = 0;
                $jurnalItemDebit->save();

                Log::info('Jurnal item debit berhasil ditambahkan.');

                $jurnalItemCredit = new JurnalItem();
                $jurnalItemCredit->jurnal_id = $jurnal->id;
                $jurnalItemCredit->code_account = $salesAccountId;
                $jurnalItemCredit->description = "Kredit untuk Invoices: " . $noRef;
                $jurnalItemCredit->debit = 0;
                $jurnalItemCredit->credit = $request->totalAmmount;
                $jurnalItemCredit->save();

                Log::info('Jurnal item kredit berhasil ditambahkan.');

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
                foreach ($request->items as $item) {
                    PaymentCustomerItems::create([
                        'payment_id' => $payment->id,
                        'coa_id' => $item['account'],
                        'description' => $item['item_desc'],
                        'nominal' => $item['debit'],
                    ]);

                    $jurnalItem = new JurnalItem();
                    $jurnalItem->jurnal_id = $jurnal->id;
                    $jurnalItem->code_account = $item['account'];
                    $jurnalItem->description = $item['item_desc'];
                    $jurnalItem->debit = $item['debit'];
                    $jurnalItem->credit = 0;
                    $jurnalItem->save();

                    Log::info('Jurnal item untuk custom items berhasil ditambahkan.', [
                        'account' => $item['account'],
                        'description' => $item['item_desc'],
                        'nominal' => $item['debit'],
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil diproses.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Transaction failed: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error during the transaction', 'error' => $e->getMessage()]);
        }
    }

    private function processNormalPayment($request)
    {
        Log::info('Mulai proses pembayaran normal', ['request' => $request->all()]);

        $accountSettings = DB::table('tbl_account_settings')->first();

        if (!$accountSettings) {
            Log::error('Account settings tidak ditemukan.');
            return response()->json([
                'status' => 'error',
                'message' => 'Silakan cek Account setting untuk mengatur pemilihan Account.',
            ], 400);
        }

        $salesAccountId = $accountSettings->sales_account_id;
        $paymentMethodId = $request->paymentMethod;
        $receivableSalesAccount = COA::find($paymentMethodId);

        if (is_null($salesAccountId) || is_null($receivableSalesAccount)) {
            Log::error('Akun pengaturan tidak lengkap.');
            return response()->json([
                'status' => 'error',
                'message' => 'Silakan cek Account setting untuk mengatur pemilihan Account.',
            ], 400);
        }

        DB::beginTransaction();
        try {
            $tanggalPayment = Carbon::createFromFormat('d F Y', $request->tanggalPayment)->format('Y-m-d');
            $totalPayment = $request->paymentAmount;
            $date = Carbon::createFromFormat('d F Y H:i', $request->tanggalPaymentBuat);
            $formattedDateTime = $date->format('Y-m-d H:i:s');

            $payment = new Payment();
            $payment->kode_pembayaran = $request->kode;
            $payment->pembeli_id = $request->marking;
            $payment->payment_date = $tanggalPayment;
            $payment->payment_buat =   $formattedDateTime;
            $payment->payment_method_id = $paymentMethodId;
            $payment->discount = $request->discountPayment ?? 0;
            $payment->Keterangan = $request->keterangan;
            $payment->save();

            $invoiceList = [];
            foreach ($request->invoice as $noInvoice) {
                $invoice = Invoice::where('no_invoice', $noInvoice)->firstOrFail();

                $remainingAmount = $invoice->total_harga - $invoice->total_bayar;
                $allocatedAmount = min($totalPayment, $remainingAmount);

                if ($allocatedAmount > $remainingAmount) {
                    Log::warning("Pembayaran melebihi jumlah yang tersisa untuk invoice {$noInvoice}. Pembayaran dibatalkan.");

                    // Stop further processing and return a response
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Pembayaran melebihi jumlah yang tersisa. Pembayaran dibatalkan.'
                    ], 400);
                }

                if ($allocatedAmount <= 0) {
                    Log::info("Invoice {$noInvoice} sudah lunas.");
                    continue;
                }

                $paymentInvoice = new PaymentInvoice();
                $paymentInvoice->payment_id = $payment->id;
                $paymentInvoice->invoice_id = $invoice->id;
                $paymentInvoice->amount = $allocatedAmount;
                $paymentInvoice->save();

                $invoice->total_bayar += $allocatedAmount;
                $invoice->status_bayar = $invoice->total_bayar >= $invoice->total_harga ? 'Lunas' : 'Belum Lunas';
                $invoice->save();

                $totalPayment -= $allocatedAmount;
                if ($totalPayment <= 0) {
                    break;
                }

                // Menambahkan nomor invoice ke list
                $invoiceList[] = $invoice->no_invoice;
            }

            // Cancel payment if the total amount is still greater than 0
            if ($totalPayment > 0) {
                Log::warning("Sisa dana pembayaran tidak teralokasi: {$totalPayment}");
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pembayaran tidak dapat diproses karena sisa dana melebihi jumlah yang harus dibayar.'
                ], 400);
            }

            $noRef = implode(', ', $request->invoice);

            if ($totalPayment > 0) {
                Log::warning("Sisa dana pembayaran tidak teralokasi: {$totalPayment}");
            }

            // Generate nomor jurnal menggunakan jurnalController
            $request->merge(['code_type' => 'BKM']);
            $noJournal = $this->jurnalController->generateNoJurnal($request)->getData()->no_journal;

            // Buat entri jurnal
            $jurnal = new Jurnal();
            $jurnal->no_journal = $noJournal;
            $jurnal->tipe_kode = 'BKM';  // Menggunakan tipe kode yang sama
            $jurnal->tanggal = $tanggalPayment;
            $jurnal->no_ref = $noRef;
            $jurnal->status = 'Approve';
            $jurnal->description = "Jurnal untuk Invoice: " . $noRef;
            $jurnal->totaldebit = $request->paymentAmount;
            $jurnal->totalcredit = $request->paymentAmount;
            $jurnal->save();

            Log::info('Entri jurnal berhasil dibuat', ['jurnalId' => $jurnal->id]);

            // Tambahkan jurnal item debit
            $jurnalItemDebit = new JurnalItem();
            $jurnalItemDebit->jurnal_id = $jurnal->id;
            $jurnalItemDebit->code_account = $receivableSalesAccount->id;  // Ambil ID dari akun penerimaan
            $jurnalItemDebit->description = "Debit untuk Invoices: " . $noRef;
            $jurnalItemDebit->debit = $request->paymentAmount;
            $jurnalItemDebit->credit = 0;
            $jurnalItemDebit->save();

            Log::info('Jurnal item debit berhasil ditambahkan.');

            // Tambahkan jurnal item kredit
            $jurnalItemCredit = new JurnalItem();
            $jurnalItemCredit->jurnal_id = $jurnal->id;
            $jurnalItemCredit->code_account = $salesAccountId;  // Ambil ID dari akun penjualan
            $jurnalItemCredit->description = "Kredit untuk Invoices: " . $noRef;
            $jurnalItemCredit->debit = 0;
            $jurnalItemCredit->credit = $request->paymentAmount;
            $jurnalItemCredit->save();

            Log::info('Jurnal item kredit berhasil ditambahkan.');

            // Commit transaksi
            DB::commit();
            Log::info('Pembayaran berhasil diproses.');
            return response()->json(['success' => true, 'message' => 'Payments successfully created and invoices updated']);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Terjadi kesalahan saat memproses pembayaran', ['error' => $e->getMessage()]);
            throw new \Exception('Error during multiple invoice payment processing: ' . $e->getMessage());
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
            $lastSequence = intval(substr($lastPayment->kode_pembayaran, -4));  // Extract last 4 digits
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

        $invoices = Invoice::join('tbl_pembeli', 'tbl_pembeli.id', '=', 'tbl_invoice.pembeli_id')
            ->where('tbl_pembeli.marking', $marking)
            ->where('tbl_invoice.status_bayar', 'Belum Lunas')
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


}
