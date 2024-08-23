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
            $table->string('no_resi', 100)->unique();
            $table->date('tanggal_pembayaran');
            $table->foreignId('pembeli_id')->constrained('tbl_pembeli');
            $table->decimal('berat', 8,2)->nullable();
            $table->decimal('panjang', 8,2)->nullable();
            $table->decimal('lebar', 8,2)->nullable();
            $table->decimal('tinggi', 8,2)->nullable();
            $table->enum('pengiriman', ['Pickup', 'Delivery']);
            $table->decimal('harga', 15,2);
            $table->foreignId('pembayaran_id')->constrained('tbl_tipe_pembayaran');
            $table->foreignId('rekening_id')->nullable()->constrained('tbl_rekening');
            $table->foreignId('matauang_id')->constrained('tbl_matauang')->nullable();
            $table->decimal('rate_matauang', 15,2)->nullable();
            $table->string('bukti_pembayaran', 100)->nullable();
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
