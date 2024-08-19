<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_users')->insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'), // Enkripsi password
                'role' => 'admin',
            ],
            // [
            //     'name' => 'Regular User',
            //     'email' => 'user@example.com',
            //     'password' => Hash::make('password'),
            //     'role' => 'user',
            // ],
        ]);
    }
}
