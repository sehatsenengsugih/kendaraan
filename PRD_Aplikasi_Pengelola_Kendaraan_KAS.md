# PRD — Aplikasi Pengelola Kendaraan (Keuskupan Agung Semarang)

**Versi**: 2.0
**Tanggal**: 27 Desember 2025
**Platform**: Web App
**Timezone**: WIB (Asia/Jakarta), data disimpan dalam UTC

---

## Tech Stack

| Layer | Teknologi | Versi | Keterangan |
|-------|-----------|-------|------------|
| **Backend** | PHP | 8.3+ | LTS, performa terbaik |
| **Framework** | Laravel | 11.x | LTS hingga 2027 |
| **Database** | PostgreSQL | 16+ | ACID compliant, JSON support |
| **Cache/Session** | Redis | 7+ | Session, cache, queue |
| **Frontend** | Tailwind CSS | 3.4+ | Utility-first CSS framework |
| **JavaScript** | Alpine.js | 3.x | Interaktivitas ringan |
| **Charts** | Chart.js | 4.x | Visualisasi data |
| **Web Server** | Nginx | latest | Reverse proxy + static files |
| **Container** | Docker + Compose | latest | Deployment consistency |

---

## 1) Ringkasan Eksekutif

Keuskupan Agung Semarang (KAS) memiliki banyak kendaraan (Mobil & Motor) yang dipakai oleh **pribadi**, **paroki**, atau **lembaga**. Aplikasi ini mengelola data aset kendaraan secara terpusat: detail kendaraan lengkap, dokumen, kepemilikan, lokasi garasi, riwayat pemakai, serta jadwal **pajak** dan **servis**.

Aplikasi memiliki 4 peran: **Super Admin**, **Admin**, **Admin Servis**, dan **User**. Admin bertanggung jawab mengelola data kendaraan dan user, serta memantau kewajiban pajak/servis.

---

## 2) Definisi Role & Hak Akses

### 2.1 Super Admin
- Akses penuh ke semua fitur
- Mengelola Admin dan User
- Melihat Audit Log
- Konfigurasi sistem

### 2.2 Admin
- CRUD kendaraan, garasi, merk, paroki, lembaga
- Mengelola pajak dan servis
- Mengelola User (tidak bisa kelola Admin)
- Melihat dashboard dan laporan

### 2.3 Admin Servis
- Mengelola servis kendaraan
- Melihat data kendaraan (read-only)
- Akses terbatas ke modul servis

### 2.4 User
- Melihat kendaraan yang dipegang
- Input laporan servis
- Melihat jadwal pajak/servis

---

## 3) Master Data

### 3.1 Kevikepan (Lookup Table - Seeded)

| Kode | Nama |
|------|------|
| SMG | Kevikepan Semarang |
| SLO | Kevikepan Surakarta |
| JKT | Kevikepan Jogja Timur |
| JKB | Kevikepan Jogja Barat |
| MGL | Kevikepan Magelang |
| KTG | Kevikepan Kategorial |

### 3.2 Paroki

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | PK |
| kevikepan_id | bigint | FK → kevikepan |
| nama | varchar(255) | Nama paroki |
| alamat | text | Alamat lengkap |
| kota | varchar(100) | Kota |
| telepon | varchar(20) | Nomor telepon |
| email | varchar(255) | Email paroki |
| is_active | boolean | Status aktif |
| timestamps | | created_at, updated_at |

### 3.3 Lembaga

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | PK |
| nama | varchar(255) | Nama lembaga |
| alamat | text | Alamat lengkap |
| kota | varchar(100) | Kota |
| telepon | varchar(20) | Nomor telepon |
| email | varchar(255) | Email lembaga |
| is_active | boolean | Status aktif |
| timestamps | | created_at, updated_at |

