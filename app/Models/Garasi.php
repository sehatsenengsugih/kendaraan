<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Garasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'garasi';

    protected $fillable = [
        'nama',
        'alamat',
        'kota',
        'kode_pos',
        'kevikepan_id',
        'pic_name',
        'pic_phone',
    ];

    /**
     * Get the kevikepan that owns this garasi.
     */
    public function kevikepan(): BelongsTo
    {
        return $this->belongsTo(Kevikepan::class, 'kevikepan_id');
    }

    /**
     * Get all kendaraan in this garasi.
     */
    public function kendaraan(): HasMany
    {
        return $this->hasMany(Kendaraan::class, 'garasi_id');
    }

    /**
     * Get the full address including city and postal code.
     */
    public function getFullAddressAttribute(): string
    {
        $parts = [$this->alamat, $this->kota];
        if ($this->kode_pos) {
            $parts[] = $this->kode_pos;
        }
        return implode(', ', $parts);
    }

    /**
     * Get the count of active vehicles in this garage.
     */
    public function getActiveKendaraanCountAttribute(): int
    {
        return $this->kendaraan()->where('status', 'aktif')->count();
    }
}
