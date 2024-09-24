<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;

class HeropageController extends Controller
{
    public function index()
    {
        return view('content.heropage.indexheropage');
    }
    
    public function getlistHeroPage(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';

        $q = "SELECT id,
                        title_heropage,
                        content_heropage,
                        image_heropage
                FROM tbl_heropage
        ";

        // dd($q);

        $data = DB::select($q);

        $output = '  <table class="table align-items-center table-flush table-hover" id="tableHeropage">
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

            $image = $item->image_heropage;
            $imagepath = Storage::url('images/' . $image);
            $output .=
                '
                <tr>
                    <td class="">' . ($item->title_heropage ?? '-') .'</td>
                    <td class="">' . ($item->content_heropage ?? '-') .'</td>
                    <td class=""><img src="' . asset($imagepath) . '" alt="Image" width="100px" height="100px"></td>
                   <td>
                        <a  class="btn btnUpdateHeroPage btn-sm btn-secondary text-white" data-id="' .$item->id.'" data-title_heropage="' .$item->title_heropage.'" data-content_heropage="' .$item->content_heropage.'" data-image_heropage="' .$item->image_heropage.'"><i class="fas fa-edit"></i></a>
                        <a  class="btn btnDestroyHeroPage btn-sm btn-danger text-white" data-id="' .$item->id.'" ><i class="fas fa-trash"></i></a>
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
            'titleHeroPage' => 'required|string|max:255', 
            'contentHeroPage' => 'required|string', 
            'imageHeroPage' => 'nullable|mimes:jpg,jpeg,png|', 
        ]);

        $titleHeroPage = $request->input('titleHeroPage');
        $contentHeroPage = $request->input('contentHeroPage');
        $imageHeroPage = $request->file('imageHeroPage');

        try {
            if ($imageHeroPage) {
                $uniqueId = uniqid('Heropage_', true);
                $fileName = $uniqueId . '.' . $imageHeroPage->getClientOriginalExtension();
                $imageHeroPage->storeAs('public/images', $fileName);
            } else {
                $fileName = null;
            }
            DB::table('tbl_heropage')->insert([
                'title_heropage' => $titleHeroPage,
                'content_heropage' => $contentHeroPage,
                'image_heropage' => $fileName,
                'created_at' => now(),
            ]);

            return response()->json(['status' => 'success', 'message' => 'berhasil ditambahkan'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menambahkan : ' . $e->getMessage()], 500);
        }
    }

    public function destroyHeroPage(Request $request)
    {
        $id = $request->input('id');

        try {
            $existingHeropage = DB::table('tbl_heropage')->where('id', $id)->first();

            if ($existingHeropage && $existingHeropage->image_heropage) {
                $existingImagePath = 'public/images/' . $existingHeropage->image_heropage;
    
    
                if (Storage::exists($existingImagePath)) {
                    Storage::delete($existingImagePath);
                }
            DB::table('tbl_heropage')
                ->where('id', $id)
                ->delete();
            }
            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function updateHeroPage(Request $request)
    {  
        $request->validate([
            'titleHeroPage' => 'required|string|max:255', 
            'contentHeroPage' => 'required|string', 
            'imageHeroPage' => 'nullable|mimes:jpg,jpeg,png|', 
        ]);

        $id = $request->input('id');
        $titleHeroPage = $request->input('titleHeroPage');
        $contentHeroPage = $request->input('contentHeroPage');
        $imageHeroPage = $request->file('imageHeroPage');

        try {
            $existingHeropage = DB::table('tbl_heropage')->where('id', $id)->first();
            
            $dataUpdate = [
                'title_heropage' => $titleHeroPage,
                'content_heropage' => $contentHeroPage,
                'updated_at' => now(),
            ];
    
            if ($imageHeroPage) {

                if ($existingHeropage && $existingHeropage->image_heropage) {
                    $existingImagePath = 'public/images/' . $existingHeropage->image_heropage;
                    if (Storage::exists($existingImagePath)) {
                        Storage::delete($existingImagePath);
                    }
                }
                

                $uniqueId = uniqid('Heropage_', true);
                $fileName = $uniqueId . '.' . $imageHeroPage->getClientOriginalExtension();
                $imageHeroPage->storeAs('public/images', $fileName);
                $dataUpdate['image_heropage'] = $fileName; 
            }

            DB::table('tbl_heropage')
                ->where('id', $id)
                ->update($dataUpdate);

            return response()->json(['status' => 'success', 'message' => 'Data berhasil diupdate'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal Mengupdate Data: ' . $e->getMessage()], 500);
        }
    }
}