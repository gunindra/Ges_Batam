<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;

class InvoiceController extends Controller
{
    public function index()
    {


        return view('invoice.indexinvoice');
    }

    public function addinvoice()
    {
        $listPembeli = DB::select("SELECT id, nama_pembeli, no_wa FROM tbl_pembeli");

        $listCurrency = DB::select("SELECT id, nama_matauang, singkatan_matauang FROM tbl_matauang");

        $listSopir = DB::select("SELECT id, nama_supir, no_wa FROM tbl_supir");

        $listRekening = DB::select("SELECT id, pemilik, nomer_rekening, nama_bank FROM tbl_rekening");

        $listTipePembayaran = DB::select("SELECT id, tipe_pembayaran FROM tbl_tipe_pembayaran");

        $listRateVolume = DB::select("SELECT id, rate_volume FROM tbl_rate_volume");


        return view('invoice.buatinvoice', [
            'listPembeli' => $listPembeli,
            'listSupir' => $listSopir,
            'listRekening' => $listRekening,
            'listTipePembayaran' => $listTipePembayaran,
            'listRateVolume' => $listRateVolume,
            'listCurrency' => $listCurrency
        ]);
    }


    public function getlistInvoice(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';

        $q = "SELECT a.id,
                    a.no_resi,
                    DATE_FORMAT(a.tanggal_pembayaran, '%d %M %Y') AS tanggal_bayar,
                    b.nama_pembeli AS pembeli,
                    a.berat,
                    a.panjang,
                    a.lebar,
                    a.tinggi,
                    f.tipe_pembayaran,
                    a.harga,
                    d.status_name
                FROM tbl_pembayaran AS a
                JOIN tbl_tipe_pembayaran AS f ON a.pembayaran_id = f.id
                JOIN tbl_pembeli AS b ON a.pembeli_id = b.id
                JOIN tbl_status AS d ON a.status_id = d.id
                ORDER BY
                    CASE d.status_name
                        WHEN 'Pending Payment' THEN 1
                        WHEN 'Out For Delivery' THEN 2
                        WHEN 'Ready For Pickup' THEN 3
                        ELSE 4
                    END
        ";

        $data = DB::select($q);

        // Dapatkan nilai tukar dari USD ke IDR
        $exchangeRate = $this->getExchangeRate('USD', 'IDR');

        $output = '<table class="table align-items-center table-flush table-hover" id="tableInvoice">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No Resi</th>
                                        <th>Tanggal</th>
                                        <th>Costumer</th>
                                        <th>Jenis Pembayaran</th>
                                        <th>Harga (USD)</th>
                                        <th>Harga (IDR)</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>';
                                foreach ($data as $item) {
                                    // Konversi harga ke IDR
                                    $hargaIDR = isset($item->harga) ? $item->harga * $exchangeRate : 0;

                                    $statusBadgeClass = '';
                                    $btnPembayaran = ''; // Inisialisasi variabel untuk tombol pembayaran

                                    switch ($item->status_name) {
                                        case 'Pending Payment':
                                            $statusBadgeClass = 'badge-warning'; // Kuning
                                            $btnPembayaran = '<a class="btn btnPembayaran btn-sm btn-success text-white" data-id="' . $item->id . '" data-tipe="' . $item->tipe_pembayaran . '"><i class="fas fa-check"></i></a>';
                                            break;
                                        case 'Ready For Pickup':
                                            $statusBadgeClass = 'badge-success'; // Hijau
                                            break;
                                        case 'Out For Delivery':
                                            $statusBadgeClass = 'badge-primary'; // Biru
                                            break;
                                        case 'Delivering':
                                            $statusBadgeClass = 'badge-orange'; // Oranye
                                            break;
                                        case 'Debt':
                                            $statusBadgeClass = 'badge-danger'; // Merah
                                            break;
                                        case 'Done':
                                            $statusBadgeClass = 'badge-secondary'; // Abu-abu
                                            break;
                                        default:
                                            $statusBadgeClass = 'badge-secondary'; // Default
                                            break;
                                    }

                                    $output .=
                                        '
                                        <tr>
                                            <td class="">' . ($item->no_resi ?? '-') . '</td>
                                            <td class="">' . ($item->tanggal_bayar ?? '-') . '</td>
                                            <td class="">' . ($item->pembeli ?? '-') . '</td>
                                            <td class="">' . ($item->tipe_pembayaran ?? '-') . '</td>
                                            <td class="">' . (isset($item->harga) ? '$ ' . number_format($item->harga, 2, '.', ',') : '-') . '</td>
                                            <td class="">' . (isset($hargaIDR) ? 'Rp ' . number_format($hargaIDR, 0, ',', '.') : '-') . '</td>
                                            <td><span class="badge ' . $statusBadgeClass . '">' . ($item->status_name ?? '-') . '</span></td>
                                            <td>
                                                ' . $btnPembayaran . '
                                                <a class="btn btnExportInvoice btn-sm btn-secondary text-white" data-id="' . $item->id . '"><i class="fas fa-print"></i></a>
                                                <a class="btn btnDeleteInvoice btn-sm btn-danger text-white" data-id="' . $item->id . '" ><i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    ';
                                }
        $output .= '</tbody></table>';
        return $output;
    }


