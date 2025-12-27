<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StatusBpkb extends Model
{
    protected $table = 'status_bpkb';

    protected $fillable = [
        'nama',
        'keterangan',
        'urutan',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // ==================
    // RELATIONSHIPS
    // ==================

    public function kendaraan(): HasMany
    {
        return $this->hasMany(Kendaraan::class, 'status_bpkb_id');
    }

    // ==================
    // SCOPES
    // ==================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan');
    }
}
