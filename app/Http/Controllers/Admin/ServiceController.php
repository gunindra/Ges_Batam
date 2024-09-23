<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
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
                                        <th>Title</th>
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
            'judulService' => 'required|string|max:255', 
            'isiService' => 'required|string', 
            'imageService' => 'nullable|mimes:jpg,jpeg,png|', 
        ]);

        $judulService = $request->input('judulService');
        $isiService = $request->input('isiService');
        $imageService = $request->file('imageService');

        try {

            if ($imageService) {
                $uniqueId = uniqid('Service_', true);
                $fileName = $uniqueId . '.' . $imageService->getClientOriginalExtension();
                $imageService->storeAs('public/images', $fileName);
            } else {
                $fileName = null;
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
       
        $existingService = DB::table('tbl_service')->where('id', $id)->first();

        if ($existingService && $existingService->image_service) {
            $existingImagePath = 'public/images/' . $existingService->image_service;


            if (Storage::exists($existingImagePath)) {
                Storage::delete($existingImagePath);
            }
        }

        DB::table('tbl_service')
            ->where('id', $id)
            ->delete();

        return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
    }
}


    public function updateService(Request $request)
    {
        $request->validate([
            'judulService' => 'required|string|max:255', 
            'isiService' => 'required|string',
            'imageService' => 'nullable|mimes:jpg,jpeg,png', 
        ]);
    
        $id = $request->input('id');
        $judulService = $request->input('judulService');
        $isiService = $request->input('isiService');
        $imageService = $request->file('imageService');
    
        try {
            $existingService = DB::table('tbl_service')->where('id', $id)->first();
            
            $dataUpdate = [
                'judul_service' => $judulService,
                'isi_service' => $isiService,
                'updated_at' => now(),
            ];
    
            if ($imageService) {

                if ($existingService && $existingService->image_service) {
                    $existingImagePath = 'public/images/' . $existingService->image_service;
                    if (Storage::exists($existingImagePath)) {
                        Storage::delete($existingImagePath);
                    }
                }
                

                $uniqueId = uniqid('Service_', true);
                $fileName = $uniqueId . '.' . $imageService->getClientOriginalExtension();
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