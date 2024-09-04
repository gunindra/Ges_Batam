<?php

namespace App\Http\Controllers;

use DB;
use Exception;
use Illuminate\Http\Request;
use Storage;

class TrackingsController extends Controller
{
    public function index(){


        return view('Tracking.indextracking');
    }

    public function getlistTracking (Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';

        $q = "SELECT * FROM tbl_tracking
             WHERE (
                UPPER(no_resi) LIKE UPPER('$txSearch')
                OR UPPER(no_do) LIKE UPPER('$txSearch')
                )";

        $data = DB::select($q);

        $output = '<table class="table align-items-center table-flush table-hover" id="tableTracking">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No. Resi</th>
                                        <th>No. DO</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>';
        foreach ($data as $item) {
            $output .=
                '
                <tr>
                    <td class="">' . ($item->no_resi ?? '-') .'</td>
                    <td class="">' . ($item->no_do ?? '-') .'</td>
                    <td class="">' . ($item->status ?? '-') .'</td>
                    <td class="">' . ($item->keterangan ?? '-') .'</td>
                    <td>
                        <a  class="btn btnUpdateTracking btn-sm btn-secondary text-white" data-id="' .$item->id .'" data-no_resi="' .$item->no_resi .'" data-no_do="' .$item->no_do .'" data-status="' .$item->status .'" data-keterangan="' .$item->keterangan .'"><i class="fas fa-edit"></i></a>
                        <a  class="btn btnDestroyTracking btn-sm btn-danger text-white" data-id="' .$item->id .'" ><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            ';
        }

        $output .= '</tbody></table>';
         return $output;
    }

    public function addTracking(Request $request)
    {
        try {
            foreach ($request->noResi as $resi) {
                DB::table('tbl_tracking')->insert([
                    'no_resi' => $resi,
                    'no_do' => $request->noDeliveryOrder,
                    'status' => $request->status,
                    'keterangan' => $request->keterangan,
                    'created_at' => now(),
                ]);
            }
            return response()->json(['message' => 'Tracking berhasil ditambahkan'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function updateTracking(Request $request)
    {
        try {

            $idTracking = $request->input('id');

            if (!$idTracking) {
                return response()->json(['message' => 'ID Tracking tidak ditemukan'], 400);
            }
            DB::table('tbl_tracking')
                ->where('id', $idTracking)
                ->update([
                    'no_do' => $request->input('noDeliveryOrder'),
                    // 'status' => $request->input('status'),
                    'keterangan' => $request->input('keterangan'),
                    'updated_at' => now(),
                ]);

            return response()->json(['message' => 'Tracking berhasil diperbarui'], 200);
        } catch (Exception $e) {
            // Tangani pengecualian jika terjadi kesalahan
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }

    }


    public function deleteTracking(Request $request)
    {
        $id = $request->input('id');

        try {

            DB::table('tbl_tracking')->where('id', $id)->delete();

            return response()->json(['status' => 'success', 'message' => 'Data Invoice berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
