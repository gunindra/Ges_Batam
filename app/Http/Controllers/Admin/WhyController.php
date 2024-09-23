<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WhyController extends Controller
{
    public function index()
    {
        $whyData = DB::table('tbl_whyus')->first();
        return view('content.whys.indexwhy', compact('whyData'));
    }

    public function addWhy(Request $request)
    {
        $request->validate([
            'imageWhy' => 'nullable|mimes:jpg,jpeg,png|', 
            'parafWhy' => 'required|string', 
        ]);
        $parafWhy = $request->input('parafWhy');
        $imageWhy = $request->file('imageWhy');
    
        try {
            $existingData = DB::table('tbl_whyus')->first();
            $fileName = $existingData ? $existingData->Image_WhyUs : null;
    
            if ($imageWhy) {
                if ($fileName && Storage::exists('public/images/' . $fileName)) {
                    Storage::delete('public/images/' . $fileName);
                }
    
                $uniqueId = uniqid('WhyUs_', true);
                $fileName = $uniqueId . '.' . $imageWhy->getClientOriginalExtension();
                $imageWhy->storeAs('public/images', $fileName);
            }
    
            if ($existingData) {
                DB::table('tbl_whyus')->update([
                    'Paraf_WhyUs' => $parafWhy,
                    'Image_WhyUs' => $fileName,
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('tbl_whyus')->insert([
                    'Paraf_WhyUs' => $parafWhy,
                    'Image_WhyUs' => $fileName,
                    'created_at' => now(),
                ]);
            }
    
            return response()->json(['status' => 'success', 'message' => 'Data berhasil disimpan', 'data' => ['imageWhy' => $fileName, 'parafWhy' => $parafWhy]], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }
    
}