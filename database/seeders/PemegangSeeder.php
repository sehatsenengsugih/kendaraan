<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PemegangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pemegangData = [
            // Paroki - Semarang
            [
                'name' => 'Paroki Katedral Semarang',
                'email' => 'katedral.smg@kas.or.id',
                'phone' => '024-8311500',
                'role' => 'user',
                'user_type' => 'paroki',
                'organization_name' => 'Paroki Katedral Santa Perawan Maria Ratu Rosario Suci',
                'status' => 'active',
                'password' => Hash::make('paroki123'),
            ],
            [
                'name' => 'Paroki Gedangan',
                'email' => 'gedangan@kas.or.id',
                'phone' => '024-7601234',
                'role' => 'user',
                'user_type' => 'paroki',
                'organization_name' => 'Paroki St. Yoseph Gedangan',
                'status' => 'active',
                'password' => Hash::make('paroki123'),
            ],
            [
                'name' => 'Paroki Banyumanik',
                'email' => 'banyumanik@kas.or.id',
                'phone' => '024-7471234',
                'role' => 'user',
                'user_type' => 'paroki',
                'organization_name' => 'Paroki St. Theresia Banyumanik',
                'status' => 'active',
                'password' => Hash::make('paroki123'),
            ],

            // Paroki - Surakarta
            [
                'name' => 'Paroki Purbayan Solo',
                'email' => 'purbayan@kas.or.id',
                'phone' => '0271-632145',
                'role' => 'user',
                'user_type' => 'paroki',
                'organization_name' => 'Paroki St. Antonius Purbayan',
                'status' => 'active',
                'password' => Hash::make('paroki123'),
            ],
            [
                'name' => 'Paroki Kebon Dalem',
                'email' => 'kebondalem@kas.or.id',
                'phone' => '0271-654321',
                'role' => 'user',
                'user_type' => 'paroki',
                'organization_name' => 'Paroki St. Petrus Kebon Dalem',
                'status' => 'active',
                'password' => Hash::make('paroki123'),
            ],

            // Paroki - Yogyakarta
            [
                'name' => 'Paroki Kotabaru Jogja',
                'email' => 'kotabaru@kas.or.id',
                'phone' => '0274-512345',
                'role' => 'user',
                'user_type' => 'paroki',
                'organization_name' => 'Paroki St. Antonius Kotabaru',
                'status' => 'active',
                'password' => Hash::make('paroki123'),
            ],
            [
                'name' => 'Paroki Pugeran Jogja',
                'email' => 'pugeran@kas.or.id',
                'phone' => '0274-378901',
                'role' => 'user',
                'user_type' => 'paroki',
                'organization_name' => 'Paroki St. Yoseph Pugeran',
                'status' => 'active',
                'password' => Hash::make('paroki123'),
            ],
            [
                'name' => 'Paroki Bintaran Jogja',
                'email' => 'bintaran@kas.or.id',
                'phone' => '0274-412345',
                'role' => 'user',
                'user_type' => 'paroki',
                'organization_name' => 'Paroki St. Yoseph Bintaran',
                'status' => 'active',
                'password' => Hash::make('paroki123'),
            ],

            // Lembaga
            [
                'name' => 'Yayasan Kanisius',
                'email' => 'kanisius@kas.or.id',
                'phone' => '0274-513456',
                'role' => 'user',
                'user_type' => 'lembaga',
                'organization_name' => 'Yayasan Pendidikan Kanisius',
                'status' => 'active',
                'password' => Hash::make('lembaga123'),
            ],
            [
                'name' => 'Yayasan Pangudi Luhur',
                'email' => 'pangudiluhur@kas.or.id',
                'phone' => '024-8412567',
                'role' => 'user',
                'user_type' => 'lembaga',
                'organization_name' => 'Yayasan Pangudi Luhur',
                'status' => 'active',
                'password' => Hash::make('lembaga123'),
            ],
            [
                'name' => 'Rumah Sakit St. Elisabeth',
                'email' => 'rselisabeth@kas.or.id',
                'phone' => '024-8310035',
                'role' => 'user',
                'user_type' => 'lembaga',
                'organization_name' => 'RS St. Elisabeth Semarang',
                'status' => 'active',
                'password' => Hash::make('lembaga123'),
            ],
            [
                'name' => 'Seminari Mertoyudan',
                'email' => 'seminari.mertoyudan@kas.or.id',
                'phone' => '0293-362145',
                'role' => 'user',
                'user_type' => 'lembaga',
                'organization_name' => 'Seminari Tinggi St. Paulus Mertoyudan',
                'status' => 'active',
                'password' => Hash::make('lembaga123'),
            ],

            // Pribadi (Imam/Romo)
            [
                'name' => 'Rm. Antonius Budi',
                'email' => 'rm.antonius@kas.or.id',
                'phone' => '08123456001',
                'role' => 'user',
                'user_type' => 'pribadi',
                'organization_name' => null,
                'status' => 'active',
                'password' => Hash::make('pribadi123'),
            ],
            [
                'name' => 'Rm. Yohanes Surya',
                'email' => 'rm.yohanes@kas.or.id',
                'phone' => '08123456002',
                'role' => 'user',
                'user_type' => 'pribadi',
                'organization_name' => null,
                'status' => 'active',
                'password' => Hash::make('pribadi123'),
            ],
            [
                'name' => 'Rm. Paulus Wibowo',
                'email' => 'rm.paulus@kas.or.id',
                'phone' => '08123456003',
                'role' => 'user',
                'user_type' => 'pribadi',
                'organization_name' => null,
                'status' => 'active',
                'password' => Hash::make('pribadi123'),
            ],
        ];

        foreach ($pemegangData as $pemegang) {
            Pengguna::create($pemegang);
        }
    }
}