### 3.4 Garasi

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | PK |
| kevikepan_id | bigint | FK → kevikepan |
| kode | varchar(20) | Kode garasi (opsional) |
| nama | varchar(255) | Nama garasi |
| tipe | enum | paroki, lembaga, pribadi, lainnya |
| nama_paroki_lembaga | varchar(255) | Nama Paroki/Lembaga |
| alamat | text | Alamat lengkap |
| kota | varchar(100) | Kota |
| no_telp | varchar(20) | No HP/Telp PIC |
| nama_pic | varchar(255) | Nama PIC |
| catatan | text | Catatan tambahan |
| is_active | boolean | Status aktif |
| timestamps | | created_at, updated_at |
| deleted_at | timestamp | Soft delete |

### 3.5 Merk

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | PK |
| nama | varchar(100) | Nama merk (Toyota, Honda, dll) |
| timestamps | | created_at, updated_at |
| deleted_at | timestamp | Soft delete |

### 3.6 Status BPKB (Lookup Table)

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | PK |
| nama | varchar(100) | Nama status (Ada, Tidak Ada, Dalam Proses) |
| keterangan | text | Penjelasan status |
| urutan | int | Urutan tampil |
| is_active | boolean | Status aktif |
| timestamps | | created_at, updated_at |

**Seeder Default:**
| Nama | Keterangan |
|------|------------|
| Ada | BPKB tersedia dan lengkap |
| Tidak Ada | BPKB tidak tersedia |
| Dalam Proses | BPKB sedang dalam proses pengurusan |

---

## 4) Manajemen Kendaraan

### 4.1 Struktur Data Kendaraan

| Field | Type | Wajib | Keterangan |
|-------|------|-------|------------|
| **Identitas Dasar** |
| id | bigint | Ya | PK |
| jenis | enum | Ya | mobil, motor |
| merk_id | bigint | Ya | FK → merk |
| nama_model | varchar(255) | Ya | Nama/seri kendaraan |
| plat_nomor | varchar(20) | Ya | Unique, uppercase |
| tahun_pembuatan | year | Tidak | Tahun pembuatan |
| warna | varchar(50) | Tidak | Warna kendaraan |
| **Dokumen** |
| status_bpkb_id | bigint | Tidak | FK → status_bpkb |
| nomor_bpkb | varchar(50) | Tidak | Nomor BPKB |
| nomor_rangka | varchar(50) | Tidak | Nomor rangka |
| nomor_mesin | varchar(50) | Tidak | Nomor mesin |
| **Kepemilikan** |
| status_kepemilikan | enum | Ya | milik_kas, milik_lembaga_lain |
| nama_pemilik_lembaga | varchar(255) | Tidak | Jika milik lembaga lain |
| pemilik_lembaga_id | bigint | Tidak | FK → lembaga |
| **Lokasi & Pemegang** |
| garasi_id | bigint | Tidak | FK → garasi |
| pemegang_id | bigint | Tidak | FK → pengguna |
| pemegang_nama | varchar(255) | Tidak | Nama pemegang (free text) |
| **Status Kendaraan** |
| status | enum | Ya | aktif, nonaktif, dihibahkan, dijual |
| tanggal_perolehan | date | Tidak | Tanggal perolehan kendaraan |
| **Info Pembelian** |
| tanggal_beli | date | Tidak | Tanggal beli |
| harga_beli | decimal(15,2) | Tidak | Harga beli |
| **Info Hibah (jika status=dihibahkan)** |
| tanggal_hibah | date | Tidak | Tanggal hibah |
| nama_penerima_hibah | varchar(255) | Tidak | Penerima hibah |
| **Info Penjualan (jika status=dijual)** |
| tanggal_jual | date | Tidak | Tanggal jual |
| harga_jual | decimal(15,2) | Tidak | Harga jual |
| nama_pembeli | varchar(255) | Tidak | Nama pembeli |
| **Status Pinjam** |
| is_dipinjam | boolean | Ya | Default: false |
| dipinjam_oleh | varchar(255) | Tidak | Nama peminjam |
| dipinjam_paroki_id | bigint | Tidak | FK → paroki |
| tanggal_pinjam | date | Tidak | Tanggal mulai pinjam |
| **Info Tarikan** |
| is_tarikan | boolean | Ya | Default: false |
| tarikan_dari | varchar(255) | Tidak | Asal tarikan |
| tarikan_paroki_id | bigint | Tidak | FK → paroki |
| tarikan_lembaga_id | bigint | Tidak | FK → lembaga |
| tarikan_pemakai | varchar(255) | Tidak | Pemakai sebelumnya |
| tarikan_kondisi | text | Tidak | Kondisi saat ditarik |
| **Lainnya** |
| catatan | text | Tidak | Catatan tambahan |
| avatar_path | varchar(500) | Tidak | Path foto utama |
| created_by | bigint | Tidak | FK → pengguna |
| timestamps | | | created_at, updated_at |
| deleted_at | timestamp | | Soft delete |

