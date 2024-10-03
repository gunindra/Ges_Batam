<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tbl_jurnal', function (Blueprint $table) {
            $table->id();
            $table->string('no_journal')->unique();
            $table->string('tipe_kode');
            $table->date('tanggal');
            $table->string('no_ref');
            $table->string('status');
            $table->text('description');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_jurnal');
    }
};
