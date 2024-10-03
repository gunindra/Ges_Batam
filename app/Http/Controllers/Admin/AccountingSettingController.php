<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\COA;
use App\Models\AccountSettings;
use DB;
use Illuminate\Http\Request;

class AccountingSettingController extends Controller
{
    public function index()
    {
        $coas = COA::all();
        $accountSettings = DB::table('tbl_account_settings')->first();

        // Memeriksa apakah $accountSettings null sebelum mengakses propertinya
        $createdAtStatus = $accountSettings ? $accountSettings->id : null;

        return view('accounting.accountingSetting.indexaccountsetting', compact('coas', 'accountSettings', 'createdAtStatus'));
    }


    public function store(Request $request)
    {
       // Validasi hanya field yang diisi
    $validatedData = $request->validate([
        'sales_account_id' => 'nullable',
        'receivable_sales_account_id' => 'nullable',
        'customer_sales_return_account_id' => 'nullable',
        'discount_sales_account_id' => 'nullable',
        'sales_profit_rate_account_id' => 'nullable',
        'sales_loss_rate_account_id' => 'nullable',
        'purchase_account_id' => 'nullable',
        'debt_account_id' => 'nullable',
        'supplier_purchase_return_account_id' => 'nullable',
        'discount_purchase_account_id' => 'nullable',
        'purchase_profit_rate_account_id' => 'nullable',
        'purchase_loss_rate_account_id' => 'nullable',
    ]);

    // Check kondisi idData
    if ($request->idData) {
        // Jika idData ada, lakukan update
        $accountSetting = AccountSettings::find($request->idData);
        $accountSetting->update($validatedData);
    } else {
        // Jika idData null, tambahkan data baru
        AccountSettings::create($validatedData);
    }

    return response()->json(['success' => 'Data berhasil disimpan']);
    }

}