### 4.2 Status Kendaraan

| Status | Keterangan |
|--------|------------|
| aktif | Kendaraan dalam penggunaan normal |
| nonaktif | Kendaraan tidak digunakan (rusak, perbaikan, dll) |
| dihibahkan | Kendaraan sudah dihibahkan |
| dijual | Kendaraan sudah dijual |

### 4.3 Galeri Gambar Kendaraan

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | PK |
| kendaraan_id | bigint | FK → kendaraan |
| nama_file | varchar(255) | Nama file asli |
| path | varchar(500) | Path file di storage |
| ukuran | int | Ukuran file (bytes) |
| mime_type | varchar(50) | image/jpeg, image/png, image/webp |
| is_avatar | boolean | True jika gambar utama |
| urutan | int | Urutan tampil |
| uploaded_by | bigint | FK → pengguna |
| timestamps | | created_at, updated_at |

**Ketentuan Upload:**
- Format: JPG, PNG, WebP
- Maksimal ukuran: 2 MB per gambar
- Maksimal jumlah: 10 gambar per kendaraan
- Hanya 1 gambar sebagai avatar

---

## 5) Riwayat Pemakai

Tabel terpisah untuk mencatat history pemakai kendaraan.

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | PK |
| kendaraan_id | bigint | FK → kendaraan |
| nama_pemakai | varchar(255) | Nama pemakai |
| jenis_pemakai | enum | lembaga, pribadi |
| paroki_id | bigint | FK → paroki (opsional) |
| lembaga_id | bigint | FK → lembaga (opsional) |
| tanggal_mulai | date | Tanggal mulai pakai |
| tanggal_selesai | date | Tanggal selesai (null = aktif) |
| catatan | text | Keterangan |
| timestamps | | created_at, updated_at |

**Fitur:**
- Satu kendaraan bisa memiliki banyak riwayat pemakai
- Pemakai aktif = tanggal_selesai null
- Durasi pemakaian dihitung otomatis
- Dapat ditambah langsung dari form edit kendaraan

---

## 6) Modul Pajak

### 6.1 Struktur Data Pajak

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | PK |
| kendaraan_id | bigint | FK → kendaraan |
| jenis | enum | tahunan, lima_tahunan |
| tanggal_jatuh_tempo | date | Jatuh tempo pajak |
| tanggal_bayar | date | Tanggal bayar (null = belum bayar) |
| nominal | decimal(15,2) | Nominal pembayaran |
| denda | decimal(15,2) | Denda keterlambatan |
| status | enum | belum_bayar, lunas, terlambat |
| nomor_notice | varchar(100) | Nomor notice pajak |
| bukti_path | varchar(500) | Path bukti pembayaran |
| catatan | text | Catatan |
| created_by | bigint | FK → pengguna |
| timestamps | | created_at, updated_at |

### 6.2 Jenis Pajak

| Jenis | Keterangan |
|-------|------------|
| tahunan | Pajak tahunan reguler |
| lima_tahunan | Pajak 5 tahunan (ganti plat) |

### 6.3 Status Pajak

| Status | Keterangan |
|--------|------------|
| belum_bayar | Belum dibayar |
| lunas | Sudah dibayar |
| terlambat | Terlambat bayar |

