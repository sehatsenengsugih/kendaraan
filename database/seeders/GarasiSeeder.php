<?php

namespace Database\Seeders;

use App\Models\Garasi;
use App\Models\Kevikepan;
use Illuminate\Database\Seeder;

class GarasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kevikepanSemarang = Kevikepan::where('kode', 'SMG')->first();
        $kevikepanSurakarta = Kevikepan::where('kode', 'SKA')->first();
        $kevikepanJogjaTimur = Kevikepan::where('kode', 'JGT')->first();
        $kevikepanJogjaBarat = Kevikepan::where('kode', 'JGB')->first();
        $kevikepanMagelang = Kevikepan::where('kode', 'MGL')->first();

        $garasiData = [
            // Semarang
            [
                'nama' => 'Garasi Keuskupan Agung Semarang',
                'alamat' => 'Jl. Pandanaran No. 13',
                'kota' => 'Semarang',
                'kode_pos' => '50134',
                'kevikepan_id' => $kevikepanSemarang->id,
                'pic_name' => 'Pak Yohanes',
                'pic_phone' => '08123456789',
            ],
            [
                'nama' => 'Garasi Paroki Katedral Semarang',
                'alamat' => 'Jl. Pandanaran No. 5',
                'kota' => 'Semarang',
                'kode_pos' => '50134',
                'kevikepan_id' => $kevikepanSemarang->id,
                'pic_name' => 'Romo Agustinus',
                'pic_phone' => '08234567890',
            ],
            // Surakarta
            [
                'nama' => 'Garasi Paroki St. Petrus Solo',
                'alamat' => 'Jl. Slamet Riyadi No. 222',
                'kota' => 'Surakarta',
                'kode_pos' => '57141',
                'kevikepan_id' => $kevikepanSurakarta->id,
                'pic_name' => 'Pak Benediktus',
                'pic_phone' => '08345678901',
            ],
            // Jogja Timur
            [
                'nama' => 'Garasi Paroki St. Yoseph Bintaran',
                'alamat' => 'Jl. Bintaran Kidul No. 12',
                'kota' => 'Yogyakarta',
                'kode_pos' => '55151',
                'kevikepan_id' => $kevikepanJogjaTimur->id,
                'pic_name' => 'Bu Maria',
                'pic_phone' => '08456789012',
            ],
            // Jogja Barat
            [
                'nama' => 'Garasi Paroki St. Antonius Kotabaru',
                'alamat' => 'Jl. Ahmad Jazuli No. 2',
                'kota' => 'Yogyakarta',
                'kode_pos' => '55224',
                'kevikepan_id' => $kevikepanJogjaBarat->id,
                'pic_name' => 'Pak Stefanus',
                'pic_phone' => '08567890123',
            ],
            // Magelang
            [
                'nama' => 'Garasi Paroki St. Ignatius Magelang',
                'alamat' => 'Jl. Ade Irma Suryani No. 1',
                'kota' => 'Magelang',
                'kode_pos' => '56117',
                'kevikepan_id' => $kevikepanMagelang->id,
                'pic_name' => 'Romo Paulus',
                'pic_phone' => '08678901234',
            ],
        ];

        foreach ($garasiData as $garasi) {
            Garasi::create($garasi);
        }
    }
}
