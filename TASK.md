# TASK.md - Milestone & Progress Tracker

**Last Updated**: 29 Desember 2025

Legend:
- [x] Selesai
- [~] Sebagian selesai / butuh perbaikan
- [ ] Belum dikerjakan

---

## Milestone 1: Foundation & Setup

### 1.1 Project Setup
- [x] Inisialisasi Laravel 11
- [x] Konfigurasi PostgreSQL
- [x] Konfigurasi Redis (session/cache)
- [x] Setup Tailwind CSS + Vite
- [x] Setup Alpine.js
- [x] Konfigurasi timezone (Asia/Jakarta)

### 1.2 Authentication
- [x] Migration tabel `pengguna`
- [x] Model Pengguna dengan roles
- [x] Login/Logout dengan session
- [x] Google SSO integration
- [x] Middleware auth
- [ ] Forgot password flow
- [ ] Rate limiting login

### 1.3 Base Layout
- [x] App layout component
- [x] Sidebar dengan navigasi
- [x] Header dengan user dropdown
- [x] Dark mode support
- [x] Responsive design
- [x] Warna aksen per-user (CSS variables)

---

## Milestone 2: Master Data

### 2.1 Kevikepan (Lookup)
- [x] Migration tabel
- [x] Model Kevikepan
- [x] Seeder (6 kevikepan)
- [x] Relasi dengan Paroki & Garasi

### 2.2 Garasi
- [x] Migration tabel
- [x] Model Garasi
- [x] GarasiController (CRUD)
- [x] Views (index, create, edit, show)
- [x] Filter by kevikepan/kota
- [x] Soft delete
- [x] Audit logging

### 2.3 Merk
- [x] Migration tabel (dengan jenis: mobil/motor)
- [x] Model Merk
- [x] MerkController (CRUD)
- [x] Views (index, create, edit, show)
- [x] Soft delete
- [x] Audit logging

### 2.4 Status BPKB (Lookup)
- [x] Migration tabel
- [x] Model StatusBpkb
- [x] Seeder (Ada, Tidak Ada, Dalam Proses)

### 2.5 Paroki
- [x] Migration tabel
- [x] Model Paroki dengan relasi Kevikepan
- [x] ParokiController (CRUD)
- [x] Views (index, create, edit, show)
- [x] Filter by kevikepan
- [x] Audit logging

### 2.6 Lembaga
- [x] Migration tabel
- [x] Model Lembaga
- [x] LembagaController (CRUD)
- [x] Views (index, create, edit, show)
- [x] Audit logging

---

## Milestone 3: Manajemen Kendaraan

### 3.1 Model & Database
- [x] Migration tabel kendaraan (field lengkap)
- [x] Model Kendaraan dengan relasi
- [x] Status enum (aktif, nonaktif, dihibahkan, dijual)
- [x] Status kepemilikan (milik_kas, milik_paroki, milik_lembaga_lain)
- [x] Soft delete
- [x] Audit logging

### 3.2 CRUD Kendaraan
- [x] KendaraanController
- [x] Form create (semua section)
- [x] Form edit (semua section)
- [x] Detail view (show)
- [x] List view dengan filter
- [x] Sorting di semua kolom
- [x] Pagination

### 3.3 Informasi Kendaraan
- [x] Identitas dasar (plat, merk, model, tahun, warna)
- [x] Dokumen (status BPKB, nomor BPKB, rangka, mesin)
- [x] Upload dokumen BPKB (PDF)
- [x] Upload dokumen STNK (PDF)
- [x] Kepemilikan (milik KAS, paroki, lembaga lain)
- [x] Lokasi & pemegang
- [x] Info pembelian
- [x] Info hibah (conditional)
- [x] Info penjualan (conditional)
- [x] Status pinjam
- [x] Info tarikan
- [x] Catatan

### 3.4 Galeri Gambar
- [x] Migration tabel gambar_kendaraan
- [x] Model GambarKendaraan
- [x] Upload multiple gambar
- [x] Avatar (foto utama)
- [x] Reorder gambar
- [ ] Lightbox preview

### 3.5 Riwayat Pemakai
- [x] Migration tabel riwayat_pemakai
- [x] Model RiwayatPemakai
- [x] CRUD inline di form kendaraan
- [x] Jenis pemakai (paroki, lembaga, pribadi)
- [x] Upload dokumen serah terima (PDF)
- [x] Tampilan di detail kendaraan
- [x] Pengguna saat ini derived dari riwayat aktif
- [x] Auto-close riwayat lama saat tambah riwayat baru yang aktif

---

## Milestone 4: Modul Pajak

### 4.1 Model & Database
- [x] Migration tabel pajak
- [x] Model Pajak dengan relasi
- [x] Jenis (tahunan, lima_tahunan)
- [x] Status (belum_bayar, lunas, terlambat)
- [x] Audit logging

### 4.2 CRUD Pajak
- [x] PajakController
- [x] Form create/edit
- [x] List view dengan filter
- [x] Sorting & pagination
- [x] Upload bukti pembayaran
- [x] Fitur "Bayar" (tandai lunas)

### 4.3 Dashboard Pajak
- [x] Kategori pengingat (terlambat, 7 hari, 30 hari, 6 bulan)
- [x] Tab interface dengan Alpine.js
- [x] Tampilan hari tersisa

---

## Milestone 5: Modul Servis

### 5.1 Model & Database
- [x] Migration tabel servis
- [x] Model Servis dengan relasi
- [x] Jenis (rutin, perbaikan, darurat, overhaul)
- [x] Status (dijadwalkan, dalam_proses, selesai, dibatalkan)
- [x] Audit logging

