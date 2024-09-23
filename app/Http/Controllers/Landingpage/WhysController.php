<?php

namespace App\Http\Controllers\LandingPage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WhysController extends Controller
{
    
        public function index(Request $request)
        {
            
            $id = $request->query('id');
    
            $dataWhy = DB::table('tbl_whyus')->first();
            $contact = DB::table('tbl_contact')->first(); 
    
            return view('landingpage.Why', [
                'dataWhy' => $dataWhy,
                'contact' => $contact,
            ]);
        }
      

}