### 6.4 Fitur Pajak
- Dashboard pengingat pajak berdasarkan kategori:
  - Terlambat (sudah lewat jatuh tempo)
  - 7 hari ke depan
  - 8-30 hari ke depan
  - 31 hari - 6 bulan ke depan
- Tombol "Bayar" untuk menandai lunas
- Upload bukti pembayaran
- Riwayat pembayaran per kendaraan

---

## 7) Modul Servis

### 7.1 Struktur Data Servis

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | PK |
| kendaraan_id | bigint | FK → kendaraan |
| jenis | enum | rutin, perbaikan, darurat, overhaul |
| tanggal_servis | date | Tanggal masuk bengkel |
| tanggal_selesai | date | Tanggal selesai |
| kilometer | int | KM saat servis |
| bengkel | varchar(255) | Nama bengkel |
| deskripsi | text | Deskripsi pekerjaan |
| spare_parts | text | Spare part yang diganti |
| biaya | decimal(15,2) | Total biaya |
| status | enum | dijadwalkan, dalam_proses, selesai, dibatalkan |
| bukti_path | varchar(500) | Path bukti/nota |
| servis_berikutnya | date | Jadwal servis berikutnya |
| km_berikutnya | int | Target KM servis berikutnya |
| catatan | text | Catatan tambahan |
| created_by | bigint | FK → pengguna |
| timestamps | | created_at, updated_at |

### 7.2 Jenis Servis

| Jenis | Keterangan |
|-------|------------|
| rutin | Servis berkala terjadwal |
| perbaikan | Perbaikan kerusakan |
| darurat | Perbaikan darurat |
| overhaul | Perbaikan besar/turun mesin |

### 7.3 Status Servis

| Status | Keterangan |
|--------|------------|
| dijadwalkan | Servis dijadwalkan |
| dalam_proses | Sedang dikerjakan |
| selesai | Servis selesai |
| dibatalkan | Servis dibatalkan |

---

## 8) Penugasan Kendaraan

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | PK |
| kendaraan_id | bigint | FK → kendaraan |
| pengguna_id | bigint | FK → pengguna |
| tanggal_mulai | date | Tanggal mulai |
| tanggal_selesai | date | Tanggal selesai (null = aktif) |
| catatan | text | Catatan penugasan |
| created_by | bigint | FK → pengguna |
| timestamps | | created_at, updated_at |

**Ketentuan:**
- Satu kendaraan hanya punya 1 penugasan aktif
- Penugasan baru otomatis menutup penugasan sebelumnya

---

## 9) Dashboard

### 9.1 Statistik Utama
- Total kendaraan (mobil & motor)
- Pajak akan jatuh tempo (30 hari)
- Pajak terlambat
- Servis akan jatuh tempo
- Penugasan aktif

### 9.2 Grafik Distribusi Umur Kendaraan
- Bar chart dengan kategori:
  - 0-5 tahun (hijau)
  - 6-10 tahun (biru)
  - 11-15 tahun (kuning)
  - 16-20 tahun (oranye)
  - \> 20 tahun (merah)
- Progress bar per kategori dengan persentase

### 9.3 Grafik Kendaraan per Merk
- Doughnut chart untuk Mobil (tema biru)
- Doughnut chart untuk Motor (tema oranye)
- Tabel detail dengan progress bar horizontal
- Tooltip dengan jumlah dan persentase

### 9.4 Pengingat Pajak
- Tab interface dengan Alpine.js:
  - Terlambat (merah)
  - 7 Hari (kuning)
  - 30 Hari (biru)
  - 6 Bulan (hijau)
- Tabel dengan informasi kendaraan dan hari tersisa

### 9.5 Kendaraan per Garasi
- Daftar 6 garasi teratas berdasarkan jumlah kendaraan

---

## 10) Manajemen Pengguna

