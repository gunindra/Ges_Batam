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
            $table->foreignId('invoice_id')->constrained('tbl_invoice');
            $table->decimal('harga', 15,2);
            $table->foreignId('pembayaran_id')->constrained('tbl_tipe_pembayaran');
            $table->foreignId('rekening_id')->nullable()->constrained('tbl_rekening');
            $table->string('bukti_pembayaran', 100)->nullable();
            $table->foreignId('status_id')->constrained('tbl_status');
            $table->enum('status_pembayaran', ['Lunas', 'Belum lunas']);
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
