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

        $dataService = DB::select("SELECT * FROM tbl_service WHERE id = $id");
        $contact = DB::table('tbl_contact')->first(); 

        return view('landingpage.Services', ['dataService' => $dataService , 'contact' => $contact]);
    }


}
