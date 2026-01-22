<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionWaitlist extends Model
{
    use HasFactory;

    protected $table = 'promotion_waitlist';

    protected $fillable = [
        'email',
        'notified',
        'notified_at',
    ];

    protected $casts = [
        'notified' => 'boolean',
        'notified_at' => 'datetime',
    ];
}
