<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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
            'password' => 'hashed',
        ];
    }

    /**
     * Get permissions for this user
     */
    public function permissions()
    {
        return $this->hasMany(UserPermission::class);
    }

    public function hasPermission($key)
    {
        // Jika role admin, abaikan tabel permissions, berikan akses TRUE (1)
        if ($this->role === 'admin') {
            return true;
        }

        return $this->permissions()->where('permission_key', $key)->exists();
    }

    /**
     * Get all permission keys for this user
     */
    public function getPermissionKeys()
    {
        if ($this->role === 'admin') {
            return []; // Admin has all permissions, return empty array
        }

        return $this->permissions()->pluck('permission_key')->toArray();
    }

    public function staff()
    {
        return $this->hasOne(Staff::class, 'user_id');
    }
}
