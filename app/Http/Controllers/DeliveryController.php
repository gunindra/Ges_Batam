<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use DateTime;
use App\Traits\WhatsappTrait;
use Log;

class DeliveryController extends Controller
{
    use WhatsappTrait;

    public function sendInvoiceNotification($noWa, $message)
    {
        $this->kirimPesanWhatsapp($noWa, $message);
    }

    public function index()
    {


        return view('customer.delivery.indexdelivery');
    }

    public function getlistDelivery(Request $request)
    {
        $status = strtoupper(trim($request->status));

        $startDate = $request->startDate ? date('Y-m-d', strtotime($request->startDate)) : null;
        $endDate = $request->endDate ? date('Y-m-d', strtotime($request->endDate)) : null;

        $query = DB::table('tbl_pengantaran as a')
            ->select(
                'a.id as pengantaran_id',
                'a.metode_pengiriman',
                'a.supir_id',
                'e.nama_supir',
                DB::raw("GROUP_CONCAT(b.no_resi SEPARATOR ', ') as list_no_resi"),
                DB::raw("GROUP_CONCAT(c.nama_pembeli SEPARATOR ', ') as list_nama_pembeli"),
                DB::raw("GROUP_CONCAT(IFNULL(b.alamat, 'Alamat Tidak Tersedia') SEPARATOR ', ') as list_alamat"),
                DB::raw("MAX(DATE_FORMAT(a.tanggal_pengantaran, '%d %M %Y')) AS tanggal_pengantaran"),
                's.status_name',
                DB::raw('COUNT(pd.id) as jumlah_invoice')
            )
            ->join('tbl_pengantaran_detail as pd', 'a.id', '=', 'pd.pengantaran_id')
            ->join('tbl_invoice as b', 'pd.invoice_id', '=', 'b.id')
            ->join('tbl_pembeli as c', 'b.pembeli_id', '=', 'c.id')
            ->join('tbl_status as s', 'a.status_id', '=', 's.id')
            ->leftjoin('tbl_supir as e', 'a.supir_id', '=', 'e.id')
            ->groupBy('a.id', 'a.supir_id', 'e.nama_supir', 's.status_name');


        if ($request->txSearch) {
            $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';
            $query->where(function ($q) use ($txSearch) {
                $q->where(DB::raw('UPPER(e.nama_supir)'), 'LIKE', $txSearch);
            });
        }

        if ($request->status) {
            $query->where('d.status_name', '=', $status);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('a.tanggal_pengantaran', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('a.tanggal_pengantaran', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('a.tanggal_pengantaran', '<=', $endDate);
        }

        $query->limit(100);

        $data = $query->get();

        $output = '<table class="table align-items-center table-flush table-hover" id="tableDelivery">
                        <thead class="thead-light">
                            <tr>
                                <th>Pengiriman</th>
                                <th>Supir</th>
                                <th>Jumlah Invoice</th>
                                <th>Tanggal Penganatran</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>';

        foreach ($data as $item) {
            $statusBadgeClass = '';
            $btnAcceptPengantaran = '';
            $btnBuktiPengantaran = '';
            $btnDetailPengantaran = '';

            switch ($item->status_name) {
                case 'Out For Delivery':
                    $statusBadgeClass = 'badge-out-for-delivery';
                    $btnAcceptPengantaran = '<a class="btn btnAcceptPengantaran btn-warning text-white" data-id="' . $item->pengantaran_id . '"><i class="fas fa-truck-moving"></i></a>';
                    break;
                case 'Ready For Pickup':
                    $statusBadgeClass = 'badge-warning';
                    break;
                case 'Delivering':
                    $statusBadgeClass = 'badge-delivering';
                    $btnBuktiPengantaran = '<a class="btn btnBuktiPengantaran btn-success text-white" data-id="' . $item->pengantaran_id . '" ><i class="fas fa-camera"></i></a>';
                    break;
                case 'Debt':
                    $statusBadgeClass = 'badge-danger';
                    break;
                case 'Done':
                    $statusBadgeClass = 'badge-secondary';
                    $btnDetailPengantaran = '<a class="btn btnDetailPengantaran btn-secondary text-white" data-id="' . $item->pengantaran_id . '" data-bukti="' . $item->bukti_pengantaran . '"><i class="fas fa-eye"></i></a>';
                    break;
                default:
                    $statusBadgeClass = 'badge-secondary';
                    break;
            }

            $btnInvoice = '
                <button type="button" class="btn btn-primary btn-sm show-invoice-modal"
                    data-supir="' . $item->nama_supir . '"
                    data-invoices="' . htmlentities($item->list_no_resi) . '"
                    data-customers="' . htmlentities($item->list_nama_pembeli) . '"
                    data-alamat="' . htmlentities($item->list_alamat) . '">
                    Invoice (' . $item->jumlah_invoice . ')
                </button>';

            $output .= '
                <tr>
                    <td>' . ($item->metode_pengiriman ?? '-') . '</td>
                    <td>' . ($item->nama_supir ?? '-') . '</td>
                    <td>' . $btnInvoice . '</td>
                    <td>' . ($item->tanggal_pengantaran ?? '-') . '</td>
                    <td><span class="badge ' . $statusBadgeClass . '">' . ($item->status_name ?? '-') . '</span></td>
                    <td>
                        ' . $btnAcceptPengantaran . '
                        ' . $btnDetailPengantaran . '
                        ' . $btnBuktiPengantaran . '
                    </td>
                </tr>';
        }

        $output .= '</tbody></table>';

        return $output;
    }


    public function addDelivery()
    {
        $listSopir = DB::select("SELECT id, nama_supir, no_wa FROM tbl_supir");

        return view('customer.delivery.buatdelivery', [
            'listSupir' => $listSopir,
        ]);
    }

    public function getlistTableBuatDelivery (Request $request)
    {
        $filterDate = $request->filter_date ? date('Y-m-d', strtotime($request->filter_date)) : null;

        $q = "SELECT a.id,
                                a.no_resi,
                                DATE_FORMAT(a.tanggal_invoice, '%d %M %Y') AS tanggal_bayar,
                                b.nama_pembeli AS pembeli,
                                b.metode_pengiriman,
                                a.status_id
                        FROM tbl_invoice AS a
                        JOIN tbl_pembeli AS b ON a.pembeli_id = b.id
                        JOIN tbl_status AS d ON a.status_id = d.id
                        WHERE a.status_id = 1
                        AND b.metode_pengiriman = 'Delivery'
                        AND a.tanggal_invoice = '$filterDate'
                        ";


        $data = DB::select($q);

                $output = '<table id="datatable_resi" class="table table-bordered table-hover">
                <thead>
                        <tr>
                            <th><input type="checkbox" id="select_all"></th>
                            <th>No Resi</th>
                            <th>Tanggal</th>
                            <th>Customer</th>
                        </tr>
                </thead>
                <tbody>';
                foreach ($data as $item) {
                    $output .=
                        '
                       <tr>
                            <td><input type="checkbox" class="checkbox_resi" value="' . $item->no_resi . '"></td>
                            <td>' . ($item->no_resi ?? '-') . '</td>
                            <td>' . ($item->tanggal_bayar ?? '-') . '</td>
                            <td>' . ($item->pembeli ?? '-') . '</td>
                        </tr>
                    ';
                }
        $output .= '</tbody></table>';
        return $output;
    }
    public function getlistTableBuatPickup (Request $request)
    {
        $filterDate = $request->filter_date ? date('Y-m-d', strtotime($request->filter_date)) : null;

        $q = "SELECT a.id,
                                a.no_resi,
                                DATE_FORMAT(a.tanggal_invoice, '%d %M %Y') AS tanggal_bayar,
                                b.nama_pembeli AS pembeli,
                                b.metode_pengiriman,
                                a.status_id
                        FROM tbl_invoice AS a
                        JOIN tbl_pembeli AS b ON a.pembeli_id = b.id
                        JOIN tbl_status AS d ON a.status_id = d.id
                        WHERE a.status_id = 1
                        AND b.metode_pengiriman = 'Pickup'
                        AND a.tanggal_invoice = '$filterDate'
                        ";


        $data = DB::select($q);

                $output = '<table id="datatable_resi_pickup" class="table table-bordered table-hover">
                <thead>
                        <tr>
                            <th><input type="checkbox" id="select_all"></th>
                            <th>No Resi</th>
                            <th>Tanggal</th>
                            <th>Customer</th>
                        </tr>
                </thead>
                <tbody>';
                foreach ($data as $item) {
                    $output .=
                        '
                       <tr>
                            <td><input type="checkbox" class="checkbox_resi" value="' . $item->no_resi . '"></td>
                            <td>' . ($item->no_resi ?? '-') . '</td>
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
        $noResi = $request->input('no_resi');

        $invoice = DB::table('tbl_invoice')
            ->where('no_resi', $noResi)
            ->first();

        if ($invoice) {
            if ($invoice->status_id != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resi sudah diproses',
                ]);
            }

            $pembeli = DB::table('tbl_pembeli')
                ->where('id', $invoice->pembeli_id)
                ->first();

            $status = DB::table('tbl_status')
                ->where('id', $invoice->status_id)
                ->first();

            if ($pembeli && $status) {
                if ($pembeli->metode_pengiriman === 'Delivery') {
                    return response()->json([
                        'success' => true,
                        'message' => 'Resi valid untuk pengiriman Delivery',
                        'data' => [
                            'no_resi' => $invoice->no_resi,
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



    public function cekResiPickup(Request $request)
    {
        $noResi = $request->input('no_resi');

        $invoice = DB::table('tbl_invoice')
            ->where('no_resi', $noResi)
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

            if ($pembeli && $status) {
                if ($pembeli->metode_pengiriman === 'Pickup') {
                    return response()->json([
                        'success' => true,
                        'message' => 'Resi valid untuk pengiriman Pickup',
                        'data' => [
                            'no_resi' => $invoice->no_resi,
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





    public function buatDelivery(Request $request)
    {
        $resiList = $request->input('resi_list');
        $tanggalPickup = $request->input('tanggal');
        $driverId = $request->input('driver_id');

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
                'status_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Array untuk menyimpan nomor resi berdasarkan pembeli
            $resiPerPembeli = [];

            foreach ($resiList as $noResi) {
                $invoice = DB::table('tbl_invoice')->where('no_resi', $noResi)->first();

                if ($invoice) {
                    // Tambahkan ke tbl_pengantaran_detail
                    DB::table('tbl_pengantaran_detail')->insert([
                        'pengantaran_id' => $pengantaranId,
                        'invoice_id' => $invoice->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Perbarui status di tbl_tracking
                    $updatedTracking = DB::table('tbl_tracking')
                        ->where('no_resi', $noResi)
                        ->update(['status' => 'Delivering..']);

                    if (!$updatedTracking) {
                        throw new \Exception("Gagal memperbarui status di tbl_tracking");
                    }

                    // Perbarui status di tbl_invoice
                    $updateInvoice = DB::table('tbl_invoice')
                        ->where('no_resi', $noResi)
                        ->update(['status_id' => 4]);

                    if (!$updateInvoice) {
                        throw new \Exception("Gagal memperbarui status di tbl_invoice");
                    }

                    // Kumpulkan nomor resi berdasarkan pembeli
                    $pembeliId = $invoice->pembeli_id;
                    $pembeli = DB::table('tbl_pembeli')->where('id', $pembeliId)->first();

                    if ($pembeli) {
                        if (!isset($resiPerPembeli[$pembeliId])) {
                            $resiPerPembeli[$pembeliId] = [
                                'no_wa' => $pembeli->no_wa,
                                'resi' => [],
                            ];
                        }
                        // Tambahkan nomor resi ke daftar resi pembeli
                        $resiPerPembeli[$pembeliId]['resi'][] = $noResi;
                    }
                } else {
                    throw new \Exception("Invoice tidak ditemukan untuk resi: " . $noResi);
                }
            }

            foreach ($resiPerPembeli as $pembeliId => $dataPembeli) {
                if ($dataPembeli['no_wa']) {
                    $pesanResi = implode(", ", $dataPembeli['resi']);
                    $pesan = "Pengiriman untuk resi berikut sedang dalam proses pengantaran: " . $pesanResi;

                    $this->kirimPesanWhatsapp($dataPembeli['no_wa'], $pesan);
                } else {
                    // Jika nomor WhatsApp kosong atau tidak ditemukan
                    Log::warning("Nomor WhatsApp tidak ditemukan untuk pembeli dengan ID: " . $pembeliId);
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
                'status_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Array untuk menyimpan nomor resi berdasarkan pembeli
            $resiPerPembeli = [];

            foreach ($resiList as $noResi) {
                $invoice = DB::table('tbl_invoice')->where('no_resi', $noResi)->first();

                if ($invoice) {
                    // Tambahkan ke tbl_pengantaran_detail
                    DB::table('tbl_pengantaran_detail')->insert([
                        'pengantaran_id' => $pengantaranId,
                        'invoice_id' => $invoice->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Perbarui status di tbl_tracking
                    $updatedTracking = DB::table('tbl_tracking')
                        ->where('no_resi', $noResi)
                        ->update(['status' => 'Ready For Pickup']);

                    if (!$updatedTracking) {
                        throw new \Exception("Gagal memperbarui status di tbl_tracking");
                    }

                    // Perbarui status di tbl_invoice
                    $updateInvoice = DB::table('tbl_invoice')
                        ->where('no_resi', $noResi)
                        ->update(['status_id' => 2]);

                    if (!$updateInvoice) {
                        throw new \Exception("Gagal memperbarui status di tbl_invoice");
                    }

                    // Kumpulkan nomor resi berdasarkan pembeli
                    $pembeliId = $invoice->pembeli_id;
                    $pembeli = DB::table('tbl_pembeli')->where('id', $pembeliId)->first();

                    if ($pembeli) {
                        if (!isset($resiPerPembeli[$pembeliId])) {
                            $resiPerPembeli[$pembeliId] = [
                                'no_wa' => $pembeli->no_wa,
                                'resi' => [],
                            ];
                        }
                        $resiPerPembeli[$pembeliId]['resi'][] = $noResi;
                    }
                } else {
                    throw new \Exception("Invoice tidak ditemukan untuk resi: " . $noResi);
                }
            }

            // Setelah semua nomor resi terkumpul, kirim pesan WhatsApp sekali untuk setiap pembeli
            foreach ($resiPerPembeli as $pembeliId => $dataPembeli) {
                if ($dataPembeli['no_wa']) {
                    $pesanResi = implode(", ", $dataPembeli['resi']);
                    $pesan = "Pengiriman untuk resi berikut telah siap untuk di pickup: " . $pesanResi;

                    // Panggil fungsi kirim pesan
                    $this->kirimPesanWhatsapp($dataPembeli['no_wa'], $pesan);
                } else {
                    // Jika nomor WhatsApp kosong atau tidak ditemukan
                    Log::warning("Nomor WhatsApp tidak ditemukan untuk pembeli dengan ID: " . $pembeliId);
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

            return response()->json([
                'status' => 'success',
                'message' => 'Status pengantaran berhasil diperbarui.'
            ], 200);

        } catch (\Exception $e) {
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

            return response()->json([
                'status' => 'success',
                'message' => 'Status pengantaran berhasil diperbarui.'
            ], 200);

        } catch (\Exception $e) {
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





    // public function kirimPesanWhatsapp($noWa, $message)
    // {
    //     $response = Http::post('https://wa.aplikasiajp.com/send-message', [
    //         'api_key' => 'qpWaNfN8vSQ7I8m1JiqzqfyyLWG9uT',
    //         'sender' => '6285183058668',
    //         'number' => $noWa,
    //         'message' => $message
    //     ]);
    //     if ($response->successful()) {
    //         return true;
    //     } else {

    //         $errorMessage = $response->json()['msg'] ?? 'Gagal mengirim pesan WhatsApp';
    //         throw new \Exception($errorMessage);
    //     }
    // }
}
