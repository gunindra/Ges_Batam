<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;

class LoginController extends Controller
{
    public function index()
    {
        return view('login.indexlogin');
    }

    public function ajaxLogin(Request $request)
    {
        $credentials = $request->only('name', 'password');
        if (Auth::attempt(['name' => $credentials['name'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            $user = Auth::user();

            $redirectUrl = $this->getRedirectUrl($user->role);
            return response()->json([
                'success' => true,
                'message' => 'Login berhasil!',
                'redirect' => $redirectUrl
            ]);
        }

        $customer = Customer::where('marking', $credentials['name'])->first();
        if ($customer && Hash::check($credentials['password'], $customer->user->password)) {
            Auth::loginUsingId($customer->user_id);

            $request->session()->regenerate();
            $redirectUrl = $this->getRedirectUrl('customer');

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

    private function getRedirectUrl($role)
    {
        switch ($role) {
            case 'superadmin':
                return route('dashboard');
            case 'driver':
                return route('supir');
            case 'customer':
            case 'admin':
            case 'supervisor':
                return route('tracking');
            default:
                return route('default.page');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
