<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';

    public const ROLE_CUSTOMER = 'customer';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'company_name',
        'identity_number',
        'registration_ip',
        'email_verified_at',
        'last_login_at',
        'last_login_ip',
        'role',
        'is_active',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_active && $this->role === self::ROLE_ADMIN;
    }

    public function isCustomer(): bool
    {
        return $this->is_active && $this->role === self::ROLE_CUSTOMER;
    }

    public function hasPermission(string $permissionKey): bool
    {
        if (! $this->is_active || $this->role !== self::ROLE_ADMIN) {
            return false;
        }

        if ($this->isFirstAdmin()) {
            return true;
        }

        return $this->permissions()
            ->where('key', $permissionKey)
            ->exists()
            || $this->roles()
                ->whereHas('permissions', fn ($query) => $query->where('key', $permissionKey))
                ->exists();
    }

    public function isFirstAdmin(): bool
    {
        $firstAdminId = self::query()
            ->where('role', self::ROLE_ADMIN)
            ->orderBy('id')
            ->value('id');

        return $firstAdminId !== null && $this->id === $firstAdminId;
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')
            ->withTimestamps();
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')
            ->withTimestamps();
    }

    public function customerServices(): HasMany
    {
        return $this->hasMany(CustomerService::class);
    }

    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function customerReviews(): HasMany
    {
        return $this->hasMany(CustomerReview::class);
    }

    public function customerNotifications(): HasMany
    {
        return $this->hasMany(CustomerNotification::class);
    }

    public function customerAddresses(): HasMany
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function customerActivityLogs(): HasMany
    {
        return $this->hasMany(CustomerActivityLog::class);
    }

    public function adminActivityLogs(): HasMany
    {
        return $this->hasMany(AdminActivityLog::class);
    }

    public function customerFiles(): HasMany
    {
        return $this->hasMany(CustomerFile::class);
    }
}
