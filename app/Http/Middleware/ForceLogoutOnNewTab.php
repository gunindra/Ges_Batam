<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ForceLogoutOnNewTab
{
    public function handle($request, Closure $next)
    {
        // Cek apakah session memiliki `tab_id`
        if (session()->has('tab_id')) {
            if (session('tab_id') !== $request->header('Tab-ID')) {
                Auth::logout();
                return redirect('/login');
            }
        }

        // Set atau perbarui `tab_id` dalam sesi sesuai dengan header 'Tab-ID'
        session(['tab_id' => $request->header('Tab-ID')]);

        return $next($request);
    }
}
