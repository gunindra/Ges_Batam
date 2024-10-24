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
        // Schema::create('tbl_cicilan', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('pembayaran_id')->constrained('tbl_pembayaran');
        //     $table->string('userlogin');
        //     $table->decimal('jumlah_cicilan', 15, 2);
        //     $table->date('tanggal_pembayaran');
        //     $table->enum('metode_pembayaran', ['Transfer', 'Tunai']);
        //     $table->string('bukti_pembayaran')->nullable();
        //     $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        //     $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_cicilan');
    }
};
