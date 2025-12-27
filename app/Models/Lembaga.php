<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lembaga extends Model
{
    use HasFactory, Auditable;

    protected $table = 'lembaga';

    protected $fillable = [
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
     * Get kendaraan yang dimiliki lembaga ini.
     */
    public function kendaraanDimiliki(): HasMany
    {
        return $this->hasMany(Kendaraan::class, 'pemilik_lembaga_id');
    }

    /**
     * Get kendaraan tarikan dari lembaga ini.
     */
    public function kendaraanTarikan(): HasMany
    {
        return $this->hasMany(Kendaraan::class, 'tarikan_lembaga_id');
    }

    /**
     * Get riwayat pemakai for this lembaga.
     */
    public function riwayatPemakai(): HasMany
    {
        return $this->hasMany(RiwayatPemakai::class, 'lembaga_id');
    }

    /**
     * Scope for active lembaga only.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
