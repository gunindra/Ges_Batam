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
            // Tambahkan kolom payment_id
            $table->unsignedBigInteger('payment_id')->nullable()->after('id');

            // Tambahkan foreign key untuk relasi ke tbl_payment_customer
            $table->foreign('payment_id')
                  ->references('id')
                  ->on('tbl_payment_customer')
                  ->onDelete('set null'); // Bisa disesuaikan dengan kebutuhan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_jurnal', function (Blueprint $table) {
            // Hapus foreign key constraint
            $table->dropForeign(['payment_id']);

            // Hapus kolom payment_id
            $table->dropColumn('payment_id');
        });
    }
};
