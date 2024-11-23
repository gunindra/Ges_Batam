<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\SupInvoice;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Customer;
use App\Models\Invoice;
use App\Traits\WhatsappTrait;

class SoaVendorController extends Controller
{
    use WhatsappTrait;

    public function index() {

        $vendors = Vendor::select('id', 'name', 'phone')->get();

        return view('Report.SoaVendor.indexSoaVendor', compact('vendors'));
    }

    public function getSoaVendor(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';
        $status = $request->status;
        $customer = $request->customer;

        $invoice = SupInvoice::where('status_bayar', '=', 'Belum Lunas')
                    ->where('vendor_id', '=', $customer);


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
                            <td>' . ($data->no_invoice) . '</td>
                            <td class="text-right">' . number_format($belum_bayar, 2) . '</td>
                        </tr>';
        }

        $output .= '</table> </div>';

        return $output;
    }


    public function soaWA(Request $request)
    {
        try {
            $vendor_id = $request->vendor;
            $vendor = Vendor::where('id', '=', $vendor_id)->first();

            if (!$vendor) {
                return redirect()->back()->withErrors(['error' => 'Mohon pilih Vendor yang diinginkan']);
            }

            $invoice = SupInvoice::where('status_bayar', '=', 'Belum Lunas')
                ->where('vendor_id', '=', $vendor_id);

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
                $pesanTerkirimDenganFile = $this->kirimPesanWhatsapp($vendor->phone, $pesan, $fileUrl);
                $pesanTerkirim = $this->kirimPesanWhatsapp($vendor->phone, $pesan);

                if (!$pesanTerkirim || !$pesanTerkirimDenganFile) {
                    return redirect()->back()->withErrors(['error' => 'Gagal mengirim pesan WhatsApp ke ' . $vendor->phone]);
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
}
