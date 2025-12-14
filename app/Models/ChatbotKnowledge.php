<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotKnowledge extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'keyword',
        'response',
        'question_patterns',
        'category',
        'priority',
        'is_active',
        'usage_count',
    ];

    protected $casts = [
        'question_patterns' => 'array',
        'is_active' => 'boolean',
        'priority' => 'integer',
        'usage_count' => 'integer',
    ];
}

