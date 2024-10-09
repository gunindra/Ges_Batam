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

        $aboutus = DB::table('tbl_aboutus')->select('Paragraph_AboutUs', DB::raw('LEFT(Paragraph_AboutUs, 250) AS Paragraph_AboutUs'))->first();

        $whyus = DB::table('tbl_whyus')->select('Paragraph_WhyUs', DB::raw('LEFT(Paragraph_WhyUs, 250) AS Paragraph_WhyUs'))->first();

        $listheropage = DB::select("SELECT id, title_heropage, LEFT(content_heropage, 160) AS content_heropage, image_heropage FROM tbl_heropage");

        $popup = DB::table('tbl_popup')->first();

        $contact = DB::table('tbl_contact')->first();
        
        $wa = DB::table('tbl_wa')->first();

        return view('landingpage.PTGes ', [
            'listinformation' => $listinformation,
            'listservices' => $listservices,
            'listiklan' => $listiklan,
            'aboutus' => $aboutus,
            'whyus' => $whyus,
            'listheropage' => $listheropage,
            'popup' => $popup,
            'wa' => $wa,
            'contact' => $contact,
        ]);
    }
}
