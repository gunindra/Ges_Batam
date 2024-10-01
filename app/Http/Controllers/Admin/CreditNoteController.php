<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreditNoteController extends Controller
{
    public function index() {


        return view('customer.creditnote.indexcreditnote');
    }
    public function addCreditNote()
    {
        return view('customer.creditnote.buatcreditnote');
    }


}