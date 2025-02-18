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

    public function __construct($data, $companyId, $jobId, $currentChunkIndex, $totalChunks)
    {
        $this->data = $data;
        $this->companyId = $companyId;
        $this->jobId = $jobId;
        $this->totalChunks = $totalChunks;
        $this->currentChunkIndex = $currentChunkIndex;
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
                Tracking::insert($insertData);
            }

            DB::commit();

            $totalProcessed = ($this->currentChunkIndex * count($this->data['noResi'])) + $processedCount;
            $progress = min(round(($totalProcessed / max($this->totalChunks, 1)) * 100), 100);

            Cache::put("job_progress_{$this->jobId}", $progress, now()->addMinutes(5));

            if (!empty($failedItems)) {
                Cache::put("job_failed_{$this->jobId}", $failedItems, now()->addMinutes(10));
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal memproses data: " . $e->getMessage());
            Cache::put("job_failed_{$this->jobId}", ['error' => $e->getMessage()], now()->addMinutes(10));
            throw new \Exception("Proses batch gagal, semua data dibatalkan.");
        }
    }
}
