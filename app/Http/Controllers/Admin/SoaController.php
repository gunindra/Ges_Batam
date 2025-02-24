<?php

namespace App\Http\Controllers\Admin;
use App\Exports\SoaCustomerExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Customer;
use App\Models\Invoice;
use App\Traits\WhatsappTrait;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class SoaController extends Controller
{
    use WhatsappTrait;

    public function index() {

        $companyId = session('active_company_id');
        $customers = Customer::where('status', '=', 1)
        ->where('tbl_pembeli.company_id', $companyId)
        ->get();
        return view('Report.Soa.indexbalance', compact('customers'));
    }

    public function getSoa(Request $request)
    {
        $companyId = session('active_company_id');
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';
        $status = $request->status;
        $customer = $request->customer;

        $invoice = Invoice::where('tbl_invoice.status_bayar', '=', 'Belum lunas')
                    ->where('tbl_invoice.pembeli_id', '=', $customer)
                    ->where('tbl_invoice.company_id', $companyId)
                    ->join('tbl_pembeli', 'tbl_invoice.pembeli_id', '=', 'tbl_pembeli.id') // Join ke tbl_pembeli
                    ->select(
                        'tbl_invoice.*',
                        'tbl_pembeli.marking'
                    );

        if ($request->startDate) {
            $invoice->whereDate('tbl_invoice.tanggal_invoice', '>=', date('Y-m-d', strtotime($request->startDate)));
        }
        if ($request->endDate) {
            $invoice->whereDate('tbl_invoice.tanggal_invoice', '<=', date('Y-m-d', strtotime($request->endDate)));
        }

        $invoice = $invoice->get();

        $output = '<div class="card-body">
                    <table class="table" width="100%">
                    <thead>
                        <th width="20%" style="text-left">Date</th>
                        <th width="20%" style="text-left">Marking</th>
                        <th width="20%">No Invoice</th>
                        <th width="20%">Payment Method</th>
                        <th width="20%" class="text-right">Jumlah Tagihan</th>
                    </thead>
                    <tbody>';

        $grandTotal = 0;

        foreach ($invoice as $data) {
            $belum_bayar = $data->total_harga - $data->total_bayar;
            $grandTotal += $belum_bayar;

            $output .= '<tr>
                            <td>' . \Carbon\Carbon::parse($data->tanggal_invoice)->format('d-m-Y') . '</td>
                            <td>' . ($data->marking) . '</td>
                            <td>' . ($data->no_invoice) . '</td>
                            <td>' . ($data->payment_type ?? '-') . '</td>
                            <td class="text-right">' . number_format($belum_bayar, 2) . '</td>
                        </tr>';
        }

        // Tambahkan Grand Total di Footer Table
        $output .= '<tfoot>
                        <tr>
                            <td colspan="4" class="text-right"><strong>Grand Total</strong></td>
                            <td class="text-right"><strong>' . number_format($grandTotal, 2) . '</strong></td>
                        </tr>
                    </tfoot>';

        $output .= '</tbody></table> </div>';

        return $output;
    }


    public function soaWA(Request $request)
    {
        try{
            $companyId = session('active_company_id');
            $customer_id = $request->customer;
            $customer = Customer::find($customer_id);

            if (!$customer) {
                return redirect()->back()->withErrors(['error' => 'Mohon Pilih Customer yang diinginkan']);
            }

            $query = Invoice::where('tbl_invoice.status_bayar', 'Belum lunas')
                        ->where('tbl_invoice.pembeli_id', $customer_id)
                        ->where('tbl_invoice.company_id', $companyId)
                        ->join('tbl_pembeli', 'tbl_invoice.pembeli_id', '=', 'tbl_pembeli.id')
                        ->select('tbl_invoice.*', 'tbl_pembeli.marking');

            if ($request->startDate) {
                $startDate = date('Y-m-d', strtotime($request->startDate));
                $query->whereDate('tbl_invoice.tanggal_invoice', '>=', $startDate);
            } else {
                $startDate = '';
            }

            if ($request->endDate) {
                $endDate = date('Y-m-d', strtotime($request->endDate));
                $query->whereDate('tbl_invoice.tanggal_invoice', '<=', $endDate);
            } else {
                $endDate = '';
            }

            $invoice = $query->get();

            if ($invoice->isEmpty()) {
                return redirect()->back()->withErrors(['error' => 'Tidak ada data Invoice yang ditemukan']);
            }

            $pdf = Pdf::loadView('exportPDF.soa', [
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

            $sender = '62' . DB::table('tbl_ptges')->value('phones');

            if ($customer->no_wa) {
                $pesanTerkirimDenganFile = $this->kirimPesanWhatsapp($customer->no_wa, $pesan, $fileUrl, $sender);
                $pesanTerkirim = $this->kirimPesanWhatsapp($customer->no_wa, $pesan, null, $sender);

                if (!$pesanTerkirimDenganFile) {
                    Log::error('Gagal mengirim pesan dengan file ke ' . $customer->no_wa);
                }

                if (!$pesanTerkirim) {
                    Log::error('Gagal mengirim pesan teks ke ' . $customer->no_wa);
                }

                if (!$pesanTerkirimDenganFile && !$pesanTerkirim) {
                    return redirect()->back()->withErrors(['error' => 'Gagal mengirim semua pesan WhatsApp ke ' . $customer->no_wa]);
                }

            } else {
                return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan, silahkan periksa kembali Nomor Telpeon Customer yang dipilih']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function exportSoaCustomerReport(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $customer = $request->nama_pembeli ?? '-';

        return Excel::download(new SoaCustomerExport($startDate, $endDate,$customer), 'Soa_Customer.xlsx');
    }
}
