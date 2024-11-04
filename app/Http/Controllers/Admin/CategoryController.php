<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return view('masterdata.category.indexmastercategory');
    }
    public function getlistCategory(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';

        $data = DB::table('tbl_category')
            ->select('id', 'category_name', 'minimum_rate', 'maximum_rate')
            ->get();

        $output = '
            <table class="table align-items-center table-flush table-hover" id="tableCategory">
                <thead class="thead-light">
                    <tr>
                        <th>Name</th>
                        <th>Minimum Rate</th>
                        <th>Maximum Rate</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($data as $item) {
            $output .= '
                <tr>
                    <td>' . ($item->category_name ?? '-') . '</td>
                    <td>' . ($item->minimum_rate ? number_format((float) $item->minimum_rate, 0, '.', ',') : '-') . '</td>
                    <td>' . ($item->maximum_rate ? number_format((float) $item->maximum_rate, 0, '.', ',') : '-') . '</td>
                    <td>
                        <a class="btn btnUpdateCategory btn-sm btn-secondary text-white" 
                           data-id="' . $item->id . '" 
                           data-category_name="' . $item->category_name . '" 
                           data-minimum_rate="' . $item->minimum_rate . '" 
                           data-maximum_rate="' . $item->maximum_rate . '">
                           <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>';
        }

        $output .= '</tbody></table>';
        return $output;
    }

    public function addCategory(Request $request)
    {
        $request->validate([
            'nameCategory' => 'required|string|max:255',
            'minimumRateCategory' => 'required|numeric|min:0',
            'maximumRateCategory' => 'required|numeric|min:0|gte:minimumRateCategory',
        ]);
        try {
            $Category = new Category();
            $Category->category_name = $request->input('nameCategory');
            $Category->minimum_rate = $request->input('minimumRateCategory');
            $Category->maximum_rate = $request->input('maximumRateCategory');

            $Category->save();

            return response()->json(['success' => 'Berhasil ditambahkan']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menambahkan']);
        }
    }
    public function updateCategory(Request $request, $id)
    {
        $validated = $request->validate([
            'nameCategory' => 'required|string|max:255',
            'minimumRateCategory' => 'required|numeric|min:0',
            'maximumRateCategory' => 'required|numeric|min:0|gte:minimumRateCategory',
        ]);
        try {
        $Category = Category::findOrFail($id);
        $Category->category_name = $request->input('nameCategory');
        $Category->minimum_rate = $request->input('minimumRateCategory');
        $Category->maximum_rate = $request->input('maximumRateCategory');

      
            $Category->update($validated);

            return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui']);
        } catch (\Exception $e) {
            return response()->json(['error' => false, 'message' => 'Data gagal diperbarui']);
        }
    }

    public function destroyCategory($id)
    {

        try {
            $Category = Category::findOrFail($id);

            $Category->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function show($id)
    {
        $Category = Category::findOrFail($id);
        return response()->json($Category);
    }

}