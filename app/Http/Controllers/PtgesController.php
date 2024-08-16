<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PtgesController extends Controller
{

    public function index()
    {

        $listinformation =  DB::select("SELECT * FROM tbl_informations");

        return view('landingpage.PTGes', [
            'listinformation' => $listinformation,
        ]);
    }
}
