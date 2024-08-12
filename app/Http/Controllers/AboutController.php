<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AboutController extends Controller
{
    public function index()
    {
      
        return view('information.abouts.indexabout');
    }

    public function insertAboutUs(Request $request)
{
    // Validasi data
    $request->validate([
        'paraf' => 'required|string',
    ]);

    // Menyimpan data baru ke database
    DB::table('tbl_aboutus')->insert([
        'Paraf_AboutUs' => $request->input('paraf'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Mengembalikan respons JSON
    return response()->json(['success' => true]);
}

    // Memperbarui data About
    public function updateAboutUs(Request $request)
    {
        // Validasi data
        $request->validate([
            'paraf' => 'required|string',
        ]);

        // Memperbarui data di database
        DB::table('tbl_aboutus')->update([
            'Paraf_AboutUs' => $request->input('paraf'),
        ]);

        // Mengembalikan respons JSON
        return response()->json(['success' => true]);
    }


    public function getlistAbout(Request $request)
    {

        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';

    
          $about = DB::table('tbl_aboutus')->first(); 

    $data = DB::select($about);
    // dd($data);


    $output = '  <table class="table align-items-center table-flush table-hover" id="tableAboutUs">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Paraf AboutUs</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>';
    foreach ($data as $item) {
        $output .=
        '  
        <tr>
            <td class="">' . ($item->Paraf_AboutUs ?? '-') .'</td>
           <td>
                <a  class="btn btnUpdateAboutUs btn-sm btn-secondary text-white" data-id="' .$item->id .'" data-Paraf_AboutUs="' .$item->Paraf_AboutUs .'" data-Image_AboutUs="' .$item->Image_AboutUs .'"><i class="fas fa-edit"></i></a>
                <a  class="btn btnDestroyAboutUs btn-sm btn-danger text-white" data-id="' .$item->id .'" ><i class="fas fa-trash"></i></a>
            </td>
        </tr>
    ';
}
    $output .= '</tbody></table>';
        return $output;

    }
}
    // public function destroyAboutUs(Request $request)
    // {
    //     $id = $request->input('id');

    //     try {
    //         DB::table('tbl_AboutUs')
    //             ->where('id', $id)
    //             ->delete();

    //         return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    //     }
    // }

    

