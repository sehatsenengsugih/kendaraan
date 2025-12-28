<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Pengguna extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'pengguna';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'avatar_path',
        'email',
        'phone',
        'role',
        'user_type',
        'organization_name',
        'status',
        'password',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Role constants
     */
    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_USER = 'user';

    /**
     * User type constants
     */
    public const TYPE_PRIBADI = 'pribadi';
    public const TYPE_PAROKI = 'paroki';
    public const TYPE_LEMBAGA = 'lembaga';

    /**
     * Status constants
     */
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if user is regular user
     */
    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Check if user can manage users
     */
    public function canManageUsers(): bool
    {
        return $this->isSuperAdmin() || $this->isAdmin();
    }

    /**
     * Check if user can manage admins (only super admin)
     */
    public function canManageAdmins(): bool
    {
        return $this->isSuperAdmin();
    }

    /**
     * Check if user can view audit logs (only super admin)
     */
    public function canViewAuditLogs(): bool
    {
        return $this->isSuperAdmin();
    }

    /**
     * Get available roles
     */
    public static function getRoles(): array
    {
        return [
            self::ROLE_SUPER_ADMIN => 'Super Admin',
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_USER => 'User',
        ];
    }

    /**
     * Get available user types
     */
    public static function getUserTypes(): array
    {
        return [
            self::TYPE_PRIBADI => 'Pribadi',
            self::TYPE_PAROKI => 'Paroki',
            self::TYPE_LEMBAGA => 'Lembaga',
        ];
    }

    /**
     * Get role badge class
     */
    public function getRoleBadgeClass(): string
    {
        return match ($this->role) {
            self::ROLE_SUPER_ADMIN => 'badge-dark',
            self::ROLE_ADMIN => 'badge-success',
            self::ROLE_USER => 'badge-secondary',
            default => 'badge-secondary',
        };
    }

    /**
     * Get role label
     */
    public function getRoleLabel(): string
    {
        return self::getRoles()[$this->role] ?? $this->role;
    }

    /**
     * Get user type label
     */
    public function getUserTypeLabel(): ?string
    {
        if (!$this->user_type) {
            return null;
        }
        return self::getUserTypes()[$this->user_type] ?? $this->user_type;
    }

    /**
     * Get display name (with organization if applicable)
     */
    public function getDisplayName(): string
    {
        if ($this->organization_name) {
            return $this->name . ' (' . $this->organization_name . ')';
        }
        return $this->name;
    }

    /**
     * Get avatar URL with fallback to ui-avatars.com
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar_path) {
            return asset('storage/' . $this->avatar_path);
        }

        // Fallback to ui-avatars.com
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=22C55E&color=fff';
    }
}
