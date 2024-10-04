<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index()
    {
        return view('content.services.indexservice');
    }

    public function getlistService()
    {
        $services = Service::all();

        $output = '<table class="table align-items-center table-flush table-hover" id="tableService">
                        <thead class="thead-light">
                            <tr>
                                <th>Judul</th>
                                <th>Content</th>
                                <th>Gambar</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>';

        foreach ($services as $service) {
            $imagePath = Storage::url('images/' . $service->image_service);
            $output .= '
                <tr>
                    <td>' . ($service->title_service ?? '-') . '</td>
                    <td>' . nl2br(e($service->content_service ?? '-')) . '</td>
                    <td><img src="' . asset($imagePath) . '" alt="Image" width="100px" height="100px"></td>
                    <td>
                        <a class="btn btnUpdateService btn-sm btn-secondary text-white" data-id="' . $service->id . '" data-title_service="' . $service->title_service . '" data-content_service="' . $service->content_service . '" data-image_service="' . $service->image_service . '"><i class="fas fa-edit"></i></a>
                        <a class="btn btnDestroyService btn-sm btn-danger text-white" data-id="' . $service->id . '"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            ';
        }

        $output .= '</tbody></table>';
        return $output;
    }

    public function addService(Request $request)
    {
        $request->validate([
            'titleService' => 'required|string|max:255',
            'contentService' => 'required|string',
            'imageService' => 'nullable|mimes:jpg,jpeg,png',
        ]);
        try {
            $service = new Service();
            $service->title_service = $request->input('titleService');
            $service->content_service = $request->input('contentService');

            if ($request->hasFile('imageService')) {
                $uniqueId = uniqid('Service_', true);
                $fileName = $uniqueId . '.' . $request->file('imageService')->getClientOriginalExtension();
                $request->file('imageService')->storeAs('public/images', $fileName);
                $service->image_service = $fileName;
            }

            $service->save();

            return response()->json(['success' => 'berhasil ditambahkan']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menambahkan']);
        }
    }

    public function destroyService($id)
    {
        try {
            $service = Service::findOrFail($id);

            if ($service->image_service) {
                $existingImagePath = 'public/images/' . $service->image_service;
                if (Storage::exists($existingImagePath)) {
                    Storage::delete($existingImagePath);
                }
            }
            $service->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateService(Request $request, $id)
    {
        $validated = $request->validate([
            'titleService' => 'required|string|max:255',
            'contentService' => 'required|string',
            'imageService' => 'nullable|mimes:jpg,jpeg,png',
        ]);
        try {
            $service = Service::findOrFail($id);
            $service->title_service = $request->input('titleService');
            $service->content_service = $request->input('contentService');

            if ($request->hasFile('imageService')) {
                if ($service->image_service) {
                    $existingImagePath = 'public/images/' . $service->image_service;
                    if (Storage::exists($existingImagePath)) {
                        Storage::delete($existingImagePath);
                    }
                }
                $uniqueId = uniqid('Service_', true);
                $fileName = $uniqueId . '.' . $request->file('imageService')->getClientOriginalExtension();
                $request->file('imageService')->storeAs('public/images', $fileName);
                $service->image_service = $fileName;
            }

            $service->update($validated);

            return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui']);
        } catch (\Exception $e) {
            return response()->json(['error' => false, 'message' => 'Data gagal diperbarui']);
        }
    }
    public function show($id)
    {
        $service = Service::findOrFail($id);
        return response()->json($service);
    }
}
