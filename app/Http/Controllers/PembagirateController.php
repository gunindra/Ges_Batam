<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembagirateController extends Controller
{

    public function index()
    {
        return view('masterdata.pembagirate.indexpembagirate');
    }
    public function getlistPembagi(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';

        $q = "SELECT id,
                        no_pembagi,
                        nilai_pembagi
                FROM tbl_pembagi
        ";

        // dd($q);

        $data = DB::select($q);

        $output = '  <table class="table align-items-center table-flush table-hover" id="tablePembagi">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nilai</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>';
        foreach ($data as $item) {
            $output .=
                '
                <tr>
                    <td class="">' . ($item->no_pembagi ?? '-') .'</td>
                    <td class="">' . ($item->nilai_pembagi ?? '-') .'</td>
                   <td>
                        <a  class="btn btnUpdatePembagi btn-sm btn-secondary text-white" data-id="' .$item->id.'" data-no_pembagi="' .$item->no_pembagi.'" data-nilai_pembagi="' .$item->nilai_pembagi.'"><i class="fas fa-edit"></i></a>
                        <a  class="btn btnDestroyPembagi btn-sm btn-danger text-white" data-id="' .$item->id.'" ><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            ';
        }

        $output .= '</tbody></table>';
         return $output;
    }
    public function addPembagi(Request $request)
    {

        $noPembagi = $request->input('noPembagi');
        $nilaiPembagi = $request->input('nilaiPembagi');

        try {
            DB::table('tbl_pembagi')->insert([
                'no_pembagi' => $noPembagi,
                'nilai_pembagi' => $nilaiPembagi,
                'created_at' => now(),
            ]);

            return response()->json(['status' => 'success', 'message' => 'berhasil ditambahkan'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menambahkan : ' . $e->getMessage()], 500);
        }
    }
    public function destroyPembagi(Request $request)
    {
        $id = $request->input('id');

        try {
            DB::table('tbl_pembagi')
                ->where('id', $id)
                ->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function updatePembagi(Request $request)
    {
        $id = $request->input('id');
        $noPembagi = $request->input('noPembagi');
        $nilaiPembagi = $request->input('nilaiPembagi');

        try {
            DB::table('tbl_iklan')
            ->where('id', $id)
            ->update([
               'no_pembagi' => $noPembagi,
                'nilai_pembagi' => $nilaiPembagi,
                'updated_at' => now(),
            ]);

            return response()->json(['status' => 'success', 'message' => 'Data berhasil diupdate'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal Mengupdate Data: ' . $e->getMessage()], 500);
        }
    }
}

