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

        $dataHeropage = DB::select("SELECT id,title_heropage,content_heropage,image_heropage FROM tbl_heropage WHERE id = $id");

        return view('landingpage.Slide', ['dataCarousel' => $dataCarousel]);
    }

}