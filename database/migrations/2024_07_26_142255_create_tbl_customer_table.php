<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblCustomerTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_pembeli', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pembeli', 100);
            $table->text('alamat');
            $table->string('no_wa', 15);
            $table->string('category', 50);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_pembeli');
    }
}

