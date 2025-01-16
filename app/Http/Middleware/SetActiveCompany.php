<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetActiveCompany
{
    public function handle(Request $request, Closure $next)
    {
        $activeCompany = \DB::table('tbl_company')
        ->where('is_active', 1)
        ->first();

        if (!$activeCompany) {
            // Jika tidak ada yang aktif, tetapkan default 1
            session(['active_company_id' => 1]);

            // Perbarui `is_active` di `tbl_company` untuk id 1
            \DB::table('tbl_company')
                ->where('id', 1)
                ->update(['is_active' => 1]);
        } else {
            session(['active_company_id' => $activeCompany->id]);
        }

        // Perbarui session jika ada `company_id` di query string
        if ($request->query('company_id')) {
            session(['active_company_id' => $request->query('company_id')]);
        }

        return $next($request);
    }
}