### 5.2 CRUD Servis
- [x] ServisController
- [x] Form create/edit
- [x] List view dengan filter
- [x] Sorting & pagination
- [x] Upload bukti/nota
- [x] Jadwal servis berikutnya

---

## Milestone 6: Penugasan

### 6.1 Model & Database
- [x] Migration tabel penugasan
- [x] Model Penugasan dengan relasi
- [x] Audit logging

### 6.2 CRUD Penugasan
- [x] PenugasanController
- [x] Form create/edit
- [x] List view
- [x] Auto-close penugasan sebelumnya
- [ ] Integrasi dengan riwayat pemakai

---

## Milestone 7: Dashboard

### 7.1 Statistik
- [x] Total kendaraan (mobil & motor)
- [x] Pajak akan jatuh tempo
- [x] Pajak terlambat
- [x] Servis aktif
- [x] Penugasan aktif

### 7.2 Grafik & Visualisasi
- [x] Grafik umur kendaraan (bar chart)
- [x] Grafik kendaraan per merk (doughnut chart)
- [x] Progress bar distribusi
- [x] Chart.js integration

### 7.3 Widget
- [x] Pengingat pajak dengan tabs
- [x] Kendaraan per garasi (top 6)
- [ ] Servis mendatang
- [ ] Aktivitas terbaru

---

## Milestone 8: Kalender

### 8.1 Kalender View
- [x] CalendarController
- [x] Full calendar view
- [x] Mini calendar widget
- [x] Event pajak & servis
- [x] Drag & drop untuk reschedule
- [x] Create/edit modal

---

## Milestone 9: Manajemen Pengguna

### 9.1 CRUD Pengguna
- [x] UserController
- [x] Form create/edit
- [x] List view dengan filter role
- [x] Aktivasi/nonaktifkan user
- [x] Role assignment

### 9.2 Profil Pengguna
- [x] ProfileController
- [x] Edit nama & email
- [x] Upload foto profil (avatar)
- [x] Ubah password
- [x] Pilih warna aksen (preset + custom)

---

## Milestone 10: Audit & Security

### 10.1 Audit Log
- [x] Migration tabel audit_logs
- [x] Model AuditLog
- [x] Auditable trait
- [x] AuditLogController
- [x] List view dengan filter
- [x] Detail perubahan (old/new values)
- [x] Hanya Super Admin akses

### 10.2 Authorization
- [x] Gate untuk audit-logs
- [x] Middleware check role
- [ ] Policy untuk setiap model
- [ ] Scoped query per user type

---

## Milestone 11: Import Data

### 11.1 Import Commands
- [x] ImportKendaraan command
- [x] ImportParoki command
- [x] ImportLembaga command
- [x] ImportGarasi command
- [x] Dry-run mode
- [x] Validasi & error reporting

---

## Milestone 12: Fitur Tambahan

### 12.1 Search
- [x] SearchController
- [x] Global search di header
- [x] Search kendaraan, paroki, lembaga

### 12.2 Manual/Panduan
- [x] ManualController
- [x] Section management (admin)
- [x] Public view
- [x] Reorder sections

### 12.3 Export
- [ ] Export kendaraan ke Excel
- [ ] Export kendaraan ke PDF
- [ ] Export laporan pajak
- [ ] Export laporan servis

### 12.4 Notifikasi
- [ ] Email reminder pajak
- [ ] Email reminder servis
- [ ] In-app notification
- [ ] Konfigurasi notifikasi per user

---

## Milestone 13: Polish & Optimization

### 13.1 UI/UX
- [x] Responsive design
- [x] Dark mode
- [x] Loading states
- [ ] Toast notifications
- [ ] Confirmation dialogs
- [ ] Form validation feedback

### 13.2 Performance
- [ ] Query optimization
- [ ] Eager loading review
- [ ] Cache implementation
- [ ] Image optimization

### 13.3 Testing
- [ ] Unit tests
- [ ] Feature tests
- [ ] Browser tests (Dusk)

---

## Milestone 14: Deployment

### 14.1 Docker
- [ ] Dockerfile
- [ ] docker-compose.yml
- [ ] nginx.conf
- [ ] Environment configuration

### 14.2 Production
- [ ] SSL setup
- [ ] Backup strategy
- [ ] Monitoring
- [ ] Log management

---

## Bugs & Issues to Fix

1. [ ] Placeholder images untuk kendaraan tanpa foto
2. [ ] Validasi duplikat plat nomor saat edit
3. [ ] Handle timezone untuk date input
4. [ ] Optimasi query dashboard

---

## Recent Changes (29 Desember 2025)

1. [x] Tambah status kepemilikan "Milik Paroki"
2. [x] Upload dokumen BPKB (PDF)
3. [x] Upload dokumen STNK (PDF)
4. [x] Upload dokumen serah terima di riwayat pemakai
5. [x] Update views (create, edit, show) untuk fitur baru
6. [x] Buat CLAUDE.md, PLANNING.md, TASK.md
7. [x] Pengguna saat ini derived dari riwayat pemakai aktif (single source of truth)
8. [x] Auto-close riwayat lama saat tambah riwayat baru yang aktif
9. [x] Hapus input pemegang_nama manual, ganti dengan info dari riwayat

---

## Next Priority Tasks

1. [ ] Lightbox untuk preview gambar
2. [ ] Export ke Excel/PDF
3. [ ] Email notifications
4. [ ] Policy authorization per model
5. [ ] Sync data lama: migrate pemegang_nama ke riwayat_pemakai
