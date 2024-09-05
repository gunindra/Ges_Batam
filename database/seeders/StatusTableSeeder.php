<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusTableSeeder extends Seeder
{
    public function run()
    {
        $statuses = [
            ['status_name' => 'Batam / Sortir'],
            ['status_name' => 'Ready For Pickup'],
            ['status_name' => 'Out For Delivery'],
            ['status_name' => 'Delivering'],
            ['status_name' => 'Debt'],
            ['status_name' => 'Done'],
        ];

        DB::table('tbl_status')->insert($statuses);
    }
}
