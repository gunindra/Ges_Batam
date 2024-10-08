<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusTableSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['status_name' => 'Batam / Sortir'],
            ['status_name' => 'Ready For Pickup'],
            ['status_name' => 'Out For Delivery'],
            ['status_name' => 'Delivering'],
            ['status_name' => 'Debt'],
            ['status_name' => 'Done'],
        ];

        foreach ($statuses as $status) {
            DB::table('tbl_status')->updateOrInsert(
                ['status_name' => $status['status_name']],
                $status
            );
        }
    }
}
