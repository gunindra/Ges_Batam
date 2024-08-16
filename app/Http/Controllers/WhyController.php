<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WhyController extends Controller
{
    public function index()
    {
        // Get existing about data
        $whyData = DB::table('tbl_whyus')->first();
        return view('information.whys.indexwhy', compact('whyData'));
    }

    public function addWhy(Request $request)
    {
        $parafWhy = $request->input('parafWhy');
        $file = $request->file('imageWhy');

        try {
            $fileName = null;
            if ($file) {
                $fileName = $file->getClientOriginalName();
                $file->storeAs('public/images', $fileName);
            }

            $existingData = DB::table('tbl_whyus')->first();

            if ($existingData) {
                DB::table('tbl_whyus')->update([
                    'Paraf_WhyUs' => $parafWhy,
                    'Image_WhyUs' => $fileName,
                    'updated_at' => now(),
                ]);
            } else {
                // Insert new record
                DB::table('tbl_aboutus')->insert([
                    'Paraf_WhyUs' => $parafWhy,
                    'Image_WhyUs' => $fileName,
                    'created_at' => now(),
                ]);
            }

            return response()->json(['status' => 'success', 'message' => 'Data berhasil disimpan'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }
}