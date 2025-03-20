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
use Illuminate\Support\Facades\Storage;
use Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use Str;

class TopUpReportController extends Controller
{
    use WhatsappTrait;

    public function index() {

        $customers = Customer::where('status', '=', 1)->get();
        return view('Report.TopUpReport.indextopupreport', compact('customers'));
    }

    public function getTopUpReport(Request $request)
    {
        // dd($request->all());
        $companyId = session('active_company_id');
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';
        $status = $request->status;

        $startDate = $request->startDate
        ? Carbon::parse($request->startDate)->format('Y-m-d')
        : Carbon::now()->startOfMonth()->format('Y-m-d');

        $endDate = $request->endDate
        ? Carbon::parse($request->endDate)->format('Y-m-d')
        : Carbon::now()->endOfMonth()->format('Y-m-d');


        $topup = HistoryTopup::leftJoin('tbl_pembeli', 'tbl_history_topup.customer_id', '=', 'tbl_pembeli.id') // Ubah ke leftJoin
            ->where('tbl_history_topup.status', '!=', 'canceled')
            ->where('tbl_history_topup.company_id', $companyId)
            ->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate)
            ->select('tbl_history_topup.*', 'tbl_pembeli.marking');

        $payment = PaymentInvoice::join('tbl_payment_customer', 'tbl_payment_invoice.payment_id', '=', 'tbl_payment_customer.id')
            ->leftJoin('tbl_pembeli', 'tbl_payment_customer.pembeli_id', '=', 'tbl_pembeli.id') // Ubah ke leftJoin
            ->where('tbl_payment_invoice.kuota', '!=', 0)
            ->where('tbl_payment_customer.company_id', $companyId)
            ->whereDate('payment_buat', '>=', $startDate)
            ->whereDate('payment_buat', '<=', $endDate)
            ->select('tbl_payment_invoice.*', 'tbl_pembeli.marking');


        $isCustomerRole = auth()->user() && auth()->user()->role === 'customer';
        if ($isCustomerRole) {
            // Hanya tambahkan WHERE tanpa JOIN ulang
            $topup->where('tbl_pembeli.user_id', auth()->user()->id);
            $payment->where('tbl_pembeli.user_id', auth()->user()->id);
        }

        if ($request->startDate) {
            $startDate = date('Y-m-d', strtotime($request->startDate));
            $topup->whereDate('date', '>=', $startDate);
            $payment->whereDate('payment_buat', '>=', $startDate);
        }
        if ($request->endDate) {
            $endDate = date('Y-m-d', strtotime($request->endDate));
            $topup->whereDate('date', '<=', $endDate);
            $payment->whereDate('payment_buat', '<=', $endDate);
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
                <th width="25%" style="text-align:center;">Marking</th>
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
                                <td style="text-align:center;">' . ($data->marking) . '</td>
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
                                <td style="text-align:center;">' . ($data->payment->pembeli->marking) . '</td>
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
        $request->validate([
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date|after_or_equal:startDate',
            'nama_pembeli' => 'nullable|exists:tbl_pembeli,id',
        ]);

        $companyId = session('active_company_id');
        $startDate = $request->input('startDate')
            ? Carbon::parse($request->input('startDate'))->format('Y-m-d')
            : Carbon::now()->startOfMonth()->format('Y-m-d');

        $endDate = $request->input('endDate')
            ? Carbon::parse($request->input('endDate'))->format('Y-m-d')
            : Carbon::now()->endOfMonth()->format('Y-m-d');
        $customerId = $request->input('nama_pembeli') ?? null;

        try {
            // Query Topup
            $topup = HistoryTopup::join('tbl_pembeli', 'tbl_history_topup.customer_id', '=', 'tbl_pembeli.id')
                ->where('tbl_history_topup.status', '!=', 'canceled')
                ->where('tbl_history_topup.company_id', $companyId)
                ->when($startDate, fn($query) => $query->whereDate('tbl_history_topup.date', '>=', $startDate))
                ->when($endDate, fn($query) => $query->whereDate('tbl_history_topup.date', '<=', $endDate))
                ->select(
                    'tbl_history_topup.id',
                    'tbl_history_topup.date',
                    'tbl_history_topup.customer_id',
                    'tbl_history_topup.remaining_points as value',
                    'tbl_pembeli.marking as customer_name',
                    'tbl_history_topup.price_per_kg', // Pastikan kolom ini ada
                    DB::raw("'topup' as type")
                )
                ->get();

            // Query Payment
            $payment = PaymentInvoice::join('tbl_payment_customer', 'tbl_payment_invoice.payment_id', '=', 'tbl_payment_customer.id')
                ->join('tbl_pembeli', 'tbl_payment_customer.pembeli_id', '=', 'tbl_pembeli.id')
                ->where('tbl_payment_invoice.kuota', '!=', 0)
                ->where('tbl_payment_customer.company_id', $companyId)
                ->when($startDate, fn($query) => $query->whereDate('tbl_payment_customer.payment_buat', '>=', $startDate))
                ->when($endDate, fn($query) => $query->whereDate('tbl_payment_customer.payment_buat', '<=', $endDate))
                ->select(
                    'tbl_payment_invoice.id',
                    'tbl_payment_customer.payment_buat as date',
                    'tbl_payment_customer.pembeli_id as customer_id',
                    'tbl_payment_invoice.kuota as value',
                    'tbl_pembeli.marking as customer_name',
                    'tbl_payment_invoice.amount', // Pastikan kolom ini ada
                    DB::raw("'payment' as type")
                )
                ->get();

            // Gabungkan & Urutkan Data
            $combined = $topup->concat($payment)->sortBy('date')->values();

            // Ambil Nama Customer Jika Ada
            $customer = Customer::find($customerId);

            // Generate PDF
            $pdf = PDF::loadView('exportPDF.topupreport', [
                'marking' => $customer->marking ?? '-',
                'combined' => $combined,
                'startDate' => $startDate ? Carbon::parse($startDate)->format('d M Y') : '-',
                'endDate' => $endDate ? Carbon::parse($endDate)->format('d M Y') : '-',
            ])
            ->setPaper('A4', 'portrait')
            ->setWarnings(false);

            // Simpan PDF ke Storage Private
            $fileName = 'topup_report_' . now()->format('YmdHis') . '.pdf';
            $filePath = 'public/topupreports/' . $fileName;
            Storage::put($filePath, $pdf->output());

            // Kembalikan URL yang Bisa Diakses
            return response()->json(['url' => Storage::url($filePath)]);

        } catch (\Exception $e) {
            Log::error('Error generating Topup Report PDF: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'An error occurred while generating the PDF'], 500);
        }
    }





        public function exportTopupReport(Request $request)
    {
        $startDate = $request->input('startDate');
        $customer = $request->nama_pembeli ?? '-';
        $endDate = $request->input('endDate');

        return Excel::download(new TopupReportExport($customer, $startDate, $endDate), 'topup_report.xlsx');
    }
}
