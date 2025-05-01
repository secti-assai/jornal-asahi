<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Interview extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'youtube_video_id',
        'featured', // Destacado na home
        'interview_date', // Data da entrevista
        'interviewee', // Nome da pessoa entrevistada
    ];

    protected $casts = [
        'featured' => 'boolean',
        'interview_date' => 'datetime',
    ];
}