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
        Schema::create('tbl_usage_points', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('history_topup_id');
            $table->decimal('used_points', 15, 2);
            $table->decimal('price_per_kg', 15, 2);
            $table->date('usage_date');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            // Foreign key constraints
            $table->foreign('customer_id')->references('id')->on('tbl_pembeli')->onDelete('cascade');
            $table->foreign('history_topup_id')->references('id')->on('tbl_history_topup')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_usage_points');
    }
};
