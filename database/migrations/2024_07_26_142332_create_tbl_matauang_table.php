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
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_matauang');
    }
}


