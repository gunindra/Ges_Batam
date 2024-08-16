<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Services2Controller extends Controller
{
    public function index()
    {
      
        return view('landingpage.Services2');
    }
}