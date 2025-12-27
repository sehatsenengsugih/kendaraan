<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'action',
        'model_type',
        'model_id',
        'model_label',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
        ];
    }

    // ==================
    // RELATIONSHIPS
    // ==================

    public function user(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'user_id');
    }

    // ==================
    // SCOPES
    // ==================

    public function scopeForModel($query, string $modelType, int $modelId)
    {
        return $query->where('model_type', $modelType)->where('model_id', $modelId);
    }

    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // ==================
    // HELPERS
    // ==================

    /**
     * Get a short description of the model type
     */
    public function getModelTypeShortAttribute(): string
    {
        if (!$this->model_type) {
            return '-';
        }

        return class_basename($this->model_type);
    }

    /**
     * Get action badge color
     */
    public function getActionColorAttribute(): string
    {
        return match ($this->action) {
            'create' => 'success',
            'update' => 'warning',
            'delete' => 'error',
            'login' => 'info',
            'logout' => 'info',
            'bayar' => 'success',
            'selesai' => 'success',
            default => 'bgray',
        };
    }

    /**
     * Get action label in Indonesian
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'create' => 'Tambah',
            'update' => 'Ubah',
            'delete' => 'Hapus',
            'login' => 'Login',
            'logout' => 'Logout',
            'bayar' => 'Bayar',
            'selesai' => 'Selesai',
            default => ucfirst($this->action),
        };
    }

    /**
     * Log an activity
     */
    public static function log(
        string $action,
        ?Model $model = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $description = null
    ): self {
        $user = auth()->user();
        $request = request();

        $data = [
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'System',
            'action' => $action,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'description' => $description,
        ];

        if ($model) {
            $data['model_type'] = get_class($model);
            $data['model_id'] = $model->getKey();
            $data['model_label'] = self::getModelLabel($model);
            $data['old_values'] = $oldValues;
            $data['new_values'] = $newValues;
        }

        return self::create($data);
    }

    /**
     * Get a human-readable label for a model
     */
    protected static function getModelLabel(Model $model): string
    {
        // Try common label fields
        if (isset($model->no_plat)) {
            $label = $model->no_plat;
            if (isset($model->kendaraan) && $model->kendaraan->merk) {
                $label = $model->kendaraan->merk->nama . ' - ' . $label;
            } elseif (isset($model->merk)) {
                $label = $model->merk->nama . ' - ' . $label;
            }
            return $label;
        }

        if (isset($model->nama)) {
            return $model->nama;
        }

        if (isset($model->name)) {
            return $model->name;
        }

        if (isset($model->email)) {
            return $model->email;
        }

        return class_basename($model) . ' #' . $model->getKey();
    }
}
