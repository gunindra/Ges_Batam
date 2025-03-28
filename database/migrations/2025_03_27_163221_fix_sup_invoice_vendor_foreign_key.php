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
        Schema::table('tbl_sup_invoice', function (Blueprint $table) {
            // Hapus constraint yang salah terlebih dahulu
            $table->dropForeign(['vendor_id']);

            // Buat constraint baru yang benar
            $table->foreign('vendor_id')
                  ->references('id')
                  ->on('tbl_vendors')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('tbl_sup_invoice', function (Blueprint $table) {
            $table->dropForeign(['vendor_id']);

            // Kembalikan ke constraint lama (jika diperlukan rollback)
            $table->foreign('vendor_id')
                  ->references('id')
                  ->on('tbl_sup_invoice')
                  ->onDelete('cascade');
        });
    }
};
