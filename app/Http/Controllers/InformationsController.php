<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InformationsController extends Controller
{
    public function index()
    {
        return view('information.informations.indexinformation');
    }
    public function getlistInformations(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';

        $q = "SELECT id,
                        image_informations,
                        judul_informations,
                        isi_informations
                FROM tbl_informations
        ";

        // dd($q);

        $data = DB::select($q);

        $output = '  <table class="table align-items-center table-flush table-hover" id="tableInformations">
                                <thead class="thead-light">
                                    <tr>
                                        <th>image</th>
                                        <th>judul</th>
                                        <th>isi</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>';
        foreach ($data as $item) {
            $output .=
                '
                <tr>
                    <td class="">' . ($item->image_informations ?? '-') .'</td>
                    <td class="">' . ($item->judul_informations ?? '-') .'</td>
                    <td class="">' . ($item->isi_informations ?? '-') .'</td>
                   <td>
                        <a  class="btn btnUpdateInformations btn-sm btn-secondary text-white" data-id="' .$item->id .'" data-image_informations="' .$item->image_informations .'" data-judul_informations="' .$item->judul_informations .'" data-isi_informations="' .$item->isi_informations .'"><i class="fas fa-edit"></i></a>
                        <a  class="btn btnDestroyInformations btn-sm btn-danger text-white" data-id="' .$item->id .'" ><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            ';
        }

        $output .= '</tbody></table>';
         return $output;
    }
    public function destroyInformations(Request $request)
    {
        $id = $request->input('id');

        try {
            DB::table('tbl_informations')
                ->where('id', $id)
                ->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

}