<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            KevikepanSeeder::class,
            SuperAdminSeeder::class,
            PemegangSeeder::class,
            GarasiSeeder::class,
            MerkSeeder::class,
            KendaraanSeeder::class,
            PenugasanSeeder::class,
            PajakSeeder::class,
            ServisSeeder::class,
        ]);
    }
}
