# Changelog

Semua perubahan penting pada proyek ini akan didokumentasikan di file ini.

Format berdasarkan [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
dan proyek ini mengikuti [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [2.4.0] - 2025-12-29

### Added
- Implementasi mobile best practices (touch targets, safe area insets)
- Mobile card view untuk tabel Riwayat Pengguna di halaman detail kendaraan
- Responsive photo gallery thumbnails (4 cols mobile, 6 cols desktop)
- E2E tests untuk kendaraan detail, sidebar bottom, dan logo desktop

### Changed
- Logo sidebar desktop di-center dengan subtitle "Keuskupan Agung Semarang"
- Ukuran logo diperbesar dari h-16 ke h-20
- Toggle button sidebar menggunakan absolute positioning

### Fixed
- User section sidebar (profil, dark/light, logout) sekarang fixed di bawah
- Back button touch target diperbesar ke minimum 44x44px
- Tampilan mobile untuk tabel dan pagination

**Commits:** `401433b`, `0b822cd`, `9196963`

---

## [2.3.0] - 2025-12-29

### Added
- Logo motoreKAS di halaman login dan sidebar
- GitHub Actions untuk CI/CD (Laravel tests, Playwright tests)
- Fitur user pemegang dapat input servis kendaraan yang dipegang

### Changed
- Rebuild struktur paroki dan generate garasi dari paroki
- Update README dengan informasi lengkap aplikasi

### Fixed
- Test untuk redirect ke login

**Commits:** `70ab3d0`, `d4d2587`, `55f57d3`, `e9a1c78`, `becc965`, `013c10e`

---

## [2.2.0] - 2025-12-29

### Added
- Fitur assign kendaraan ke user
- Pengguna saat ini derived dari riwayat pemakai aktif (single source of truth)

### Fixed
- Hapus widget Tambah Penugasan dari dashboard
- Perbaikan dashboard

**Commits:** `bf9f870`, `bbfc5cc`, `d7401f7`

---

## [2.1.0] - 2025-12-28

### Added
- TinyMCE editor untuk konten panduan
- CRUD dan navigasi fixed untuk halaman panduan

### Fixed
- Batasi akses Master Data hanya untuk admin
- Perbaiki tampilan halaman login

**Commits:** `532be6d`, `bcbbe41`, `ebf3cda`, `c72fa5c`

---

## [2.0.0] - 2025-12-28

### Added
- **Fitur warna aksen per-user** dengan 6 preset warna + custom color picker
- CSS variables untuk warna aksen (`--accent-50` s/d `--accent-400`)
- Halaman panduan pengguna (manual)
- Master password untuk reset password user

### Changed
- Terapkan warna aksen ke seluruh UI (sidebar, buttons, forms, icons)
- Semua komponen menggunakan CSS variables untuk warna aksen

**Commits:** `8038278`, `0b7a3a5`, `39141f7`

---

## [1.9.0] - 2025-12-28

### Added
- Fitur kalender kendaraan (pajak, servis, penugasan)
- Fitur avatar pengguna dengan upload gambar
- Otomatis histori & seragamkan istilah pengguna

### Changed
- Hapus file test development

**Commits:** `04e754d`, `892413e`, `5f595b8`, `4047f5d`

---

## [1.8.0] - 2025-12-28

### Added
- Fitur manajemen gambar kendaraan (multiple images)

### Fixed
- Ganti istilah status BPKB "Diberikan" menjadi "Diserahkan"
- Tambah role Admin Servis dan perbaiki label role
- Normalisasi format plat nomor kendaraan (uppercase, spasi)

**Commits:** `0d5de87`, `b5c2d90`, `9961da6`, `faf9c7c`

---

## [1.7.0] - 2025-12-27

### Added
- Fitur pencarian global di header
- Fitur sorting A-Z/Z-A pada semua tabel

### Changed
- Perbaikan dashboard dengan statistik lebih lengkap

**Commits:** `0d3a803`, `b05051e`

---

## [1.6.0] - 2025-12-27

### Changed
- Refactor komentar kode menjadi bahasa Indonesia
- Update PRD ke versi 2.0 sesuai implementasi aktual

**Commits:** `f8d019b`, `197d678`

---

## [1.5.0] - 2025-12-27

### Added
- Import CSV untuk data kendaraan, paroki, lembaga, garasi
- Dashboard pengingat pajak (jatuh tempo)
- Grafik distribusi kendaraan per merk (Chart.js)

**Commits:** `f9b4191`, `422d740`

---

## [1.4.0] - 2025-12-27

### Added
- Role `admin_servis` untuk akses khusus menu servis
- Dropdown status BPKB menggantikan checkbox ada_bpkb

### Fixed
- Field `garasi_id` dan `tanggal_perolehan` menjadi nullable

**Commits:** `39ffa44`, `488e3f0`, `0c14bf4`

---

## [1.3.0] - 2025-12-27

### Added
- Master data Paroki dengan relasi Kevikepan
- Master data Lembaga
- Sistem Audit Log untuk tracking perubahan data
- Enhanced form Kendaraan dengan field tambahan

**Commits:** `5b58668`

---

## [1.2.0] - 2025-12-27

### Added
- Sistem notifikasi untuk user
- Manajemen user (CRUD pengguna)
- Role-based access control (super_admin, admin, user)

**Commits:** `f016045`

---

## [1.1.0] - 2025-12-27

### Added
- Sidebar toggle (collapse/expand)
- Dark/Light theme toggle dengan localStorage persistence

### Fixed
- Sidebar layout: prevent main content overlap
- Main content clipping when sidebar is open

**Commits:** `a46f5d8`, `ebb248a`, `381568d`

---

## [1.0.0] - 2025-12-27

### Added
- **Initial Release** - Kendaraan Management System untuk Keuskupan Agung Semarang
- CRUD Kendaraan (mobil & motor)
- CRUD Garasi
- CRUD Merk
- CRUD Pajak kendaraan
- CRUD Servis kendaraan
- CRUD Penugasan kendaraan
- Dashboard dengan statistik kendaraan
- Authentication dengan Laravel Breeze
- Responsive UI dengan Tailwind CSS

**Commits:** `e83a02b`

---

## Statistik Proyek

| Versi | Tanggal | Total Commits |
|-------|---------|---------------|
| 1.0.0 | 2025-12-27 | 1 |
| 1.1.0 | 2025-12-27 | 3 |
| 1.2.0 | 2025-12-27 | 1 |
| 1.3.0 | 2025-12-27 | 1 |
| 1.4.0 | 2025-12-27 | 3 |
| 1.5.0 | 2025-12-27 | 2 |
| 1.6.0 | 2025-12-27 | 2 |
| 1.7.0 | 2025-12-27 | 2 |
| 1.8.0 | 2025-12-28 | 4 |
| 1.9.0 | 2025-12-28 | 4 |
| 2.0.0 | 2025-12-28 | 3 |
| 2.1.0 | 2025-12-28 | 4 |
| 2.2.0 | 2025-12-29 | 3 |
| 2.3.0 | 2025-12-29 | 6 |
| 2.4.0 | 2025-12-29 | 3 |

**Total: 42 commits dalam 15 versi**

---

## Teknologi

- **Backend:** Laravel 10
- **Frontend:** Blade, Tailwind CSS, Alpine.js
- **Database:** PostgreSQL
- **Charts:** Chart.js
- **Calendar:** FullCalendar
- **Editor:** TinyMCE
- **Testing:** PHPUnit, Playwright
- **CI/CD:** GitHub Actions

---

## Kontributor

- Development: Claude Code (AI Assistant)
- Product Owner: Keuskupan Agung Semarang

---

## Links

- Repository: https://github.com/sehatsenengsugih/kendaraan
- Documentation: [CLAUDE.md](CLAUDE.md)
- PRD: [PRD_Aplikasi_Pengelola_Kendaraan_KAS.md](../PRD_Aplikasi_Pengelola_Kendaraan_KAS.md)
