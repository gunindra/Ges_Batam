<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblPoinTable extends Migration
{
    public function up()
    {
        // Schema::create('tbl_poin', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('pembeli_id')->constrained('tbl_pembeli');
        //     $table->date('tanggal_pembelianpoin');
        //     $table->integer('poin');
        //     $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        //     $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        // });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_poin');
    }
}

