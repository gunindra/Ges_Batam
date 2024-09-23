<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PopupController extends Controller
{
    public function index()
    {
        $popupData = DB::table('tbl_popup')->first();
        return view('content.popup.indexpopup', compact('popupData'));
    }

    public function addPopup(Request $request)
    {
        $request->validate([
            'judulPopup' => 'required|string|max:255', 
            'parafPopup' => 'required|string', 
            'linkPopup' => 'required|string|max:255',
            'imagePopup' => 'nullable|mimes:jpg,jpeg,png', 
        ]);
    
        $judulPopup = $request->input('judulPopup');
        $parafPopup = $request->input('parafPopup');
        $linkPopup = $request->input('linkPopup');
        $imagePopup = $request->file('imagePopup');
    
        try {
            $existingData = DB::table('tbl_popup')->first();
            $fileName = $existingData ? $existingData->Image_Popup : null;
    
            if ($imagePopup) {
                $uniqueId = uniqid('Popup_', true);
                $fileName = $uniqueId . '.' . $imagePopup->getClientOriginalExtension();
                $imagePopup->storeAs('public/images', $fileName);
            } else {
                $fileName = null;
            }
    
            if ($existingData) {
                DB::table('tbl_popup')->update([
                    'Judul_Popup' => $judulPopup,
                    'Paraf_Popup' => $parafPopup,
                    'Link_Popup' => $linkPopup,
                    'Image_Popup' => $fileName, // Gunakan gambar lama atau baru
                    'updated_at' => now(),
                ]);
                $id = $existingData->id;
            } else {
                $id = DB::table('tbl_popup')->insertGetId([
                    'Judul_Popup' => $judulPopup,
                    'Paraf_Popup' => $parafPopup,
                    'Link_Popup' => $linkPopup,
                    'Image_Popup' => $fileName,
                    'created_at' => now(),
                ]);
            }
            
            $popupData = DB::table('tbl_popup')->where('id', $id)->first();
    
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan',
                'data' => [
                    'id' => $popupData->id,
                    'imagePopup' => $popupData->Image_Popup,
                    'judulPopup' => $popupData->Judul_Popup,
                    'parafPopup' => $popupData->Paraf_Popup,
                    'linkPopup' => $popupData->Link_Popup
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }


    public function destroyPopup(Request $request)
    {
        $id = $request->input('id');

        if (!$id) {
            return response()->json(['status' => 'warning', 'message' => 'Tidak dapat menghapus data.'], 400);
        }

        try {
            $existingPopup = DB::table('tbl_popup')->where('id', $id)->first();

            if ($existingPopup && $existingPopup->Image_Popup) {
                $existingImagePath = 'public/images/' . $existingPopup->Image_Popup;
    
    
                if (Storage::exists($existingImagePath)) {
                    Storage::delete($existingImagePath);
                }
            $deleted = DB::table('tbl_popup')->where('id', $id)->delete();
            }
            if ($deleted) {
                return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Data tidak ditemukan'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }

}
