<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dj extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'stage_name',
        'slug',
        'email',
        'phone',
        'avatar_url',
        'specialty',
        'bio',
        'is_featured',
        'instagram',
        'twitter',
        'facebook',
        'mixcloud',
        'booking_link',
        'availability',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'availability' => 'array',
    ];

    public function shows()
    {
        return $this->hasMany(Show::class);
    }

    public function liveStreams()
    {
        return $this->hasMany(LiveStream::class);
    }
}
