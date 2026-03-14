<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enum\UserRole;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'user_id';
    
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'google_id',
        'avatar',
        'role',
        'status', // Add status to fillable
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'role' => UserRole::class,
    ];

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if user is archived
     */
    public function isArchived(): bool
    {
        return $this->status === 'archived';
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles): bool
    {
        foreach ($roles as $role) {
            if ($this->role === $role) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(UserRole $role): bool
    {
        return $this->role === $role;
    }
}