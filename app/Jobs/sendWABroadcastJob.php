<?php

namespace App\Jobs;

use App\Models\WhatsappBroadcast;
use App\Jobs\SendWABroadcastDetailJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Log;
use Exception;

class sendWABroadcastJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $broadcastId;

    public function __construct($broadcastId)
    {
        $this->broadcastId = $broadcastId;
    }

    public function handle()
    {
        DB::beginTransaction();

        try {
            Log::info("Processing broadcast with ID: {$this->broadcastId}");

            // Find the broadcast with its recipients
            $broadcast = WhatsappBroadcast::with('recipients')->findOrFail($this->broadcastId);
            $broadcast->update(['status' => 'in queue']);

            $recipientJobs = [];

            // Loop over each recipient and dispatch the job for each
            foreach ($broadcast->recipients as $recipient) {
                // Dispatch each job and chain the final job
                SendWABroadcastDetailJob::dispatch($recipient->id, $broadcast->id);
            }

            // After all jobs for recipients, dispatch the job to update broadcast status to 'sent'
            dispatch(new UpdateBroadcastStatusJob($broadcast));

            DB::commit();

            Log::info("Broadcast with ID: {$this->broadcastId} processed successfully.");
        } catch (Exception $e) {
            DB::rollBack();

            // Handle broadcast-level failure
            if (isset($broadcast)) {
                $broadcast->update(['status' => 'failed']);
            }

            Log::error("Broadcast job failed for ID: {$this->broadcastId}. Error: {$e->getMessage()}");
        }
    }

    /**
     * Method to chain a job after all recipient jobs are complete
     * This job will update the broadcast status to 'sent'
     */
    private function afterAllJobsComplete($recipientJobs, $broadcast)
    {
        dispatch(new UpdateBroadcastStatusJob($broadcast));
    }
}
