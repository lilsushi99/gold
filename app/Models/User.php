<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['role_id', 'name', 'email', 'password', 'must_change_password', 'is_active', 'last_login_at'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'must_change_password' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role?->slug === 'super-admin';
    }

    /** Super administrators pass every check; others need the permission on their role. */
    public function hasPermission(string $slug): bool
    {
        if (! $this->is_active) {
            return false;
        }
        if ($this->isSuperAdmin()) {
            return true;
        }
        return $this->permissionSlugs()->contains($slug);
    }

    public function permissionSlugs()
    {
        return once(fn () => $this->role ? $this->role->permissions()->pluck('slug') : collect());
    }
}
