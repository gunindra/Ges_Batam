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


        return view('landingpage.PTGes', [
            'listinformation' => $listinformation,
            'listservices' => $listservices,
            'listiklan' => $listiklan,
        ]);
    }
}
