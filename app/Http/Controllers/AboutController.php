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
        return view('content.abouts.indexabout', compact('aboutData'));
    }

    public function addAbout(Request $request)
    {
        $parafAbout = $request->input('parafAbout');
        $file = $request->file('imageAbout');
    
        try {
            $fileName = null;
            $existingData = DB::table('tbl_aboutus')->first();
    
            if ($file) {
                // Jika ada gambar baru yang diunggah, simpan gambar tersebut
                $fileName = $file->getClientOriginalName();
                $file->storeAs('public/images', $fileName);
            } elseif ($existingData) {
                // Jika tidak ada gambar baru yang diunggah, gunakan gambar lama
                $fileName = $existingData->Image_AboutUs;
            }
    
            if ($existingData) {
                // Update data yang sudah ada
                DB::table('tbl_aboutus')->update([
                    'Paraf_AboutUs' => $parafAbout,
                    'Image_AboutUs' => $fileName,
                    'updated_at' => now(),
                ]);
            } else {
                // Insert data baru
                DB::table('tbl_aboutus')->insert([
                    'Paraf_AboutUs' => $parafAbout,
                    'Image_AboutUs' => $fileName,
                    'created_at' => now(),
                ]);
            }
    
            return response()->json(['status' => 'success', 'message' => 'Data berhasil disimpan', 'data' => ['imageAbout' => $fileName, 'parafAbout' => $parafAbout]], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }
    
}

    
