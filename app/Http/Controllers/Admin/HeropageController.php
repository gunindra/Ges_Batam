<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeropageController extends Controller
{
    public function index()
    {
        return view('content.heropage.indexheropage');
    }

    public function getlistHeroPage()
    {
        $data = HeroPage::all();

        $output = '<table class="table align-items-center table-flush table-hover" id="tableHeropage">
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
            $imagePath = Storage::url('images/' . $item->image_heropage);

            $output .= '
                <tr>
                    <td>' . ($item->title_heropage ?? '-') . '</td>
                    <td>' . nl2br(e($item->content_heropage ?? '-')) . '</td>
                    <td><img src="' . asset($imagePath) . '" alt="Image" width="100px" height="100px"></td>
                    <td>
                        <a class="btn btnUpdateHeroPage btn-sm btn-secondary text-white" data-id="' . $item->id . '" data-title_heropage="' . $item->title_heropage . '" data-content_heropage="' . $item->content_heropage . '" data-image_heropage="' . $item->image_heropage . '"><i class="fas fa-edit"></i></a>
                        <a class="btn btnDestroyHeroPage btn-sm btn-danger text-white" data-id="' . $item->id . '"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            ';
        }
        $output .= '</tbody></table>';
        return $output;
    }

    public function addHeroPage(Request $request)
    {
        $request->validate([
            'titleHeroPage' => 'required|string|max:255|unique:tbl_heropage,title_heropage',
            'contentHeroPage' => 'required|string',
            'imageHeroPage' => 'nullable|mimes:jpg,jpeg,png|',
        ]);
        try {
            $heroPage = new HeroPage();
            $heroPage->title_heropage = $request->input('titleHeroPage');
            $heroPage->content_heropage = $request->input('contentHeroPage');

            if ($request->hasFile('imageHeroPage')) {
                $uniqueId = uniqid('Heropage_', true);
                $fileName = $uniqueId . '.' . $request->file('imageHeroPage')->getClientOriginalExtension();
                $request->file('imageHeroPage')->storeAs('public/images', $fileName);
                $heroPage->image_heropage = $fileName;
            }

            $heroPage->save();
            return response()->json(['success' => 'Berhasil ditambahkan']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menambahkan']);
        }
    }

    public function destroyHeroPage($id)
    {
        try {
            $heroPage = HeroPage::findOrFail($id);

            if ($heroPage->image_heropage) {
                $existingImagePath = 'public/images/' . $heroPage->image_heropage;
                if (Storage::exists($existingImagePath)) {
                    Storage::delete($existingImagePath);
                }
            }
            $heroPage->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateHeroPage(Request $request, $id)
    {
        $validated = $request->validate([
            'titleHeroPage' => 'required|string|max:255',
            'contentHeroPage' => 'required|string',
            'imageHeroPage' => 'nullable|mimes:jpg,jpeg,png',
        ]);
        try {
            $heroPage = HeroPage::findOrFail($id);
            $heroPage->title_heropage = $request->input('titleHeroPage');
            $heroPage->content_heropage = $request->input('contentHeroPage');

            if ($request->hasFile('imageHeroPage')) {
                if ($heroPage->image_heropage) {
                    $existingImagePath = 'public/images/' . $heroPage->image_heropage;
                    if (Storage::exists($existingImagePath)) {
                        Storage::delete($existingImagePath);
                    }
                }
                $uniqueId = uniqid('Heropage_', true);
                $fileName = $uniqueId . '.' . $request->file('imageHeroPage')->getClientOriginalExtension();
                $request->file('imageHeroPage')->storeAs('public/images', $fileName);
                $heroPage->image_heropage = $fileName;
            }

            $heroPage->update($validated);

            return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui']);
        } catch (\Exception $e) {
            return response()->json(['error' => false, 'message' => 'Data gagal diperbarui']);
        }

    }
    public function show($id)
    {
        $heroPage = HeroPage::findOrFail($id);
        return response()->json($heroPage);
    }
}
