<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Jobs\AddTrackingJob;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
        $user = auth()->user();

        $query = DB::table('tbl_tracking')
            ->select([
                'tbl_tracking.id',
                'tbl_tracking.no_resi',
                'tbl_tracking.no_do',
                'tbl_tracking.status',
                'tbl_tracking.keterangan'
            ])
            ->where('tbl_tracking.company_id', $companyId);

        if ($user->role === 'customer') {
            $query->join('tbl_resi', 'tbl_tracking.no_resi', '=', 'tbl_resi.no_resi')
                  ->join('tbl_invoice', 'tbl_resi.invoice_id', '=', 'tbl_invoice.id')
                  ->join('tbl_pembeli', 'tbl_invoice.pembeli_id', '=', 'tbl_pembeli.id')
                  ->where('tbl_pembeli.user_id', $user->id);
        }

        if ($request->status) {
            $query->where('tbl_tracking.status', $request->status);
        }

        $query->orderBy('tbl_tracking.id', 'desc');

        $data = $query->get();
        $allIds = $data->pluck('id')->toArray();

        return DataTables::of($data)
            ->with(['allIds' => $allIds])
            ->addColumn('select', function ($row) {
                if ($row->status === "Dalam Perjalanan") {
                    return '<input type="checkbox" class="select-row" data-id="' . $row->id . '">';
                }
                return '';
            })
            ->editColumn('status', function ($row) {
                $statusBadgeClass = match ($row->status) {
                    'Dalam Perjalanan' => 'badge-success',
                    'Batam / Sortir' => 'badge-primary',
                    'Delivering' => 'badge-success',
                    'Ready For Pickup' => 'badge-warning',
                    default => 'badge-secondary',
                };
                return '<span class="badge ' . $statusBadgeClass . '">' . $row->status . '</span>';
            })
            ->addColumn('action', function ($row) {
                $deleteButton = $row->status == 'Dalam Perjalanan' ?
                    '<a href="#" class="btn btnDestroyTracking btn-sm btn-danger ml-2" data-id="' . $row->id . '"><i class="fas fa-trash"></i></a>' : '';
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
            $jobId = Str::uuid()->toString();
            $noResiList = $request->input('noResi');
            $chunkSize = 200;
            $chunks = array_chunk($noResiList, $chunkSize);
            $totalChunks = count($chunks);

            foreach ($chunks as $index => $chunk) {
                AddTrackingJob::dispatch([
                    'noResi' => $chunk,
                    'noDeliveryOrder' => $request->input('noDeliveryOrder'),
                    'status' => $request->input('status'),
                    'keterangan' => $request->input('keterangan'),
                ], $companyId, $jobId, $index, $totalChunks);
            }

            return response()->json(['success' => 'Data is being processed', 'jobId' => $jobId]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to add data', 'message' => $e->getMessage()], 500);
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

        if ($Tracking->status != "Dalam Perjalanan") {
            return response()->json([
                'status' => 'error',
                'message' => 'Tracking hanya bisa dihapus jika Status Dalam perjalanan silahkan merefresh halaman untuk mengupdate data'
            ], 400);
        }

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
