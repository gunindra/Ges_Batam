<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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

    // Menyimpan perusahaan yang dipilih
    public function setActiveCompany(Request $request)
    {
        $request->validate([
            'active_company_id' => 'required|exists:tbl_company,id',
        ]);

        // Simpan company_id yang dipilih di session
        session(['active_company_id' => $request->active_company_id]);

        return response()->json(['success' => true]);
    }
}
