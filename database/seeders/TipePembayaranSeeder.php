<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipePembayaranSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['tipe_pembayaran' => 'Cash'],
            ['tipe_pembayaran' => 'Transfer'],
            ['tipe_pembayaran' => 'Poin'],
        ];

        DB::table('tbl_tipe_pembayaran')->insert($statuses);
    }
}
