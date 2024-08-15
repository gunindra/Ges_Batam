<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index(){


        return view('Tracking');
    }

    public function lacakResi(Request $request)
    {
        $noresi = $request->input('noresi');




    }
}
