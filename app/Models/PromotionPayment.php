<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'music_promotion_id',
        'paystack_reference',
        'amount',
        'currency',
        'status',
        'paystack_response',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paystack_response' => 'array',
    ];

    public function promotion()
    {
        return $this->belongsTo(MusicPromotion::class, 'music_promotion_id');
    }
}
