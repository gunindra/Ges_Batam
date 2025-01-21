<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetActiveCompany
{
    public function handle(Request $request, Closure $next)
    {
        $companyIdFromQuery = $request->query('company_id');
        if ($companyIdFromQuery) {
            $company = DB::table('tbl_company')->where('id', $companyIdFromQuery)->first();

            if ($company) {
                session(['active_company_id' => $company->id]);
                DB::table('tbl_company')->update(['is_active' => 0]);
                DB::table('tbl_company')->where('id', $company->id)->update(['is_active' => 1]);
            }
        }

        if (!session()->has('active_company_id')) {
            $activeCompany = DB::table('tbl_company')->where('is_active', 1)->first();

            if (!$activeCompany) {
                $defaultCompany = DB::table('tbl_company')->orderBy('id', 'asc')->first();
                if ($defaultCompany) {
                    session(['active_company_id' => $defaultCompany->id]);
                    DB::table('tbl_company')->where('id', $defaultCompany->id)->update(['is_active' => 1]);
                } else {
                    abort(500, 'Tidak ada perusahaan yang tersedia.');
                }
            } else {
                session(['active_company_id' => $activeCompany->id]);
            }
        }

        return $next($request);
    }
}
