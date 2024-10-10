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
        Schema::create('tbl_resi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('tbl_invoice');
            $table->string('no_resi', 100)->unique();
            $table->string('no_do', 100);
            $table->decimal('berat', 8,2)->nullable();
            $table->decimal('panjang', 8,2)->nullable();
            $table->decimal('lebar', 8,2)->nullable();
            $table->decimal('tinggi', 8,2)->nullable();
            $table->decimal('harga', 15,2);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_resi');
    }
};
