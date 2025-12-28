<x-app-layout title="Panduan Pengguna">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Panduan Pengguna</h2>
                <p class="text-sm text-bgray-600 dark:text-bgray-400 mt-1">Manual lengkap penggunaan aplikasi Kendaraan KAS</p>
            </div>
            <a href="#" onclick="window.print(); return false;" class="inline-flex items-center gap-2 rounded-lg bg-bgray-200 px-4 py-2 text-sm font-medium text-bgray-700 hover:bg-bgray-300 dark:bg-darkblack-500 dark:text-white dark:hover:bg-darkblack-400">
                <i class="fa fa-print"></i>
                Cetak Manual
            </a>
        </div>
    </x-slot>

    <div class="flex gap-6">
        <!-- Sidebar Navigation -->
        <div class="hidden lg:block w-64 flex-shrink-0">
            <div class="sticky top-24 rounded-lg bg-white p-4 shadow-sm dark:bg-darkblack-600">
                <h3 class="mb-4 text-sm font-semibold text-bgray-900 dark:text-white uppercase tracking-wider">Daftar Isi</h3>
                <nav class="space-y-1">
                    <a href="#pengenalan" class="manual-nav-link block rounded-lg px-3 py-2 text-sm text-bgray-700 hover:bg-accent-50 hover:text-accent-400 dark:text-bgray-300 dark:hover:bg-darkblack-500">
                        <i class="fa fa-home w-5 mr-2"></i>Pengenalan
                    </a>
                    <a href="#login" class="manual-nav-link block rounded-lg px-3 py-2 text-sm text-bgray-700 hover:bg-accent-50 hover:text-accent-400 dark:text-bgray-300 dark:hover:bg-darkblack-500">
                        <i class="fa fa-sign-in-alt w-5 mr-2"></i>Login & Akun
                    </a>
                    <a href="#dashboard" class="manual-nav-link block rounded-lg px-3 py-2 text-sm text-bgray-700 hover:bg-accent-50 hover:text-accent-400 dark:text-bgray-300 dark:hover:bg-darkblack-500">
                        <i class="fa fa-tachometer-alt w-5 mr-2"></i>Dashboard
                    </a>
                    <a href="#kendaraan" class="manual-nav-link block rounded-lg px-3 py-2 text-sm text-bgray-700 hover:bg-accent-50 hover:text-accent-400 dark:text-bgray-300 dark:hover:bg-darkblack-500">
                        <i class="fa fa-car w-5 mr-2"></i>Kendaraan
                    </a>
                    <a href="#pajak" class="manual-nav-link block rounded-lg px-3 py-2 text-sm text-bgray-700 hover:bg-accent-50 hover:text-accent-400 dark:text-bgray-300 dark:hover:bg-darkblack-500">
                        <i class="fa fa-file-invoice w-5 mr-2"></i>Pajak
                    </a>
                    <a href="#servis" class="manual-nav-link block rounded-lg px-3 py-2 text-sm text-bgray-700 hover:bg-accent-50 hover:text-accent-400 dark:text-bgray-300 dark:hover:bg-darkblack-500">
                        <i class="fa fa-tools w-5 mr-2"></i>Servis
                    </a>
                    <a href="#master-data" class="manual-nav-link block rounded-lg px-3 py-2 text-sm text-bgray-700 hover:bg-accent-50 hover:text-accent-400 dark:text-bgray-300 dark:hover:bg-darkblack-500">
                        <i class="fa fa-database w-5 mr-2"></i>Master Data
                    </a>
                    <a href="#kalender" class="manual-nav-link block rounded-lg px-3 py-2 text-sm text-bgray-700 hover:bg-accent-50 hover:text-accent-400 dark:text-bgray-300 dark:hover:bg-darkblack-500">
                        <i class="fa fa-calendar-alt w-5 mr-2"></i>Kalender
                    </a>
                    <a href="#profil" class="manual-nav-link block rounded-lg px-3 py-2 text-sm text-bgray-700 hover:bg-accent-50 hover:text-accent-400 dark:text-bgray-300 dark:hover:bg-darkblack-500">
                        <i class="fa fa-user-cog w-5 mr-2"></i>Profil & Pengaturan
                    </a>
                    @can('manage-users')
                    <a href="#pengguna" class="manual-nav-link block rounded-lg px-3 py-2 text-sm text-bgray-700 hover:bg-accent-50 hover:text-accent-400 dark:text-bgray-300 dark:hover:bg-darkblack-500">
                        <i class="fa fa-users-cog w-5 mr-2"></i>Manajemen Pengguna
                    </a>
                    @endcan
                    <a href="#tips" class="manual-nav-link block rounded-lg px-3 py-2 text-sm text-bgray-700 hover:bg-accent-50 hover:text-accent-400 dark:text-bgray-300 dark:hover:bg-darkblack-500">
                        <i class="fa fa-lightbulb w-5 mr-2"></i>Tips & Trik
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 space-y-8">
            {{-- ==================== PENGENALAN ==================== --}}
            <section id="pengenalan" class="rounded-lg bg-white p-6 shadow-sm dark:bg-darkblack-600 scroll-mt-24">
                <div class="flex items-center gap-3 mb-6">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-accent-50">
                        <i class="fa fa-book text-xl text-accent-400"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-bgray-900 dark:text-white">Pengenalan Aplikasi</h2>
                        <p class="text-sm text-bgray-500">Selamat datang di Aplikasi Pengelola Kendaraan KAS</p>
                    </div>
                </div>

                <div class="prose prose-sm max-w-none dark:prose-invert">
                    <p class="text-bgray-700 dark:text-bgray-300 leading-relaxed">
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
                    </div>
                </div>
            </section>

            {{-- ==================== LOGIN & AKUN ==================== --}}
            <section id="login" class="rounded-lg bg-white p-6 shadow-sm dark:bg-darkblack-600 scroll-mt-24">
                <div class="flex items-center gap-3 mb-6">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-accent-50">
                        <i class="fa fa-sign-in-alt text-xl text-accent-400"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-bgray-900 dark:text-white">Login & Akun</h2>
                        <p class="text-sm text-bgray-500">Cara masuk ke aplikasi</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-3">Langkah Login</h3>
                        <ol class="list-decimal list-inside space-y-3 text-bgray-700 dark:text-bgray-300">
                            <li>Buka aplikasi di browser (Chrome/Firefox/Edge direkomendasikan)</li>
                            <li>Masukkan <strong>Email</strong> yang terdaftar</li>
                            <li>Masukkan <strong>Password</strong></li>
                            <li>Centang "Ingat Saya" jika menggunakan perangkat pribadi</li>
                            <li>Klik tombol <strong>Masuk</strong></li>
                        </ol>
                    </div>

                    <div class="rounded-lg border border-bgray-200 dark:border-darkblack-400 overflow-hidden">
                        <div class="bg-bgray-50 dark:bg-darkblack-500 px-4 py-2 border-b border-bgray-200 dark:border-darkblack-400">
                            <span class="text-sm font-medium text-bgray-700 dark:text-bgray-300">Screenshot: Halaman Login</span>
                        </div>
                        <div class="p-4 bg-bgray-100 dark:bg-darkblack-700 flex items-center justify-center min-h-[200px]">
                            <div class="text-center text-bgray-500">
                                <i class="fa fa-image text-4xl mb-2"></i>
                                <p class="text-sm">Tampilan halaman login dengan form email dan password</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg bg-warning-50 p-4 border-l-4 border-warning-400">
                        <h4 class="font-semibold text-warning-700 mb-2"><i class="fa fa-exclamation-triangle mr-2"></i>Catatan Keamanan</h4>
                        <ul class="list-disc list-inside text-sm text-warning-600 space-y-1">
                            <li>Maksimal 5 percobaan login dalam 15 menit</li>
                            <li>Jika melebihi, akun akan dikunci sementara</li>
                            <li>Jangan bagikan password kepada orang lain</li>
                            <li>Hubungi admin jika lupa password</li>
                        </ul>
                    </div>
                </div>
            </section>

            {{-- ==================== DASHBOARD ==================== --}}
            <section id="dashboard" class="rounded-lg bg-white p-6 shadow-sm dark:bg-darkblack-600 scroll-mt-24">
                <div class="flex items-center gap-3 mb-6">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-accent-50">
                        <i class="fa fa-tachometer-alt text-xl text-accent-400"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-bgray-900 dark:text-white">Dashboard</h2>
                        <p class="text-sm text-bgray-500">Ringkasan informasi kendaraan</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <p class="text-bgray-700 dark:text-bgray-300">
                        Dashboard menampilkan ringkasan data kendaraan dan pengingat penting dalam satu tampilan.
                    </p>

                    <h3 class="text-lg font-semibold text-bgray-900 dark:text-white">Komponen Dashboard</h3>

                    <div class="grid gap-4">
                        {{-- Statistik Cards --}}
                        <div class="rounded-lg border border-bgray-200 dark:border-darkblack-400 p-4">
                            <h4 class="font-semibold text-bgray-900 dark:text-white mb-2">1. Kartu Statistik</h4>
                            <p class="text-sm text-bgray-600 dark:text-bgray-400 mb-3">
                                Baris atas menampilkan 4 kartu statistik:
                            </p>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <div class="rounded-lg bg-accent-50 p-3 text-center">
                                    <p class="text-2xl font-bold text-accent-400">150</p>
                                    <p class="text-xs text-bgray-600">Total Kendaraan</p>
                                </div>
                                <div class="rounded-lg bg-warning-50 p-3 text-center">
                                    <p class="text-2xl font-bold text-warning-400">12</p>
                                    <p class="text-xs text-bgray-600">Pajak 30 Hari</p>
                                </div>
                                <div class="rounded-lg bg-error-50 p-3 text-center">
                                    <p class="text-2xl font-bold text-error-300">5</p>
                                    <p class="text-xs text-bgray-600">Pajak Terlambat</p>
                                </div>
                                <div class="rounded-lg bg-bgray-100 p-3 text-center">
                                    <p class="text-2xl font-bold text-bgray-700">8</p>
                                    <p class="text-xs text-bgray-600">Servis Aktif</p>
                                </div>
                            </div>
                        </div>

                        {{-- Grafik --}}
                        <div class="rounded-lg border border-bgray-200 dark:border-darkblack-400 p-4">
                            <h4 class="font-semibold text-bgray-900 dark:text-white mb-2">2. Grafik Kendaraan</h4>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-2">Distribusi Umur Kendaraan</p>
                                    <p class="text-xs text-bgray-500">Bar chart menampilkan kategori umur:</p>
                                    <ul class="text-xs text-bgray-600 dark:text-bgray-400 mt-2 space-y-1">
                                        <li><span class="inline-block w-3 h-3 bg-accent-400 rounded mr-2"></span>0-5 tahun</li>
                                        <li><span class="inline-block w-3 h-3 bg-blue-400 rounded mr-2"></span>6-10 tahun</li>
                                        <li><span class="inline-block w-3 h-3 bg-warning-400 rounded mr-2"></span>11-15 tahun</li>
                                        <li><span class="inline-block w-3 h-3 bg-orange-400 rounded mr-2"></span>16-20 tahun</li>
                                        <li><span class="inline-block w-3 h-3 bg-error-300 rounded mr-2"></span>> 20 tahun</li>
                                    </ul>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-2">Kendaraan per Merk</p>
                                    <p class="text-xs text-bgray-500">Doughnut chart terpisah untuk Mobil dan Motor</p>
                                </div>
                            </div>
                        </div>

                        {{-- Pengingat Pajak --}}
                        <div class="rounded-lg border border-bgray-200 dark:border-darkblack-400 p-4">
                            <h4 class="font-semibold text-bgray-900 dark:text-white mb-2">3. Pengingat Pajak</h4>
                            <p class="text-sm text-bgray-600 dark:text-bgray-400 mb-3">
                                Tab interface untuk melihat pajak berdasarkan kategori waktu:
                            </p>
                            <div class="flex flex-wrap gap-2">
                                <span class="rounded-full bg-error-50 px-3 py-1 text-xs font-medium text-error-300">Terlambat</span>
                                <span class="rounded-full bg-warning-50 px-3 py-1 text-xs font-medium text-warning-400">7 Hari</span>
                                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-400">30 Hari</span>
                                <span class="rounded-full bg-accent-50 px-3 py-1 text-xs font-medium text-accent-400">6 Bulan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ==================== KENDARAAN ==================== --}}
            <section id="kendaraan" class="rounded-lg bg-white p-6 shadow-sm dark:bg-darkblack-600 scroll-mt-24">
                <div class="flex items-center gap-3 mb-6">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-accent-50">
                        <i class="fa fa-car text-xl text-accent-400"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-bgray-900 dark:text-white">Manajemen Kendaraan</h2>
                        <p class="text-sm text-bgray-500">Kelola data kendaraan</p>
                    </div>
                </div>

                <div class="space-y-6">
                    {{-- Lihat Daftar --}}
                    <div>
                        <h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-3">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-accent-400 text-white text-sm mr-2">1</span>
                            Melihat Daftar Kendaraan
                        </h3>
                        <ol class="list-decimal list-inside space-y-2 text-bgray-700 dark:text-bgray-300 ml-8">
                            <li>Klik menu <strong>Kendaraan</strong> di sidebar</li>
                            <li>Tampil daftar semua kendaraan dalam bentuk tabel</li>
                            <li>Gunakan <strong>Filter</strong> untuk menyaring data:
                                <ul class="list-disc list-inside ml-4 mt-1 text-sm text-bgray-600">
                                    <li>Jenis (Mobil/Motor)</li>
                                    <li>Merk</li>
                                    <li>Garasi</li>
                                    <li>Kevikepan</li>
                                    <li>Status</li>
                                </ul>
                            </li>
                            <li>Klik <strong>header kolom</strong> untuk mengurutkan data</li>
                            <li>Gunakan <strong>Search</strong> untuk pencarian cepat</li>
                        </ol>
                    </div>

                    {{-- Tambah Kendaraan --}}
                    <div>
                        <h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-3">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-accent-400 text-white text-sm mr-2">2</span>
                            Menambah Kendaraan Baru
                        </h3>
                        <ol class="list-decimal list-inside space-y-2 text-bgray-700 dark:text-bgray-300 ml-8">
                            <li>Klik tombol <span class="inline-flex items-center rounded bg-accent-100 px-2 py-0.5 text-xs text-accent-400"><i class="fa fa-plus mr-1"></i>Tambah</span></li>
                            <li>Isi form sesuai section:
                                <div class="mt-2 grid gap-2 text-sm">
                                    <div class="rounded bg-bgray-50 dark:bg-darkblack-500 p-3">
                                        <strong class="text-bgray-900 dark:text-white">Identitas Dasar</strong>
                                        <p class="text-bgray-600 dark:text-bgray-400">Jenis, Merk, Model, Plat Nomor, Tahun, Warna</p>
                                    </div>
                                    <div class="rounded bg-bgray-50 dark:bg-darkblack-500 p-3">
                                        <strong class="text-bgray-900 dark:text-white">Dokumen</strong>
                                        <p class="text-bgray-600 dark:text-bgray-400">Status BPKB, Nomor BPKB, Nomor Rangka, Nomor Mesin</p>
                                    </div>
                                    <div class="rounded bg-bgray-50 dark:bg-darkblack-500 p-3">
                                        <strong class="text-bgray-900 dark:text-white">Kepemilikan</strong>
                                        <p class="text-bgray-600 dark:text-bgray-400">Status Kepemilikan, Nama Pemilik (jika milik lembaga lain)</p>
                                    </div>
                                    <div class="rounded bg-bgray-50 dark:bg-darkblack-500 p-3">
                                        <strong class="text-bgray-900 dark:text-white">Lokasi & Pengguna</strong>
                                        <p class="text-bgray-600 dark:text-bgray-400">Garasi, Nama Pengguna saat ini</p>
                                    </div>
                                    <div class="rounded bg-bgray-50 dark:bg-darkblack-500 p-3">
                                        <strong class="text-bgray-900 dark:text-white">Gambar</strong>
                                        <p class="text-bgray-600 dark:text-bgray-400">Upload hingga 10 foto kendaraan</p>
                                    </div>
                                </div>
                            </li>
                            <li>Klik <strong>Simpan</strong> untuk menyimpan data</li>
                        </ol>
                    </div>

                    {{-- Detail Kendaraan --}}
                    <div>
                        <h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-3">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-accent-400 text-white text-sm mr-2">3</span>
                            Melihat Detail Kendaraan
                        </h3>
                        <ol class="list-decimal list-inside space-y-2 text-bgray-700 dark:text-bgray-300 ml-8">
                            <li>Klik <strong>Plat Nomor</strong> atau ikon <i class="fa fa-eye text-accent-400"></i> pada baris kendaraan</li>
                            <li>Halaman detail menampilkan:
                                <ul class="list-disc list-inside ml-4 mt-1 text-sm text-bgray-600">
                                    <li>Galeri foto kendaraan</li>
                                    <li>Informasi lengkap kendaraan</li>
                                    <li>Riwayat pengguna</li>
                                    <li>Riwayat pajak</li>
                                    <li>Riwayat servis</li>
                                </ul>
                            </li>
                        </ol>
                    </div>

                    {{-- Edit & Hapus --}}
                    <div>
                        <h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-3">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-accent-400 text-white text-sm mr-2">4</span>
                            Edit & Hapus Kendaraan
                        </h3>
                        <div class="grid md:grid-cols-2 gap-4 ml-8">
                            <div class="rounded-lg bg-bgray-50 dark:bg-darkblack-500 p-4">
                                <h4 class="font-medium text-bgray-900 dark:text-white mb-2"><i class="fa fa-edit text-warning-400 mr-2"></i>Edit</h4>
                                <p class="text-sm text-bgray-600 dark:text-bgray-400">Klik ikon pensil atau tombol Edit di halaman detail</p>
                            </div>
                            <div class="rounded-lg bg-bgray-50 dark:bg-darkblack-500 p-4">
                                <h4 class="font-medium text-bgray-900 dark:text-white mb-2"><i class="fa fa-trash text-error-300 mr-2"></i>Hapus</h4>
                                <p class="text-sm text-bgray-600 dark:text-bgray-400">Klik ikon tempat sampah, konfirmasi penghapusan</p>
                            </div>
                        </div>
                    </div>

                    {{-- Riwayat Pengguna --}}
                    <div class="rounded-lg bg-accent-50 p-4 border-l-4 border-accent-400">
                        <h4 class="font-semibold text-accent-700 mb-2"><i class="fa fa-history mr-2"></i>Fitur Riwayat Pengguna Otomatis</h4>
                        <p class="text-sm text-accent-600">
                            Ketika Anda mengubah nama Pengguna kendaraan, sistem akan <strong>otomatis</strong> mencatat
                            pengguna sebelumnya ke dalam Riwayat Pengguna dengan tanggal selesai hari ini.
                        </p>
                    </div>
                </div>
            </section>

            {{-- ==================== PAJAK ==================== --}}
            <section id="pajak" class="rounded-lg bg-white p-6 shadow-sm dark:bg-darkblack-600 scroll-mt-24">
                <div class="flex items-center gap-3 mb-6">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-accent-50">
                        <i class="fa fa-file-invoice text-xl text-accent-400"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-bgray-900 dark:text-white">Manajemen Pajak</h2>
                        <p class="text-sm text-bgray-500">Kelola jadwal dan pembayaran pajak kendaraan</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-3">Jenis Pajak</h3>
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
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-3">Status Pajak</h3>
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
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-3">Cara Menambah Pajak</h3>
                        <ol class="list-decimal list-inside space-y-2 text-bgray-700 dark:text-bgray-300">
                            <li>Buka menu <strong>Pajak</strong></li>
                            <li>Klik tombol <strong>Tambah Pajak</strong></li>
                            <li>Pilih kendaraan dari dropdown</li>
                            <li>Pilih jenis pajak (Tahunan/5 Tahunan)</li>
                            <li>Isi tanggal jatuh tempo</li>
                            <li>Isi nominal pajak (opsional)</li>
                            <li>Klik <strong>Simpan</strong></li>
                        </ol>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-3">Mencatat Pembayaran</h3>
                        <ol class="list-decimal list-inside space-y-2 text-bgray-700 dark:text-bgray-300">
                            <li>Pada daftar pajak, klik tombol <span class="inline-flex items-center rounded bg-accent-100 px-2 py-0.5 text-xs text-accent-400">Bayar</span></li>
                            <li>Isi tanggal bayar</li>
                            <li>Isi nominal (jika berbeda)</li>
                            <li>Isi denda (jika ada)</li>
                            <li>Upload bukti pembayaran (opsional)</li>
                            <li>Klik <strong>Simpan</strong></li>
                        </ol>
                    </div>
                </div>
            </section>

            {{-- ==================== SERVIS ==================== --}}
            <section id="servis" class="rounded-lg bg-white p-6 shadow-sm dark:bg-darkblack-600 scroll-mt-24">
                <div class="flex items-center gap-3 mb-6">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-accent-50">
                        <i class="fa fa-tools text-xl text-accent-400"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-bgray-900 dark:text-white">Manajemen Servis</h2>
                        <p class="text-sm text-bgray-500">Kelola jadwal dan riwayat servis kendaraan</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-3">Jenis Servis</h3>
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
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-3">Status Servis</h3>
                        <div class="flex flex-wrap gap-3">
                            <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-sm text-blue-400">
                                <i class="fa fa-calendar mr-2"></i>Dijadwalkan
                            </span>
                            <span class="inline-flex items-center rounded-full bg-warning-50 px-3 py-1 text-sm text-warning-400">
                                <i class="fa fa-spinner mr-2"></i>Dalam Proses
                            </span>
                            <span class="inline-flex items-center rounded-full bg-accent-50 px-3 py-1 text-sm text-accent-400">
                                <i class="fa fa-check mr-2"></i>Selesai
                            </span>
                            <span class="inline-flex items-center rounded-full bg-bgray-100 px-3 py-1 text-sm text-bgray-500">
                                <i class="fa fa-times mr-2"></i>Dibatalkan
                            </span>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-3">Data yang Dicatat</h3>
                        <ul class="list-disc list-inside space-y-1 text-bgray-700 dark:text-bgray-300">
                            <li>Kendaraan yang diservis</li>
                            <li>Jenis servis</li>
                            <li>Tanggal masuk & selesai</li>
                            <li>Kilometer saat servis</li>
                            <li>Nama bengkel</li>
                            <li>Deskripsi pekerjaan</li>
                            <li>Spare part yang diganti</li>
                            <li>Biaya servis</li>
                            <li>Jadwal servis berikutnya</li>
                        </ul>
                    </div>
                </div>
            </section>

            {{-- ==================== MASTER DATA ==================== --}}
            <section id="master-data" class="rounded-lg bg-white p-6 shadow-sm dark:bg-darkblack-600 scroll-mt-24">
                <div class="flex items-center gap-3 mb-6">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-accent-50">
                        <i class="fa fa-database text-xl text-accent-400"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-bgray-900 dark:text-white">Master Data</h2>
                        <p class="text-sm text-bgray-500">Data referensi yang digunakan di seluruh aplikasi</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        {{-- Garasi --}}
                        <div class="rounded-lg border border-bgray-200 dark:border-darkblack-400 p-4">
                            <h3 class="font-semibold text-bgray-900 dark:text-white mb-2">
                                <i class="fa fa-warehouse text-accent-400 mr-2"></i>Garasi
                            </h3>
                            <p class="text-sm text-bgray-600 dark:text-bgray-400 mb-3">Lokasi penyimpanan kendaraan</p>
                            <ul class="text-sm text-bgray-600 dark:text-bgray-400 space-y-1">
                                <li>• Nama & alamat garasi</li>
                                <li>• Kevikepan</li>
                                <li>• Tipe (Paroki/Lembaga/Pribadi)</li>
                                <li>• PIC & kontak</li>
                            </ul>
                        </div>

                        {{-- Merk --}}
                        <div class="rounded-lg border border-bgray-200 dark:border-darkblack-400 p-4">
                            <h3 class="font-semibold text-bgray-900 dark:text-white mb-2">
                                <i class="fa fa-tag text-accent-400 mr-2"></i>Merk
                            </h3>
                            <p class="text-sm text-bgray-600 dark:text-bgray-400 mb-3">Merk/brand kendaraan</p>
                            <ul class="text-sm text-bgray-600 dark:text-bgray-400 space-y-1">
                                <li>• Nama merk (Toyota, Honda, dll)</li>
                                <li>• Jenis (Mobil/Motor)</li>
                            </ul>
                        </div>

                        {{-- Paroki --}}
                        <div class="rounded-lg border border-bgray-200 dark:border-darkblack-400 p-4">
                            <h3 class="font-semibold text-bgray-900 dark:text-white mb-2">
                                <i class="fa fa-church text-accent-400 mr-2"></i>Paroki
                            </h3>
                            <p class="text-sm text-bgray-600 dark:text-bgray-400 mb-3">Data paroki di KAS</p>
                            <ul class="text-sm text-bgray-600 dark:text-bgray-400 space-y-1">
                                <li>• Nama paroki</li>
                                <li>• Kevikepan</li>
                                <li>• Alamat & kota</li>
                                <li>• Kontak</li>
                            </ul>
                        </div>

                        {{-- Lembaga --}}
                        <div class="rounded-lg border border-bgray-200 dark:border-darkblack-400 p-4">
                            <h3 class="font-semibold text-bgray-900 dark:text-white mb-2">
                                <i class="fa fa-building text-accent-400 mr-2"></i>Lembaga
                            </h3>
                            <p class="text-sm text-bgray-600 dark:text-bgray-400 mb-3">Lembaga di bawah KAS</p>
                            <ul class="text-sm text-bgray-600 dark:text-bgray-400 space-y-1">
                                <li>• Nama lembaga</li>
                                <li>• Alamat & kota</li>
                                <li>• Kontak</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ==================== KALENDER ==================== --}}
            <section id="kalender" class="rounded-lg bg-white p-6 shadow-sm dark:bg-darkblack-600 scroll-mt-24">
                <div class="flex items-center gap-3 mb-6">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-accent-50">
                        <i class="fa fa-calendar-alt text-xl text-accent-400"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-bgray-900 dark:text-white">Kalender</h2>
                        <p class="text-sm text-bgray-500">Tampilan kalender untuk jadwal pajak dan servis</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <p class="text-bgray-700 dark:text-bgray-300">
                        Kalender menampilkan semua jadwal pajak dan servis dalam tampilan visual kalender.
                    </p>

                    <div>
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
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-3">Warna Event</h3>
                        <div class="flex flex-wrap gap-3">
                            <span class="inline-flex items-center gap-2 rounded-lg bg-blue-100 px-3 py-1 text-sm text-blue-600">
                                <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                                Pajak
                            </span>
                            <span class="inline-flex items-center gap-2 rounded-lg bg-purple-100 px-3 py-1 text-sm text-purple-600">
                                <span class="w-3 h-3 rounded-full bg-purple-500"></span>
                                Servis
                            </span>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ==================== PROFIL ==================== --}}
            <section id="profil" class="rounded-lg bg-white p-6 shadow-sm dark:bg-darkblack-600 scroll-mt-24">
                <div class="flex items-center gap-3 mb-6">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-accent-50">
                        <i class="fa fa-user-cog text-xl text-accent-400"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-bgray-900 dark:text-white">Profil & Pengaturan</h2>
                        <p class="text-sm text-bgray-500">Kelola akun dan preferensi tampilan</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-3">Mengakses Profil</h3>
                        <ol class="list-decimal list-inside space-y-2 text-bgray-700 dark:text-bgray-300">
                            <li>Klik ikon <i class="fa fa-cog text-bgray-500"></i> di sebelah nama Anda di sidebar bawah</li>
                            <li>Atau klik nama/foto profil Anda</li>
                        </ol>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-3">Fitur Profil</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="rounded-lg border border-bgray-200 dark:border-darkblack-400 p-4">
                                <h4 class="font-medium text-bgray-900 dark:text-white mb-2">
                                    <i class="fa fa-camera text-accent-400 mr-2"></i>Foto Profil
                                </h4>
                                <p class="text-sm text-bgray-600 dark:text-bgray-400">
                                    Upload foto profil Anda. Format: JPG, PNG, WebP. Maks 2MB.
                                </p>
                            </div>
                            <div class="rounded-lg border border-bgray-200 dark:border-darkblack-400 p-4">
                                <h4 class="font-medium text-bgray-900 dark:text-white mb-2">
                                    <i class="fa fa-palette text-accent-400 mr-2"></i>Warna Aksen
                                </h4>
                                <p class="text-sm text-bgray-600 dark:text-bgray-400">
                                    Pilih warna aksen untuk personalisasi tampilan aplikasi.
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
                                    Ganti password akun Anda untuk keamanan.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-3">Pilihan Warna Aksen</h3>
                        <p class="text-sm text-bgray-600 dark:text-bgray-400 mb-3">
                            Warna aksen mempengaruhi tampilan tombol, link, icon, dan elemen UI lainnya.
                        </p>
                        <div class="flex flex-wrap gap-3">
                            @php
                                $presets = [
                                    '#22C55E' => 'Hijau',
                                    '#3B82F6' => 'Biru',
                                    '#8B5CF6' => 'Ungu',
                                    '#EF4444' => 'Merah',
                                    '#F97316' => 'Orange',
                                    '#EC4899' => 'Pink',
                                ];
                            @endphp
                            @foreach($presets as $hex => $name)
                                <div class="flex items-center gap-2 rounded-lg bg-bgray-50 dark:bg-darkblack-500 px-3 py-2">
                                    <span class="w-5 h-5 rounded-full" style="background-color: {{ $hex }};"></span>
                                    <span class="text-sm text-bgray-700 dark:text-bgray-300">{{ $name }}</span>
                                </div>
                            @endforeach
                        </div>
                        <p class="text-xs text-bgray-500 mt-2">
                            Selain preset di atas, Anda juga dapat memilih warna kustom menggunakan color picker.
                        </p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-3">Mode Gelap</h3>
                        <p class="text-sm text-bgray-600 dark:text-bgray-400">
                            Klik tombol <i class="fa fa-moon"></i>/<i class="fa fa-sun"></i> di sidebar bawah untuk
                            beralih antara mode terang dan gelap.
                        </p>
                    </div>
                </div>
            </section>

            {{-- ==================== MANAJEMEN PENGGUNA ==================== --}}
            @can('manage-users')
            <section id="pengguna" class="rounded-lg bg-white p-6 shadow-sm dark:bg-darkblack-600 scroll-mt-24">
                <div class="flex items-center gap-3 mb-6">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-accent-50">
                        <i class="fa fa-users-cog text-xl text-accent-400"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-bgray-900 dark:text-white">Manajemen Pengguna</h2>
                        <p class="text-sm text-bgray-500">Kelola akun pengguna aplikasi (Admin only)</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="rounded-lg bg-warning-50 p-4 border-l-4 border-warning-400">
                        <p class="text-sm text-warning-700">
                            <i class="fa fa-info-circle mr-2"></i>
                            Fitur ini hanya tersedia untuk <strong>Super Admin</strong> dan <strong>Admin</strong>.
                        </p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-3">Menambah Pengguna Baru</h3>
                        <ol class="list-decimal list-inside space-y-2 text-bgray-700 dark:text-bgray-300">
                            <li>Buka menu <strong>Pengguna</strong> di sidebar</li>
                            <li>Klik tombol <strong>Tambah Pengguna</strong></li>
                            <li>Isi form:
                                <ul class="list-disc list-inside ml-4 mt-1 text-sm text-bgray-600">
                                    <li>Nama lengkap</li>
                                    <li>Email (untuk login)</li>
                                    <li>Nomor telepon</li>
                                    <li>Peran (Admin/Admin Servis/User)</li>
                                    <li>Tipe pengguna (Pribadi/Paroki/Lembaga)</li>
                                    <li>Password</li>
                                </ul>
                            </li>
                            <li>Klik <strong>Simpan</strong></li>
                        </ol>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-3">Aktivasi/Nonaktifkan Pengguna</h3>
                        <p class="text-sm text-bgray-600 dark:text-bgray-400">
                            Pengguna yang dinonaktifkan tidak dapat login ke aplikasi. Untuk mengubah status:
                        </p>
                        <ol class="list-decimal list-inside space-y-2 text-bgray-700 dark:text-bgray-300 mt-2">
                            <li>Buka halaman edit pengguna</li>
                            <li>Ubah status menjadi <strong>Aktif</strong> atau <strong>Tidak Aktif</strong></li>
                            <li>Klik <strong>Simpan</strong></li>
                        </ol>
                    </div>
                </div>
            </section>
            @endcan

            {{-- ==================== TIPS & TRIK ==================== --}}
            <section id="tips" class="rounded-lg bg-white p-6 shadow-sm dark:bg-darkblack-600 scroll-mt-24">
                <div class="flex items-center gap-3 mb-6">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-accent-50">
                        <i class="fa fa-lightbulb text-xl text-accent-400"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-bgray-900 dark:text-white">Tips & Trik</h2>
                        <p class="text-sm text-bgray-500">Cara menggunakan aplikasi lebih efisien</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-3">Keyboard Shortcuts</h3>
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
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-3">Tips Penggunaan</h3>
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
                                <span><strong>Perhatikan badge warna</strong> untuk melihat status dengan cepat</span>
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
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-3">Butuh Bantuan?</h3>
                        <p class="text-bgray-700 dark:text-bgray-300">
                            Jika Anda mengalami kendala atau memiliki pertanyaan, silakan hubungi administrator sistem.
                        </p>
                    </div>
                </div>
            </section>

            {{-- Back to Top --}}
            <div class="text-center py-4">
                <a href="#pengenalan" class="inline-flex items-center gap-2 text-sm text-bgray-500 hover:text-accent-400">
                    <i class="fa fa-arrow-up"></i>
                    Kembali ke atas
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Highlight active section in navigation
        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.manual-nav-link');

            function highlightNav() {
                let currentSection = '';
                sections.forEach(section => {
                    const sectionTop = section.offsetTop - 150;
                    if (window.scrollY >= sectionTop) {
                        currentSection = section.getAttribute('id');
                    }
                });

                navLinks.forEach(link => {
                    link.classList.remove('bg-accent-50', 'text-accent-400', 'font-medium');
                    if (link.getAttribute('href') === '#' + currentSection) {
                        link.classList.add('bg-accent-50', 'text-accent-400', 'font-medium');
                    }
                });
            }

            window.addEventListener('scroll', highlightNav);
            highlightNav();
        });
    </script>
    @endpush

    <style>
        @media print {
            .sidebar-wrapper, .header-wrapper, .manual-nav-link, [onclick*="print"] {
                display: none !important;
            }
            .body-wrapper {
                margin-left: 0 !important;
            }
            section {
                break-inside: avoid;
                page-break-inside: avoid;
            }
        }
    </style>
</x-app-layout>
