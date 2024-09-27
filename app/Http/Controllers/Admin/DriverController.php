<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    

        $namaDriver = $request->input('namaDriver');
        $alamatDriver = $request->input('alamatDriver');
        $noTelponDriver = $request->input('noTelponDriver');
        $simDriver = $request->file('simDriver');

        $fileName = $simDriver ? uniqid('Sim_', true) . '.' . $simDriver->getClientOriginalExtension() : null;

        if ($simDriver) {
            $simDriver->storeAs('public/sim', $fileName);
        }

        try {
            DB::table('tbl_supir')->insert([
                'nama_supir' => $namaDriver,
                'alamat_supir' => $alamatDriver,
                'no_wa' => $noTelponDriver,
                'image_sim' => $fileName,
                'created_at' => now(),
            ]);

            return response()->json(['status' => 'success', 'message' => 'Driver berhasil ditambahkan'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menambahkan Driver: ' . $e->getMessage()], 500);
        }
    }

    public function updateDriver(Request $request)
    {
        $request->validate([
            'namaDriver' => 'required|string|max:255',
            'alamatDriver' => 'required|string|max:255',
            'noTelponDriver' => 'required|string|max:15',
            'simDriverEdit' => 'nullable|mimes:jpg,jpeg,png',
        ]);

        $id = $request->input('id');
        $namaDriver = $request->input('namaDriver');
        $alamatDriver = $request->input('alamatDriver');
        $noTelponDriver = $request->input('noTelponDriver');
        $simDriver = $request->file('simDriverEdit');

        $driver = DB::table('tbl_supir')->where('id', $id)->first();
        
        $fileName = $simDriver ? 'SIM_' . time() . '_' . $simDriver->getClientOriginalName() : $driver->image_sim;

        if ($simDriver) {
            $simDriver->storeAs('public/sim', $fileName);
        }

        try {
            DB::table('tbl_supir')
                ->where('id', $id)
                ->update([
                    'nama_supir' => $namaDriver,
                    'alamat_supir' => $alamatDriver,
                    'no_wa' => $noTelponDriver,
                    'image_sim' => $fileName,
                    'updated_at' => now(),
                ]);

            return response()->json(['status' => 'success', 'message' => 'Data Driver berhasil diupdate'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal Mengupdate Data Driver: ' . $e->getMessage()], 500);
        }
    }
}
