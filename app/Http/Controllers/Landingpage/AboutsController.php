<?php

namespace App\Http\Controllers\LandingPage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AboutsController extends Controller
{
    public function index(Request $request)
    {
        $dataPtges = DB::table('tbl_ptges')->first();

        return view('landingpage.About', [
            'dataPtges' => $dataPtges,
        ]);
    }
   


}