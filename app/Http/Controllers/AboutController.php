<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    public function index()
    {
        // Get existing about data
        $aboutData = DB::table('tbl_aboutus')->first();
        return view('information.abouts.indexabout', compact('aboutData'));
    }

    public function addAbout(Request $request)
    {
        $parafAbout = $request->input('parafAbout');
        $file = $request->file('imageAbout');

        try {
            $fileName = null;
            if ($file) {
                $fileName = $file->getClientOriginalName();
                $file->storeAs('public/images', $fileName);
            }

            $existingData = DB::table('tbl_aboutus')->first();

            if ($existingData) {
                // Update existing record
                DB::table('tbl_aboutus')->update([
                    'Paraf_AboutUs' => $parafAbout,
                    'Image_AboutUs' => $fileName,
                    'updated_at' => now(),
                ]);
            } else {
                // Insert new record
                DB::table('tbl_aboutus')->insert([
                    'Paraf_AboutUs' => $parafAbout,
                    'Image_AboutUs' => $fileName,
                    'created_at' => now(),
                ]);
            }

            return response()->json(['status' => 'success', 'message' => 'Data berhasil disimpan'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }
}