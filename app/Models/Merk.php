<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Merk extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    protected $table = 'merk';

    protected $fillable = [
        'nama',
        'jenis',
    ];

    public const JENIS_MOBIL = 'mobil';
    public const JENIS_MOTOR = 'motor';

    public const JENIS_OPTIONS = [
        self::JENIS_MOBIL => 'Mobil',
        self::JENIS_MOTOR => 'Motor',
    ];

    /**
     * Get all kendaraan with this merk.
     */
    public function kendaraan(): HasMany
    {
        return $this->hasMany(Kendaraan::class, 'merk_id');
    }

    /**
     * Get the display name with jenis.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->nama . ' (' . ucfirst($this->jenis) . ')';
    }

    /**
     * Check if this is a mobil merk.
     */
    public function isMobil(): bool
    {
        return $this->jenis === self::JENIS_MOBIL;
    }

    /**
     * Check if this is a motor merk.
     */
    public function isMotor(): bool
    {
        return $this->jenis === self::JENIS_MOTOR;
    }

    /**
     * Scope for mobil merk only.
     */
    public function scopeMobil($query)
    {
        return $query->where('jenis', self::JENIS_MOBIL);
    }

    /**
     * Scope for motor merk only.
     */
    public function scopeMotor($query)
    {
        return $query->where('jenis', self::JENIS_MOTOR);
    }
}
