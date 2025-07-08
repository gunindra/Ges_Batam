<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Information;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InformationsController extends Controller
{
    public function index()
    {
        return view('content.informations.indexinformation');
    }

    public function getlistInformations()
    {
        $data = Information::all();

        $output = '<table class="table align-items-center table-flush table-hover" id="tableInformations">
                        <thead class="thead-light">
                            <tr>
                                <th>Judul</th>
                                <th>Content</th>
                                <th>Gambar</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>';
        foreach ($data as $item) {
            $imagePath = Storage::url('images/' . $item->image_informations);

            $output .= '
                <tr>
                    <td class="">' . ($item->title_informations ?? '-') . '</td>
                    <td class="">' . nl2br(e($item->content_informations ?? '-')) . '</td>
                    <td class=""><img src="' . asset($imagePath) . '" alt="Gambar" width="100px" height="100px"></td>
                    <td>
                        <a class="btn btnUpdateInformations btn-sm btn-secondary text-white" data-id="' . $item->id . '" data-title_informations="' . $item->title_informations . '" data-content_informations="' . e($item->content_informations) . '" data-image_informations="' . $item->image_informations . '"><i class="fas fa-edit"></i></a>
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
        DB::beginTransaction();
        $request->validate([
            'titleInformations' => 'required|string|max:255|unique:tbl_informations,title_informations',
            'contentInformations' => 'required|string|max:1000',
            'imageInformations' => 'nullable|mimes:jpg,jpeg,png,svg',
        ]);

        try {
            $information = new Information();
            $information->title_informations = $request->input('titleInformations');
            $information->content_informations = $request->input('contentInformations');

            if ($request->hasFile('imageInformations')) {
                $uniqueId = uniqid('Information_', true);
                $fileName = $uniqueId . '.' . $request->file('imageInformations')->getClientOriginalExtension();
                $request->file('imageInformations')->storeAs('public/images', $fileName);
                $information->image_informations = $fileName;
            }


            $information->save();
            DB::commit();
            return response()->json(['success' => 'Berhasil ditambahkan']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Gagal menambahkan']);
        }
    }

    public function destroyInformations($id)
    {
        DB::beginTransaction();
        try {
            $information = Information::findOrFail($id);


            if ($information->image_informations) {
                $existingImagePath = 'public/images/' . $information->image_informations;
                if (Storage::exists($existingImagePath)) {
                    Storage::delete($existingImagePath);
                }
            }

            $information->delete();

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }


    public function updateInformations(Request $request, $id)
    {
        DB::beginTransaction();
        $validated = $request->validate([
            'titleInformations' => 'required|string|max:255',
            'contentInformations' => 'required|string|max:1000',
            'imageInformations' => 'nullable|mimes:jpg,jpeg,png,svg',
        ]);
        try {
            $information = Information::findOrFail($id);
            $information->title_informations = $request->input('titleInformations');
            $information->content_informations = $request->input('contentInformations');

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

            $information->update($validated);
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => false, 'message' => 'Data gagal diperbarui']);
        }

    }
    public function show($id)
    {
        $information = Information::findOrFail($id);
        return response()->json($information);
    }

}
