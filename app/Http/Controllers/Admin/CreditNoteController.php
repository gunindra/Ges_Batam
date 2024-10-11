<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\COA;

class CreditNoteController extends Controller
{
    public function index() {


        return view('customer.creditnote.indexcreditnote');
    }
    public function addCreditNote()
    {

        $coas = COA::all();
        $listCurrency = DB::select("SELECT id, nama_matauang, singkatan_matauang FROM tbl_matauang");
        $listInvoice = DB::select("SELECT id, no_invoice FROM tbl_invoice");


        return view('customer.creditnote.buatcreditnote', [
            'listCurrency' => $listCurrency,
            'coas' => $coas,
            'listInvoice' => $listInvoice
        ]);
    }


}
