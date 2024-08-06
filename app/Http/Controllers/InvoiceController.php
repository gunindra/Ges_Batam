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

        $listSopir = DB::select("SELECT id, nama_supir, no_wa FROM tbl_supir");

        $listRekening = DB::select("SELECT id, pemilik, nomer_rekening, nama_bank FROM tbl_rekening");

        $listTipePembayaran = DB::select("SELECT id, tipe_pembayaran FROM tbl_tipe_pembayaran");

        $listRateVolume = DB::select("SELECT id, rate_volume FROM tbl_rate_volume");

        return view('invoice.buatinvoice', [
            'listPembeli' => $listPembeli,
            'listSupir' => $listSopir,
            'listRekening' => $listRekening,
            'listTipePembayaran' => $listTipePembayaran,
            'listRateVolume' => $listRateVolume
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
            a.pengiriman,
            a.harga,
            d.status_name
        FROM tbl_pembayaran AS a
        JOIN tbl_pembeli AS b ON a.pembeli_id = b.id
        JOIN tbl_status AS d ON a.status_id = d.id
        ORDER BY a.tanggal_pembayaran desc;
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
                                        <th>Pengiriman</th>
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

            $output .=
                '
                <tr>
                    <td class="">' . ($item->no_resi ?? '-') . '</td>
                    <td class="">' . ($item->tanggal_bayar ?? '-') . '</td>
                    <td class="">' . ($item->pembeli ?? '-') . '</td>
                    <td class="">' . ($item->pengiriman ?? '-') . '</td>
                    <td class="">' . (isset($item->harga) ? '$ ' . number_format($item->harga, 2, '.', ',') : '-') . '</td>
                    <td class="">' . (isset($hargaIDR) ? 'Rp ' . number_format($hargaIDR, 0, ',', '.') : '-') . '</td>
                    <td><span class="badge badge-warning">' . ($item->status_name ?? '-') . '</span></td>
                    <td>
                        <a class="btn btnExportInvoice btn-sm btn-secondary text-white" data-id="' . $item->id . '"><i class="fas fa-print"></i></a>
                        <a class="btn btnDestroyBooking btn-sm btn-danger text-white" data-id="' . $item->id . '" ><i class="fas fa-trash"></i></a>
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
            // Insert into tbl_pembayaran and get the new id
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
                'status_id' => 3,
                'created_at' => now(),
            ]);

            // Debug: Check if pembayaranId is valid
            if (!$pembayaranId) {
                throw new \Exception("Failed to get the new ID from tbl_pembayaran");
            }

            // Check if metodePengiriman is 'delivery'
            if ($metodePengiriman === 'delivery') {
                DB::table('tbl_pengantaran')->insert([
                    'pembayaran_id' => $pembayaranId, // Use the new id from tbl_pembayaran
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
            if ($invoice->pengiriman === 'delivery') {
                $additionalDetails = [
                    'driverName' => $invoice->nama_supir,
                    'driverPhone' => $invoice->supir_no_wa,
                    'destinationAddress' => $invoice->alamat
                ];
            }

            // Handle berat (default to 0 if null)
            $berat = $invoice->berat ?? 0;

            // Handle payment type
            $paymentDetails = [];
            if ($invoice->tipe_pembayaran === 'Transfer') {
                $paymentDetails = [
                    'rekeningNumber' => $invoice->nomer_rekening,
                    'accountHolder' => $invoice->pemilik,
                    'bankName' => $invoice->nama_bank
                ];
            }

            // Calculate harga in IDR
            $exchangeRate = $this->getExchangeRate('USD', 'IDR');
            $hargaIDR = $invoice->harga * $exchangeRate;

            // Generate PDF
            $pdf = Pdf::loadView('exportPDF.invoice', [
                'invoice' => $invoice,
                'hargaIDR' => $hargaIDR,
                'additionalDetails' => $additionalDetails,
                'paymentDetails' => $paymentDetails,
                'berat' => $berat,
                'panjang' => $invoice->panjang,
                'lebar' => $invoice->lebar,
                'tinggi' => $invoice->tinggi
            ]);

            // Save PDF to storage
            $fileName = 'invoice_' . $invoice->no_resi . '.pdf';
            $filePath = storage_path('app/public/' . $fileName);
            $pdf->save($filePath);

            // Send PDF URL
            $url = asset('storage/' . $fileName);
            return response()->json(['url' => $url]);

        } catch (\Exception $e) {
            // Log error
            \Log::error('Error generating invoice PDF: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while generating the invoice PDF'], 500);
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
