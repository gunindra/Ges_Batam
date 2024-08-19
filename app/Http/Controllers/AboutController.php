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
                // Generate unique file name to prevent conflicts
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/images', $fileName);
            }

            $existingData = DB::table('tbl_aboutus')->first();

            if ($existingData) {
                // Update existing record
                $dataToUpdate = [
                    'Paraf_AboutUs' => $parafAbout,
                    'updated_at' => now(),
                ];

                // Only update Image_AboutUs if a new file was uploaded
                if ($fileName) {
                    $dataToUpdate['Image_AboutUs'] = $fileName;
                    // Optionally delete the old file if a new one is uploaded
                    if ($existingData->Image_AboutUs) {
                        Storage::delete('public/images/' . $existingData->Image_AboutUs);
                    }
                }

                DB::table('tbl_aboutus')->update($dataToUpdate);
            } else {
                // Insert new record
                DB::table('tbl_aboutus')->insert([
                    'Paraf_AboutUs' => $parafAbout,
                    'Image_AboutUs' => $fileName,
                    'created_at' => now(),
                ]);
            }

            // Return updated data for preview
            $updatedData = DB::table('tbl_aboutus')->first();

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan',
                'data' => [
                    'parafAbout' => $updatedData->Paraf_AboutUs,
                    'imageAbout' => $updatedData->Image_AboutUs
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }
}

    
