<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index(){


        return view('Tracking');
    }

    public function lacakResi(Request $request)
    {
        $noresi = $request->input('noresi');
        $q = "SELECT a.no_resi,
                    d.nama_pembeli,
                    b.tanggal_pengantaran,
                    c.nama_supir,
                    c.no_wa,
                    b.alamat,
                    CONCAT_WS(', ', b.kelurahan, b.kecamatan, b.kotakab, b.provinsi) AS full_address,
                    f.status_name
            FROM  tbl_pembayaran AS a
            LEFT JOIN tbl_pengantaran  AS b ON b.pembayaran_id = a.id
            LEFT JOIN tbl_supir AS c ON b.supir_id = c.id
            JOIN tbl_pembeli AS d ON a.pembeli_id = d.id
            JOIN tbl_status AS f ON a.status_id = f.id
            WHERE a.no_resi = '$noresi'
        ";

        $data = DB::select($q);

        return response()->json($data);
    }

}
