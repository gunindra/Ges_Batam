<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblMatauangTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_matauang', function (Blueprint $table) {
            $table->id();
            $table->string('nama_matauang', 100);
            $table->string('singkatan_matauang', 10);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_matauang');
    }
}


