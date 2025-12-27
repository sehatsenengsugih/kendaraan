<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\AuditLog;

trait Auditable
{
    /**
     * Boot the auditable trait for a model.
     */
    public static function bootAuditable(): void
    {
        // Log when model is created
        static::created(function ($model) {
            AuditLog::log(
                'create',
                $model,
                null,
                $model->getAttributes(),
                static::getAuditDescription('create', $model)
            );
        });

        // Log when model is updated
        static::updated(function ($model) {
            $oldValues = $model->getOriginal();
            $newValues = $model->getChanges();

            // Remove timestamps from changes
            unset($newValues['updated_at']);

            if (!empty($newValues)) {
                // Only include old values for changed fields
                $relevantOldValues = array_intersect_key($oldValues, $newValues);

                AuditLog::log(
                    'update',
                    $model,
                    $relevantOldValues,
                    $newValues,
                    static::getAuditDescription('update', $model)
                );
            }
        });

        // Log when model is deleted
        static::deleted(function ($model) {
            AuditLog::log(
                'delete',
                $model,
                $model->getAttributes(),
                null,
                static::getAuditDescription('delete', $model)
            );
        });
    }

    /**
     * Get a human-readable description for the audit log.
     * Override this in your model to customize the description.
     */
    protected static function getAuditDescription(string $action, $model): string
    {
        $modelName = static::getAuditModelName();
        $label = static::getAuditLabel($model);

        return match ($action) {
            'create' => "Menambah {$modelName}: {$label}",
            'update' => "Mengubah {$modelName}: {$label}",
            'delete' => "Menghapus {$modelName}: {$label}",
            default => ucfirst($action) . " {$modelName}: {$label}",
        };
    }

    /**
     * Get the model name for audit logs.
     * Override this in your model to customize.
     */
    protected static function getAuditModelName(): string
    {
        return class_basename(static::class);
    }

    /**
     * Get a label for this model instance.
     * Override this in your model to customize.
     */
    protected static function getAuditLabel($model): string
    {
        // Try common label fields
        if (isset($model->no_plat)) {
            return $model->no_plat;
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

        return '#' . $model->getKey();
    }

    /**
     * Log a custom action for this model.
     */
    public function logActivity(string $action, ?string $description = null): AuditLog
    {
        return AuditLog::log(
            $action,
            $this,
            null,
            null,
            $description ?? static::getAuditDescription($action, $this)
        );
    }
}
