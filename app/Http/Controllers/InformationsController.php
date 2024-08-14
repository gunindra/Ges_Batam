<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
                        judul_informations,
                        isi_informations,
                        image_informations
                FROM tbl_informations
        ";

        // dd($q);

        $data = DB::select($q);

        $output = '  <table class="table align-items-center table-flush table-hover" id="tableInformations">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Judul</th>
                                        <th>Contet</th>
                                        <th>Gambar</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>';
        foreach ($data as $item) {

            $image = $item->image_informations;
            $imagepath = Storage::url('images/' . $image);

            $output .=
                '
                <tr>
                    <td class="">' . ($item->judul_informations ?? '-') .'</td>
                    <td class="">' . ($item->isi_informations ?? '-') .'</td>
                    <td class=""><img src="' . asset($imagepath) . '" alt="Gambar" width="100px" height="100px"></td>
                    <td>
                        <a  class="btn btnUpdateInformations btn-sm btn-secondary text-white" data-id="' .$item->id .'" data-judul_informations="' .$item->judul_informations .'" data-isi_informations="' .$item->isi_informations .'" data-image_informations="' .$item->image_informations .'"><i class="fas fa-edit"></i></a>
                        <a  class="btn btnDestroyInformations btn-sm btn-danger text-white" data-id="' .$item->id .'" ><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            ';
        }

        $output .= '</tbody></table>';
         return $output;
    }
    public function addInformations(Request $request)
    {

        $judulInformations = $request->input('judulInformations');
        $isiInformations = $request->input('isiInformations');
        $file = $request->file('imageInformations');

        try {
            if ($file) {
                $fileName = $file->getClientOriginalName();
                $filePath = $file->storeAs('public/images', $fileName);
            } else {
                $file = null; // No image was uploaded
            }

            DB::table('tbl_informations')->insert([
                'judul_informations' => $judulInformations,
                'isi_informations' => $isiInformations,
                'image_informations' => $fileName,
                'created_at' => now(),
            ]);

            return response()->json(['status' => 'success', 'message' => 'berhasil ditambahkan'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menambahkan : ' . $e->getMessage()], 500);
        }
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

    public function updateInformations(Request $request)
    {
        $id = $request->input('id');
        $judulInformations = $request->input('judulInformations');
        $isiInformations = $request->input('isiInformations');
        $imageInformations = $request->file('imageInformations');

        try {
            $oldInformation = DB::table('tbl_informations')->where('id', $id)->first();

            if ($imageInformations) {
                $fileName = $imageInformations->getClientOriginalName();
                $filePath = $imageInformations->storeAs('public/images', $fileName);

                if ($oldInformation->image_informations) {
                    Storage::delete('public/images/' . $oldInformation->image_informations);
                }
            } else {
                return response()->json(['status' => 'error', 'message' => 'Gagal Menemukan data'], 401);
            }

            DB::table('tbl_informations')
                ->where('id', $id)
                ->update([
                    'judul_informations' => $judulInformations,
                    'isi_informations' => $isiInformations,
                    'image_informations' => $fileName,
                    'updated_at' => now(),
                ]);

            return response()->json(['status' => 'success', 'message' => 'Data berhasil diupdate'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal Mengupdate Data: ' . $e->getMessage()], 500);
        }
    }
}
