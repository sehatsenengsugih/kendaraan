<?php

namespace Database\Seeders;

use App\Models\ManualSection;
use Illuminate\Database\Seeder;

class ManualSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            [
                'slug' => 'pengenalan',
                'title' => 'Pengenalan Aplikasi',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>',
                'order' => 1,
                'content' => '<p class="text-bgray-700 dark:text-bgray-300 leading-relaxed">
    <strong>Aplikasi Pengelola Kendaraan KAS</strong> adalah sistem informasi untuk mengelola aset kendaraan
    milik Keuskupan Agung Semarang (KAS). Aplikasi ini membantu mendata kendaraan, memonitor jadwal pajak
    dan servis, serta mencatat riwayat penggunaan kendaraan.
</p>

<h3 class="text-lg font-semibold text-bgray-900 dark:text-white mt-6 mb-3">Fitur Utama</h3>
<div class="grid md:grid-cols-2 gap-4">
    <div class="flex items-start gap-3 p-4 rounded-lg bg-bgray-50 dark:bg-darkblack-500">
        <i class="fa fa-car text-accent-400 mt-1"></i>
        <div>
            <h4 class="font-medium text-bgray-900 dark:text-white">Manajemen Kendaraan</h4>
            <p class="text-sm text-bgray-600 dark:text-bgray-400">Data lengkap kendaraan termasuk dokumen, foto, dan riwayat pengguna</p>
        </div>
    </div>
    <div class="flex items-start gap-3 p-4 rounded-lg bg-bgray-50 dark:bg-darkblack-500">
        <i class="fa fa-file-invoice text-accent-400 mt-1"></i>
        <div>
            <h4 class="font-medium text-bgray-900 dark:text-white">Monitoring Pajak</h4>
            <p class="text-sm text-bgray-600 dark:text-bgray-400">Pengingat jatuh tempo pajak dan pencatatan pembayaran</p>
        </div>
    </div>
    <div class="flex items-start gap-3 p-4 rounded-lg bg-bgray-50 dark:bg-darkblack-500">
        <i class="fa fa-tools text-accent-400 mt-1"></i>
        <div>
            <h4 class="font-medium text-bgray-900 dark:text-white">Jadwal Servis</h4>
            <p class="text-sm text-bgray-600 dark:text-bgray-400">Pencatatan servis berkala dan perbaikan kendaraan</p>
        </div>
    </div>
    <div class="flex items-start gap-3 p-4 rounded-lg bg-bgray-50 dark:bg-darkblack-500">
        <i class="fa fa-calendar-alt text-accent-400 mt-1"></i>
        <div>
            <h4 class="font-medium text-bgray-900 dark:text-white">Kalender Terintegrasi</h4>
            <p class="text-sm text-bgray-600 dark:text-bgray-400">Tampilan kalender untuk jadwal pajak dan servis</p>
        </div>
    </div>
</div>

<h3 class="text-lg font-semibold text-bgray-900 dark:text-white mt-6 mb-3">Peran Pengguna</h3>
<div class="overflow-x-auto">
    <table class="min-w-full text-sm">
        <thead class="bg-bgray-100 dark:bg-darkblack-500">
            <tr>
                <th class="px-4 py-3 text-left font-semibold text-bgray-900 dark:text-white">Peran</th>
                <th class="px-4 py-3 text-left font-semibold text-bgray-900 dark:text-white">Hak Akses</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-bgray-200 dark:divide-darkblack-400">
            <tr>
                <td class="px-4 py-3">
                    <span class="inline-flex rounded-full bg-bgray-800 px-2 py-1 text-xs text-white">Super Admin</span>
                </td>
                <td class="px-4 py-3 text-bgray-700 dark:text-bgray-300">Akses penuh ke semua fitur, termasuk manajemen pengguna dan audit log</td>
            </tr>
            <tr>
                <td class="px-4 py-3">
                    <span class="inline-flex rounded-full bg-accent-50 px-2 py-1 text-xs text-accent-400">Admin</span>
                </td>
                <td class="px-4 py-3 text-bgray-700 dark:text-bgray-300">Kelola kendaraan, pajak, servis, master data, dan user biasa</td>
            </tr>
            <tr>
                <td class="px-4 py-3">
                    <span class="inline-flex rounded-full bg-warning-50 px-2 py-1 text-xs text-warning-400">Admin Servis</span>
                </td>
                <td class="px-4 py-3 text-bgray-700 dark:text-bgray-300">Khusus mengelola servis kendaraan</td>
            </tr>
            <tr>
                <td class="px-4 py-3">
                    <span class="inline-flex rounded-full bg-bgray-100 px-2 py-1 text-xs text-bgray-600">User</span>
                </td>
                <td class="px-4 py-3 text-bgray-700 dark:text-bgray-300">Lihat data kendaraan dan jadwal</td>
            </tr>
        </tbody>
    </table>
</div>',
            ],
            [
                'slug' => 'login',
                'title' => 'Login & Akun',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>',
                'order' => 2,
                'content' => '<h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-3">Langkah Login</h3>
<ol class="list-decimal list-inside space-y-3 text-bgray-700 dark:text-bgray-300">
    <li>Buka aplikasi di browser (Chrome/Firefox/Edge direkomendasikan)</li>
    <li>Masukkan <strong>Email</strong> yang terdaftar</li>
    <li>Masukkan <strong>Password</strong></li>
    <li>Centang "Ingat Saya" jika menggunakan perangkat pribadi</li>
    <li>Klik tombol <strong>Masuk</strong></li>
</ol>

<div class="mt-6 rounded-lg bg-warning-50 p-4 border-l-4 border-warning-400">
    <h4 class="font-semibold text-warning-700 mb-2"><i class="fa fa-exclamation-triangle mr-2"></i>Catatan Keamanan</h4>
    <ul class="list-disc list-inside text-sm text-warning-600 space-y-1">
        <li>Maksimal 5 percobaan login dalam 15 menit</li>
        <li>Jika melebihi, akun akan dikunci sementara</li>
        <li>Jangan bagikan password kepada orang lain</li>
        <li>Hubungi admin jika lupa password</li>
    </ul>
</div>',
            ],
            [
                'slug' => 'dashboard',
                'title' => 'Dashboard',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>',
                'order' => 3,
                'content' => '<p class="text-bgray-700 dark:text-bgray-300">
    Dashboard menampilkan ringkasan data kendaraan dan pengingat penting dalam satu tampilan.
</p>

<h3 class="text-lg font-semibold text-bgray-900 dark:text-white mt-6 mb-3">Komponen Dashboard</h3>

<div class="space-y-4">
    <div class="rounded-lg border border-bgray-200 dark:border-darkblack-400 p-4">
        <h4 class="font-semibold text-bgray-900 dark:text-white mb-2">1. Kartu Statistik</h4>
        <p class="text-sm text-bgray-600 dark:text-bgray-400 mb-3">
            Baris atas menampilkan 4 kartu statistik: Total Kendaraan, Pajak 30 Hari, Pajak Terlambat, dan Servis Aktif.
        </p>
    </div>

    <div class="rounded-lg border border-bgray-200 dark:border-darkblack-400 p-4">
        <h4 class="font-semibold text-bgray-900 dark:text-white mb-2">2. Grafik Kendaraan</h4>
        <p class="text-sm text-bgray-600 dark:text-bgray-400">
            Distribusi umur kendaraan (bar chart) dan kendaraan per merk (doughnut chart).
        </p>
    </div>

    <div class="rounded-lg border border-bgray-200 dark:border-darkblack-400 p-4">
        <h4 class="font-semibold text-bgray-900 dark:text-white mb-2">3. Pengingat Pajak</h4>
        <p class="text-sm text-bgray-600 dark:text-bgray-400">
            Tab interface untuk melihat pajak berdasarkan kategori: Terlambat, 7 Hari, 30 Hari, 6 Bulan.
        </p>
    </div>
</div>',
            ],
            [
                'slug' => 'kendaraan',
                'title' => 'Manajemen Kendaraan',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>',
                'order' => 4,
                'content' => '<h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-3">Melihat Daftar Kendaraan</h3>
<ol class="list-decimal list-inside space-y-2 text-bgray-700 dark:text-bgray-300">
    <li>Klik menu <strong>Kendaraan</strong> di sidebar</li>
    <li>Tampil daftar semua kendaraan dalam bentuk tabel</li>
    <li>Gunakan <strong>Filter</strong> untuk menyaring data (Jenis, Merk, Garasi, Status)</li>
    <li>Klik <strong>header kolom</strong> untuk mengurutkan data</li>
    <li>Gunakan <strong>Search</strong> untuk pencarian cepat</li>
</ol>

<h3 class="text-lg font-semibold text-bgray-900 dark:text-white mt-6 mb-3">Menambah Kendaraan Baru</h3>
<ol class="list-decimal list-inside space-y-2 text-bgray-700 dark:text-bgray-300">
    <li>Klik tombol <strong>Tambah</strong></li>
    <li>Isi form sesuai section: Identitas Dasar, Dokumen, Kepemilikan, Lokasi & Pengguna, Gambar</li>
    <li>Klik <strong>Simpan</strong> untuk menyimpan data</li>
</ol>

<div class="mt-6 rounded-lg bg-accent-50 p-4 border-l-4 border-accent-400">
    <h4 class="font-semibold text-accent-700 mb-2"><i class="fa fa-history mr-2"></i>Fitur Riwayat Pengguna Otomatis</h4>
    <p class="text-sm text-accent-600">
        Ketika Anda mengubah nama Pengguna kendaraan, sistem akan <strong>otomatis</strong> mencatat
        pengguna sebelumnya ke dalam Riwayat Pengguna dengan tanggal selesai hari ini.
    </p>
</div>',
            ],
            [
                'slug' => 'pajak',
                'title' => 'Manajemen Pajak',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>',
                'order' => 5,
                'content' => '<h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-3">Jenis Pajak</h3>
<div class="grid md:grid-cols-2 gap-4">
    <div class="rounded-lg border border-bgray-200 dark:border-darkblack-400 p-4">
        <h4 class="font-medium text-bgray-900 dark:text-white">Pajak Tahunan</h4>
        <p class="text-sm text-bgray-600 dark:text-bgray-400">Pajak rutin setiap tahun</p>
    </div>
    <div class="rounded-lg border border-bgray-200 dark:border-darkblack-400 p-4">
        <h4 class="font-medium text-bgray-900 dark:text-white">Pajak 5 Tahunan</h4>
        <p class="text-sm text-bgray-600 dark:text-bgray-400">Pajak + ganti plat nomor setiap 5 tahun</p>
    </div>
</div>

<h3 class="text-lg font-semibold text-bgray-900 dark:text-white mt-6 mb-3">Status Pajak</h3>
<div class="flex flex-wrap gap-3">
    <span class="inline-flex items-center rounded-full bg-accent-50 px-3 py-1 text-sm text-accent-400">
        <i class="fa fa-check-circle mr-2"></i>Lunas
    </span>
    <span class="inline-flex items-center rounded-full bg-warning-50 px-3 py-1 text-sm text-warning-400">
        <i class="fa fa-clock mr-2"></i>Belum Bayar
    </span>
    <span class="inline-flex items-center rounded-full bg-error-50 px-3 py-1 text-sm text-error-300">
        <i class="fa fa-exclamation-circle mr-2"></i>Terlambat
    </span>
</div>

<h3 class="text-lg font-semibold text-bgray-900 dark:text-white mt-6 mb-3">Cara Menambah & Membayar Pajak</h3>
<ol class="list-decimal list-inside space-y-2 text-bgray-700 dark:text-bgray-300">
    <li>Buka menu <strong>Pajak</strong></li>
    <li>Klik <strong>Tambah Pajak</strong>, pilih kendaraan, isi tanggal jatuh tempo</li>
    <li>Untuk pembayaran, klik tombol <strong>Bayar</strong> pada baris pajak</li>
    <li>Isi tanggal bayar, nominal, denda (jika ada), dan upload bukti</li>
</ol>',
            ],
            [
                'slug' => 'servis',
                'title' => 'Manajemen Servis',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
                'order' => 6,
                'content' => '<h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-3">Jenis Servis</h3>
<div class="grid md:grid-cols-2 lg:grid-cols-4 gap-3">
    <div class="rounded-lg bg-bgray-50 dark:bg-darkblack-500 p-3 text-center">
        <i class="fa fa-sync text-accent-400 text-xl mb-2"></i>
        <p class="text-sm font-medium text-bgray-900 dark:text-white">Rutin</p>
        <p class="text-xs text-bgray-500">Servis berkala terjadwal</p>
    </div>
    <div class="rounded-lg bg-bgray-50 dark:bg-darkblack-500 p-3 text-center">
        <i class="fa fa-wrench text-warning-400 text-xl mb-2"></i>
        <p class="text-sm font-medium text-bgray-900 dark:text-white">Perbaikan</p>
        <p class="text-xs text-bgray-500">Perbaikan kerusakan</p>
    </div>
    <div class="rounded-lg bg-bgray-50 dark:bg-darkblack-500 p-3 text-center">
        <i class="fa fa-exclamation-triangle text-error-300 text-xl mb-2"></i>
        <p class="text-sm font-medium text-bgray-900 dark:text-white">Darurat</p>
        <p class="text-xs text-bgray-500">Perbaikan mendesak</p>
    </div>
    <div class="rounded-lg bg-bgray-50 dark:bg-darkblack-500 p-3 text-center">
        <i class="fa fa-cogs text-bgray-600 text-xl mb-2"></i>
        <p class="text-sm font-medium text-bgray-900 dark:text-white">Overhaul</p>
        <p class="text-xs text-bgray-500">Perbaikan besar/turun mesin</p>
    </div>
</div>

<h3 class="text-lg font-semibold text-bgray-900 dark:text-white mt-6 mb-3">Data yang Dicatat</h3>
<ul class="list-disc list-inside space-y-1 text-bgray-700 dark:text-bgray-300">
    <li>Kendaraan yang diservis</li>
    <li>Jenis servis dan tanggal</li>
    <li>Kilometer saat servis</li>
    <li>Nama bengkel dan deskripsi pekerjaan</li>
    <li>Spare part yang diganti</li>
    <li>Biaya servis</li>
</ul>',
            ],
            [
                'slug' => 'master-data',
                'title' => 'Master Data',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>',
                'order' => 7,
                'content' => '<p class="text-bgray-700 dark:text-bgray-300 mb-4">Data referensi yang digunakan di seluruh aplikasi.</p>

<div class="grid md:grid-cols-2 gap-6">
    <div class="rounded-lg border border-bgray-200 dark:border-darkblack-400 p-4">
        <h3 class="font-semibold text-bgray-900 dark:text-white mb-2">
            <i class="fa fa-warehouse text-accent-400 mr-2"></i>Garasi
        </h3>
        <p class="text-sm text-bgray-600 dark:text-bgray-400">Lokasi penyimpanan kendaraan: nama, alamat, kevikepan, tipe, PIC</p>
    </div>

    <div class="rounded-lg border border-bgray-200 dark:border-darkblack-400 p-4">
        <h3 class="font-semibold text-bgray-900 dark:text-white mb-2">
            <i class="fa fa-tag text-accent-400 mr-2"></i>Merk
        </h3>
        <p class="text-sm text-bgray-600 dark:text-bgray-400">Merk/brand kendaraan: nama merk dan jenis (Mobil/Motor)</p>
    </div>

    <div class="rounded-lg border border-bgray-200 dark:border-darkblack-400 p-4">
        <h3 class="font-semibold text-bgray-900 dark:text-white mb-2">
            <i class="fa fa-church text-accent-400 mr-2"></i>Paroki
        </h3>
        <p class="text-sm text-bgray-600 dark:text-bgray-400">Data paroki di KAS: nama, kevikepan, alamat, kontak</p>
    </div>

    <div class="rounded-lg border border-bgray-200 dark:border-darkblack-400 p-4">
        <h3 class="font-semibold text-bgray-900 dark:text-white mb-2">
            <i class="fa fa-building text-accent-400 mr-2"></i>Lembaga
        </h3>
        <p class="text-sm text-bgray-600 dark:text-bgray-400">Lembaga di bawah KAS: nama, alamat, kontak</p>
    </div>
</div>',
            ],
            [
                'slug' => 'kalender',
                'title' => 'Kalender',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
                'order' => 8,
                'content' => '<p class="text-bgray-700 dark:text-bgray-300 mb-4">
    Kalender menampilkan semua jadwal pajak dan servis dalam tampilan visual kalender.
</p>

<h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-3">Fitur Kalender</h3>
<div class="grid md:grid-cols-2 gap-4">
    <div class="flex items-start gap-3">
        <i class="fa fa-eye text-accent-400 mt-1"></i>
        <div>
            <h4 class="font-medium text-bgray-900 dark:text-white">Tampilan Bulan/Minggu/Hari</h4>
            <p class="text-sm text-bgray-600 dark:text-bgray-400">Switch tampilan sesuai kebutuhan</p>
        </div>
    </div>
    <div class="flex items-start gap-3">
        <i class="fa fa-filter text-accent-400 mt-1"></i>
        <div>
            <h4 class="font-medium text-bgray-900 dark:text-white">Filter Event</h4>
            <p class="text-sm text-bgray-600 dark:text-bgray-400">Tampilkan hanya Pajak atau Servis</p>
        </div>
    </div>
    <div class="flex items-start gap-3">
        <i class="fa fa-mouse-pointer text-accent-400 mt-1"></i>
        <div>
            <h4 class="font-medium text-bgray-900 dark:text-white">Klik untuk Detail</h4>
            <p class="text-sm text-bgray-600 dark:text-bgray-400">Klik event untuk lihat detail</p>
        </div>
    </div>
    <div class="flex items-start gap-3">
        <i class="fa fa-arrows-alt text-accent-400 mt-1"></i>
        <div>
            <h4 class="font-medium text-bgray-900 dark:text-white">Drag & Drop</h4>
            <p class="text-sm text-bgray-600 dark:text-bgray-400">Geser event untuk ubah tanggal</p>
        </div>
    </div>
</div>',
            ],
            [
                'slug' => 'profil',
                'title' => 'Profil & Pengaturan',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>',
                'order' => 9,
                'content' => '<h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-3">Mengakses Profil</h3>
<ol class="list-decimal list-inside space-y-2 text-bgray-700 dark:text-bgray-300">
    <li>Klik ikon <i class="fa fa-cog text-bgray-500"></i> di sebelah nama Anda di sidebar bawah</li>
    <li>Atau klik nama/foto profil Anda</li>
</ol>

<h3 class="text-lg font-semibold text-bgray-900 dark:text-white mt-6 mb-3">Fitur Profil</h3>
<div class="grid md:grid-cols-2 gap-4">
    <div class="rounded-lg border border-bgray-200 dark:border-darkblack-400 p-4">
        <h4 class="font-medium text-bgray-900 dark:text-white mb-2">
            <i class="fa fa-camera text-accent-400 mr-2"></i>Foto Profil
        </h4>
        <p class="text-sm text-bgray-600 dark:text-bgray-400">
            Upload foto profil. Format: JPG, PNG, WebP. Maks 2MB.
        </p>
    </div>
    <div class="rounded-lg border border-bgray-200 dark:border-darkblack-400 p-4">
        <h4 class="font-medium text-bgray-900 dark:text-white mb-2">
            <i class="fa fa-palette text-accent-400 mr-2"></i>Warna Aksen
        </h4>
        <p class="text-sm text-bgray-600 dark:text-bgray-400">
            Pilih warna aksen untuk personalisasi tampilan.
        </p>
    </div>
    <div class="rounded-lg border border-bgray-200 dark:border-darkblack-400 p-4">
        <h4 class="font-medium text-bgray-900 dark:text-white mb-2">
            <i class="fa fa-user text-accent-400 mr-2"></i>Informasi Akun
        </h4>
        <p class="text-sm text-bgray-600 dark:text-bgray-400">
            Ubah nama dan email Anda.
        </p>
    </div>
    <div class="rounded-lg border border-bgray-200 dark:border-darkblack-400 p-4">
        <h4 class="font-medium text-bgray-900 dark:text-white mb-2">
            <i class="fa fa-key text-accent-400 mr-2"></i>Ubah Password
        </h4>
        <p class="text-sm text-bgray-600 dark:text-bgray-400">
            Ganti password akun untuk keamanan.
        </p>
    </div>
</div>',
            ],
            [
                'slug' => 'pengguna',
                'title' => 'Manajemen Pengguna',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>',
                'order' => 10,
                'content' => '<div class="rounded-lg bg-warning-50 p-4 border-l-4 border-warning-400 mb-6">
    <p class="text-sm text-warning-700">
        <i class="fa fa-info-circle mr-2"></i>
        Fitur ini hanya tersedia untuk <strong>Super Admin</strong>.
    </p>
</div>

<h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-3">Menambah Pengguna Baru</h3>
<ol class="list-decimal list-inside space-y-2 text-bgray-700 dark:text-bgray-300">
    <li>Buka menu <strong>Pengguna</strong> di sidebar</li>
    <li>Klik tombol <strong>Tambah Pengguna</strong></li>
    <li>Isi form: Nama, Email, Nomor telepon, Peran, Password</li>
    <li>Klik <strong>Simpan</strong></li>
</ol>

<h3 class="text-lg font-semibold text-bgray-900 dark:text-white mt-6 mb-3">Aktivasi/Nonaktifkan Pengguna</h3>
<p class="text-sm text-bgray-600 dark:text-bgray-400">
    Pengguna yang dinonaktifkan tidak dapat login. Untuk mengubah status, buka halaman edit pengguna.
</p>',
            ],
            [
                'slug' => 'tips',
                'title' => 'Tips & Trik',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>',
                'order' => 11,
                'content' => '<h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-3">Keyboard Shortcuts</h3>
<div class="grid md:grid-cols-2 gap-3">
    <div class="flex items-center justify-between rounded-lg bg-bgray-50 dark:bg-darkblack-500 px-4 py-3">
        <span class="text-sm text-bgray-700 dark:text-bgray-300">Toggle Sidebar</span>
        <kbd class="rounded bg-bgray-200 dark:bg-darkblack-400 px-2 py-1 text-xs font-mono">Ctrl + B</kbd>
    </div>
    <div class="flex items-center justify-between rounded-lg bg-bgray-50 dark:bg-darkblack-500 px-4 py-3">
        <span class="text-sm text-bgray-700 dark:text-bgray-300">Pencarian Global</span>
        <kbd class="rounded bg-bgray-200 dark:bg-darkblack-400 px-2 py-1 text-xs font-mono">Ctrl + K</kbd>
    </div>
</div>

<h3 class="text-lg font-semibold text-bgray-900 dark:text-white mt-6 mb-3">Tips Penggunaan</h3>
<ul class="space-y-3">
    <li class="flex items-start gap-3 text-bgray-700 dark:text-bgray-300">
        <i class="fa fa-check-circle text-accent-400 mt-1"></i>
        <span><strong>Gunakan filter</strong> untuk menyaring data dengan cepat di halaman daftar</span>
    </li>
    <li class="flex items-start gap-3 text-bgray-700 dark:text-bgray-300">
        <i class="fa fa-check-circle text-accent-400 mt-1"></i>
        <span><strong>Klik header kolom</strong> untuk mengurutkan data (ascending/descending)</span>
    </li>
    <li class="flex items-start gap-3 text-bgray-700 dark:text-bgray-300">
        <i class="fa fa-check-circle text-accent-400 mt-1"></i>
        <span><strong>Cek dashboard secara berkala</strong> untuk memantau pengingat pajak</span>
    </li>
    <li class="flex items-start gap-3 text-bgray-700 dark:text-bgray-300">
        <i class="fa fa-check-circle text-accent-400 mt-1"></i>
        <span><strong>Upload foto kendaraan</strong> untuk memudahkan identifikasi</span>
    </li>
</ul>

<h3 class="text-lg font-semibold text-bgray-900 dark:text-white mt-6 mb-3">Butuh Bantuan?</h3>
<p class="text-bgray-700 dark:text-bgray-300">
    Jika Anda mengalami kendala atau memiliki pertanyaan, silakan hubungi administrator sistem.
</p>',
            ],
        ];

        foreach ($sections as $section) {
            ManualSection::updateOrCreate(
                ['slug' => $section['slug']],
                $section
            );
        }

        $this->command->info('Manual sections seeded successfully!');
    }
}
