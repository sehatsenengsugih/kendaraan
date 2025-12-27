<?php

namespace Database\Seeders;

use App\Models\Merk;
use Illuminate\Database\Seeder;

class MerkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $merkData = [
            // Mobil
            ['nama' => 'Toyota', 'jenis' => 'mobil'],
            ['nama' => 'Honda', 'jenis' => 'mobil'],
            ['nama' => 'Mitsubishi', 'jenis' => 'mobil'],
            ['nama' => 'Suzuki', 'jenis' => 'mobil'],
            ['nama' => 'Daihatsu', 'jenis' => 'mobil'],
            ['nama' => 'Nissan', 'jenis' => 'mobil'],
            ['nama' => 'Hyundai', 'jenis' => 'mobil'],
            ['nama' => 'Kia', 'jenis' => 'mobil'],
            ['nama' => 'Mazda', 'jenis' => 'mobil'],
            ['nama' => 'Wuling', 'jenis' => 'mobil'],
            ['nama' => 'DFSK', 'jenis' => 'mobil'],
            ['nama' => 'Isuzu', 'jenis' => 'mobil'],
            ['nama' => 'Mercedes-Benz', 'jenis' => 'mobil'],
            ['nama' => 'BMW', 'jenis' => 'mobil'],

            // Motor
            ['nama' => 'Honda', 'jenis' => 'motor'],
            ['nama' => 'Yamaha', 'jenis' => 'motor'],
            ['nama' => 'Suzuki', 'jenis' => 'motor'],
            ['nama' => 'Kawasaki', 'jenis' => 'motor'],
            ['nama' => 'TVS', 'jenis' => 'motor'],
            ['nama' => 'Vespa', 'jenis' => 'motor'],
            ['nama' => 'Benelli', 'jenis' => 'motor'],
        ];

        foreach ($merkData as $merk) {
            Merk::create($merk);
        }
    }
}
