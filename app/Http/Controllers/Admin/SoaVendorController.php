<?php

namespace App\Http\Controllers\Admin;
use App\Exports\SoaVendorExport;
use App\Http\Controllers\Controller;
use App\Models\SupInvoice;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Customer;
use App\Models\Invoice;
use App\Traits\WhatsappTrait;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class SoaVendorController extends Controller
{
    use WhatsappTrait;

    public function index() {
        $companyId = session('active_company_id');
        $vendors = Vendor::select('id', 'name', 'phone')
        ->where('tbl_vendors.company_id', $companyId)
        ->get();

        return view('Report.SoaVendor.indexSoaVendor', compact('vendors'));
    }

    public function getSoaVendor(Request $request)
    {
        $companyId = session('active_company_id');
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';
        $status = $request->status;
        $customer = $request->customer;

        $invoice = SupInvoice::where('status_bayar', '=', 'Belum lunas')
                    ->where('vendor_id', '=', $customer)
                    ->where('tbl_sup_invoice.company_id', $companyId);


        if ($request->startDate){
            $invoice->whereDate('tanggal', '>=', date('Y-m-d', strtotime($request->startDate)));
        }
        if ($request->endDate){
            $invoice->whereDate('tanggal', '<=', date('Y-m-d', strtotime($request->endDate)));
        }

        $invoice = $invoice->get();

        $output = '<div class="card-body">
                    <table class="table" width="100%">
                    <thead>
                        <th width="30%" style="text-left">Date</th>
                        <th width="30%">No Invoice</th>
                        <th width="20%" class="text-right">Jumlah Tagihan</th>
                    </thead>
                    <tbody>';

        $belum_bayar = 0;
        foreach($invoice as $data){
            $belum_bayar = $data->total_harga - $data->total_bayar;
            $output .='<tr>
                            <td>' . \Carbon\Carbon::parse($data->tanggal)->format('d-m-Y') . '</td>
                            <td>' . ($data->invoice_no) . '</td>
                            <td class="text-right">' . number_format($belum_bayar, 2) . '</td>
                        </tr>';
        }

        $output .= '</table> </div>';

        return $output;
    }


    public function soaWA(Request $request)
    {
        try {
            $companyId = session('active_company_id');
            $vendor_id = $request->vendor;
            $vendor = Vendor::where('id', '=', $vendor_id)->first();

            if (!$vendor) {
                return redirect()->back()->withErrors(['error' => 'Mohon pilih Vendor yang diinginkan']);
            }

            $invoice = SupInvoice::where('status_bayar', '=', 'Belum lunas')
                ->where('vendor_id', '=', $vendor_id)
                ->where('tbl_sup_invoice.company_id', $companyId);

            $startDate = '';
            $endDate = '';

            if ($request->startDate) {
                $invoice->whereDate('tanggal', '>=', date('Y-m-d', strtotime($request->startDate)));
                $startDate = date('Y-m-d', strtotime($request->startDate));
            }
            if ($request->endDate) {
                $invoice->whereDate('tanggal', '<=', date('Y-m-d', strtotime($request->endDate)));
                $endDate = date('Y-m-d', strtotime($request->endDate));
            }

            $invoice = $invoice->get();

            if ($invoice->isEmpty()) {
                return redirect()->back()->withErrors(['error' => 'Tidak ada data Invoice yang ditemukan']);
            }

            $pdf = Pdf::loadView('exportPDF.soaVendor', [
                'startDate' => $startDate,
                'endDate' => $endDate,
                'invoice' => $invoice,
                'vendor' => $vendor
            ]);

            $directoryPath = storage_path('app/public/soa');
            $pdfFileName = 'Statement_of_Account_' . $vendor->name . '.pdf';
            $filePath = $directoryPath . '/' . $pdfFileName;

            if (!file_exists($directoryPath)) {
                mkdir($directoryPath, 0755, true);
            }

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $pdf->save($filePath);

            $pesan = "Berikut kami lampirkan Statement of Account dari daftar Invoice yang belum dilunaskan. Terima kasih.";
            $fileUrl = asset('storage/soa/' . $pdfFileName);

            if ($vendor->phone) {
                // Tambahkan kode negara 62 jika belum ada
                $phone = $vendor->phone;
                if (!str_starts_with($phone, '62')) {
                    // Jika nomor dimulai dengan 0, ganti 0 dengan 62
                    if (str_starts_with($phone, '0')) {
                        $phone = '62' . substr($phone, 1);
                    } else {
                        $phone = '62' . $phone;
                    }
                }

                $sender = '62' . DB::table('tbl_ptges')->value('phones');

                $pesanTerkirimDenganFile = $this->kirimPesanWhatsapp($phone, $pesan, $fileUrl, $sender);
                $pesanTerkirim = $this->kirimPesanWhatsapp($phone, $pesan, null, $sender);

                if (!$pesanTerkirimDenganFile) {
                    Log::error('Gagal mengirim pesan dengan file ke ' . $vendor->no_wa);
                }

                if (!$pesanTerkirim) {
                    Log::error('Gagal mengirim pesan teks ke ' . $vendor->no_wa);
                }

                if (!$pesanTerkirimDenganFile && !$pesanTerkirim) {
                    return redirect()->back()->withErrors(['error' => 'Gagal mengirim semua pesan WhatsApp ke ' . $vendor->no_wa]);
                }

                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            } else {
                return redirect()->back()->withErrors(['error' => 'Nomor WhatsApp vendor tidak ditemukan. Mohon periksa kembali data vendor.']);
            }

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    public function exportSoaVendorReport(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        // $idname = $request->name;
        // $q = DB::table('tbl_vendors')->where('id', $idname)->first();
        // $customer = $q->name;
        $customer = $request->name ?? '-';

        return Excel::download(new SoaVendorExport($startDate, $endDate,$customer), 'Soa_Vendor.xlsx');
    }
}
