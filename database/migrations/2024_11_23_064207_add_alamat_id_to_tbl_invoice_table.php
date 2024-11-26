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
        Schema::table('tbl_invoice', function (Blueprint $table) {
            $table->unsignedBigInteger('alamat_id')->nullable();
            $table->unsignedBigInteger('pembagi_id')->nullable();
            $table->unsignedBigInteger('rateberat_id')->nullable();
            $table->unsignedBigInteger('ratevolume_id')->nullable();

            $table->foreign('alamat_id')->references('id')->on('tbl_alamat')->onDelete('cascade');
            $table->foreign('pembagi_id')->references('id')->on('tbl_pembagi')->onDelete('cascade');
            $table->foreign('rateberat_id')->references('id')->on('tbl_rate')->onDelete('cascade');
            $table->foreign('ratevolume_id')->references('id')->on('tbl_rate')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_invoice', function (Blueprint $table) {
            $table->dropColumn('alamat_id');
            $table->dropColumn('pembagi_id');
            $table->dropColumn('rate_id');
            $table->dropColumn('ratevolume_id');
        });
    }
};
