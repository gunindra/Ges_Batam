<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $countUser = User::count();
        $countInvoice = Invoice::count();

        return view('dashboard.indexdashboard', compact('countUser', 'countInvoice'));
    }


    public function fetchMonthlyData(Request $request)
    {
        $month = $request->input('month');

        $startDate = Carbon::createFromFormat('M Y', $month)->startOfMonth();
        $endDate = Carbon::createFromFormat('M Y', $month)->endOfMonth();

        $data = DB::table('tbl_invoice')
            ->select(DB::raw("DATE(tanggal_buat) as date"), DB::raw("SUM(total_harga) as daily_revenue"))
            ->whereBetween('tanggal_buat', [$startDate, $endDate])
            ->groupBy(DB::raw("DATE(tanggal_buat)"))
            ->orderBy('date')
            ->get();

        return response()->json(['data' => $data]);
    }
}
