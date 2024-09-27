<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Jobs\KirimPesanWaPembeliJob;
use App\Traits\WhatsappTrait;
use Log;
use Str;

class InvoiceController extends Controller
{
    use WhatsappTrait;

    public function sendInvoiceNotification($noWa, $message)
    {
        $this->kirimPesanWhatsapp($noWa, $message);
    }
    public function index()
    {
        $listStatus = DB::select("SELECT status_name FROM tbl_status");

        return view('customer.invoice.indexinvoice', [
            'listStatus' => $listStatus
        ]);
    }

    public function addinvoice()
    {
        // Mulai transaksi
        DB::beginTransaction();
        try {
            $yearMonth = date('ym');
            $q = "SELECT no_invoice FROM tbl_invoice ORDER BY no_invoice DESC LIMIT 1;";
            $data = DB::select($q);

            if (!empty($data)) {
                $lastMarking = $data[0]->no_invoice;
                $lastYearMonth = substr($lastMarking, 0, 4);

                if ($lastYearMonth === $yearMonth) {
                    $lastNumber = (int)substr($lastMarking, 4);
                    $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
                } else {
                    $newNumber = '00001';
                }
            } else {
                $newNumber = '00001';
            }

            $newNoinvoice = $yearMonth . $newNumber;

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

            // Commit transaksi
            DB::commit();

            return view('customer.invoice.buatinvoice', [
                'listPembeli' => $listPembeli,
                'listRekening' => $listRekening,
                'listTipePembayaran' => $listTipePembayaran,
                'listRateVolume' => $listRateVolume,
                'listCurrency' => $listCurrency,
                'lisPembagi' => $lisPembagi,
                'newNoinvoice' => $newNoinvoice
            ]);
        } catch (\Exception $e) {
            // Rollback transaksi jika ada error
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat membuat invoice: ' . $e->getMessage());
        }
    }

    public function generateInvoice()
    {
        DB::beginTransaction();

        try {
            $yearMonth = date('ym');
            $q = "SELECT no_invoice FROM tbl_invoice ORDER BY no_invoice DESC LIMIT 1;";
            $data = DB::select($q);

            if (!empty($data)) {
                $lastMarking = $data[0]->no_invoice;
                $lastYearMonth = substr($lastMarking, 0, 4);

                if ($lastYearMonth === $yearMonth) {
                    $lastNumber = (int)substr($lastMarking, 4);
                    $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
                } else {
                    $newNumber = '00001';
                }
            } else {
                $newNumber = '00001';
            }

            $newNoinvoice = $yearMonth . $newNumber;

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'No invoice berhasil di-generate', 'no_invoice' => $newNoinvoice], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Gagal menghasilkan nomor invoice: ' . $e->getMessage()], 500);
        }
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
        // $isNotif = $request->isNotif;

        $dateCondition = '';
        if ($startDate && $endDate) {
            $dateCondition = "AND a.tanggal_invoice BETWEEN '$startDate' AND '$endDate'";
        }

        $statusCondition = $status ? "AND d.status_name LIKE '$status'" : "";

        // Updated query to include wa_status only for relevant invoices
        $q = "SELECT a.id,
                    a.no_invoice,
                    DATE_FORMAT(a.tanggal_invoice, '%d %M %Y') AS tanggal_bayar,
                    b.nama_pembeli AS pembeli,
                    a.alamat,
                    a.metode_pengiriman,
                    a.total_harga AS harga,
                    a.matauang_id,
                    a.rate_matauang,
                    GROUP_CONCAT(r.no_resi ORDER BY r.no_resi SEPARATOR ', ') AS resi_list,
                    d.id AS status_id,
                    d.status_name,
                    a.wa_status  -- Include the WhatsApp message status
                FROM tbl_invoice AS a
                JOIN tbl_pembeli AS b ON a.pembeli_id = b.id
                JOIN tbl_status AS d ON a.status_id = d.id
                LEFT JOIN tbl_resi AS r ON r.invoice_id = a.id
                WHERE (
                    UPPER(b.nama_pembeli) LIKE '$txSearch'
                    OR UPPER(a.no_invoice) LIKE '$txSearch'
                    )
                $dateCondition
                $statusCondition
                GROUP BY a.id, a.no_invoice, a.tanggal_invoice, b.nama_pembeli, a.alamat, a.metode_pengiriman, a.total_harga, a.matauang_id, a.rate_matauang, d.id, d.status_name, a.wa_status
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

