<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatPemakai extends Model
{
    use HasFactory, Auditable;

    protected $table = 'riwayat_pemakai';

    protected $fillable = [
        'kendaraan_id',
        'nama_pemakai',
        'paroki_id',
        'lembaga_id',
        'pengguna_id',
        'jenis_pemakai',
        'tanggal_mulai',
        'tanggal_selesai',
        'catatan',
        'dokumen_serah_terima_path',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public const JENIS_PAROKI = 'paroki';
    public const JENIS_LEMBAGA = 'lembaga';
    public const JENIS_PRIBADI = 'pribadi';

    public const JENIS_OPTIONS = [
        self::JENIS_PAROKI => 'Paroki',
        self::JENIS_LEMBAGA => 'Lembaga',
        self::JENIS_PRIBADI => 'Pribadi',
    ];

    /**
     * Get the kendaraan for this riwayat.
     */
    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }

    /**
     * Get the paroki for this riwayat.
     */
    public function paroki(): BelongsTo
    {
        return $this->belongsTo(Paroki::class, 'paroki_id');
    }

    /**
     * Get the lembaga for this riwayat.
     */
    public function lembaga(): BelongsTo
    {
        return $this->belongsTo(Lembaga::class, 'lembaga_id');
    }

    /**
     * Get the pengguna (user aplikasi) for this riwayat.
     */
    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    /**
     * Check if this riwayat is still active.
     */
    public function isAktif(): bool
    {
        return is_null($this->tanggal_selesai);
    }

    /**
     * Get duration in days.
     */
    public function getDurasiAttribute(): ?int
    {
        $end = $this->tanggal_selesai ?? now();
        return $this->tanggal_mulai->diffInDays($end);
    }

    /**
     * Scope for active riwayat only.
     */
    public function scopeAktif($query)
    {
        return $query->whereNull('tanggal_selesai');
    }

    /**
     * Scope for lembaga type.
     */
    public function scopeLembaga($query)
    {
        return $query->where('jenis_pemakai', self::JENIS_LEMBAGA);
    }

    /**
     * Scope for pribadi type.
     */
    public function scopePribadi($query)
    {
        return $query->where('jenis_pemakai', self::JENIS_PRIBADI);
    }
}
