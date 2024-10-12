<?php

namespace App\Http\Controllers\LandingPage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->query('id');

        $dataService = DB::select("SELECT title_service,content_service,image_service FROM tbl_service WHERE id = $id");
        $dataPtges = DB::table('tbl_ptges')->first(); 

        return view('landingpage.Services', ['dataService' => $dataService , 'dataPtges' => $dataPtges]);
    }


}
