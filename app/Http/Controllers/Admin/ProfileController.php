<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
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
        $listPoin = DB::table('tbl_pembeli')
        ->where('user_id', $request->user()->id)
        ->value('sisa_poin');
        return view('profile.edit', [
            'user' => $request->user(),
            'listPoin' => $listPoin,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('tbl_users')->ignore($request->user()->id)],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);

        $request->user()->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? Hash::make($validated['password']) : $request->user()->password,
        ]);

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

    public function getPointsHistory()
    {
        // Raw SQL Query
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

            ORDER BY
                date
        ";

        // Run the raw SQL query
        $combinedData = \DB::select($sql);

        // Convert the result to a collection for DataTables compatibility
        $combinedData = collect($combinedData);

        // Return DataTables response
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
