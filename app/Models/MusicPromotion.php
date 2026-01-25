<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MusicPromotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'artist_name',
        'track_title',
        'description',
        'audio_embed_url',
        'cover_image',
        'cta_url',
        'duration_days',
        'price_paid',
        'starts_at',
        'ends_at',
        'status',
        'impressions',
        'clicks',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'price_paid' => 'decimal:2',
        'duration_days' => 'integer',
        'impressions' => 'integer',
        'clicks' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->hasOne(PromotionPayment::class);
    }

    /**
     * Check if promotion is currently active
     */
    public function isActive(): bool
    {
        return $this->status === 'active' 
            && $this->starts_at 
            && $this->ends_at 
            && Carbon::now()->between($this->starts_at, $this->ends_at);
    }

    /**
     * Scope to get only active promotions
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('starts_at', '<=', Carbon::now())
            ->where('ends_at', '>=', Carbon::now());
    }

    /**
     * Scope to get promotions ordered by price DESC, then created_at ASC
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('price_paid', 'desc')
            ->orderBy('created_at', 'asc');
    }

    /**
     * Increment impressions counter
     */
    public function incrementImpressions()
    {
        $this->increment('impressions');
    }

    /**
     * Increment clicks counter
     */
    public function incrementClicks()
    {
        $this->increment('clicks');
    }
}
