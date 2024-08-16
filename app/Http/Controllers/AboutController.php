<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AboutController extends Controller
{
    public function index()
    {
      
        return view('information.abouts.indexabout');
    }
    public function addAbout(Request $request)
    {

        $parafAbout = $request->input('parafAbout');
        $file = $request->file('imageAbout');


        try {
            if ($file) {
                $fileName = $file->getClientOriginalName();
                $filePath = $file->storeAs('public/images', $fileName);
            } else {
                $file = null; // No image was uploaded
            }

            DB::table('tbl_aboutus')->insert([
                'Paraf_AboutUs' => $parafAbout,
                'Image_AboutUs' => $fileName,
                'created_at' => now(),
            ]);

            return response()->json(['status' => 'success', 'message' => 'berhasil ditambahkan'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menambahkan : ' . $e->getMessage()], 500);
        }
    }


}
