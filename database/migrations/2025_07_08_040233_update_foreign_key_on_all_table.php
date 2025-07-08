<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tbl_jurnal_items', function (Blueprint $table) {
            // Drop the foreign key constraint using raw SQL
            DB::statement('ALTER TABLE tbl_jurnal_items DROP FOREIGN KEY tbl_jurnal_items_code_account_foreign');
        });

        Schema::table('tbl_account_settings', function (Blueprint $table) {
            DB::statement('ALTER TABLE tbl_account_settings DROP FOREIGN KEY tbl_account_settings_sales_account_id_foreign');
            DB::statement('ALTER TABLE tbl_account_settings DROP FOREIGN KEY tbl_account_settings_receivable_sales_account_id_foreign');
            DB::statement('ALTER TABLE tbl_account_settings DROP FOREIGN KEY tbl_account_settings_customer_sales_return_account_id_foreign');
            DB::statement('ALTER TABLE tbl_account_settings DROP FOREIGN KEY tbl_account_settings_discount_sales_account_id_foreign');
            DB::statement('ALTER TABLE tbl_account_settings DROP FOREIGN KEY tbl_account_settings_sales_profit_rate_account_id_foreign');
            DB::statement('ALTER TABLE tbl_account_settings DROP FOREIGN KEY tbl_account_settings_sales_loss_rate_account_id_foreign');
            DB::statement('ALTER TABLE tbl_account_settings DROP FOREIGN KEY tbl_account_settings_purchase_account_id_foreign');
            DB::statement('ALTER TABLE tbl_account_settings DROP FOREIGN KEY tbl_account_settings_debt_account_id_foreign');
            DB::statement('ALTER TABLE tbl_account_settings DROP FOREIGN KEY tbl_account_settings_supplier_purchase_return_account_id_foreign');
            DB::statement('ALTER TABLE tbl_account_settings DROP FOREIGN KEY tbl_account_settings_discount_purchase_account_id_foreign');
            DB::statement('ALTER TABLE tbl_account_settings DROP FOREIGN KEY tbl_account_settings_purchase_profit_rate_account_id_foreign');
            DB::statement('ALTER TABLE tbl_account_settings DROP FOREIGN KEY tbl_account_settings_purchase_loss_rate_account_id_foreign');
        });

        Schema::table('tbl_credit_note', function (Blueprint $table) {
            // Drop the foreign key constraint using raw SQL
            DB::statement('ALTER TABLE tbl_credit_note DROP FOREIGN KEY tbl_credit_note_account_id_foreign');
        });

        Schema::table('tbl_sup_invoice_items', function (Blueprint $table) {
            // Drop the foreign key constraint using raw SQL
            DB::statement('ALTER TABLE tbl_sup_invoice_items DROP FOREIGN KEY tbl_sup_invoice_items_coa_id_foreign');
        });

        Schema::table('tbl_debit_note', function (Blueprint $table) {
            // Drop the foreign key constraint using raw SQL
            DB::statement('ALTER TABLE tbl_debit_note DROP FOREIGN KEY tbl_debit_note_account_id_foreign');
        });

        Schema::table('tbl_payment_account', function (Blueprint $table) {
            // Drop the foreign key constraint using raw SQL
            DB::statement('ALTER TABLE tbl_payment_account DROP FOREIGN KEY tbl_payment_account_coa_id_foreign');
        });
        
        Schema::table('tbl_report_accounts', function (Blueprint $table) {
            // Drop the foreign key constraint using raw SQL
            DB::statement('ALTER TABLE tbl_report_accounts DROP FOREIGN KEY tbl_report_accounts_coa_id_foreign');
        });

        Schema::table('tbl_jurnal_items', function (Blueprint $table) {
            // Recreate the foreign key with RESTRICT
            $table->foreign('code_account')->references('id')->on('tbl_coa')->onDelete('restrict');
        });

        Schema::table('tbl_account_settings', function (Blueprint $table) {
            // Recreate the foreign key with RESTRICT
            $table->foreign('sales_account_id')->references('id')->on('tbl_coa')->onDelete('restrict');
            $table->foreign('receivable_sales_account_id')->references('id')->on('tbl_coa')->onDelete('restrict');
            $table->foreign('customer_sales_return_account_id')->references('id')->on('tbl_coa')->onDelete('restrict');
            $table->foreign('discount_sales_account_id')->references('id')->on('tbl_coa')->onDelete('restrict');
            $table->foreign('sales_profit_rate_account_id')->references('id')->on('tbl_coa')->onDelete('restrict');
            $table->foreign('sales_loss_rate_account_id')->references('id')->on('tbl_coa')->onDelete('restrict');
            $table->foreign('purchase_account_id')->references('id')->on('tbl_coa')->onDelete('restrict');
            $table->foreign('debt_account_id')->references('id')->on('tbl_coa')->onDelete('restrict');
            $table->foreign('supplier_purchase_return_account_id')->references('id')->on('tbl_coa')->onDelete('restrict');
            $table->foreign('discount_purchase_account_id')->references('id')->on('tbl_coa')->onDelete('restrict');
            $table->foreign('purchase_profit_rate_account_id')->references('id')->on('tbl_coa')->onDelete('restrict');
            $table->foreign('purchase_loss_rate_account_id')->references('id')->on('tbl_coa')->onDelete('restrict');
        });

        Schema::table('tbl_credit_note', function (Blueprint $table) {
            // Recreate the foreign key with CASCADE
            $table->foreign('account_id')->references('id')->on('tbl_coa')->onDelete('restrict');
        });

        Schema::table('tbl_sup_invoice_items', function (Blueprint $table) {
            // Recreate the foreign key with CASCADE
            $table->foreign('coa_id')->references('id')->on('tbl_coa')->onDelete('restrict');
        });

        Schema::table('tbl_debit_note', function (Blueprint $table) {
            // Recreate the foreign key with CASCADE
            $table->foreign('account_id')->references('id')->on('tbl_coa')->onDelete('restrict');
        });

        Schema::table('tbl_payment_account', function (Blueprint $table) {
            // Recreate the foreign key with CASCADE
            $table->foreign('coa_id')->references('id')->on('tbl_coa')->onDelete('restrict');
        });

        Schema::table('tbl_report_accounts', function (Blueprint $table) {
            // Recreate the foreign key with CASCADE
            $table->foreign('coa_id')->references('id')->on('tbl_coa')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::table('tbl_jurnal_items', function (Blueprint $table) {
            DB::statement('ALTER TABLE tbl_jurnal_items DROP FOREIGN KEY tbl_jurnal_items_code_account_foreign');
        });

        Schema::table('tbl_account_settings', function (Blueprint $table) {
            DB::statement('ALTER TABLE tbl_account_settings DROP FOREIGN KEY tbl_account_settings_sales_account_id_foreign');
            DB::statement('ALTER TABLE tbl_account_settings DROP FOREIGN KEY tbl_account_settings_receivable_sales_account_id_foreign');
            DB::statement('ALTER TABLE tbl_account_settings DROP FOREIGN KEY tbl_account_settings_customer_sales_return_account_id_foreign');
            DB::statement('ALTER TABLE tbl_account_settings DROP FOREIGN KEY tbl_account_settings_discount_sales_account_id_foreign');
            DB::statement('ALTER TABLE tbl_account_settings DROP FOREIGN KEY tbl_account_settings_sales_profit_rate_account_id_foreign');
            DB::statement('ALTER TABLE tbl_account_settings DROP FOREIGN KEY tbl_account_settings_sales_loss_rate_account_id_foreign');
            DB::statement('ALTER TABLE tbl_account_settings DROP FOREIGN KEY tbl_account_settings_purchase_account_id_foreign');
            DB::statement('ALTER TABLE tbl_account_settings DROP FOREIGN KEY tbl_account_settings_debt_account_id_foreign');
            DB::statement('ALTER TABLE tbl_account_settings DROP FOREIGN KEY tbl_account_settings_supplier_purchase_return_account_id_foreign');
            DB::statement('ALTER TABLE tbl_account_settings DROP FOREIGN KEY tbl_account_settings_discount_purchase_account_id_foreign');
            DB::statement('ALTER TABLE tbl_account_settings DROP FOREIGN KEY tbl_account_settings_purchase_profit_rate_account_id_foreign');
            DB::statement('ALTER TABLE tbl_account_settings DROP FOREIGN KEY tbl_account_settings_purchase_loss_rate_account_id_foreign');
        });

        Schema::table('tbl_credit_note', function (Blueprint $table) {
            // Drop the foreign key constraint using raw SQL
            DB::statement('ALTER TABLE tbl_credit_note DROP FOREIGN KEY tbl_credit_note_account_id_foreign');
        });

        Schema::table('tbl_sup_invoice_items', function (Blueprint $table) {
            // Drop the foreign key constraint using raw SQL
            DB::statement('ALTER TABLE tbl_sup_invoice_items DROP FOREIGN KEY tbl_sup_invoice_items_coa_id_foreign');
        });

        Schema::table('tbl_debit_note', function (Blueprint $table) {
            // Drop the foreign key constraint using raw SQL
            DB::statement('ALTER TABLE tbl_debit_note DROP FOREIGN KEY tbl_debit_note_account_id_foreign');
        });

        Schema::table('tbl_payment_account', function (Blueprint $table) {
            // Drop the foreign key constraint using raw SQL
            DB::statement('ALTER TABLE tbl_payment_account DROP FOREIGN KEY tbl_payment_account_coa_id_foreign');
        });

        Schema::table('tbl_report_accounts', function (Blueprint $table) {
            // Drop the foreign key constraint using raw SQL
            DB::statement('ALTER TABLE tbl_report_accounts DROP FOREIGN KEY tbl_report_accounts_coa_id_foreign');
        });

        Schema::table('tbl_jurnal_items', function (Blueprint $table) {
            // Recreate the foreign key with CASCADE
            $table->foreign('code_account')->references('id')->on('tbl_coa')->onDelete('cascade');
        });

        Schema::table('tbl_account_settings', function (Blueprint $table) {
            // Recreate the foreign key with RESTRICT
            $table->foreign('sales_account_id')->references('id')->on('tbl_coa')->onDelete('cascade');
            $table->foreign('receivable_sales_account_id')->references('id')->on('tbl_coa')->onDelete('cascade');
            $table->foreign('customer_sales_return_account_id')->references('id')->on('tbl_coa')->onDelete('cascade');
            $table->foreign('discount_sales_account_id')->references('id')->on('tbl_coa')->onDelete('cascade');
            $table->foreign('sales_profit_rate_account_id')->references('id')->on('tbl_coa')->onDelete('cascade');
            $table->foreign('sales_loss_rate_account_id')->references('id')->on('tbl_coa')->onDelete('cascade');
            $table->foreign('purchase_account_id')->references('id')->on('tbl_coa')->onDelete('cascade');
            $table->foreign('debt_account_id')->references('id')->on('tbl_coa')->onDelete('cascade');
            $table->foreign('supplier_purchase_return_account_id')->references('id')->on('tbl_coa')->onDelete('cascade');
            $table->foreign('discount_purchase_account_id')->references('id')->on('tbl_coa')->onDelete('cascade');
            $table->foreign('purchase_profit_rate_account_id')->references('id')->on('tbl_coa')->onDelete('cascade');
            $table->foreign('purchase_loss_rate_account_id')->references('id')->on('tbl_coa')->onDelete('cascade');
        });

        Schema::table('tbl_credit_note', function (Blueprint $table) {
            // Recreate the foreign key with CASCADE
            $table->foreign('account_id')->references('id')->on('tbl_coa')->onDelete('cascade');
        });

        Schema::table('tbl_sup_invoice_items', function (Blueprint $table) {
            // Recreate the foreign key with CASCADE
            $table->foreign('coa_id')->references('id')->on('tbl_coa')->onDelete('cascade');
        });

        Schema::table('tbl_debit_note', function (Blueprint $table) {
            // Recreate the foreign key with CASCADE
            $table->foreign('account_id')->references('id')->on('tbl_coa')->onDelete('cascade');
        });

        Schema::table('tbl_payment_account', function (Blueprint $table) {
            // Recreate the foreign key with CASCADE
            $table->foreign('coa_id')->references('id')->on('tbl_coa')->onDelete('cascade');
        });

        Schema::table('tbl_report_accounts', function (Blueprint $table) {
            // Recreate the foreign key with CASCADE
            $table->foreign('coa_id')->references('id')->on('tbl_coa')->onDelete('cascade');
        });
    }
};
