<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index()
    {


        return view('booking.indexbooking');
    }

    public function getlistBooking(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';

        $q = "SELECT
                    a.id,
                    a.kode_pemesanan,
                    DATE_FORMAT(a.tanggal_pemesanan, '%d %M %Y') AS tanggal_pemesanan,
                    b.nama_pembeli AS pembeli,
                    GROUP_CONCAT(d.nama_barang SEPARATOR ', ') AS nama_barang,
                    c.status_name AS status_name
                FROM
                    tbl_pemesanan a
                JOIN
                    tbl_pembeli b ON a.pembeli_id = b.id
                JOIN
                    tbl_status c ON a.status_id = c.id
                LEFT JOIN
                    tbl_itemdetail d ON d.pemesanan_id = a.id
                GROUP BY
                    a.id,
                    a.kode_pemesanan,
                    a.tanggal_pemesanan,
                    b.nama_pembeli,
                    c.status_name
        ";

        $data = DB::select($q);

        $output = ' <table class="table align-items-center table-flush table-hover" id="tableBooking">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Booking Code</th>
                                        <th>Booking Date</th>
                                        <th>Costumer</th>
                                        <th>Nama Barang</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>';
        foreach ($data as $item) {
            $output .=
                '
                <tr>
                    <td class="">' . ($item->kode_pemesanan ?? '-') .'</td>
                    <td class="">' . ($item->tanggal_pemesanan ?? '-') .'</td>
                    <td class="">' . ($item->pembeli ?? '-') .'</td>
                    <td class="">' . ($item->nama_barang ?? '-') .'</td>
                    <td><span class="badge badge-warning">' . ($item->status_name ?? '-') .'</span></td>
                   <td>
                        <a  class="btn btnUpdateBooking btn-sm btn-secondary text-white" data-id="' .$item->id .'" data-kode_pemesanan="' .$item->kode_pemesanan .'" data-tanggal_pemesanan="' .$item->tanggal_pemesanan .'" data-pembeli="' .$item->pembeli .'" data-nama_barang="' .$item->nama_barang .'"><i class="fas fa-edit"></i></a>
                        <a  class="btn btnDestroyBooking btn-sm btn-danger text-white" data-id="' .$item->id .'" ><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            ';
        }

        $output .= '</tbody></table>';
         return $output;
    }

    public function dataBookingForm(Request $request)
    {
        // Code Generate Booking Code
        $prefix = 'BC';

        $year = date('y');
        $month = date('m');
        $day = date('d');

        $datePart = $year . $month . $day;

        $lastBooking = DB::table('tbl_pemesanan')
                         ->where('kode_pemesanan', 'LIKE', $prefix . $datePart . '%')
                         ->orderBy('kode_pemesanan', 'desc')
                         ->first();

        if ($lastBooking) {
            $lastNumber = (int)substr($lastBooking->kode_pemesanan, -3);
            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '001';
        }

        $newBookingCode = $prefix . $datePart . $nextNumber;
        // End Generate booking Code

        // List Customer
        $listPembeli = DB::select("SELECT id, nama_pembeli FROM tbl_pembeli");

        return response()->json([
            'new_codebooking' => $newBookingCode,
            'list_pembeli' => $listPembeli
        ]);
    }
}
