<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblStatusTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_status', function (Blueprint $table) {
            $table->id();
            $table->string('status_name', 50);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_status');
    }
}
