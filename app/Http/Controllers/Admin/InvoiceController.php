<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Jobs\KirimPesanWaPembeliJob;
use App\Traits\WhatsappTrait;
use App\Http\Controllers\Admin\JournalController;
use App\Models\Jurnal;
use App\Models\JurnalItem;
use Log;
use Str;


class InvoiceController extends Controller
{

    protected $jurnalController;

    public function __construct(JournalController $jurnalController)
    {
        $this->jurnalController = $jurnalController;
    }


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
                    a.wa_status,
                    a.status_bayar
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
                GROUP BY a.id, a.no_invoice, a.tanggal_invoice,a.status_bayar, b.nama_pembeli, a.alamat, a.metode_pengiriman, a.total_harga, a.matauang_id, a.rate_matauang, d.id, d.status_name, a.wa_status
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
                                <th>Status Pembayaran </th>
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
                $waStatusIcon = '<i class="fas fa-paper-plane" style="font-size: 12px; color: orange;" title="Mengirimkan pesan WhatsApp"></i>';
            } elseif ($item->wa_status == 'sent') {
                $waStatusIcon = '<i class="fas fa-check-circle" style="font-size: 12px; color: green;" title="Pesan WhatsApp terkirim"></i>';
            } elseif ($item->wa_status == 'failed') {
                $waStatusIcon = '<i class="fas fa-exclamation" style="font-size: 12px; color: red;" title="Pesan WhatsApp gagal"></i>';
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
                <td>' . ($item->status_bayar ?? '-') . '</td>
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

        $accountSettings = DB::table('tbl_account_settings')->first();

        $salesAccountId = $accountSettings->sales_account_id;
        $receivableSalesAccountId = $accountSettings->receivable_sales_account_id;

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

            try {
                $request->merge(['code_type' => 'AR']);
                $noJournal = $this->jurnalController->generateNoJurnal($request)->getData()->no_journal;
                $jurnal = new Jurnal();
                $jurnal->no_journal = $noJournal;
                $jurnal->tipe_kode = 'AR';
                $jurnal->tanggal = $formattedDate;
                $jurnal->no_ref = $noInvoice;
                $jurnal->status = 'Approve';
                $jurnal->description = "Jurnal untuk Invoice {$noInvoice}";
                $jurnal->totaldebit = $totalharga;
                $jurnal->totalcredit = $totalharga;
                $jurnal->save();

                $jurnalItemDebit = new JurnalItem();
                $jurnalItemDebit->jurnal_id = $jurnal->id;
                $jurnalItemDebit->code_account = $receivableSalesAccountId;
                $jurnalItemDebit->description = "Debit untuk Invoice {$noInvoice}";
                $jurnalItemDebit->debit = $totalharga;
                $jurnalItemDebit->credit = 0;
                $jurnalItemDebit->save();

                $jurnalItemCredit = new JurnalItem();
                $jurnalItemCredit->jurnal_id = $jurnal->id;
                $jurnalItemCredit->code_account = $salesAccountId;
                $jurnalItemCredit->description = "Kredit untuk Invoice {$noInvoice}";
                $jurnalItemCredit->debit = 0;
                $jurnalItemCredit->credit = $totalharga;
                $jurnalItemCredit->save();

            } catch (\Exception $e) {
                throw new \Exception('Gagal menambahkan jurnal: ' . $e->getMessage());
            }
                DB::commit();
                return response()->json(['status' => 'success', 'message' => 'Invoice berhasil ditambahkan dan status tracking diperbarui'], 200);
            } catch (\Exception $e) {
                DB::rollBack();

                if (strpos($e->getMessage(), 'code_account') !== false) {
                    return response()->json(['status' => 'error', 'message' => 'Pengaturan akun belum lengkap. Silakan periksa pengaturan akun di Account Setting.'], 400);
                }

                return response()->json(['status' => 'error', 'message' => 'Gagal menambahkan Invoice. Silakan coba lagi.'], 500);
        }
    }

    public function kirimPesanWaPembeli(Request $request)
    {
        try {
            $invoiceIds = $request->input('id');
            if (!is_array($invoiceIds) || count($invoiceIds) === 0) {
                throw new \Exception("Tidak ada invoice yang diterima");
            }
            foreach ($invoiceIds as $invoiceId) {
                KirimPesanWaPembeliJob::dispatch($invoiceId);
            }
            return response()->json(['success' => true, 'message' => 'Pesan WhatsApp berhasil dikirim untuk semua invoice']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
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



    public function cekResiInvoice(Request $request)
    {
        $noResi = $request->input('noResi');

        try {

            $tracking = DB::table('tbl_tracking')->where('no_resi', $noResi)->first();
            if (!$tracking) {
                return response()->json(['status' => 'error', 'message' => 'Nomor resi tidak ditemukan'], 404);
            }

            if ($tracking->status === 'Dalam Perjalanan') {
                return response()->json(['status' => 'success', 'message' => 'Nomor resi valid untuk diproses'], 200);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Status nomor resi tidak valid'], 400);
            }

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
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
