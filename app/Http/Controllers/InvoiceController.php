<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class InvoiceController extends Controller
{
    public function index()
    {


        return view('customer.invoice.indexinvoice');
    }

    public function addinvoice()
    {
        $listPembeli = DB::select("SELECT id, nama_pembeli, marking FROM tbl_pembeli");

        $listCurrency = DB::select("SELECT id, nama_matauang, singkatan_matauang FROM tbl_matauang");

        $listSopir = DB::select("SELECT id, nama_supir, no_wa FROM tbl_supir");

        $listRekening = DB::select("SELECT id, pemilik, nomer_rekening, nama_bank FROM tbl_rekening");

        $listTipePembayaran = DB::select("SELECT id, tipe_pembayaran FROM tbl_tipe_pembayaran");

        $listRateVolume = DB::select("SELECT id, nilai_rate FROM tbl_rate");

        $lisPembagi = DB::select("SELECT id, nilai_pembagi FROM tbl_pembagi");


        return view('customer.invoice.buatinvoice', [
            'listPembeli' => $listPembeli,
            'listSupir' => $listSopir,
            'listRekening' => $listRekening,
            'listTipePembayaran' => $listTipePembayaran,
            'listRateVolume' => $listRateVolume,
            'listCurrency' => $listCurrency,
            'lisPembagi' => $lisPembagi
        ]);
    }


    public function getlistInvoice(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';
        $status = $request->status;
        $startDate = $request->startDate ? date('Y-m-d', strtotime($request->startDate)) : null;
        $endDate = $request->endDate ? date('Y-m-d', strtotime($request->endDate)) : null;

        $dateCondition = '';
        if ($startDate && $endDate) {
            $dateCondition = "AND a.tanggal_pembayaran BETWEEN '$startDate' AND '$endDate'";
        }

        $statusCondition = $status ? "AND d.status_name LIKE '$status'" : "";

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
                    a.matauang_id,
                    a.rate_matauang,
                    a.bukti_pembayaran,
                    d.status_name
            FROM tbl_pembayaran AS a
            JOIN tbl_tipe_pembayaran AS f ON a.pembayaran_id = f.id
            JOIN tbl_pembeli AS b ON a.pembeli_id = b.id
            JOIN tbl_status AS d ON a.status_id = d.id
            WHERE (
                UPPER(b.nama_pembeli) LIKE UPPER('$txSearch')
                OR UPPER(a.no_resi) LIKE UPPER('$txSearch')
                OR UPPER(f.tipe_pembayaran) LIKE UPPER('$txSearch')
                )
                $dateCondition
                $statusCondition
            ORDER BY CASE d.status_name
                        WHEN 'Pending Payment' THEN 1
                        WHEN 'Debt' THEN 2
                        WHEN 'Out For Delivery' THEN 3
                        WHEN 'Ready For Pickup' THEN 4
                        WHEN 'Delivering' THEN 5
                        ELSE 6
                    END,
                     a.id DESC
            LIMIT 100;";

        $data = DB::select($q);

        $currencySymbols = [
            1 => 'Rp ',
            2 => '$ ',
            3 => '¥ '
        ];

        $output = '<table class="table align-items-center table-flush table-hover" id="tableInvoice">
                    <thead class="thead-light">
                        <tr>
                            <th>No Resi</th>
                            <th>Tanggal</th>
                            <th>Customer</th>
                            <th>Jenis Pembayaran</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>';
                    foreach ($data as $item) {

                        $statusBadgeClass = '';
                        $btnPembayaran = '';
                        switch ($item->status_name) {
                            case 'Pending Payment':
                                $statusBadgeClass = 'badge-warning';
                                $btnPembayaran = '<a class="btn btnPembayaran btn-sm btn-success text-white" data-id="' . $item->id . '" data-tipe="' . $item->tipe_pembayaran . '"><i class="fas fa-check"></i></a>';
                                break;
                            case 'Ready For Pickup':
                                $statusBadgeClass = 'badge-success';
                                if ($item->tipe_pembayaran === 'Transfer') {
                                    $btnPembayaran = '<a class="btn btnDetailPembayaran btn-sm btn-primary text-white" data-id="' . $item->id . '" data-bukti="' . $item->bukti_pembayaran . '"><i class="fas fa-eye"></i></a>';
                                }
                                break;
                            case 'Out For Delivery':
                                $statusBadgeClass = 'badge-primary';
                                if ($item->tipe_pembayaran === 'Transfer') {
                                    $btnPembayaran = '<a class="btn btnDetailPembayaran btn-sm btn-primary text-white" data-id="' . $item->id . '" data-bukti="' . $item->bukti_pembayaran . '"><i class="fas fa-eye"></i></a>';
                                }
                                break;
                            case 'Delivering':
                                $statusBadgeClass = 'badge-delivering';
                                break;
                            case 'Debt':
                                $statusBadgeClass = 'badge-danger';
                                break;
                            case 'Done':
                                $statusBadgeClass = 'badge-secondary';
                                break;
                            default:
                                $statusBadgeClass = 'badge-secondary';
                                break;
                        }

                        $convertedHarga = $item->harga;
                        if ($item->matauang_id != 1) {
                            $convertedHarga = $item->harga / $item->rate_matauang;
                        }

                        $output .=
                            '
                            <tr>
                                <td>' . ($item->no_resi ?? '-') . '</td>
                                <td>' . ($item->tanggal_bayar ?? '-') . '</td>
                                <td>' . ($item->pembeli ?? '-') . '</td>
                                <td>' . ($item->tipe_pembayaran ?? '-') . '</td>
                                <td>' . $currencySymbols[$item->matauang_id] . number_format($convertedHarga, 2, '.', ',') . '</td>
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
        $rateCurrency = $request->input('rateCurrency');
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
                'rate_matauang' => $rateCurrency,
                'status_id' => 1,
                'created_at' => now(),
            ]);

            if (!$pembayaranId) {
                throw new \Exception("Failed to get the new ID from tbl_pembayaran");
            }

            // if ($metodePengiriman === 'Pickup') {
            //     DB::table('tbl_pengantaran')->insert([
            //         'pembayaran_id' => $pembayaranId,
            //         'tanggal_pengantaran' => $formattedDate,
            //         'supir_id' => $driver,
            //         'alamat' => $alamatTujuan,
            //         'provinsi' => $provinsi,
            //         'kotakab' => $kabupatenKota,
            //         'kecamatan' => $kecamatan,
            //         'kelurahan' => $kelurahan,
            //         'created_at' => now(),
            //     ]);
            // }

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
        try {
            $id = $request->input('id');
            $file = $request->file('file');

            try {
                $payment = DB::table('tbl_pembayaran')->where('id', $id)->first(['pengiriman', 'status_id', 'pembayaran_id']);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to retrieve payment record.'], 500);
            }

            if ($payment) {
                if ($payment->pembayaran_id === 2) {
                    if ($file) {
                        try {
                            $fileName = $file->getClientOriginalName();
                            $filePath = $file->storeAs('public/bukti_pembayaran', $fileName);

                            DB::table('tbl_pembayaran')->where('id', $id)->update(['bukti_pembayaran' => $fileName]);
                        } catch (\Exception $e) {
                            return response()->json(['error' => true, 'message' => 'File upload or database update failed.'], 500);
                        }
                    } else {
                        return response()->json(['error' => true, 'message' => 'File not uploaded.'], 400);
                    }
                }

                // Update status based on pengiriman
                try {
                    if ($payment->pengiriman === 'Delivery') {
                        DB::table('tbl_pembayaran')->where('id', $id)->update(['status_id' => 3]);
                    } elseif ($payment->pengiriman === 'Pickup') {
                        DB::table('tbl_pembayaran')->where('id', $id)->update(['status_id' => 2]);
                    }
                } catch (\Exception $e) {
                    return response()->json(['error' => true, 'message' => 'Failed to update payment status.'], 500);
                }

                return response()->json(['success' => true, 'message' => 'Status updated successfully.'], 200);
            }

            return response()->json(['error' => true, 'message' => 'Payment not found.']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'An unexpected error occurred.'], 500);
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
                ])
                ->setPaper('A4', 'portrait')
                ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                ->setWarnings(false);
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

    public function detailBuktiPembayaran(Request $request)
    {
        $tester = $request->input('namafoto');

        try {
            // Gunakan Storage untuk mendapatkan URL file
            $filePath = 'public/bukti_pembayaran/' . $tester;

            if (!Storage::exists($filePath)) {
                return response()->json(['status' => 'error', 'message' => 'File tidak ditemukan'], 404);
            }
            // Mendapatkan URL dari file
            $url = Storage::url($filePath);
            return response()->json(['status' => 'success', 'url' => $url], 200);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request)
    {
        $id = $request->id;

        try {
            if ($id) {
                DB::table('tbl_pembayaran')->where('id', $id)->update(['status_id' => 6]);
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false, 'message' => 'ID tidak ditemukan.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

}
