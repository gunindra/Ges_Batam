<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tbl_jurnal_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jurnal_id')->constrained('tbl_jurnal')->onDelete('cascade');
            $table->foreignId('code_account')->constrained('tbl_coa')->onDelete('cascade');
            $table->string('description');
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->string('memo')->nullable(); // Kolom memo opsional
            // $table->foreignId('customer_invoice_id')->nullable()->constrained('tbl_customer_invoices')->onDelete('set null'); // Referensi opsional ke faktur pelanggan
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_jurnal_items');
    }
};
