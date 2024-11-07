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
        Schema::table('tbl_resi', function (Blueprint $table) {
            $table->decimal('priceperkg', 10, 2)->nullable()->after('no_do');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_resi', function (Blueprint $table) {
            $table->dropColumn('priceperkg');
        });
    }
};
