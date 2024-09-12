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
use Str;

class InvoiceController extends Controller
{
    public function index()
    {
        $listStatus = DB::select("SELECT status_name FROM tbl_status");

        return view('customer.invoice.indexinvoice', [
            'listStatus' => $listStatus
        ]);
    }

    public function addinvoice()
    {
        $listPembeli = DB::select("SELECT a.id,
                                        a.nama_pembeli,
                                        a.marking,
                                        a.metode_pengiriman,
                                        c.id AS category_id,
                                        c.minimum_rate,
                                        GROUP_CONCAT(b.alamat SEPARATOR ', ') AS alamat,
                                        COUNT(b.id) AS jumlah_alamat
                                    FROM tbl_pembeli a
                                    LEFT JOIN tbl_alamat b ON b.pembeli_id = a.id
                                    JOIN tbl_category c ON a.category_id = c.id
                                    GROUP BY a.id,
                                    a.nama_pembeli,
                                     a.marking,
                                      a.metode_pengiriman,
                                       c.id,
                                        c.minimum_rate");


        $listCurrency = DB::select("SELECT id, nama_matauang, singkatan_matauang FROM tbl_matauang");

        $listRekening = DB::select("SELECT id, pemilik, nomer_rekening, nama_bank FROM tbl_rekening");

        $listTipePembayaran = DB::select("SELECT id, tipe_pembayaran FROM tbl_tipe_pembayaran");

        $listRateVolume = DB::select("SELECT id, nilai_rate, rate_for FROM tbl_rate");

        $lisPembagi = DB::select("SELECT id, nilai_pembagi FROM tbl_pembagi");


        return view('customer.invoice.buatinvoice', [
            'listPembeli' => $listPembeli,
            'listRekening' => $listRekening,
            'listTipePembayaran' => $listTipePembayaran,
            'listRateVolume' => $listRateVolume,
            'listCurrency' => $listCurrency,
            'lisPembagi' => $lisPembagi
        ]);
    }

    public function editinvoice(Request $request, $id)
    {
        $data = DB::table('tbl_pembayaran as a')
        ->join('tbl_pembeli as b', 'a.pembeli_id', '=', 'b.id')
        ->leftJoin('tbl_pengantaran as c', 'a.id', '=', 'c.pembayaran_id')
        ->leftJoin('tbl_supir as d', 'c.supir_id', '=', 'd.id')
        ->where('a.id', $id)
        ->select(
            'a.id',
            'a.no_resi',
            DB::raw("DATE_FORMAT(a.tanggal_pembayaran, '%d %M %Y') AS tanggal_bayar"),
            'b.marking',
            'b.nama_pembeli',
            'a.berat',
            'a.panjang',
            'a.lebar',
            'a.tinggi',
            'a.pengiriman',
            'd.nama_supir',
            'c.alamat',
            'c.provinsi',
            'c.kotakab',
            'c.kecamatan',
            'c.kelurahan',
            'a.pembayaran_id',
            'a.rekening_id',
            'a.rate_matauang',
            'a.matauang_id'
        )
        ->first();


        $listPembeli = DB::select("SELECT id, nama_pembeli, marking FROM tbl_pembeli");

        $listCurrency = DB::select("SELECT id, nama_matauang, singkatan_matauang FROM tbl_matauang");

        $listSopir = DB::select("SELECT id, nama_supir, no_wa FROM tbl_supir");

        $listRekening = DB::select("SELECT id, pemilik, nomer_rekening, nama_bank FROM tbl_rekening");

        $listTipePembayaran = DB::select("SELECT id, tipe_pembayaran FROM tbl_tipe_pembayaran");

        $listRateVolume = DB::select("SELECT id, nilai_rate, rate_for FROM tbl_rate");

        $lisPembagi = DB::select("SELECT id, nilai_pembagi FROM tbl_pembagi");


        return view('customer.invoice.deleteoreditinvoice', [
            'listPembeli' => $listPembeli,
            'listSupir' => $listSopir,
            'listRekening' => $listRekening,
            'listTipePembayaran' => $listTipePembayaran,
            'listRateVolume' => $listRateVolume,
            'listCurrency' => $listCurrency,
            'lisPembagi' => $lisPembagi,
            'data' => $data
        ]);
    }

    public function cicilanInvoice(Request $request, $id)
    {
        return view('customer.invoice.cicilaninvoice');
    }


    public function getlistInvoice(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';
        $status = $request->status;
        $startDate = $request->startDate ? date('Y-m-d', strtotime($request->startDate)) : null;
        $endDate = $request->endDate ? date('Y-m-d', strtotime($request->endDate)) : null;
        $isNotif = $request->isNotif;

        $dateCondition = '';
        if ($startDate && $endDate) {
            $dateCondition = "AND a.tanggal_invoice BETWEEN '$startDate' AND '$endDate'";
        }

        $statusCondition = $status ? "AND d.status_name LIKE '$status'" : "";

        $q = "SELECT a.id,
                    a.no_resi,
                    DATE_FORMAT(a.tanggal_invoice, '%d %M %Y') AS tanggal_bayar,
                    b.nama_pembeli AS pembeli,
                    a.alamat,
                    b.metode_pengiriman,
                    a.berat,
                    a.panjang,
                    a.lebar,
                    a.tinggi,
                    a.harga,
                    a.matauang_id,
                    a.rate_matauang,
                    d.id,
                    d.status_name
            FROM tbl_invoice AS a
            JOIN tbl_pembeli AS b ON a.pembeli_id = b.id
            JOIN tbl_status AS d ON a.status_id = d.id
            WHERE (
                UPPER(b.nama_pembeli) LIKE UPPER('$txSearch')
                OR UPPER(a.no_resi) LIKE UPPER('$txSearch')
                )
                $dateCondition
                $statusCondition
            ORDER BY CASE d.id
                        WHEN '1' THEN 1
                        WHEN '5' THEN 2
                        WHEN '3' THEN 3
                        WHEN '2' THEN 4
                        WHEN '4' THEN 5
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
                        <tr>';
                        if ($isNotif == 'true') {
                            $output .= '<th class="no-sort"><input type="checkbox" class="selectAll" id="selectAll"></th>';
                        }
                 $output .= '
                            <th>No Resi</th>
                            <th>Tanggal</th>
                            <th>Customer</th>
                            <th>Pengiriman</th>
                            <th>Alamat</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>';
                    foreach ($data as $item) {

                        $statusBadgeClass = '';
                        $btnEditinvoice = '';

                        switch ($item->status_name) {
                            case 'Batam / Sortir':
                                $statusBadgeClass = 'badge-primary';
                                $btnEditinvoice = '<a class="btn btnEditInvoice btn-sm btn-primary text-white" data-id="' . $item->id . '" ><i class="fas fa-edit"></i></a>';
                                break;
                            case 'Ready For Pickup':
                                $statusBadgeClass = 'badge-warning';
                                $btnEditinvoice = '<a class="btn btnEditInvoice btn-sm btn-primary text-white" data-id="' . $item->id . '" ><i class="fas fa-edit"></i></a>';
                                break;
                            case 'Out For Delivery':
                                $statusBadgeClass = 'badge-primary';
                                $btnEditinvoice = '<a class="btn btnEditInvoice btn-sm btn-primary text-white" data-id="' . $item->id . '" ><i class="fas fa-edit"></i></a>';
                                break;
                            case 'Delivering':
                                $statusBadgeClass = 'badge-delivering';
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
                            <tr>';
                            if ($isNotif == 'true') {
                                $output .= '<td><input type="checkbox" class="selectItem" data-id="' . $item->id . '"></td>';
                            }
                             $output .=
                            '
                                <td>' . ($item->no_resi ?? '-') . '</td>
                                <td>' . ($item->tanggal_bayar ?? '-') . '</td>
                                <td>' . ($item->pembeli ?? '-') . '</td>
                                <td>' . ($item->metode_pengiriman ?? '-') . '</td>
                                <td>' . ($item->alamat ?? '-') . '</td>
                                <td>' . $currencySymbols[$item->matauang_id] . number_format($convertedHarga, 2, '.', ',') . '</td>
                                <td><span class="badge ' . $statusBadgeClass . '">' . ($item->status_name ?? '-') . '</span></td>
                                <td>

                                    ' . $btnEditinvoice . '
                                    <a class="btn btnExportInvoice btn-sm btn-secondary text-white" data-id="' . $item->id . '"><i class="fas fa-print"></i></a>
                                </td>
                            </tr>
                        ';
                    }
        $output .= '</tbody></table>';
        return $output;
    }

    public function getlistHeadCicilan(Request $request)
    {
        $id = $request->input('id');


        $data = DB::table('tbl_pembayaran')
            ->join('tbl_pembeli', 'tbl_pembayaran.pembeli_id', '=', 'tbl_pembeli.id')
            ->select('tbl_pembayaran.no_resi', 'tbl_pembeli.nama_pembeli', 'tbl_pembayaran.status_pembayaran', 'tbl_pembayaran.cicilan')
            ->where('tbl_pembayaran.id', $id)
            ->first();

        return response()->json($data);
    }

    public function getlistCicilan(Request $request)
    {
        $id = $request->input('id');
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';
        $startDate = $request->startDate ? date('Y-m-d', strtotime($request->startDate)) : null;
        $endDate = $request->endDate ? date('Y-m-d', strtotime($request->endDate)) : null;

        $dateCondition = '';
        if ($startDate && $endDate) {
            $dateCondition = "AND tanggal_pembayaran BETWEEN '$startDate' AND '$endDate'";
        }

        $q = " SELECT   id,
                        userlogin,
                        jumlah_cicilan,
                        DATE_FORMAT(tanggal_pembayaran, '%d %M %Y') AS tanggal_bayar,
                        metode_pembayaran,
                        bukti_pembayaran
                FROM tbl_cicilan
                WHERE pembayaran_id = $id
                and (
                UPPER(userlogin) LIKE UPPER('$txSearch')
                OR UPPER(metode_pembayaran) LIKE UPPER('$txSearch')
                )
                $dateCondition
                ORDER BY id DESC
                LIMIT 100
                ";

                    $data = DB::select($q);



                    $output = '  <table class="table align-items-center table-flush table-hover" id="tableCicilan">
                                 <thead class="thead-light">
                                    <tr>
                                        <th>Admin</th>
                                        <th>Tanggal Pembayaran</th>
                                        <th>Jumlah Pembayaran</th>
                                        <th>Metode Pembayaran</th>
                                        <th>Bukti Pembayaran</th>
                                    </tr>
                                </thead>
                                <tbody>';
                    foreach ($data as $item) {

                        $btnDetailBukti = '-';
                        if ($item->bukti_pembayaran)
                        {
                            $btnDetailBukti = '<a  class="btn btnDetailCicilan btn-sm btn-primary text-white" data-id="' . $item->id . '" data-bukti="' . $item->bukti_pembayaran . '"><i class="fas fa-eye"></i></a>';
                        }

                        $output .=
                            '
                            <tr>
                                <td class="">' . ($item->userlogin ?? '-') .'</td>
                                <td class="">' . ($item->tanggal_bayar ?? '-') .'</td>
                                <td class="">' . (isset($item->jumlah_cicilan) ? '' . number_format($item->jumlah_cicilan,0, '.', ',') : '-') . '</td>
                                <td class="">' . ($item->metode_pembayaran ?? '-') .'</td>
                                <td>
                                 ' . $btnDetailBukti . '
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
        $metodePengiriman = $request->input('metodePengiriman');
        $rateCurrency = $request->input('rateCurrency');
        $beratBarang = floatval(str_replace(',', '.', $request->input('beratBarang')));
        $panjang = floatval(str_replace(',', '.', $request->input('panjang')));
        $lebar = floatval(str_replace(',', '.', $request->input('lebar')));
        $tinggi = floatval(str_replace(',', '.', $request->input('tinggi')));
        $alamatTujuan = $request->input('alamat');
        $totalharga = $request->input('totalharga');

        $date = DateTime::createFromFormat('j F Y', $tanggal);
        $formattedDate = $date ? $date->format('Y-m-d') : null;

        DB::beginTransaction();

        try {
            $statusId = 1;
            $pembayaranId = DB::table('tbl_invoice')->insertGetId([
                'no_resi' => $noResi,
                'tanggal_invoice' => $formattedDate,
                'pembeli_id' => $customer,
                'metode_pengiriman' => $metodePengiriman,
                'alamat' => $alamatTujuan,
                'berat' => $beratBarang,
                'panjang' => $panjang,
                'lebar' => $lebar,
                'tinggi' => $tinggi,
                'harga' => $totalharga,
                'matauang_id' => $currencyInvoice,
                'rate_matauang' => $rateCurrency,
                'status_id' => $statusId,
                'created_at' => now(),
            ]);

            if (!$pembayaranId) {
                throw new \Exception("Gagal mendapatkan ID baru dari tbl_invoice");
            }

            $updatedTracking = DB::table('tbl_tracking')
                ->where('no_resi', $noResi)
                ->update(['status' => 'Batam / Sortir']);

            if (!$updatedTracking) {
                throw new \Exception("Gagal memperbarui status di tbl_tracking");
            }

            $updatedPembeli = DB::table('tbl_pembeli')
                ->where('id', $customer)
                ->update(['transaksi_terakhir' => now()]);

            if (!$updatedPembeli) {
                throw new \Exception("Gagal memperbarui transaksi terakhir di tbl_pembeli");
            }


            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Invoice berhasil ditambahkan dan status tracking diperbarui'], 200);
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
                        DB::table('tbl_pembayaran')->where('id', $id)->update([
                            'status_id' => 3,
                            'status_pembayaran' => 'Lunas'
                        ]);
                    } elseif ($payment->pengiriman === 'Pickup') {
                        DB::table('tbl_pembayaran')->where('id', $id)->update([
                            'status_id' => 2,
                            'status_pembayaran' => 'Lunas'
                        ]);
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
                $fileName = 'invoice_' . (string) Str::uuid() . '.pdf';
                $filePath = storage_path('app/public/invoice/' . $fileName);
                $pdf->save($filePath);
            } catch (\Exception $e) {
                \Log::error('Error saving PDF: ' . $e->getMessage(), ['exception' => $e]);
                return response()->json(['error' => 'Failed to save PDF'], 500);
            }

            // Send PDF URL
            $url = asset('storage/invoice/' . $fileName);
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

    public function bayarTagihan(Request $request)
    {
        try {
            $id = $request->id;
            $jumlahCicilan = $request->jumlahPembayaran;
            $metodePembayaran = $request->metodePembayaran;
            $buktiPembayaran = $request->file('buktiPembayaran');

            $pembayaran = DB::table('tbl_pembayaran')->where('id', $id)->first();

            if (!$pembayaran) {
                return response()->json(['status' => 'error', 'message' => 'Data pembayaran tidak ditemukan']);
            }

            if ($jumlahCicilan > $pembayaran->cicilan) {
                return response()->json(['status' => 'error', 'message' => 'Jumlah pembayaran melebihi sisa cicilan yang ada']);
            }

            $cicilanBaru = $pembayaran->cicilan - $jumlahCicilan;
            $statusPembayaranBaru = $cicilanBaru <= 0 ? 'Lunas' : 'Belum Lunas';

            DB::table('tbl_pembayaran')->where('id', $id)->update([
                'cicilan' => $cicilanBaru,
                'status_pembayaran' => $statusPembayaranBaru,
                'updated_at' => now()
            ]);

            $fileName = null;

            if ($buktiPembayaran) {
                try {
                    $fileName = time() . '_' . $buktiPembayaran->getClientOriginalName();
                    $filePath = $buktiPembayaran->storeAs('public/bukti_pembayaran_cicilan/', $fileName);
                } catch (\Exception $e) {
                    return response()->json(['error' => true, 'message' => 'File upload failed.'], 500);
                }
            }

            DB::table('tbl_cicilan')->insert([
                'pembayaran_id' => $id,
                'userlogin' => auth()->user()->name,
                'jumlah_cicilan' => $jumlahCicilan,
                'tanggal_pembayaran' => now(),
                'metode_pembayaran' => $metodePembayaran,
                'bukti_pembayaran' => $fileName,
                'created_at' => now()
            ]);

            return response()->json(['status' => 'success', 'message' => 'Pembayaran berhasil disimpan']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
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
