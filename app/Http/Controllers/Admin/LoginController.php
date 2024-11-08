<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        // Uncomment this line if you want to redirect authenticated users to the dashboard
        // if (auth()->check()) {
        //     return redirect()->route('dashboard');
        // }

        return view('login.indexlogin');
    }

    public function ajaxLogin(Request $request)
    {
        $credentials = $request->only('name', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            // Handle role-based redirection
            if ($user->role === 'superadmin') {
                $redirectUrl = route('dashboard');
            } elseif ($user->role === 'driver') {
                $redirectUrl = route('supir');
            } elseif ($user->role === 'customer') {
                $redirectUrl = route('tracking');
            } elseif (in_array($user->role, ['admin', 'supervisor'])) {
                $redirectUrl = route('tracking');
            } else {
                // Default redirection if role doesn't match any condition
                $redirectUrl = route('default.page'); // Replace 'default.page' with a valid route
            }

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil!',
                'redirect' => $redirectUrl
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Nama dan Password Salah'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
