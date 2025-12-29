<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paroki extends Model
{
    use HasFactory, Auditable;

    protected $table = 'paroki';

    // Status Paroki constants
    const STATUS_PAROKI = 1;
    const STATUS_QUASI_PAROKI = 2;
    const STATUS_LAINNYA = 3;
    const STATUS_STASI = 4;

    protected $fillable = [
        'kode',
        'kevikepan_id',
        'nama_gereja',
        'nama',
        'alamat',
        'kota',
        'kota_id',
        'telepon',
        'email',
        'fax',
        'latitude',
        'longitude',
        'status_paroki_id',
        'kecamatan_id',
        'kelurahan_id',
        'gambar',
        'parent_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude' => 'decimal:14',
        'longitude' => 'decimal:14',
        'kota_id' => 'integer',
        'kecamatan_id' => 'integer',
        'kelurahan_id' => 'integer',
        'status_paroki_id' => 'integer',
    ];

    /**
     * Get the kevikepan this paroki belongs to.
     */
    public function kevikepan(): BelongsTo
    {
        return $this->belongsTo(Kevikepan::class, 'kevikepan_id');
    }

    /**
     * Get the parent paroki (for stasi).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Paroki::class, 'parent_id');
    }

    /**
     * Get child paroki/stasi.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Paroki::class, 'parent_id');
    }

    /**
     * Get kendaraan yang dipinjam oleh paroki ini.
     */
    public function kendaraanDipinjam(): HasMany
    {
        return $this->hasMany(Kendaraan::class, 'dipinjam_paroki_id');
    }

    /**
     * Get kendaraan tarikan dari paroki ini.
     */
    public function kendaraanTarikan(): HasMany
    {
        return $this->hasMany(Kendaraan::class, 'tarikan_paroki_id');
    }

    /**
     * Get riwayat pemakai for this paroki.
     */
    public function riwayatPemakai(): HasMany
    {
        return $this->hasMany(RiwayatPemakai::class, 'paroki_id');
    }

    /**
     * Scope for active paroki only.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for paroki only (not stasi).
     */
    public function scopeParokiOnly($query)
    {
        return $query->where('status_paroki_id', self::STATUS_PAROKI);
    }

    /**
     * Scope for stasi only.
     */
    public function scopeStasiOnly($query)
    {
        return $query->where('status_paroki_id', self::STATUS_STASI);
    }

    /**
     * Check if this is a stasi.
     */
    public function isStasi(): bool
    {
        return $this->status_paroki_id === self::STATUS_STASI;
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status_paroki_id) {
            self::STATUS_PAROKI => 'Paroki',
            self::STATUS_QUASI_PAROKI => 'Quasi Paroki',
            self::STATUS_STASI => 'Stasi',
            default => 'Lainnya',
        };
    }

    /**
     * Get full name (nama gereja + nama).
     */
    public function getFullNameAttribute(): string
    {
        if ($this->nama_gereja) {
            return trim($this->nama_gereja) . ' ' . $this->nama;
        }
        return $this->nama;
    }

    /**
     * Get display name with kevikepan.
     */
    public function getDisplayNameAttribute(): string
    {
        $name = $this->full_name;
        if ($this->kevikepan) {
            $name .= ' (' . $this->kevikepan->nama . ')';
        }
        if ($this->isStasi()) {
            $name .= ' [Stasi]';
        }
        return $name;
    }

    /**
     * Get gambar URL.
     */
    public function getGambarUrlAttribute(): ?string
    {
        if (!$this->gambar) {
            return null;
        }
        // Assuming images are stored in storage/app/public/paroki/
        return asset('storage/paroki/' . $this->gambar);
    }

    /**
     * Daftar kota di Jawa Tengah & DIY.
     */
    public static function getKotaList(): array
    {
        return [
            1 => 'Kab. Banjarnegara',
            2 => 'Kab. Banyumas',
            3 => 'Kab. Batang',
            4 => 'Kab. Blora',
            5 => 'Kab. Boyolali',
            6 => 'Kab. Brebes',
            7 => 'Kab. Cilacap',
            8 => 'Kab. Demak',
            9 => 'Kab. Grobogan',
            10 => 'Kab. Jepara',
            11 => 'Kab. Karanganyar',
            12 => 'Kab. Kebumen',
            13 => 'Kab. Kendal',
            14 => 'Kab. Klaten',
            15 => 'Kab. Kudus',
            16 => 'Kab. Magelang',
            17 => 'Kab. Pati',
            18 => 'Kab. Pekalongan',
            19 => 'Kab. Pemalang',
            20 => 'Kab. Purbalingga',
            21 => 'Kab. Purworejo',
            22 => 'Kab. Rembang',
            23 => 'Kab. Semarang',
            24 => 'Kab. Sragen',
            25 => 'Kab. Sukoharjo',
            26 => 'Kab. Tegal',
            27 => 'Kab. Temanggung',
            28 => 'Kab. Wonogiri',
            29 => 'Kab. Wonosobo',
            30 => 'Kota Magelang',
            31 => 'Kota Pekalongan',
            32 => 'Kota Salatiga',
            33 => 'Kota Semarang',
            34 => 'Kota Surakarta',
            35 => 'Kota Tegal',
            36 => 'Kab. Bantul',
            37 => 'Kab. Gunungkidul',
            38 => 'Kab. Kulon Progo',
            39 => 'Kab. Sleman',
            40 => 'Kota Yogyakarta',
            41 => 'Kab. Ngawi',
        ];
    }

    /**
     * Get kota name from kota_id.
     */
    public function getKotaAttribute(): ?string
    {
        if (!$this->kota_id) {
            return null;
        }
        return self::getKotaList()[$this->kota_id] ?? null;
    }
}
