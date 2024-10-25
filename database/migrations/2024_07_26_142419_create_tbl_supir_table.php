<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblSupirTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_supir', function (Blueprint $table) {
            $table->id();
            $table->string('nama_supir', 100);
            $table->text('alamat_supir');
            $table->string('no_wa', 15);
            $table->string('image_sim', 100);
            $table->foreignId('user_id')->nullable()->constrained('tbl_users')->onDelete('cascade'); // Foreign key to users
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_supir');
    }
}

