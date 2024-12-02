<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class OngoingInvoiceController extends Controller
{
    public function index()
    {
        return view('Report.OngoingInvoice.indexongoinginvoice');
    }
}