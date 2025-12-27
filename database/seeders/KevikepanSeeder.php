<?php

namespace Database\Seeders;

use App\Models\Kevikepan;
use Illuminate\Database\Seeder;

class KevikepanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Sesuai PRD: 6 Kevikepan di Keuskupan Agung Semarang
     */
    public function run(): void
    {
        $kevikepan = [
            [
                'kode' => 'SMG',
                'nama' => 'Kevikepan Semarang',
                'deskripsi' => 'Wilayah Kevikepan Semarang dan sekitarnya',
            ],
            [
                'kode' => 'SKA',
                'nama' => 'Kevikepan Surakarta',
                'deskripsi' => 'Wilayah Kevikepan Surakarta dan sekitarnya',
            ],
            [
                'kode' => 'JGT',
                'nama' => 'Kevikepan Jogja Timur',
                'deskripsi' => 'Wilayah Kevikepan Yogyakarta bagian Timur',
            ],
            [
                'kode' => 'JGB',
                'nama' => 'Kevikepan Jogja Barat',
                'deskripsi' => 'Wilayah Kevikepan Yogyakarta bagian Barat',
            ],
            [
                'kode' => 'MGL',
                'nama' => 'Kevikepan Magelang',
                'deskripsi' => 'Wilayah Kevikepan Magelang dan sekitarnya',
            ],
            [
                'kode' => 'KTG',
                'nama' => 'Kevikepan Kategorial',
                'deskripsi' => 'Kevikepan untuk kategori khusus (lembaga keuskupan, dll)',
            ],
        ];

        foreach ($kevikepan as $item) {
            Kevikepan::updateOrCreate(
                ['kode' => $item['kode']],
                $item
            );
        }
    }
}
