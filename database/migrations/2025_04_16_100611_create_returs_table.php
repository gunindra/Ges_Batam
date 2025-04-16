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
        Schema::create('tbl_retur', function (Blueprint $table) {
            $table->id();
            // $table->string('no_retur')->unique();
            $table->foreignId('invoice_id')->constrained('tbl_invoice')->onDelete('cascade');
            $table->foreignId('currency_id')->constrained('tbl_matauang')->onDelete('restrict');
            $table->foreignId('account_id')->constrained('tbl_coa')->onDelete('restrict');
            $table->decimal('total_nominal', 15, 2);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_retur');
    }
};
