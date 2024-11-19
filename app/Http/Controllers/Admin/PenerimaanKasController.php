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

        $payment = Payment::whereDate('payment_date', '>=', $startDate)
                            ->whereDate('payment_date', '<=', $endDate);

        if ($request->customer){    
            $payment->where('customer_id', '=', $request->customer);
        }

        if ($request->payment){    
            $payment->where('payment_method_id', '=', $request->payment);
        }
        
        $payment = $payment->get();
        
        $output = '
                    <h5 style="text-align:center; width:100%">' 
                        . \Carbon\Carbon::parse($startDate)->format('d M Y') . ' - ' 
                        . \Carbon\Carbon::parse($endDate)->format('d M Y') . 
                    '</h5>

                    <div class="card-body">   
                    <table class="table" width="100%">
                    <thead>
                        <th width="15%" style="text-left">Date</th>
                        <th width="15%">Transfer Date</th>
                        <th width="15%">Customer</th>
                        <th width="30%" class="text-right">No Invoice</th>
                        <th width="25%" class="text-right">Total</th>
                    </thead>
                    <tbody>';
        
        foreach($payment as $data){
            $output .='<tr>
                            <td>' . \Carbon\Carbon::parse($data->created_date)->format('d M Y') . '</td>
                            <td>' . \Carbon\Carbon::parse($data->payment_date)->format('d M Y') . '</td>
                            <td class="text-center">' . ($data->customer_id) . ' </td>
                            <td class="text-right">' . number_format($data->invoice_id, 2) . '</td>
                            <td class="text-right">' . number_format($data->amount - $data->discount, 2) . '</td>
                        </tr>';
        }
        
        $output .= '</table> </div>';

        return $output;
    }

    public function generatePdf(Request $request)
    {
        //
    }
}
