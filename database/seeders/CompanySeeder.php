<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = [
            [
                'name' => 'PT GES LOGISTIC',
                'logo' => 'img/logo4.png', 
                'alamat' => '42Q2+6PH, Unnamed Road, Batu Selicin, Kec. Lubuk Baja, Kota Batam, Kepulauan Riau',
                'hp' => '62897767432',
                'email' => 'GESbatam@gmail.com',
            ],
        ];

        foreach ($company as $companys) {
            DB::table('tbl_company')->updateOrInsert(
                ['email' => $companys['email']],
                $companys,
            );
        }
    }
}
