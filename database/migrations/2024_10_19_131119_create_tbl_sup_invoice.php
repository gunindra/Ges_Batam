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
             $table->string('vendor');
             // $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
             $table->foreignId('matauang_id')->constrained('tbl_matauang')->nullable();
             $table->decimal('rate_matauang', 20,2)->nullable();
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


