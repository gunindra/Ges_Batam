<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblTujuanTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_tujuan', function (Blueprint $table) {
            $table->id();
            $table->text('alamat');
            $table->string('kota', 100);
            $table->string('provinsi', 100);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_tujuan');
    }
}

