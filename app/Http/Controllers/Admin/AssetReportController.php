<?php

namespace App\Http\Controllers\Admin;
use App\Exports\AssetReportExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Asset;
use App\Traits\WhatsappTrait;
use Carbon\Carbon;
use Log;
use Maatwebsite\Excel\Facades\Excel;
use Str;

class AssetReportController extends Controller
{
    use WhatsappTrait;

    public function index()
    {

        return view('Report.AssetReport.indexassetreport');
    }

    public function getAssetReport(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';
        $status = $request->status;
        $customer = $request->customer;

        $startDate = $request->startDate ? date('Y-m-d', strtotime($request->startDate)) : Carbon::now()->startOfMonth();
        $endDate = $request->endDate ? date('Y-m-d', strtotime($request->endDate)) : Carbon::now()->endOfMonth();

        $asset = Asset::select(
            'tbl_assets.id',
            'tbl_assets.acquisition_price',
            'tbl_assets.estimated_age',
            'tbl_assets.asset_name',
            'tbl_assets.acquisition_date',
            DB::raw('IFNULL(SUM(tbl_jurnal.totalcredit), 0) as total_credit'),
            // // Subquery for the beginning balance
            DB::raw("(SELECT IFNULL(SUM(totalcredit), 0) 
                                    FROM tbl_jurnal 
                                    WHERE tbl_jurnal.asset_id = tbl_assets.id 
                                    AND tbl_jurnal.tanggal < '{$startDate}') as total_credit_before")
        )
            ->join('tbl_jurnal', 'tbl_assets.id', '=', 'tbl_jurnal.asset_id')
            ->whereDate('tbl_jurnal.tanggal', '>=', $startDate)
            ->whereDate('tbl_jurnal.tanggal', '<=', $endDate)
            ->groupBy(
                'tbl_assets.id',
                'tbl_assets.acquisition_price',
                'tbl_assets.estimated_age',
                'tbl_assets.asset_name',
                'tbl_assets.acquisition_date'
            )
            ->get()
            ->map(function ($item) {
                // Adjusting the balance calculation                  
                // Use the correct beginning balance from the subquery
                $item->beginning_balance = $item->acquisition_price - $item->total_credit_before;
                $item->ending_balance = $item->beginning_balance - $item->total_credit;
                return $item;
            });

        $output = '
                    <h5 style="text-align:center; width:100%">'
            . \Carbon\Carbon::parse($startDate)->format('d M Y') . ' - '
            . \Carbon\Carbon::parse($endDate)->format('d M Y') .
            '</h5>

                    <div class="card-body">   
                    <table class="table" width="100%">
                    <thead>
                        <th width="15%" style="text-left">Date</th>
                        <th width="30%">Asset Name</th>
                        <th width="15%">Estimated Age</th>
                        <th width="20%" class="text-right">Begining Value</th>
                        <th width="20%" class="text-right">Ending Value</th>
                    </thead>
                    <tbody>';

        foreach ($asset as $data) {
            $output .= '<tr>
                            <td>' . \Carbon\Carbon::parse($data->acquisition_date)->format('d M Y') . '</td>
                            <td>' . ($data->asset_name) . '</td>
                            <td class="text-center">' . ($data->estimated_age) . ' Month </td>
                            <td class="text-right">' . number_format($data->beginning_balance, 2) . '</td>
                            <td class="text-right">' . number_format($data->ending_balance, 2) . '</td>
                        </tr>';
        }

        $output .= '</table> </div>';

        return $output;
    }

    // public function generatePdf(Request $request)
    // {
    //     $htmlOutput = $this->getAssetReport($request);

    //     $pdf = PDF::loadHTML($htmlOutput);
    //     return $pdf->download('Asset Report.pdf');
    // }
    public function exportAssetReport(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        return Excel::download(new AssetReportExport($startDate, $endDate), 'asset_report.xlsx');
    }
    public function generatePdf(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        try {
            $query = Asset::select(
                'tbl_assets.id',
                'tbl_assets.acquisition_price',
                'tbl_assets.estimated_age',
                'tbl_assets.asset_name',
                'tbl_assets.acquisition_date',
                DB::raw('COALESCE(SUM(tbl_jurnal.totalcredit), 0) as total_credit'),
                DB::raw("(SELECT COALESCE(SUM(totalcredit), 0) 
                          FROM tbl_jurnal 
                          WHERE tbl_jurnal.asset_id = tbl_assets.id) as total_credit_before")
            )
            ->leftJoin('tbl_jurnal', 'tbl_assets.id', '=', 'tbl_jurnal.asset_id')
            ->groupBy(
                'tbl_assets.id',
                'tbl_assets.acquisition_price',
                'tbl_assets.estimated_age',
                'tbl_assets.asset_name',
                'tbl_assets.acquisition_date'
            );
    
            // Filter berdasarkan tanggal jika tersedia
            if ($request->startDate && $request->endDate) {
                $query->whereBetween('tbl_jurnal.tanggal', [
                    Carbon::parse($request->startDate)->format('Y-m-d'),
                    Carbon::parse($request->endDate)->format('Y-m-d')
                ]);
            }
    
            // Ambil hasil query dan proses data
            $assets = $query->get()->map(function ($asset) {
                $asset->beginning_balance = $asset->acquisition_price - $asset->total_credit_before;
                $asset->ending_balance = $asset->beginning_balance - $asset->total_credit;
                return $asset;
            });
    
            // Pastikan data tidak kosong
            if ($assets->isEmpty()) {
                return response()->json(['error' => 'No data available for the selected date range'], 404);
            }
    
            // Generate PDF
            $pdf = pdf::loadView('exportPDF.assetreportpdf', [
                'assets' => $assets,
                'startDate' => $startDate,
                'endDate' => $endDate,
            ])
            ->setPaper('A4', 'portrait')
            ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
            ->setWarnings(false);
    
            // Buat folder untuk menyimpan PDF jika belum ada
            $folderPath = storage_path('app/public/assetreports');
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }
    
            // Tentukan nama file untuk PDF
            $fileName = 'assets_report_' . (string) Str::uuid() . '.pdf';
            $filePath = $folderPath . '/' . $fileName;
    
            // Simpan PDF
            $pdf->save($filePath);
    
            // Kembalikan URL PDF yang dihasilkan
            $url = asset('storage/assetreports/' . $fileName);
            return response()->json(['url' => $url]);
    
        } catch (\Exception $e) {
            Log::error('Error generating Asset Report PDF: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'An error occurred while generating the PDF'], 500);
        }
    }
}    
