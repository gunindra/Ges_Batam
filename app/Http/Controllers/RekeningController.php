<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekeningController extends Controller
{
    public function index() {


        return view('masterdata.rekening.indexmasterrekening');
    }

    public function getlistRekening(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';

        $q = "SELECT id,
                        pemilik,
                        nomer_rekening,
                        nama_bank
                FROM tbl_rekening
        ";

        // dd($q);

        $data = DB::select($q);

        $output = ' <table class="table align-items-center table-flush table-hover" id="tableRekening">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Pemilik</th>
                                        <th>No. Rekening</th>
                                        <th>Bank</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>';
        foreach ($data as $item) {
            $output .=
                '
                <tr>
                    <td class="">' . ($item->pemilik ?? '-') .'</td>
                    <td class="">' . ($item->nomer_rekening ?? '-') .'</td>
                    <td class="">' . ($item->nama_bank ?? '-') .'</td>
                   <td>
                        <a  class="btn btnUpdateCustomer btn-sm btn-secondary text-white" data-id="' .$item->id .'" data-pemilik="' .$item->pemilik .'" data-nomer_rekening="' .$item->nomer_rekening .'" data-nama_bank="' .$item->nama_bank .'"><i class="fas fa-edit"></i></a>
                        <a  class="btn btnDestroyCustomer btn-sm btn-danger text-white" data-id="' .$item->id .'" ><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            ';
        }

        $output .= '</tbody></table>';
         return $output;
    }

    public function addRekening(Request $request)
    {

        $namacostumer = $request->input('namaCostmer');
        $alamatcostumer = $request->input('alamatCustomer');
        $notlponcostumer = $request->input('noTelpon');
        $categorycostumer = $request->input('CategoryCustomer');


        // dd($namacostumer, $alamatcostumer, $notlponcostumer, $categorycostumer);

        try {
            DB::table('tbl_pembeli')->insert([
                'nama_pembeli' => $namacostumer,
                'no_wa' => $notlponcostumer,
                'alamat' => $alamatcostumer,
                'category' => $categorycostumer,
                'created_at' => now(),
            ]);

            // Mengembalikan respons JSON jika berhasil
            return response()->json(['status' => 'success', 'message' => 'Pelanggan berhasil ditambahkan'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menambahkan pelanggan: ' . $e->getMessage()], 500);
        }
    }

    public function updateRekening(Request $request)
    {

    }

    public function destroyRekening(Request $request)
    {

    }
}
