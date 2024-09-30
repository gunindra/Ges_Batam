<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

class CoaController extends Controller
{
    public function index()
    {


        return view('accounting.coa.indexcoa');
    }


}
