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
    protected $totalData;
    protected $currentJobIndex;

    public function __construct($data, $jobId, $totalData, $currentJobIndex)
    {
        $this->data = $data;
        $this->jobId = $jobId;
        $this->totalData = $totalData;
        $this->currentJobIndex = $currentJobIndex;
    }

    public function handle()
    {
        $failedItems = [];
        $processedCount = 0;

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            foreach ($this->data as $index => $resi) {
                try {
                    $resi = trim($resi);

                    // Periksa apakah No Resi sudah ada
                    $existingTracking = Tracking::where('no_resi', $resi)->first();
                    if ($existingTracking) {
                        throw new \Exception("No Resi {$resi} sudah ada.");
                    }

                    // Simpan data baru
                    Tracking::create([
                        'no_resi' => $resi,
                        'no_do' => $this->data['noDeliveryOrder'],
                        'status' => $this->data['status'],
                        'keterangan' => $this->data['keterangan'],
                        'company_id' => $this->data['companyId'],
                    ]);

                    $processedCount++;

                    // Hitung progress
                    $progress = round((($index + 1) / $this->totalData) * 100);
                    Cache::put("job_progress_{$this->jobId}", $progress, now()->addMinutes(5));

                } catch (\Exception $e) {
                    // Jika terjadi error pada satu item, simpan ke array failedItems dan lanjut ke data berikutnya
                    $failedItems[] = [
                        'resi' => $resi,
                        'error' => $e->getMessage()
                    ];
                }
            }

            // Jika semua sukses, commit transaksi
            DB::commit();

            // Simpan hasil sukses
            Cache::put("job_progress_{$this->jobId}", 100, now()->addMinutes(5));

            // Simpan data kegagalan jika ada
            if (!empty($failedItems)) {
                Cache::put("job_failed_{$this->jobId}", $failedItems, now()->addMinutes(10));
            }

        } catch (\Exception $e) {
            // Jika ada error fatal yang mempengaruhi seluruh batch
            DB::rollBack();
            Log::error("Gagal memproses data: " . $e->getMessage());

            // Menyimpan error ke cache
            Cache::put("job_failed_{$this->jobId}", ['error' => $e->getMessage()], now()->addMinutes(10));

            // Melempar exception agar queue tahu ada kegagalan
            throw new \Exception("Proses batch gagal, semua data dibatalkan.");
        }
    }

}
