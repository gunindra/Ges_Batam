<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;

class AdvertisementController extends Controller
{
    public function index()
    {
        return view('content.advertisement.indexAdvertisement');
    }

    public function getlistAdvertisement(Request $request)
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
                                        <th>Title</th>
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
    public function addAdvertisement(Request $request)
    {
        $request->validate([
            'judulIklan' => 'required|string|max:255', 
            'imageIklan' => 'nullable|mimes:jpg,jpeg,png,svg|', 
        ]);

        $judulIklan = $request->input('judulIklan');
        $imageIklan = $request->file('imageIklan');


        try {
            $chekdata = DB::table('tbl_iklan')->count();

            if ($chekdata >= 7) {
                return response()->json(['status' => 'error', 'message' => 'Data tidak bisa ditambahkan lagi, jumlah maksimal 7 data sudah tercapai.'], 400);
            }
           
                if ($imageIklan) {
                    $uniqueId = uniqid('Advertisement_', true);
                    $fileName = $uniqueId . '.' . $imageIklan->getClientOriginalExtension();
                    $imageIklan->storeAs('public/images', $fileName);
                } else {
                    $fileName = null;
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
    public function destroyAdvertisement(Request $request)
    {
        $id = $request->input('id');

        try {
            $existingIklan = DB::table('tbl_iklan')->where('id', $id)->first();

            if ($existingIklan && $existingIklan->image_iklan) {
                $existingImagePath = 'public/images/' . $existingIklan->image_iklan;
    
    
                if (Storage::exists($existingImagePath)) {
                    Storage::delete($existingImagePath);
                }
            DB::table('tbl_iklan')
                ->where('id', $id)
                ->delete();
            }
            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function updateAdvertisement(Request $request)
    {
        $request->validate([
            'judulIklan' => 'required|string|max:255', 
            'imageIklan' => 'nullable|mimes:jpg,jpeg,png,svg|', 
        ]);

        $id = $request->input('id');
        $judulIklan = $request->input('judulIklan');
        $imageIklan = $request->file('imageIklan');

        try {
            $existingIklan = DB::table('tbl_iklan')->where('id', $id)->first();

            $dataUpdate = [
                'judul_iklan' => $judulIklan,
                'updated_at' => now(),
            ];
            if ($imageIklan) {

                if ($existingIklan && $existingIklan->image_iklan) {
                    $existingImagePath = 'public/images/' . $existingIklan->image_iklan;
                    if (Storage::exists($existingImagePath)) {
                        Storage::delete($existingImagePath);
                    }
                }
                

                $uniqueId = uniqid('Advertisement_', true);
                $fileName = $uniqueId . '.' . $imageIklan->getClientOriginalExtension();
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
