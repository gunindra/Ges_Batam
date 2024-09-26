<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdvertisementController extends Controller
{
    public function index()
    {
        return view('content.advertisement.indexadvertisement');
    }

    public function getlistAdvertisement(Request $request)
    {
        $data = Advertisement::all();

        $output = '<table class="table align-items-center table-flush table-hover" id="tableAdvertisement">
                        <thead class="thead-light">
                            <tr>
                                <th>Title</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>';
        foreach ($data as $item) {
            $imagePath = Storage::url('images/' . $item->image_Advertisement);

            $output .= '
                <tr>
                    <td class="">' . ($item->title_Advertisement ?? '-') . '</td>
                    <td class=""><img src="' . asset($imagePath) . '" alt="Gambar" width="100px" height="100px"></td>
                    <td>
                        <a class="btn btnUpdateAdvertisement btn-sm btn-secondary text-white" data-id="' . $item->id . '" data-title_Advertisement="' . $item->title_Advertisement . '" data-image_Advertisement="' . $item->image_Advertisement . '"><i class="fas fa-edit"></i></a>
                        <a class="btn btnDestroyAdvertisement btn-sm btn-danger text-white" data-id="' . $item->id . '"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            ';
        }
        $output .= '</tbody></table>';
        return $output;
    }

    public function addAdvertisement(Request $request)
    {
        $request->validate([
            'titleAdvertisement' => 'required|string|max:255', 
            'imageAdvertisement' => 'nullable|mimes:jpg,jpeg,png,svg|', 
        ]);

        $checkData = Advertisement::count();
        if ($checkData >= 7) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak bisa ditambahkan lagi, jumlah maksimal 7 data sudah tercapai.'], 400);
        }

        $advertisement = new Advertisement();
        $advertisement->title_Advertisement = $request->input('titleAdvertisement');

        if ($request->hasFile('imageAdvertisement')) {
            $uniqueId = uniqid('Advertisement_', true);
            $fileName = $uniqueId . '.' . $request->file('imageAdvertisement')->getClientOriginalExtension();
            $request->file('imageAdvertisement')->storeAs('public/images', $fileName);
            $advertisement->image_Advertisement = $fileName;
        }

        try {
            $advertisement->save();
            return response()->json(['status' => 'success', 'message' => 'Berhasil ditambahkan'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menambahkan: ' . $e->getMessage()], 500);
        }
    }

    public function destroyAdvertisement(Request $request)
    {
        $id = $request->input('id');

        try {
            $advertisement = Advertisement::findOrFail($id);

            if ($advertisement->image_Advertisement) {
                $existingImagePath = 'public/images/' . $advertisement->image_Advertisement;
                if (Storage::exists($existingImagePath)) {
                    Storage::delete($existingImagePath);
                }
            }
            $advertisement->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateAdvertisement(Request $request)
    {
        $request->validate([
            'titleAdvertisement' => 'required|string|max:255', 
            'imageAdvertisement' => 'nullable|mimes:jpg,jpeg,png,svg|', 
        ]);

        $id = $request->input('id');
        $advertisement = Advertisement::findOrFail($id);
        $advertisement->title_Advertisement = $request->input('titleAdvertisement');

        try {
            if ($request->hasFile('imageAdvertisement')) {
                if ($advertisement->image_Advertisement) {
                    $existingImagePath = 'public/images/' . $advertisement->image_Advertisement;
                    if (Storage::exists($existingImagePath)) {
                        Storage::delete($existingImagePath);
                    }
                }
                $uniqueId = uniqid('Advertisement_', true);
                $fileName = $uniqueId . '.' . $request->file('imageAdvertisement')->getClientOriginalExtension();
                $request->file('imageAdvertisement')->storeAs('public/images', $fileName);
                $advertisement->image_Advertisement = $fileName;
            }

            $advertisement->save();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil diupdate'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal Mengupdate Data: ' . $e->getMessage()], 500);
        }
    }
}
