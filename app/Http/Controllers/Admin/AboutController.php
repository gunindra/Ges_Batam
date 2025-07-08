<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\PTges;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    public function index()
    {
        $aboutData = PTges::first();
        return view('content.abouts.indexabout', compact('aboutData'));
    }

    public function addAbout(Request $request)
    {
        DB::beginTransaction();
        $request->validate([
            'imageAbout' => 'nullable|mimes:jpg,jpeg,png',
            'contentAbout' => 'required|string',
        ]);
    
        $contentAbout = $request->input('contentAbout');
        $imageAbout = $request->file('imageAbout');
    
        try {
            $existingData = PTges::first();
            $fileName = $existingData ? $existingData->Image_AboutUs : null;
    
            if ($imageAbout) {
                
                if ($existingData && Storage::exists('public/images/' . $existingData->Image_AboutUs)) {
                    Storage::delete('public/images/' . $existingData->Image_AboutUs);
                }
    
                $uniqueId = uniqid('AboutUs_', true);
                $fileName = $uniqueId . '.' . $imageAbout->getClientOriginalExtension();
                $imageAbout->storeAs('public/images', $fileName);
            }
    
            PTges::updateOrCreate(
                ['id' => $existingData ? $existingData->id : null],
                [
                    'Paragraph_AboutUs' => $contentAbout,
                    'Image_AboutUs' => $fileName,
                ]
            );
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Data berhasil disimpan', 'data' => ['imageAbout' => $fileName, 'contentAbout' =>  nl2br(e($contentAbout))]], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }
    
    
    }
    
    
    


