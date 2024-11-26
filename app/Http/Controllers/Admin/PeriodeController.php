<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Periode;
use Yajra\DataTables\DataTables;

class PeriodeController extends Controller
{
    public function index()
    {
        $listStatus = DB::table('tbl_periode')
            ->select('status')
            ->distinct()
            ->get();

        return view('masterdata.periode.indexmasterperiode', [
            'listStatus' => $listStatus,
        ]);
    }

    public function getPeriode(Request $request)
    {
        $startDate = $request->startDate ? date('Y-m-d', strtotime($request->startDate)) : null;
        $endDate = $request->endDate ? date('Y-m-d', strtotime($request->endDate)) : null;
        $query = DB::table('tbl_periode')
            ->select([
                'periode',
                'periode_start',
                'periode_end',
                'status',
                'id'
            ]);
        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($startDate) {
            $query->where('periode_start', '>=', $startDate);
        }
    
        if ($endDate) {
            $query->where('periode_end', '<=', $endDate);
        }

        $query->orderBy('id', 'desc');

        $data = $query->get();

        return DataTables::of($data)
            ->editColumn('periode_start', function ($row) {
                return Carbon::parse($row->periode_start)->format('d F Y');
            })
            ->editColumn('periode_end', function ($row) {
                return Carbon::parse($row->periode_end)->format('d F Y');
            })
            ->editColumn('status', function ($row) {
                $statusBadgeClass = '';
                switch ($row->status) {
                    case 'Open':
                        $statusBadgeClass = 'badge-warning';
                        break;
                    case 'Closed':
                        $statusBadgeClass = 'badge-danger';
                        break;
                    default:
                        $statusBadgeClass = 'badge-secondary';
                        break;
                }

                return '<span class="badge ' . $statusBadgeClass . '">' . $row->status . '</span>';
            })
            ->addColumn('action', function ($row): string {
                return '<a href="#" class="btn btnUpdatePeriode btn-sm btn-secondary" data-id="' . $row->id . '"><i class="fas fa-edit"></i></a>' .
                    '<a href="#" class="btn btnDestroyPeriode btn-sm btn-danger ml-2" data-id="' . $row->id . '"><i class="fas fa-trash"></i></a>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function addPeriode(Request $request)
    {
        $request->validate([
            'periode' => 'required|string',
            'periodeStart' => 'required|date',
            'periodeEnd' => 'required|date',
            'status' => 'required|string',
        ]);

        try {
            $Periode = new Periode();
            $Periode->periode = $request->input('periode');
            $Periode->periode_start = Carbon::createFromFormat('d F Y', $request->input('periodeStart'))->format('Y-m-d');
            $Periode->periode_end = Carbon::createFromFormat('d F Y', $request->input('periodeEnd'))->format('Y-m-d');
            $Periode->status = $request->input('status');
            $Periode->save();

            return response()->json(['success' => 'Data berhasil ditambahkan']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menambahkan', 'message' => $e->getMessage()]);
        }
    }
    public function updatePeriode(Request $request, $id)
    {
        $validated = $request->validate([
            'periode' => 'required|string',
            'periodeStart' => 'required|date',
            'periodeEnd' => 'required|date',
            'status' => 'required|string',
        ]);

        try {
            $Periode = Periode::findOrFail($id);

            $Periode->periode = $request->input('periode');
            $Periode->periode_start = Carbon::parse($request->input('periodeStart'))->format('Y-m-d');
            $Periode->periode_end = Carbon::parse($request->input('periodeEnd'))->format('Y-m-d');
            $Periode->status = $request->input('status');

            $Periode->save(); // Simpan perubahan

            return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui']);
        } catch (\Exception $e) {
            \Log::error('Error updating periode: ' . $e->getMessage());
            return response()->json(['error' => true, 'message' => 'Data gagal diperbarui', 'details' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $Periode = Periode::findOrFail($id);
        return response()->json($Periode);
    }
    public function deletePeriode($id)
    {
        $Periode = Periode::findOrFail($id);

        try {

            $Periode->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function generatePeriode()
    {
        $codeType = "PE";
        $currentYear = date('y');

        $lastPE = Periode::where('periode', 'like', $codeType . $currentYear . '%')
            ->orderBy('periode', 'desc')
            ->first();

        $newSequence = 1;
        if ($lastPE) {
            $lastSequence = intval(substr($lastPE->periode, -4));
            $newSequence = $lastSequence + 1;
        }

        $periode = $codeType . $currentYear . str_pad($newSequence, 4, '0', STR_PAD_LEFT);

        return response()->json([
            'status' => 'success',
            'periode' => $periode
        ]);
    }
}