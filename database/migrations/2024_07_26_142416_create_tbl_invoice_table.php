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
            $table->timestamp('tanggal_buat')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->foreignId('pembeli_id')->constrained('tbl_pembeli');
            $table->string('metode_pengiriman', 50);
            $table->text('alamat')->nullable();
            $table->foreignId('matauang_id')->constrained('tbl_matauang')->nullable();
            $table->decimal('rate_matauang', 15,2)->nullable();
            $table->decimal('total_harga', 15,2);
            $table->decimal('total_bayar', 15,2)->nullable();
            $table->string('wa_status', 50)->nullable();
            $table->string('status_bayar', 50)->nullable()->default('Belum lunas');
            $table->foreignId('status_id')->constrained('tbl_status')->default(1);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
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
