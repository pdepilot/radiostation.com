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

    /**
     * Get the full URL for the avatar image
     * Handles both public assets and storage paths
     */
    public function getAvatarUrlAttribute($value)
    {
        if (!$value) {
            return asset('assets/images/face.jpg'); // Fallback image
        }

        // If it's already a full URL, return as-is
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        // Handle public assets paths (e.g., /assets/images/...)
        if (str_starts_with($value, '/assets/') || str_starts_with($value, 'assets/')) {
            return asset(ltrim($value, '/'));
        }

        // For storage paths, construct the URL manually
        $imagePath = $value;
        if (str_starts_with($imagePath, 'storage/')) {
            $imagePath = substr($imagePath, 8);
        }
        if (str_starts_with($imagePath, '/storage/')) {
            $imagePath = substr($imagePath, 9);
        }

        return asset('storage/' . ltrim($imagePath, '/'));
    }
}
