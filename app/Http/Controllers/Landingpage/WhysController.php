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

            $dataPtges = DB::table('tbl_ptges')->first(); 
    
            return view('landingpage.Why', [
                'dataPtges' => $dataPtges,
            ]);
        }
      

}