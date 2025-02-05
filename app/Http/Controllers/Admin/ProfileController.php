<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Alamat;
use App\Models\Customer;
use App\Models\HistoryTopup;
use App\Models\UsagePoints;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\PricePoin;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();

        // dd( $user->id);

        $listPoin = DB::table('tbl_pembeli')
            ->where('user_id', $user->id)
            ->value('sisa_poin');

        $customer = Customer::where('user_id', $user->id)->first();
        $alamatList = $customer ? $customer->alamat()->get() : collect();


        return view('profile.edit', [
            'user' => $user,
            'listPoin' => $listPoin,
            'alamatList' => $alamatList,
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        // Validasi umum
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:tbl_users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
        ];

        // Validasi alamat hanya jika role bukan customer
        if ($user->role !== 'customer') {
            $rules['alamatCustomer'] = 'required|array|min:1';
            $rules['alamatCustomer.*'] = 'required|string|max:255';
        } else {
            $rules['alamatCustomer'] = 'nullable|array';
            $rules['alamatCustomer.*'] = 'nullable|string|max:255';
        }

        // Custom messages
        $messages = [
            'alamatCustomer.required' => 'Minimal satu alamat harus diisi.',
            'alamatCustomer.*.required' => 'Alamat tidak boleh kosong.',
        ];

        $validated = $request->validate($rules, $messages);

        // Update profil user
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? Hash::make($validated['password']) : $user->password,
        ]);

        // Update alamat jika ada
        if (isset($validated['alamatCustomer'])) {
            $customer = Customer::where('user_id', $user->id)->first();
            if ($customer) {
                Alamat::where('pembeli_id', $customer->id)->delete();
                foreach ($validated['alamatCustomer'] as $alamat) {
                    if (!empty($alamat)) {
                        Alamat::create([
                            'pembeli_id' => $customer->id,
                            'alamat' => $alamat,
                        ]);
                    }
                }
            }
        }

        // Response untuk AJAX
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Profile updated successfully!']);
        }

        return redirect()->route('profile.edit')->with('status', 'Profile updated successfully.');
    }


    public function destroy(Request $request)
    {
        $request->user()->delete();

        return redirect()->route('home');
    }

    public function getPointsHistory(Request $request)
    {
        $user = auth()->user();
        $whereConditionTopup = "";
        $whereConditionUsage = "";

        // Tambahkan filter user_id jika bukan superadmin
        if ($user->role !== 'superadmin') {
            $whereConditionTopup .= "WHERE tbl_pembeli.user_id = {$user->id}";
            $whereConditionUsage .= "WHERE tbl_pembeli.user_id = {$user->id}";
        }

        // Tambahkan filter tanggal untuk semua pengguna
        $startDate = $request->startDate ? date('Y-m-d', strtotime($request->startDate)) : null;
        $endDate = $request->endDate ? date('Y-m-d', strtotime($request->endDate)) : null;

        if ($startDate && $endDate) {
            $dateConditionTopup = "date BETWEEN '{$startDate}' AND '{$endDate}'";
            $dateConditionUsage = "usage_date BETWEEN '{$startDate}' AND '{$endDate}'";

            $whereConditionTopup .= ($whereConditionTopup ? " AND " : "WHERE ") . $dateConditionTopup;
            $whereConditionUsage .= ($whereConditionUsage ? " AND " : "WHERE ") . $dateConditionUsage;
        }

        $sql = "
            SELECT
                tbl_history_topup.id,
                tbl_history_topup.customer_id,
                tbl_pembeli.marking AS marking,
                tbl_pembeli.nama_pembeli AS customer_name,
                tbl_history_topup.remaining_points AS points,
                tbl_history_topup.price_per_kg,
                tbl_history_topup.date,
                'Masuk' AS type
            FROM
                tbl_history_topup
            JOIN
                tbl_pembeli ON tbl_history_topup.customer_id = tbl_pembeli.id
            JOIN
                tbl_coa ON tbl_history_topup.account_id = tbl_coa.id
            $whereConditionTopup

            UNION ALL

            SELECT
                tbl_usage_points.id,
                tbl_usage_points.customer_id,
                tbl_pembeli.marking AS marking,
                tbl_pembeli.nama_pembeli AS customer_name,
                tbl_usage_points.used_points AS points,
                tbl_usage_points.price_per_kg,
                tbl_usage_points.usage_date AS date,
                'Keluar' AS type
            FROM
                tbl_usage_points
            JOIN
                tbl_pembeli ON tbl_usage_points.customer_id = tbl_pembeli.id
            $whereConditionUsage

            ORDER BY
                date
        ";

        $combinedData = \DB::select($sql);
        $combinedData = collect($combinedData);
        return DataTables::of($combinedData)
            ->editColumn('date', function ($row) {
                return \Carbon\Carbon::parse($row->date)->translatedFormat('d F Y');
            })
            ->addColumn('type_badge', function ($row) {
                return $row->type === 'Masuk'
                    ? '<span class="badge text-white bg-success">Masuk</span>'
                    : '<span class="badge text-white bg-danger">Keluar</span>';
            })
            ->rawColumns(['type_badge'])
            ->make(true);
    }

}
