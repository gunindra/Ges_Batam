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
        return view('content.advertisement.indexadvertisement');
    }

    public function getlistAdvertisement(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';

        $q = "SELECT id,
                        title_Advertisement,
                        image_Advertisement
                FROM tbl_advertisement
        ";

        // dd($q);

        $data = DB::select($q);

        $output = '  <table class="table align-items-center table-flush table-hover" id="tableAdvertisement">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Title</th>
                                        <th>Image</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>';
        foreach ($data as $item) {

            $image = $item->image_Advertisement;
            $imagepath = Storage::url('images/' . $image);


            $output .=
                '
                <tr>
                    <td class="">' . ($item->title_Advertisement ?? '-') .'</td>
                     <td class=""><img src="' . asset($imagepath) . '" alt="Gambar" width="100px" height="100px"></td>
                   <td>
                     <a class="btn btnUpdateAdvertisement btn-sm btn-secondary text-white" data-id="' . $item->id . '" data-title_Advertisement="' . $item->title_Advertisement . '" data-image_Advertisement="' . $item->image_Advertisement . '"><i class="fas fa-edit"></i></a>
                    <a class="btn btnDestroyAdvertisement btn-sm btn-danger text-white" data-id="' .$item->id.'" ><i class="fas fa-trash"></i></a>
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
            'titleAdvertisement' => 'required|string|max:255', 
            'imageAdvertisement' => 'nullable|mimes:jpg,jpeg,png,svg|', 
        ]);

        $titleAdvertisement = $request->input('titleAdvertisement');
        $imageAdvertisement = $request->file('imageAdvertisement');


        try {
            $chekdata = DB::table('tbl_advertisement')->count();

            if ($chekdata >= 7) {
                return response()->json(['status' => 'error', 'message' => 'Data tidak bisa ditambahkan lagi, jumlah maksimal 7 data sudah tercapai.'], 400);
            }
           
                if ($imageAdvertisement) {
                    $uniqueId = uniqid('Advertisement_', true);
                    $fileName = $uniqueId . '.' . $imageAdvertisement->getClientOriginalExtension();
                    $imageAdvertisement->storeAs('public/images', $fileName);
                } else {
                    $fileName = null;
                }

            DB::table('tbl_advertisement')->insert([
                'title_Advertisement' => $titleAdvertisement,
                'image_Advertisement' => $fileName,
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
            $existingAdvertisement = DB::table('tbl_advertisement')->where('id', $id)->first();

            if ($existingAdvertisement && $existingAdvertisement->image_Advertisement) {
                $existingImagePath = 'public/images/' . $existingAdvertisement->image_Advertisement;
    
    
                if (Storage::exists($existingImagePath)) {
                    Storage::delete($existingImagePath);
                }
            DB::table('tbl_advertisement')
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
            'titleAdvertisement' => 'required|string|max:255', 
            'imageAdvertisement' => 'nullable|mimes:jpg,jpeg,png,svg|', 
        ]);

        $id = $request->input('id');
        $titleAdvertisement = $request->input('titleAdvertisement');
        $imageAdvertisement = $request->file('imageAdvertisement');

        try {
            $existingAdvertisement = DB::table('tbl_advertisement')->where('id', $id)->first();

            $dataUpdate = [
                'title_Advertisement' => $titleAdvertisement,
                'updated_at' => now(),
            ];
            if ($imageAdvertisement) {

                if ($existingAdvertisement && $existingAdvertisement->image_Advertisement) {
                    $existingImagePath = 'public/images/' . $existingAdvertisement->image_Advertisement;
                    if (Storage::exists($existingImagePath)) {
                        Storage::delete($existingImagePath);
                    }
                }
                

                $uniqueId = uniqid('Advertisement_', true);
                $fileName = $uniqueId . '.' . $imageAdvertisement->getClientOriginalExtension();
                $imageAdvertisement->storeAs('public/images', $fileName);
                $dataUpdate['image_Advertisement'] = $fileName; 
            }
            DB::table('tbl_advertisement')
                ->where('id', $id)
                ->update($dataUpdate);

            return response()->json(['status' => 'success', 'message' => 'Data berhasil diupdate'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal Mengupdate Data: ' . $e->getMessage()], 500);
        }
    }
}
