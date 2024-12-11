<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tbl_assets', function (Blueprint $table) {
            $table->foreignId('asset_account')->references('id')->on('tbl_coa')->onDelete('cascade');
            $table->foreignId('expense_account')->references('id')->on('tbl_coa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_assets', function (Blueprint $table) {
            $table->dropColumn('asset_account');
            $table->dropColumn('expense_account');
        });
    }
};
