<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsPost extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'body',
        'hero_image',
        'author_name',
        'reading_time',
        'tags',
        'status',
        'published_at',
        'is_featured',
        'comment_count',
        'view_count',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'view_count' => 'integer',
    ];

    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class, 'news_post_id');
    }

    /**
     * Get hero_image attribute - normalize for Filament
     */
    public function getHeroImageAttribute($value)
    {
        if (!$value) {
            return null;
        }
        
        // If it's a full URL, return as-is
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }
        
        // Remove /storage/ or storage/ prefix if present (Filament expects relative to disk)
        $path = $value;
        if (str_starts_with($path, '/storage/')) {
            $path = substr($path, 9);
        } elseif (str_starts_with($path, 'storage/')) {
            $path = substr($path, 8);
        }
        
        return ltrim($path, '/');
    }

    /**
     * Increment the view count for this news post
     * Call this method when a user views the post
     */
    public function incrementViews()
    {
        $this->increment('view_count');
    }

}
