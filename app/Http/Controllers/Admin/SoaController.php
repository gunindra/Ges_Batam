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
        // $customers = Customer::where('tbl_pembeli.company_id', $companyId)->get();

        $customers = DB::select("SELECT id, marking, nama_pembeli FROM tbl_pembeli WHERE company_id = $companyId");

        // dd(  $customers);

        return view('Report.Soa.indexbalance', compact('customers'));
    }

    public function getSoa(Request $request)
    {
        $companyId = session('active_company_id');
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';
        $status = $request->status;
        $customer = $request->customer;

        $invoiceQuery = Invoice::where('tbl_invoice.status_bayar', '=', 'Belum lunas')
                    ->where('tbl_invoice.status_id', 6)
                    ->where('tbl_invoice.company_id', $companyId)
                    ->where('tbl_invoice.soa_closing', false)
                    ->join('tbl_pembeli', 'tbl_invoice.pembeli_id', '=', 'tbl_pembeli.id')
                    ->leftJoin('tbl_resi', 'tbl_invoice.id', '=', 'tbl_resi.invoice_id')
                    ->select(
                        'tbl_invoice.id',
                        'tbl_invoice.tanggal_invoice',
                        'tbl_invoice.no_invoice',
                        'tbl_invoice.total_harga',
                        'tbl_invoice.total_bayar',
                        'tbl_invoice.payment_type',
                        'tbl_pembeli.marking',
                        \DB::raw('COALESCE(MIN(tbl_resi.no_do), "-") as no_do')
                    )
                    ->groupBy(
                        'tbl_invoice.id',
                        'tbl_invoice.tanggal_invoice',
                        'tbl_invoice.no_invoice',
                        'tbl_invoice.total_harga',
                        'tbl_invoice.total_bayar',
                        'tbl_invoice.payment_type',
                        'tbl_pembeli.marking'
                    );

        if ($request->startDate) {
            $invoiceQuery->whereDate('tbl_invoice.tanggal_invoice', '>=', date('Y-m-d', strtotime($request->startDate)));
        }
        if ($request->endDate) {
            $invoiceQuery->whereDate('tbl_invoice.tanggal_invoice', '<=', date('Y-m-d', strtotime($request->endDate)));
        }
        if ($customer) {
            $invoiceQuery->where('tbl_pembeli.id', '=', $customer);
        }


        $invoices = $invoiceQuery->get();

        $invoiceIds = [];
        $grandTotal = 0;
        $output = '<div class="card-body">
                    <table class="table" width="100%">
                    <thead>
                        <th width="20%" style="text-left">Date</th>
                        <th width="20%" style="text-left">Marking</th>
                        <th width="20%" style="text-left">No Do</th>
                        <th width="20%">No Invoice</th>
                        <th width="20%">Payment Method</th>
                        <th width="20%" class="text-right">Jumlah Tagihan</th>
                    </thead>
                    <tbody>';

        foreach ($invoices as $data) {
            $belum_bayar = $data->total_harga - $data->total_bayar;
            $grandTotal += $belum_bayar;
            $invoiceIds[] = $data->id;

            $output .= '<tr>
                            <td>' . \Carbon\Carbon::parse($data->tanggal_invoice)->format('d-m-Y') . '</td>
                            <td>' . ($data->marking) . '</td>
                            <td>' . ($data->no_do) . '</td>
                            <td>' . ($data->no_invoice) . '</td>
                            <td>' . ($data->payment_type ?? '-') . '</td>
                            <td class="text-right">' . number_format($belum_bayar, 2) . '</td>
                        </tr>';
        }

        $output .= '<tfoot>
                        <tr>
                            <td colspan="5" class="text-right"><strong>Grand Total</strong></td>
                            <td class="text-right"><strong>' . number_format($grandTotal, 2) . '</strong></td>
                        </tr>
                    </tfoot>';

        $output .= '</tbody></table> </div>';

        // Kembalikan response dalam bentuk JSON
        return response()->json([
            'html' => $output,
            'invoiceIds' => $invoiceIds
        ]);
    }


    public function soaWA(Request $request)
    {
        try {
            Log::info('Memulai proses SOA WhatsApp');

            $companyId = session('active_company_id');
            Log::info('Company ID: ' . $companyId);

            $customer_id = $request->customer;
            Log::info('Customer ID: ' . $customer_id);

            $customer = Customer::find($customer_id);

            if (!$customer) {
                Log::error('Customer tidak ditemukan');
                return response()->json(['error' => 'Mohon Pilih Customer yang diinginkan'], 400);
            }

            Log::info('Customer ditemukan: ' . $customer->nama_pembeli);

            $query = Invoice::where('tbl_invoice.status_bayar', 'Belum lunas')
                ->where('tbl_invoice.pembeli_id', $customer->id)
                ->where('tbl_invoice.company_id', $companyId)
                ->where('tbl_invoice.status_id', 6)
                ->where('tbl_invoice.soa_closing', false)
                ->join('tbl_pembeli', 'tbl_invoice.pembeli_id', '=', 'tbl_pembeli.id')
                ->leftJoin('tbl_resi', 'tbl_invoice.id', '=', 'tbl_resi.invoice_id')
                ->selectRaw('tbl_invoice.*, tbl_pembeli.marking, COALESCE(MIN(tbl_resi.no_do), "-") as no_do')
                ->groupBy('tbl_invoice.id', 'tbl_pembeli.marking');

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

            Log::info("Start Date: $startDate, End Date: $endDate");

            $invoice = $query->get();
            Log::info('Jumlah invoice ditemukan: ' . $invoice->count());

            if ($invoice->isEmpty()) {
                Log::warning('Tidak ada data Invoice ditemukan');
                return response()->json(['error' => 'Tidak ada data Invoice yang ditemukan'], 400);
            }

            $directoryPath = public_path('storage/soa');
            // Mengganti karakter yang tidak valid dengan "_"
            $customerName = str_replace(['/','\\',':','*','?','"','<','>','|'], '_', $customer->nama_pembeli);
            $pdfFileName = 'Statement_of_Account_' . $customerName . '.pdf';

            $directoryPath = public_path('storage/soa');
            $filePath = $directoryPath . '/' . $pdfFileName;

            if (!file_exists($directoryPath)) {
                mkdir($directoryPath, 0755, true);
                Log::info('Direktori SOA dibuat: ' . $directoryPath);
            }

            if (file_exists($filePath)) {
                unlink($filePath);
                Log::info('File lama dihapus: ' . $filePath);
            }

            Log::info('Mulai generate PDF');
            $pdf = Pdf::loadView('exportPDF.soa', [
                'startDate' => $startDate,
                'endDate' => $endDate,
                'invoice' => $invoice,
                'customer' => $customer
            ]);
            $pdf->save($filePath);
            Log::info('PDF berhasil disimpan: ' . $filePath);

            $pesan = "Berikut kita lampirkan Statement of Account dari list Invoice yang belum dilunaskan, Terima Kasih";
            $fileUrl = asset('storage/soa/' . $pdfFileName);
            Log::info('File URL: ' . $fileUrl);

            $sender = '62' . DB::table('tbl_ptges')->value('phones');
            Log::info('Sender WA: ' . $sender);

            if ($customer->no_wa) {
                Log::info('Mengirim pesan ke WA: ' . $customer->no_wa);

                $pesanTerkirimDenganFile = $this->kirimPesanWhatsapp($customer->no_wa, $pesan, $fileUrl, $sender);
                $pesanTerkirim = $this->kirimPesanWhatsapp($customer->no_wa, $pesan, null, $sender);

                if (!$pesanTerkirimDenganFile && !$pesanTerkirim) {
                    Log::error('Gagal mengirim pesan WhatsApp ke ' . $customer->no_wa);
                    return response()->json(['error' => 'Gagal mengirim semua pesan WhatsApp ke ' . $customer->no_wa], 400);
                }
            } else {
                Log::error('Nomor WhatsApp customer tidak ditemukan');
                return response()->json(['error' => 'Terjadi kesalahan, silahkan periksa kembali Nomor Telepon Customer yang dipilih'], 400);
            }

            Log::info('Pesan WhatsApp berhasil dikirim');
            return response()->json(['success' => 'Pesan WhatsApp berhasil dikirim']);
        } catch (\Exception $e) {
            Log::error('Error di soaWA: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }




    public function exportSoaCustomerReport(Request $request)
    {
        $startDate = $request->input('startDate') ?? now()->startOfMonth()->toDateString();
        $endDate = $request->input('endDate') ?? now()->endOfMonth()->toDateString();
        $customer = $request->nama_pembeli ?? '-';

        return Excel::download(new SoaCustomerExport($startDate, $endDate,$customer), 'Soa_Customer.xlsx');
    }

    public function closingSoa(Request $request)
    {
        $request->validate([
            'invoiceIds' => 'required|array',
            'invoiceIds.*' => 'exists:tbl_invoice,id',
        ]);

        $invoiceIds = $request->invoiceIds;
        Invoice::whereIn('id', $invoiceIds)->update(['soa_closing' => true]);

        return response()->json([
            'success' => true,
            'message' => 'SOA berhasil di-closing.',
            'updated_ids' => $invoiceIds
        ]);
    }
}
