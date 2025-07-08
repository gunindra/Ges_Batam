<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\COA;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VendorController extends Controller
{
    public function index()
    {
        $coas = COA::whereNotNull('parent_id')
            ->where('set_as_group', 0)
            ->get();
        return view('masterdata.vendor.indexvendor', [
            'coas' => $coas
        ]);
    }

    public function getVendors(Request $request)
    {
        $companyId = session('active_company_id');

        if ($request->ajax()) {
            // Mengambil data vendor beserta nama akun COA terkait
            $data = Vendor::with('account')
                ->whereHas('account', function ($query) use ($companyId) {
                    $query->where('tbl_vendors.company_id', $companyId);
                })
                ->orderBy('id', 'desc')
                ->get();


            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('phone', function ($row) {
                    return '+62' . ltrim($row->phone, '0');
                })
                ->addColumn('coa_account', function ($row) {
                    // Menampilkan nama akun COA terkait, jika ada
                    return $row->account ? $row->account->name : 'Tidak ada akun';
                })
                ->addColumn('action', function ($row) {
                    $editBtn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="edit btn btn-primary btn-sm editVendor"><i class="fas fa-edit"></i></a>';
                    return $editBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }



    public function store(Request $request)
    {
        $companyId = session('active_company_id');
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required',
                'address' => 'required',
                'phone' => 'required',
                'account_id' => 'required|exists:tbl_coa,id',
            ]);

            Vendor::create([
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
                'account_id' => $request->account_id,
                'company_id'=> $companyId,
            ]);
            DB::commit();
            return response()->json(['success' => 'Vendor Berhasil ditambahkan!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }


    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'account_id' => 'required|exists:tbl_coa,id',
        ]);

        $vendor = Vendor::find($id);
        if ($vendor) {
            $vendor->update([
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
                'account_id' => $request->account_id,
            ]);
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Vendor berhasil diperbarui!']);
        } else {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => 'Vendor tidak ditemukan.'], 404);
        }
    }



    public function getVendorById(Request $request)
    {
        if ($request->ajax()) {
            $vendor = Vendor::find($request->id);

            if ($vendor) {
                return response()->json([
                    'status' => 'success',
                    'data' => $vendor
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Vendor tidak ditemukan'
                ]);
            }
        }
    }

}
