<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pembagi;
use App\Models\Rate;
class PembagirateController extends Controller
{

    public function index()
    {
        return view('masterdata.pembagirate.indexpembagirate');
    }
    public function getlistPembagi(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';

        $data = DB::table('tbl_pembagi')->select('id', 'nilai_pembagi')->get();


        // dd($q);



        $output = '  <table class="table align-items-center table-flush table-hover" id="tablePembagi">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nilai</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>';
        $no = 1;
        foreach ($data as $item) {
            $output .=
                '
                <tr>
                    <td class="">' . $no++ . '</td>
                     <td class="">' . (isset($item->nilai_pembagi) ? ' ' . number_format($item->nilai_pembagi, 0, '.', ',') : '-') . '</td>
                   <td>
                        <a  class="btn btnUpdatePembagi btn-sm btn-secondary text-white" data-id="' . $item->id . '" data-nilai_pembagi="' . $item->nilai_pembagi . '"><i class="fas fa-edit"></i></a>
                        <a  class="btn btnDestroyPembagi btn-sm btn-danger text-white" data-id="' . $item->id . '" ><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            ';
        }

        $output .= '</tbody></table>';
        return $output;
    }
    public function addPembagi(Request $request)
    {
        $request->validate([
            'nilaiPembagi' => 'required|numeric',
        ]);
        try {
            $Pembagi = new Pembagi();
            $Pembagi->nilai_pembagi = $request->input('nilaiPembagi');


            $Pembagi->save();

            return response()->json(['success' => 'Berhasil ditambahkan']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menambahkan']);
        }
    }
    public function destroyPembagi($id)
    {
        $Pembagi = Pembagi::findOrFail($id);

        try {

            $Pembagi->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function updatePembagi(Request $request, $id)
    {
        $validated = $request->validate([
            'nilaiPembagi' => 'required|numeric',
        ]);
        try {
            $Pembagi = Pembagi::findOrFail($id);
            $Pembagi->nilai_pembagi = $request->input('nilaiPembagi');


            $Pembagi->update($validated);


            return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui']);
        } catch (\Exception $e) {
            return response()->json(['error' => false, 'message' => 'Data gagal diperbarui']);
        }
    }

    public function show($id)
    {
        $Pembagi = Pembagi::findOrFail($id);
        return response()->json($Pembagi);
    }
    public function getlistRate(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';

        $data = DB::table('tbl_rate')
            ->select('id', 'nilai_rate', 'rate_for')
            ->get();

        $output = '<table class="table align-items-center table-flush table-hover" id="tableRate">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Nilai</th>
                                <th>For</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>';

        $no = 1;
        foreach ($data as $item) {
            $output .= '
                <tr>
                    <td class="">' . $no++ . '</td>
                    <td class="">' . (isset($item->nilai_rate) ? number_format($item->nilai_rate, 0, '.', ',') : '-') . '</td>
                    <td class="">' . $item->rate_for . '</td>
                    <td>
                        <a class="btn btnUpdateRate btn-sm btn-secondary text-white" data-id="' . $item->id . '" data-nilai_rate="' . $item->nilai_rate . '" data-rate_for="' . $item->rate_for . '"><i class="fas fa-edit"></i></a>
                        <a class="btn btnDestroyRate btn-sm btn-danger text-white" data-id="' . $item->id . '"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>';
        }

        $output .= '</tbody></table>';

        return $output;
    }

    public function addRate(Request $request)
    {
        $request->validate([
            'nilaiRate' => 'required|numeric',
            'forRate' => 'required|in:Berat,Volume',
        ]);
        try {
            $Rate = new Rate();
            $Rate->nilai_rate = $request->input('nilaiRate');
            $Rate->rate_for = $request->input('forRate');

            $Rate->save();

            return response()->json(['success' => 'berhasil ditambahkan']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menambahkan']);
        }
    }
    public function destroyRate($id)
    {
        try {
            $Rate = Rate::findOrFail($id);

            $Rate->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function updateRate(Request $request, $id)
    {
        $validated = $request->validate([
            'nilaiRate' => 'required|numeric',
            'forRate' => 'required|in:Berat,Volume',
        ]);
        try {
            $Rate = Rate::findOrFail($id);
            $Rate->nilai_rate = $request->input('nilaiRate');
            $Rate->rate_for = $request->input('forRate');


            $Rate->update($validated);

            return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui']);
        } catch (\Exception $e) {
            return response()->json(['error' => false, 'message' => 'Data gagal diperbarui']);
        }
    }
    public function showRate($id)
    {
        $Rate = Rate::findOrFail($id);
        return response()->json($Rate);
    }

}
