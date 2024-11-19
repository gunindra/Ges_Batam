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
         Schema::create('tbl_sup_invoice_items', function (Blueprint $table) {
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
      *
      * @return void
      */
     public function down()
     {
         Schema::dropIfExists('tbl_sup_invoice_items');
     }
};

