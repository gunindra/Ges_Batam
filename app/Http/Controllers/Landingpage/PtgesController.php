<?php

namespace App\Http\Controllers\LandingPage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PtgesController extends Controller
{

    public function index()
    {
        $listinformation = DB::select("SELECT title_informations, content_informations, image_informations FROM tbl_informations");

        $listservices = DB::select("SELECT id, title_service, LEFT(content_service, 150) AS content_service, image_service FROM tbl_service");

        $listiklan = DB::select("SELECT image_Advertisement, title_Advertisement FROM tbl_Advertisement");

        $dataPtges = DB::table('tbl_ptges')->select('Image_AboutUs','Paragraph_AboutUs', DB::raw('LEFT(Paragraph_AboutUs, 250) AS Paragraph_AboutUs'),'Image_WhyUs','Paragraph_WhyUs', DB::raw('LEFT(Paragraph_WhyUs, 250) AS Paragraph_WhyUs'),'email','phone','phones')->first();

        $listheropage = DB::select("SELECT id, title_heropage AS title_heropage, content_heropage AS content_heropage, image_heropage FROM tbl_heropage");

        $popup = DB::table('tbl_popup')->first();
        
        $wa = DB::table('tbl_wa')->first();

        return view('landingpage.PTGes ', [
            'listinformation' => $listinformation,
            'listservices' => $listservices,
            'listiklan' => $listiklan,
            'dataPtges' => $dataPtges,
            'listheropage' => $listheropage,
            'popup' => $popup,
            'wa' => $wa,
        ]);
    }
}
