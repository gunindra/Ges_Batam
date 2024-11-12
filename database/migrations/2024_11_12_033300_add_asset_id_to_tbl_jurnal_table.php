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
        Schema::table('tbl_jurnal', function (Blueprint $table) {
            $table->unsignedBigInteger('asset_id')->nullable(); // Add the foreign key column
            $table->foreign('asset_id')->references('id')->on('tbl_assets')->onDelete('cascade');
            $table->unsignedBigInteger('invoice_id')->nullable(); // Add the foreign key column
            $table->foreign('invoice_id')->references('id')->on('tbl_invoice')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_jurnal', function (Blueprint $table) {
            $table->dropColumn('asset_id');
            $table->dropColumn('invoice_id');
        });
    }
};
