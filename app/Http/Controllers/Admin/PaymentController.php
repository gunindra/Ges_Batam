<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Invoice;
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
        $coas = COA::all();

        $listInvoice = DB::select("SELECT no_invoice FROM tbl_invoice
                                    WHERE status_bayar = 'Belum Lunas'");

        return view('customer.payment.buatpayment', [
            'listInvoice' => $listInvoice,
            'coas' => $coas
        ]);
    }

    public function getPaymentData(Request $request)
    {
        $query = DB::table('tbl_payment_customer as a')
            ->join('tbl_invoice as b', 'a.invoice_id', '=', 'b.id')
            ->join('tbl_coa as c', 'a.payment_method_id', '=', 'c.id')
            ->select([
                'a.kode_pembayaran',
                'b.no_invoice',
                DB::raw("DATE_FORMAT(a.payment_date, '%d %M %Y') as tanggal_bayar"),
                'a.amount',
                'c.name as payment_method',
                'b.status_bayar',
                'a.id'
            ]);

        if (!empty($request->status)) {
            $query->where('c.name', $request->status);
        }

        if (!empty($request->startDate) && !empty($request->endDate)) {
            $startDate = date('Y-m-d', strtotime($request->startDate));
            $endDate = date('Y-m-d', strtotime($request->endDate));
            $query->whereBetween('a.payment_date', [$startDate, $endDate]);
        }

        $query->orderBy('a.id', 'desc');

        return DataTables::of($query)
            ->editColumn('tanggal_bayar', function ($row) {
                return $row->tanggal_bayar;
            })
            ->addColumn('status_bayar', function($row) {
                return $row->status_bayar == 'Lunas'
                    ? '<span class="text-success"><i class="fas fa-check-circle"></i> Lunas</span>'
                    : '<span class="text-danger"><i class="fas fa-exclamation-circle"></i> Belum Lunas</span>';
            })
            ->rawColumns(['status_bayar'])
            ->make(true);
    }



    public function getInvoiceAmount(Request $request)
    {
        $invoiceSelect = $request->no_invoice;
        $invoice = DB::select("SELECT
                                    a.id,
                                    a.no_invoice,
                                    DATE_FORMAT(a.tanggal_invoice, '%d %M %Y') AS tanggal_bayar,
                                    FORMAT(a.total_harga, 0) AS total_harga,
                                    FORMAT(a.total_bayar, 0) AS total_bayar,
                                    FORMAT(a.total_harga - a.total_bayar, 0) AS sisa_bayar,
                                    b.status_name,
                                    (SELECT SUM(r.berat) FROM tbl_resi AS r WHERE r.invoice_id = a.id) AS total_berat,
                                    (SELECT SUM(r.panjang * r.lebar * r.tinggi) FROM tbl_resi AS r WHERE r.invoice_id = a.id) AS total_dimensi,
                                    (SELECT COUNT(*) FROM tbl_resi AS r WHERE r.invoice_id = a.id AND r.berat > 0) AS count_berat,
                                    (SELECT COUNT(*) FROM tbl_resi AS r WHERE r.invoice_id = a.id AND (r.panjang * r.lebar * r.tinggi) > 0) AS count_dimensi
                                FROM
                                    tbl_invoice AS a
                                JOIN
                                    tbl_status AS b ON a.status_id = b.id
                                WHERE
                                    a.no_invoice = '$invoiceSelect'");

        if ($invoice) {
            return response()->json([
                'success' => true,
                'invoice' => $invoice
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invoice tidak ditemukan'
            ]);
        }
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
            'invoice' => 'required|string',
            'tanggalPayment' => 'required|date',
            'paymentAmount' => 'required|numeric',
            'paymentMethod' => 'required|integer',
        ]);

        DB::beginTransaction();

        try {
            Log::info("Starting payment process for Invoice: {$request->invoice}");

            if ($request->amountPoin === null) {
                return $this->processNormalPayment($request);
            }

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

            if (is_null($salesAccountId) || is_null($receivableSalesAccount) || is_null($poinMarginAccount)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Silakan cek Account setting untuk mengatur pemilihan Account.',
                ], 400);
            }

            $invoice = Invoice::where('no_invoice', $request->invoice)->firstOrFail();
            $invoice_id = $invoice->id;

            $codeType = "BO";
            $currentYear = date('y');

            if (!$receivableSalesAccount) {
                Log::error("Account with ID {$paymentMethodId} not found.");
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Akun dengan ID yang diberikan tidak ditemukan.'
                ], 404);
            }

            $tanggalPayment = Carbon::createFromFormat('d F Y', $request->tanggalPayment)->format('Y-m-d');
            Log::info("Formatted Payment Date: {$tanggalPayment}");

            $totalTagihanInvoice = $invoice->total_harga;
            $totalBayarInvoice = $invoice->total_bayar ?? 0;
            $sisaTagihan = $totalTagihanInvoice - $totalBayarInvoice;

            $lastPayment = Payment::where('kode_pembayaran', 'like', $codeType . $currentYear . '%')
                ->orderBy('kode_pembayaran', 'desc')
                ->first();

            $newSequence = 1;
            if ($lastPayment) {
                $lastSequence = intval(substr($lastPayment->kode_pembayaran, -4));
                $newSequence = $lastSequence + 1;
            }
            $newKodePembayaran = $codeType . $currentYear . str_pad($newSequence, 4, '0', STR_PAD_LEFT);

            $payment = new Payment();
            $payment->invoice_id = $invoice_id;
            $payment->payment_date = $tanggalPayment;
            $payment->amount = $request->paymentAmount;
            $payment->payment_method_id = $paymentMethodId;
            $payment->kode_pembayaran = $newKodePembayaran;
            $payment->save();

            if ($request->amountPoin) {
                $remainingPoin = $request->amountPoin;

                $topups = DB::table('tbl_history_topup')
                    ->where('customer_id', $invoice->pembeli_id)
                    ->where('balance', '>', 0)
                    ->orderBy('created_at', 'asc')
                    ->get();

                $totalUsedPoin = 0;
                $totalNominal = 0;

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
                            'usage_date' => now()
                        ]);

                        $remainingPoin = 0;
                    } else {
                        $nominal = $topup->balance * $topup->price_per_kg;
                        $totalNominal += $nominal;
                        $totalUsedPoin += $topup->balance;
                        $remainingPoin -= $topup->balance;
                        DB::table('tbl_history_topup')->where('id', $topup->id)->update(['balance' => 0]);
                        UsagePoints::create([
                            'customer_id' => $invoice->pembeli_id,
                            'history_topup_id' => $topup->id,
                            'used_points' => $topup->balance,
                            'price_per_kg' => $topup->price_per_kg,
                            'usage_date' => now()
                        ]);
                    }
                }

                if ($remainingPoin > 0) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Poin tidak mencukupi untuk pembayaran ini.'
                    ], 400);
                }

                $poinMargin = $totalNominal - $sisaTagihan;



                Log::info("Poin Margin: {$poinMargin}");

                $request->merge(['code_type' => 'BKM']);
                $noJournal = $this->jurnalController->generateNoJurnal($request)->getData()->no_journal;
                $jurnal = new Jurnal();
                $jurnal->no_journal = $noJournal;
                $jurnal->tipe_kode = 'BKM';
                $jurnal->tanggal = $tanggalPayment;
                $jurnal->no_ref = $request->invoice;
                $jurnal->status = 'Approve';
                $jurnal->description = "Jurnal untuk Invoice {$request->invoice}";
                $jurnal->totaldebit = $totalNominal;
                $jurnal->totalcredit = $totalNominal;
                // $jurnal->totaldebit = $sisaTagihan + abs($poinMargin);
                // $jurnal->totalcredit = $sisaTagihan + abs($poinMargin);
                $jurnal->save();
                $journalItems = [];

                if ($poinMargin == 0) {
                    // Kondisi poin margin 0: Hanya dua akun, debit dan kredit sama
                    $journalItems[] = [
                        'code_account' => $salesAccountId,
                        'description' => "Debit untuk Invoice {$request->invoice}",
                        'debit' => $totalTagihanInvoice,
                        'credit' => 0,
                    ];

                    $journalItems[] = [
                        'code_account' => $paymentMethodId,
                        'description' => "Kredit untuk Invoice {$request->invoice}",
                        'debit' => 0,
                        'credit' => $totalNominal,
                    ];

                } elseif ($poinMargin < 0) {
                    // Kondisi poin margin negatif: Tambahkan akun margin di kredit, tambah nilai pada debit akun utama
                    $journalItems[] = [
                        'code_account' => $salesAccountId,
                        'description' => "Debit untuk Invoice {$request->invoice}",
                        'debit' => $sisaTagihan, // Tambah kekurangan
                        // 'debit' => $totalTagihanInvoice, // Tambah kekurangan
                        'credit' => 0,
                    ];

                    $journalItems[] = [
                        'code_account' => $paymentMethodId,
                        'description' => "Kredit untuk Invoice {$request->invoice}",
                        'debit' => 0,
                        'credit' => $totalNominal,
                    ];

                    Log::info("Menambahkan akun margin di kredit dengan nilai: " . abs($poinMargin));
                    $journalItems[] = [
                        'code_account' => $poinMarginAccount, // Ganti ke akun diskon
                        'description' => "Poin Margin untuk Invoice {$request->invoice}",
                        'debit' => 0,
                        'credit' => abs($poinMargin),
                    ];

                } elseif ($poinMargin > 0) {
                    // Kondisi poin margin positif: Tambahkan akun margin di debit, tambah nilai pada kredit akun utama
                    $journalItems[] = [
                        'code_account' => $salesAccountId,
                        'description' => "Debit untuk Invoice {$request->invoice}",
                        'debit' => $totalTagihanInvoice,
                        'credit' => 0,
                    ];

                    $journalItems[] = [
                        'code_account' => $paymentMethodId,
                        'description' => "Kredit untuk Invoice {$request->invoice}",
                        'debit' => 0,
                        'credit' =>  $sisaTagihan + abs($poinMargin), // Kurangi kelebihan
                    ];

                    Log::info("Menambahkan akun margin di debit dengan nilai: {$poinMargin}");
                    $journalItems[] = [
                        'code_account' => $accountSettings->discount_sales_account_id, // Ganti ke akun diskon
                        'description' => "Poin Margin untuk Invoice {$request->invoice}",
                        'debit' => $poinMargin,
                        'credit' => 0,
                    ];
                }

                // Simpan item jurnal ke database
                foreach ($journalItems as $item) {
                    Log::info("Menambahkan item jurnal: ", $item);
                    JurnalItem::create([
                        'jurnal_id' => $jurnal->id,
                        'code_account' => $item['code_account'],
                        'description' => $item['description'],
                        'debit' => $item['debit'],
                        'credit' => $item['credit']
                    ]);
                }

                if ($poinMargin < 0 && abs($poinMargin) > 20000) {
                    Log::error("Margin poin negatif terlalu besar untuk dianggap Lunas.");
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Poin tidak cukup untuk menutup tagihan ini.'
                    ], 400);
                }

                if ($poinMargin <= 0) {
                    Log::info("Invoice dianggap Lunas.");
                    $invoice->update([
                        'total_bayar' => $totalTagihanInvoice,
                        'status_bayar' => 'Lunas'
                    ]);
                } else {
                    Log::info("Invoice Lunas dengan saldo lebih sebesar {$poinMargin}.");
                    $invoice->update([
                        'total_bayar' => $totalTagihanInvoice + $poinMargin,
                        'status_bayar' => 'Lunas'
                    ]);
                }

                $updatesipoin = DB::table('tbl_pembeli')->where('id', $invoice->pembeli_id)->decrement('sisa_poin', $totalUsedPoin);
                Log::info("Sisa poin pembeli dengan ID {$invoice->pembeli_id} dikurangi sebanyak {$totalUsedPoin}. Sisa poin telah diperbarui.");
                Log::info("Sisa poin pembeli dengan ID {updatesipoin}");

            }


            // dd($totalTagihanInvoice);


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



    public function export(Request $request)
    {
        return Excel::download(new PaymentExport(
            $request->status,
            $request->startDate,
            $request->endDate
        ), 'Payment Customers.xlsx');
    }

    private function processNormalPayment($request)
    {
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

        if (is_null($salesAccountId) || is_null($receivableSalesAccount)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Silakan cek Account setting untuk mengatur pemilihan Account.',
            ], 400);
        }

        if (!$receivableSalesAccount) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Akun dengan ID yang diberikan tidak ditemukan.'
            ], 404);
        }

        $receivableSalesAccountId = $receivableSalesAccount->id;

        try {
            $tanggalPayment = Carbon::createFromFormat('d F Y', $request->tanggalPayment)->format('Y-m-d');
            $codeType = "BO";
            $currentYear = date('y');

            $invoice = Invoice::where('no_invoice', $request->invoice)->firstOrFail();
            $invoice_id = $invoice->id;

            $totalBayarBaru = $invoice->total_bayar + $request->paymentAmount;
            if ($totalBayarBaru > $invoice->total_harga) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Total pembayaran melebihi total harga invoice.'
                ], 400);
            }

            $lastPayment = Payment::where('kode_pembayaran', 'like', $codeType . $currentYear . '%')
                ->orderBy('kode_pembayaran', 'desc')
                ->first();

            $newSequence = $lastPayment ? intval(substr($lastPayment->kode_pembayaran, -4)) + 1 : 1;
            $newKodePembayaran = $codeType . $currentYear . str_pad($newSequence, 4, '0', STR_PAD_LEFT);

            $payment = new Payment();
            $payment->invoice_id = $invoice_id;
            $payment->payment_date = $tanggalPayment;
            $payment->amount = $request->paymentAmount;
            $payment->payment_method_id = $paymentMethodId;
            $payment->kode_pembayaran = $newKodePembayaran;
            $payment->save();

            $invoice->total_bayar = $totalBayarBaru;
            $invoice->status_bayar = $invoice->total_bayar >= $invoice->total_harga ? 'Lunas' : 'Belum Lunas';
            $invoice->save();

            $request->merge(['code_type' => 'BKM']);
            $noJournal = $this->jurnalController->generateNoJurnal($request)->getData()->no_journal;

            $jurnal = new Jurnal();
            $jurnal->no_journal = $noJournal;
            $jurnal->tipe_kode = 'BKM';
            $jurnal->tanggal = $tanggalPayment;
            $jurnal->no_ref = $request->invoice;
            $jurnal->status = 'Approve';
            $jurnal->description = "Jurnal untuk Invoice {$request->invoice}";
            $jurnal->totaldebit = $request->paymentAmount;
            $jurnal->totalcredit = $request->paymentAmount;
            $jurnal->save();

            $jurnalItemDebit = new JurnalItem();
            $jurnalItemDebit->jurnal_id = $jurnal->id;
            $jurnalItemDebit->code_account = $receivableSalesAccountId;
            $jurnalItemDebit->description = "Kredit untuk Invoice {$request->invoice}";
            $jurnalItemDebit->debit = 0;
            $jurnalItemDebit->credit = $request->paymentAmount;
            $jurnalItemDebit->save();

            $jurnalItemCredit = new JurnalItem();
            $jurnalItemCredit->jurnal_id = $jurnal->id;
            $jurnalItemCredit->code_account = $salesAccountId;
            $jurnalItemCredit->description = "Debit untuk Invoice {$request->invoice}";
            $jurnalItemCredit->debit = $request->paymentAmount;
            $jurnalItemCredit->credit = 0;
            $jurnalItemCredit->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Payment successfully created and invoice updated']);
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception('Error during normal payment processing: ' . $e->getMessage());
        }
    }


}
