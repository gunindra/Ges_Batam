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
        Schema::create('tbl_ptges', function (Blueprint $table) {
            $table->id();
            $table->text('Paragraph_AboutUs')->nullable();
            $table->string('Image_AboutUs')->nullable();
            $table->text('Paragraph_WhyUs')->nullable();
            $table->string('Image_WhyUs')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('phones')->nullable();
            $table->string('api_key')->default('qpWaNfN8vSQ7I8m1JiqzqfyyLWG9uT');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_ptges');
    }
};
