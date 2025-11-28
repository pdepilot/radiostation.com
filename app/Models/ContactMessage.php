<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'type',
        'message',
        'status',
        'handled_by',
        'handled_at',
    ];

    protected $casts = [
        'handled_at' => 'datetime',
    ];

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }
}
