<?php

namespace Database\Seeders;

use App\Models\Kendaraan;
use App\Models\Pengguna;
use App\Models\Servis;
use Illuminate\Database\Seeder;

class ServisSeeder extends Seeder
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

        $bengkelList = [
            'Bengkel Resmi Toyota',
            'Bengkel Resmi Honda',
            'Bengkel Resmi Yamaha',
            'Bengkel Jaya Motor',
            'Bengkel Mitra Sejahtera',
            'Bengkel Cahaya Motor',
            'Auto 2000 Semarang',
            'Honda Semarang Center',
            'Yamaha Flagship Semarang',
            'Bengkel Pak Budi',
            'Bengkel Makmur Jaya',
        ];

        $deskripsiRutin = [
            'Ganti oli mesin dan filter oli',
            'Service rutin berkala 10.000 km',
            'Tune up mesin dan pengecekan kelistrikan',
            'Ganti oli mesin, oli gardan, dan filter udara',
            'Service rutin + pengecekan rem',
            'Ganti oli mesin dan cek kondisi ban',
        ];

        $deskripsiPerbaikan = [
            'Perbaikan AC tidak dingin, ganti freon dan service kompresor',
            'Ganti kampas rem depan dan belakang',
            'Perbaikan suspensi, ganti shockbreaker',
            'Ganti timing belt dan water pump',
            'Perbaikan power steering bocor',
            'Ganti aki dan perbaikan dinamo starter',
            'Perbaikan radiator bocor',
            'Ganti kopling set',
        ];

        $deskripsiDarurat = [
            'Mesin overheat, perbaikan radiator dan thermostat',
            'Ban pecah di jalan, ganti ban baru',
            'Aki soak mendadak, ganti aki baru',
            'Rem blong, ganti master rem dan kampas rem',
            'Mesin mogok, perbaikan fuel pump',
        ];

        $sparePartsList = [
            'Oli mesin Castrol 4L, Filter oli',
            'Filter oli, Filter udara, Busi NGK x4',
            'Kampas rem Bendix depan + belakang',
            'Shockbreaker Monroe x2, Bushing arm',
            'Timing belt kit, Water pump, Tensioner',
            'Aki GS Astra 45AH, Terminal aki',
            'Radiator coolant 5L, Thermostat',
            'Kopling set Exedy (plat, matahari, bearing)',
            'Freon R134a 2 kaleng, Oli kompresor',
        ];

        $servisData = [];

        foreach ($kendaraan as $index => $k) {
            // Setiap kendaraan punya 1-3 riwayat servis
            $jumlahServis = rand(1, 3);

            for ($i = 0; $i < $jumlahServis; $i++) {
                $jenis = $this->getRandomJenis();
                $tanggalServis = now()->subMonths(rand(1, 24))->subDays(rand(0, 30));
                $status = $this->getRandomStatus();
                $tanggalSelesai = null;
                $servisBerikutnya = null;
                $kmBerikutnya = null;

                if ($status === 'selesai') {
                    $tanggalSelesai = $tanggalServis->copy()->addDays(rand(1, 7));
                    // 60% punya jadwal servis berikutnya
                    if (rand(1, 100) <= 60) {
                        $servisBerikutnya = $tanggalSelesai->copy()->addMonths(rand(3, 6));
                        $kmBerikutnya = rand(5, 15) * 10000;
                    }
                } elseif ($status === 'dalam_proses') {
                    $tanggalServis = now()->subDays(rand(1, 5));
                } elseif ($status === 'dijadwalkan') {
                    $tanggalServis = now()->addDays(rand(1, 14));
                }

                $deskripsi = match ($jenis) {
                    'rutin' => $deskripsiRutin[array_rand($deskripsiRutin)],
                    'perbaikan' => $deskripsiPerbaikan[array_rand($deskripsiPerbaikan)],
                    'darurat' => $deskripsiDarurat[array_rand($deskripsiDarurat)],
                    'overhaul' => 'Overhaul mesin lengkap - turun mesin, ganti piston ring, metal jalan, seal-seal',
                };

                $biaya = match ($jenis) {
                    'rutin' => rand(3, 8) * 100000,
                    'perbaikan' => rand(5, 25) * 100000,
                    'darurat' => rand(8, 30) * 100000,
                    'overhaul' => rand(30, 80) * 100000,
                };

                $servisData[] = [
                    'kendaraan_id' => $k->id,
                    'jenis' => $jenis,
                    'tanggal_servis' => $tanggalServis->format('Y-m-d'),
                    'tanggal_selesai' => $tanggalSelesai?->format('Y-m-d'),
                    'kilometer' => rand(10, 150) * 1000,
                    'bengkel' => $bengkelList[array_rand($bengkelList)],
                    'deskripsi' => $deskripsi,
                    'spare_parts' => rand(1, 100) <= 70 ? $sparePartsList[array_rand($sparePartsList)] : null,
                    'biaya' => $biaya ?? 0,
                    'status' => $status,
                    'bukti_path' => null,
                    'servis_berikutnya' => $servisBerikutnya?->format('Y-m-d'),
                    'km_berikutnya' => $kmBerikutnya,
                    'catatan' => null,
                    'created_by' => $admin->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Tambah beberapa servis yang jadwal berikutnya akan segera tiba (untuk testing reminder)
        $kendaraanSample = $kendaraan->random(min(5, $kendaraan->count()));
        foreach ($kendaraanSample as $k) {
            $tanggalServis = now()->subMonths(rand(3, 6));
            $servisData[] = [
                'kendaraan_id' => $k->id,
                'jenis' => 'rutin',
                'tanggal_servis' => $tanggalServis->format('Y-m-d'),
                'tanggal_selesai' => $tanggalServis->copy()->addDays(1)->format('Y-m-d'),
                'kilometer' => rand(40, 80) * 1000,
                'bengkel' => $bengkelList[array_rand($bengkelList)],
                'deskripsi' => 'Service rutin berkala',
                'spare_parts' => 'Oli mesin, Filter oli, Filter udara',
                'biaya' => rand(4, 6) * 100000,
                'status' => 'selesai',
                'bukti_path' => null,
                'servis_berikutnya' => now()->addDays(rand(5, 25))->format('Y-m-d'), // Segera jatuh tempo
                'km_berikutnya' => rand(50, 90) * 1000,
                'catatan' => 'Servis berikutnya dalam waktu dekat',
                'created_by' => $admin->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Tambah beberapa servis dalam proses
        $kendaraanProses = $kendaraan->random(min(3, $kendaraan->count()));
        foreach ($kendaraanProses as $k) {
            $servisData[] = [
                'kendaraan_id' => $k->id,
                'jenis' => ['perbaikan', 'darurat'][array_rand(['perbaikan', 'darurat'])],
                'tanggal_servis' => now()->subDays(rand(1, 3))->format('Y-m-d'),
                'tanggal_selesai' => null,
                'kilometer' => rand(50, 100) * 1000,
                'bengkel' => $bengkelList[array_rand($bengkelList)],
                'deskripsi' => $deskripsiPerbaikan[array_rand($deskripsiPerbaikan)],
                'spare_parts' => $sparePartsList[array_rand($sparePartsList)],
                'biaya' => rand(8, 20) * 100000,
                'status' => 'dalam_proses',
                'bukti_path' => null,
                'servis_berikutnya' => null,
                'km_berikutnya' => null,
                'catatan' => 'Sedang dalam pengerjaan',
                'created_by' => $admin->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Servis::insert($servisData);

        $this->command->info('Servis seeder completed: ' . count($servisData) . ' records created.');
    }

    private function getRandomJenis(): string
    {
        $rand = rand(1, 100);
        if ($rand <= 50) return 'rutin';
        if ($rand <= 80) return 'perbaikan';
        if ($rand <= 95) return 'darurat';
        return 'overhaul';
    }

    private function getRandomStatus(): string
    {
        $rand = rand(1, 100);
        if ($rand <= 70) return 'selesai';
        if ($rand <= 85) return 'dalam_proses';
        if ($rand <= 95) return 'dijadwalkan';
        return 'dibatalkan';
    }
}
