<?php

namespace Database\Seeders;

use App\Models\Kendaraan;
use App\Models\Pengguna;
use App\Models\Penugasan;
use Illuminate\Database\Seeder;

class PenugasanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Pengguna::where('role', 'super_admin')->first();
        $pemegang = Pengguna::where('role', 'user')->where('status', 'active')->get();
        $kendaraan = Kendaraan::all();

        if ($kendaraan->isEmpty() || $pemegang->isEmpty()) {
            $this->command->warn('Tidak ada kendaraan atau pemegang. Jalankan seeder lain terlebih dahulu.');
            return;
        }

        $tujuanList = [
            'Operasional harian paroki',
            'Kunjungan pastoral ke umat',
            'Pengantaran tamu/keuskupan',
            'Kegiatan sosial dan karitatif',
            'Perjalanan dinas keuskupan',
            'Pelayanan sakramen di stasi',
            'Kegiatan katekese dan pembinaan',
            'Rapat koordinasi kevikepan',
            'Kunjungan ke panti asuhan',
            'Pengantaran logistik gereja',
            'Kegiatan retret dan rekoleksi',
            'Pelayanan orang sakit',
        ];

        $penugasanData = [];
        $usedKendaraan = [];

        // Penugasan aktif untuk beberapa kendaraan (sekitar 40%)
        $jumlahAktif = (int) ($kendaraan->count() * 0.4);
        $kendaraanAktif = $kendaraan->random($jumlahAktif);

        foreach ($kendaraanAktif as $k) {
            $pemegangRandom = $pemegang->random();
            $tanggalMulai = now()->subDays(rand(1, 90));

            $penugasanData[] = [
                'kendaraan_id' => $k->id,
                'pemegang_id' => $pemegangRandom->id,
                'assigned_by' => $admin->id,
                'tanggal_mulai' => $tanggalMulai->format('Y-m-d'),
                'tanggal_selesai' => null,
                'status' => 'aktif',
                'tujuan' => $tujuanList[array_rand($tujuanList)],
                'catatan' => null,
                'created_at' => $tanggalMulai,
                'updated_at' => $tanggalMulai,
            ];

            $usedKendaraan[] = $k->id;

            // Update pemegang_id di kendaraan
            $k->update(['pemegang_id' => $pemegangRandom->id]);
        }

        // Penugasan selesai (riwayat) untuk beberapa kendaraan
        $kendaraanSelesai = $kendaraan->whereNotIn('id', $usedKendaraan)->random(min(15, $kendaraan->count() - count($usedKendaraan)));

        foreach ($kendaraanSelesai as $k) {
            // Buat 1-2 riwayat penugasan selesai
            $jumlahRiwayat = rand(1, 2);

            for ($i = 0; $i < $jumlahRiwayat; $i++) {
                $pemegangRandom = $pemegang->random();
                $tanggalMulai = now()->subMonths(rand(3, 18))->subDays(rand(0, 30));
                $durasi = rand(30, 180); // 1-6 bulan
                $tanggalSelesai = $tanggalMulai->copy()->addDays($durasi);

                // Pastikan tanggal selesai tidak di masa depan
                if ($tanggalSelesai->isFuture()) {
                    $tanggalSelesai = now()->subDays(rand(1, 30));
                }

                $penugasanData[] = [
                    'kendaraan_id' => $k->id,
                    'pemegang_id' => $pemegangRandom->id,
                    'assigned_by' => $admin->id,
                    'tanggal_mulai' => $tanggalMulai->format('Y-m-d'),
                    'tanggal_selesai' => $tanggalSelesai->format('Y-m-d'),
                    'status' => 'selesai',
                    'tujuan' => $tujuanList[array_rand($tujuanList)],
                    'catatan' => 'Penugasan selesai dengan baik',
                    'created_at' => $tanggalMulai,
                    'updated_at' => $tanggalSelesai,
                ];
            }
        }

        // Beberapa penugasan dibatalkan
        $kendaraanBatal = $kendaraan->random(min(3, $kendaraan->count()));
        foreach ($kendaraanBatal as $k) {
            $pemegangRandom = $pemegang->random();
            $tanggalMulai = now()->subMonths(rand(2, 6));

            $penugasanData[] = [
                'kendaraan_id' => $k->id,
                'pemegang_id' => $pemegangRandom->id,
                'assigned_by' => $admin->id,
                'tanggal_mulai' => $tanggalMulai->format('Y-m-d'),
                'tanggal_selesai' => $tanggalMulai->copy()->addDays(rand(5, 15))->format('Y-m-d'),
                'status' => 'dibatalkan',
                'tujuan' => $tujuanList[array_rand($tujuanList)],
                'catatan' => 'Dibatalkan karena perubahan jadwal',
                'created_at' => $tanggalMulai,
                'updated_at' => $tanggalMulai->copy()->addDays(rand(5, 15)),
            ];
        }

        Penugasan::insert($penugasanData);

        $this->command->info('Penugasan seeder completed: ' . count($penugasanData) . ' records created.');
        $this->command->info('  - Aktif: ' . count($kendaraanAktif));
    }
}
