<?php

namespace Database\Seeders;

use App\Models\Garasi;
use App\Models\Kendaraan;
use App\Models\Merk;
use App\Models\Pengguna;
use Illuminate\Database\Seeder;

class KendaraanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get merks
        $toyotaMobil = Merk::where('nama', 'Toyota')->where('jenis', 'mobil')->first();
        $hondaMobil = Merk::where('nama', 'Honda')->where('jenis', 'mobil')->first();
        $mitsubishiMobil = Merk::where('nama', 'Mitsubishi')->where('jenis', 'mobil')->first();
        $suzukiMobil = Merk::where('nama', 'Suzuki')->where('jenis', 'mobil')->first();
        $daihatsuMobil = Merk::where('nama', 'Daihatsu')->where('jenis', 'mobil')->first();
        $isuzuMobil = Merk::where('nama', 'Isuzu')->where('jenis', 'mobil')->first();
        $hondaMotor = Merk::where('nama', 'Honda')->where('jenis', 'motor')->first();
        $yamahaMotor = Merk::where('nama', 'Yamaha')->where('jenis', 'motor')->first();

        // Get garasi
        $garasiKAS = Garasi::where('nama', 'like', '%Keuskupan Agung%')->first();
        $garasiKatedral = Garasi::where('nama', 'like', '%Katedral%')->first();
        $garasiSolo = Garasi::where('nama', 'like', '%Solo%')->first();
        $garasiBintaran = Garasi::where('nama', 'like', '%Bintaran%')->first();
        $garasiKotabaru = Garasi::where('nama', 'like', '%Kotabaru%')->first();
        $garasiMagelang = Garasi::where('nama', 'like', '%Magelang%')->first();

        // Get pemegang (users)
        $parokiKatedral = Pengguna::where('email', 'katedral.smg@kas.or.id')->first();
        $parokiGedangan = Pengguna::where('email', 'gedangan@kas.or.id')->first();
        $parokiBanyumanik = Pengguna::where('email', 'banyumanik@kas.or.id')->first();
        $parokiPurbayan = Pengguna::where('email', 'purbayan@kas.or.id')->first();
        $parokiKebonDalem = Pengguna::where('email', 'kebondalem@kas.or.id')->first();
        $parokiKotabaru = Pengguna::where('email', 'kotabaru@kas.or.id')->first();
        $parokiBintaran = Pengguna::where('email', 'bintaran@kas.or.id')->first();
        $kanisius = Pengguna::where('email', 'kanisius@kas.or.id')->first();
        $pangudiluhur = Pengguna::where('email', 'pangudiluhur@kas.or.id')->first();
        $rsElisabeth = Pengguna::where('email', 'rselisabeth@kas.or.id')->first();
        $seminari = Pengguna::where('email', 'seminari.mertoyudan@kas.or.id')->first();
        $rmAntonius = Pengguna::where('email', 'rm.antonius@kas.or.id')->first();
        $rmYohanes = Pengguna::where('email', 'rm.yohanes@kas.or.id')->first();
        $rmPaulus = Pengguna::where('email', 'rm.paulus@kas.or.id')->first();

        $kendaraanData = [
            // ============ KENDARAAN KEUSKUPAN ============
            [
                'plat_nomor' => 'H 1 KAS',
                'nomor_bpkb' => 'BPKB-KAS-001',
                'merk_id' => $toyotaMobil->id,
                'nama_model' => 'Innova Reborn V',
                'tahun_pembuatan' => 2023,
                'warna' => 'Putih Mutiara',
                'jenis' => 'mobil',
                'garasi_id' => $garasiKAS->id,
                'pemegang_id' => null,
                'status' => 'aktif',
                'tanggal_perolehan' => '2023-03-15',
                'catatan' => 'Kendaraan operasional Uskup',
            ],
            [
                'plat_nomor' => 'H 2 KAS',
                'nomor_bpkb' => 'BPKB-KAS-002',
                'merk_id' => $hondaMobil->id,
                'nama_model' => 'CR-V Turbo',
                'tahun_pembuatan' => 2022,
                'warna' => 'Hitam Kristal',
                'jenis' => 'mobil',
                'garasi_id' => $garasiKAS->id,
                'pemegang_id' => null,
                'status' => 'aktif',
                'tanggal_perolehan' => '2022-08-20',
                'catatan' => 'Kendaraan operasional Vikjen',
            ],

            // ============ PAROKI KATEDRAL SEMARANG ============
            [
                'plat_nomor' => 'H 1234 KT',
                'nomor_bpkb' => 'BPKB-KTD-001',
                'merk_id' => $toyotaMobil->id,
                'nama_model' => 'Avanza Veloz',
                'tahun_pembuatan' => 2021,
                'warna' => 'Silver Metalik',
                'jenis' => 'mobil',
                'garasi_id' => $garasiKatedral->id,
                'pemegang_id' => $parokiKatedral?->id,
                'status' => 'aktif',
                'tanggal_perolehan' => '2021-05-10',
                'catatan' => 'Mobil operasional paroki',
            ],
            [
                'plat_nomor' => 'H 5678 KT',
                'nomor_bpkb' => 'BPKB-KTD-002',
                'merk_id' => $hondaMotor->id,
                'nama_model' => 'PCX 160',
                'tahun_pembuatan' => 2022,
                'warna' => 'Putih',
                'jenis' => 'motor',
                'garasi_id' => $garasiKatedral->id,
                'pemegang_id' => $parokiKatedral?->id,
                'status' => 'aktif',
                'tanggal_perolehan' => '2022-01-15',
            ],

            // ============ PAROKI GEDANGAN ============
            [
                'plat_nomor' => 'H 2345 GD',
                'nomor_bpkb' => 'BPKB-GDG-001',
                'merk_id' => $mitsubishiMobil->id,
                'nama_model' => 'Xpander Cross',
                'tahun_pembuatan' => 2023,
                'warna' => 'Putih',
                'jenis' => 'mobil',
                'garasi_id' => $garasiKatedral->id,
                'pemegang_id' => $parokiGedangan?->id,
                'status' => 'aktif',
                'tanggal_perolehan' => '2023-02-01',
            ],
            [
                'plat_nomor' => 'H 6789 GD',
                'nomor_bpkb' => 'BPKB-GDG-002',
                'merk_id' => $yamahaMotor->id,
                'nama_model' => 'NMAX Connected',
                'tahun_pembuatan' => 2023,
                'warna' => 'Biru Doff',
                'jenis' => 'motor',
                'garasi_id' => $garasiKatedral->id,
                'pemegang_id' => $parokiGedangan?->id,
                'status' => 'aktif',
                'tanggal_perolehan' => '2023-06-01',
            ],

            // ============ PAROKI BANYUMANIK ============
            [
                'plat_nomor' => 'H 3456 BM',
                'nomor_bpkb' => 'BPKB-BNM-001',
                'merk_id' => $suzukiMobil->id,
                'nama_model' => 'Ertiga GX',
                'tahun_pembuatan' => 2020,
                'warna' => 'Abu-abu Metalik',
                'jenis' => 'mobil',
                'garasi_id' => $garasiKatedral->id,
                'pemegang_id' => $parokiBanyumanik?->id,
                'status' => 'aktif',
                'tanggal_perolehan' => '2020-09-15',
            ],

            // ============ PAROKI PURBAYAN SOLO ============
            [
                'plat_nomor' => 'AD 1234 PB',
                'nomor_bpkb' => 'BPKB-PRB-001',
                'merk_id' => $toyotaMobil->id,
                'nama_model' => 'Kijang Innova G',
                'tahun_pembuatan' => 2019,
                'warna' => 'Hitam',
                'jenis' => 'mobil',
                'garasi_id' => $garasiSolo->id,
                'pemegang_id' => $parokiPurbayan?->id,
                'status' => 'aktif',
                'tanggal_perolehan' => '2019-04-20',
            ],
            [
                'plat_nomor' => 'AD 5678 PB',
                'nomor_bpkb' => 'BPKB-PRB-002',
                'merk_id' => $hondaMotor->id,
                'nama_model' => 'Vario 160',
                'tahun_pembuatan' => 2022,
                'warna' => 'Merah',
                'jenis' => 'motor',
                'garasi_id' => $garasiSolo->id,
                'pemegang_id' => $parokiPurbayan?->id,
                'status' => 'aktif',
                'tanggal_perolehan' => '2022-03-10',
            ],

            // ============ PAROKI KEBON DALEM ============
            [
                'plat_nomor' => 'AD 2345 KD',
                'nomor_bpkb' => 'BPKB-KBD-001',
                'merk_id' => $daihatsuMobil->id,
                'nama_model' => 'Xenia R Sporty',
                'tahun_pembuatan' => 2021,
                'warna' => 'Putih',
                'jenis' => 'mobil',
                'garasi_id' => $garasiSolo->id,
                'pemegang_id' => $parokiKebonDalem?->id,
                'status' => 'aktif',
                'tanggal_perolehan' => '2021-07-05',
            ],

            // ============ PAROKI KOTABARU JOGJA ============
            [
                'plat_nomor' => 'AB 1234 KB',
                'nomor_bpkb' => 'BPKB-KTB-001',
                'merk_id' => $toyotaMobil->id,
                'nama_model' => 'Fortuner VRZ',
                'tahun_pembuatan' => 2022,
                'warna' => 'Putih Mutiara',
                'jenis' => 'mobil',
                'garasi_id' => $garasiKotabaru->id,
                'pemegang_id' => $parokiKotabaru?->id,
                'status' => 'aktif',
                'tanggal_perolehan' => '2022-11-20',
                'catatan' => 'Hibah dari umat',
            ],
            [
                'plat_nomor' => 'AB 5678 KB',
                'nomor_bpkb' => 'BPKB-KTB-002',
                'merk_id' => $hondaMobil->id,
                'nama_model' => 'Brio RS',
                'tahun_pembuatan' => 2023,
                'warna' => 'Kuning',
                'jenis' => 'mobil',
                'garasi_id' => $garasiKotabaru->id,
                'pemegang_id' => $parokiKotabaru?->id,
                'status' => 'aktif',
                'tanggal_perolehan' => '2023-01-15',
            ],

            // ============ PAROKI BINTARAN ============
            [
                'plat_nomor' => 'AB 2345 BT',
                'nomor_bpkb' => 'BPKB-BTR-001',
                'merk_id' => $mitsubishiMobil->id,
                'nama_model' => 'Pajero Sport',
                'tahun_pembuatan' => 2021,
                'warna' => 'Hitam',
                'jenis' => 'mobil',
                'garasi_id' => $garasiBintaran->id,
                'pemegang_id' => $parokiBintaran?->id,
                'status' => 'aktif',
                'tanggal_perolehan' => '2021-08-10',
            ],
            [
                'plat_nomor' => 'AB 6789 BT',
                'nomor_bpkb' => 'BPKB-BTR-002',
                'merk_id' => $yamahaMotor->id,
                'nama_model' => 'Aerox 155',
                'tahun_pembuatan' => 2022,
                'warna' => 'Biru',
                'jenis' => 'motor',
                'garasi_id' => $garasiBintaran->id,
                'pemegang_id' => $parokiBintaran?->id,
                'status' => 'aktif',
                'tanggal_perolehan' => '2022-05-20',
            ],
            [
                'plat_nomor' => 'AB 7890 BT',
                'nomor_bpkb' => 'BPKB-BTR-003',
                'merk_id' => $hondaMotor->id,
                'nama_model' => 'Beat Street',
                'tahun_pembuatan' => 2023,
                'warna' => 'Hitam Doff',
                'jenis' => 'motor',
                'garasi_id' => $garasiBintaran->id,
                'pemegang_id' => $parokiBintaran?->id,
                'status' => 'aktif',
                'tanggal_perolehan' => '2023-03-01',
            ],

            // ============ YAYASAN KANISIUS ============
            [
                'plat_nomor' => 'AB 1111 KN',
                'nomor_bpkb' => 'BPKB-KNS-001',
                'merk_id' => $toyotaMobil->id,
                'nama_model' => 'HiAce Commuter',
                'tahun_pembuatan' => 2022,
                'warna' => 'Putih',
                'jenis' => 'mobil',
                'garasi_id' => $garasiKotabaru->id,
                'pemegang_id' => $kanisius?->id,
                'status' => 'aktif',
                'tanggal_perolehan' => '2022-06-01',
                'catatan' => 'Bus antar jemput siswa',
            ],
            [
                'plat_nomor' => 'AB 2222 KN',
                'nomor_bpkb' => 'BPKB-KNS-002',
                'merk_id' => $isuzuMobil->id,
                'nama_model' => 'Elf NLR',
                'tahun_pembuatan' => 2021,
                'warna' => 'Kuning',
                'jenis' => 'mobil',
                'garasi_id' => $garasiKotabaru->id,
                'pemegang_id' => $kanisius?->id,
                'status' => 'aktif',
                'tanggal_perolehan' => '2021-02-15',
                'catatan' => 'Bus sekolah',
            ],
            [
                'plat_nomor' => 'AB 3333 KN',
                'nomor_bpkb' => 'BPKB-KNS-003',
                'merk_id' => $hondaMobil->id,
                'nama_model' => 'HR-V',
                'tahun_pembuatan' => 2023,
                'warna' => 'Abu-abu',
                'jenis' => 'mobil',
                'garasi_id' => $garasiKotabaru->id,
                'pemegang_id' => $kanisius?->id,
                'status' => 'aktif',
                'tanggal_perolehan' => '2023-04-01',
                'catatan' => 'Kendaraan operasional yayasan',
            ],

            // ============ YAYASAN PANGUDI LUHUR ============
            [
                'plat_nomor' => 'H 1111 PL',
                'nomor_bpkb' => 'BPKB-PGL-001',
                'merk_id' => $toyotaMobil->id,
                'nama_model' => 'HiAce Premio',
                'tahun_pembuatan' => 2023,
                'warna' => 'Silver',
                'jenis' => 'mobil',
                'garasi_id' => $garasiKAS->id,
                'pemegang_id' => $pangudiluhur?->id,
                'status' => 'aktif',
                'tanggal_perolehan' => '2023-01-10',
                'catatan' => 'Bus antar jemput siswa',
            ],
            [
                'plat_nomor' => 'H 2222 PL',
                'nomor_bpkb' => 'BPKB-PGL-002',
                'merk_id' => $mitsubishiMobil->id,
                'nama_model' => 'L300 Minibus',
                'tahun_pembuatan' => 2020,
                'warna' => 'Putih',
                'jenis' => 'mobil',
                'garasi_id' => $garasiKAS->id,
                'pemegang_id' => $pangudiluhur?->id,
                'status' => 'aktif',
                'tanggal_perolehan' => '2020-08-20',
            ],

            // ============ RS ST. ELISABETH ============
            [
                'plat_nomor' => 'H 1111 RS',
                'nomor_bpkb' => 'BPKB-RSE-001',
                'merk_id' => $toyotaMobil->id,
                'nama_model' => 'HiAce Ambulance',
                'tahun_pembuatan' => 2022,
                'warna' => 'Putih',
                'jenis' => 'mobil',
                'garasi_id' => $garasiKAS->id,
                'pemegang_id' => $rsElisabeth?->id,
                'status' => 'aktif',
                'tanggal_perolehan' => '2022-01-01',
                'catatan' => 'Ambulans IGD',
            ],
            [
                'plat_nomor' => 'H 2222 RS',
                'nomor_bpkb' => 'BPKB-RSE-002',
                'merk_id' => $toyotaMobil->id,
                'nama_model' => 'HiAce Ambulance',
                'tahun_pembuatan' => 2023,
                'warna' => 'Putih',
                'jenis' => 'mobil',
                'garasi_id' => $garasiKAS->id,
                'pemegang_id' => $rsElisabeth?->id,
                'status' => 'aktif',
                'tanggal_perolehan' => '2023-03-15',
                'catatan' => 'Ambulans cadangan',
            ],
            [
                'plat_nomor' => 'H 3333 RS',
                'nomor_bpkb' => 'BPKB-RSE-003',
                'merk_id' => $hondaMobil->id,
                'nama_model' => 'Accord',
                'tahun_pembuatan' => 2021,
                'warna' => 'Hitam',
                'jenis' => 'mobil',
                'garasi_id' => $garasiKAS->id,
                'pemegang_id' => $rsElisabeth?->id,
                'status' => 'aktif',
                'tanggal_perolehan' => '2021-06-01',
                'catatan' => 'Kendaraan Direktur RS',
            ],

            // ============ SEMINARI MERTOYUDAN ============
            [
                'plat_nomor' => 'AA 1234 SM',
                'nomor_bpkb' => 'BPKB-SMR-001',
                'merk_id' => $isuzuMobil->id,
                'nama_model' => 'Elf NHR',
                'tahun_pembuatan' => 2021,
                'warna' => 'Putih',
                'jenis' => 'mobil',
                'garasi_id' => $garasiMagelang->id,
                'pemegang_id' => $seminari?->id,
                'status' => 'aktif',
                'tanggal_perolehan' => '2021-07-01',
                'catatan' => 'Bus seminaris',
            ],
            [
                'plat_nomor' => 'AA 5678 SM',
                'nomor_bpkb' => 'BPKB-SMR-002',
                'merk_id' => $toyotaMobil->id,
                'nama_model' => 'Avanza G',
                'tahun_pembuatan' => 2020,
                'warna' => 'Silver',
                'jenis' => 'mobil',
                'garasi_id' => $garasiMagelang->id,
                'pemegang_id' => $seminari?->id,
                'status' => 'aktif',
                'tanggal_perolehan' => '2020-03-15',
            ],

            // ============ KENDARAAN PRIBADI ROMO ============
            [
                'plat_nomor' => 'H 7777 RM',
                'nomor_bpkb' => 'BPKB-RM-001',
                'merk_id' => $hondaMobil->id,
                'nama_model' => 'Jazz RS',
                'tahun_pembuatan' => 2022,
                'warna' => 'Merah',
                'jenis' => 'mobil',
                'garasi_id' => $garasiKAS->id,
                'pemegang_id' => $rmAntonius?->id,
                'status' => 'aktif',
                'tanggal_perolehan' => '2022-04-01',
                'catatan' => 'Kendaraan dinas Rm. Antonius',
            ],
            [
                'plat_nomor' => 'AB 8888 RM',
                'nomor_bpkb' => 'BPKB-RM-002',
                'merk_id' => $toyotaMobil->id,
                'nama_model' => 'Yaris Cross',
                'tahun_pembuatan' => 2023,
                'warna' => 'Biru',
                'jenis' => 'mobil',
                'garasi_id' => $garasiBintaran->id,
                'pemegang_id' => $rmYohanes?->id,
                'status' => 'aktif',
                'tanggal_perolehan' => '2023-02-01',
                'catatan' => 'Kendaraan dinas Rm. Yohanes',
            ],
            [
                'plat_nomor' => 'AA 9999 RM',
                'nomor_bpkb' => 'BPKB-RM-003',
                'merk_id' => $suzukiMobil->id,
                'nama_model' => 'XL7 Alpha',
                'tahun_pembuatan' => 2022,
                'warna' => 'Putih',
                'jenis' => 'mobil',
                'garasi_id' => $garasiMagelang->id,
                'pemegang_id' => $rmPaulus?->id,
                'status' => 'aktif',
                'tanggal_perolehan' => '2022-09-15',
                'catatan' => 'Kendaraan dinas Rm. Paulus',
            ],

            // ============ KENDARAAN LAMA/DIHIBAHKAN ============
            [
                'plat_nomor' => 'H 9999 OLD',
                'nomor_bpkb' => 'BPKB-OLD-001',
                'merk_id' => $toyotaMobil->id,
                'nama_model' => 'Kijang LGX',
                'tahun_pembuatan' => 2005,
                'warna' => 'Hijau',
                'jenis' => 'mobil',
                'garasi_id' => $garasiKAS->id,
                'pemegang_id' => null,
                'status' => 'dihibahkan',
                'tanggal_perolehan' => '2005-01-01',
                'tanggal_hibah' => '2023-06-30',
                'catatan' => 'Dihibahkan ke Paroki St. Maria Wonosobo karena kondisi sudah tua.',
            ],
            [
                'plat_nomor' => 'AD 8888 OLD',
                'nomor_bpkb' => 'BPKB-OLD-002',
                'merk_id' => $suzukiMobil->id,
                'nama_model' => 'APV Arena',
                'tahun_pembuatan' => 2010,
                'warna' => 'Silver',
                'jenis' => 'mobil',
                'garasi_id' => $garasiSolo->id,
                'pemegang_id' => null,
                'status' => 'nonaktif',
                'tanggal_perolehan' => '2010-05-01',
                'catatan' => 'Kendaraan rusak berat, menunggu keputusan penghapusan.',
            ],
        ];

        foreach ($kendaraanData as $kendaraan) {
            Kendaraan::create($kendaraan);
        }
    }
}
