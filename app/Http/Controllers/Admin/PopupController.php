<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Popup;
use Illuminate\Support\Facades\Storage;

class PopupController extends Controller
{
    public function index()
    {
        $popupData = Popup::first();
        return view('content.popup.indexpopup', compact('popupData'));
    }

    public function addPopup(Request $request)
    {
        $request->validate([
            'titlePopup' => 'required|string|max:255|unique:tbl_popup,title_Popup',
            'paragraphPopup' => 'required|string',
            'linkPopup' => 'required|string|max:255',
            'imagePopup' => $request->hasFile('imagePopup') ? 'nullable|mimes:jpg,jpeg,png' : '',
        ]);
        $titlePopup = $request->input('titlePopup');
        $paragraphPopup = $request->input('paragraphPopup');
        $linkPopup = $request->input('linkPopup');
        $imagePopup = $request->file('imagePopup');

        try {
            $existingData = Popup::first();
            $fileName = $existingData ? $existingData->Image_Popup : null;

            if ($imagePopup) {
                if ($existingData && $existingData->Image_Popup) {
                    $existingImagePath = 'public/images/' . $existingData->Image_Popup;
                    if (Storage::exists($existingImagePath)) {
                        Storage::delete($existingImagePath);
                    }
                }
                $uniqueId = uniqid('Popup_', true);
                $fileName = $uniqueId . '.' . $imagePopup->getClientOriginalExtension();
                $imagePopup->storeAs('public/images', $fileName);
            } 

            Popup::updateOrCreate(
                ['id' => $existingData ? $existingData->id : null],
                [
                    'title_Popup' =>$titlePopup ,
                    'Paragraph_Popup' =>$paragraphPopup ,
                    'Link_Popup' => $linkPopup,
                    'Image_Popup' => $fileName,
                ]
            );

            return response()->json(['status' => 'success', 'message' => 'Data berhasil disimpan', 'data' => ['imagePopup' =>$fileName, 'titlePopup' => $titlePopup, 'paragraphPopup' => nl2br(e($paragraphPopup)), 'linkPopup' => $linkPopup]]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }


    public function destroyPopup($id)
    {

        if (!$id) {
            return response()->json(['status' => 'warning', 'message' => 'Tidak dapat menghapus data.'], 400);
        }

        try {
            $popup = Popup::find($id);

            if ($popup) {

                if ($popup->Image_Popup) {
                    $existingImagePath = 'public/images/' . $popup->Image_Popup;

                    if (Storage::exists($existingImagePath)) {
                        Storage::delete($existingImagePath);
                    }
                }

                $popup->delete();

                return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Data tidak ditemukan'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }
}
