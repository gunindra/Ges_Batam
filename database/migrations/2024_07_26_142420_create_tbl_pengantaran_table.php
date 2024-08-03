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
        Schema::create('tbl_pengantaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembayaran_id')->constrained('tbl_pembayaran')->unique();
            $table->date('tanggal_pengantaran');
            $table->foreignId('supir_id')->constrained('tbl_supir');
            $table->text('alamat');
            $table->string('kota', 100);
            $table->string('provinsi', 100);
            $table->text('bukti_pengantaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_pengantaran');
    }
};
