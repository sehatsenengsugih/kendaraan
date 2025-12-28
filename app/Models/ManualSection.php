<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ManualSection extends Model
{
    use HasFactory;

    protected $table = 'manual_sections';

    protected $fillable = [
        'slug',
        'title',
        'icon',
        'content',
        'order',
        'is_active',
        'parent_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get the parent section.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(ManualSection::class, 'parent_id');
    }

    /**
     * Get the child sections.
     */
    public function children(): HasMany
    {
        return $this->hasMany(ManualSection::class, 'parent_id')->orderBy('order');
    }

    /**
     * Scope to get only active sections.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only root sections (no parent).
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope to order by position.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Get all sections in tree structure.
     */
    public static function getTree()
    {
        return static::active()
            ->root()
            ->ordered()
            ->with(['children' => function ($query) {
                $query->active()->ordered();
            }])
            ->get();
    }
}
