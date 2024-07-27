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
            $table->string('nama_barang', 100);
            $table->decimal('harga_barang', 10, 2);
            $table->foreignId('pembayaran_id')->constrained('tbl_pembayaran');
            $table->foreignId('matauang_id')->constrained('tbl_matauang');
            $table->foreignId('rekening_id')->constrained('tbl_rekening');
            $table->foreignId('supir_id')->constrained('tbl_supir');
            $table->foreignId('alamat_id')->constrained('tbl_tujuan');
            $table->text('bukti_pengantaran')->nullable();
            $table->foreignId('status_id')->constrained('tbl_status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_pemesanan');
    }
}
