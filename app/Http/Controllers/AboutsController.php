<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AboutsController extends Controller
{
        public function index(Request $request)
        {
            $id = $request->query('id');
    
            $dataAbout = DB::select("SELECT * FROM tbl_aboutus WHERE id = 1");
    
            return view('landingpage.About', ['dataAbout' => $dataAbout]);
        }
   


}