<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\PaymentInvoice;
use App\Models\Asset;
use App\Models\HistoryTopup;
use App\Traits\WhatsappTrait;
use Carbon\Carbon;

class TopUpReportController extends Controller
{
    use WhatsappTrait;

    public function index() {

        $customers = Customer::where('status', '=', 1)->get();
        return view('Report.TopUpReport.indextopupreport', compact('customers'));
    }

    public function getTopUpReport(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';
        $status = $request->status;
        
        $startDate = $request->startDate ? date('Y-m-d', strtotime($request->startDate)) : Carbon::now()->startOfMonth();
        $endDate = $request->endDate ? date('Y-m-d', strtotime($request->endDate)) : Carbon::now()->endOfMonth();

        
        $topup = HistoryTopup::where('tbl_history_topup.status', '!=', 'canceled');

        $payment = PaymentInvoice::join('tbl_payment_customer', 'tbl_payment_invoice.payment_id', '=', 'tbl_payment_customer.id');
        
        $isCustomerRole = auth()->user() && auth()->user()->role === 'customer';
        if ($isCustomerRole) {
            // Add condition to show only records for the logged-in customer
            $topup->join('tbl_pembeli', 'tbl_history_topup.customer_id', '=', 'tbl_pembeli.id')
                    ->where('tbl_pembeli.user_id', auth()->user()->id);
            
            $payment->join('tbl_pembeli', 'tbl_payment_customer.pembeli_id', '=', 'tbl_pembeli.id')
                    ->where('tbl_pembeli.user_id', auth()->user()->id);
        }

        if ($request->startDate){
            $startDate = date('Y-m-d', strtotime($request->startDate));
            $topup->whereDate('date', '>=', $startDate);    
        }
        if ($request->endDate){
            $endDate = date('Y-m-d', strtotime($request->endDate));
            $topup->whereDate('date', '<=', $endDate);
        }
        if ($request->customer){
            $topup->where('customer_id', '=', $request->customer);
        }
        
        $payment = $payment->get();
        $topup = $topup->get();
        // Determine if the user is a customer
        
        $output = '
                    <h5 style="text-align:center; width:100%">' 
                        . \Carbon\Carbon::parse($startDate)->format('d M Y') . ' - ' 
                        . \Carbon\Carbon::parse($endDate)->format('d M Y') . 
                    '</h5>

                    <div class="card-body">   
                    <table class="table" width="100%">
                    <thead>
                        <th width="15%" style="text-align:center;">Topup Date</th>
                        <th width="25%" style="text-align:center;">Customer</th>
                        <th width="10%" style="text-align:center;">In (Kg)</th>
                        <th width="10%" style="text-align:center;">Out (Kg)</th>
                        <th width="15%" style="text-align:center;">Saldo (Kg)</th>';
        
        // Add "Value (Rp)" column only if the user is not a customer
        if (!$isCustomerRole) {
            $output .= '<th width="20%" style="text-align:center;">Value (Rp)</th>';
        }

        $output .= '<th width="5%" style="text-align:center;">Status</th>
                    </thead>
                    <tbody>';
        
        foreach($topup as $data){
            $output .='<tr>
                            <td style="text-align:center;">' . \Carbon\Carbon::parse($data->date)->format('d M Y') . '</td>
                            <td style="text-align:center;">' . ($data->customer_name) . '</td>
                            <td style="text-align:center;">' . number_format($data->remaining_points, 2) . '</td>
                            <td style="text-align:center;">' . number_format($data->remaining_points - $data->balance, 2) . '</td>
                            <td style="text-align:center;">' . number_format($data->balance, 2) . '</td>';
            
            // Add "Value (Rp)" column value only if the user is not a customer
            if (!$isCustomerRole) {
                $output .= '<td style="text-align:center;"> Rp. ' . number_format($data->balance * $data->price_per_kg, 2) . '</td>';
            }

            $output .= '<td style="text-align:center;"> IN </td>
                        </tr>';
        }

        foreach($payment as $data2){
            $output .='<tr>
                            <td style="text-align:center;">' . \Carbon\Carbon::parse($data2->payment_buat)->format('d M Y') . '</td>
                            <td style="text-align:center;">' . ($data2->payment->pembeli->nama_pembeli) . '</td>
                            <td style="text-align:center;">' . number_format($data2->remaining_points, 2) . '</td>
                            <td style="text-align:center;">' . number_format($data2->remaining_points - $data->balance, 2) . '</td>
                            <td style="text-align:center;">' . number_format($data2->balance, 2) . '</td>';
            
            // Add "Value (Rp)" column value only if the user is not a customer
            if (!$isCustomerRole) {
                $output .= '<td style="text-align:center;"> Rp. ' . number_format($data->balance * $data->price_per_kg, 2) . '</td>';
            }

            $output .= '<td style="text-align:center;"> IN </td>
                        </tr>';
        }
        
        $output .= '</table> </div>';

        return $output;
    }


    public function generatePdf(Request $request)
    {
        $htmlOutput = $this->getTopUpReport($request);

        $pdf = PDF::loadHTML($htmlOutput);
        return $pdf->download('Top Up Report.pdf');
    }
}
