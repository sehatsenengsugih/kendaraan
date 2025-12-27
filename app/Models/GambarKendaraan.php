<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class GambarKendaraan extends Model
{
    use HasFactory;

    protected $table = 'gambar_kendaraan';

    protected $fillable = [
        'kendaraan_id',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'caption',
        'urutan',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'urutan' => 'integer',
    ];

    /**
     * Get the kendaraan that owns this image.
     */
    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }

    /**
     * Get the URL for this image.
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    /**
     * Get the thumbnail URL (same as URL for now, can be modified for thumbnails later).
     */
    public function getThumbnailUrlAttribute(): string
    {
        return $this->url;
    }

    /**
     * Get human-readable file size.
     */
    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Delete the file from storage when the model is deleted.
     */
    protected static function booted(): void
    {
        static::deleting(function (GambarKendaraan $gambar) {
            if ($gambar->file_path && Storage::disk('public')->exists($gambar->file_path)) {
                Storage::disk('public')->delete($gambar->file_path);
            }
        });
    }
}
