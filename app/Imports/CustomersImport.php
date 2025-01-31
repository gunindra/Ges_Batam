<?php

namespace App\Imports;

use App\Models\Alamat;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class CustomersImport implements ToCollection, WithHeadingRow
{
    public function rules(): array
    {
        return [
            '*.marking_costumer' => 'required|string|max:255|unique:tbl_pembeli,marking',
            '*.email' => 'required|email|unique:tbl_users,email',
            '*.password' => 'required|min:6',
            '*.category_customer' => 'required|exists:tbl_category,id',
            '*.metode_pengiriman' => 'required|in:Delivery,Pickup',
        ];
    }

    public function collection(Collection $rows)
    {
        $companyId = session('active_company_id');

        // Array untuk menampung data batch
        $users = [];
        $pembelis = [];
        $alamatData = [];

        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                // Validasi data
                $validator = validator($row->toArray(), [
                    'marking_costumer' => 'required|string|max:255|unique:tbl_pembeli,marking',
                    'email' => 'required|email|unique:tbl_users,email',
                    'password' => 'required|min:6',
                    'category_customer' => 'required|exists:tbl_category,id',
                    'metode_pengiriman' => 'required|in:Delivery,Pickup',
                ]);

                if ($validator->fails()) {
                    throw new \Exception("Validation failed for row: " . json_encode($validator->errors()));
                }

                // Menambahkan data ke array users
                $users[] = [
                    'name' => $row['nama_customer'],
                    'email' => $row['email'],
                    'password' => Hash::make($row['password']),
                    'role' => 'customer',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Menambahkan data ke array pembelis
                $pembelis[] = [
                    'marking' => $row['marking_costumer'],
                    'nama_pembeli' => $row['nama_customer'],
                    'no_wa' => $row['no_telpon'],
                    'category_id' => $row['category_customer'],
                    'metode_pengiriman' => $row['metode_pengiriman'],
                    'status' => 1,
                    'company_id' => $companyId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Menambahkan data alamat ke array alamatData
                $alamatCustomer = is_array($row['alamat_customer']) ? $row['alamat_customer'] : [$row['alamat_customer']];
                foreach ($alamatCustomer as $alamat) {
                    if (!is_null($alamat) && trim($alamat) !== '') {
                        $alamatData[] = [
                            'alamat' => trim($alamat),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }

            // Insert users dalam batch
            $userIds = User::insert($users);

            // Insert pembelis dalam batch dan mendapatkan ID
            $pembeliIds = [];
            foreach ($pembelis as $pembeli) {
                $pembeliIds[] = DB::table('tbl_pembeli')->insertGetId($pembeli);
            }

            // Menambahkan pembeli_id untuk alamat
            foreach ($alamatData as $key => $alamat) {
                $alamatData[$key]['pembeli_id'] = $pembeliIds[$key];
            }

            // Insert alamat dalam batch
            DB::table('tbl_alamat')->insert($alamatData);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Gagal mengimpor pelanggan: " . $e->getMessage());
        }
    }
}
