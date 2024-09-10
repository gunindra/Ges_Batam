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
            $table->foreignId('supir_id')->nullable()->constrained('tbl_supir');
            $table->date('tanggal_pengantaran');
            $table->enum('metode_pengiriman', ['Pickup', 'Delivery']);
            $table->foreignId('status_id')->constrained('tbl_status')->default(3);
            $table->text('bukti_pengantaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.fe
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_pengantaran');
    }
};
