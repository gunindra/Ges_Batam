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

    public function __construct($validData, $companyId, $jobId)
    {
        $this->validData = $validData;
        $this->companyId = $companyId;
        $this->jobId = $jobId;
    }

    public function handle()
    {
        $totalData = count($this->validData);
        $progress = 0;

        foreach ($this->validData as $index => $row) {
            DB::beginTransaction();  // Mulai transaksi

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

                // Insert pembeli dengan user_id yang baru dibuat
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

                // Proses alamat (jika ada)
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

                DB::commit();  // Commit transaksi jika semua berhasil

                // Update progress dengan pembulatan ke angka bulat
                $progress = round((($index + 1) / $totalData) * 100);

                // Simpan progres ke dalam cache
                Cache::put('job_progress_' . $this->jobId, $progress, now()->addMinutes(5));
            } catch (\Exception $e) {
                DB::rollback();  // Rollback jika ada error
                // Log error or handle it
                Cache::put('job_progress_' . $this->jobId, $progress, now()->addMinutes(5));
                // You might want to log or notify about the error
            }
        }

        // Mark job as completed (progress 100%)
        Cache::put('job_progress_' . $this->jobId, 100, now()->addMinutes(5));
    }
}
