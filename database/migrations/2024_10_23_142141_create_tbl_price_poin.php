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
        // Schema::create('tbl_price_poin', function (Blueprint $table) {
        //     $table->id();
        //     $table->decimal('price_per_kg', 15, 2); // Harga per kg
        //     $table->date('effective_date'); // Tanggal harga mulai berlaku
        //     $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        //     $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_price_poin');
    }
};
