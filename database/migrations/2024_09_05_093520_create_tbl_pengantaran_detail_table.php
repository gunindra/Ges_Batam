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
            $table->timestamps();
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
