# CLAUDE.md - Panduan untuk Claude Code

## Tentang Proyek Ini

**Aplikasi Pengelola Kendaraan** untuk Keuskupan Agung Semarang (KAS). Aplikasi web untuk mengelola aset kendaraan (mobil & motor) milik keuskupan, paroki, dan lembaga gerejawi.

## Quick Start

```bash
# Lokasi proyek
cd /var/home/x1e/programku/kendaraan/kendaraan-app

# Jalankan development server
php artisan serve

# Clear cache
php artisan cache:clear && php artisan view:clear && php artisan route:clear

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed
```

## Struktur Proyek

```
kendaraan-app/
├── app/
│   ├── Http/Controllers/    # Controllers
│   ├── Models/              # Eloquent Models
│   ├── Traits/              # Auditable trait
│   └── Console/Commands/    # Import commands
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/             # Database seeders
├── resources/views/         # Blade templates
│   ├── components/          # Reusable components
│   ├── partials/            # Sidebar, header
│   ├── kendaraan/           # CRUD views
│   ├── garasi/              # CRUD views
│   └── ...
├── routes/web.php           # Web routes
└── public/storage/          # Uploaded files
```

## Models Utama

| Model | Tabel | Deskripsi |
|-------|-------|-----------|
| Kendaraan | kendaraan | Data kendaraan utama |
| Garasi | garasi | Lokasi penyimpanan kendaraan |
| Merk | merk | Merk kendaraan (Toyota, Honda, dll) |
| Paroki | paroki | Data paroki |
| Lembaga | lembaga | Data lembaga |
| Kevikepan | kevikepan | Wilayah keuskupan (lookup) |
| Pajak | pajak | Catatan pajak kendaraan |
| Servis | servis | Catatan servis kendaraan |
| Penugasan | penugasan | Penugasan kendaraan ke user |
| RiwayatPemakai | riwayat_pemakai | Riwayat pemakai kendaraan |
| Pengguna | pengguna | User aplikasi |
| StatusBpkb | status_bpkb | Status BPKB (lookup) |
| AuditLog | audit_logs | Log perubahan data |

## Relasi Penting

```
Kendaraan
├── belongsTo: Merk, Garasi, StatusBpkb, Pengguna (pemegang)
├── belongsTo: Lembaga (pemilik), Paroki (pemilik, dipinjam, tarikan)
├── hasMany: Pajak, Servis, Penugasan, RiwayatPemakai, GambarKendaraan
├── hasOne: pemakaiSaatIni → RiwayatPemakai (tanggal_selesai = null)

Garasi, Paroki
└── belongsTo: Kevikepan

RiwayatPemakai
└── belongsTo: Kendaraan, Paroki, Lembaga
```

## Pengguna Saat Ini (Single Source of Truth)

**PENTING**: Pengguna/pemegang kendaraan saat ini BUKAN disimpan di field `pemegang_nama`, melainkan **derived dari riwayat_pemakai yang aktif**.

### Cara Kerja

```
riwayat_pemakai
├── tanggal_selesai = NULL → Pengguna Aktif (saat ini)
└── tanggal_selesai = date → Pengguna Sebelumnya (history)
```

### Accessor di Model Kendaraan

```php
// Relasi ke riwayat aktif (single record)
public function pemakaiSaatIni(): HasOne
{
    return $this->hasOne(RiwayatPemakai::class)
        ->whereNull('tanggal_selesai')
        ->latest('tanggal_mulai');
}

// Nama pengguna saat ini
public function getPenggunaSaatIniAttribute(): ?string
{
    $aktif = $this->pemakaiSaatIni;
    if (!$aktif) return null;

    // Return nama berdasarkan jenis
    if ($aktif->jenis_pemakai === 'paroki') return $aktif->paroki?->nama;
    if ($aktif->jenis_pemakai === 'lembaga') return $aktif->lembaga?->nama;
    return $aktif->nama_pemakai;
}
```

### Mengganti Pengguna

Untuk mengganti pengguna kendaraan:
1. Tambah riwayat baru dengan `tanggal_selesai = null`
2. Controller otomatis close riwayat aktif sebelumnya
3. Pengguna saat ini terupdate otomatis

```php
// Di KendaraanController
private function closeActiveRiwayat(int $kendaraanId, ?string $tanggalSelesai = null): void
{
    RiwayatPemakai::where('kendaraan_id', $kendaraanId)
        ->whereNull('tanggal_selesai')
        ->update(['tanggal_selesai' => $tanggalSelesai ?? now()->toDateString()]);
}
```

### Eager Loading

Saat load kendaraan, selalu eager load `pemakaiSaatIni`:

```php
$kendaraan->load(['pemakaiSaatIni.paroki', 'pemakaiSaatIni.lembaga']);
```

## Role & Permission

| Role | Akses |
|------|-------|
| super_admin | Full access + audit log |
| admin | CRUD semua data, kelola user (bukan admin) |
| admin_servis | Kelola servis, lihat kendaraan (read-only) |
| user | Lihat kendaraan, input servis untuk kendaraan yang dipegang |

### User Pemegang Kendaraan

