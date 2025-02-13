<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use DB;
use Exception;
use Illuminate\Http\Request;
use Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Tracking;
use Yajra\DataTables\Facades\DataTables;

class TrackingsController extends Controller
{
    public function index()
    {
        $listStatus = DB::table('tbl_tracking')
            ->select('status')
            ->distinct()
            ->get();

        return view('Tracking.indextracking', [
            'listStatus' =>  $listStatus,
            'hasActionColumn' => in_array(Auth::user()->role, ['superadmin', 'admin', 'supervisor'])
        ]);
    }
    public function getTrackingData(Request $request)
    {
        $companyId = session('active_company_id');

        $query = DB::table('tbl_tracking')
            ->select([
                'id',
                'no_resi',
                'no_do',
                'status',
                'keterangan'
            ])
            ->where('tbl_tracking.company_id', $companyId);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $query->orderBy('id', 'desc');

        $data = $query->get();
        $allIds = $data->pluck('id')->toArray(); // Semua ID dari database

        return DataTables::of($data)
            ->with(['allIds' => $allIds]) // Kirim semua ID ke frontend
            ->addColumn('select', function ($row) {
                if ($row->status === "Dalam Perjalanan") {
                    return '<input type="checkbox" class="select-row" data-id="' . $row->id . '">';
                }
                return '';
            })
            ->editColumn('status', function ($row) {
                $statusBadgeClass = '';
                switch ($row->status) {
                    case 'Dalam Perjalanan':
                        $statusBadgeClass = 'badge-success';
                        break;
                    case 'Batam / Sortir':
                        $statusBadgeClass = 'badge-primary';
                        break;
                    case 'Delivering':
                        $statusBadgeClass = 'badge-success';
                        break;
                    case 'Ready For Pickup':
                        $statusBadgeClass = 'badge-warning';
                        break;
                    default:
                        $statusBadgeClass = 'badge-secondary';
                        break;
                }

                return '<span class="badge ' . $statusBadgeClass . '">' . $row->status . '</span>';
            })
            ->addColumn('action', function ($row) {
                $deleteButton = '';
                if ($row->status == 'Dalam Perjalanan') {
                    $deleteButton = '<a href="#" class="btn btnDestroyTracking btn-sm btn-danger ml-2" data-id="' . $row->id . '"><i class="fas fa-trash"></i></a>';
                }
                return '<a href="#" class="btn btnUpdateTracking btn-sm btn-secondary" data-id="' . $row->id . '"><i class="fas fa-edit"></i></a>' . $deleteButton;
            })
            ->rawColumns(['select', 'status', 'action'])
            ->make(true);
    }


    public function addTracking(Request $request)
    {
        $companyId = session('active_company_id');
        $request->validate([
            'noResi' => 'required|array|min:1',
            'noDeliveryOrder' => 'required|string|max:20',
            'status' => 'required|string|max:50',
            'keterangan' => 'nullable|string|max:255',
        ]);

        try {
            foreach ($request->input('noResi') as $resi) {
                $resi = trim($resi);

                $existingTracking = Tracking::where('no_resi', $resi)
                                            ->where('company_id', $companyId)
                                            ->first();

                if ($existingTracking) {
                    return response()->json(['error' => "No Resi {$resi} is already in the system."], 400);
                }
                $Tracking = new Tracking();
                $Tracking->no_resi = $resi;
                $Tracking->no_do = $request->input('noDeliveryOrder');
                $Tracking->status = $request->input('status');
                $Tracking->keterangan = $request->input('keterangan');
                $Tracking->company_id = $companyId;
                $Tracking->save();
            }
            return response()->json(['success' => 'Data successfully added']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to add data'], 500);
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
        $Tracking = Tracking::findOrFail($id);
        return response()->json($Tracking);
    }

    public function deleteTrackingMultipe(Request $request)
    {
        $ids = $request->input('ids');
        $ids = array_map('intval', $ids);

        if (count($ids) > 0) {
            $trackings = Tracking::whereIn('id', $ids)->get();
            $idsToDelete = $trackings->filter(function ($tracking) {
                return $tracking->status === 'Dalam Perjalanan';
            })->pluck('id')->toArray();

            if (count($idsToDelete) > 0) {
                $deletedCount = Tracking::whereIn('id', $idsToDelete)->delete();

                return response()->json(['success' => true, 'message' => "$deletedCount record(s) deleted successfully."]);
            } else {
                return response()->json(['success' => false, 'message' => 'No records with status "Dalam Perjalanan" to delete.']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'No IDs provided']);
        }
    }


}
