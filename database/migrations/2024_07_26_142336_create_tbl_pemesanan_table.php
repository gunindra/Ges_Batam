<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblPemesananTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_pemesanan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pemesanan', 50);
            $table->dateTime('tanggal_pemesanan');
            $table->foreignId('pembeli_id')->constrained('tbl_pembeli');
            $table->decimal('total_harga', 10, 2);
            $table->foreignId('status_id')->constrained('tbl_status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_pemesanan');
    }
}
