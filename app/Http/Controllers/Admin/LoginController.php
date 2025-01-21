<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        // Cek login untuk user biasa
        if (Auth::attempt(['name' => $credentials['name'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Jika company_id user ada
            if ($user->company_id) {
                $request->session()->put('active_company_id', $user->company_id);
            } else {
                // Jika company_id null, cari company dengan is_active = 1
                $activeCompany = DB::table('tbl_company')
                    ->where('is_active', 1)
                    ->first();

                if ($activeCompany) {
                    $request->session()->put('active_company_id', $activeCompany->id);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tidak ada perusahaan aktif yang ditemukan.'
                    ]);
                }
            }

            $redirectUrl = $this->getRedirectUrl($user->role);
            return response()->json([
                'success' => true,
                'message' => 'Login berhasil!',
                'redirect' => $redirectUrl
            ]);
        }

        // Cek login untuk customer
        $customer = Customer::where('marking', $credentials['name'])->first();
        if ($customer && Hash::check($credentials['password'], $customer->user->password)) {
            Auth::loginUsingId($customer->user_id);

            $request->session()->regenerate();

            // Periksa company_id untuk customer
            $user = Auth::user();
            if ($user->company_id) {
                $request->session()->put('active_company_id', $user->company_id);
            } else {
                $activeCompany = DB::table('tbl_company')
                    ->where('is_active', 1)
                    ->first();

                if ($activeCompany) {
                    $request->session()->put('active_company_id', $activeCompany->id);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tidak ada perusahaan aktif yang ditemukan.'
                    ]);
                }
            }

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
                return route('tracking');
            case 'admin':
            case 'supervisor':
                return route('tracking');
            case 'pickup':
                return route('pickup');
            default:
                return route('tracking');
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
