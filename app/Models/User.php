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
        'username',
        'email',
        'description',
        'education_level',
        'relation', // Novo campo adicionado
        'password',
        'role_id',
        'profile_image'
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

    /**
     * Verifica se o usuário é um administrador
     *
     * @return bool
     */
    public function isAdmin()
    {
        return (int)$this->role_id === 3; // Certifique-se que 3 é o ID correto para administradores
    }

    /**
     * Verifica se o usuário é um aprovador
     *
     * @return bool
     */
    public function isApprover()
    {
        return $this->role_id === 2 || $this->isAdmin(); // ID 2 é aprovador
    }

    /**
     * Verifica se o usuário é um repórter
     *
     * @return bool
     */
    public function isReporter()
    {
        return $this->role_id === 1 || $this->isApprover(); // ID 1 é reporter
    }

    /**
     * Obter todas as interações que este usuário recebeu
     */
    public function receivedInteractions()
    {
        return $this->hasMany(UserInteraction::class, 'user_id');
    }

    /**
     * Obter todas as interações que este usuário realizou
     */
    public function madeInteractions()
    {
        return $this->hasMany(UserInteraction::class, 'visitor_id');
    }
}