### 10.1 Struktur Data Pengguna

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | PK |
| name | varchar(255) | Nama lengkap |
| email | varchar(255) | Email (unique) |
| phone | varchar(20) | Nomor HP |
| role | enum | super_admin, admin, admin_servis, user |
| user_type | enum | pribadi, paroki, lembaga |
| organization_name | varchar(255) | Nama paroki/lembaga |
| status | enum | active, inactive |
| password | varchar(255) | Password (hashed) |
| google_id | varchar(255) | ID untuk SSO Google |
| remember_token | varchar(100) | Remember me token |
| timestamps | | created_at, updated_at |
| deleted_at | timestamp | Soft delete |

### 10.2 Role

| Role | Keterangan |
|------|------------|
| super_admin | Akses penuh |
| admin | Kelola kendaraan & user |
| admin_servis | Kelola servis saja |
| user | Pengguna biasa |

### 10.3 User Type

| Type | Keterangan |
|------|------------|
| pribadi | Perorangan |
| paroki | Mewakili paroki |
| lembaga | Mewakili lembaga |

---

## 11) Audit Log

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | PK |
| user_id | bigint | FK → pengguna (nullable) |
| event | varchar(50) | created, updated, deleted |
| auditable_type | varchar(255) | Nama model |
| auditable_id | bigint | ID record |
| old_values | jsonb | Nilai sebelum perubahan |
| new_values | jsonb | Nilai setelah perubahan |
| url | varchar(500) | URL request |
| ip_address | varchar(45) | IP address |
| user_agent | varchar(500) | Browser info |
| created_at | timestamp | Waktu kejadian |

**Model yang di-audit:**
- Kendaraan
- Garasi
- Merk
- Paroki
- Lembaga
- Pajak
- Servis
- RiwayatPemakai
- Penugasan

---

## 12) Halaman Aplikasi

### 12.1 Authentication
- Login (email/password + Google SSO)
- Logout
- Forgot Password

### 12.2 Dashboard
- Statistik ringkasan
- Grafik umur kendaraan
- Grafik merk kendaraan
- Pengingat pajak (tab)
- Kendaraan per garasi

### 12.3 Master Data
- Garasi (CRUD + filter kevikepan/kota)
- Merk (CRUD)
- Paroki (CRUD + filter kevikepan)
- Lembaga (CRUD)

### 12.4 Kendaraan
- List + filter (jenis, merk, garasi, kevikepan, status)
- Create (form lengkap dengan semua section)
- Edit (form lengkap + riwayat pemakai inline)
- Show (detail lengkap dengan galeri)

### 12.5 Pajak
- List + filter
- Create/Edit
- Bayar (tandai lunas)

### 12.6 Servis
- List + filter
- Create/Edit

### 12.7 Pengguna
- List + filter role
- Create/Edit
- Aktivasi/Nonaktif

### 12.8 Audit Log (Super Admin)
- List dengan filter
- Detail perubahan

---

## 13) Routes

| Method | URI | Keterangan |
|--------|-----|------------|
| **Auth** |
| GET | /login | Halaman login |
| POST | /login | Proses login |
| POST | /logout | Logout |
| GET | /auth/google | SSO Google |
| **Dashboard** |
| GET | /dashboard | Dashboard utama |
| **Kendaraan** |
| GET | /kendaraan | List kendaraan |
| GET | /kendaraan/create | Form tambah |
| POST | /kendaraan | Simpan kendaraan |
| GET | /kendaraan/{id} | Detail kendaraan |
| GET | /kendaraan/{id}/edit | Form edit |
| PUT | /kendaraan/{id} | Update kendaraan |
| DELETE | /kendaraan/{id} | Hapus kendaraan |
| **Garasi** |
| GET | /garasi | List garasi |
| GET | /garasi/create | Form tambah |
| POST | /garasi | Simpan garasi |
| GET | /garasi/{id} | Detail garasi |
| GET | /garasi/{id}/edit | Form edit |
| PUT | /garasi/{id} | Update garasi |
| DELETE | /garasi/{id} | Hapus garasi |
| **Merk** |
| GET | /merk | List merk |
| POST | /merk | Simpan merk |
| PUT | /merk/{id} | Update merk |
| DELETE | /merk/{id} | Hapus merk |
| **Paroki** |
| GET | /paroki | List paroki |
| POST | /paroki | Simpan paroki |
| PUT | /paroki/{id} | Update paroki |
| DELETE | /paroki/{id} | Hapus paroki |
| **Lembaga** |
| GET | /lembaga | List lembaga |
| POST | /lembaga | Simpan lembaga |
| PUT | /lembaga/{id} | Update lembaga |
| DELETE | /lembaga/{id} | Hapus lembaga |
| **Pajak** |
| GET | /pajak | List pajak |
| POST | /pajak | Simpan pajak |
| PUT | /pajak/{id} | Update pajak |
| POST | /pajak/{id}/bayar | Bayar pajak |
| DELETE | /pajak/{id} | Hapus pajak |
| **Servis** |
| GET | /servis | List servis |
| POST | /servis | Simpan servis |
| PUT | /servis/{id} | Update servis |
| DELETE | /servis/{id} | Hapus servis |
| **Pengguna** |
| GET | /pengguna | List pengguna |
| POST | /pengguna | Simpan pengguna |
| PUT | /pengguna/{id} | Update pengguna |
| DELETE | /pengguna/{id} | Hapus pengguna |
| **Audit Log** |
| GET | /audit-logs | List audit log |
| GET | /audit-logs/{id} | Detail audit log |

