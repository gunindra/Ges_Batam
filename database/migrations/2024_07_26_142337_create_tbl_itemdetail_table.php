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
        Schema::create('tbl_itemdetail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemesanan_id')->constrained('tbl_pemesanan');
            $table->string('nama_barang', 100);
            $table->decimal('harga_barang', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_itemdetail');
    }
};
