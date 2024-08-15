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
        $no = 1;
        foreach ($data as $item) {
            $output .=
                '
                <tr>
                    <td class="">' . $no++ .'</td>
                     <td class="">' . (isset($item->nilai_pembagi) ? ' ' . number_format($item->nilai_pembagi,0, '.', ',') : '-') . '</td>
                   <td>
                        <a  class="btn btnUpdatePembagi btn-sm btn-secondary text-white" data-id="' .$item->id.'" data-nilai_pembagi="' .$item->nilai_pembagi.'"><i class="fas fa-edit"></i></a>
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


        $nilaiPembagi = $request->input('nilaiPembagi');

        try {
            DB::table('tbl_pembagi')->insert([
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
        $nilaiPembagi = $request->input('nilaiPembagi');

        try {
            DB::table('tbl_pembagi')
            ->where('id', $id)
            ->update([
                'nilai_pembagi' => $nilaiPembagi,
                'updated_at' => now(),
            ]);

            return response()->json(['status' => 'success', 'message' => 'Data berhasil diupdate'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal Mengupdate Data: ' . $e->getMessage()], 500);
        }
    }

    public function getlistRate(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';

        $q = "SELECT id,
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
        $no = 1;
        foreach ($data as $item) {
            $output .=
                '
                <tr>
                    <td class="">' . $no .'</td>
                    <td class="">' . (isset($item->nilai_rate) ? '' . number_format($item->nilai_rate,0, '.', ',') : '-') . '</td>
                   <td>
                        <a  class="btn btnUpdateRate btn-sm btn-secondary text-white" data-id="' .$item->id.'" data-nilai_rate="' .$item->nilai_rate.'"><i class="fas fa-edit"></i></a>
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
        $nilaiRate = $request->input('nilaiRate');

        try {
            DB::table('tbl_rate')->insert([
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
        $nilaiRate = $request->input('nilaiRate');

        try {
            DB::table('tbl_rate')
            ->where('id', $id)
            ->update([
                'nilai_rate' => $nilaiRate,
                'updated_at' => now(),
            ]);

            return response()->json(['status' => 'success', 'message' => 'Data berhasil diupdate'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal Mengupdate Data: ' . $e->getMessage()], 500);
        }
    }
}