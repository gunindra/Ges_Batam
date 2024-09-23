<?php

namespace App\Http\Controllers\LandingPage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AboutsController extends Controller
{
    public function index(Request $request)
    {
       
        $contact = DB::table('tbl_contact')->first(); 

 
        $dataAbout = DB::select("SELECT * FROM tbl_aboutus WHERE id = 1"); 
        // ganti tanda * dan kenapa id = 1

        return view('landingpage.About', [
            'contact' => $contact,
            'dataAbout' => $dataAbout
        ]);
    }
   


}