<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaylistTrack extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'artist',
        'genre',
        'mood',
        'duration',
        'cover_image',
        'audio_url',
        'scheduled_for',
        'is_featured',
    ];

    protected $casts = [
        'scheduled_for' => 'date',
        'is_featured' => 'boolean',
    ];
}
