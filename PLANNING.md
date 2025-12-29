# PLANNING.md - Visi, Arsitektur & Tech Stack

## Visi Produk

Aplikasi Pengelola Kendaraan KAS adalah sistem manajemen aset kendaraan terpusat untuk Keuskupan Agung Semarang yang memungkinkan:

1. **Inventarisasi Terpusat** - Semua data kendaraan (mobil & motor) tercatat dalam satu sistem
2. **Tracking Kepemilikan** - Pelacakan status kepemilikan (milik KAS, paroki, atau lembaga)
3. **Manajemen Lokasi** - Pencatatan lokasi garasi dan pemegang kendaraan
4. **Pengingat Kewajiban** - Notifikasi pajak dan servis berkala
5. **Audit Trail** - Riwayat perubahan data untuk akuntabilitas
6. **Multi-role Access** - Akses berbeda untuk Super Admin, Admin, Admin Servis, dan User

---

## Arsitektur Sistem

### High-Level Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                         CLIENT LAYER                            │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────────┐ │
│  │   Browser   │  │   Mobile    │  │   (Future: Mobile App)  │ │
│  │  (Desktop)  │  │  (Responsive)│  │                         │ │
│  └──────┬──────┘  └──────┬──────┘  └────────────┬────────────┘ │
└─────────┼────────────────┼──────────────────────┼───────────────┘
          │                │                      │
          ▼                ▼                      ▼
┌─────────────────────────────────────────────────────────────────┐
│                       WEB SERVER (Nginx)                        │
│                 Reverse Proxy + Static Files + SSL              │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│                    APPLICATION LAYER (Laravel 11)               │
│  ┌────────────┐  ┌────────────┐  ┌────────────┐  ┌───────────┐ │
│  │ Controllers│  │   Models   │  │   Views    │  │ Middleware│ │
│  │  (HTTP)    │  │ (Eloquent) │  │  (Blade)   │  │  (Auth)   │ │
│  └────────────┘  └────────────┘  └────────────┘  └───────────┘ │
│  ┌────────────┐  ┌────────────┐  ┌────────────┐  ┌───────────┐ │
│  │  Services  │  │   Traits   │  │   Jobs     │  │  Events   │ │
│  │  (Logic)   │  │ (Auditable)│  │  (Queue)   │  │ (Hooks)   │ │
│  └────────────┘  └────────────┘  └────────────┘  └───────────┘ │
└────────────────────────────┬────────────────────────────────────┘
                             │
          ┌──────────────────┼──────────────────┐
          ▼                  ▼                  ▼
┌──────────────────┐ ┌──────────────┐ ┌──────────────────────────┐
│   PostgreSQL 16  │ │    Redis 7   │ │    File Storage          │
│   (Primary DB)   │ │(Cache/Queue) │ │  (Local / S3 Compatible) │
│                  │ │              │ │                          │
│ - kendaraan      │ │ - Sessions   │ │ - Avatar images          │
│ - garasi         │ │ - Cache      │ │ - Kendaraan photos       │
│ - pajak          │ │ - Queue jobs │ │ - PDF documents          │
│ - servis         │ │              │ │ - Bukti pembayaran       │
│ - audit_logs     │ │              │ │                          │
└──────────────────┘ └──────────────┘ └──────────────────────────┘
```

### Request Flow

```
User Request
     │
     ▼
┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│  Middleware │ ──► │  Controller │ ──► │   Service   │
│  (Auth,     │     │  (Validate, │     │  (Business  │
│   CSRF)     │     │   Route)    │     │   Logic)    │
└─────────────┘     └─────────────┘     └──────┬──────┘
                                               │
     ┌─────────────────────────────────────────┘
     ▼
┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│    Model    │ ──► │  Database   │     │  Audit Log  │
│  (Eloquent) │     │ (PostgreSQL)│ ◄── │  (Trait)    │
└──────┬──────┘     └─────────────┘     └─────────────┘
       │
       ▼
┌─────────────┐
│    View     │ ──► Response to User
│  (Blade +   │
│  Tailwind)  │
└─────────────┘
```

---

## Tech Stack

### Backend

| Komponen | Teknologi | Versi | Justifikasi |
|----------|-----------|-------|-------------|
| Runtime | PHP | 8.3+ | Performa terbaik, LTS |
| Framework | Laravel | 11.x | Ekosistem lengkap, LTS hingga 2027 |
| ORM | Eloquent | - | Bagian dari Laravel |
| Authentication | Laravel Breeze + Socialite | - | Session-based + Google SSO |

### Database

| Komponen | Teknologi | Versi | Justifikasi |
|----------|-----------|-------|-------------|
| Primary DB | PostgreSQL | 16+ | ACID, JSON support, performance |
| Cache | Redis | 7+ | Session, cache, queue |

### Frontend

| Komponen | Teknologi | Versi | Justifikasi |
|----------|-----------|-------|-------------|
| CSS Framework | Tailwind CSS | 3.4+ | Utility-first, customizable |
| JavaScript | Alpine.js | 3.x | Reactive tanpa build step |
| Charts | Chart.js | 4.x | Visualisasi data |
| Icons | Font Awesome | 6.x | Comprehensive icon set |
| Build Tool | Vite | 5.x | Fast bundling |

### Infrastructure

| Komponen | Teknologi | Justifikasi |
|----------|-----------|-------------|
| Web Server | Nginx | Reverse proxy, static files |
| Container | Docker + Compose | Konsistensi deployment |
| OS | Linux (Fedora/Ubuntu) | Production-ready |

---

## Required Tools

### Development Environment

```bash
# PHP & Extensions
php 8.3+
php-pgsql        # PostgreSQL driver
php-redis        # Redis driver
php-gd           # Image processing
php-mbstring     # String handling
php-xml          # XML processing
php-curl         # HTTP client
php-zip          # ZIP handling

