<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kevikepan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'kevikepan';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'nama',
        'kode',
        'deskripsi',
    ];

    /**
     * Get garasi in this kevikepan
     */
    public function garasi()
    {
        return $this->hasMany(Garasi::class, 'kevikepan_id');
    }

    /**
     * Get paroki in this kevikepan
     */
    public function paroki()
    {
        return $this->hasMany(Paroki::class, 'kevikepan_id');
    }
}
