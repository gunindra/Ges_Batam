<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
class SetActiveCompany
{
    public function handle(Request $request, Closure $next)
    {
        // Periksa apakah session `active_company_id` ada
        if (!session()->has('active_company_id')) {
            // Jika tidak ada, tetapkan nilai default 1
            session(['active_company_id' => 1]);
        }

        // Perbarui session jika ada `company_id` di query string
        if ($request->query('company_id')) {
            session(['active_company_id' => $request->query('company_id')]);
        }

        return $next($request);
    }
}
