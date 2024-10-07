<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_account_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_account_id')->nullable()->constrained('tbl_coa')->onDelete('cascade');
            $table->foreignId('receivable_sales_account_id')->nullable()->constrained('tbl_coa')->onDelete('cascade');
            $table->foreignId('customer_sales_return_account_id')->nullable()->constrained('tbl_coa')->onDelete('cascade');
            $table->foreignId('discount_sales_account_id')->nullable()->constrained('tbl_coa')->onDelete('cascade');
            $table->foreignId('sales_profit_rate_account_id')->nullable()->constrained('tbl_coa')->onDelete('cascade');
            $table->foreignId('sales_loss_rate_account_id')->nullable()->constrained('tbl_coa')->onDelete('cascade');
            $table->foreignId('purchase_account_id')->nullable()->constrained('tbl_coa')->onDelete('cascade');
            $table->foreignId('debt_account_id')->nullable()->constrained('tbl_coa')->onDelete('cascade');
            $table->foreignId('supplier_purchase_return_account_id')->nullable()->constrained('tbl_coa')->onDelete('cascade');
            $table->foreignId('discount_purchase_account_id')->nullable()->constrained('tbl_coa')->onDelete('cascade');
            $table->foreignId('purchase_profit_rate_account_id')->nullable()->constrained('tbl_coa')->onDelete('cascade');
            $table->foreignId('purchase_loss_rate_account_id')->nullable()->constrained('tbl_coa')->onDelete('cascade');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_settings');
    }
}