# Package Managers
composer 2.x     # PHP dependency manager
npm 20+          # Node.js package manager (for frontend build)

# Database
postgresql 16+   # Primary database
redis 7+         # Cache & sessions

# Development Tools
git              # Version control
```

### Production Environment

```bash
# Server
nginx            # Web server
supervisor       # Process manager (untuk queue worker)

# Container (opsional)
docker
docker-compose

# SSL
certbot          # Let's Encrypt SSL
```

### IDE/Editor Recommendations

```
- VS Code dengan extensions:
  - PHP Intelephense
  - Laravel Blade Snippets
  - Tailwind CSS IntelliSense
  - Alpine.js IntelliSense

- PHPStorm (alternatif)
  - Laravel plugin
  - PHP inspections
```

---

## Database Schema Overview

### Core Tables

```
kevikepan (6 wilayah - seeded)
    │
    ├── paroki
    │       └── kendaraan (pemilik/dipinjam/tarikan)
    │
    └── garasi
            └── kendaraan (lokasi)

lembaga
    └── kendaraan (pemilik/tarikan)

merk
    └── kendaraan

status_bpkb (3 status - seeded)
    └── kendaraan

kendaraan
    ├── gambar_kendaraan
    ├── pajak
    ├── servis
    ├── penugasan
    └── riwayat_pemakai ◄── Pengguna saat ini = tanggal_selesai NULL

pengguna
    ├── penugasan
    └── audit_logs
```

### Pengguna Kendaraan (Single Source of Truth)

```
riwayat_pemakai
├── kendaraan_id      → FK ke kendaraan
├── jenis_pemakai     → paroki | lembaga | pribadi
├── paroki_id         → FK (jika jenis = paroki)
├── lembaga_id        → FK (jika jenis = lembaga)
├── nama_pemakai      → Nama (jika jenis = pribadi)
├── tanggal_mulai     → Tanggal mulai pakai
├── tanggal_selesai   → NULL = aktif, date = selesai
└── dokumen_serah_terima_path → PDF dokumen

Pengguna Saat Ini = SELECT * FROM riwayat_pemakai
                    WHERE tanggal_selesai IS NULL
                    ORDER BY tanggal_mulai DESC LIMIT 1
```

### Audit Trail

Semua tabel utama memiliki:
- `created_at` / `updated_at` (timestamps)
- `deleted_at` (soft delete untuk tabel tertentu)
- Logging ke `audit_logs` via `Auditable` trait

---

## Security Architecture

### Authentication
- Session-based dengan Redis
- Password hashing: Argon2id
- Rate limiting: 5 attempts / 15 menit
- Google SSO via Laravel Socialite

### Authorization
- RBAC (Role-Based Access Control)
- Laravel Gates & Policies
- Middleware untuk route protection

### Data Protection
- HTTPS wajib di production
- CSRF protection (Laravel default)
- XSS prevention (Blade auto-escaping)
- SQL injection prevention (Eloquent ORM)
- Input validation di setiap controller

---

## Deployment Architecture

### Development

```
Local Machine
├── PHP built-in server (php artisan serve)
├── PostgreSQL (local/Docker)
└── Redis (local/Docker)
```

### Staging/Production

```
Docker Compose
├── nginx (port 80/443)
├── php-fpm (Laravel app)
├── postgresql (database)
├── redis (cache/session)
└── queue-worker (background jobs)
```

### File Storage

```
Development: Local filesystem (storage/app/public)
Production:  Local atau S3-compatible storage

Symlink: public/storage -> storage/app/public
```

---

## Future Considerations

### Phase 1 (Current)
- Web application dengan fitur lengkap
- Responsive design untuk mobile browser

### Phase 2 (Planned)
- Export ke Excel/PDF
- Notifikasi email untuk pajak/servis
- Backup otomatis

### Phase 3 (Future)
- Mobile app (React Native / Flutter)
- API untuk integrasi eksternal
- Dashboard analytics lanjutan
- Barcode/QR scanning untuk kendaraan
