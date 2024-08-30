<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VendorController extends Controller
{
    public function index()
    {
      
        return view('vendor.indexvendor');
    }
}