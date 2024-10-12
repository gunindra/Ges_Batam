<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use DB;
use Exception;
use Illuminate\Http\Request;
use Storage;
use App\Models\Tracking;
use Yajra\DataTables\Facades\DataTables;

class TrackingsController extends Controller
{
    public function index(){


        return view('Tracking.indextracking');
    }
    public function getTrackingData(Request $request)
    {
        // Build the query
        $query = DB::table('tbl_tracking as a')
            ->select([
                'a.no_resi',
                'a.no_do',
                'a.status',
                'a.keterangan',
                'a.id'
            ])
            ->limit(100);
    
        $query->orderBy('a.id', 'desc');
    
        $data = $query->get();

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                return '<a href="#" class="btn btnUpdateTracking btn-sm btn-secondary" data-id="' . $row->id . '"><i class="fas fa-edit"></i></a>' .
                       '<a href="#" class="btn btnDestroyTracking btn-sm btn-danger ml-2" data-id="' . $row->id . '"><i class="fas fa-trash"></i></a>';
            })
            ->rawColumns(['action'])
            ->make(true);
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
