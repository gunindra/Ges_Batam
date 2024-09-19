<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class SupirController extends Controller
{
    public function index(Request $request)
    {
    $listInvoice = DB::select("SELECT no_invoice FROM tbl_invoice");

        return view('supir.indexsupir', [
            'listInvoice' => $listInvoice
        ]);
    }
   

}
