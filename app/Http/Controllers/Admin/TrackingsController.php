<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use DB;
use Exception;
use Illuminate\Http\Request;
use Storage;
use App\Models\Tracking;

class TrackingsController extends Controller
{
    public function index(){


        return view('Tracking.indextracking');
    }

    public function getlistTracking (Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';

        $data = DB::table('tbl_tracking')
        ->where(function ($query) use ($txSearch) {
            $query->whereRaw('UPPER(no_resi) LIKE ?', [$txSearch])
                  ->orWhereRaw('UPPER(no_do) LIKE ?', [$txSearch]);
        })
        ->orderBy('id', 'desc')
        ->limit(100)
        ->get();

        $output = '<table class="table align-items-center table-flush table-hover" id="tableTracking">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No. Resi</th>
                                        <th>No. DO</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>';
        foreach ($data as $item) {
            $output .=
                '
                <tr>
                    <td class="">' . ($item->no_resi ?? '-') .'</td>
                    <td class="">' . ($item->no_do ?? '-') .'</td>
                    <td class="">' . ($item->status ?? '-') .'</td>
                    <td class="">' . ($item->keterangan ?? '-') .'</td>
                    <td>
                        <a  class="btn btnUpdateTracking btn-sm btn-secondary text-white" data-id="' .$item->id .'" data-no_resi="' .$item->no_resi .'" data-no_do="' .$item->no_do .'" data-status="' .$item->status .'" data-keterangan="' .$item->keterangan .'"><i class="fas fa-edit"></i></a>
                        <a  class="btn btnDestroyTracking btn-sm btn-danger text-white" data-id="' .$item->id .'" ><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            ';
        }

        $output .= '</tbody></table>';
         return $output;
    }

    public function addTracking(Request $request)
    {
        $request->validate([
            'noResi' => 'required|array|min:1',
            'noDeliveryOrder' => 'required|string|max:20',
            'status' => 'required|string|max:50',
            'keterangan' => 'nullable|string|max:255',
        ]);
    
        try {
            foreach ($request->input('noResi') as $resi) {
                $Tracking = new Tracking();
                $Tracking->no_resi = $resi;
                $Tracking->no_do = $request->input('noDeliveryOrder');
                $Tracking->status = $request->input('status');
                $Tracking->keterangan = $request->input('keterangan');
                $Tracking->save();
            }
    
            return response()->json(['success' => 'Data berhasil ditambahkan']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menambahkan']);
        }
    }
    

    public function updateTracking(Request $request, $id)
    {
       $validated = $request->validate([
            'noDeliveryOrder' => 'required|string|max:20',
            'keterangan' => 'nullable|string|max:255',
        ]);

        try {
            $Tracking = Tracking::findOrFail($id);

            if (!$Tracking) {
                return response()->json(['message' => 'ID Tracking tidak ditemukan'], 400);
            }
            $Tracking->no_do = $request->input('noDeliveryOrder');
            $Tracking->keterangan = $request->input('keterangan');

            $Tracking->update($validated);

            return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui']);
        } catch (\Exception $e) {
            return response()->json(['error' => false, 'message' => 'Data gagal diperbarui']);
        }

    }


    public function deleteTracking($id)
    {
        $Tracking = Tracking::findOrFail($id);

        try {

            $Tracking->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function show($id)
    {
        $Tracking = Tracking::findOrFail( $id);
        return response()->json($Tracking);
    }
}
