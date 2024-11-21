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

        $savedPaymentAccounts = DB::table('tbl_payment_account')
            ->pluck('coa_id') 
            ->toArray();

        $createdAtStatus = $accountSettings ? $accountSettings->id : null;

        return view('accounting.accountingSetting.indexaccountsetting', compact('coas', 'accountSettings', 'createdAtStatus', 'savedPaymentAccounts'));
    }


    public function store(Request $request)
    {
        // Validasi semua data yang dibutuhkan
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
            'coa_id' => 'nullable|array',
            'coa_id.*' => 'exists:tbl_coa,id',
        ]);


        if ($request->idData) {
            $accountSetting = AccountSettings::find($request->idData);
            $accountSetting->update($validatedData);
        } else {
            AccountSettings::create($validatedData);
        }


        if (empty($request->coa_id)) {
            DB::table('tbl_payment_account')->delete();
        } else {
            foreach ($request->coa_id as $coaId) {
                DB::table('tbl_payment_account')->updateOrInsert(
                    ['coa_id' => $coaId],
                    ['updated_at' => now()]
                );
            }
        }
    


        return response()->json(['success' => 'Data berhasil disimpan']);
    }
}


