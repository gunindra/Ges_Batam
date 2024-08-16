<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function index(){


        return view('landingpage.Services');
    }
}