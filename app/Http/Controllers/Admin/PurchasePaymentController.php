<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\COA;
use App\Models\Jurnal;
use App\Models\JurnalItem;
use App\Models\PaymentSup;
use App\Models\SupInvoice;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Admin\JournalController;

class PurchasePaymentController extends Controller
{

    protected $jurnalController;

    public function __construct(JournalController $jurnalController)
    {
        $this->jurnalController = $jurnalController;
    }

    public function index() {


        return view('vendor.purchasepayment.indexpurchasepayment');
    }
    public function addPurchasePayment()
    {

        $coas = COA::all();

        $listInvoice = SupInvoice::where('status_bayar', 'Belum Lunas')
        ->select('invoice_no')
        ->get();

        return view('vendor.purchasepayment.buatpurchasepayment' , [
            'listInvoice' => $listInvoice,
            'coas' => $coas
        ]);
    }


    public function getSupInvoiceAmount(Request $request)
    {
        $invoiceSelect = $request->no_invoice;

        // Menggunakan Eloquent untuk mengambil data dari tbl_sup_invoice
        $invoice = SupInvoice::select([
            'invoice_no',
            DB::raw("DATE_FORMAT(tanggal, '%d %M %Y') AS tanggal_bayar"),
            DB::raw("FORMAT(total_harga, 0) AS total_harga"),
            DB::raw("FORMAT(total_bayar, 0) AS total_bayar"),
            DB::raw("FORMAT(total_harga - total_bayar, 0) AS sisa_bayar"),
            'status_bayar' // Ambil status dari kolom status_bayar
        ])
        ->where('invoice_no', $invoiceSelect)
        ->first(); // Mengambil hasil pertama yang cocok

        // Cek jika invoice ditemukan
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

        try {
            // Ambil data akun dari vendor berdasarkan invoice yang diberikan
            $invoice = SupInvoice::where('invoice_no', $request->invoice)->firstOrFail();
            $vendor = Vendor::findOrFail($invoice->vendor_id);
            $vendorAccountId = $vendor->account_id;

            if (!$vendorAccountId) {
                throw new \Exception('Akun vendor tidak ditemukan.');
            }

            // Format tanggal pembayaran
            $tanggalPayment = Carbon::createFromFormat('d F Y', $request->tanggalPayment)->format('Y-m-d');
            $codeType = "SP"; // Ganti dengan "SP"

            // Generate kode pembayaran baru
            $currentYear = date('y');
            $lastPayment = PaymentSup::where('kode_pembayaran', 'like', $codeType . $currentYear . '%')
                ->orderBy('kode_pembayaran', 'desc')
                ->first();

            $newSequence = 1;
            if ($lastPayment) {
                $lastSequence = intval(substr($lastPayment->kode_pembayaran, -4));
                $newSequence = $lastSequence + 1;
            }
            $newKodePembayaran = $codeType . $currentYear . str_pad($newSequence, 4, '0', STR_PAD_LEFT);

            // Buat entri baru di tabel tbl_payment_sup
            $payment = new PaymentSup();
            $payment->invoice_id = $invoice->id;
            $payment->payment_date = $tanggalPayment;
            $payment->amount = $request->paymentAmount;
            $payment->payment_method_id = $request->paymentMethod;
            $payment->kode_pembayaran = $newKodePembayaran;
            $payment->save();

            // Update total_bayar dan status_bayar pada tabel tbl_sup_invoice
            $invoice->total_bayar += $request->paymentAmount;
            $invoice->status_bayar = $invoice->total_bayar >= $invoice->total_harga ? 'Lunas' : 'Belum Lunas';
            $invoice->save();

            // Buat Jurnal
            try {
                $request->merge(['code_type' => 'BKK']);
                $noJournal = $this->jurnalController->generateNoJurnal($request)->getData()->no_journal;

                $jurnal = new Jurnal();
                $jurnal->no_journal = $noJournal;
                $jurnal->tipe_kode = 'BKK';
                $jurnal->tanggal = $tanggalPayment;
                $jurnal->no_ref = $request->invoice;
                $jurnal->status = 'Approve';
                $jurnal->description = "Jurnal untuk Invoice {$request->invoice}";
                $jurnal->totaldebit = $request->paymentAmount;
                $jurnal->totalcredit = $request->paymentAmount;
                $jurnal->save();

                // Tambah JurnalItem untuk Debit (Vendor Account)
                $jurnalItemDebit = new JurnalItem();
                $jurnalItemDebit->jurnal_id = $jurnal->id;
                $jurnalItemDebit->code_account = $vendorAccountId; // Vendor Account untuk debit
                $jurnalItemDebit->description = "Debit untuk Invoice {$request->invoice}";
                $jurnalItemDebit->debit = $request->paymentAmount;
                $jurnalItemDebit->credit = 0;
                $jurnalItemDebit->save();

                // Tambah JurnalItem untuk Credit (Payment Method)
                $jurnalItemCredit = new JurnalItem();
                $jurnalItemCredit->jurnal_id = $jurnal->id;
                $jurnalItemCredit->code_account = $request->paymentMethod; // Payment method untuk credit
                $jurnalItemCredit->description = "Kredit untuk Invoice {$request->invoice}";
                $jurnalItemCredit->debit = 0;
                $jurnalItemCredit->credit = $request->paymentAmount;
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



}
