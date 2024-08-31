<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CheckUserActivity extends Command
{
    protected $signature = 'check:user-activity';

    protected $description = 'Check user activity and update status if inactive';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $now = Carbon::now('Asia/Jakarta');
        $threshold = $now->subMinutes(3);

        $this->info("Threshold time: " . $threshold);

        $affectedRows = DB::table('tbl_pembeli')
            ->where('transaksi_terakhir', '<', $threshold)
            ->orWhereNull('transaksi_terakhir')
            ->update([
                'status' => 0,
                'non_active_at' => $now,
            ]);

        $this->info("User activity checked. Rows affected: $affectedRows");

        $users = DB::table('tbl_pembeli')
            ->where('transaksi_terakhir', '<', $threshold)
            ->orWhereNull('transaksi_terakhir')
            ->get();

        $this->info($users);
    }
}
