<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryController extends Controller
{
    public function index()
    {


        return view('delivery.indexdelivery');
    }

    public function getlistDelivery(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';

        $q = "SELECT
                    a.id,
                    b.no_resi,
                    DATE_FORMAT(a.tanggal_pengantaran, '%d %M %Y') AS tanggal_pengantaran,
                    c.nama_supir,
                    a.alamat,
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
;
        ";

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
                                            $statusBadgeClass = 'badge-primary'; // Biru
                                            $btnAcceptPengantaran = '<a class="btn   btn-sm btn-danger text-white" data-id="' . $item->id . '"><i class="fas fa-truck-moving"></i></a>';
                                            break;
                                        case 'Delivering':
                                            $statusBadgeClass = 'badge-orange'; // Oranye
                                            $btnBuktiPengantaran = '<a class="btn btnBuktiPengantaran btn-sm btn-success text-white" data-id="' . $item->id . '" ><i class="fas fa-camera"></i></a>';
                                            break;
                                        case 'Debt':
                                            $statusBadgeClass = 'badge-danger'; // Merah
                                            break;
                                        case 'Done':
                                            $statusBadgeClass = 'badge-secondary'; // Abu-abu
                                            $btnDetailPengantaran = '<a class="btn btnDetailPengantaran btn-sm btn-secondary text-white" data-id="' . $item->id . '"><i class="fas fa-eye"></i></a>';
                                            break;
                                        default:
                                            $statusBadgeClass = 'badge-secondary'; // Default
                                            break;
                                    }


                                    $output .=
                                        '
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
                                        </tr>
                                    ';
                                }

                                $output .= '</tbody></table>';
                                return $output;

    }
}
