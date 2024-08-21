<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PtgesController extends Controller
{

    public function index()
    {
        $listinformation =  DB::select("SELECT * FROM tbl_informations");
        $listservices = DB::select("SELECT * FROM tbl_service");
        foreach ($listservices as $service) {
            $service->isi_service = Str::limit($service->isi_service, 150);
        }
        $listiklan = DB::select("SELECT image_iklan, judul_iklan FROM tbl_iklan");
        $aboutus = DB::table('tbl_aboutus')->first();
        if ($aboutus) {
            $aboutus->Paraf_AboutUs = Str::limit($aboutus->Paraf_AboutUs, 220); 
        }
        $whyus = DB::table('tbl_whyus')->first();
        if ($whyus) {
            $whyus->Paraf_WhyUs = Str::limit($whyus->Paraf_WhyUs, 220); 
        }
        $listcarousel =  DB::select("SELECT * FROM tbl_carousel");
        foreach ($listcarousel as $carousel) {
            $carousel->isi_carousel = Str::limit($carousel->isi_carousel, 160);
        }
       



        return view('landingpage.PTGes', [
            'listinformation' => $listinformation,
            'listservices' => $listservices,
            'listiklan' => $listiklan,
            'aboutus' => $aboutus,
            'whyus' => $whyus,
            'listcarousel' => $listcarousel,
        ]);
    }
}
