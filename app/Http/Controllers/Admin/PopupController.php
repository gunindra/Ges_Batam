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
        DB::beginTransaction();
        $request->validate([
            'titlePopup' => 'required|string|max:255',
            'paragraphPopup' => 'required|string',
            'linkPopup' => 'required|string|max:255',
            'imagePopup' => $request->hasFile('imagePopup') ? 'nullable|mimes:jpg,jpeg,png' : '',
        ]);

        try {
            $existingData = Popup::first();
            $fileName = $existingData ? $existingData->Image_Popup : null;

            if ($request->hasFile('imagePopup')) {
                if ($existingData && $existingData->Image_Popup) {
                    $existingImagePath = 'public/images/' . $existingData->Image_Popup;
                    if (Storage::exists($existingImagePath)) {
                        Storage::delete($existingImagePath);
                    }
                }
                $uniqueId = uniqid('Popup_', true);
                $fileName = $uniqueId . '.' . $request->file('imagePopup')->getClientOriginalExtension();
                $request->file('imagePopup')->storeAs('public/images', $fileName);
            }

            $popup = Popup::updateOrCreate(
                ['id' => $existingData ? $existingData->id : null],
                [
                    'title_Popup' => $request->input('titlePopup'),
                    'Paragraph_Popup' => $request->input('paragraphPopup'),
                    'Link_Popup' => $request->input('linkPopup'),
                    'Image_Popup' => $fileName,
                ]
            );
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Data berhasil disimpan', 'data' => ['id' => $popup->id, 'imagePopup' => $fileName, 'titlePopup' => $request->input('titlePopup'), 'paragraphPopup' => nl2br(e($request->input('paragraphPopup'))), 'linkPopup' => $request->input('linkPopup')]]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    public function destroyPopup($id)
    {

        if (!$id) {
            return response()->json(['status' => 'warning', 'message' => 'Tidak dapat menghapus data.'], 400);
        }
        DB::beginTransaction();
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
                DB::commit();
                return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
            } else {
                DB::rollback();
                return response()->json(['status' => 'info', 'message' => 'Data tidak ditemukan'], 404);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }
}
