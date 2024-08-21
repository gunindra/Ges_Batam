<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PickupController extends Controller
{
    public function index()
    {


        return view('customer.pickup.indexpickup');
    }

}