        // Currency symbols array
        $currencySymbols = [
            1 => 'Rp. ',
            2 => '$ ',
            3 => '¥ '
        ];

        // Table generation
        $output = '<table class="table align-items-center table-flush table-hover" id="tableInvoice">
                        <thead class="thead-light">
                            <tr>';
                            // if ($isNotif == 'true') {
                                $output .= '<th class="no-sort"><input type="checkbox" class="selectAll" id="selectAll"></th>';
                            // }
        $output .= '
                                <th>No Invoice</th>
                                <th>Tanggal</th>
                                <th>Customer</th>
                                <th>Pengiriman</th>
                                <th>Alamat</th>
                                <th>Harga</th>
                                <th>Status</th>';
                            // if ($isNotif = 'true') {
                                $output .= '<th>Action</th>';
                            // }
        $output .= '</tr>
                        </thead>
                        <tbody>';

        foreach ($data as $item) {
            $statusBadgeClass = '';
            $btnChangeMethod = '';

            switch ($item->status_name) {
                case 'Batam / Sortir':
                    $statusBadgeClass = 'badge-primary';
                    break;
                case 'Ready For Pickup':
                    $statusBadgeClass = 'badge-warning';
                    break;
                case 'Out For Delivery':
                    $statusBadgeClass = 'badge-primary';
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

            // Change method button for certain conditions
            if ($item->metode_pengiriman == 'Pickup' && $item->status_id == 1) {
                $btnChangeMethod = '<a class="btn btnChangeMethod btn-sm btn-success text-white" data-id="' . $item->id . '" data-method="Delivery" ><i class="fas fa-sync-alt"></i></a>';
            }

            // Convert the price based on currency
            $convertedHarga = $item->harga;
            if ($item->matauang_id != 1) {
                $convertedHarga = $item->harga / $item->rate_matauang;
            }

            // WhatsApp status icon logic
            $waStatusIcon = '';
            if ($item->wa_status == 'pending') {
                $waStatusIcon = '<i class="fas fa-paper-plane" style="font-size: 12px; color: orange;"></i>';
            } elseif ($item->wa_status == 'sent') {
                $waStatusIcon = '<i class="fas fa-check-circle" style="font-size: 12px; color: green;"></i>';
            } elseif ($item->wa_status == 'failed') {
                $waStatusIcon = '<i class="fas fa-exclamation" style="font-size: 12px; color: red;"></i>';
            }

            // Generating table rows
            $output .= '<tr>';
            // if ($isNotif == 'true') {
                $output .= '<td><input type="checkbox" class="selectItem" data-id="' . $item->id . '"></td>';
            // }

            $output .= '
                <td>' . ($item->no_invoice ?? '-') . ' ' . $waStatusIcon . '</td>
                <td>' . ($item->tanggal_bayar ?? '-') . '</td>
                <td>' . ($item->pembeli ?? '-') . '</td>
                <td>' . ($item->metode_pengiriman ?? '-') . '</td>
                <td>' . ($item->alamat ?? '-') . '</td>
                <td>' . $currencySymbols[$item->matauang_id] . number_format($convertedHarga, 2, '.', ',') . '</td>
                <td><span class="badge ' . $statusBadgeClass . '">' . ($item->status_name ?? '-') . '</span></td>';

            // if ($isNotif = 'true') {
                $output .= '<td>' . $btnChangeMethod . '
                            <a class="btn btnExportInvoice btn-sm btn-secondary text-white" data-id="' . $item->id . '"><i class="fas fa-print"></i></a>
                            </td>';
            // }
            $output .= '</tr>';
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
        $noInvoice = $request->input('noInvoice');
        $noResi = $request->input('noResi');
        $tanggal = $request->input('tanggal');
        $customer = $request->input('customer');
        $currencyInvoice = $request->input('currencyInvoice');
        $metodePengiriman = $request->input('metodePengiriman');
        $rateCurrency = $request->input('rateCurrency');
        $beratBarang = $request->input('beratBarang');
        $panjang = $request->input('panjang');
        $lebar = $request->input('lebar');
        $tinggi = $request->input('tinggi');
        $alamatTujuan = $request->input('alamat');
        $hargaBarang = $request->input('hargaBarang');
        $totalharga = $request->input('totalharga');

        $existingInvoice = DB::table('tbl_invoice')->where('no_invoice', $noInvoice)->first();
        if ($existingInvoice) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nomor invoice sudah ada, silakan refresh nomor invoice.'
            ], 400);
        }

        $date = DateTime::createFromFormat('j F Y', $tanggal);
        $formattedDate = $date ? $date->format('Y-m-d') : null;

        DB::beginTransaction();

        try {

            $invoiceId = DB::table('tbl_invoice')->insertGetId([
                'no_invoice' => $noInvoice,
                'tanggal_invoice' => $formattedDate,
                'pembeli_id' => $customer,
                'metode_pengiriman' => $metodePengiriman,
                'alamat' => $alamatTujuan,
                'matauang_id' => $currencyInvoice,
                'rate_matauang' => $rateCurrency,
                'total_harga' => $totalharga,
                'status_id' => 1,
                'created_at' => now(),
            ]);

            if (!$invoiceId) {
                throw new \Exception("Gagal mendapatkan ID baru dari tbl_invoice");
            }

            foreach ($noResi as $index => $resi) {

                $noDo = DB::table('tbl_tracking')->where('no_resi', $resi)->value('no_do');
                DB::table('tbl_resi')->insert([
                    'invoice_id' => $invoiceId,
                    'no_resi' => $resi,
                    'no_do' => $noDo,
                    'berat' => $beratBarang[$index] ?? null,
                    'panjang' => $panjang[$index] ?? null,
                    'lebar' => $lebar[$index] ?? null,
                    'tinggi' => $tinggi[$index] ?? null,
                    'harga' => $hargaBarang[$index] ?? null,
                    'created_at' => now(),
                ]);

            }

            foreach ($noResi as $resi) {
                $updatedTracking = DB::table('tbl_tracking')
                    ->where('no_resi', $resi)
                    ->update(['status' => 'Batam / Sortir']);

                if (!$updatedTracking) {
                    throw new \Exception("{$resi} No Resi ini tidak terdaftar di Tracking");
                }
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

    public function kirimPesanWaPembeli(Request $request)
    {
        try {
            // Ambil invoice IDs dari request
            $invoiceIds = $request->input('id');

            if (!is_array($invoiceIds) || count($invoiceIds) === 0) {
                throw new \Exception("Tidak ada invoice yang diterima");
            }

            // Dispatch job untuk setiap invoice ID
            foreach ($invoiceIds as $invoiceId) {
                KirimPesanWaPembeliJob::dispatch($invoiceId);
            }

            return response()->json(['success' => true, 'message' => 'Pesan WhatsApp berhasil dikirim untuk semua invoice']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
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

            $q = "SELECT a.id,
                        a.no_invoice,
                        DATE_FORMAT(a.tanggal_invoice, '%d %M %Y') AS tanggal_bayar,
                        b.nama_pembeli AS pembeli,
                        a.alamat,
                        a.metode_pengiriman,
                        a.total_harga AS harga,
                        a.matauang_id,
                        a.rate_matauang,
                        d.id AS status_id,
                        d.status_name
                    FROM tbl_invoice AS a
                    JOIN tbl_pembeli AS b ON a.pembeli_id = b.id
                    JOIN tbl_status AS d ON a.status_id = d.id
                    WHERE a.id = $id";
            $invoice = DB::select($q);

            if (!$invoice) {
                return response()->json(['error' => 'Invoice not found'], 404);
            }

            $invoice = $invoice[0];


            $resiData = DB::table('tbl_resi')
                ->where('invoice_id', $id)
                ->get(['no_resi', 'no_do', 'berat', 'panjang', 'lebar', 'tinggi', 'harga']);

            try {
                $pdf = Pdf::loadView('exportPDF.invoice', [
                    'invoice' => $invoice,
                    'resiData' => $resiData,
                    'hargaIDR' => $invoice->harga,
                    'tanggal' => $invoice->tanggal_bayar,
                ])
                ->setPaper('A4', 'portrait')
                ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                ->setWarnings(false);
            } catch (\Exception $e) {
                Log::error('Error generating PDF: ' . $e->getMessage(), ['exception' => $e]);
                return response()->json(['error' => 'Failed to generate PDF'], 500);
            }

            try {
                $fileName = 'invoice_' . (string) Str::uuid() . '.pdf';
                $filePath = storage_path('app/public/invoice/' . $fileName);
                $pdf->save($filePath);
            } catch (\Exception $e) {
                Log::error('Error saving PDF: ' . $e->getMessage(), ['exception' => $e]);
                return response()->json(['error' => 'Failed to save PDF'], 500);
            }

            $url = asset('storage/invoice/' . $fileName);
            return response()->json(['url' => $url]);

        } catch (\Exception $e) {
            Log::error('Error generating invoice PDF: ' . $e->getMessage(), ['exception' => $e]);
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

    public function cekResiInvoice(Request $request)
    {
        $noResi = $request->input('noResi');

        try {

            $tracking = DB::table('tbl_tracking')->where('no_resi', $noResi)->first();

            // Jika no_resi tidak ditemukan
            if (!$tracking) {
                return response()->json(['status' => 'error', 'message' => 'Nomor resi tidak ditemukan'], 404);
            }

            // Cek status
            if ($tracking->status === 'Dalam Perjalanan') {
                // Jika status "Dalam Perjalanan", kembalikan respons sukses
                return response()->json(['status' => 'success', 'message' => 'Nomor resi valid untuk diproses'], 200);
            } else {
                // Jika status bukan "Dalam Perjalanan", kembalikan respons dengan error
                return response()->json(['status' => 'error', 'message' => 'Status nomor resi tidak valid'], 400);
            }

        } catch (\Exception $e) {
            // Menangkap kesalahan dan mengembalikan respons error
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
    public function changeMethod(Request $request)
    {
        $invoiceId = $request->input('id');
        $newMethod = $request->input('method');

        $invoice = DB::table('tbl_invoice')->where('id', $invoiceId)->first();

        if ($invoice && $newMethod == 'Delivery' && $invoice->metode_pengiriman == 'Pickup') {
            $alamatPembeli = DB::table('tbl_alamat')
            ->where('pembeli_id', $invoice->pembeli_id)
            ->first();

            if ($alamatPembeli) {
                DB::table('tbl_invoice')->where('id', $invoiceId)->update([
                    'metode_pengiriman' => 'Delivery',
                    'alamat' => $alamatPembeli->alamat,
                    'updated_at' => now()
                ]);

                return response()->json(['success' => true, 'message' => 'Metode pengiriman berhasil diubah menjadi Delivery dan alamat diperbarui']);
            } else {
                return response()->json(['success' => false, 'message' => 'Alamat pembeli tidak ditemukan']);
            }
        }

        return response()->json(['success' => false, 'message' => 'Gagal mengubah metode pengiriman']);
    }

}
