<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Pajak extends Model
{
    use HasFactory;

    protected $table = 'pajak';

    protected $fillable = [
        'kendaraan_id',
        'jenis',
        'tanggal_jatuh_tempo',
        'tanggal_bayar',
        'nominal',
        'denda',
        'status',
        'nomor_notice',
        'bukti_path',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_bayar' => 'date',
        'nominal' => 'decimal:2',
        'denda' => 'decimal:2',
    ];

    public const JENIS_TAHUNAN = 'tahunan';
    public const JENIS_LIMA_TAHUNAN = 'lima_tahunan';

    public const JENIS_OPTIONS = [
        self::JENIS_TAHUNAN => 'Pajak Tahunan',
        self::JENIS_LIMA_TAHUNAN => 'Pajak 5 Tahunan (Ganti Plat)',
    ];

    public const STATUS_BELUM_BAYAR = 'belum_bayar';
    public const STATUS_LUNAS = 'lunas';
    public const STATUS_TERLAMBAT = 'terlambat';

    public const STATUS_OPTIONS = [
        self::STATUS_BELUM_BAYAR => 'Belum Bayar',
        self::STATUS_LUNAS => 'Lunas',
        self::STATUS_TERLAMBAT => 'Terlambat',
    ];

    /**
     * Get the kendaraan for this pajak.
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
     * Check if pajak is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->status !== self::STATUS_LUNAS && $this->tanggal_jatuh_tempo->isPast();
    }

    /**
     * Check if pajak is due soon (within 30 days).
     */
    public function isDueSoon(): bool
    {
        if ($this->status === self::STATUS_LUNAS) {
            return false;
        }
        $daysUntilDue = now()->diffInDays($this->tanggal_jatuh_tempo, false);
        return $daysUntilDue >= 0 && $daysUntilDue <= 30;
    }

    /**
     * Get days until due date.
     */
    public function getDaysUntilDueAttribute(): int
    {
        return now()->startOfDay()->diffInDays($this->tanggal_jatuh_tempo->startOfDay(), false);
    }

    /**
     * Get total amount (nominal + denda).
     */
    public function getTotalAttribute(): float
    {
        return ($this->nominal ?? 0) + ($this->denda ?? 0);
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
     * Scope for unpaid pajak.
     */
    public function scopeBelumBayar($query)
    {
        return $query->where('status', self::STATUS_BELUM_BAYAR);
    }

    /**
     * Scope for lunas pajak.
     */
    public function scopeLunas($query)
    {
        return $query->where('status', self::STATUS_LUNAS);
    }

    /**
     * Scope for overdue pajak.
     */
    public function scopeTerlambat($query)
    {
        return $query->where('status', self::STATUS_TERLAMBAT);
    }

    /**
     * Scope for pajak due within days.
     */
    public function scopeDueWithinDays($query, int $days = 30)
    {
        return $query->where('status', self::STATUS_BELUM_BAYAR)
            ->whereBetween('tanggal_jatuh_tempo', [now(), now()->addDays($days)]);
    }

    /**
     * Delete bukti file when model is deleted.
     */
    protected static function booted(): void
    {
        static::deleting(function (Pajak $pajak) {
            if ($pajak->bukti_path && Storage::disk('public')->exists($pajak->bukti_path)) {
                Storage::disk('public')->delete($pajak->bukti_path);
            }
        });
    }
}
