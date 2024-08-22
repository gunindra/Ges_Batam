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
            $table->string('marking', 100)->unique();
            $table->string('nama_pembeli', 100);
            $table->text('alamat');
            $table->string('no_wa', 50);
            $table->string('sisa_poin', 50)->nullable();
            $table->string('category', 50);
            $table->timestamp('transaksi_terakhir')->nullable();
            $table->tinyInteger('status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_pembeli');
    }
}

