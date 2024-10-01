<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DebitNoteController extends Controller
{
    public function index() {


        return view('vendor.debitnote.indexdebitnote');
    }
    public function addDebitNote()
    {
        return view('vendor.debitnote.buatdebitnote');
    }


}