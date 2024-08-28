<?php

namespace App\Http\Controllers;

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

        $q = "SELECT
		a.no_resi , 
		a.tanggal_pembayaran , 
		f.nama_pembeli AS customer,
		tandrio.status_name AS status
		FROM tbl_pembayaran AS a 
		JOIN tbl_pembeli AS f ON a.pembeli_id = f.id
		JOIN tbl_status AS tandrio ON a.status_id = tandrio.id
        ";

        // dd($q);

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


            $output .=
                '
                <tr>
                    <td class="">' . ($item->no_resi ?? '-') . '</td>
                    <td class="">' . ($item->tanggal_pembayaran ?? '-') . '</td>
                    <td class="">' . ($item->nama_pembeli ?? '-') . '</td>
                    <td class="">' . ($item->status_name ?? '-') . '</td>
                   <td>
                        <a class="btn btnPickup btn-sm btn-success text-white" data-id="' . $item->id . '"><i class="fas fa-check"></i></a>
                    </td>
                </tr>
            ';
        }

        $output .= '</tbody></table>';
        return $output;
    }

}
