# Aplikasi Pengelola Kendaraan KAS

Sistem manajemen aset kendaraan terpusat untuk **Keuskupan Agung Semarang (KAS)**.

Aplikasi web untuk mengelola data kendaraan (mobil & motor) milik keuskupan, paroki, dan lembaga gerejawi secara efisien.

---

## Fitur Utama

### Manajemen Kendaraan
- Inventarisasi lengkap kendaraan (mobil & motor)
- Data identitas, dokumen (BPKB, STNK), dan kepemilikan
- Galeri foto dengan avatar
- Riwayat pemakai (single source of truth)
- Status: aktif, nonaktif, dihibahkan, dijual
- Fitur pinjam dan tarikan kendaraan

### Manajemen Pajak
- Pencatatan pajak tahunan dan 5 tahunan
- Pengingat otomatis berdasarkan jatuh tempo
- Dashboard kategori: terlambat, 7 hari, 30 hari, 6 bulan
- Upload bukti pembayaran

### Manajemen Servis
- Pencatatan servis rutin, perbaikan, darurat, overhaul
- Jadwal servis berikutnya
- Upload bukti/nota servis

### Master Data
- **Kevikepan**: 6 wilayah keuskupan
- **Paroki**: Data paroki dengan relasi kevikepan
- **Lembaga**: Data lembaga gerejawi
- **Garasi**: Lokasi penyimpanan kendaraan
- **Merk**: Daftar merk kendaraan

### Dashboard & Laporan
- Statistik kendaraan (total, aktif, per jenis)
- Grafik distribusi umur kendaraan
- Grafik kendaraan per merk
- Pengingat pajak dengan tabs interaktif
- Distribusi kendaraan per garasi

### Keamanan & Audit
- Multi-role access control (Super Admin, Admin, Admin Servis, User)
- Audit log untuk semua perubahan data
- Google SSO integration
- Session-based authentication dengan Redis

---

## Tech Stack

| Layer | Teknologi | Versi |
|-------|-----------|-------|
| Backend | PHP | 8.3+ |
| Framework | Laravel | 11.x |
| Database | PostgreSQL | 16+ |
| Cache/Session | Redis | 7+ |
| CSS | Tailwind CSS | 3.4+ |
| JavaScript | Alpine.js | 3.x |
| Charts | Chart.js | 4.x |
| Build Tool | Vite | 5.x |

---

## Requirements

- PHP 8.3+ dengan extensions: pgsql, redis, gd, mbstring, xml, curl, zip
- PostgreSQL 16+
- Redis 7+
- Composer 2.x
- Node.js 20+ & NPM

---

## Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/sehatsenengsugih/kendaraan.git
cd kendaraan
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` dan sesuaikan:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=kendaraan
DB_USERNAME=postgres
DB_PASSWORD=secret

CACHE_DRIVER=redis
SESSION_DRIVER=redis
REDIS_HOST=127.0.0.1
```

### 4. Database Setup

```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
```

### 5. Build Assets

```bash
npm run build
```

### 6. Run Development Server

```bash
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

---

## Login Default

| Role | Email | Password |
|------|-------|----------|
| Super Admin | superadmin@kas.or.id | password |
| Admin | admin@kas.or.id | password |

---

## Role & Hak Akses

| Role | Hak Akses |
|------|-----------|
| **Super Admin** | Akses penuh + audit log + kelola admin |
| **Admin** | CRUD kendaraan, master data, pajak, servis, kelola user |
| **Admin Servis** | Kelola servis, lihat kendaraan (read-only) |
| **User** | Lihat kendaraan sendiri, input laporan servis |

---

## Struktur Direktori

```
kendaraan-app/
├── app/
│   ├── Http/Controllers/    # Controllers
│   ├── Models/              # Eloquent Models
│   ├── Traits/              # Auditable trait
│   └── Console/Commands/    # Import commands
├── database/
│   ├── migrations/          # Database schema
│   └── seeders/             # Data seeders
├── resources/views/         # Blade templates
│   ├── components/          # Reusable UI components
│   ├── partials/            # Sidebar, header
│   ├── kendaraan/           # CRUD views
│   └── ...
├── routes/web.php           # Web routes
├── public/storage/          # Uploaded files
├── CLAUDE.md                # Panduan developer
├── PLANNING.md              # Arsitektur & tech stack
└── TASK.md                  # Progress tracker
```

---

## Import Data

Aplikasi mendukung import data via CLI:

```bash
# Import kendaraan dari CSV
php artisan kendaraan:import data.csv --dry-run

# Import paroki
php artisan paroki:import paroki.csv

# Import lembaga
php artisan lembaga:import lembaga.csv

# Import garasi
php artisan garasi:import garasi.csv
```

Gunakan `--dry-run` untuk preview tanpa menyimpan ke database.

---

## Dokumentasi Developer

| File | Deskripsi |
|------|-----------|
| `CLAUDE.md` | Panduan lengkap untuk developer |
| `PLANNING.md` | Visi, arsitektur, dan tech stack |
| `TASK.md` | Milestone dan progress tracker |

---

## Glossary

| Istilah | Definisi |
|---------|----------|
| **KAS** | Keuskupan Agung Semarang |
| **Kevikepan** | Wilayah administratif dalam Keuskupan |
| **Paroki** | Komunitas umat Katolik di suatu wilayah |
| **Lembaga** | Institusi di bawah naungan Keuskupan |
| **Garasi** | Lokasi fisik penyimpanan kendaraan |
| **Tarikan** | Kendaraan yang ditarik dari pemakai sebelumnya |
| **BPKB** | Buku Pemilik Kendaraan Bermotor |
| **STNK** | Surat Tanda Nomor Kendaraan |

---

## Troubleshooting

```bash
# Clear all cache
php artisan cache:clear && php artisan view:clear && php artisan route:clear

# Recreate database
php artisan migrate:fresh --seed

# Fix storage symlink
php artisan storage:link

# Autoload classes
composer dump-autoload
```

---

## License

Aplikasi ini dikembangkan untuk keperluan internal Keuskupan Agung Semarang.

---

**Dikembangkan dengan Laravel 11 & Tailwind CSS**
