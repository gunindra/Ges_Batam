<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PickupController extends Controller
{
    public function index()
    {


        return view('customer.pickup.indexpickup');
    }

    public function getlistPickup(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';
        $status = $request->status;
        $startDate = $request->startDate ? date('Y-m-d', strtotime($request->startDate)) : null;
        $endDate = $request->endDate ? date('Y-m-d', strtotime($request->endDate)) : null;

        $dateCondition = '';
        if ($startDate && $endDate) {
            $dateCondition = "AND a.tanggal_pembayaran BETWEEN '$startDate' AND '$endDate'";
        }

        $statusCondition = $status ? "AND tandrio.status_name LIKE '$status'" : "";

        $q = "SELECT a.id,
                     a.no_resi,
                     DATE_FORMAT(a.tanggal_pembayaran, '%d %M %Y') AS tanggal_bayar,
                     f.nama_pembeli AS customer,
                     tandrio.status_name AS status_name
              FROM tbl_pembayaran AS a
              JOIN tbl_pembeli AS f ON a.pembeli_id = f.id
              JOIN tbl_status AS tandrio ON a.status_id = tandrio.id
              WHERE a.pengiriman = 'Pickup'
              AND (
                  UPPER(a.no_resi) LIKE UPPER('$txSearch')
                  OR UPPER(f.nama_pembeli) LIKE UPPER('$txSearch')
              )
              $dateCondition
              $statusCondition
              ORDER BY CASE tandrio.status_name
                  WHEN 'Pending Payment' THEN 1
                  WHEN 'Ready For Pickup' THEN 2
                  ELSE 3
              END,
              a.id DESC;
        ";



        $data = DB::select($q);

        $output = '  <table class="table align-items-center table-flush table-hover" id="tablePickup">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No Resi</th>
                                        <th>Tanggal</th>
                                        <th>Customer</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>';
        foreach ($data as $item) {

            $statusBadgeClass = '';
            $btnbtnPickup = '-';
            switch ($item->status_name) {
                case 'Pending Payment':
                    $statusBadgeClass = 'badge-warning';
                    break;
                case 'Ready For Pickup':
                    $statusBadgeClass = 'badge-success';
                    $btnbtnPickup = ' <a class="btn btnPickup btn-sm btn-success text-white" data-id="' . $item->id . '"><i class="fas fa-check"></i></a>';
                    break;
                case 'Done':
                    $statusBadgeClass = 'badge-secondary';
                    break;
            }
            $output .=
                '
                <tr>
                    <td class="">' . ($item->no_resi ?? '-') . '</td>
                    <td class="">' . ($item->tanggal_bayar ?? '-') . '</td>
                    <td class="">' . ($item->customer ?? '-') . '</td>
                    <td><span class="badge ' . $statusBadgeClass . '">' . ($item->status_name ?? '-') . '</span></td>
                   <td>
                         ' . $btnbtnPickup . '
                    </td>
                </tr>
            ';
        }

        $output .= '</tbody></table>';
        return $output;
    }


    public function acceptPickup (Request $request)
    {

        $id = $request->input('id');

        try {
                DB::table('tbl_pembayaran')->where('id', $id)->update([
                    'status_id' => 6,
                ]);

        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to update payment status.'], 500);
        }
    }

}
