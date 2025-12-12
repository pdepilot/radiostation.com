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
    ];

    protected $casts = [
        'tags' => 'array',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class, 'news_post_id');
    }
}
