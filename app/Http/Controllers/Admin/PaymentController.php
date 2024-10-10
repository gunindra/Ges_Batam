<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PaymentController extends Controller
{
    public function index()
    {
        return view('customer.payment.indexpayment');
    }

    public function addPayment()
    {


        $listInvoice = DB::select("SELECT no_invoice FROM tbl_invoice
                                    WHERE status_bayar = 'Belum Lunas'");

        return view('customer.payment.buatpayment', [
            'listInvoice' => $listInvoice
        ]);
    }


    public function getPaymentData(Request $request)
    {
        $query = DB::table('tbl_payment_customer as a')
            ->join('tbl_invoice as b', 'a.invoice_id', '=', 'b.id')
            ->select([
                'a.kode_pembayaran',
                'b.no_invoice',
                DB::raw("DATE_FORMAT(a.payment_date, '%d %M %Y') as tanggal_bayar"),
                'a.amount',
                'a.payment_method',
                'b.status_bayar',
                'a.id'
            ]);

        if (!empty($request->status)) {
            $query->where('b.status_bayar', $request->status);
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
            ->addColumn('action', function ($row) {
                return '<a href="#" class="btn btn-sm btn-secondary" id="edit-' . $row->id . '"><i class="fas fa-edit"></i></a>' .
                       '<a href="#" class="btn btn-sm btn-danger ml-2" id="delete-' . $row->id . '"><i class="fas fa-trash"></i></a>';
            })
            ->rawColumns(['action'])
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
            'paymentMethod' => 'required|string',
        ]);

        DB::beginTransaction();

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
            $payment->payment_method = $request->paymentMethod;
            $payment->kode_pembayaran = $newKodePembayaran;
            $payment->save();

            $invoice->total_bayar += $request->paymentAmount;
            $invoice->status_bayar = $invoice->total_bayar >= $invoice->total_harga ? 'Lunas' : 'Belum Lunas';
            $invoice->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Payment successfully created and invoice updated']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Error during the transaction', 'error' => $e->getMessage()]);
        }
    }



}
