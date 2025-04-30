<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInteraction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'visitor_id',
        'visitor_name',
        'comment',
        'like'
    ];

    /**
     * Obter o usuário que recebeu a interação
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Obter o usuário que fez a interação (se autenticado)
     */
    public function visitor()
    {
        return $this->belongsTo(User::class, 'visitor_id');
    }
}