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
            ['tipe_pembayaran' => 'Cicilan'],
        ];

        foreach ($statuses as $status) {
            DB::table('tbl_tipe_pembayaran')->updateOrInsert(
                ['tipe_pembayaran' => $status['tipe_pembayaran']],
                $status
            );
        }
    }
}
