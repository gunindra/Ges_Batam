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
        Schema::table('tbl_payment_invoice', function (Blueprint $table) {
            $table->decimal('kuota', 15, 2)->nullable()->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('tbl_payment_invoice', function (Blueprint $table) {
            $table->dropColumn('kuota');
        });
    }
};
