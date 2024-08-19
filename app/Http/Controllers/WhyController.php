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
                // Generate unique file name to prevent conflicts
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/images', $fileName);
            }

            $existingData = DB::table('tbl_whyus')->first();

            if ($existingData) {
                // Update existing record
                $dataToUpdate = [
                    'Paraf_WhyUs' => $parafWhy,
                    'updated_at' => now(),
                ];

                // Only update Image_AboutUs if a new file was uploaded
                if ($fileName) {
                    $dataToUpdate['Image_WhyUs'] = $fileName;
                    // Optionally delete the old file if a new one is uploaded
                    if ($existingData->Image_WhyUs) {
                        Storage::delete('public/images/' . $existingData->Image_WhyUs);
                    }
                }

                DB::table('tbl_whyus')->update($dataToUpdate);
            } else {
                // Insert new record
                DB::table('tbl_whyus')->insert([
                    'Paraf_WhyUs' => $parafWhy,
                    'Image_WhyUs' => $fileName,
                    'created_at' => now(),
                ]);
            }

            // Return updated data for preview
            $updatedData = DB::table('tbl_whyus')->first();

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan',
                'data' => [
                    'parafWhy' => $updatedData->Paraf_WhyUs,
                    'imageWhy' => $updatedData->Image_WhyUs
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }
}