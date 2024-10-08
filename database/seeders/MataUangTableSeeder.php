<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MataUangTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $matauang = [
            ['nama_matauang' => 'Rupiah Indonesia', 'singkatan_matauang' => 'RP'],
            ['nama_matauang' => 'Dollar Singapura', 'singkatan_matauang' => 'SGD'],
            ['nama_matauang' => 'Yuan Tiongkok', 'singkatan_matauang' => 'RMB'],
        ];

        foreach ($matauang as $m) {
            DB::table('tbl_matauang')->updateOrInsert(
                ['singkatan_matauang' => $m['singkatan_matauang']],
                ['nama_matauang' => $m['nama_matauang']]
            );
        }
    }
}
