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
            $table->unsignedBigInteger('invoice_id_sup')->nullable()->after('invoice_id'); // Ganti 'existing_column' dengan nama kolom sebelumnya
            $table->foreign('invoice_id_sup')->references('id')->on('tbl_sup_invoice')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_jurnal', function (Blueprint $table) {
            $table->dropForeign(['invoice_id_sup']);
            $table->dropColumn('invoice_id_sup');
        });
    }
};
