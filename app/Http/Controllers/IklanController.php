<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;

class IklanController extends Controller
{
    public function index()
    {
        return view('information.iklan.indexiklan');
    }
    
    public function getlistIklan(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';

        $q = "SELECT id,
                        judul_iklan,
                        image_iklan
                FROM tbl_iklan
        ";

        // dd($q);

        $data = DB::select($q);

        $output = '  <table class="table align-items-center table-flush table-hover" id="tableIklan">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Judul Iklan</th>
                                        <th>Image</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>';
        foreach ($data as $item) {

            $image = $item->image_iklan;
            $imagepath = Storage::url('images/' . $image);


            $output .=
                '
                <tr>
                    <td class="">' . ($item->judul_iklan ?? '-') .'</td>
                     <td class=""><img src="' . asset($imagepath) . '" alt="Gambar" width="100px" height="100px"></td>
                   <td>
                        <a  class="btn btnUpdateIklan btn-sm btn-secondary text-white" data-id="' .$item->id.'" data-judul_iklan="' .$item->judul_iklan.'" data-image_iklan="' .$item->image_iklan.'"><i class="fas fa-edit"></i></a>
                        <a  class="btn btnDestroyIklan btn-sm btn-danger text-white" data-id="' .$item->id.'" ><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            ';
        }

        $output .= '</tbody></table>';
         return $output;
    }
    public function addIklan(Request $request)
    {

        $judulIklan = $request->input('judulIklan');
        $file = $request->file('imageIklan');


        try {
            if ($file) {
                $fileName = $file->getClientOriginalName();
                $filePath = $file->storeAs('public/images', $fileName);
            } else {
                $file = null; // No image was uploaded
            }

            DB::table('tbl_iklan')->insert([
                'judul_iklan' => $judulIklan,
                'image_iklan' => $fileName,
                'created_at' => now(),
            ]);

            return response()->json(['status' => 'success', 'message' => 'berhasil ditambahkan'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menambahkan : ' . $e->getMessage()], 500);
        }
    }
    public function destroyIklan(Request $request)
    {
        $id = $request->input('id');

        try {
            DB::table('tbl_iklan')
                ->where('id', $id)
                ->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function updateIklan(Request $request)
    {
        $id = $request->input('id');
        $judulIklan = $request->input('judulIklan');
        $imageIklan = $request->file('imageIklan');

        try {
            $dataUpdate = [
                'judul_iklan' => $judulIklan,
                'updated_at' => now(),
            ];

            // Hanya tambahkan file image jika tidak null
            if ($imageIklan) {
                $fileName = $imageIklan->getClientOriginalName();
                $imageIklan->storeAs('public/images', $fileName);
                $dataUpdate['image_iklan'] = $fileName;
            }

            DB::table('tbl_iklan')
                ->where('id', $id)
                ->update($dataUpdate);

            return response()->json(['status' => 'success', 'message' => 'Data berhasil diupdate'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal Mengupdate Data: ' . $e->getMessage()], 500);
        }
    }
}