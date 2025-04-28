<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveStream extends Model
{
    protected $fillable = [
        'youtube_video_id',
        'title',
        'description',
        'is_active',
        'start_time',
        'end_time'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
}
