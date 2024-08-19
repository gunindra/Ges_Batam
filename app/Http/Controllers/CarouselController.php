<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;

class CarouselController extends Controller
{
    public function index()
    {
        return view('information.carousel.indexcarousel');
    }
    
    public function getlistCarousel(Request $request)
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
    public function addCarousel(Request $request)
    {

        $judulCarousel = $request->input('judulCarousel');
        $isiCarousel = $request->input('isiCarousel');
        $file = $request->file('imageCarousel');

        try {
            if ($file) {
                $fileName = $file->getClientOriginalName();
                $filePath = $file->storeAs('public/images', $fileName);
            } else {
                $file = null; // No image was uploaded
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

    public function destroyCarousel(Request $request)
    {
        $id = $request->input('id');

        try {
            DB::table('tbl_carousel')
                ->where('id', $id)
                ->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function updateCarousel(Request $request)
    {
        $id = $request->input('id');
        $judulCarousel = $request->input('judulCarousel');
        $isiCarousel = $request->input('isiCarousel');
        $imageCarousel = $request->file('imageCarousel');

        try {
            $dataUpdate = [
                'judul_carousel' => $judulCarousel,
                'isi_carousel' => $isiCarousel,
                'updated_at' => now(),
            ];

            // Hanya tambahkan file image jika tidak null
            if ($imageCarousel) {
                $fileName = $imageCarousel->getClientOriginalName();
                $imageCarousel->storeAs('public/images', $fileName);
                $dataUpdate['image_Carousel'] = $fileName;
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