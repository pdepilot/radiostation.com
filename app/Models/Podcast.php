<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Podcast extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'slug',
        'host',
        'sponsor',
        'cover_image',
        'description',
        'audio_url',
        'duration',
        'listen_count',
        'published_at',
    ];

    protected $casts = [
        'listen_count' => 'integer',
        'published_at' => 'datetime',
    ];
}
