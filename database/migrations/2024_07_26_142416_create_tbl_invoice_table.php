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
        Schema::create('tbl_invoice', function (Blueprint $table) {
            $table->id();
            $table->string('no_resi', 100)->unique();
            $table->timestamp('tanggal_invoice');
            $table->foreignId('pembeli_id')->constrained('tbl_pembeli');
            $table->text('alamat')->nullable();
            $table->decimal('berat', 8,2)->nullable();
            $table->decimal('panjang', 8,2)->nullable();
            $table->decimal('lebar', 8,2)->nullable();
            $table->decimal('tinggi', 8,2)->nullable();
            $table->foreignId('matauang_id')->constrained('tbl_matauang')->nullable();
            $table->decimal('rate_matauang', 15,2)->nullable();
            $table->decimal('harga', 15,2);
            $table->foreignId('status_id')->constrained('tbl_status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_invoice');
    }
};
