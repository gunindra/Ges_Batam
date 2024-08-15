<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RateController extends Controller
{

    public function index()
    {
        return view('masterdata.pembagirate.indexpembagirate');
    }
    public function getlistRate(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';

        $q = "SELECT id,
                        no_rate,
                        nilai_rate
                FROM tbl_rate
        ";

        // dd($q);

        $data = DB::select($q);

        $output = '  <table class="table align-items-center table-flush table-hover" id="tableRate">
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
                    <td class="">' . ($item->no_rate ?? '-') .'</td>
                    <td class="">' . ($item->nilai_rate ?? '-') .'</td>
                   <td>
                        <a  class="btn btnUpdateRate btn-sm btn-secondary text-white" data-id="' .$item->id.'" data-no_rate="' .$item->no_rate.'" data-nilai_rate="' .$item->nilai_rate.'"><i class="fas fa-edit"></i></a>
                        <a  class="btn btnDestroyRate btn-sm btn-danger text-white" data-id="' .$item->id.'" ><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            ';
        }

        $output .= '</tbody></table>';
         return $output;
    }
    public function addRate(Request $request)
    {

        $noRate = $request->input('noRate');
        $nilaiRate = $request->input('nilaiRate');

        try {
            DB::table('tbl_rate')->insert([
                'no_rate' => $noRate,
                'nilai_rate' => $nilaiRate,
                'created_at' => now(),
            ]);

            return response()->json(['status' => 'success', 'message' => 'berhasil ditambahkan'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menambahkan : ' . $e->getMessage()], 500);
        }
    }
    public function destroyRate(Request $request)
    {
        $id = $request->input('id');

        try {
            DB::table('tbl_rate')
                ->where('id', $id)
                ->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function updateRate(Request $request)
    {
        $id = $request->input('id');
        $noRate = $request->input('noRate');
        $nilaiRate = $request->input('nilaiRate');

        try {
            DB::table('tbl_rate')
            ->where('id', $id)
            ->update([
               'no_rate' => $noRate,
                'nilai_rate' => $nilaiRate,
                'updated_at' => now(),
            ]);

            return response()->json(['status' => 'success', 'message' => 'Data berhasil diupdate'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal Mengupdate Data: ' . $e->getMessage()], 500);
        }
    }
}