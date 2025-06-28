<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        Schema::table('tbl_jurnal', function (Blueprint $table) {
            $table->unsignedBigInteger('credit_note_id')->nullable()->after('invoice_id');
            $table->foreign('credit_note_id')->references('id')->on('tbl_credit_note')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('tbl_jurnal', function (Blueprint $table) {
            $table->dropForeign(['credit_note_id']);
            $table->dropColumn('credit_note_id');
        });
    }
};
