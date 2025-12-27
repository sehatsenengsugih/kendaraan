<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Servis extends Model
{
    use HasFactory;

    protected $table = 'servis';

    protected $fillable = [
        'kendaraan_id',
        'jenis',
        'tanggal_servis',
        'tanggal_selesai',
        'kilometer',
        'bengkel',
        'deskripsi',
        'spare_parts',
        'biaya',
        'status',
        'bukti_path',
        'servis_berikutnya',
        'km_berikutnya',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'tanggal_servis' => 'date',
        'tanggal_selesai' => 'date',
        'servis_berikutnya' => 'date',
        'biaya' => 'decimal:2',
        'kilometer' => 'integer',
        'km_berikutnya' => 'integer',
    ];

    public const JENIS_RUTIN = 'rutin';
    public const JENIS_PERBAIKAN = 'perbaikan';
    public const JENIS_DARURAT = 'darurat';
    public const JENIS_OVERHAUL = 'overhaul';

    public const JENIS_OPTIONS = [
        self::JENIS_RUTIN => 'Servis Rutin',
        self::JENIS_PERBAIKAN => 'Perbaikan',
        self::JENIS_DARURAT => 'Perbaikan Darurat',
        self::JENIS_OVERHAUL => 'Overhaul',
    ];

    public const STATUS_DIJADWALKAN = 'dijadwalkan';
    public const STATUS_DALAM_PROSES = 'dalam_proses';
    public const STATUS_SELESAI = 'selesai';
    public const STATUS_DIBATALKAN = 'dibatalkan';

    public const STATUS_OPTIONS = [
        self::STATUS_DIJADWALKAN => 'Dijadwalkan',
        self::STATUS_DALAM_PROSES => 'Dalam Proses',
        self::STATUS_SELESAI => 'Selesai',
        self::STATUS_DIBATALKAN => 'Dibatalkan',
    ];

    /**
     * Get the kendaraan for this servis.
     */
    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }

    /**
     * Get the user who created this record.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'created_by');
    }

    /**
     * Check if next service is due soon (within 30 days).
     */
    public function isNextServiceDueSoon(): bool
    {
        if (!$this->servis_berikutnya || $this->status !== self::STATUS_SELESAI) {
            return false;
        }
        $daysUntilDue = now()->diffInDays($this->servis_berikutnya, false);
        return $daysUntilDue >= 0 && $daysUntilDue <= 30;
    }

    /**
     * Get days until next service.
     */
    public function getDaysUntilNextServiceAttribute(): ?int
    {
        if (!$this->servis_berikutnya) {
            return null;
        }
        return now()->startOfDay()->diffInDays($this->servis_berikutnya->startOfDay(), false);
    }

    /**
     * Get bukti URL.
     */
    public function getBuktiUrlAttribute(): ?string
    {
        if ($this->bukti_path) {
            return asset('storage/' . $this->bukti_path);
        }
        return null;
    }

    /**
     * Get formatted biaya.
     */
    public function getBiayaFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->biaya, 0, ',', '.');
    }

    /**
     * Check if servis is completed.
     */
    public function isSelesai(): bool
    {
        return $this->status === self::STATUS_SELESAI;
    }

    /**
     * Scope for completed servis.
     */
    public function scopeSelesai($query)
    {
        return $query->where('status', self::STATUS_SELESAI);
    }

    /**
     * Scope for scheduled servis.
     */
    public function scopeDijadwalkan($query)
    {
        return $query->where('status', self::STATUS_DIJADWALKAN);
    }

    /**
     * Scope for in progress servis.
     */
    public function scopeDalamProses($query)
    {
        return $query->where('status', self::STATUS_DALAM_PROSES);
    }

    /**
     * Scope for servis with upcoming next service date.
     */
    public function scopeNextServiceDueSoon($query, int $days = 30)
    {
        return $query->where('status', self::STATUS_SELESAI)
            ->whereNotNull('servis_berikutnya')
            ->whereBetween('servis_berikutnya', [now(), now()->addDays($days)]);
    }

    /**
     * Delete bukti file when model is deleted.
     */
    protected static function booted(): void
    {
        static::deleting(function (Servis $servis) {
            if ($servis->bukti_path && Storage::disk('public')->exists($servis->bukti_path)) {
                Storage::disk('public')->delete($servis->bukti_path);
            }
        });
    }
}
