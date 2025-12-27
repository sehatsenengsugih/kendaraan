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

    protected $fillable = [
        'kevikepan_id',
        'nama',
        'alamat',
        'kota',
        'telepon',
        'email',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the kevikepan this paroki belongs to.
     */
    public function kevikepan(): BelongsTo
    {
        return $this->belongsTo(Kevikepan::class, 'kevikepan_id');
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
     * Get display name with kevikepan.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->nama . ' (' . ($this->kevikepan->nama ?? '-') . ')';
    }
}
