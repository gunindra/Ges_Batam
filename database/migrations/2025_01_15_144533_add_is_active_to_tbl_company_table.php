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
        Schema::table('tbl_company', function (Blueprint $table) {
            $table->boolean('is_active')->default(0)->after('alamat'); // Atur default menjadi 0 (Off)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_company', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
