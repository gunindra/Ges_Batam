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
        Schema::table('tbl_payment_customer', function (Blueprint $table) {
            $table->dateTime('payment_buat')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_payment_customer', function (Blueprint $table) {
            $table->date('payment_buat')->change();
        });
    }
};
