<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $logoPath = 'logos/logo4.png';
        if (!Storage::disk('public')->exists($logoPath)) {
            $sourcePath = public_path('img/logo4.png');
            if (file_exists($sourcePath)) {
                Storage::disk('public')->put($logoPath, file_get_contents($sourcePath));
            } else {
                echo "Source logo file not found at $sourcePath. Seeder aborted.";
                return;
            }
        }

        $company = [
            [
                'name' => 'PT GES LOGISTIC',
                'logo' => $logoPath,
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
