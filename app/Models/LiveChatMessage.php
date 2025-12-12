<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'message',
        'ip_address',
        'is_moderated',
    ];

    protected $casts = [
        'is_moderated' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeRecent($query, $limit = 50)
    {
        return $query->latest()->limit($limit);
    }
}
