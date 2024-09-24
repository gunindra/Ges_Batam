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
        $listinformation =  DB::select("SELECT  title_informations,content_informations,image_informations FROM tbl_informations");
        $listservices = DB::select("SELECT  id,title_service,content_service,image_service FROM tbl_service");
        foreach ($listservices as $service) {
            $service->content_service = Str::limit($service->content_service, 150,'');
        }
        $listiklan = DB::select("SELECT image_Advertisement,title_Advertisement FROM tbl_Advertisement");
        $aboutus = DB::table('tbl_aboutus')->first();
        if ($aboutus) {
            $aboutus->Paragraph_AboutUs = Str::limit($aboutus->Paragraph_AboutUs, 250, '');
        }
        $whyus = DB::table('tbl_whyus')->first();
        if ($whyus) {
            $whyus->Paragraph_WhyUs = Str::limit($whyus->Paragraph_WhyUs, 250 ,''); 
        }
        $listheropage =  DB::select("SELECT  id, title_heropage,content_heropage,image_heropage FROM tbl_heropage");
        foreach ($listheropage as $heropage) {
            $heropage->content_heropage = Str::limit($heropage->content_heropage, 160,'');
        }
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
