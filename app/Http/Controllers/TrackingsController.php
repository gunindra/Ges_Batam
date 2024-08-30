<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Storage;

class TrackingsController extends Controller
{
    public function index(){


        return view('Tracking.indextracking');
    }
}