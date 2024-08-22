<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InformationsController extends Controller
{
    public function index()
    {
        return view('content.informations.indexinformation');
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
                                        <th>Content</th>
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
        $imageInformations = $request->file('imageInformations');

        try {
            // Cek apakah jumlah data sudah lebih dari atau sama dengan 6
            $chekdata = DB::table('tbl_informations')->count();

            if ($chekdata >= 6) {
                return response()->json(['status' => 'error', 'message' => 'Data tidak bisa ditambahkan lagi, jumlah maksimal 6 data sudah tercapai.'], 400);
            }

            if ($imageInformations) {
                $fileName = 'Information_' . $imageInformations->getClientOriginalName();
                $filePath = $imageInformations->storeAs('public/images', $fileName);
            } else {
                $fileName = null; // No image was uploaded
            }

            DB::table('tbl_informations')->insert([
                'judul_informations' => $judulInformations,
                'isi_informations' => $isiInformations,
                'image_informations' => $fileName,
                'created_at' => now(),
            ]);

            return response()->json(['status' => 'success', 'message' => 'Berhasil ditambahkan'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menambahkan: ' . $e->getMessage()], 500);
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
            $dataUpdate = [
                'judul_informations' => $judulInformations,
                'isi_informations' => $isiInformations,
                'updated_at' => now(),
            ];

            // Hanya tambahkan file image jika tidak null
            if ($imageInformations) {
                $fileName = $imageInformations->getClientOriginalName();
                $imageInformations->storeAs('public/images', $fileName);
                $dataUpdate['image_informations'] = $fileName;
            }

            DB::table('tbl_informations')
                ->where('id', $id)
                ->update($dataUpdate);

            return response()->json(['status' => 'success', 'message' => 'Data berhasil diupdate'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal Mengupdate Data: ' . $e->getMessage()], 500);
        }
    }

}
