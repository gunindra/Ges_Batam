<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HistoryTopup;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Admin\JournalController;
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
     * The journal controller instance.
     *
     * @var JournalController
     */
    protected $jurnalController;

    /**
     * Create a new command instance.
     *
     * @param JournalController $jurnalController
     * @return void
     */
    public function __construct(JournalController $jurnalController)
    {
        parent::__construct(); // This is the crucial line that was missing
        $this->jurnalController = $jurnalController;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now = Carbon::now('Asia/Jakarta')->startOfDay();
        $this->info("Current date: " . $now->toDateString());

        $expiredTopups = HistoryTopup::where('status', 'active')
            ->whereDate('expired_date', '<', $now)
            ->get();

        foreach ($expiredTopups as $topup) {
            DB::beginTransaction();
            try {
                 if (!in_array($topup->status, ['active'])) {
                    $this->info("Skipping top-up ID {$topup->id} because status is '{$topup->status}'.");
                    DB::rollBack();
                    continue;
                }

                // Skip jika balance sudah 0
                if ($topup->balance <= 0) {
                    $this->info("Skipping top-up ID {$topup->id} because balance is already 0.");
                    DB::rollBack();
                    continue;
                }

                $customer = Customer::findOrFail($topup->customer_id);

                $customer->sisa_poin = max(0, $customer->sisa_poin - $topup->balance);
                $customer->save();

                $topup->expired_amount = $topup->balance;
                $topup->balance = 0;
                $topup->status = 'expired';
                $topup->save();

                $this->jurnalController->createExpiredTopupJurnal($topup, $customer, $topup->company_id);

                DB::commit();
                $this->info("Top-up ID {$topup->id} expired successfully.");

            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("Failed to expire top-up ID {$topup->id}: " . $e->getMessage());
                continue;
            }
        }

        $this->info('All expired top-ups have been processed.');
    }
}
