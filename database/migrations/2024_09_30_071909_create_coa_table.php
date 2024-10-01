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
        Schema::create('tbl_coa', function (Blueprint $table) {
            $table->id();
            $table->string('code_account_id')->unique();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('set_as_group')->default(false);
            $table->string('default_posisi');
            $table->timestamps();
            $table->foreign('parent_id')->references('id')->on('tbl_coa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_coa');
    }
};
