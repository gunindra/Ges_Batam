<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblPoinTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_poin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembeli_id')->constrained('tbl_pembeli');
            $table->date('tanggal_transaksi');
            $table->integer('poin');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_poin');
    }
}

