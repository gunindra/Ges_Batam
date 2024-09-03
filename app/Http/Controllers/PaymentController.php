<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index()
    {
        return view('customer.payment.indexpayment');
    }

    public function addPayment()
    {
        return view('customer.payment.buatpayment');
    }


}
