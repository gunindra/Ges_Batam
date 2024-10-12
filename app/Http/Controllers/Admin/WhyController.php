<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PTges; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WhyController extends Controller
{
    public function index()
    {
        $whyData = PTges::first();
        return view('content.whys.indexwhy', compact('whyData'));
    }

    public function addWhy(Request $request)
    {
        $request->validate([
            'imageWhy' => 'nullable|mimes:jpg,jpeg,png',
            'contentWhy' => 'required|string',
        ]);

        $contentWhy = $request->input('contentWhy');
        $imageWhy = $request->file('imageWhy');

        try {
            $existingData = PTges::first();
            $fileName = $existingData ? $existingData->Image_WhyUs : null;

            if ($imageWhy) {
                if ($existingData && Storage::exists('public/images/' . $existingData->Image_WhyUs)) {
                    Storage::delete('public/images/' . $existingData->Image_WhyUs);
                }

                $uniqueId = uniqid('WhyUs_', true);
                $fileName = $uniqueId . '.' . $imageWhy->getClientOriginalExtension();
                $imageWhy->storeAs('public/images', $fileName);
            }

           
            PTges::updateOrCreate(
                ['id' => $existingData ? $existingData->id : null],
                [
                    'Paragraph_WhyUs' => $contentWhy,
                    'Image_WhyUs' => $fileName,
                ]
            );

            return response()->json(['status' => 'success', 'message' => 'Data berhasil disimpan', 'data' => ['imageWhy' => $fileName, 'contentWhy' => nl2br( e($contentWhy))]], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }
}
