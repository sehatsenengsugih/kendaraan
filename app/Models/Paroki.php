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
}
