<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index(){


        return view('landingpage.Tracking');
    }

    public function lacakResi(Request $request)
    {
            $noresiString = $request->input('noresiTags');
            $noresiArray = explode(',', $noresiString);
            if (empty($noresiArray)) {
                return response()->json(['error' => 'No resi harus berupa array dan tidak boleh kosong.'], 400);
            }

            $data = DB::table('tbl_tracking')
                ->whereIn('no_resi', $noresiArray)
                ->get();

            return response()->json($data);
    }

}
