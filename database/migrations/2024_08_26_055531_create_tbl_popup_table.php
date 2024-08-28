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
        Schema::create('tbl_popup', function (Blueprint $table) {
            $table->id();
            $table->string('Judul_Popup');
            $table->text('Paraf_Popup');
            $table->string('Link_Popup');
            $table->string('Image_Popup');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_popup');
    }
};
