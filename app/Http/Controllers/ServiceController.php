<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;

class ServiceController extends Controller
{
    public function index()
    {
      
        return view('content.services.indexservice');
    }

    public function getlistService(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';

        $q = "SELECT id,
                        judul_service,
                        isi_service,
                        image_service
                FROM tbl_service
        ";

        // dd($q);

        $data = DB::select($q);

        $output = '  <table class="table align-items-center table-flush table-hover" id="tableService">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Judul</th>
                                        <th>Content</th>
                                        <th>Image</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>';
        foreach ($data as $item) {

            $image = $item->image_service;
            $imagepath = Storage::url('images/' . $image);
            

            $output .=
                '
                <tr>
                    <td class="">' . ($item->judul_service ?? '-') .'</td>
                    <td class="">' . ($item->isi_service ?? '-') .'</td>
                     <td class=""><img src="' . asset($imagepath) . '" alt="Gambar" width="100px" height="100px"></td>
                   <td>
                        <a  class="btn btnUpdateService btn-sm btn-secondary text-white" data-id="' .$item->id.'" data-judul_service="' .$item->judul_service.'"data-isi_service="' .$item->isi_service.'" data-image_service="' .$item->image_service.'"><i class="fas fa-edit"></i></a>
                        <a  class="btn btnDestroyService btn-sm btn-danger text-white" data-id="' .$item->id.'" ><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            ';
        }

        $output .= '</tbody></table>';
         return $output;
    }

    public function addService(Request $request)
    {
        $request->validate([
            'imageService' => 'nullable|mimes:jpg,jpeg,png|', 
        ]);

        $judulService = $request->input('judulService');
        $isiService = $request->input('isiService');
        $imageService = $request->file('imageService');

        try {
            // Cek apakah jumlah data sudah lebih dari atau sama dengan 6

            if ($imageService) {
                $fileName = 'Service_' . $imageService->getClientOriginalName();
                $filePath = $imageService->storeAs('public/images', $fileName);
            } else {
                $fileName = null; // No image was uploaded
            }

            DB::table('tbl_service')->insert([
                'judul_service' => $judulService,
                'isi_service' => $isiService,
                'image_service' => $fileName,
                'created_at' => now(),
            ]);

            return response()->json(['status' => 'success', 'message' => 'Berhasil ditambahkan'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menambahkan: ' . $e->getMessage()], 500);
        }
    }
    public function destroyService(Request $request)
    {
        $id = $request->input('id');

        try {
            DB::table('tbl_service')
                ->where('id', $id)
                ->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateService(Request $request)
    {
        $request->validate([
            'imageService' => 'nullable|mimes:jpg,jpeg,png|', 
        ]);
        $id = $request->input('id');
        $judulService= $request->input('judulService');
        $isiService = $request->input('isiService');
        $imageService = $request->file('imageService');

        try {
            
            $dataUpdate = [
                'judul_service' => $judulService,
                'isi_service' => $isiService,
                'updated_at' => now(),
            ];

            // Hanya tambahkan file image jika tidak null
            if ($imageService) {
                $fileName = $imageService->getClientOriginalName();
                $imageService->storeAs('public/images', $fileName);
                $dataUpdate['image_service'] = $fileName;
            }

            DB::table('tbl_service')
                ->where('id', $id)
                ->update($dataUpdate);

            return response()->json(['status' => 'success', 'message' => 'Data berhasil diupdate'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal Mengupdate Data: ' . $e->getMessage()], 500);
        }
    }

}