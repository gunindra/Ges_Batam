<?php

namespace App\Http\Controllers\LandingPage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SlideController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->query('id');

        $dataCarousel = DB::select("SELECT id,judul_carousel,isi_carousel,image_carousel FROM tbl_carousel WHERE id = $id");

        return view('landingpage.Slide', ['dataCarousel' => $dataCarousel]);
    }

}