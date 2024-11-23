<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Customer;
use App\Models\Invoice;
use App\Traits\WhatsappTrait;

class SoaController extends Controller
{
    use WhatsappTrait;

    public function index() {

        $customers = Customer::where('status', '=', 1)->get();
        return view('Report.Soa.indexbalance', compact('customers'));
    }

    public function getSoa(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';
        $status = $request->status;
        $customer = $request->customer;

        $invoice = Invoice::where('status_bayar', '=', 'Belum Lunas')
                    ->where('pembeli_id', '=', $customer);


        if ($request->startDate){
            $invoice->whereDate('tanggal_invoice', '>=', date('Y-m-d', strtotime($request->startDate)));
        }
        if ($request->endDate){
            $invoice->whereDate('tanggal_invoice', '<=', date('Y-m-d', strtotime($request->endDate)));
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
                            <td>' . \Carbon\Carbon::parse($data->tanggal_invoice)->format('d-m-Y') . '</td>
                            <td>' . ($data->no_invoice) . '</td>
                            <td class="text-right">' . number_format($belum_bayar, 2) . '</td>
                        </tr>';
        }

        $output .= '</table> </div>';

        return $output;
    }

    public function soaWA(Request $request)
    {
        try{
            $customer_id = $request->customer;
            $customer = Customer::where('id', '=', $customer_id)->first();

            if (!$customer) {
                return redirect()->back()->withErrors(['error' => 'Mohon Pilih Customer yang diinginkan']);
            }

            $invoice = Invoice::where('status_bayar', '=', 'Belum Lunas')
                        ->where('pembeli_id', '=', $customer_id);
            $startDate = '';
            $endDate = '';

            if ($request->startDate){
                $invoice->whereDate('tanggal_invoice', '>=', date('Y-m-d', strtotime($request->startDate)));
                $startDate = date('Y-m-d', strtotime($request->startDate));
            }
            if ($request->endDate){
                $invoice->whereDate('tanggal_invoice', '<=', date('Y-m-d', strtotime($request->endDate)));
                $endDate = date('Y-m-d', strtotime($request->endDate));
            }

            $invoice = $invoice->get();

            if ($invoice->isEmpty()) {
                return redirect()->back()->withErrors(['error' => 'Tidak ada data Invoice yang ditemukan']);
            }

            $pdf = Pdf::loadView('exportPDF.soa',
            [
                'startDate' => $startDate,
                'endDate' => $endDate,
                'invoice' => $invoice,
                'customer' => $customer
            ]);

            $directoryPath = storage_path('app/public/soa');
            $pdfFileName = 'Statement of Account_'. $customer->nama_pembeli .'.pdf';
            $filePath = $directoryPath . '/' . $pdfFileName;

            // Check if the directory exists; if not, create it
            if (!file_exists($directoryPath)) {
                mkdir($directoryPath, 0755, true);
            }

            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $pdf->save($filePath);

            $pesan = "Beikut kita lampirkan Statement of Account dari list Invoice yang belum dilunaskan, Terima Kasih";
            $fileUrl = asset('storage/soa/' . $pdfFileName);

            if ($customer->no_wa) {
                $pesanTerkirimDenganFile = $this->kirimPesanWhatsapp($customer->no_wa, $pesan, $fileUrl);
                $pesanTerkirim = $this->kirimPesanWhatsapp($customer->no_wa, $pesan);

                if (!$pesanTerkirim || !$pesanTerkirimDenganFile) {
                    return redirect()->back()->withErrors(['error' => 'Gagal mengirim pesan WhatsApp ke ' . $customer->no_wa]);
                }

            } else {
                return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan, silahkan periksa kembali Nomor Telpeon Customer yang dipilih']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
