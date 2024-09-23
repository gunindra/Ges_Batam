<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    public function index()
    {
        $aboutData = DB::table('tbl_aboutus')->first();
        return view('content.abouts.indexabout', compact('aboutData'));
    }

    public function addAbout(Request $request)
    {
        $request->validate([
            'imageAbout' => 'nullable|mimes:jpg,jpeg,png',
            'parafAbout' => 'required|string',
        ]);
        
        $parafAbout = $request->input('parafAbout');
        $imageAbout = $request->file('imageAbout');
    
        try {
            $existingData = DB::table('tbl_aboutus')->first();
            $fileName = $existingData ? $existingData->Image_AboutUs : null;
    
            if ($imageAbout) {
                if ($fileName && Storage::exists('public/images/' . $fileName)) {
                    Storage::delete('public/images/' . $fileName);
                }
    
                $uniqueId = uniqid('AboutUs_', true);
                $fileName = $uniqueId . '.' . $imageAbout->getClientOriginalExtension();
                $imageAbout->storeAs('public/images', $fileName);
            }
    
            if ($existingData) {
                DB::table('tbl_aboutus')->update([
                    'Paraf_AboutUs' => $parafAbout,
                    'Image_AboutUs' => $fileName,
                    'updated_at' => now(),
                ]);
            } else {
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


