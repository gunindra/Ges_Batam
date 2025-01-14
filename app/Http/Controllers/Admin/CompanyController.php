<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class CompanyController extends Controller
{
    // Mengambil daftar perusahaan
    public function getCompanies()
    {
        // Ambil daftar perusahaan dari tabel 'tbl_company'
        $companies = DB::table('tbl_company')->get();

        return response()->json([
            'companies' => $companies
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
