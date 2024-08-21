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
                        <a  class="btn btnUpdateRekening btn-sm btn-secondary text-white" data-id="' .$item->id .'" data-pemilik="' .$item->pemilik .'" data-nomer_rekening="' .$item->nomer_rekening .'" data-nama_bank="' .$item->nama_bank .'"><i class="fas fa-edit"></i></a>
                    </td>
                </tr>
            ';
        }

        $output .= '</tbody></table>';
         return $output;
    }

    public function addRekening(Request $request)
    {

        $namaRekening = $request->input('namaRekening');
        $noRekening = $request->input('noRekening');
        $bankRekening = $request->input('bankRekening');

        try {
            DB::table('tbl_rekening')->insert([
                'pemilik' => $namaRekening,
                'nomer_rekening' => $noRekening,
                'nama_bank' => $bankRekening,
                'created_at' => now(),
            ]);

            return response()->json(['status' => 'success', 'message' => 'Rekening berhasil ditambahkan'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menambahkan Rekening: ' . $e->getMessage()], 500);
        }
    }

    public function updateRekening(Request $request)
    {
        $id = $request->input('id');
        $namaRekening = $request->input('namaRekening');
        $noRekening = $request->input('noRekening');
        $bankRekening = $request->input('bankRekening');

        try {
            DB::table('tbl_rekening')
            ->where('id', $id)
            ->update([
                'pemilik' => $namaRekening,
                'nomer_rekening' => $noRekening,
                'nama_bank' => $bankRekening,
                'updated_at' => now(),
            ]);

            return response()->json(['status' => 'success', 'message' => 'Data Rekening berhasil diupdate'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal Mengupdate Data Rekening: ' . $e->getMessage()], 500);
        }
    }
}
