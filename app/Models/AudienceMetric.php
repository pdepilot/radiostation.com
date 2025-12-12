<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AudienceMetric extends Model
{
    use HasFactory;
    protected $fillable = [
        'captured_for',
        'peak_listeners',
        'average_listeners',
        'total_listening_sessions',
        'unique_listeners',
        'total_listening_time',
        'new_followers',
        'chat_messages',
        'podcast_streams',
        'sms_votes',
        'top_cities',
    ];

    protected $casts = [
        'captured_for' => 'date',
        'top_cities' => 'array',
    ];
}
