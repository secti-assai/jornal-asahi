<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsImage extends Model
{
    use HasFactory;

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'news_id', 
        'path', 
        'is_cover',  // Certifique-se que está usando is_cover e não is_featured
        'caption'
    ];

    /**
     * Obter a notícia associada à imagem.
     */
    public function news()
    {
        return $this->belongsTo(News::class);
    }
}