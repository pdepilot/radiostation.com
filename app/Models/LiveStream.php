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
}