User dengan role `user` yang sedang memegang kendaraan (ada riwayat_pemakai aktif) dapat:
- Melihat daftar servis kendaraan yang mereka pegang
- Menambah servis baru untuk kendaraan yang dipegang
- Mengedit servis yang sudah ada (milik kendaraan mereka)
- Menandai servis sebagai selesai

Yang **tidak bisa** dilakukan user pemegang:
- Menghapus data servis
- Mengakses servis kendaraan lain
- Mengakses menu master data

### Penentuan User Pemegang

User dikaitkan dengan kendaraan melalui field `pengguna_id` di tabel `riwayat_pemakai`:

```php
// Di RiwayatPemakai
public function pengguna(): BelongsTo
{
    return $this->belongsTo(Pengguna::class, 'pengguna_id');
}

// Di Pengguna
public function kendaraanDipegang()
{
    return Kendaraan::whereHas('riwayatPemakaiAktif', function ($query) {
        $query->where('pengguna_id', $this->id);
    });
}

// Cek apakah user memegang kendaraan tertentu
$user->isHoldingKendaraan($kendaraanId);
```

## Konvensi Kode

### Controller
- Gunakan resource controller (`index`, `create`, `store`, `show`, `edit`, `update`, `destroy`)
- Validasi di method `store()` dan `update()`
- Upload file dengan `$file->storeAs($directory, $filename, 'public')`

### Views (Blade)
- Layout: `<x-app-layout>` dengan slot `title` dan `header`
- Form: `enctype="multipart/form-data"` untuk file upload
- Styling: Tailwind CSS dengan custom colors (`accent-300`, `bgray-*`, `darkblack-*`)
- Dark mode: Gunakan `dark:` prefix

### Model
- Soft delete: Gunakan `SoftDeletes` trait
- Audit: Gunakan `Auditable` trait untuk logging
- Relasi: Definisikan return type `BelongsTo`, `HasMany`

### Database
- Naming: snake_case (contoh: `tanggal_mulai`, `status_kepemilikan`)
- Enum: Gunakan constants di Model
- Foreign key: Selalu nullable dengan `nullOnDelete()`

## Enum Values

### Kendaraan
```php
// Jenis
'mobil', 'motor'

// Status
'aktif', 'nonaktif', 'dihibahkan', 'dijual'

// Status Kepemilikan
'milik_kas', 'milik_paroki', 'milik_lembaga_lain'
```

### Pajak
```php
// Jenis
'tahunan', 'lima_tahunan'

// Status
'belum_bayar', 'lunas', 'terlambat'
```

### Servis
```php
// Jenis
'rutin', 'perbaikan', 'darurat', 'overhaul'

// Status
'dijadwalkan', 'dalam_proses', 'selesai', 'dibatalkan'
```

### Riwayat Pemakai
```php
// Jenis Pemakai
'paroki', 'lembaga', 'pribadi'
```

## File Upload

| Tipe | Path | Max Size | Format |
|------|------|----------|--------|
| Avatar Kendaraan | kendaraan/avatar/ | 2MB | jpg, png, webp |
| Galeri Kendaraan | kendaraan/gambar/ | 2MB | jpg, png, webp |
| Dokumen BPKB | kendaraan/dokumen/bpkb/ | 5MB | pdf |
| Dokumen STNK | kendaraan/dokumen/stnk/ | 5MB | pdf |
| Dokumen Serah Terima | riwayat/dokumen/ | 5MB | pdf |
| Bukti Pajak | pajak/bukti/ | 5MB | pdf, jpg, png |
| Bukti Servis | servis/bukti/ | 5MB | pdf, jpg, png |
| Avatar User | pengguna/avatar/ | 2MB | jpg, png, webp |

## Testing Manual

```bash
# Test login
curl -X POST http://localhost:8000/login \
  -d "email=admin@kas.or.id&password=password"

# Test dengan browser
# URL: http://localhost:8000
# Login: admin@kas.or.id / password
```

## Referensi Dokumentasi

- PRD lengkap: `/home/x1e/programku/kendaraan/PRD_Aplikasi_Pengelola_Kendaraan_KAS.md`
- Planning: `PLANNING.md`
- Task list: `TASK.md`

## Catatan Penting

1. **Timezone**: WIB (Asia/Jakarta), data disimpan UTC
2. **Soft Delete**: Kendaraan, Garasi, Merk, Pengguna menggunakan soft delete
3. **Audit Log**: Semua model utama di-audit (lihat `Auditable` trait)
4. **Plat Nomor**: Uppercase, format "H 1234 AB"
5. **Warna Aksen**: User bisa pilih warna aksen via profil (CSS variables)
6. **Pengguna Kendaraan**: Derived dari `riwayat_pemakai` yang aktif, bukan field terpisah
7. **Auto-close Riwayat**: Saat tambah riwayat baru yang aktif, riwayat lama otomatis ditutup

## Troubleshooting

```bash
# View tidak terupdate
php artisan view:clear

# Route tidak ditemukan
php artisan route:clear

# Model tidak ditemukan
composer dump-autoload

# Storage link error
php artisan storage:link

# Migration error
php artisan migrate:fresh --seed
```
