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
        Schema::create('tbl_assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_code')->nullable();
            $table->string('asset_name');
            $table->decimal('acquisition_price', 15, 2)->default(0);
            $table->date('acquisition_date');
            $table->date('depreciation_date');
            $table->decimal('residue_value', 15, 2)->default(0);
            $table->integer('estimated_age');
            $table->foreignId('depreciation_account')->references('id')->on('tbl_coa')->onDelete('cascade');
            $table->foreignId('accumulated_account')->references('id')->on('tbl_coa')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_assets');
    }
};
