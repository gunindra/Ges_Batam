<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Information; // Import model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InformationsController extends Controller
{
    public function index()
    {
        return view('content.informations.indexinformation');
    }

    public function getlistInformations(Request $request)
    {
        $data = Information::all();

        $output = '<table class="table align-items-center table-flush table-hover" id="tableInformations">
                        <thead class="thead-light">
                            <tr>
                                <th>Title</th>
                                <th>Content</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>';
        foreach ($data as $item) {
            $output .= '
                <tr>
                    <td class="">' . ($item->title_informations ?? '-') . '</td>
                    <td class="">' . ($item->content_informations ?? '-') . '</td>
                    <td class=""><img src="' . asset($item->image_url) . '" alt="Gambar" width="100px" height="100px"></td>
                    <td>
                        <a class="btn btnUpdateInformations btn-sm btn-secondary text-white" data-id="' . $item->id . '" data-title_informations="' . $item->title_informations . '" data-content_informations="' . $item->content_informations . '" data-image_informations="' . $item->image_informations . '"><i class="fas fa-edit"></i></a>
                        <a class="btn btnDestroyInformations btn-sm btn-danger text-white" data-id="' . $item->id . '"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            ';
        }
        $output .= '</tbody></table>';
        return $output;
    }

    public function addInformations(Request $request)
    {
        $request->validate([
            'imageInformations' => 'nullable|mimes:jpg,jpeg,png|',
        ]);

        $information = new Information();
        $information->title_informations = $request->input('titleInformations');
        $information->content_informations = $request->input('contentInformations');

        if ($request->hasFile('imageInformations')) {
            $uniqueId = uniqid('Information_', true);
            $fileName = $uniqueId . '.' . $request->file('imageInformations')->getClientOriginalExtension();
            $request->file('imageInformations')->storeAs('public/images', $fileName);
            $information->image_informations = $fileName;
        }

        try {
            $checkData = Information::count();

            if ($checkData >= 6) {
                return response()->json(['status' => 'error', 'message' => 'Data tidak bisa ditambahkan lagi, jumlah maksimal 6 data sudah tercapai.'], 400);
            }

            $information->save();

            return response()->json(['status' => 'success', 'message' => 'Berhasil ditambahkan'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menambahkan: ' . $e->getMessage()], 500);
        }
    }

    public function destroyInformations(Request $request)
    {
        $id = $request->input('id');

        try {
            $existingInformation = Information::find($id);

            if ($existingInformation && $existingInformation->image_informations) {
                $existingImagePath = 'public/images/' . $existingInformation->image_informations;
                if (Storage::exists($existingImagePath)) {
                    Storage::delete($existingImagePath);
                }
            }
            $existingInformation->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateInformations(Request $request)
    {
        $request->validate([
            'imageInformations' => 'nullable|mimes:jpg,jpeg,png|',
        ]);
        
        $id = $request->input('id');
        $information = Information::find($id);
        $information->title_informations = $request->input('titleInformations');
        $information->content_informations = $request->input('contentInformations');

        try {
            if ($request->hasFile('imageInformations')) {
                if ($information->image_informations) {
                    $existingImagePath = 'public/images/' . $information->image_informations;
                    if (Storage::exists($existingImagePath)) {
                        Storage::delete($existingImagePath);
                    }
                }
                $uniqueId = uniqid('Information_', true);
                $fileName = $uniqueId . '.' . $request->file('imageInformations')->getClientOriginalExtension();
                $request->file('imageInformations')->storeAs('public/images', $fileName);
                $information->image_informations = $fileName;
            }

            $information->save();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil diupdate'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal Mengupdate Data: ' . $e->getMessage()], 500);
        }
    }
}
