<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
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
        ->join('tbl_coa as coa', 'pc.payment_method_id', '=', 'coa.id') // Menambahkan join dengan tbl_coa
        ->select('coa.name as payment_method') // Menggunakan nama dari coa sebagai payment method
        ->groupBy('coa.name') // Grouping berdasarkan nama
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
            ->join('tbl_coa as c', 'a.payment_method_id', '=', 'c.id') // Menambahkan join ke tbl_coa
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
            // Uncomment and modify if you need action buttons
            // ->addColumn('action', function ($row) {
            //     return '<a href="#" class="btn btn-sm btn-secondary" id="edit-' . $row->id . '"><i class="fas fa-edit"></i></a>' .
            //            '<a href="#" class="btn btn-sm btn-danger ml-2" id="delete-' . $row->id . '"><i class="fas fa-trash"></i></a>';
            // })
            // ->rawColumns(['action'])
            ->make(true);
    }


    public function getInvoiceAmount(Request $request)
    {
        $invoiceSelect = $request->no_invoice;
        $invoice = DB::select("SELECT
                                        a.no_invoice,
                                        DATE_FORMAT(a.tanggal_invoice, '%d %M %Y') AS tanggal_bayar,
                                        FORMAT(a.total_harga, 0) AS total_harga,
                                        FORMAT(a.total_bayar, 0) AS total_bayar,
                                        FORMAT(a.total_harga - a.total_bayar, 0) AS sisa_bayar,
                                        b.status_name
                                FROM tbl_invoice AS a
                                JOIN tbl_status AS b ON a.status_id = b.id
                                WHERE a.no_invoice = '$invoiceSelect'");

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



    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'invoice' => 'required|string',
            'tanggalPayment' => 'required|date',
            'paymentAmount' => 'required|numeric',
            'paymentMethod' => 'required|integer',
        ]);


        DB::beginTransaction();

        $accountSettings = DB::table('tbl_account_settings')->first();

        $salesAccountId = $accountSettings->sales_account_id;
        $paymentMethodId = $request->paymentMethod;
        $receivableSalesAccount = COA::find($paymentMethodId);

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

            $invoice->total_bayar += $request->paymentAmount;
            $invoice->status_bayar = $invoice->total_bayar >= $invoice->total_harga ? 'Lunas' : 'Belum Lunas';
            $invoice->save();

            try {
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
                $jurnalItemDebit->description = "Debit untuk Invoice {$request->invoice}";
                $jurnalItemDebit->debit = 0;
                $jurnalItemDebit->credit = $request->paymentAmount;
                $jurnalItemDebit->save();

                $jurnalItemCredit = new JurnalItem();
                $jurnalItemCredit->jurnal_id = $jurnal->id;
                $jurnalItemCredit->code_account = $salesAccountId;
                $jurnalItemCredit->description = "Kredit untuk Invoice {$request->invoice}";
                $jurnalItemCredit->debit = $request->paymentAmount;
                $jurnalItemCredit->credit = 0;
                $jurnalItemCredit->save();

            } catch (\Exception $e) {
                throw new \Exception('Gagal menambahkan jurnal: ' . $e->getMessage());
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Payment successfully created and invoice updated']);
        } catch (\Exception $e) {
            DB::rollback();
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



}
