<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Matauang;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Jobs\KirimPesanWaPembeliJob;
use App\Traits\WhatsappTrait;
use App\Http\Controllers\Admin\JournalController;
use App\Models\Jurnal;
use App\Models\JurnalItem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Log;
use Str;
use Yajra\DataTables\Facades\DataTables;


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
        $listDo = DB::table('tbl_resi')
            ->select('no_do')
            ->distinct()
            ->get();
        $listMarking = DB::table('tbl_pembeli')
            ->select('marking')
            ->distinct()
            ->get();
        $listStatus = DB::select("SELECT status_name FROM tbl_status");

        return view('customer.invoice.indexinvoice', [
            'listStatus' => $listStatus,
            'listDo' => $listDo,
            'listMarking' => $listMarking
        ]);
    }


    public function addinvoice()
    {


        $companyId = session('active_company_id');
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
                    $lastNumber = (int) substr($lastMarking, 4);
                    $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
                } else {
                    $newNumber = '00001';
                }
            } else {
                $newNumber = '00001';
            }

            $newNoinvoice = $yearMonth . $newNumber;

            $listPembeli = DB::table('tbl_pembeli as a')
            ->leftJoin('tbl_alamat as b', 'b.pembeli_id', '=', 'a.id')
            ->join('tbl_category as c', 'a.category_id', '=', 'c.id')
            ->where('a.company_id', $companyId)
            ->whereNull('a.deleted_at')
            ->groupBy('a.id', 'a.nama_pembeli', 'a.marking', 'a.metode_pengiriman', 'a.company_id', 'c.id', 'c.minimum_rate', 'c.maximum_rate')
            ->selectRaw('
                a.id,
                a.nama_pembeli,
                a.marking,
                a.metode_pengiriman,
                c.id AS category_id,
                c.minimum_rate,
                c.maximum_rate,
                GROUP_CONCAT(b.alamat SEPARATOR "; ") AS alamat,
                COUNT(b.id) AS jumlah_alamat
            ')
            ->get();

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
                    $lastNumber = (int) substr($lastMarking, 4);
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
        $companyId = session('active_company_id');

        $txSearch = $request->txSearch
            ? '%' . strtolower(trim($request->txSearch)) . '%'
            : '%%';

        /**
         * ---------------------------------
         *  RESI SUBQUERY (Pre-aggregated)
         * ---------------------------------
         */
        $resiSub = DB::table('tbl_resi')
            ->select(
                'invoice_id',
                DB::raw("GROUP_CONCAT(no_resi ORDER BY no_resi SEPARATOR ', ') AS resi_list"),
                DB::raw("GROUP_CONCAT(no_resi SEPARATOR '; ') AS no_resi"),
                DB::raw("COUNT(no_resi) AS resi_count"),
                DB::raw("MAX(no_do) AS no_do")
            )
            ->groupBy('invoice_id');

        /**
         * ---------------------------------
         *  MAIN QUERY + JOIN PERIODE
         *  (replaces slow per-row DB check)
         * ---------------------------------
         */
        $query = DB::table('tbl_invoice as a')
            ->select(
                'a.id',
                'a.no_invoice',
                'a.tanggal_invoice as raw_tanggal_invoice',
                DB::raw("DATE_FORMAT(a.tanggal_invoice, '%d %M %Y') AS tanggal_invoice"),
                DB::raw("DATE_FORMAT(a.tanggal_buat, '%d %M %Y') AS tanggal_bayar"),
                'b.nama_pembeli',
                'b.marking',
                'a.alamat',
                'a.metode_pengiriman',
                'a.total_harga AS harga',
                'a.matauang_id',
                'a.rate_matauang',
                'd.id AS status_id',
                'd.status_name',
                'a.wa_status',
                'a.status_bayar',
                DB::raw("DATE_FORMAT(a.created_at, '%d %M %Y %H:%i:%s') AS created_at_formatted"),
                DB::raw("DATE_FORMAT(a.updated_at, '%d %M %Y %H:%i:%s') AS updated_at_formatted"),
                'a.user',
                'a.user_update',

                // RESI MERGED RESULTS
                'r.no_do',
                'r.resi_list',
                'r.no_resi',
                'r.resi_count',

                'e.createby AS signed_by',
                'e.tanda_tangan',
                'p.status AS periode_status'
            )
            ->join('tbl_pembeli as b', 'a.pembeli_id', '=', 'b.id')
            ->join('tbl_status as d', 'a.status_id', '=', 'd.id')
            ->leftJoinSub($resiSub, 'r', 'r.invoice_id', '=', 'a.id')
            ->leftJoin('tbl_pengantaran_detail as e', 'e.invoice_id', '=', 'a.id')
            ->leftJoin('tbl_periode as p', function ($join) {
                $join->on('a.tanggal_invoice', '>=', 'p.periode_start')
                    ->on('a.tanggal_invoice', '<=', 'p.periode_end');
            })

            ->where('a.company_id', $companyId);

        /**
         * ---------------------------------
         *  SEARCH OPTIMIZED
         * ---------------------------------
         */
        if ($txSearch !== '%%') {
            $query->where(function ($q) use ($txSearch) {
                $q->whereRaw('LOWER(b.nama_pembeli) LIKE ?', [$txSearch])
                ->orWhereRaw('LOWER(a.no_invoice) LIKE ?', [$txSearch])
                ->orWhereRaw('LOWER(a.metode_pengiriman) LIKE ?', [$txSearch])
                ->orWhereRaw('LOWER(a.alamat) LIKE ?', [$txSearch])
                ->orWhereRaw('LOWER(b.marking) LIKE ?', [$txSearch])
                ->orWhereRaw('LOWER(r.resi_list) LIKE ?', [$txSearch]);
            });
        }

        /**
         * ---------------------------------
         *  FILTER
         * ---------------------------------
         */
        if ($request->startDate && $request->endDate) {
            $query->whereBetween('a.tanggal_buat', [
                $request->startDate,
                $request->endDate
            ]);
        }

        if ($request->status) {
            $query->where('d.status_name', 'LIKE', $request->status);
        }

        if ($request->payment_status !== null) {
            $query->where('status_bayar', $request->payment_status);
        }

        if ($request->no_do) {
            $query->where('r.no_do', 'LIKE', $request->no_do);
        }

        if ($request->marking) {
            $query->where('b.marking', 'LIKE', $request->marking);
        }

        /**
         * ---------------------------------
         *  ORDERING
         * ---------------------------------
         */
        if (!$request->has('order')) {
            $query->orderByRaw("
                CASE d.id
                    WHEN '1' THEN 1
                    WHEN '5' THEN 2
                    WHEN '3' THEN 3
                    WHEN '2' THEN 4
                    WHEN '4' THEN 5
                    ELSE 6
                END
            ");
            $query->orderBy('a.id', 'DESC');
        }

        /**
         * ---------------------------------
         *  DATATABLES
         * ---------------------------------
         */
        return DataTables::of($query)
            ->addColumn('no_invoice', function ($item) {
                $icons = [
                    'pending' => '<i class="fas fa-paper-plane" style="font-size: 12px; color: orange;"></i>',
                    'sent'    => '<i class="fas fa-check-circle" style="font-size: 12px; color: green;"></i>',
                    'failed'  => '<i class="fas fa-exclamation" style="font-size: 12px; color: red;"></i>'
                ];
                return ($item->no_invoice ?? '-') . ' ' . ($icons[$item->wa_status] ?? '');
            })
            ->addColumn('status_bayar', fn($row) =>
                $row->status_bayar == 'Lunas'
                    ? '<span class="text-success"><i class="fas fa-check-circle"></i> Lunas</span>'
                    : '<span class="text-danger"><i class="fas fa-exclamation-circle"></i> Belum lunas</span>'
            )
            ->addColumn('status_badge', function ($item) {
                $classes = [
                    'Batam / Sortir'   => 'badge-primary',
                    'Ready For Pickup' => 'badge-warning',
                    'Out For Delivery' => 'badge-primary',
                    'Delivering'       => 'badge-delivering',
                    'Received'         => 'badge-secondary'
                ];
                $class = $classes[$item->status_name] ?? 'badge-secondary';
                return "<span class='badge $class'>{$item->status_name}</span>";
            })
            ->addColumn('resi_cell', function ($item) {
                if ($item->resi_count > 1) {
                    return "<button class='btn btn-primary btn-sm show-address-modal'
                            data-id='{$item->id}'
                            data-no_resi='" . htmlentities($item->no_resi) . "'>
                            Lihat Resi ({$item->resi_count})
                            </button>";
                }
                return $item->no_resi ?? '-';
            })
            ->addColumn('converted_harga', function ($item) {
                $symbols = [1 => 'Rp. ', 2 => '$ ', 3 => '¥ '];
                $converted = $item->matauang_id == 1
                    ? $item->harga
                    : $item->harga / $item->rate_matauang;
                return $symbols[$item->matauang_id] . number_format($converted, 2, '.', ',');
            })
            ->addColumn('created_at', fn($i) => $i->created_at_formatted ?? '-')
            ->addColumn('updated_at', fn($i) => $i->user_update ? ($i->updated_at_formatted ?? '-') : '-')
            ->addColumn('signed_by', fn($i) => $i->tanda_tangan ? ($i->signed_by ?? '-') : '-')

            ->addColumn('action', function ($item) {

                $btnExport = "<a class='btn btnExportInvoice btn-sm btn-primary text-white mr-1'
                            data-id='{$item->id}'><i class='fas fa-print'></i></a>";
                if ($item->periode_status === 'Closed') {
                    $btnEdit = "<button class='btn btn-sm btn-secondary text-white' disabled>
                                <i class='fas fa-lock'></i>
                                </button>";
                } else {
                    $btnEdit = "<a class='btn btnEditInvoice btn-sm btn-secondary text-white'
                                data-id='{$item->id}'><i class='fas fa-edit'></i></a>";
                }

                return "<div class='d-flex'>{$btnExport}{$btnEdit}</div>";
            })

            ->rawColumns(['no_invoice', 'status_bayar', 'status_badge', 'action', 'resi_cell'])
            ->make(true);
    }

    public function tambainvoice(Request $request)
    {
        // dd($request->all());
        $companyId = session('active_company_id');
        $noInvoice = $request->input('noInvoice');
        $noResi = $request->input('noResi');
        $resiCounts = array_count_values($noResi);
        $duplicateResi = array_keys(array_filter($resiCounts, function ($count) {
            return $count > 1;
        }));

        if (!empty($duplicateResi)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terdapat no resi yang duplikat: ' . implode(', ', $duplicateResi),
                'duplicates' => $duplicateResi,
            ], 400);
        }
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
        $rateBerat = $request->input('rateBerat');
        $rateVolume = $request->input('rateVolume');
        $pembagiVolume = $request->input('pembagiVolume');
        $hargaBarang = $request->input('hargaBarang');
        $totalharga = ceil($request->input('totalharga') / 1000) * 1000;
        $user = Auth::user()->name;

        $accountSettings = DB::table('tbl_account_settings')->first();
        if (!$accountSettings) {
            Log::error("Gagal menemukan pengaturan akun. Silakan cek Account setting.");
            return response()->json([
                'status' => 'error',
                'message' => 'Silakan cek Account setting untuk mengatur pemilihan Account.',
            ], 400);
        }

        $salesAccountId = $accountSettings->sales_account_id;
        $receivableSalesAccountId = $accountSettings->receivable_sales_account_id;

        if (is_null($salesAccountId) || is_null($receivableSalesAccountId)) {
            Log::error("sales_account_id atau receivable_sales_account_id kosong di pengaturan akun.");
            return response()->json([
                'status' => 'error',
                'message' => 'Silakan cek Account setting untuk mengatur pemilihan Account.',
            ], 400);
        }

        $date = DateTime::createFromFormat('j F Y', $tanggal);
        $formattedDate = $date ? $date->format('Y-m-d') : null;

        $rateBeratId = DB::table('tbl_rate')->where('nilai_rate', $rateBerat)->where('rate_for', 'Berat')->value('id');
        $rateVolumeId = DB::table('tbl_rate')->where('nilai_rate', $rateVolume)->where('rate_for', 'Volume')->value('id');
        $pembagiId = DB::table('tbl_pembagi')->where('nilai_pembagi', $pembagiVolume)->value('id');

        if (!$rateBeratId || !$rateVolumeId || !$pembagiId) {
            Log::error("Data Rate atau Pembagi tidak valid.");
            return response()->json(['status' => 'error', 'message' => 'Data Rate atau Pembagi tidak valid.'], 400);
        }

        $existingInvoice = DB::table('tbl_invoice')->where('no_invoice', $noInvoice)->first();
        if ($existingInvoice) {
            Log::warning("Nomor invoice sudah ada: {$noInvoice}");
            return response()->json([
                'status' => 'error',
                'message' => 'Nomor invoice sudah ada, silakan refresh nomor invoice.'
            ], 400);
        }

        DB::beginTransaction();
        try {

            $closedPeriod = DB::table('tbl_periode')
                ->whereDate('periode_start', '<=', $formattedDate)
                ->whereDate('periode_end', '>=', $formattedDate)
                ->where('status', 'Closed')
                ->first();

            if ($closedPeriod) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak dapat membuat invoice karena tanggal tersebut berada di dalam periode yang sudah ditutup: ' . $closedPeriod->periode,
                ], 400);
            }


            Log::info("Memulai penyimpanan invoice.");

            $invoiceId = DB::table('tbl_invoice')->insertGetId([
                'no_invoice' => $noInvoice,
                'tanggal_invoice' => $formattedDate,
                'tanggal_buat' => now(),
                'pembeli_id' => $customer,
                'metode_pengiriman' => $metodePengiriman,
                'alamat' => $alamatTujuan,
                'matauang_id' => $currencyInvoice,
                'rate_matauang' => $rateCurrency,
                'total_harga' => $totalharga,
                'rateberat_id' => $rateBeratId,
                'ratevolume_id' => $rateVolumeId,
                'pembagi_id' => $pembagiId,
                'status_id' => 1,
                'created_at' => now(),
                'user' => $user,
                'company_id' => $companyId,
            ]);

            Log::info("Berhasil menyimpan invoice dengan ID: {$invoiceId}");

            if (!$invoiceId) {
                throw new \Exception("Gagal mendapatkan ID baru dari tbl_invoice");
            }

            foreach ($noResi as $index => $resi) {
                Log::info("Memproses resi: {$resi}");

                $noDo = DB::table('tbl_tracking')->where('no_resi', $resi)->value('no_do');
                $harga = isset($hargaBarang[$index]) ? str_replace(',', '.', str_replace('.', '', $hargaBarang[$index])) : null;

                DB::table('tbl_resi')->insert([
                    'invoice_id' => $invoiceId,
                    'no_resi' => $resi,
                    'no_do' => $noDo,
                    'priceperkg' => $rateBerat,
                    'berat' => $beratBarang[$index] ?? null,
                    'panjang' => $panjang[$index] ?? null,
                    'lebar' => $lebar[$index] ?? null,
                    'tinggi' => $tinggi[$index] ?? null,
                    'harga' => $harga,
                    'created_at' => now(),
                ]);
                Log::info("Berhasil menyimpan data resi untuk invoice: {$noInvoice}");
            }

            $updateStatus = 'Batam / Sortir';
            foreach ($noResi as $resi) {
                $updatedTracking = DB::table('tbl_tracking')
                    ->where('no_resi', $resi)
                    ->update(['status' => $updateStatus]);
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
                Log::info("Membuat jurnal untuk invoice {$noInvoice}");

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
                $jurnal->invoice_id = $invoiceId;
                $jurnal->company_id = $companyId;
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
                Log::error("Gagal menambahkan jurnal: " . $e->getMessage());
                throw new \Exception('Gagal menambahkan jurnal: ' . $e->getMessage());
            }

            DB::commit();
            Log::info("Proses tambainvoice selesai dengan sukses.");
            return response()->json(['status' => 'success', 'message' => 'Invoice Berhasil ditambahkan dan status tracking diperbarui'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal menambahkan Invoice. Error: " . $e->getMessage());

            return response()->json(['status' => 'error', 'message' => 'Gagal menambahkan Invoice. Silakan coba lagi.'], 500);
        }
    }
    public function editInvoice(Request $request, $id)
    {

        $noInvoice = $request->input('noInvoice');
        $noResi = $request->input('noResi');
        $tanggal = $request->input('tanggal');
        $customer = $request->input('customer');
        $currencyInvoice = $request->input('currencyInvoice');
        $metodePengiriman = $request->input('metodePengiriman');
        $rateCurrency = $request->input('rateCurrency');
        $beratBarang = $request->input('beratBarang');
        $alamatTujuan = $request->input('alamat');
        $rateBerat = $request->input('rateBerat');
        $rateVolume = $request->input('rateVolume');
        $pembagiVolume = $request->input('pembagiVolume');
        $hargaBarang = $request->input('hargaBarang');
        $totalharga = $request->input('totalharga');
        $panjang = $request->input('panjang');
        $lebar = $request->input('lebar');
        $tinggi = $request->input('tinggi');
        $user_update = Auth::user()->name;

        $date = DateTime::createFromFormat('j F Y', $tanggal);
        $formattedDate = $date ? $date->format('Y-m-d') : null;

        DB::beginTransaction();
        try {

             $closedPeriod = DB::table('tbl_periode')
                ->whereDate('periode_start', '<=', $formattedDate)
                ->whereDate('periode_end', '>=', $formattedDate)
                ->where('status', 'Closed')
                ->first();

            if ($closedPeriod) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak dapat membuat invoice karena tanggal tersebut berada di dalam periode yang sudah ditutup: ' . $closedPeriod->periode,
                ], 400);
            }
            Log::info("Memulai proses edit invoice untuk Invoice ID: {$id}");

            Log::info("Memperbarui data pada tabel tbl_invoice untuk Invoice ID: {$id}");
            $dataToUpdate = [
                'no_invoice' => $noInvoice,
                'tanggal_invoice' => $formattedDate,
                'pembeli_id' => $customer,
                'metode_pengiriman' => $metodePengiriman,
                'alamat' => $alamatTujuan,
                'matauang_id' => $currencyInvoice,
                'rate_matauang' => $rateCurrency,
                'total_harga' => $totalharga,
                'rateberat_id' => DB::table('tbl_rate')->where('nilai_rate', $rateBerat)->where('rate_for', 'Berat')->value('id'),
                'ratevolume_id' => DB::table('tbl_rate')->where('nilai_rate', $rateVolume)->where('rate_for', 'Volume')->value('id'),
                'pembagi_id' => DB::table('tbl_pembagi')->where('nilai_pembagi', $pembagiVolume)->value('id'),
                'updated_at' => now(),
                'user_update' => $user_update,
            ];

            $updateInvoice = DB::table('tbl_invoice')->where('id', $id)->update($dataToUpdate);

            if (!$updateInvoice) {
                throw new \Exception("Gagal memperbarui data invoice untuk Invoice ID: {$id}");
            }
            Log::info("Berhasil memperbarui data tbl_invoice untuk Invoice ID: {$id}");


            $resiLama = DB::table('tbl_resi')->where('invoice_id', $id)->pluck('no_resi')->toArray();

            $countResiLama = array_count_values($resiLama);
            $countNoResi   = array_count_values($noResi);

            $resiDihapus = [];
            $resiDitambahkan = [];

            // Cari resi yang berkurang (dihapus)
            foreach ($countResiLama as $resi => $jumlahLama) {
                $jumlahBaru = $countNoResi[$resi] ?? 0;

                if ($jumlahLama > $jumlahBaru) {
                    $selisih = $jumlahLama - $jumlahBaru;
                    $resiDihapus = array_merge($resiDihapus, array_fill(0, $selisih, $resi));
                }
            }

            // Cari resi yang bertambah (ditambahkan)
            foreach ($countNoResi as $resi => $jumlahBaru) {
                $jumlahLama = $countResiLama[$resi] ?? 0;

                if ($jumlahBaru > $jumlahLama) {
                    $selisih = $jumlahBaru - $jumlahLama;
                    $resiDitambahkan = array_merge($resiDitambahkan, array_fill(0, $selisih, $resi));
                }
            }

            $resiDihapus = array_map('strval', $resiDihapus);
            $resiDitambahkan = array_map('strval', $resiDitambahkan);
            if (!empty($resiDihapus)) {
                $statusResi = DB::table('tbl_tracking')
                    ->whereIn('no_resi', $resiDihapus)
                    ->pluck('status', 'no_resi')
                    ->toArray();

                foreach ($statusResi as $resi => $status) {
                    if (!in_array($status, ['Batam / Sortir', 'Dalam Perjalan'])) {
                        return response()->json([
                            'status' => 'error',
                            'message' => "Resi {$resi} tidak dapat diedit karena statusnya '{$status}'."
                        ], 400);
                    }
                }

                DB::table('tbl_tracking')
                    ->whereIn('no_resi', $resiDihapus)
                    ->update(['status' => 'Dalam Perjalanan', 'updated_at' => now()]);

                Log::info("Status resi yang dihapus dikembalikan ke 'Dalam Perjalanan'.");
                DB::table('tbl_resi')->where('invoice_id', $id)->whereIn('no_resi', $resiDihapus)->delete();
                Log::info("Berhasil menghapus resi yang tidak dipakai untuk Invoice ID: {$id}");
            }

            foreach ($noResi as $index => $resi) {
                $noDo = DB::table('tbl_tracking')->where('no_resi', $resi)->value('no_do');

                DB::table('tbl_resi')->updateOrInsert(
                    ['invoice_id' => $id, 'no_resi' => $resi],
                    [
                        'no_do' => $noDo,
                        'berat' => $beratBarang[$index] ?? null,
                        'harga' => $hargaBarang[$index] ?? null,
                        'panjang' => $panjang[$index] ?? null,
                        'lebar' => $lebar[$index] ?? null,
                        'tinggi' => $tinggi[$index] ?? null,
                        'updated_at' => now(),
                    ]
                );
            }

            Log::info("Berhasil menambahkan dan memperbarui resi untuk Invoice ID: {$id}");

            if (!empty($resiDitambahkan)) {
                DB::table('tbl_tracking')
                    ->whereIn('no_resi', $resiDitambahkan)
                    ->update(['status' => 'Batam / Sortir', 'updated_at' => now()]);

                Log::info("Status resi baru diperbarui ke 'Batam / Sortir'.");
            }

            Log::info("Memperbarui transaksi terakhir untuk Pembeli ID: {$customer}");
            DB::table('tbl_pembeli')->where('id', $customer)->update(['transaksi_terakhir' => now()]);
            Log::info("Berhasil memperbarui transaksi terakhir untuk Pembeli ID: {$customer}");

            Log::info("Mengambil pengaturan akun dari tbl_account_settings");
            $accountSettings = DB::table('tbl_account_settings')->first();
            if (!$accountSettings) {
                Log::error("Pengaturan akun tidak ditemukan.");
                return response()->json([
                    'status' => 'error',
                    'message' => 'Silakan cek Account setting untuk mengatur pemilihan Account.',
                ], 400);
            }

            $salesAccountId = $accountSettings->sales_account_id;
            $receivableSalesAccountId = $accountSettings->receivable_sales_account_id;

            if (is_null($salesAccountId) || is_null($receivableSalesAccountId)) {
                Log::error("sales_account_id atau receivable_sales_account_id kosong di pengaturan akun.");
                return response()->json([
                    'status' => 'error',
                    'message' => 'Silakan cek Account setting untuk mengatur pemilihan Account.',
                ], 400);
            }

            try {
                Log::info("Memperbarui jurnal untuk Invoice ID: {$id}");
                $jurnal = DB::table('tbl_jurnal')->where('invoice_id', $id)->first();

                if (!$jurnal) {
                    throw new \Exception("Jurnal untuk Invoice ID: {$id} tidak ditemukan.");
                }

                DB::table('tbl_jurnal_items')->where('jurnal_id', $jurnal->id)->delete();

                DB::table('tbl_jurnal')->where('id', $jurnal->id)->update([
                    'totaldebit' => $totalharga,
                    'totalcredit' => $totalharga,
                    'tanggal' => $formattedDate,
                    'updated_at' => now(),
                ]);

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

                Log::info("Jurnal untuk Invoice ID: {$id} berhasil diperbarui.");

            } catch (\Exception $e) {
                Log::error("Gagal memperbarui jurnal: " . $e->getMessage());
                throw new \Exception('Gagal memperbarui jurnal: ' . $e->getMessage());
            }

            DB::commit();
            Log::info("Proses edit invoice untuk Invoice ID: {$id} selesai dengan sukses.");

            return response()->json(['status' => 'success', 'message' => 'Invoice berhasil diperbarui.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Terjadi kesalahan dalam proses edit invoice untuk Invoice ID: {$id}. Error: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Gagal memperbarui Invoice. Silakan coba lagi.'], 500);
        }
    }

    public function kirimPesanWaPembeli(Request $request)
    {

        try {
            $invoiceIds = $request->input('id');
            $type = $request->input('type');

            if (!is_array($invoiceIds) || count($invoiceIds) === 0) {
                throw new \Exception("Tidak ada invoice yang diterima");
            }

            foreach ($invoiceIds as $invoiceId) {
                $invoice = DB::table('tbl_invoice')->where('id', $invoiceId)->first();
                $statusPembayaran = $invoice ? $invoice->status_bayar : null;
                KirimPesanWaPembeliJob::dispatch($invoiceId, $type, $statusPembayaran);
            }

            return response()->json(['success' => true, 'message' => 'Pesan WhatsApp berhasil dikirim untuk semua invoice']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function kirimInvoice(Request $request)
    {
        try {

            $invoiceIds = $request->input('id');

            if (!is_array($invoiceIds) || count($invoiceIds) === 0) {
                throw new \Exception("Tidak ada invoice yang diterima");
            }

            return response()->json(['success' => true, 'message' => 'Invoice berhasil dikirim']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function exportPdf(Request $request, $token = null)
    {
        if ($request->ajax()) {
            $id = intval($request->input('id'));

            $invoiceExists = DB::table('tbl_invoice')->where('id', $id)->exists();
            if (!$invoiceExists) {
                return response()->json(['error' => 'Invoice not found'], 404);
            }

            $token = Str::uuid()->toString();

            Session::put("export_invoice_{$token}", $id);

            return response()->json([
                'url' => route('exportPdf', ['token' => $token])
            ]);
        }

        $id = Session::pull("export_invoice_{$token}");

        if (!$id) {
            return response()->json(['error' => 'Invalid or expired token'], 403);
        }

        $q = "SELECT
                a.id,
                a.no_invoice,
                DATE_FORMAT(a.tanggal_buat, '%d %M %Y') AS tanggal_bayar,
                b.nama_pembeli AS pembeli,
                a.alamat,
                b.marking,
                a.metode_pengiriman,
                a.total_harga AS harga,
                a.matauang_id,
                a.status_bayar,
                a.rateberat_id,
                a.rate_matauang,
                d.id AS status_id,
                d.status_name,
                e.tanda_tangan,
                c.metode_pengiriman,
                f.nama_supir,
                e.createby
                FROM tbl_invoice AS a
                JOIN tbl_pembeli AS b ON a.pembeli_id = b.id
                JOIN tbl_status AS d ON a.status_id = d.id
                LEFT JOIN tbl_pengantaran_detail AS e ON a.id = e.invoice_id
                LEFT JOIN tbl_pengantaran AS c ON e.pengantaran_id = c.id
                LEFT JOIN tbl_supir AS f ON c.supir_id = f.id
                WHERE a.id = ?
                ";

        $invoice = DB::select($q, [$id]);

        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        $invoice = $invoice[0];

        // Ambil rateberat_id dari tbl_invoice
        $rateberat_id = $invoice->rateberat_id;

        // Ambil nilai_rate dari tbl_rate berdasarkan rateberat_id
        $rateData = DB::table('tbl_rate')
            ->where('id', $rateberat_id)
            ->first(['nilai_rate']);

        $nilaiRateBerat = $rateData ? $rateData->nilai_rate : 0;

        $resiData = DB::table('tbl_resi')
            ->where('invoice_id', $id)
            ->get(['no_resi', 'no_do', 'priceperkg', 'berat', 'panjang', 'lebar', 'tinggi', 'harga']);

        // Cek apakah invoice memiliki Credit Note
        $creditNote = DB::table('tbl_credit_note')
            ->where('invoice_id', $id)
            ->first();

        $creditNoteItems = [];

        if ($creditNote) {
            // Ambil data Credit Note Item jika ada
            $creditNoteItems = DB::table('tbl_credit_note_item')
                ->where('credit_note_id', $creditNote->id)
                ->get(['no_resi', 'deskripsi', 'harga']);
        }

        $retur = DB::table('tbl_retur')
            ->where('invoice_id', $id)
            ->first();

        $returItems = [];

        if ($retur) {
            // Ambil data Credit Note Item jika ada
            $returItems = DB::table('tbl_retur_item')
                ->where('retur_id', $retur->id)
                ->get(['resi_id']);
        }

        $resi = [];
        foreach($returItems as $item){
            $returItems = DB::table('tbl_resi')
                ->where('id', $item->resi_id)
                ->get(['no_resi', 'harga', 'berat']);
        }
        try {
            $pdf = Pdf::loadView('exportPDF.invoice', [
                'invoice' => $invoice,
                'nilaiRateBerat' => $nilaiRateBerat,
                'resiData' => $resiData,
                'hargaIDR' => $invoice->harga,
                'creditNoteItems' => $creditNoteItems,
                'returItems' => $returItems,
                'tanggal' => $invoice->tanggal_bayar,
                'tanda_tangan' => $invoice->tanda_tangan ?? null
            ])
                ->setPaper('A4', 'portrait')
                ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                ->setWarnings(false);

            $fileName = 'invoice_' . $id . '.pdf';

            return response()->stream(function () use ($pdf) {
                echo $pdf->output();
            }, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate PDF'], 500);
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
                return response()->json([
                    'status' => 'success',
                    'message' => 'Nomor resi valid untuk diproses',
                    'no_do' => $tracking->no_do,
                ], 200);
            } elseif ($tracking->status === 'Return') {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Nomor resi valid dan sedang dalam status Return',
                    'no_do' => $tracking->no_do,
                ], 200);
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
    public function deleteoreditinvoice($id)
    {
        $invoice = Invoice::with('resi')->find($id);
        $invoiceDate = date('Y-m-d', strtotime($invoice->tanggal_invoice));
        $periodStatus = DB::table('tbl_periode')
                        ->whereDate('periode_start', '<=', $invoiceDate)
                        ->whereDate('periode_end', '>=', $invoiceDate)
                        ->value('status');
        $btnUpdateInvoiceDisabled = ($periodStatus == 'Closed') ? 'disabled' : '';
        
        $listCurrency = DB::table('tbl_matauang')->select('id', 'nama_matauang', 'singkatan_matauang')->get();
        $listAlamat = DB::select("SELECT a.id,
        a.nama_pembeli,
        a.marking,
        a.metode_pengiriman,
        c.id AS category_id,
        c.minimum_rate,
        c.maximum_rate,
        GROUP_CONCAT(b.alamat SEPARATOR '; ') AS alamat,
        COUNT(b.id) AS jumlah_alamat
            FROM tbl_pembeli a
            LEFT JOIN tbl_alamat b ON b.pembeli_id = a.id
            JOIN tbl_category c ON a.category_id = c.id
            GROUP BY a.id,
                a.nama_pembeli,
                a.marking,
                a.metode_pengiriman,
                c.id,
                c.minimum_rate,
                c.maximum_rate");


        $listRateVolume = DB::table('tbl_rate')->select('id', 'nilai_rate', 'rate_for')->get();
        $listRateBerat = DB::table('tbl_rate')->select('id', 'nilai_rate', 'rate_for')->get();
        $listPembagi = DB::table('tbl_pembagi')->select('id', 'nilai_pembagi')->get();

        return view('customer.invoice.deleteoreditinvoice', [
            'invoice' => $invoice,
            'listCurrency' => $listCurrency,
            'listRateVolume' => $listRateVolume,
            'listPembagi' => $listPembagi,
            'listAlamat' => $listAlamat,
            'listRateBerat' => $listRateBerat,
            'btnUpdateInvoiceDisabled' => $btnUpdateInvoiceDisabled,
        ]);
    }


    public function unpaidInvoices()
    {
        $unpaidInvoices = Invoice::selectRaw(
            'pembeli_id, SUM(IFNULL(total_harga, 0) - IFNULL(total_bayar, 0)) as total_sisa_bayar'
        )
            ->where('status_bayar', 'Belum lunas')
            ->where('tanggal_buat', '<', Carbon::now()->subMonths(2)) // Ubah ke 2 bulan
            ->groupBy('pembeli_id')
            ->with('pembeli:id,nama_pembeli,marking')
            ->get();

        $unpaidInvoices = $unpaidInvoices->map(function ($item) {
            return [
                'pembeli_id' => $item->pembeli_id,
                'nama_pembeli' => $item->pembeli->nama_pembeli ?? 'Unknown',
                'marking' => $item->pembeli->marking ?? 'N/A',
                'total_sisa_bayar' => number_format($item->total_sisa_bayar, 2, ',', '.'),
            ];
        });

        return response()->json($unpaidInvoices);
    }




}
