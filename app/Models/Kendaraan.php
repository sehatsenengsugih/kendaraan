<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kendaraan extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    protected $table = 'kendaraan';

    protected $fillable = [
        'plat_nomor',
        'nomor_bpkb',
        'status_bpkb_id',
        'nomor_rangka',
        'nomor_mesin',
        'merk_id',
        'nama_model',
        'tahun_pembuatan',
        'warna',
        'jenis',
        'garasi_id',
        'pemegang_id',
        'status',
        'status_kepemilikan',
        'nama_pemilik_lembaga',
        'pemilik_lembaga_id',
        'tanggal_perolehan',
        'tanggal_beli',
        'harga_beli',
        'tanggal_hibah',
        'nama_penerima_hibah',
        'tanggal_jual',
        'harga_jual',
        'nama_pembeli',
        'is_dipinjam',
        'dipinjam_oleh',
        'dipinjam_paroki_id',
        'tanggal_pinjam',
        'is_tarikan',
        'tarikan_dari',
        'tarikan_paroki_id',
        'tarikan_lembaga_id',
        'tarikan_pemakai',
        'tarikan_kondisi',
        'catatan',
        'avatar_path',
    ];

    protected $casts = [
        'tanggal_perolehan' => 'date',
        'tanggal_beli' => 'date',
        'tanggal_hibah' => 'date',
        'tanggal_jual' => 'date',
        'tanggal_pinjam' => 'date',
        'tahun_pembuatan' => 'integer',
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2',
        'is_dipinjam' => 'boolean',
        'is_tarikan' => 'boolean',
    ];

    public const STATUS_AKTIF = 'aktif';
    public const STATUS_NONAKTIF = 'nonaktif';
    public const STATUS_DIHIBAHKAN = 'dihibahkan';
    public const STATUS_DIJUAL = 'dijual';

    public const STATUS_OPTIONS = [
        self::STATUS_AKTIF => 'Aktif',
        self::STATUS_NONAKTIF => 'Non-Aktif',
        self::STATUS_DIHIBAHKAN => 'Dihibahkan',
        self::STATUS_DIJUAL => 'Dijual',
    ];

    public const JENIS_MOBIL = 'mobil';
    public const JENIS_MOTOR = 'motor';

    public const JENIS_OPTIONS = [
        self::JENIS_MOBIL => 'Mobil',
        self::JENIS_MOTOR => 'Motor',
    ];

    public const KEPEMILIKAN_KAS = 'milik_kas';
    public const KEPEMILIKAN_LEMBAGA_LAIN = 'milik_lembaga_lain';

    public const KEPEMILIKAN_OPTIONS = [
        self::KEPEMILIKAN_KAS => 'Milik KAS',
        self::KEPEMILIKAN_LEMBAGA_LAIN => 'Milik Lembaga Lain',
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
     * Get the lembaga pemilik of this kendaraan.
     */
    public function pemilikLembaga(): BelongsTo
    {
        return $this->belongsTo(Lembaga::class, 'pemilik_lembaga_id');
    }

    /**
     * Get the paroki yang meminjam kendaraan ini.
     */
    public function dipinjamParoki(): BelongsTo
    {
        return $this->belongsTo(Paroki::class, 'dipinjam_paroki_id');
    }

    /**
     * Get the paroki asal tarikan.
     */
    public function tarikanParoki(): BelongsTo
    {
        return $this->belongsTo(Paroki::class, 'tarikan_paroki_id');
    }

    /**
     * Get the lembaga asal tarikan.
     */
    public function tarikanLembaga(): BelongsTo
    {
        return $this->belongsTo(Lembaga::class, 'tarikan_lembaga_id');
    }

    /**
     * Get the status BPKB of this kendaraan.
     */
    public function statusBpkb(): BelongsTo
    {
        return $this->belongsTo(StatusBpkb::class, 'status_bpkb_id');
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
     * Get all riwayat pemakai for this kendaraan.
     */
    public function riwayatPemakai(): HasMany
    {
        return $this->hasMany(RiwayatPemakai::class, 'kendaraan_id')->orderByDesc('tanggal_mulai');
    }

    /**
     * Get active riwayat pemakai (tanggal_selesai is null).
     */
    public function riwayatPemakaiAktif(): HasMany
    {
        return $this->hasMany(RiwayatPemakai::class, 'kendaraan_id')->whereNull('tanggal_selesai');
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
     * Check if kendaraan is dijual.
     */
    public function isDijual(): bool
    {
        return $this->status === self::STATUS_DIJUAL;
    }

    /**
     * Check if kendaraan is milik KAS.
     */
    public function isMilikKas(): bool
    {
        return $this->status_kepemilikan === self::KEPEMILIKAN_KAS;
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
