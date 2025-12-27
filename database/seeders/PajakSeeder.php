<?php

namespace Database\Seeders;

use App\Models\Kendaraan;
use App\Models\Pajak;
use App\Models\Pengguna;
use Illuminate\Database\Seeder;

class PajakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Pengguna::where('role', 'super_admin')->first();
        $kendaraan = Kendaraan::all();

        if ($kendaraan->isEmpty()) {
            $this->command->warn('Tidak ada kendaraan. Jalankan KendaraanSeeder terlebih dahulu.');
            return;
        }

        $pajakData = [];

        foreach ($kendaraan as $index => $k) {
            // Pajak tahunan untuk semua kendaraan
            $dueDate = now()->subMonths(rand(-6, 12)); // Random -6 bulan lalu sampai 12 bulan ke depan
            $status = 'belum_bayar';
            $tanggalBayar = null;

            // 40% sudah lunas
            if (rand(1, 100) <= 40) {
                $status = 'lunas';
                $tanggalBayar = $dueDate->copy()->subDays(rand(1, 30));
            } elseif ($dueDate->isPast()) {
                $status = 'terlambat';
            }

            $pajakData[] = [
                'kendaraan_id' => $k->id,
                'jenis' => 'tahunan',
                'tanggal_jatuh_tempo' => $dueDate->format('Y-m-d'),
                'tanggal_bayar' => $tanggalBayar?->format('Y-m-d'),
                'nominal' => rand(1, 5) * 100000 + rand(50, 500) * 1000, // 150rb - 1jt
                'denda' => $status === 'terlambat' ? rand(1, 5) * 25000 : 0,
                'status' => $status,
                'nomor_notice' => $status === 'lunas' ? 'NOT-' . strtoupper(substr(md5(rand()), 0, 8)) : null,
                'catatan' => null,
                'created_by' => $admin->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Pajak 5 tahunan untuk beberapa kendaraan (setiap 5 kendaraan)
            if ($index % 5 === 0) {
                $dueDate5 = now()->addMonths(rand(-3, 18));
                $status5 = 'belum_bayar';
                $tanggalBayar5 = null;

                if (rand(1, 100) <= 30) {
                    $status5 = 'lunas';
                    $tanggalBayar5 = $dueDate5->copy()->subDays(rand(1, 14));
                } elseif ($dueDate5->isPast()) {
                    $status5 = 'terlambat';
                }

                $pajakData[] = [
                    'kendaraan_id' => $k->id,
                    'jenis' => 'lima_tahunan',
                    'tanggal_jatuh_tempo' => $dueDate5->format('Y-m-d'),
                    'tanggal_bayar' => $tanggalBayar5?->format('Y-m-d'),
                    'nominal' => rand(3, 8) * 100000 + rand(100, 900) * 1000, // 400rb - 1.7jt
                    'denda' => $status5 === 'terlambat' ? rand(2, 8) * 25000 : 0,
                    'status' => $status5,
                    'nomor_notice' => $status5 === 'lunas' ? 'NOT5-' . strtoupper(substr(md5(rand()), 0, 8)) : null,
                    'catatan' => 'Ganti plat nomor',
                    'created_by' => $admin->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Tambah beberapa pajak yang akan jatuh tempo dalam 30 hari (untuk testing reminder)
        $kendaraanSample = $kendaraan->random(min(5, $kendaraan->count()));
        foreach ($kendaraanSample as $k) {
            $pajakData[] = [
                'kendaraan_id' => $k->id,
                'jenis' => 'tahunan',
                'tanggal_jatuh_tempo' => now()->addDays(rand(1, 30))->format('Y-m-d'),
                'tanggal_bayar' => null,
                'nominal' => rand(2, 4) * 100000 + rand(50, 500) * 1000,
                'denda' => 0,
                'status' => 'belum_bayar',
                'nomor_notice' => null,
                'catatan' => 'Pajak akan jatuh tempo',
                'created_by' => $admin->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Tambah beberapa pajak yang sudah terlambat (untuk testing)
        $kendaraanOverdue = $kendaraan->random(min(3, $kendaraan->count()));
        foreach ($kendaraanOverdue as $k) {
            $overdueDays = rand(5, 60);
            $pajakData[] = [
                'kendaraan_id' => $k->id,
                'jenis' => 'tahunan',
                'tanggal_jatuh_tempo' => now()->subDays($overdueDays)->format('Y-m-d'),
                'tanggal_bayar' => null,
                'nominal' => rand(2, 4) * 100000 + rand(50, 500) * 1000,
                'denda' => $overdueDays * 500, // Denda per hari
                'status' => 'belum_bayar',
                'nomor_notice' => null,
                'catatan' => 'Pajak terlambat ' . $overdueDays . ' hari',
                'created_by' => $admin->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Pajak::insert($pajakData);

        $this->command->info('Pajak seeder completed: ' . count($pajakData) . ' records created.');
    }
}
