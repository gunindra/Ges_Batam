<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
               // Insert ke tbl_account_settings
        DB::table('tbl_account_settings')->insert([
            'id' => 1,
            'sales_account_id' => 84,
            'receivable_sales_account_id' => 15,
            'customer_sales_return_account_id' => 86,
            'discount_sales_account_id' => 160,
            'sales_profit_rate_account_id' => 85,
            'sales_loss_rate_account_id' => null,
            'purchase_account_id' => null,
            'debt_account_id' => null,
            'supplier_purchase_return_account_id' => 91,
            'discount_purchase_account_id' => null,
            'purchase_profit_rate_account_id' => 159,
            'purchase_loss_rate_account_id' => null,


        ]);

        // Insert ke tbl_payment_account
        DB::table('tbl_payment_account')->insert([
            [
                'id' => 1,
                'coa_id' => 3,
            ],
            [
                'id' => 2,
                'coa_id' => 7,
            ],
            [
                'id' => 3,
                'coa_id' => 159,
            ],
        ]);

        // Insert ke tbl_report_accounts
        DB::table('tbl_report_accounts')->insert([
            [
                'id' => 1, 'coa_id' => 83, 'type' => 'Operating Revenue',
            ],
            [
                'id' => 2, 'coa_id' => 88, 'type' => 'Operating Expense',
            ],
            [
                'id' => 3, 'coa_id' => 94, 'type' => 'Operating Expense',
            ],
            [
                'id' => 4, 'coa_id' => 103, 'type' => 'Operating Expense',
            ],
            [
                'id' => 5, 'coa_id' => 133, 'type' => 'Operating Expense',
            ],
            [
                'id' => 6, 'coa_id' => 144, 'type' => 'Non Operating Revenue',
            ],
            [
                'id' => 7, 'coa_id' => 149, 'type' => 'Non Operating Expense',
            ],
            [
                'id' => 8, 'coa_id' => 76, 'type' => 'Capital',
            ],
            [
                'id' => 9, 'coa_id' => 77, 'type' => 'Additional Capital',
            ],
            [
                'id' => 10, 'coa_id' => 79, 'type' => 'Returned Profit',
            ],
            [
                'id' => 11, 'coa_id' => 80, 'type' => 'Current Profit',
            ],
            [
                'id' => 12, 'coa_id' => 81, 'type' => 'Deviden',
            ],
            [
                'id' => 13, 'coa_id' => 34, 'type' => 'Investing',
            ],
            [
                'id' => 14, 'coa_id' => 75, 'type' => 'Financing',
            ],
            [
                'id' => 15, 'coa_id' => 78, 'type' => 'Financing',
            ],
            [
                'id' => 16, 'coa_id' => 82, 'type' => 'Financing',
            ],
        ]);
    }
}
