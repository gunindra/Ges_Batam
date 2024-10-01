<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchasePaymentController extends Controller
{
    public function index() {


        return view('vendor.purchasepayment.indexpurchasepayment');
    }
    public function addPurchasePayment()
    {
        return view('vendor.purchasepayment.buatpurchasepayment');
    }


}