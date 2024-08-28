<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WhysController extends Controller
{
    
        public function index(Request $request)
        {
            
            $id = $request->query('id');
    
            $dataWhy = DB::select("SELECT * FROM tbl_whyus WHERE id = 1");
    
            return view('landingpage.Why', ['dataWhy' => $dataWhy]);
        }
      

}