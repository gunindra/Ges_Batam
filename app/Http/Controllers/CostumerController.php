<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CostumerController extends Controller
{
    public function index() {


        return view('masterdata.costumer.indexmaster');
    }
}
