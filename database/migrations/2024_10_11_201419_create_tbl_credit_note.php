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
        Schema::create('tbl_credit_note', function (Blueprint $table) {
            $table->id();
            $table->string('no_creditnote');
            $table->foreignId('invoice_id')->constrained('tbl_invoice')->onDelete('cascade');
            $table->foreignId('account_id')->constrained('tbl_coa')->onDelete('cascade');
            $table->foreignId('matauang_id')->constrained('tbl_matauang')->nullable();
            $table->text('note')->nullable();
            $table->decimal('total_keseluruhan', 15, 2)->default(0);
            $table->decimal('rate_currency', 15, 2)->nullable();
            // $table->string('status_bayar')->default("Draft");
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
        Schema::dropIfExists('tbl_credit_note');
    }
};
