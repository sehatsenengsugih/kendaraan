<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kendaraan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kendaraan';

    protected $fillable = [
        'plat_nomor',
        'nomor_bpkb',
        'merk_id',
        'nama_model',
        'tahun_pembuatan',
        'warna',
        'jenis',
        'garasi_id',
        'pemegang_id',
        'status',
        'tanggal_perolehan',
        'tanggal_hibah',
        'catatan',
        'avatar_path',
    ];

    protected $casts = [
        'tanggal_perolehan' => 'date',
        'tanggal_hibah' => 'date',
        'tahun_pembuatan' => 'integer',
    ];

    public const STATUS_AKTIF = 'aktif';
    public const STATUS_NONAKTIF = 'nonaktif';
    public const STATUS_DIHIBAHKAN = 'dihibahkan';

    public const STATUS_OPTIONS = [
        self::STATUS_AKTIF => 'Aktif',
        self::STATUS_NONAKTIF => 'Non-Aktif',
        self::STATUS_DIHIBAHKAN => 'Dihibahkan',
    ];

    public const JENIS_MOBIL = 'mobil';
    public const JENIS_MOTOR = 'motor';

    public const JENIS_OPTIONS = [
        self::JENIS_MOBIL => 'Mobil',
        self::JENIS_MOTOR => 'Motor',
    ];

    /**
     * Get the merk of this kendaraan.
     */
    public function merk(): BelongsTo
    {
        return $this->belongsTo(Merk::class, 'merk_id');
    }

    /**
     * Get the garasi of this kendaraan.
     */
    public function garasi(): BelongsTo
    {
        return $this->belongsTo(Garasi::class, 'garasi_id');
    }

    /**
     * Get the pemegang of this kendaraan.
     */
    public function pemegang(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'pemegang_id');
    }

    /**
     * Get all images of this kendaraan.
     */
    public function gambar(): HasMany
    {
        return $this->hasMany(GambarKendaraan::class, 'kendaraan_id')->orderBy('urutan');
    }

    /**
     * Get all penugasan for this kendaraan.
     */
    public function penugasan(): HasMany
    {
        return $this->hasMany(Penugasan::class, 'kendaraan_id')->orderByDesc('tanggal_mulai');
    }

    /**
     * Get active penugasan for this kendaraan.
     */
    public function penugasanAktif(): HasMany
    {
        return $this->hasMany(Penugasan::class, 'kendaraan_id')->where('status', 'aktif');
    }

    /**
     * Get all pajak records for this kendaraan.
     */
    public function pajak(): HasMany
    {
        return $this->hasMany(Pajak::class, 'kendaraan_id')->orderByDesc('tanggal_jatuh_tempo');
    }

    /**
     * Get latest unpaid pajak.
     */
    public function pajakAktif(): HasMany
    {
        return $this->hasMany(Pajak::class, 'kendaraan_id')->where('status', 'belum_bayar');
    }

    /**
     * Get all servis records for this kendaraan.
     */
    public function servis(): HasMany
    {
        return $this->hasMany(Servis::class, 'kendaraan_id')->orderByDesc('tanggal_servis');
    }

    /**
     * Get the display name (merk + model).
     */
    public function getDisplayNameAttribute(): string
    {
        $merkName = $this->merk ? $this->merk->nama : '';
        return trim($merkName . ' ' . $this->nama_model);
    }

    /**
     * Get the full display name with year.
     */
    public function getFullDisplayNameAttribute(): string
    {
        return $this->display_name . ' (' . $this->tahun_pembuatan . ')';
    }

    /**
     * Check if kendaraan is aktif.
     */
    public function isAktif(): bool
    {
        return $this->status === self::STATUS_AKTIF;
    }

    /**
     * Check if kendaraan is dihibahkan.
     */
    public function isDihibahkan(): bool
    {
        return $this->status === self::STATUS_DIHIBAHKAN;
    }

    /**
     * Scope for active kendaraan only.
     */
    public function scopeAktif($query)
    {
        return $query->where('status', self::STATUS_AKTIF);
    }

    /**
     * Scope for mobil kendaraan only.
     */
    public function scopeMobil($query)
    {
        return $query->where('jenis', self::JENIS_MOBIL);
    }

    /**
     * Scope for motor kendaraan only.
     */
    public function scopeMotor($query)
    {
        return $query->where('jenis', self::JENIS_MOTOR);
    }

    /**
     * Get avatar URL or placeholder.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar_path) {
            return asset('storage/' . $this->avatar_path);
        }

        // Return placeholder based on jenis
        return $this->jenis === self::JENIS_MOTOR
            ? asset('images/placeholder-motor.png')
            : asset('images/placeholder-mobil.png');
    }
}
