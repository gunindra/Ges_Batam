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
            $table->string('no_wa', 50);
            $table->string('sisa_poin', 50)->nullable();
            $table->foreignId('category_id')->constrained('tbl_category')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('tbl_users')->onDelete('cascade');
            $table->timestamp('transaksi_terakhir')->nullable();
            $table->tinyInteger('status');
            $table->timestamp('non_active_at')->nullable();
            $table->string('metode_pengiriman', 50);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });

    }

    public function down()
    {
        Schema::dropIfExists('tbl_pembeli');
    }
}

