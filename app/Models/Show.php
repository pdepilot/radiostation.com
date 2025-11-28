<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Show extends Model
{
    use HasFactory;
    protected $fillable = [
        'dj_id',
        'title',
        'slug',
        'tagline',
        'description',
        'genre',
        'day_of_week',
        'start_time',
        'end_time',
        'hero_image',
        'is_live',
        'sponsor',
        'listener_count',
        'stream_url',
        'status',
    ];

    protected $casts = [
        'is_live' => 'boolean',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function dj()
    {
        return $this->belongsTo(Dj::class);
    }

    public function liveStreams()
    {
        return $this->hasMany(LiveStream::class);
    }
}
