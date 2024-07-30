<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusTableSeeder extends Seeder
{
    public function run()
    {
        $statuses = [
            ['status_name' => 'Booking'],
            ['status_name' => 'Arrived'],
            ['status_name' => 'Paid'],
            ['status_name' => 'Delivering'],
            ['status_name' => 'Done'],
        ];

        DB::table('tbl_status')->insert($statuses);
    }
}
