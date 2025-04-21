<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HistoryTopup;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdateExpiredTopups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:expired-topups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        // Start the transaction
        DB::beginTransaction();

        try {
            $now = Carbon::now('Asia/Jakarta')->startOfDay();

            $this->info("Current date: " . $now->toDateString());

            $expiredTopups = HistoryTopup::where('status', 'active')
                ->whereDate('expired_date', '<', $now)
                ->get();

            foreach ($expiredTopups as $topup) {
                $customer = $topup->customer;

                if ($customer) {
                    $customer->sisa_poin = max(0, $customer->sisa_poin - $topup->balance);
                    $customer->save();
                }

                $topup->balance = 0;
                $topup->status = 'expired';
                $topup->save();

                $topupDate = Carbon::parse($topup->expired_date);
                $this->info("Top-up ID {$topup->id} expired on {$topupDate->toDateString()} and has been updated.");
            }

            DB::commit();

            $this->info('All expired top-ups have been updated.');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Failed to update expired top-ups: " . $e->getMessage());
        }
    }


}
