<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SlideController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->query('id');

        $dataCarousel = DB::select("SELECT * FROM tbl_carousel WHERE id = $id");

        return view('landingpage.Slide', ['dataCarousel' => $dataCarousel]);
    }

}