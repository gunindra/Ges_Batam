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
            $table->string('no_invoice', 20)->unique();
            $table->timestamp('tanggal_invoice');
            $table->foreignId('pembeli_id')->constrained('tbl_pembeli');
            $table->string('metode_pengiriman', 50);
            $table->text('alamat')->nullable();
            $table->foreignId('matauang_id')->constrained('tbl_matauang')->nullable();
            $table->decimal('rate_matauang', 15,2)->nullable();
            $table->decimal('total_harga', 15,2);
            $table->string('wa_status', 50)->nullable();
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
