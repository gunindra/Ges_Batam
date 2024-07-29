<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RekeningController extends Controller
{
    public function index() {


        return view('masterdata.rekening.indexmasterrekening');
    }
}
