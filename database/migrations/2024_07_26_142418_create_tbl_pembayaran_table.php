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
        Schema::create('tbl_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('no_resi', 100);
            $table->date('tanggal_pembayaran');
            $table->foreignId('pembeli_id')->constrained('tbl_pembeli');
            $table->decimal('berat', 8)->nullable();
            $table->decimal('panjang', 8)->nullable();
            $table->decimal('lebar', 8)->nullable();
            $table->decimal('tinggi', 8)->nullable();
            $table->decimal('pembagi', 8)->nullable();
            $table->decimal('rate', 8)->nullable();
            $table->enum('pengiriman', ['pickup', 'delivery']);
            $table->decimal('harga', 15);
            $table->foreignId('pembayaran_id')->constrained('tbl_tipe_pembayaran');
            $table->foreignId('rekening_id')->constrained('tbl_rekening')->nullable();
            $table->foreignId('matauang_id')->constrained('tbl_matauang')->nullable();
            $table->foreignId('status_id')->constrained('tbl_status');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_pembayaran');
    }
};
