<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use DateTime;
use App\Traits\WhatsappTrait;
use Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Str;

class DeliveryController extends Controller
{
    use WhatsappTrait;

    public function sendInvoiceNotification($noWa, $message)
    {
        $this->kirimPesanWhatsapp($noWa, $message);
    }

    public function index(Request $request)
    {
        $companyId = session('active_company_id');
        $listnodo = DB::table('tbl_resi')
            ->join('tbl_invoice', 'tbl_resi.invoice_id', '=', 'tbl_invoice.id')
            ->select('tbl_resi.no_do')
            ->distinct()
            // ->where('tbl_invoice.status_id', 1)
            ->where('tbl_invoice.company_id', $companyId)
            ->get();

        $listmarking = DB::table('tbl_pembeli')
            ->join('tbl_invoice', 'tbl_pembeli.id', '=', 'tbl_invoice.pembeli_id')
            ->select('tbl_pembeli.marking')
            ->distinct()
            ->whereNull('tbl_pembeli.deleted_at')
            ->where('tbl_pembeli.company_id', $companyId)
            ->get();

        return view('customer.delivery.indexdelivery', [
            'listmarking' => $listmarking,
            'listnodo' => $listnodo,
        ]);
    }

    public function getlistDelivery(Request $request)
    {
        $status = strtoupper(trim($request->status));
        $startDate = $request->startDate ? date('Y-m-d', strtotime($request->startDate)) : null;
        $endDate = $request->endDate ? date('Y-m-d', strtotime($request->endDate)) : null;
        $filtermarking = $request->marking ?? '';
        $filternodo = $request->no_do ?? '';
        $companyId = session('active_company_id');

        $query = DB::table('tbl_pengantaran as a')
            ->select(
                'a.id as pengantaran_id',
                'a.metode_pengiriman',
                'a.supir_id',
                'e.nama_supir',
                DB::raw("GROUP_CONCAT(DISTINCT pd.id SEPARATOR ';') as pengantaran_detail_id"),
                DB::raw("GROUP_CONCAT(DISTINCT b.no_invoice SEPARATOR ';') as list_no_resi"),
                DB::raw("GROUP_CONCAT(c.nama_pembeli SEPARATOR ';') as list_nama_pembeli"),
                DB::raw("GROUP_CONCAT(IFNULL(b.alamat, 'Alamat Tidak Tersedia') SEPARATOR ';') as list_alamat"),
                DB::raw("MAX(DATE_FORMAT(a.tanggal_pengantaran, '%d %M %Y')) as tanggal_pengantaran"),
                DB::raw("COUNT(DISTINCT b.no_invoice) as jumlah_invoice"),
                DB::raw("GROUP_CONCAT(IFNULL(pd.bukti_pengantaran, 'Tidak Ada Bukti') SEPARATOR ';') as list_bukti_pengantaran"),
                DB::raw("GROUP_CONCAT(IFNULL(pd.tanda_tangan, 'Tidak Ada Tanda Tangan') SEPARATOR ';') as list_tanda_tangan"),
                DB::raw("GROUP_CONCAT(CONCAT(s.status_name) SEPARATOR ';') as list_status_per_invoice"),
                DB::raw("GROUP_CONCAT(IFNULL(pd.keterangan, 'Belum ada keterangan') SEPARATOR ';') as list_keterangan"),
                DB::raw("GROUP_CONCAT(r.no_do SEPARATOR ';') as list_no_do"),
                DB::raw("GROUP_CONCAT(c.marking SEPARATOR ';') as list_marking")
            )
            ->where('a.company_id', $companyId)
            ->join('tbl_pengantaran_detail as pd', 'a.id', '=', 'pd.pengantaran_id')
            ->join('tbl_invoice as b', 'pd.invoice_id', '=', 'b.id')
            ->join('tbl_pembeli as c', 'b.pembeli_id', '=', 'c.id')
            ->join('tbl_status as s', 'b.status_id', '=', 's.id')
            ->leftJoin('tbl_supir as e', 'a.supir_id', '=', 'e.id')
            ->leftJoin('tbl_resi as r', 'a.id', '=', 'r.invoice_id')
            ->groupBy('a.id', 'a.metode_pengiriman', 'a.supir_id', 'e.nama_supir')
            ->orderBy('a.id', 'desc');

        if ($request->txSearch) {
            $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';
            $query->where(function ($q) use ($txSearch) {
                $q->where(DB::raw('UPPER(e.nama_supir)'), 'LIKE', $txSearch);
            });
        }

        if ($request->status) {
            $query->where('s.status_name', '=', $status);
        }
        if (!empty($filtermarking)) {
            $query->where('c.marking', '=', $filtermarking);
        }

        if (!empty($filternodo)) {
            $query->where('r.no_do', '=', $filternodo);
        }
        if ($startDate && $endDate) {
            $query->whereBetween('a.tanggal_pengantaran', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('a.tanggal_pengantaran', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('a.tanggal_pengantaran', '<=', $endDate);
        }

        $data = $query->get();

        // dd( $query );

        $output = '<table class="table align-items-center table-flush table-hover" id="tableDelivery">
        <thead class="thead-light">
            <tr>
                <th>Pengiriman</th>
                <th>Supir</th>
                <th>Jumlah Invoice</th>
                <th>Tanggal</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        foreach ($data as $item) {
            $pengantaranDetailId = htmlspecialchars($item->pengantaran_id ?? '');
            $jumlahInvoice = htmlspecialchars($item->jumlah_invoice ?? '0');
            $btnInvoice = '
            <button type="button" class="btn btn-primary btn-sm show-invoice-modal"
                data-id="' . $pengantaranDetailId . '">
                Invoice (' . $jumlahInvoice . ')
            </button>';

            $output .= '
            <tr>
                <td>' . ($item->metode_pengiriman ?? '-') . '</td>
                <td>' . ($item->nama_supir ?? '-') . '</td>
                <td>' . $btnInvoice . '</td>
                <td>' . ($item->tanggal_pengantaran ?? '-') . '</td>
                <td>
                    <a class="btn btnExportPDF btn-secondary text-white" data-id="' . $item->pengantaran_id . '"><i class="fas fa-file-pdf"></i></a>
                </td>
            </tr>';
        }

        $output .= '</tbody></table>';

        return $output;
    }


    public function getInvoiceDetails(Request $request)
    {
        $pengantaranId = $request->input('pengantaran_id');
        $pengantaranId = explode(';', $pengantaranId);

        // Ambil data pengantaran
        $pengantaran = DB::table('tbl_pengantaran')
            ->whereIn('id', $pengantaranId)
            ->get();

        if ($pengantaran->isEmpty()) {
            return '<div class="alert alert-danger">Data tidak ditemukan</div>';
        }

        // Ambil semua invoice terkait dengan pengantaran
        $invoices = DB::table('tbl_pengantaran_detail as c')
            ->join('tbl_invoice as d', 'c.invoice_id', '=', 'd.id')
            ->join('tbl_pembeli as e', 'd.pembeli_id', '=', 'e.id')
            ->leftJoin('tbl_resi as f', 'f.invoice_id', '=', 'd.id') // Pakai left join untuk menghindari error jika tidak ada resi
            ->leftJoin('tbl_status as g', 'd.status_id', '=', 'g.id') // Join ke tbl_status
            ->select(
                'c.pengantaran_id',
                'c.invoice_id',
                'd.no_invoice',
                'e.marking',
                'e.nama_pembeli',
                DB::raw('MIN(f.no_do) AS no_do'),
                'c.bukti_pengantaran',
                'c.tanda_tangan',
                'g.status_name',
                'c.keterangan'
            )
            ->whereIn('c.pengantaran_id', $pengantaranId)
            ->groupBy(
                'c.pengantaran_id',
                'c.invoice_id',
                'd.no_invoice',
                'e.marking',
                'e.nama_pembeli',
                'c.bukti_pengantaran',
                'c.tanda_tangan',
                'g.status_name',
                'c.keterangan'
            )
            ->get();

        // Buat tabel dalam format HTML
        $output = '<table class="table align-items-center table-flush table-hover" id="tableInvoice">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>No Invoice</th>
                                <th>Marking</th>
                                <th>No. DO</th>
                                <th>Bukti Pengantaran</th>
                                <th>Tanda Tangan</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>';

        $no = 1;
        foreach ($invoices as $invoice) {
            $buktiLinks = 'Tidak Ada Bukti';
            if (!empty($invoice->bukti_pengantaran)) {
                $buktiImages = explode('|', $invoice->bukti_pengantaran);
                $buktiLinks = '';
                foreach ($buktiImages as $img) {
                    $label = str_contains($img, 'signature') ? 'Lihat Bukti' : 'Lihat Foto';
                    $buktiLinks .= '<a href="' . asset('storage/' . $img) . '" target="_blank">' . $label . '</a><br>';
                }
            }

            $tandaTangan = $invoice->tanda_tangan
                ? '<a href="' . asset('storage/' . $invoice->tanda_tangan) . '" target="_blank">Lihat Tanda Tangan</a>'
                : 'Tidak Ada Tanda Tangan';

            // Menentukan class badge berdasarkan status
            $badgeClass = match ($invoice->status_name) {
                'Batam / Sortir' => 'badge-primary',
                'Ready For Pickup' => 'badge-warning',
                'Out For Delivery' => 'badge-primary',
                'Delivering' => 'badge-delivering',
                'Received' => 'badge-secondary',
                default => 'badge-secondary',
            };

            // Format badge untuk status
            $statusBadge = '<span class="badge ' . $badgeClass . '">' . htmlspecialchars($invoice->status_name) . '</span>';

            $output .= '<tr>
                            <td>' . $no++ . '</td>
                            <td>' . $invoice->no_invoice . '</td>
                            <td>' . ($invoice->marking ?? 'Tidak Ada Marking') . '</td>
                            <td>' . ($invoice->no_do ?? 'Tidak Ada No. DO') . '</td>
                            <td>' . $buktiLinks . '</td>
                            <td>' . $tandaTangan . '</td>
                            <td>' . $statusBadge . '</td>
                            <td>' . ($invoice->keterangan ?? 'Belum ada keterangan') . '</td>
                        </tr>';
        }

        $output .= '</tbody></table>';

        return $output;
    }



    public function addDelivery()
    {
        $companyId = session('active_company_id');
        $listNoDo = DB::table('tbl_resi')
            ->join('tbl_invoice', 'tbl_resi.invoice_id', '=', 'tbl_invoice.id')
            ->select('tbl_resi.no_do')
            ->distinct()
            ->where('tbl_invoice.status_id', 1)
            ->where('tbl_invoice.metode_pengiriman', 'Delivery')
            ->get();

        $listMarking = DB::table('tbl_pembeli')
            ->join('tbl_invoice', 'tbl_pembeli.id', '=', 'tbl_invoice.pembeli_id')
            ->select('tbl_pembeli.marking')
            ->distinct()
            ->where('tbl_invoice.status_id', 1)
            ->where('tbl_invoice.metode_pengiriman', 'Delivery')
            ->get();

        $listNoDoPickup = DB::table('tbl_resi  as p')
            ->join('tbl_invoice as i', 'p.invoice_id', '=', 'i.id')
            ->select('p.no_do')
            ->distinct()
            ->where('i.status_id', 1)
            ->where('i.metode_pengiriman', 'Pickup')
            ->get();


        $listMarkingPickup = DB::table('tbl_pembeli as p')
            ->join('tbl_invoice as i', 'p.id', '=', 'i.pembeli_id')
            ->select('p.marking')
            ->distinct()
            ->where('i.status_id', 1)
            ->where('i.metode_pengiriman', 'Pickup')
            ->get();
        $listSopir = DB::select("SELECT id,
        nama_supir,
        no_wa
        FROM tbl_supir
        WHERE tbl_supir.company_id = $companyId");

        return view('customer.delivery.buatdelivery', [
            'listSupir' => $listSopir,
            'listMarking' => $listMarking,
            'listNoDo' => $listNoDo,
            'listNoDoPickup' => $listNoDoPickup,
            'listMarkingPickup' => $listMarkingPickup
        ]);
    }

    public function getlistTableBuatDelivery(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';

        $filterMarking = $request->marking ?? '';
        $filterNoDo = $request->no_do ?? '';
        $companyId = session('active_company_id');

        if ($request->filter_date) {
            [$startDate, $endDate] = explode(' - ', $request->filter_date);
            $startDate = date('Y-m-d', strtotime($startDate));
            $endDate = date('Y-m-d', strtotime($endDate));
        } else {
            $startDate = null;
            $endDate = null;
        }

        $q = "SELECT a.id,
                a.no_invoice,
                DATE_FORMAT(a.tanggal_invoice, '%d %M %Y') AS tanggal_bayar,
                b.nama_pembeli AS pembeli,
                c.no_do,
                b.marking,
                a.metode_pengiriman,
                a.status_id
            FROM tbl_invoice AS a
            JOIN tbl_pembeli AS b ON a.pembeli_id = b.id
            JOIN tbl_resi AS c ON a.id = c.invoice_id
            JOIN tbl_status AS d ON a.status_id = d.id
            WHERE (
                UPPER(a.no_invoice) LIKE '$txSearch'
                OR UPPER(b.nama_pembeli) LIKE '$txSearch'
                OR UPPER(c.no_do) LIKE '$txSearch'
                OR UPPER(b.marking) LIKE '$txSearch'
            )
            AND a.status_id = 1
            AND a.metode_pengiriman = 'Delivery'";

        if ($startDate && $endDate) {
            $q .= " AND DATE(a.tanggal_invoice) BETWEEN '$startDate' AND '$endDate'";
        }

        if (!empty($filterMarking)) {
            $q .= " AND b.marking = '$filterMarking'";
        }

        if (!empty($filterNoDo)) {
            $q .= " AND c.no_do = '$filterNoDo'";
        }

        $q .= "GROUP BY a.id, a.no_invoice, a.tanggal_invoice, b.nama_pembeli, c.no_do, b.marking, a.metode_pengiriman, a.status_id";

        // Execute the query and get the results
        $data = DB::select($q);

        $output = '<table id="datatable_resi" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select_all"></th>
                            <th>No Do</th>
                            <th>Marking</th>
                            <th>No Invoice</th>
                            <th>Tanggal</th>
                            <th>Customer</th>
                        </tr>
                    </thead>
                    <tbody>';
        foreach ($data as $item) {
            $output .= '
                       <tr>
                            <td><input type="checkbox" class="checkbox_resi" value="' . $item->no_invoice . '"></td>
                            <td>' . ($item->no_do ?? '-') . '</td>
                            <td>' . ($item->marking ?? '-') . '</td>
                             <td>' . ($item->no_invoice ?? '-') . '</td>
                            <td>' . ($item->tanggal_bayar ?? '-') . '</td>
                            <td>' . ($item->pembeli ?? '-') . '</td>
                        </tr>
                    ';
        }
        $output .= '</tbody></table>';

        return $output;
    }


    public function getlistTableBuatPickup(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';

        $filterMarkingPickup = $request->marking ?? '';
        $filterNoDoPickup = $request->no_do ?? '';
        $companyId = session('active_company_id');

        if ($request->filter_date) {
            [$startDate, $endDate] = explode(' - ', $request->filter_date);
            $startDate = date('Y-m-d', strtotime($startDate));
            $endDate = date('Y-m-d', strtotime($endDate));
        } else {
            $startDate = null;
            $endDate = null;
        }

        $q = "SELECT a.id,
        a.no_invoice,
        DATE_FORMAT(a.tanggal_invoice, '%d %M %Y') AS tanggal_bayar,
        b.nama_pembeli AS pembeli,
        c.no_do,
        b.marking,
        a.metode_pengiriman,
        a.status_id
        FROM tbl_invoice AS a
        JOIN tbl_pembeli AS b ON a.pembeli_id = b.id
        JOIN tbl_status AS d ON a.status_id = d.id
        JOIN tbl_resi AS c ON a.id = c.invoice_id
        WHERE (
            UPPER(a.no_invoice) LIKE '$txSearch'
            OR UPPER(b.nama_pembeli) LIKE '$txSearch'
             OR UPPER(c.no_do) LIKE '$txSearch'
                OR UPPER(b.marking) LIKE '$txSearch'
        )
        AND a.status_id = 1
        AND a.metode_pengiriman = 'Pickup'
        GROUP BY a.id, a.no_invoice, a.tanggal_invoice, b.nama_pembeli, c.no_do, b.marking, a.metode_pengiriman, a.status_id";

        if ($startDate && $endDate) {
            $q .= " AND DATE(a.tanggal_invoice) BETWEEN '$startDate' AND '$endDate'";
        }

        if (!empty($filterMarkingPickup)) {
            $q .= " AND b.marking = '$filterMarkingPickup'";
        }

        if (!empty($filterNoDoPickup)) {
            $q .= " AND c.no_do = '$filterNoDoPickup'";
        }
        $data = DB::select($q);

        $output = '<table id="datatable_resi_pickup" class="table table-bordered table-hover">
                <thead>
                        <tr>
                            <th><input type="checkbox" id="select_all_pickup"></th>
                           <th>No Do</th>
                            <th>Marking</th>
                            <th>No Invoice</th>
                            <th>Tanggal</th>
                            <th>Customer</th>
                        </tr>
                </thead>
                <tbody>';
        foreach ($data as $item) {
            $output .=
                '
                       <tr>
                            <td><input type="checkbox" class="checkbox_resi_pickup" value="' . $item->no_invoice . '"></td>
                           <td>' . ($item->no_do ?? '-') . '</td>
                            <td>' . ($item->marking ?? '-') . '</td>
                             <td>' . ($item->no_invoice ?? '-') . '</td>
                            <td>' . ($item->tanggal_bayar ?? '-') . '</td>
                            <td>' . ($item->pembeli ?? '-') . '</td>
                        </tr>
                    ';
        }
        $output .= '</tbody></table>';
        return $output;
    }

    public function cekResi(Request $request)
    {
        $noResi = $request->input('no_invoice');


        $invoice = DB::table('tbl_invoice')
            ->where('no_invoice', $noResi)
            ->first();

        if ($invoice) {
            if ($invoice->status_id != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice sudah diproses',
                ]);
            }

            $pembeli = DB::table('tbl_pembeli')
                ->where('id', $invoice->pembeli_id)
                ->first();

            $status = DB::table('tbl_status')
                ->where('id', $invoice->status_id)
                ->first();

            if ($invoice && $status) {
                if ($invoice->metode_pengiriman === 'Delivery') {
                    return response()->json([
                        'success' => true,
                        'message' => 'Invoice untuk pengiriman Delivery',
                        'data' => [
                            'no_invoice' => $invoice->no_invoice,
                            'nama_pembeli' => $pembeli->nama_pembeli,
                            'status_name' => $status->status_name
                        ]
                    ]);
                } else {
                    return response()->json(['success' => false, 'message' => 'Resi tidak valid untuk Delivery']);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'Data pembeli atau status tidak ditemukan']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Resi tidak ditemukan']);
        }
    }

    public function cekResiBulk(Request $request)
    {
        $noResis = $request->input('no_invoices');
        $results = [];

        foreach ($noResis as $noResi) {
            $invoice = DB::table('tbl_invoice')
                ->where('no_invoice', $noResi)
                ->first();

            if ($invoice) {
                if ($invoice->status_id != 1) {
                    $results[] = [
                        'no_invoice' => $noResi,
                        'success' => false,
                        'message' => 'Invoice sudah diproses'
                    ];
                    continue; // Skip ke iterasi berikutnya
                }

                $pembeli = DB::table('tbl_pembeli')
                    ->where('id', $invoice->pembeli_id)
                    ->first();

                $status = DB::table('tbl_status')
                    ->where('id', $invoice->status_id)
                    ->first();



                if ($invoice && $status) {
                    if ($invoice->metode_pengiriman === 'Delivery') {
                        $results[] = [
                            'no_invoice' => $invoice->no_invoice,
                            'success' => true,
                            'message' => 'Invoice untuk pengiriman Delivery',
                            'data' => [
                                'no_invoice' => $invoice->no_invoice,
                                'nama_pembeli' => $pembeli->nama_pembeli,
                                'status_name' => $status->status_name
                            ]
                        ];
                    } else {
                        $results[] = [
                            'no_invoice' => $noResi,
                            'success' => false,
                            'message' => 'Resi tidak valid untuk Delivery'
                        ];
                    }
                } else {
                    $results[] = [
                        'no_invoice' => $noResi,
                        'success' => false,
                        'message' => 'Data pembeli atau status tidak ditemukan'
                    ];
                }
            } else {
                $results[] = [
                    'no_invoice' => $noResi,
                    'success' => false,
                    'message' => 'Resi tidak ditemukan'
                ];
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Proses selesai',
            'data' => array_filter($results, function ($result) {
                return $result['success'] === true;
            })
        ]);
    }

    public function cekResiPickup(Request $request)
    {
        $noResi = $request->input('no_invoice');

        $invoice = DB::table('tbl_invoice')
            ->where('no_invoice', $noResi)
            ->first();

        if ($invoice) {

            if ($invoice->status_id != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resi ditemukan, tetapi status tidak valid untuk diproses',
                ]);
            }

            $pembeli = DB::table('tbl_pembeli')
                ->where('id', $invoice->pembeli_id)
                ->first();

            $status = DB::table('tbl_status')
                ->where('id', $invoice->status_id)
                ->first();

            if ($invoice && $status) {
                if ($invoice->metode_pengiriman === 'Pickup') {
                    return response()->json([
                        'success' => true,
                        'message' => 'Resi valid untuk pengiriman Pickup',
                        'data' => [
                            'no_invoice' => $invoice->no_invoice,
                            'nama_pembeli' => $pembeli->nama_pembeli,
                            'status_name' => $status->status_name
                        ]
                    ]);
                } else {
                    return response()->json(['success' => false, 'message' => 'Metode pengiriman tidak valid']);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'Data pembeli atau status tidak ditemukan']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Resi tidak ditemukan']);
        }
    }

    public function cekResiBulkPickup(Request $request)
    {
        $noResis = $request->input('no_invoices');
        $results = [];

        foreach ($noResis as $noResi) {
            $invoice = DB::table('tbl_invoice')
                ->where('no_invoice', $noResi)
                ->first();

            if ($invoice) {
                if ($invoice->status_id != 1) {
                    $results[] = [
                        'no_invoice' => $noResi,
                        'success' => false,
                        'message' => 'Invoice sudah diproses'
                    ];
                    continue;
                }

                $pembeli = DB::table('tbl_pembeli')
                    ->where('id', $invoice->pembeli_id)
                    ->first();

                $status = DB::table('tbl_status')
                    ->where('id', $invoice->status_id)
                    ->first();

                if ($invoice && $status) {
                    if ($invoice->metode_pengiriman === 'Pickup') {
                        $results[] = [
                            'no_invoice' => $invoice->no_invoice,
                            'success' => true,
                            'message' => 'Invoice untuk pengiriman Pick-Up',
                            'data' => [
                                'no_invoice' => $invoice->no_invoice,
                                'nama_pembeli' => $pembeli->nama_pembeli,
                                'status_name' => $status->status_name
                            ]
                        ];
                    } else {
                        $results[] = [
                            'no_invoice' => $noResi,
                            'success' => false,
                            'message' => 'Resi tidak valid untuk Pick-Up'
                        ];
                    }
                } else {
                    $results[] = [
                        'no_invoice' => $noResi,
                        'success' => false,
                        'message' => 'Data pembeli atau status tidak ditemukan'
                    ];
                }
            } else {
                $results[] = [
                    'no_invoice' => $noResi,
                    'success' => false,
                    'message' => 'Resi tidak ditemukan'
                ];
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Proses selesai',
            'data' => array_filter($results, function ($result) {
                return $result['success'] === true;
            })
        ]);
    }


    public function buatDelivery(Request $request)
    {
        $resiList = $request->input('resi_list');
        $tanggalPickup = $request->input('tanggal');
        $driverId = $request->input('driver_id');
        $companyId = session('active_company_id');

        $date = DateTime::createFromFormat('j F Y', $tanggalPickup);
        $formattedDate = $date ? $date->format('Y-m-d') : null;

        if (!$resiList || count($resiList) == 0) {
            return response()->json(['success' => false, 'message' => 'Tidak ada resi yang diterima']);
        }

        if (!$tanggalPickup) {
            return response()->json(['success' => false, 'message' => 'Tanggal delivery tidak boleh kosong']);
        }

        if (!$driverId) {
            return response()->json(['success' => false, 'message' => 'Driver tidak dipilih']);
        }

        DB::beginTransaction();
        try {
            $pengantaranId = DB::table('tbl_pengantaran')->insertGetId([
                'metode_pengiriman' => 'Delivery',
                'supir_id' => $driverId,
                'tanggal_pengantaran' => $formattedDate,
                'company_id' => $companyId,
                // 'status_id' => 4,
                'created_at' => now(),
            ]);

            foreach ($resiList as $noInvoice) {
                $invoice = DB::table('tbl_invoice')->where('no_invoice', $noInvoice)->first();

                if ($invoice) {
                    DB::table('tbl_pengantaran_detail')->insert([
                        'pengantaran_id' => $pengantaranId,
                        'invoice_id' => $invoice->id,
                        'created_at' => now(),
                    ]);

                    $resiList = DB::table('tbl_resi')->where('invoice_id', $invoice->id)->pluck('no_resi');

                    foreach ($resiList as $noResi) {
                        $updatedTracking = DB::table('tbl_tracking')
                            ->where('no_resi', $noResi)
                            ->update(['status' => 'Delivering']);

                        if (!$updatedTracking) {
                            throw new \Exception("Gagal memperbarui status di tbl_tracking untuk resi: " . $noResi);
                        }
                    }

                    $updateInvoice = DB::table('tbl_invoice')
                        ->where('no_invoice', $noInvoice)
                        ->update(['status_id' => 4]);

                    if (!$updateInvoice) {
                        throw new \Exception("Gagal memperbarui status di tbl_invoice untuk no_invoice: " . $noInvoice);
                    }
                } else {
                    throw new \Exception("Invoice tidak ditemukan untuk no_invoice: " . $noInvoice);
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Delivery berhasil dibuat!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function buatPickup(Request $request)
    {
        $resiList = $request->input('resi_list');
        $tanggalPickup = $request->input('tanggal');
        $companyId = session('active_company_id');

        $date = DateTime::createFromFormat('j F Y', $tanggalPickup);
        $formattedDate = $date ? $date->format('Y-m-d') : null;

        if (!$resiList || count($resiList) == 0) {
            return response()->json(['success' => false, 'message' => 'Tidak ada resi yang diterima']);
        }

        if (!$tanggalPickup) {
            return response()->json(['success' => false, 'message' => 'Tanggal pickup tidak boleh kosong']);
        }

        DB::beginTransaction();
        try {
            $pengantaranId = DB::table('tbl_pengantaran')->insertGetId([
                'metode_pengiriman' => 'Pickup',
                'tanggal_pengantaran' => $formattedDate,
                'company_id' => $companyId,
                // 'status_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($resiList as $noInvoice) {

                $invoice = DB::table('tbl_invoice')->where('no_invoice', $noInvoice)->first();

                if ($invoice) {
                    DB::table('tbl_pengantaran_detail')->insert([
                        'pengantaran_id' => $pengantaranId,
                        'invoice_id' => $invoice->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $resiList = DB::table('tbl_resi')->where('invoice_id', $invoice->id)->pluck('no_resi');

                    foreach ($resiList as $noResi) {
                        $updatedTracking = DB::table('tbl_tracking')
                            ->where('no_resi', $noResi)
                            ->update(['status' => 'Ready For Pickup']);

                        if (!$updatedTracking) {
                            throw new \Exception("Gagal memperbarui status di tbl_tracking untuk resi: " . $noResi);
                        }
                    }

                    $updateInvoice = DB::table('tbl_invoice')
                        ->where('no_invoice', $noInvoice)
                        ->update(['status_id' => 2]);

                    if (!$updateInvoice) {
                        throw new \Exception("Gagal memperbarui status di tbl_invoice untuk no_invoice: " . $noInvoice);
                    }
                } else {
                    throw new \Exception("Invoice tidak ditemukan untuk no_invoice: " . $noInvoice);
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Pickup berhasil dibuat!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function acceptPengantaran(Request $request)
    {
        $idpengantaran = $request->input('id');
        DB::beginTransaction();
        try {
            $result = DB::table('tbl_pengantaran')
                ->select('pembayaran_id')
                ->where('id', $idpengantaran)
                ->first();

            if (!$result) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pengantaran tidak ditemukan.'
                ], 404);
            }
            $pembayaranId = $result->pembayaran_id;

            DB::table('tbl_pembayaran')->where('id', $pembayaranId)->update(['status_id' => 4]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Status pengantaran berhasil diperbarui.'
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui status pengantaran.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function confirmasiPengantaran(Request $request)
    {
        $idpengantaran = $request->input('id');
        $file = $request->file('file');
        DB::beginTransaction();
        try {
            $result = DB::table('tbl_pengantaran')
                ->where('id', $idpengantaran)
                ->first();

            if (!$result) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pengantaran tidak ditemukan.'
                ], 404);
            }


            if ($file) {
                try {
                    $fileName = $file->getClientOriginalName();
                    $filePath = $file->storeAs('public/bukti_pengantaran', $fileName);

                    DB::table('tbl_pengantaran')->where('id', $idpengantaran)->update(['bukti_pengantaran' => $fileName]);
                } catch (\Exception $e) {
                    return response()->json(['error' => true, 'message' => 'File upload or database update failed.'], 500);
                }
            } else {
                return response()->json(['error' => true, 'message' => 'File not uploaded.'], 400);
            }

            // DB::table('tbl_pembayaran')->where('id', $pembayaranId)->update(['status_id' => 6]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Status pengantaran berhasil diperbarui.'
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui status pengantaran.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function detailBuktiPengantaran(Request $request)
    {
        $tester = $request->input('namafoto');

        try {
            $filePath = 'public/bukti_pengantaran/' . $tester;

            if (!Storage::exists($filePath)) {
                return response()->json(['status' => 'error', 'message' => 'File tidak ditemukan'], 404);
            }
            $url = Storage::url($filePath);
            return response()->json(['status' => 'success', 'url' => $url], 200);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }


    public function exportPDF(Request $request)
    {
        $id = $request->input('id');

        try {
            $pengantaran = DB::table('tbl_pengantaran as a')
                ->select(
                    'a.id as pengantaran_id',
                    'a.supir_id',
                    'b.nama_supir',
                    'a.metode_pengiriman',
                    DB::raw("DATE_FORMAT(a.tanggal_pengantaran, '%d %M %Y') as tanggal_pengantaran"),
                    DB::raw("GROUP_CONCAT(c.invoice_id ORDER BY c.invoice_id ASC SEPARATOR ', ') as list_invoice")
                )
                ->leftJoin('tbl_supir as b', 'a.supir_id', '=', 'b.id')
                ->join('tbl_pengantaran_detail as c', 'a.id', '=', 'c.pengantaran_id')
                ->where('a.id', $id)
                ->groupBy('a.id', 'a.supir_id', 'b.nama_supir', 'a.metode_pengiriman', 'a.tanggal_pengantaran')
                ->first();

            if (!$pengantaran) {
                return response()->json(['error' => 'Pengantaran tidak ditemukan.'], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching pengantaran data: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Failed to fetch pengantaran data'], 500);
        }

        try {
            $invoiceIds = explode(', ', $pengantaran->list_invoice);
            $invoices = DB::table('tbl_invoice as i')
                ->select('i.id','i.no_invoice', 'i.alamat', 'p.nama_pembeli', 'p.marking')
                ->join('tbl_pembeli as p', 'i.pembeli_id', '=', 'p.id')
                ->whereIn('i.id', $invoiceIds)
                ->orderBy('p.marking', 'asc')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching invoice data: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Failed to fetch invoice data'], 500);
        }

        try {
            $invoiceResi = DB::table('tbl_resi as r')
                ->select(
                    'r.invoice_id',
                    'r.no_resi',
                    'r.no_do',
                    'r.berat',
                    'r.panjang',
                    'r.lebar',
                    'r.tinggi'
                )
                ->whereIn('r.invoice_id', $invoiceIds)
                ->orderBy('r.invoice_id')
                ->orderBy('r.no_resi')
                ->get()
                ->groupBy('invoice_id');


        } catch (\Exception $e) {
            Log::error('Error fetching resi data: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Failed to fetch resi data'], 500);
        }

        try {
            $pdf = Pdf::loadView('exportPDF.deliverylist', [
                'pengantaran' => $pengantaran,
                'invoices' => $invoices,
                'invoiceResi' => $invoiceResi,
            ])
                ->setPaper('A4', 'portrait')
                ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                ->setWarnings(false);
        } catch (\Exception $e) {
            Log::error('Error generating PDF: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Failed to generate PDF'], 500);
        }

        try {
            // Cek dan buat folder jika tidak ada
            $folderPath = storage_path('app/public/delivery');
            if (!File::exists($folderPath)) {
                File::makeDirectory($folderPath, 0755, true);
            }

            $fileName = 'Delivery' . (string) Str::uuid() . '.pdf';
            $filePath = $folderPath . '/' . $fileName;
            $pdf->save($filePath);
        } catch (\Exception $e) {
            Log::error('Error saving PDF: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Failed to save PDF'], 500);
        }

        try {
            $url = asset('storage/delivery/' . $fileName);
            return response()->json(['url' => $url]);
        } catch (\Exception $e) {
            Log::error('Error sending PDF URL: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Failed to send PDF URL'], 500);
        }
    }


    public function updateStatus(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'admin_signature' => 'nullable|mimes:jpg,jpeg,png',
            'customer_signature' => 'nullable|mimes:jpg,jpeg,png',
            'photo' => 'nullable|mimes:jpg,jpeg,png',
            'selectedPayment' => 'required'
        ]);

        $verifiedUsername = $request->input('verified_username');

        DB::beginTransaction();

        try {
            $invoiceIds = explode(',', $request->input('selectedValues'));

            foreach ($invoiceIds as $invoiceId) {
                // Ambil nomor invoice
                $noInvoice = DB::table('tbl_invoice')->where('id', $invoiceId)->value('no_invoice');
                if (!$noInvoice) {
                    throw new \Exception("No invoice found for invoice_id {$invoiceId}");
                }

                // Inisialisasi path untuk admin_signature dan photo
                $adminSignaturePath = null;
                $photoPath = null;

                // Proses admin signature
                if ($request->hasFile('admin_signature')) {
                    $adminSignatureFile = $request->file('admin_signature');
                    $adminSignatureFilename = time() . '_admin_signature_' . $noInvoice . '.' . $adminSignatureFile->getClientOriginalExtension();
                    $adminSignaturePath = $adminSignatureFile->storeAs('ttd_pengantaran', $adminSignatureFilename, 'public');
                }

                // Proses photo
                if ($request->hasFile('photo')) {
                    $photoFile = $request->file('photo');
                    $photoFilename = time() . '_photo_' . $noInvoice . '.' . $photoFile->getClientOriginalExtension();
                    $photoPath = $photoFile->storeAs('ttd_pengantaran', $photoFilename, 'public');
                }

                // Gabungkan admin_signature dengan photo (gunakan | sebagai pemisah)
                $finalAdminSignaturePath = $adminSignaturePath;
                if ($photoPath) {
                    $finalAdminSignaturePath = $adminSignaturePath ? "$adminSignaturePath|$photoPath" : $photoPath;
                }

                // Proses customer signature
                $customerSignaturePath = null;
                if ($request->hasFile('customer_signature')) {
                    $customerSignatureFile = $request->file('customer_signature');
                    $customerSignatureFilename = time() . '_customer_signature_' . $noInvoice . '.' . $customerSignatureFile->getClientOriginalExtension();
                    $customerSignaturePath = $customerSignatureFile->storeAs('ttd_pengantaran', $customerSignatureFilename, 'public');
                }

                // Update ke database
                DB::table('tbl_pengantaran_detail')
                    ->where('invoice_id', $invoiceId)
                    ->update([
                        'bukti_pengantaran' => $finalAdminSignaturePath,
                        'tanda_tangan' => $customerSignaturePath,
                        'keterangan' => "Barang Telah Selesai Di Pickup Costumer. Invoice di proses oleh admin: $verifiedUsername",
                        'updated_at' => now(),
                        'tanggal_penerimaan' => now(),
                        'createby' => $verifiedUsername,
                    ]);

                // Cek apakah semua invoice telah memiliki bukti_pengantaran atau tanda_tangan
                $pengantaranDetails = DB::table('tbl_pengantaran_detail')->where('invoice_id', $invoiceId)->get();
                $allCompleted = $pengantaranDetails->every(fn($detail) => !empty($detail->bukti_pengantaran) || !empty($detail->tanda_tangan));

                if ($allCompleted) {
                    DB::table('tbl_invoice')->where('id', $invoiceId)->update([
                        'status_id' => 6,
                        'payment_type' => $request->input('selectedPayment'),
                        'updated_at' => now(),
                    ]);

                    $pengantaranId = DB::table('tbl_pengantaran_detail')->where('invoice_id', $invoiceId)->value('pengantaran_id');
                    if ($pengantaranId) {
                        DB::table('tbl_pengantaran')->where('id', $pengantaranId)->update([
                            'updated_at' => now(),
                        ]);
                    }
                }

                // Update status tracking
                $noResiList = DB::table('tbl_resi')->where('invoice_id', $invoiceId)->pluck('no_resi');
                if ($noResiList->isNotEmpty()) {
                    DB::table('tbl_tracking')->whereIn('no_resi', $noResiList)->update([
                        'status' => 'Received',
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Data berhasil diupdate.'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error processing updateStatus: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengupdate data.'], 500);
        }
    }


}
