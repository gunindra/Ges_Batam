<?php

namespace App\Http\Controllers\Landingpage;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index(Request $request){

        $id = $request->query('id');

            $dataPtges = DB::table('tbl_ptges')->first();

            return view('landingpage.Tracking', [
                'dataPtges' => $dataPtges,
            ]);

    }

    public function lacakResi(Request $request)
    {

        // dd($request->all());
            $noresiString = $request->input('noresiTags');
            $noresiArray = explode(',', $noresiString);
            if (empty($noresiArray)) {
                return response()->json(['error' => 'No resi harus berupa array dan tidak boleh kosong.'], 400);
            }

            $data = DB::table('tbl_tracking as t')
                ->leftJoin('tbl_resi as r', 't.no_resi', '=', 'r.no_resi')
                ->leftJoin('tbl_invoice as i', 'r.invoice_id', '=', 'i.id')
                ->leftJoin('tbl_pembeli as p', 'i.pembeli_id', '=', 'p.id')
                ->whereIn('t.no_resi', $noresiArray)
                ->select(
                    't.*',
                    DB::raw('COALESCE(p.marking, "-") as marking') // Jika marking NULL, maka diganti "-"
                )
                ->get();

            return response()->json($data);
    }

}
