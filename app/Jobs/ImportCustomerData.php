<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ImportCustomerData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $validData;
    protected $companyId;
    protected $jobId;
    protected $currentJobIndex;
    protected $totalJobs;

    public function __construct($validData, $companyId, $jobId, $currentJobIndex, $totalJobs)
    {
        $this->validData = $validData;
        $this->companyId = $companyId;
        $this->jobId = $jobId;
        $this->currentJobIndex = $currentJobIndex;
        $this->totalJobs = $totalJobs;
    }

    public function handle()
{
    $totalData = count($this->validData);
    $progress = 0;

    foreach ($this->validData as $index => $row) {
        DB::beginTransaction();

        try {
            // Insert user and get the user_id
            $user = User::create([
                'name' => $row['nama_customer'],
                'email' => $row['email'],
                'password' => Hash::make($row['password']),
                'role' => 'customer',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert pembeli with user_id
            $pembeliId = DB::table('tbl_pembeli')->insertGetId([
                'user_id' => $user->id,
                'marking' => $row['marking_costumer'],
                'nama_pembeli' => $row['nama_customer'],
                'no_wa' => $row['no_telpon'],
                'category_id' => $row['category_customer'],
                'metode_pengiriman' => $row['metode_pengiriman'],
                'status' => 1,
                'company_id' => $this->companyId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Process address if available
            $alamatCustomer = is_array($row['alamat_customer']) ? $row['alamat_customer'] : [$row['alamat_customer']];
            foreach ($alamatCustomer as $alamat) {
                if (!is_null($alamat) && trim($alamat) !== '') {
                    DB::table('tbl_alamat')->insert([
                        'pembeli_id' => $pembeliId,
                        'alamat' => trim($alamat),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::commit();  // Commit transaction if successful

            // Update progress for the current chunk
            $progress = round((($index + 1) / $totalData) * 100);

            // Store the chunk progress in cache for this job
            Cache::put("job_progress_{$this->jobId}_chunk_{$this->currentJobIndex}", $progress, now()->addMinutes(5));

        } catch (\Exception $e) {
            DB::rollback();
            Cache::put("job_progress_{$this->jobId}_chunk_{$this->currentJobIndex}", $progress, now()->addMinutes(5));
        }
    }

    // Calculate overall progress after all chunks
    $overallProgress = round((($this->currentJobIndex) / $this->totalJobs) * 100);
    Cache::put('job_progress_' . $this->jobId, $overallProgress, now()->addMinutes(5));

    // Mark job as completed if it's the last chunk
    if ($this->currentJobIndex == $this->totalJobs) {
        Cache::put('job_progress_' . $this->jobId, 100, now()->addMinutes(5));
    }
}
}
