<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         Schema::create('tbl_sup_invoice', function (Blueprint $table) {
             $table->id();
             $table->string('invoice_no');
             $table->date('tanggal')->nullable();
             $table->unsignedBigInteger('vendor_id')->nullable();
             $table->foreign('vendor_id')->references('id')->on('tbl_sup_invoice')->onDelete('cascade');
             $table->string('no_ref');
             $table->foreignId('matauang_id')->constrained('tbl_matauang')->nullable();
             $table->decimal('rate_matauang', 20,2)->nullable();
             $table->string('status_bayar', 50)->nullable()->default('Belum lunas');
             $table->decimal('total_bayar', 15,2)->nullable();
             $table->decimal('total_harga', 15,2);
             $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
             $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
         });
     }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
         Schema::dropIfExists('tbl_sup_invoice');
     }
};


