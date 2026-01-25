<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListenerSession extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'session_id',
        'live_stream_id',
        'user_id',
        'ip_address',
        'started_at',
        'last_activity_at',
        'is_active',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function liveStream()
    {
        return $this->belongsTo(LiveStream::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

