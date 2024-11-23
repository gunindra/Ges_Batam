<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('whatsapp_broadcast_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('whatsapp_broadcast_id')->nullable()->constrained('whatsapp_broadcasts')->onDelete('cascade');
            $table->string('recipient');
            $table->string('phone');
            $table->datetime('send_time');
            $table->enum('status', ['pending', 'in queue', 'sent', 'failed'])->default('pending');
            $table->text('send_response');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_broadcast_details');
    }
};
