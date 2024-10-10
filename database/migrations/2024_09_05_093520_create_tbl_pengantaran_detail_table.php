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
        Schema::create('tbl_pengantaran_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengantaran_id')->constrained('tbl_pengantaran')->onDelete('cascade');
            $table->foreignId('invoice_id')->constrained('tbl_invoice')->onDelete('cascade');
            $table->text('bukti_pengantaran')->nullable();
            $table->text('tanda_tangan')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_pengantaran_detail');
    }
};
