<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penugasan extends Model
{
    use HasFactory;

    protected $table = 'penugasan';

    protected $fillable = [
        'kendaraan_id',
        'pemegang_id',
        'assigned_by',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'tujuan',
        'catatan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public const STATUS_AKTIF = 'aktif';
    public const STATUS_SELESAI = 'selesai';
    public const STATUS_DIBATALKAN = 'dibatalkan';

    public const STATUS_OPTIONS = [
        self::STATUS_AKTIF => 'Aktif',
        self::STATUS_SELESAI => 'Selesai',
        self::STATUS_DIBATALKAN => 'Dibatalkan',
    ];

    /**
     * Get the kendaraan for this penugasan.
     */
    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }

    /**
     * Get the pemegang for this penugasan.
     */
    public function pemegang(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'pemegang_id');
    }

    /**
     * Get the admin who assigned this penugasan.
     */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'assigned_by');
    }

    /**
     * Check if penugasan is active.
     */
    public function isAktif(): bool
    {
        return $this->status === self::STATUS_AKTIF;
    }

    /**
     * Check if penugasan is completed.
     */
    public function isSelesai(): bool
    {
        return $this->status === self::STATUS_SELESAI;
    }

    /**
     * Get duration in days.
     */
    public function getDurasiAttribute(): ?int
    {
        if (!$this->tanggal_selesai) {
            return $this->tanggal_mulai->diffInDays(now());
        }
        return $this->tanggal_mulai->diffInDays($this->tanggal_selesai);
    }

    /**
     * Scope for active penugasan.
     */
    public function scopeAktif($query)
    {
        return $query->where('status', self::STATUS_AKTIF);
    }

    /**
     * Scope for completed penugasan.
     */
    public function scopeSelesai($query)
    {
        return $query->where('status', self::STATUS_SELESAI);
    }
}
