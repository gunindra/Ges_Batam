<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeliveryController extends Controller
{
    public function index()
    {


        return view('delivery.indexdelivery');
    }

    public function getlistDelivery(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';
        $filter = $request->filter;
        $status = $request->status; // Terima filter status dari request

        if (!$filter) {
            $formattedFilter = date('Y-m');
        } else {
            $formattedFilter = date_create_from_format("M Y", $filter)->format("Y-m");
        }

        $lastDayOfMonth = date("t", strtotime($formattedFilter . "-01"));

        $q = "SELECT
                    a.id,
                    b.no_resi,
                    DATE_FORMAT(a.tanggal_pengantaran, '%d %M %Y') AS tanggal_pengantaran,
                    c.nama_supir,
                    a.alamat,
                    a.bukti_pengantaran,
                    d.status_name,
                    CONCAT_WS(', ', a.kelurahan, a.kecamatan, a.kotakab, a.provinsi) AS full_address
                FROM
                    tbl_pengantaran AS a
                JOIN
                    tbl_pembayaran AS b ON a.pembayaran_id = b.id
                JOIN
                    tbl_supir AS c ON a.supir_id = c.id
                JOIN
                    tbl_status AS d ON b.status_id = d.id
                WHERE (
                    UPPER(c.nama_supir) LIKE UPPER('$txSearch')
                    OR UPPER(b.no_resi) LIKE UPPER('$txSearch')
                    OR UPPER(a.alamat) LIKE UPPER('$txSearch')
                    OR UPPER(d.status_name) LIKE UPPER('$txSearch')
                )
                AND a.tanggal_pengantaran BETWEEN '" . $formattedFilter . "-01' AND '" . $formattedFilter . "-" . $lastDayOfMonth . "'";

            if (!empty($status)) {
            $q .= " AND d.status_name = '" . strtoupper($status) . "'";
            }

            $q .= " LIMIT 100";

        $data = DB::select($q);

        $output = '<table class="table align-items-center table-flush table-hover" id="tableDelivery">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No Resi</th>
                                        <th>Tanggal</th>
                                        <th>Driver</th>
                                        <th>Pengiriman</th>
                                        <th>Daerah</th>
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
                    $btnAcceptPengantaran = '<a class="btn btnAcceptPengantaran btn-warning text-white" data-id="' . $item->id . '"><i class="fas fa-truck-moving"></i></a>';
                    break;
                case 'Delivering':
                    $statusBadgeClass = 'badge-delivering';
                    $btnBuktiPengantaran = '<a class="btn btnBuktiPengantaran btn-success text-white" data-id="' . $item->id . '" ><i class="fas fa-camera"></i></a>';
                    break;
                case 'Debt':
                    $statusBadgeClass = 'badge-danger'; // Merah
                    break;
                case 'Done':
                    $statusBadgeClass = 'badge-secondary'; // Abu-abu
                    $btnDetailPengantaran = '<a class="btn btnDetailPengantaran btn-secondary text-white" data-id="' . $item->id . '" data-bukti="' . $item->bukti_pengantaran . '"><i class="fas fa-eye"></i></a>';
                    break;
                default:
                    $statusBadgeClass = 'badge-secondary'; // Default
                    break;
            }

            $output .= '
                <tr>
                    <td class="">' . ($item->no_resi ?? '-') . '</td>
                    <td class="">' . ($item->tanggal_pengantaran ?? '-') . '</td>
                    <td class="">' . ($item->nama_supir ?? '-') . '</td>
                    <td class="">' . ($item->alamat ?? '-') . '</td>
                    <td class="">' . ($item->full_address ?? '-') . '</td>
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

            DB::table('tbl_pembayaran')->where('id', $pembayaranId)->update(['status_id' => 6]);

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
            // Gunakan Storage untuk mendapatkan URL file
            $filePath = 'public/bukti_pengantaran/' . $tester;

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
}
