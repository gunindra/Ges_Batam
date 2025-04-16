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
        Schema::create('tbl_retur_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retur_id')->constrained('tbl_retur')->onDelete('cascade');
            $table->foreignId('resi_id')->constrained('tbl_resi')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_retur_item');
    }
};
