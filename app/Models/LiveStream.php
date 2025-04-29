<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveStream extends Model
{
    protected $fillable = [
        'title',
        'description',
        'youtube_video_id',
        'is_active',  // Certifique-se de que é is_active e não active
        'start_time',
        'end_time'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
}
