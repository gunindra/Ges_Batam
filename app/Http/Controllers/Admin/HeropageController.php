<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;

class HeropageController extends Controller
{
    public function index()
    {
        return view('content.heropage.indexheropage');
    }
    
    public function getlistHeroPage(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';

        $q = "SELECT id,
                        judul_carousel,
                        isi_carousel,
                        image_carousel
                FROM tbl_carousel
        ";

        // dd($q);

        $data = DB::select($q);

        $output = '  <table class="table align-items-center table-flush table-hover" id="tableCarousel">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Judul</th>
                                        <th>Isi Carousel</th>
                                        <th>Image</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>';
        foreach ($data as $item) {

            $image = $item->image_carousel;
            $imagepath = Storage::url('images/' . $image);
            $output .=
                '
                <tr>
                    <td class="">' . ($item->judul_carousel ?? '-') .'</td>
                    <td class="">' . ($item->isi_carousel ?? '-') .'</td>
                    <td class=""><img src="' . asset($imagepath) . '" alt="Gambar" width="100px" height="100px"></td>
                   <td>
                        <a  class="btn btnUpdateCarousel btn-sm btn-secondary text-white" data-id="' .$item->id.'" data-judul_carousel="' .$item->judul_carousel.'" data-isi_carousel="' .$item->isi_carousel.'" data-image_carousel="' .$item->image_carousel.'"><i class="fas fa-edit"></i></a>
                        <a  class="btn btnDestroyCarousel btn-sm btn-danger text-white" data-id="' .$item->id.'" ><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            ';
        }

        $output .= '</tbody></table>';
         return $output;
    }
    public function addHeroPage(Request $request)
    {
        $request->validate([
            'judulCarousel' => 'required|string|max:255', 
            'isiCarousel' => 'required|string|', 
            'imageCarousel' => 'nullable|mimes:jpg,jpeg,png|', 
        ]);

        $judulCarousel = $request->input('judulCarousel');
        $isiCarousel = $request->input('isiCarousel');
        $imageCarousel = $request->file('imageCarousel');

        try {
            if ($imageCarousel) {
                $uniqueId = uniqid('Heropage_', true);
                $fileName = $uniqueId . '.' . $imageCarousel->getClientOriginalExtension();
                $imageCarousel->storeAs('public/images', $fileName);
            } else {
                $fileName = null;
            }
            DB::table('tbl_carousel')->insert([
                'judul_carousel' => $judulCarousel,
                'isi_carousel' => $isiCarousel,
                'image_carousel' => $fileName,
                'created_at' => now(),
            ]);

            return response()->json(['status' => 'success', 'message' => 'berhasil ditambahkan'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menambahkan : ' . $e->getMessage()], 500);
        }
    }

    public function destroyHeroPage(Request $request)
    {
        $id = $request->input('id');

        try {
            $existingCarousel = DB::table('tbl_carousel')->where('id', $id)->first();

            if ($existingCarousel && $existingCarousel->image_carousel) {
                $existingImagePath = 'public/images/' . $existingCarousel->image_carousel;
    
    
                if (Storage::exists($existingImagePath)) {
                    Storage::delete($existingImagePath);
                }
            DB::table('tbl_carousel')
                ->where('id', $id)
                ->delete();
            }
            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function updateHeroPage(Request $request)
    {  
        $request->validate([
        'judulCarousel' => 'required|string|max:255', 
        'isiCarousel' => 'required|string', 
        'imageCarousel' => 'nullable|mimes:jpg,jpeg,png|', 
    ]);
        $id = $request->input('id');
        $judulCarousel = $request->input('judulCarousel');
        $isiCarousel = $request->input('isiCarousel');
        $imageCarousel = $request->file('imageCarousel');

        try {
            $existingCarousel = DB::table('tbl_carousel')->where('id', $id)->first();
            
            $dataUpdate = [
                'judul_carousel' => $judulCarousel,
                'isi_carousel' => $isiCarousel,
                'updated_at' => now(),
            ];
    
            if ($imageCarousel) {

                if ($existingCarousel && $existingCarousel->image_carousel) {
                    $existingImagePath = 'public/images/' . $existingCarousel->image_carousel;
                    if (Storage::exists($existingImagePath)) {
                        Storage::delete($existingImagePath);
                    }
                }
                

                $uniqueId = uniqid('Carousel_', true);
                $fileName = $uniqueId . '.' . $imageCarousel->getClientOriginalExtension();
                $imageCarousel->storeAs('public/images', $fileName);
                $dataUpdate['image_carousel'] = $fileName; 
            }

            DB::table('tbl_carousel')
                ->where('id', $id)
                ->update($dataUpdate);

            return response()->json(['status' => 'success', 'message' => 'Data berhasil diupdate'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal Mengupdate Data: ' . $e->getMessage()], 500);
        }
    }
}