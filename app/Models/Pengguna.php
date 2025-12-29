<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'accent_color',
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
     * Default accent color (green)
     */
    public const DEFAULT_ACCENT_COLOR = '#22C55E';

    /**
     * Preset accent colors with their palettes
     */
    public const ACCENT_PRESETS = [
        '#22C55E' => ['name' => 'Hijau', '50' => '#D9FBE6', '100' => '#B7FFD1', '200' => '#4ADE80', '300' => '#22C55E', '400' => '#16A34A'],
        '#3B82F6' => ['name' => 'Biru', '50' => '#DBEAFE', '100' => '#BFDBFE', '200' => '#60A5FA', '300' => '#3B82F6', '400' => '#2563EB'],
        '#8B5CF6' => ['name' => 'Ungu', '50' => '#EDE9FE', '100' => '#DDD6FE', '200' => '#A78BFA', '300' => '#8B5CF6', '400' => '#7C3AED'],
        '#EF4444' => ['name' => 'Merah', '50' => '#FEE2E2', '100' => '#FECACA', '200' => '#F87171', '300' => '#EF4444', '400' => '#DC2626'],
        '#F97316' => ['name' => 'Orange', '50' => '#FFEDD5', '100' => '#FED7AA', '200' => '#FB923C', '300' => '#F97316', '400' => '#EA580C'],
        '#EC4899' => ['name' => 'Pink', '50' => '#FCE7F3', '100' => '#FBCFE8', '200' => '#F472B6', '300' => '#EC4899', '400' => '#DB2777'],
    ];

    /**
     * Get the user's accent color or default
     */
    public function getAccentColor(): string
    {
        return $this->accent_color ?? self::DEFAULT_ACCENT_COLOR;
    }

    /**
     * Get the accent color palette for this user
     */
    public function getAccentPalette(): array
    {
        $color = $this->getAccentColor();

        // Check if it's a preset color
        if (isset(self::ACCENT_PRESETS[$color])) {
            return self::ACCENT_PRESETS[$color];
        }

        // For custom colors, generate a basic palette
        return $this->generatePalette($color);
    }

    /**
     * Generate a color palette from a single hex color
     */
    private function generatePalette(string $hex): array
    {
        // Convert hex to RGB
        $hex = ltrim($hex, '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        // Generate lighter and darker variants
        return [
            'name' => 'Custom',
            '50' => $this->adjustBrightness($r, $g, $b, 0.9),
            '100' => $this->adjustBrightness($r, $g, $b, 0.8),
            '200' => $this->adjustBrightness($r, $g, $b, 0.4),
            '300' => '#' . $hex,
            '400' => $this->adjustBrightness($r, $g, $b, -0.15),
        ];
    }

    /**
     * Adjust color brightness
     */
    private function adjustBrightness(int $r, int $g, int $b, float $percent): string
    {
        if ($percent > 0) {
            // Lighten - mix with white
            $r = $r + (255 - $r) * $percent;
            $g = $g + (255 - $g) * $percent;
            $b = $b + (255 - $b) * $percent;
        } else {
            // Darken - reduce values
            $factor = 1 + $percent;
            $r = $r * $factor;
            $g = $g * $factor;
            $b = $b * $factor;
        }

        return sprintf('#%02X%02X%02X', min(255, max(0, (int)$r)), min(255, max(0, (int)$g)), min(255, max(0, (int)$b)));
    }

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

        // Fallback to ui-avatars.com with user's accent color
        $bgColor = ltrim($this->getAccentColor(), '#');
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=' . $bgColor . '&color=fff';
    }

    /**
     * Get riwayat pemakai yang terkait dengan user ini.
     */
    public function riwayatPemakai(): HasMany
    {
        return $this->hasMany(RiwayatPemakai::class, 'pengguna_id');
    }

    /**
     * Get riwayat pemakai aktif (kendaraan yang sedang dipegang user ini).
     */
    public function riwayatPemakaiAktif(): HasMany
    {
        return $this->hasMany(RiwayatPemakai::class, 'pengguna_id')->whereNull('tanggal_selesai');
    }

    /**
     * Get kendaraan yang sedang dipegang/digunakan user ini.
     */
    public function kendaraanDipegang()
    {
        return Kendaraan::whereHas('riwayatPemakaiAktif', function ($query) {
            $query->where('pengguna_id', $this->id);
        });
    }

    /**
     * Check if user is currently holding a specific kendaraan.
     */
    public function isHoldingKendaraan(int $kendaraanId): bool
    {
        return $this->riwayatPemakaiAktif()->where('kendaraan_id', $kendaraanId)->exists();
    }
}
