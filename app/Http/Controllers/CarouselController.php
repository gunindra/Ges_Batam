<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $output .=
                '
                <tr>
                    <td class="">' . ($item->judul_carousel ?? '-') .'</td>
                    <td class="">' . ($item->isi_carousel ?? '-') .'</td>
                    <td class="">' . ($item->image_carousel ?? '-') .'</td>
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
}