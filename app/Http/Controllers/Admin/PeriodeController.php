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
        return view('masterdata.periode.indexmasterperiode');
    }

    public function getPeriode(Request $request)
    {
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


}