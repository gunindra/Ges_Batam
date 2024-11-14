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
        Schema::create('tbl_history_topup', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id'); // Mengacu ke tabel tbl_pembeli
            $table->string('customer_name');
            $table->decimal('topup_amount', 15, 2);
            $table->decimal('remaining_points', 15, 2);
            $table->timestamp('tanggal_topup');
            $table->decimal('price_per_kg', 15, 2); // Mengubah tipe data menjadi decimal
            $table->unsignedBigInteger('account_id');
            $table->decimal('balance', 15, 2)->default(0);
            $table->date('date');
            $table->string('code');
            $table->date('expired_date');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            // Foreign key constraint untuk customer_id, mengacu ke tbl_pembeli
            $table->foreign('customer_id')->references('id')->on('tbl_pembeli')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('tbl_coa')->onDelete('cascade');
        });




    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_history_topup');
    }
};
