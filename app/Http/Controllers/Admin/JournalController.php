<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

class JournalController extends Controller
{
    public function index()
    {


        return view('accounting.journal.indexjournal');
    }


}

