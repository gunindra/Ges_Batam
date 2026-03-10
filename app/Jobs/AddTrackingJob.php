<?php

namespace App\Jobs;

use App\Models\Tracking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AddTrackingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $jobId;
    protected $totalChunks;
    protected $currentChunkIndex;
    protected $companyId;
    protected $totalItems;

    public function __construct($data, $companyId, $jobId, $currentChunkIndex, $totalChunks, $totalItems = 0)
    {
        $this->data = $data;
        $this->companyId = $companyId;
        $this->jobId = $jobId;
        $this->totalChunks = $totalChunks;
        $this->currentChunkIndex = $currentChunkIndex;
        $this->totalItems = $totalItems;
    }

    public function handle()
    {
        $failedItems = [];
        $processedCount = 0;

        DB::beginTransaction();

        try {
            $insertData = [];
            foreach ($this->data['noResi'] as $resi) {
                $resi = trim($resi);

                if (Tracking::where('no_resi', $resi)->exists()) {
                    $failedItems[] = ['resi' => $resi, 'error' => "No Resi {$resi} sudah ada."];
                    continue;
                }

                $insertData[] = [
                    'no_resi' => $resi,
                    'no_do' => $this->data['noDeliveryOrder'],
                    'status' => $this->data['status'],
                    'keterangan' => $this->data['keterangan'],
                    'company_id' => $this->companyId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $processedCount++;
            }

            if (!empty($insertData)) {
                DB::table('tbl_tracking')->insertOrIgnore($insertData);
            }

            DB::commit();

            // Progress = percentage of chunks completed (chunk-based, not item-based)
            $progress = min(round((($this->currentChunkIndex + 1) / max($this->totalChunks, 1)) * 100), 100);
            Cache::put("job_progress_{$this->jobId}", $progress, now()->addMinutes(30));

            if (!empty($failedItems)) {
                $existing = Cache::get("job_failed_{$this->jobId}", []);
                Cache::put("job_failed_{$this->jobId}", array_merge($existing, $failedItems), now()->addMinutes(30));
            }

            // Mark overall job as completed when last chunk finishes
            if ($this->currentChunkIndex + 1 >= $this->totalChunks) {
                Cache::put("job_status_{$this->jobId}", 'completed', now()->addMinutes(30));
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal memproses data: " . $e->getMessage());
            $existing = Cache::get("job_failed_{$this->jobId}", []);
            Cache::put("job_failed_{$this->jobId}", array_merge($existing, [['error' => $e->getMessage()]]), now()->addMinutes(30));
            Cache::put("job_status_{$this->jobId}", 'failed', now()->addMinutes(30));
            throw new \Exception("Proses batch gagal, semua data dibatalkan.");
        }
    }
}
