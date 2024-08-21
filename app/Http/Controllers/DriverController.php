<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DriverController extends Controller
{
    public function index() {


        return view('masterdata.driver.indexmasterdriver');
    }

    public function getlistDriver(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';

        $q = "SELECT id,
                        nama_supir,
                        alamat_supir,
                        no_wa
                FROM tbl_supir
        ";

        $data = DB::select($q);

        $output = '  <table class="table align-items-center table-flush table-hover" id="tableDriver">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>No. Telp</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>';
        foreach ($data as $item) {
            $output .=
                '
                <tr>
                    <td class="">' . ($item->nama_supir ?? '-') .'</td>
                    <td class="">' . ($item->alamat_supir ?? '-') .'</td>
                    <td class="">' . ($item->no_wa ?? '-') .'</td>
                   <td>
                        <a  class="btn btnUpdateDriver btn-sm btn-secondary text-white" data-id="' .$item->id .'" data-nama_supir="' .$item->nama_supir .'" data-alamat_supir="' .$item->alamat_supir .'" data-no_wa="' .$item->no_wa .'"><i class="fas fa-edit"></i></a>
                        <a  class="btn btnDestroyDriver btn-sm btn-danger text-white" data-id="' .$item->id .'" ><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            ';
        }

        $output .= '</tbody></table>';
         return $output;
    }

    public function addDriver(Request $request)
    {

        $namaDriver = $request->input('namaDriver');
        $alamatDriver = $request->input('alamatDriver');
        $noTelponDriver = $request->input('noTelponDriver');

        try {
            DB::table('tbl_supir')->insert([
                'nama_supir' => $namaDriver,
                'alamat_supir' => $alamatDriver,
                'no_wa' => $noTelponDriver,
                'created_at' => now(),
            ]);

            return response()->json(['status' => 'success', 'message' => 'Driver berhasil ditambahkan'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menambahkan Driver: ' . $e->getMessage()], 500);
        }
    }

    public function updateDriver(Request $request)
    {
        $id = $request->input('id');
        $namaDriver = $request->input('namaDriver');
        $alamatDriver = $request->input('alamatDriver');
        $noTelponDriver = $request->input('noTelponDriver');

        try {
            DB::table('tbl_supir')
            ->where('id', $id)
            ->update([
               'nama_supir' => $namaDriver,
                'alamat_supir' => $alamatDriver,
                'no_wa' => $noTelponDriver,
                'updated_at' => now(),
            ]);

            return response()->json(['status' => 'success', 'message' => 'Data Driver berhasil diupdate'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal Mengupdate Data Driver: ' . $e->getMessage()], 500);
        }
    }

    public function destroyDriver(Request $request)
    {
        $id = $request->input('id');

        try {
            DB::table('tbl_supir')
                ->where('id', $id)
                ->delete();

            return response()->json(['status' => 'success', 'message' => 'Data Driver berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