    public function tambainvoice(Request $request)
    {
        $noResi = $request->input('noResi');
        $tanggal = $request->input('tanggal');
        $customer = $request->input('customer');
        $currencyInvoice = $request->input('currencyInvoice');
        $beratBarang = floatval(str_replace(',', '.', $request->input('beratBarang')));
        $panjang = floatval(str_replace(',', '.', $request->input('panjang')));
        $lebar = floatval(str_replace(',', '.', $request->input('lebar')));
        $tinggi = floatval(str_replace(',', '.', $request->input('tinggi')));
        $metodePengiriman = $request->input('metodePengiriman');
        $driver = $request->input('driver');
        $alamatTujuan = $request->input('alamat');
        $provinsi = $request->input('provinsi');
        $kabupatenKota = $request->input('kabupatenKota');
        $kecamatan = $request->input('kecamatan');
        $kelurahan = $request->input('kelurahan');
        $metodePembayaran = $request->input('metodePembayaran');
        $rekening = $request->input('rekening');
        $totalharga = $request->input('totalharga');

        $date = DateTime::createFromFormat('j F Y', $tanggal);
        $formattedDate = $date ? $date->format('Y-m-d') : null;

        DB::beginTransaction();

        try {
            $pembayaranId = DB::table('tbl_pembayaran')->insertGetId([
                'no_resi' => $noResi,
                'tanggal_pembayaran' => $formattedDate,
                'pembeli_id' => $customer,
                'berat' => $beratBarang,
                'panjang' => $panjang,
                'lebar' => $lebar,
                'tinggi' => $tinggi,
                'pengiriman' => $metodePengiriman,
                'harga' => $totalharga,
                'pembayaran_id' => $metodePembayaran,
                'rekening_id' => $rekening,
                'matauang_id' => $currencyInvoice,
                'status_id' => 1,
                'created_at' => now(),
            ]);

            if (!$pembayaranId) {
                throw new \Exception("Failed to get the new ID from tbl_pembayaran");
            }

            if ($metodePengiriman === 'Delivery') {
                DB::table('tbl_pengantaran')->insert([
                    'pembayaran_id' => $pembayaranId,
                    'tanggal_pengantaran' => $formattedDate,
                    'supir_id' => $driver,
                    'alamat' => $alamatTujuan,
                    'provinsi' => $provinsi,
                    'kotakab' => $kabupatenKota,
                    'kecamatan' => $kecamatan,
                    'kelurahan' => $kelurahan,
                    'created_at' => now(),
                ]);
            }

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Invoice berhasil ditambahkan'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Gagal menambahkan Invoice: ' . $e->getMessage()], 500);
        }
    }


    public function completePayment(Request $request)
    {
        $id = $request->input('id');
        $payment = DB::table('tbl_pembayaran')->where('id', $id)->first(['pengiriman', 'status_id']);
        if ($payment) {
            if ($payment->pengiriman === 'Delivery') {
                DB::table('tbl_pembayaran')->where('id', $id)->update(['status_id' => 3]);
            } elseif ($payment->pengiriman === 'Pickup') {
                DB::table('tbl_pembayaran')->where('id', $id)->update(['status_id' => 2]);
            }
            return response()->json(['success' => true, 'message' => 'Status updated successfully.'], 200);
        }
        return response()->json(['error' => false, 'message' => 'Payment not found.']);
    }





    public function exportPdf(Request $request)
    {
        $id = $request->input('id');
        $id = intval($id);

        try {
            // Fetch invoice data
            $q = "SELECT a.id,
                        a.no_resi,
                        DATE_FORMAT(a.tanggal_pembayaran, '%d %M %Y') AS tanggal_bayar,
                        b.nama_pembeli AS pembeli,
                        b.no_wa AS nohp,
                        d.nama_supir,
                        d.no_wa AS supir_no_wa,
                        c.alamat,
                        a.berat,
                        a.panjang,
                        a.lebar,
                        a.tinggi,
                        a.pengiriman,
                        f.tipe_pembayaran,
                        e.nomer_rekening,
                        e.pemilik,
                        e.nama_bank,
                        a.harga
                    FROM tbl_pembayaran AS a
                    JOIN tbl_pembeli AS b ON a.pembeli_id = b.id
                    JOIN tbl_tipe_pembayaran AS f ON a.pembayaran_id = f.id
                    LEFT JOIN tbl_pengantaran AS c ON a.id = c.pembayaran_id
                    LEFT JOIN tbl_supir AS d ON c.supir_id = d.id
                    LEFT JOIN tbl_rekening AS e ON a.rekening_id = e.id
                    WHERE a.id = $id;
                  ";
            $invoice = DB::select($q);

            if (!$invoice) {
                return response()->json(['error' => 'Invoice not found'], 404);
            }

            $invoice = $invoice[0];

            // Handle delivery or pickup
            $additionalDetails = [];
            if ($invoice->pengiriman === 'Delivery') {
                $additionalDetails = [
                    'driverName' => $invoice->nama_supir ?? 'N/A', // Default to 'N/A' if null
                    'driverPhone' => $invoice->supir_no_wa ?? 'N/A',
                    'destinationAddress' => $invoice->alamat ?? 'N/A'
                ];
            }

            // Handle berat (default to 0 if null)
            $berat = $invoice->berat ?? 0;

            // Handle payment type
            $paymentDetails = [];
            if ($invoice->tipe_pembayaran === 'Transfer') {
                $paymentDetails = [
                    'rekeningNumber' => $invoice->nomer_rekening ?? 'N/A',
                    'accountHolder' => $invoice->pemilik ?? 'N/A',
                    'bankName' => $invoice->nama_bank ?? 'N/A'
                ];
            }

            // Calculate harga in IDR
            try {
                $hargaIDR = $invoice->harga;
            } catch (\Exception $e) {
                \Log::error('Error calculating exchange rate: ' . $e->getMessage(), ['exception' => $e]);
                return response()->json(['error' => 'Failed to calculate exchange rate'], 500);
            }

            // Generate PDF
            try {
                $pdf = Pdf::loadView('exportPDF.invoice', [
                    'invoice' => $invoice,
                    'hargaIDR' => $hargaIDR,
                    'additionalDetails' => $additionalDetails,
                    'paymentDetails' => $paymentDetails,
                    'berat' => $berat,
                    'panjang' => $invoice->panjang,
                    'lebar' => $invoice->lebar,
                    'tinggi' => $invoice->tinggi,
                    'tanggal' => $invoice->tanggal_bayar,
                ]);
            } catch (\Exception $e) {
                \Log::error('Error generating PDF: ' . $e->getMessage(), ['exception' => $e]);
                return response()->json(['error' => 'Failed to generate PDF'], 500);
            }

            // Save PDF to storage
            try {
                $fileName = 'invoice_' . $invoice->no_resi . '.pdf';
                $filePath = storage_path('app/public/' . $fileName);
                $pdf->save($filePath);
            } catch (\Exception $e) {
                \Log::error('Error saving PDF: ' . $e->getMessage(), ['exception' => $e]);
                return response()->json(['error' => 'Failed to save PDF'], 500);
            }

            // Send PDF URL
            $url = asset('storage/' . $fileName);
            return response()->json(['url' => $url]);

        } catch (\Exception $e) {
            // Log general error
            \Log::error('Error generating invoice PDF: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'An error occurred while generating the invoice PDF'], 500);
        }
    }

    public function deleteInvoice(Request $request)
    {
        $id = $request->input('id');

        try {

            $relatedRecords = DB::table('tbl_pengantaran')->where('pembayaran_id', $id)->get();

            if ($relatedRecords->count() > 0) {

                DB::table('tbl_pengantaran')->where('pembayaran_id', $id)->delete();
            }

            DB::table('tbl_pembayaran')->where('id', $id)->delete();

            return response()->json(['status' => 'success', 'message' => 'Data Invoice berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }



    private function getExchangeRate($fromCurrency, $toCurrency)
    {
        $client = new Client();
        try {
            $response = $client->request('GET', 'https://api.exchangerate-api.com/v4/latest/' . $fromCurrency);
            $data = json_decode($response->getBody(), true);

            return $data['rates'][$toCurrency];
        } catch (RequestException $e) {
            // Log error
            \Log::error('Error fetching exchange rate: ' . $e->getMessage());
            return 1; // Default exchange rate if an error occurs
        }
    }

}
