<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tbl_resi', function (Blueprint $table) {
            $table->dropUnique(['no_resi']);
        });
    }

    /**
     * Balikkan perubahan jika diperlukan.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_resi', function (Blueprint $table) {
            $table->unique('no_resi');
        });
    }
};
