<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class UnpaidInvoiceController extends Controller
{
    public function index()
    {
        return view('Report.UnpaidInvoice.indexunpaidinvoice');
    }
}