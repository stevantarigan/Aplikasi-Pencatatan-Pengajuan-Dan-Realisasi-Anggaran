<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'username',
        'password',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        
    ];

    /**
     * Get the user's role in Indonesian.
     */
    public function getRoleNameAttribute()
    {
        if ($this->role == 'admin') {
            return 'Admin';
        } elseif ($this->role == 'keuangan') {
            return 'Keuangan';
        } elseif ($this->role == 'kepala_gizi') {
            return 'Kepala Gizi';
        } else {
            return ucfirst($this->role);
        }
    }

    /**
     * Get role icon
     */
    public function getRoleIconAttribute()
    {
        if ($this->role == 'admin') {
            return 'shield-lock';
        } elseif ($this->role == 'keuangan') {
            return 'calculator';
        } elseif ($this->role == 'kepala_gizi') {
            return 'person-workspace';
        } else {
            return 'person';
        }
    }

    /**
     * Get role color class
     */
    public function getRoleColorAttribute()
    {
        if ($this->role == 'admin') {
            return 'admin';
        } elseif ($this->role == 'keuangan') {
            return 'keuangan';
        } elseif ($this->role == 'kepala_gizi') {
            return 'kepala_gizi';
        } else {
            return 'default';
        }
    }

    /**
     * Get the user's status in Indonesian.
     */
    public function getStatusNameAttribute()
    {
        if ($this->status == 'active') {
            return 'Aktif';
        } elseif ($this->status == 'inactive') {
            return 'Tidak Aktif';
        } else {
            return ucfirst($this->status);
        }
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is keuangan.
     */
    public function isKeuangan(): bool
    {
        return $this->role === 'keuangan';
    }

    /**
     * Check if user is kepala gizi.
     */
    public function isKepalaGizi(): bool
    {
        return $this->role === 'kepala_gizi';
    }

    /**
     * Check if user is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for inactive users
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope for specific role
     */
    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }
}