---

## 14) Keamanan

### 14.1 Authentication
- Session timeout: 60 menit (sliding)
- Session driver: Redis
- Password hashing: Argon2id
- Rate limiting: 5 attempts / 15 menit
- SSO Google dengan Laravel Socialite

### 14.2 Authorization
- RBAC dengan Laravel Policy/Gates
- Middleware auth untuk semua protected routes
- Scoped query untuk data per user

### 14.3 Data Protection
- HTTPS wajib
- CSRF protection (Laravel default)
- XSS protection (Blade auto-escaping)
- SQL Injection protection (Eloquent ORM)

---

## 15) Import Data

Aplikasi mendukung import data via Artisan Command:

### 15.1 Import Kendaraan
```bash
php artisan kendaraan:import {file.csv} [--dry-run]
```

### 15.2 Import Paroki
```bash
php artisan paroki:import {file.csv} [--dry-run]
```

### 15.3 Import Lembaga
```bash
php artisan lembaga:import {file.csv} [--dry-run]
```

### 15.4 Import Garasi
```bash
php artisan garasi:import {file.csv} [--dry-run]
```

**Fitur:**
- Validasi data sebelum import
- Dry-run mode untuk preview
- Mapping nama kevikepan fleksibel
- Skip duplikat otomatis
- Laporan hasil import

---

## 16) Glossary

| Term | Definisi |
|------|----------|
| **KAS** | Keuskupan Agung Semarang |
| **Kevikepan** | Wilayah administratif dalam Keuskupan (Vicariate) |
| **Paroki** | Komunitas umat Katolik di suatu wilayah |
| **Lembaga** | Institusi/organisasi di bawah naungan Keuskupan |
| **Garasi** | Lokasi fisik penyimpanan kendaraan |
| **Pemegang** | User yang bertanggung jawab atas kendaraan |
| **Tarikan** | Kendaraan yang ditarik dari pemakai sebelumnya |
| **BPKB** | Buku Pemilik Kendaraan Bermotor |
| **STNK** | Surat Tanda Nomor Kendaraan |

---

## 17) Changelog

### Versi 2.0 (27 Desember 2025)
- Implementasi penuh detail kendaraan
- Tambah tabel Paroki dan Lembaga
- Tambah tabel Status BPKB
- Tambah tabel Riwayat Pemakai
- Tambah fitur pinjam dan tarikan
- Tambah grafik umur kendaraan di dashboard
- Tambah grafik merk kendaraan di dashboard
- Tambah pengingat pajak dengan kategori waktu
- Tambah role Admin Servis
- Import data via Artisan Command
- Audit log untuk semua model utama

### Versi 1.4 (Initial)
- Struktur dasar aplikasi
- CRUD kendaraan, garasi, merk
- Modul pajak dan servis
- Manajemen pengguna
- Dashboard dasar
