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
    public function up(): void
    {
        Schema::create('tbl_credit_note_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_note_id')->constrained('tbl_credit_note')->onDelete('cascade');
            $table->string('no_resi');
            $table->string('deskripsi');
            $table->decimal('harga', 15, 2);
            $table->integer('jumlah');
            $table->decimal('total', 15, 2)->default(0);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_credit_note_item');
    }
};
