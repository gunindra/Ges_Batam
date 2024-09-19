<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CategoryController extends Controller
{
    public function index()
    {
        return view('masterdata.category.indexmastercategory');
    }
    public function getlistCategory(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';

        $q = "SELECT id,
                        category_name,
                        minimum_rate,
                        maximum_rate
                FROM tbl_category
        ";

        // dd($q);

        $data = DB::select($q);

        $output = '  <table class="table align-items-center table-flush table-hover" id="tableCategory">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Nama</th>
                                        <th>Minimum Rate</th>
                                        <th>Maximum Rate</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>';
        foreach ($data as $item) {

            $output .=
                '
                <tr>
                    <td class="">' . ($item->category_name ?? '-') .'</td>
                    <td class="">' . (isset($item->minimum_rate) ? ' ' . number_format($item->minimum_rate,0, '.', ',') : '-') . '</td>
                    <td class="">' . (isset($item->maximum_rate) ? ' ' . number_format($item->maximum_rate,0, '.', ',') : '-') . '</td>
                    <td>
                        <a  class="btn btnUpdateCategory btn-sm btn-secondary text-white" data-id="' .$item->id .'" data-category_name="' .$item->category_name .'" data-minimum_rate="' .$item->minimum_rate .'" data-maximum_rate="' .$item->maximum_rate .'" ><i class="fas fa-edit"></i></a>
                    </td>
                </tr>
            ';
        }

        $output .= '</tbody></table>';
         return $output;
    }
    public function addCategory(Request $request)
    {

        $namaCategory = $request->input('namaCategory');
        $minimumRateCategory = $request->input('minimumRateCategory');
        $maximumRateCategory = $request->input('maximumRateCategory');
        try {
           
            DB::table('tbl_category')->insert([
                'category_name' => $namaCategory,
                'minimum_rate' => $minimumRateCategory,
                'maximum_rate' => $maximumRateCategory,
                'created_at' => now(),
            ]);

            return response()->json(['status' => 'success', 'message' => 'Berhasil ditambahkan'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menambahkan: ' . $e->getMessage()], 500);
        }
    }
    public function updateCategory(Request $request)
    {
        $id = $request->input('id');
        $namaCategory = $request->input('namaCategory');
        $minimumRateCategory = $request->input('minimumRateCategory');
        $maximumRateCategory = $request->input('maximumRateCategory');

        try {
            $dataUpdate = [
                'category_name' => $namaCategory,
                'minimum_rate' => $minimumRateCategory,
                'maximum_rate' => $maximumRateCategory,
                'updated_at' => now(),
            ];

            DB::table('tbl_category')
                ->where('id', $id)
                ->update($dataUpdate);

            return response()->json(['status' => 'success', 'message' => 'Data berhasil diupdate'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal Mengupdate Data: ' . $e->getMessage()], 500);
        }
    }

    public function destroyCategory(Request $request)
    {
        $id = $request->input('id');

        try {
            DB::table('tbl_category')
                ->where('id', $id)
                ->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

}