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
            'titlePopup' => 'required|string|max:255', 
            'paragraphPopup' => 'required|string', 
            'linkPopup' => 'required|string|max:255',
            'imagePopup' => $request->hasFile('imagePopup') ? 'nullable|mimes:jpg,jpeg,png' : '',
        ]);
    
        $titlePopup = $request->input('titlePopup');
        $paragraphPopup = $request->input('paragraphPopup');
        $linkPopup = $request->input('linkPopup');
        $imagePopup = $request->file('imagePopup');
    
        try {
            $existingData = DB::table('tbl_popup')->first();
            $fileName = $existingData ? $existingData->Image_Popup : null;
    
            if ($imagePopup) {
                  if ($fileName && Storage::exists('public/images/' . $fileName)) {
                    Storage::delete('public/images/' . $fileName);
                }
    
                $uniqueId = uniqid('Popup_', true);
                $fileName = $uniqueId . '.' . $imagePopup->getClientOriginalExtension();
                $imagePopup->storeAs('public/images', $fileName);
            }
    
            if ($existingData) {
                DB::table('tbl_popup')->update([
                    'title_Popup' => $titlePopup,
                    'Paragraph_Popup' => $paragraphPopup,
                    'Link_Popup' => $linkPopup,
                    'Image_Popup' => $fileName,  
                    'updated_at' => now(),
                ]);
                $id = $existingData->id;
            } else {
                $id = DB::table('tbl_popup')->insertGetId([
                    'title_Popup' => $titlePopup,
                    'Paragraph_Popup' => $paragraphPopup,
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
                    'titlePopup' => $popupData->title_Popup,
                    'paragraphPopup' => $popupData->Paragraph_Popup,
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
