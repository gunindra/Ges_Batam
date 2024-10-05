<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Driver;

class DriverController extends Controller
{
    public function index()
    {
        return view('masterdata.driver.indexmasterdriver');
    }

    public function getlistDriver(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';

        $data = DB::table('tbl_supir')
            ->select('id', 'nama_supir', 'alamat_supir', 'no_wa', 'image_sim')
            ->where(function ($query) use ($txSearch) {
                $query->where(DB::raw('UPPER(nama_supir)'), 'LIKE', $txSearch)
                    ->orWhere(DB::raw('UPPER(alamat_supir)'), 'LIKE', $txSearch)
                    ->orWhere(DB::raw('UPPER(no_wa)'), 'LIKE', $txSearch);
            })
            ->get();

        $output = '
            <table class="table align-items-center table-flush table-hover" id="tableDriver">
                <thead class="thead-light">
                    <tr>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No. Telp</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($data as $item) {
            $output .= '
                <tr>
                    <td>' . ($item->nama_supir ?? '-') . '</td>
                    <td>' . ($item->alamat_supir ?? '-') . '</td>
                    <td>' . ($item->no_wa ?? '-') . '</td>
                    <td>
                        <a class="btn btnDetailSim btn-sm btn-primary text-white" data-id="' . $item->id . '" data-bukti="' . $item->image_sim . '"><i class="fas fa-eye"></i></a>
                        <a class="btn btnUpdateDriver btn-sm btn-secondary text-white" data-id="' . $item->id . '" data-nama_supir="' . $item->nama_supir . '" data-alamat_supir="' . $item->alamat_supir . '" data-no_wa="' . $item->no_wa . '" data-sim="' . $item->image_sim . '"><i class="fas fa-edit"></i></a>
                    </td>
                </tr>';
        }

        $output .= '</tbody></table>';
        return $output;
    }

    public function addDriver(Request $request)
    {
        $request->validate([
            'namaDriver' => 'required|string|max:255',
            'alamatDriver' => 'required|string|max:255',
            'noTelponDriver' => 'required|string|max:15',
            'simDriver' => 'nullable|mimes:jpg,jpeg,png',
        ]);

        try {
            $Driver = new Driver();
            $Driver->nama_supir = $request->input('namaDriver');
            $Driver->alamat_supir = $request->input('alamatDriver');
            $Driver->no_wa = $request->input('noTelponDriver');
            $simDriver = $request->file('simDriver');

            if ($simDriver) {
                $fileName = uniqid('Sim_', true) . '.' . $simDriver->getClientOriginalExtension();
                $simDriver->storeAs('public/sim', $fileName);
                $Driver->image_sim = $fileName;
            }


            $Driver->save();

            return response()->json(['success' => 'berhasil ditambahkan']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menambahkan']);
        }
    }

    public function updateDriver(Request $request, $id)
    {
        $validated = $request->validate([
            'namaDriver' => 'required|string|max:255',
            'alamatDriver' => 'required|string|max:255',
            'noTelponDriver' => 'required|string|max:15',
            'simDriverEdit' => 'nullable|mimes:jpg,jpeg,png',
        ]);

        try {

            $Driver = Driver::findOrFail($id);

            $Driver->nama_supir = $request->input('namaDriver');
            $Driver->alamat_supir = $request->input('alamatDriver');
            $Driver->no_wa = $request->input('noTelponDriver');

            if ($request->hasFile('simDriverEdit')) {
                $simDriver = $request->file('simDriverEdit');
                $fileName = 'SIM_' . time() . '_' . $simDriver->getClientOriginalName();
                $simDriver->storeAs('public/sim', $fileName);
                $Driver->image_sim = $fileName;
            }

            $Driver->save();

            return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui']);
        } catch (\Exception $e) {
            return response()->json(['error' => false, 'message' => 'Data gagal diperbarui']);
        }
    }

    public function show($id)
    {
        $Driver = Driver::findOrFail($id);
        return response()->json($Driver);
    }
}
