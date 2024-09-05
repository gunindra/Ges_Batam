<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SupplierInvoiceController extends Controller
{
    public function index()
    {
      
        return view('vendor.supplierinvoice.indexsupplierInvoice');
    }
    public function getlistSupplierInvoice(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';

        $q = "SELECT
                    a.no_resi
                FROM tbl_invoice AS a 
        ";

        // dd($q);

        $data = DB::select($q);

        $output = '  <table class="table align-items-center table-flush table-hover" id="tableSupplierInvoice">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No Resi</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>';
        foreach ($data as $item) {

    

            $output .=
                '
                <tr>
                    <td class="">' . ($item->no_resi ?? '-') .'</td>

                   <td>
                        <a  class="btn btnUpdateSupplierInvoice btn-sm btn-secondary text-white" data-id="' .$item->id.'" data-judul_service="' .$item->judul_service.'"data-isi_service="' .$item->isi_service.'" data-image_service="' .$item->image_service.'"><i class="fas fa-edit"></i></a>
                        <a  class="btn btnDestroySupplierInvoice btn-sm btn-danger text-white" data-id="' .$item->id.'" ><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            ';
        }

        $output .= '</tbody></table>';
         return $output;
    }
}