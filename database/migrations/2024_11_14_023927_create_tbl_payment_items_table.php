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
        Schema::create('tbl_payment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('tbl_sup_invoice')->onDelete('cascade');
            $table->foreignId('coa_id')->constrained('tbl_coa')->onDelete('cascade'); 
            $table->string('description')->nullable();
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->string('memo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_payment_items');
    }
};
