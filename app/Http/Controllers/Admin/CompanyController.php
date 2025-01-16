<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Http\Request;

class CompanyController extends Controller
{

    public function index ()
    {

        return view('masterdata.company.indexmastercompany');
    }
    // Mengambil daftar perusahaan
    public function getCompanies()
    {
        $activeCompanyId = session('active_company_id');
        $companies = DB::table('tbl_company')->get();

        return response()->json([
            'companies' => $companies,
            'active_company_id' => $activeCompanyId,
        ]);
    }

    public function getlistCompany()
    {
        $query = DB::table('tbl_company')
        ->select([
            'id',
            'name',
            'logo',
            'alamat',
            'is_active', // Pastikan kolom ini ada di database
        ])
        ->orderBy('id', 'desc');


        $data = $query->get();

        // Buat output tabel secara manual
        $output = '
            <table id="tableCompany" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Logo</th>
                        <th>Alamat</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
        ';

        foreach ($data as $row) {
            $logo = '<img src="' . $row->logo . '" alt="Logo" class="img-thumbnail" style="width: 50px; height: 50px;">';
            $updateBtn = '<a href="#" class="btn btnUpdateCompany btn-sm btn-secondary" data-id="' . $row->id . '"><i class="fas fa-edit"></i></a>';
            $deleteBtn = '<a href="#" class="btn btnDestroyCompany btn-sm btn-danger ml-2" data-id="' . $row->id . '"><i class="fas fa-trash"></i></a>';
            $switchBtn = '
            <div class="switch">
                <input type="checkbox" id="switch' . $row->id . '" class="switch-input" data-id="' . $row->id . '" ' . ($row->is_active ? 'checked' : '') . ' />
                <label for="switch' . $row->id . '" class="switch-label">
                    <span class="text-on">On</span>
                    <span class="text-off">Off</span>
                </label>
            </div>';

            $output .= '
                <tr>
                    <td>' . $row->name . '</td>
                    <td>' . $logo . '</td>
                    <td>' . $row->alamat . '</td>
                    <td>' . $updateBtn . $deleteBtn . $switchBtn . '</td>
                </tr>
            ';
        }

        $output .= '
                </tbody>
            </table>
        ';

        return $output;
    }



    // Menyimpan perusahaan yang dipilih
    public function setActiveCompany(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:tbl_company,id',
            'is_active' => 'required|boolean', // Pastikan status is_active adalah boolean
        ]);

        // Matikan semua perusahaan yang aktif terlebih dahulu
        if ($request->is_active) {
            DB::table('tbl_company')->update(['is_active' => 0]);
        }

        // Perbarui status aktif untuk perusahaan yang dipilih
        DB::table('tbl_company')
            ->where('id', $request->id)
            ->update(['is_active' => $request->is_active]);

        // Simpan company_id yang dipilih di session
        session(['active_company_id' => $request->id]);

        return response()->json(['success' => true, 'message' => 'Company status updated successfully.']);
    }

}
