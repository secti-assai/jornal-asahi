<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function news()
    {
        return $this->hasMany(News::class, 'author_id');
    }

    public function approvedNews()
    {
        return $this->hasMany(News::class, 'approved_by');
    }

    public function isReporter()
    {
        return $this->role->level === 1;
    }

    public function isApprover()
    {
        return $this->role->level === 2;
    }

    public function isAdmin()
    {
        return $this->role->level === 3;
    }
}