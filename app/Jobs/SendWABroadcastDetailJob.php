<?php

namespace App\Jobs;

use App\Models\WhatsappBroadcast; 
use App\Models\WhatsappBroadcastDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Traits\WhatsappTrait;
use Log;
use Exception;

class SendWABroadcastDetailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, WhatsappTrait;

    protected $recipientId;
    protected $broadcastId;

    public function __construct($recipientId, $broadcastId)
    {
        $this->recipientId = $recipientId;
        $this->broadcastId = $broadcastId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // Retrieve the recipient and broadcast objects from the database
            $recipient = WhatsappBroadcastDetail::findOrFail($this->recipientId);
            $broadcast = WhatsappBroadcast::findOrFail($this->broadcastId);

            // Send the message to the recipient
            $response = $this->kirimPesanWhatsapp(
                $recipient->phone,
                $broadcast->message,
                $broadcast->media_path
            );

            // Update recipient status to sent
            $recipient->update([
                'status' => 'sent',
                'send_response' => $response,
            ]);

            Log::info("Message sent to recipient: {$recipient->phone}");
            sleep(2);

        } catch (Exception $e) {
            // Ensure recipient is defined before updating its status
            if (isset($recipient)) {
                // Update recipient status to failed
                $recipient->update([
                    'status' => 'failed',
                    'send_response' => $e->getMessage(),
                ]);
            }

            Log::error("Failed to send message to recipient: {$this->recipientId}. Error: {$e->getMessage()}");
        }
    }



}
