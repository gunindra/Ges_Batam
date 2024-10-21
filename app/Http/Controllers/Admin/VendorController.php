<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VendorController extends Controller
{
    public function index()
    {
        return view('masterdata.vendor.indexvendor');
    }

    public function getVendors(Request $request)
    {
        if ($request->ajax()) {
            $data = Vendor::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('phone', function ($row) {

                    return '+62' . ltrim($row->phone, '0');
                })
                ->addColumn('action', function ($row) {
                    $editBtn = '<a href="javascript:void(0)" data-id="'.$row->id.'" class="edit btn btn-primary btn-sm editVendor"><i class="fas fa-edit"></i></a>';
                    return $editBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
        ]);

        Vendor::create([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
        ]);

        return response()->json(['success' => 'Vendor berhasil ditambahkan!']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
        ]);

        $vendor = Vendor::find($id);
        if ($vendor) {
            $vendor->update([
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
            ]);

            return response()->json(['status' => 'success', 'message' => 'Vendor berhasil diperbarui!']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Vendor tidak ditemukan.'], 404);
        }
    }


    public function getVendorById(Request $request)
{
    if ($request->ajax()) {
        // Ambil data vendor berdasarkan ID
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
