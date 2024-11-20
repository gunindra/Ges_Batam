<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Asset;
use App\Models\Payment;
use App\Models\COA;
use App\Traits\WhatsappTrait;
use Carbon\Carbon;

class PenerimaanKasController extends Controller
{
    use WhatsappTrait;

    public function index() {

        $customers = Customer::where('status', '=', 1)->get();
        $payment = COA::whereIn('parent_id', [3, 7])->get();
        return view('Report.PenerimaanKas.indexpenerimaankas', [
            'customers' => $customers,
            'payment' => $payment
        ]);
    }

    public function getPenerimaanKas(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';
        $status = $request->status;
        
        $startDate = $request->startDate ? date('Y-m-d', strtotime($request->startDate)) : Carbon::now()->startOfMonth();
        $endDate = $request->endDate ? date('Y-m-d', strtotime($request->endDate)) : Carbon::now()->endOfMonth();

        $payment = Payment::join('tbl_payment_invoice', 'tbl_payment_customer.id', '=', 'tbl_payment_invoice.payment_id')
                            ->join('tbl_invoice', 'tbl_payment_invoice.invoice_id', '=', 'tbl_invoice.id')
                            ->join('tbl_pembeli', 'tbl_payment_customer.pembeli_id', '=', 'tbl_pembeli.id')
                            ->join('tbl_coa', 'tbl_payment_customer.payment_method_id', '=', 'tbl_coa.id')
                            ->whereDate('tbl_payment_customer.payment_date', '>=', $startDate)
                            ->whereDate('tbl_payment_customer.payment_date', '<=', $endDate);

        if ($request->customer) {    
            $payment->where('tbl_payment_customer.customer_id', '=', $request->customer);
        }

        if ($request->payment) {    
            $payment->where('tbl_payment_customer.payment_method_id', '=', $request->payment);
        }

        // Add groupBy and use GROUP_CONCAT to combine invoices with amounts
        $payment->selectRaw("
            tbl_payment_customer.kode_pembayaran as kode_pembayaran,
            tbl_payment_customer.payment_buat as created_date,
            tbl_payment_customer.payment_date as payment_date,
            tbl_payment_customer.discount as discount,
            tbl_pembeli.nama_pembeli as customer_name,
            tbl_coa.name as payment_method,
            GROUP_CONCAT(CONCAT(tbl_invoice.no_invoice, ' (', tbl_payment_invoice.amount, ')') SEPARATOR ', ') as no_invoice_with_amount,
            SUM(tbl_payment_invoice.amount) as total_amount
        ")
        ->groupBy(
            'tbl_payment_customer.id', 
            'tbl_payment_customer.payment_buat',
            'tbl_payment_customer.payment_date',
            'tbl_payment_customer.kode_pembayaran',
            'tbl_payment_customer.discount',
            'tbl_coa.name',
            'tbl_pembeli.nama_pembeli'
        );

        // Get the results
        $payments = $payment->get();

        
        $output = '
                    <h5 style="text-align:center; width:100%">' 
                        . \Carbon\Carbon::parse($startDate)->format('d M Y') . ' - ' 
                        . \Carbon\Carbon::parse($endDate)->format('d M Y') . 
                    '</h5>

                    <div class="card-body">   
                    <table class="table" id="penerimaanKasTable" width="100%">
                    <thead>
                        <th onclick="sortTable(0)" width="10%" style="text-align:center;">No</th>
                        <th onclick="sortTable(1)" width="15%" style="text-align:center;">Date</th>
                        <th onclick="sortTable(2)" width="15%" style="text-align:center;">Transfer Date</th>
                        <th onclick="sortTable(3)" width="15%" style="text-align:center;">Customer</th>
                        <th onclick="sortTable(4)" width="10%" style="text-align:center;">Method</th>
                        <th onclick="sortTable(5)" width="20%" style="text-align:center;">No Invoice</th>
                        <th onclick="sortTable(6)" width="15%" style="text-align:right;">Total</th>
                    </thead>
                    <tbody>';
        
        foreach($payments as $data){
            $output .='<tr>
                            <td style="text-align:center;">' . $data->kode_pembayaran . ' </td>
                            <td style="text-align:center;">' . \Carbon\Carbon::parse($data->created_date)->format('d M Y') . '</td>
                            <td style="text-align:center;">' . \Carbon\Carbon::parse($data->payment_date)->format('d M Y H:m') . '</td>
                            <td style="text-align:center;">' . $data->customer_name . ' </td>
                            <td style="text-align:center;">' . $data->payment_method . '</td>
                            <td style="text-align:center;">' . $data->no_invoice_with_amount . '</td>
                            <td style="text-align:right;">' . number_format($data->total_amount - $data->discount, 2) . '</td>
                        </tr>';
        }
        
        $output .= '</table> </div>';

        return $output;
    }

    public function generatePdf(Request $request)
    {
        $htmlOutput = $this->getPenerimaanKas($request);

        $pdf = PDF::loadHTML($htmlOutput);
        return $pdf->download('Penerimaan Kas Report.pdf');
    }
}
