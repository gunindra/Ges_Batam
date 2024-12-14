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
use App\Models\Asset;
use App\Models\HistoryTopup;
use App\Traits\WhatsappTrait;
use Carbon\Carbon;
use Log;
use Maatwebsite\Excel\Facades\Excel;
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
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';
        $status = $request->status;

        $startDate = $request->startDate ? date('Y-m-d', strtotime($request->startDate)) : Carbon::now()->startOfMonth();
        $endDate = $request->endDate ? date('Y-m-d', strtotime($request->endDate)) : Carbon::now()->endOfMonth();

        $topup = HistoryTopup::where('status', '!=', 'cancel');

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

        $topup = $topup->get();


        $output = '
                    <h5 style="text-align:center; width:100%">'
                        . \Carbon\Carbon::parse($startDate)->format('d M Y') . ' - '
                        . \Carbon\Carbon::parse($endDate)->format('d M Y') .
                    '</h5>

                    <div class="card-body">
                    <table class="table" width="100%">
                    <thead>
                        <th width="20%" style="text-align:center;">Topup Date</th>
                        <th width="30%" style="text-align:center;">Customer</th>
                        <th width="10%" style="text-align:center;">In (Kg)</th>
                        <th width="10%" style="text-align:center;">Out (Kg)</th>
                        <th width="15%" style="text-align:center;">Saldo (Kg)</th>
                        <th width="15%" style="text-align:center;">Status</th>
                    </thead>
                    <tbody>';

        foreach($topup as $data){
            $output .='<tr>
                            <td style="text-align:center;">' . \Carbon\Carbon::parse($data->date)->format('d M Y') . '</td>
                            <td style="text-align:center;">' . ($data->customer_name) . '</td>
                            <td style="text-align:center;">' . number_format($data->remaining_points, 2) . '</td>
                            <td style="text-align:center;">' . number_format($data->remaining_points - $data->balance, 2) . '</td>
                            <td style="text-align:center;">' . number_format($data->balance, 2) . '</td>
                            <td style="text-align:center;">' . ($data->status) . '</td>
                        </tr>';
        }

        $output .= '</table> </div>';

        return $output;
    }
    public function generatePdf(Request $request)
    {
        $startDate = $request->input('startDate') ? Carbon::parse($request->input('startDate'))->format('d M Y') : '-';
        $endDate = $request->input('endDate') ? Carbon::parse($request->input('endDate'))->format('d M Y') : '-';
        $customer = $request->nama_pembeli ?? null;
    
        try {
            // Query Topup
            $topupQuery = HistoryTopup::where('status', '!=', 'cancel');
    
            // Tambahkan filter customer jika tersedia
            if (!is_null($customer)) {
                $topupQuery->where('customer_id', '=', $customer);
            }
    
            // Tambahkan filter tanggal jika diisi
            if ($startDate !== '-') {
                $topupQuery->whereDate('date', '>=', Carbon::parse($request->input('startDate')));
            }
    
            if ($endDate !== '-') {
                $topupQuery->whereDate('date', '<=', Carbon::parse($request->input('endDate')));
            }
    
            $topup = $topupQuery->get();
    
            // Ambil nama pembeli jika customer ID diberikan
            $customerName = '-';
            if (!is_null($customer)) {
                $customerData = DB::table('tbl_pembeli')->where('id', $customer)->first();
                $customerName = $customerData ? $customerData->nama_pembeli : 'Unknown';
            }
    
            // Generate PDF
            $pdf = pdf::loadView('exportPDF.topupreport', [
                'topup' => $topup,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'customer' => $customerName,
            ])
            ->setPaper('A4', 'portrait')
            ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
            ->setWarnings(false);
    
            // Buat folder untuk menyimpan PDF jika belum ada
            $folderPath = storage_path('app/public/topupreports');
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }
    
            // Tentukan nama file untuk PDF
            $fileName = 'topup_report_' . (string) Str::uuid() . '.pdf';
            $filePath = $folderPath . '/' . $fileName;
    
            // Simpan PDF
            $pdf->save($filePath);
    
            // Kembalikan URL PDF yang dihasilkan
            $url = asset('storage/topupreports/' . $fileName);
            return response()->json(['url' => $url]);
    
        } catch (\Exception $e) {
            Log::error('Error generating Asset Report PDF: ' . $e->getMessage(), ['exception' => $e]);
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
