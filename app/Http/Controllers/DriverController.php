<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DriverController extends Controller
{
    public function index() {


        return view('masterdata.driver.indexmasterdriver');
    }

    public function getlistDriver(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';

        $q = "SELECT id,
                        nama_supir,
                        alamat_supir,
                        no_wa,
                        image_sim
                FROM tbl_supir
                WHERE (UPPER(nama_supir) LIKE UPPER('$txSearch') OR UPPER(alamat_supir) LIKE UPPER('$txSearch') OR UPPER(no_wa) LIKE UPPER('$txSearch'))
        ";

        $data = DB::select($q);

        $output = '  <table class="table align-items-center table-flush table-hover" id="tableDriver">
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
            $output .=
                '
                <tr>
                    <td class="">' . ($item->nama_supir ?? '-') .'</td>
                    <td class="">' . ($item->alamat_supir ?? '-') .'</td>
                    <td class="">' . ($item->no_wa ?? '-') .'</td>
                   <td>
                        <a  class="btn btnDetailSim btn-sm btn-primary text-white" data-id="' . $item->id . '" data-bukti="' . $item->image_sim . '"><i class="fas fa-eye"></i></a>
                        <a  class="btn btnUpdateDriver btn-sm btn-secondary text-white" data-id="' .$item->id .'" data-nama_supir="' .$item->nama_supir .'" data-alamat_supir="' .$item->alamat_supir .'" data-no_wa="' .$item->no_wa .'" data-sim="' . $item->image_sim . '"><i class="fas fa-edit"></i></a>
                    </td>
                </tr>
            ';
        }

        $output .= '</tbody></table>';
         return $output;
    }

    public function addDriver(Request $request)
    {
        $request->validate([
            'simDriver' => 'nullable|mimes:jpg,jpeg,png|',
        ]);

        $namaDriver = $request->input('namaDriver');
        $alamatDriver = $request->input('alamatDriver');
        $noTelponDriver = $request->input('noTelponDriver');
        $simDriver = $request->file('simDriver');

        if ($simDriver) {
            $uniqueId = uniqid('Sim_', true);
            $fileName = $uniqueId . '.' . $simDriver->getClientOriginalExtension();
            $simDriver->storeAs('public/images', $fileName);
        } else {
            $fileName = null;
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
            'simDriverEdit' => 'nullable|mimes:jpg,jpeg,png',
        ]);
    
        $id = $request->input('id');
        $namaDriver = $request->input('namaDriver');
        $alamatDriver = $request->input('alamatDriver');
        $noTelponDriver = $request->input('noTelponDriver');
        $simDriver = $request->file('simDriverEdit');
    
        // Retrieve the existing driver record
        $driver = DB::table('tbl_supir')->where('id', $id)->first();
        
        if ($simDriver) {
            $fileName = 'SIM_' . time() . '_' . $simDriver->getClientOriginalName(); // Add a timestamp to avoid filename conflicts
            $filePath = $simDriver->storeAs('public/sim', $fileName);
        } else {
            // Use the existing image if no new image is uploaded
            $fileName = $driver->image_sim;
        }
    
        try {
            DB::table('tbl_supir')
                ->where('id', $id)
                ->update([
                    'nama_supir' => $namaDriver,
                    'alamat_supir' => $alamatDriver,
                    'no_wa' => $noTelponDriver,
                    'image_sim' => $fileName, // Update with the new or old image
                    'updated_at' => now(),
                ]);
    
            return response()->json(['status' => 'success', 'message' => 'Data Driver berhasil diupdate'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal Mengupdate Data Driver: ' . $e->getMessage()], 500);
        }
    }
    
}
