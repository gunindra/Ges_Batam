<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Company;

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
            $logo = '<img src="' . asset('storage/' . $row->logo) . '" alt="Logo" class="img-thumbnail" style="width: 70px; height: 50px;">';
            $updateBtn = '<a href="#" class="btn btnUpdateCompany btn-sm btn-secondary" data-id="' . $row->id . '"><i class="fas fa-edit"></i></a>';
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
                    <td>' . $updateBtn . $switchBtn . '</td>
                </tr>
            ';
        }

        $output .= '
                </tbody>
            </table>
        ';

        return $output;
    }

    public function setActiveCompany(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:tbl_company,id',
            'is_active' => 'required|boolean',
        ]);

        if ($request->is_active) {
            DB::table('tbl_company')->update(['is_active' => 0]);
        }

        DB::table('tbl_company')
            ->where('id', $request->id)
            ->update(['is_active' => $request->is_active]);

        session(['active_company_id' => $request->id]);

        return response()->json(['success' => true, 'message' => 'Company status updated successfully.']);
    }


    public function tambahCompany(Request $request)
    {
        $request->validate([
            'namaCompany' => 'required|string|max:255|unique:tbl_company,name',
            'alamatCompany' => 'required|string|max:255',
            'logoCompany' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $logoPath = $request->file('logoCompany')->store('logos', 'public');

            // Simpan data ke database
            $company = Company::create([
                'name' => $request->namaCompany,
                'alamat' => $request->alamatCompany,
                'logo' => $logoPath,
            ]);

            return response()->json(['success' => 'Company Berhasil ditambahkan'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422); // Validasi gagal
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menambahkan Company: ' . $e->getMessage()], 500);
        }
    }


    public function getDataCompany(Request $request)
    {
        try {
            // Validasi bahwa ID dikirimkan
            $request->validate([
                'id' => 'required|integer|exists:tbl_company,id',
            ]);

            // Ambil data berdasarkan ID
            $company = Company::findOrFail($request->id);

            // Kembalikan data sebagai JSON
            return response()->json([
                'status' => 'success',
                'data' => $company,
            ], 200);
        } catch (\Exception $e) {
            // Tangani jika terjadi kesalahan
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data Company: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateCompany(Request $request)
    {

        // dd($request->all());
        // Validasi input
        $request->validate([
            'namaCompany' => 'required|string|max:255|unique:tbl_company,name,' . $request->id,
            'alamatCompany' => 'required|string|max:255',
            'logoCompany' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            // Temukan perusahaan berdasarkan ID
            $company = Company::findOrFail($request->id);

            // Perbarui data
            $company->name = $request->namaCompany;
            $company->alamat = $request->alamatCompany;

            // Periksa apakah ada file logo baru yang diunggah
            if ($request->hasFile('logoCompany')) {
                // Hapus logo lama jika ada
                if ($company->logo && Storage::disk('public')->exists($company->logo)) {
                    Storage::disk('public')->delete($company->logo);
                }

                // Simpan logo baru
                $logoPath = $request->file('logoCompany')->store('logos', 'public');
                $company->logo = $logoPath;
            }

            // Simpan perubahan
            $company->save();

            return response()->json([
                'success' => true,
                'message' => 'Company berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui company: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteCompany(Request $request)
    {
        try {

            // dd($request->all());
            // Validasi ID perusahaan
            $request->validate([
                'id' => 'required|integer|exists:tbl_company,id',
            ]);

            $company = Company::findOrFail($request->id);
            $company->delete();

            if ($company->logo) {
                $logoPath = public_path('storage/' . $company->logo);
                if (file_exists($logoPath)) {
                    unlink($logoPath);
                }
            }

            return response()->json(['success' => 'Company berhasil dihapus.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus Company: ' . $e->getMessage()], 500);
        }
    }

}
