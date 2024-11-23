<?php

namespace App\Jobs;

use App\Models\WhatsappBroadcast;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class UpdateBroadcastStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $broadcast;

    public function __construct(WhatsappBroadcast $broadcast)
    {
        $this->broadcast = $broadcast;
    }

    public function handle()
    {
        // Update the broadcast status to 'sent'
        $this->broadcast->update(['status' => 'sent']);
        Log::info("Broadcast with ID: {$this->broadcast->id} marked as sent.");
    }
}
