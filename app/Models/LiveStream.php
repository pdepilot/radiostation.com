<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveStream extends Model
{
    use HasFactory;
    protected $fillable = [
        'show_id',
        'dj_id',
        'title',
        'slug',
        'description',
        'status',
        'stream_url',
        'chat_enabled',
        'listener_count',
        'last_reset_at',
        'server_host',
        'bitrate',
        'scheduled_for',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'chat_enabled' => 'boolean',
        'listener_count' => 'integer',
        'bitrate' => 'integer',
        'scheduled_for' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'last_reset_at' => 'datetime',
    ];

    public function show()
    {
        return $this->belongsTo(Show::class);
    }

    public function dj()
    {
        return $this->belongsTo(Dj::class);
    }

    public function listenerSessions()
    {
        return $this->hasMany(ListenerSession::class);
    }

    /**
     * Get the total count of all listeners who have tuned into this live stream
     * This counts all listener sessions (including inactive ones)
     */
    public function getTotalListenersCountAttribute()
    {
        return $this->listenerSessions()->count();
    }

    /**
     * Get the count of unique listeners who have tuned into this live stream
     * This counts unique users/IPs or unique sessions
     */
    public function getUniqueListenersCountAttribute()
    {
        // Count unique sessions (each session represents a listener tuning in)
        // If you want truly unique users, you could group by user_id or ip_address
        return $this->listenerSessions()
            ->distinct('session_id')
            ->count();
    }
}
