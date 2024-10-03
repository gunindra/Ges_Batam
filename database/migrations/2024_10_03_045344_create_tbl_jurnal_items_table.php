<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tbl_jurnal_items', function (Blueprint $table) {
            $table->id(); // ID auto-increment
            $table->foreignId('jurnal_id')->constrained('tbl_jurnal')->onDelete('cascade'); // Referensi ke tbl_jurnal
            $table->string('code_account'); // Kode akun
            $table->string('description'); // Deskripsi item
            $table->decimal('debit', 15, 2)->default(0); // Jumlah debit
            $table->decimal('credit', 15, 2)->default(0); // Jumlah kredit
            $table->string('memo')->nullable(); // Kolom memo opsional
            // $table->foreignId('customer_invoice_id')->nullable()->constrained('tbl_customer_invoices')->onDelete('set null'); // Referensi opsional ke faktur pelanggan
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_jurnal_items');
    }
};
