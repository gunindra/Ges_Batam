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
        Schema::table('tbl_retur', function (Blueprint $table) {
            $table->dropForeign(['currency_id']); // jika ada foreign key
            $table->dropColumn('currency_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_retur', function (Blueprint $table) {
            $table->unsignedBigInteger('currency_id')->nullable();

            // Kalau sebelumnya ada foreign key, tambahkan kembali
            $table->foreign('currency_id')->references('id')->on('tbl_matauang')->onDelete('set null');
        });
    }
};
