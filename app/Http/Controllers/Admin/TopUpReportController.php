<?php

namespace App\Http\Controllers\Admin;
use App\Exports\AssetReportExport;
use App\Exports\TopupReportExport;
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
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;

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
    $payment = PaymentInvoice::join('tbl_payment_customer', 'tbl_payment_invoice.payment_id', '=', 'tbl_payment_customer.id')
                              ->where('tbl_payment_invoice.kuota', '!=', 0);

    $isCustomerRole = auth()->user() && auth()->user()->role === 'customer';
    if ($isCustomerRole) {
        $topup->join('tbl_pembeli', 'tbl_history_topup.customer_id', '=', 'tbl_pembeli.id')
              ->where('tbl_pembeli.user_id', auth()->user()->id);

        $payment->join('tbl_pembeli', 'tbl_payment_customer.pembeli_id', '=', 'tbl_pembeli.id')
                ->where('tbl_pembeli.user_id', auth()->user()->id);
    }

    if ($request->startDate) {
        $startDate = date('Y-m-d', strtotime($request->startDate));
        $topup->whereDate('date', '>=', $startDate);
        $payment->whereDate('payment_buat', '>=', $startDate);
    }
    if ($request->endDate) {
        $endDate = date('Y-m-d', strtotime($request->endDate));
        $topup->whereDate('date', '<=', $endDate);
        $payment->whereDate('payment_buat', '<=', $startDate);
    }
    if ($request->customer) {
        $topup->where('customer_id', '=', $request->customer);
        $payment->where('tbl_payment_customer.pembeli_id', '=', $request->customer);
    }

    // Retrieve data
    $topup = $topup->get()->map(function ($item) {
        $item->type = 'topup';
        return $item;
    });

    $payment = $payment->get()->map(function ($item) {
        $item->type = 'payment';
        $item->date = $item->payment_buat;
        $item->customer_id = $item->pembeli_id;
        return $item;
    });
    // Combine and sort by date
    $combined = $topup->concat($payment)->sortBy('date');

    $output = '
        <h5 style="text-align:center; width:100%">' 
            . \Carbon\Carbon::parse($startDate)->format('d M Y') . ' - ' 
            . \Carbon\Carbon::parse($endDate)->format('d M Y') . 
        '</h5>

        <div class="card-body">   
        <table class="table" width="100%">
        <thead>
            <th width="15%" style="text-align:center;">Date</th>
            <th width="25%" style="text-align:center;">Customer</th>
            <th width="10%" style="text-align:center;">In (Kg)</th>
            <th width="10%" style="text-align:center;">Out (Kg)</th>
            <th width="15%" style="text-align:center;">Saldo (Kg)</th>';

    if (!$isCustomerRole) {
        $output .= '<th width="20%" style="text-align:center;">Value (Rp)</th>';
    }

    $output .= '<th width="5%" style="text-align:center;">Status</th>
                </thead>
                <tbody>';

    // Dynamic saldo tracking for each customer
    $customerSaldo = [];

    foreach ($combined as $data) {
        $customerId = $data->customer_id;
    
        if (!isset($customerSaldo[$customerId])) {
            $customerSaldo[$customerId] = 0; // Initialize saldo for the customer
        }

        if ($data->type === 'topup') {
            $customerSaldo[$customerId] += $data->remaining_points;

            $output .= '<tr>
                            <td style="text-align:center;">' . \Carbon\Carbon::parse($data->date)->format('d M Y') . '</td>
                            <td style="text-align:center;">' . ($data->customer_name) . '</td>
                            <td style="text-align:center;">' . number_format($data->remaining_points, 2) . '</td>
                            <td style="text-align:center;"> 0 </td>
                            <td style="text-align:center;">' . number_format($customerSaldo[$customerId], 2) . '</td>';

            if (!$isCustomerRole) {
                $output .= '<td style="text-align:center;"> Rp. ' . number_format($customerSaldo[$customerId] * $data->price_per_kg, 2) . '</td>';
            }

            $output .= '<td style="text-align:center;"> IN </td>
                        </tr>';
        } elseif ($data->type === 'payment') {
            $customerSaldo[$customerId] -= $data->kuota;
            $price = ($data->kuota != 0) ? ($data->amount / $data->kuota) : 0;

            $output .= '<tr>
                            <td style="text-align:center;">' . \Carbon\Carbon::parse($data->date)->format('d M Y') . '</td>
                            <td style="text-align:center;">' . ($data->payment->pembeli->nama_pembeli) . '</td>
                            <td style="text-align:center;"> 0 </td>
                            <td style="text-align:center;">' . number_format($data->kuota, 2) . '</td>
                            <td style="text-align:center;">' . number_format($customerSaldo[$customerId], 2) . '</td>';

            if (!$isCustomerRole) {
                $output .= '<td style="text-align:center;"> Rp. ' . number_format($customerSaldo[$customerId] * $price, 2) . '</td>';
            }

            $output .= '<td style="text-align:center;"> OUT </td>
                        </tr>';
        }
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
    public function exportTopupReport(Request $request)
    {
        $startDate = $request->input('startDate');
        $customer = $request->nama_pembeli;
        $endDate = $request->input('endDate');

        return Excel::download(new TopupReportExport($customer, $startDate, $endDate), 'topup_report.xlsx');
    }
}
