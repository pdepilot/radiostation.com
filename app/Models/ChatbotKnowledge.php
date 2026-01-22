<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    /**
     * Get question_patterns formatted for Filament Repeater (array of objects)
     */
    protected function questionPatternsForRepeater(): Attribute
    {
        return Attribute::make(
            get: function () {
                $patterns = $this->question_patterns ?? [];
                if (empty($patterns) || !is_array($patterns)) {
                    return [];
                }
                // Check if already in object format
                if (isset($patterns[0]) && is_array($patterns[0]) && isset($patterns[0]['pattern'])) {
                    return $patterns;
                }
                // Convert array of strings to array of objects
                return array_map(fn ($pattern) => ['pattern' => $pattern], $patterns);
            },
        );
    }
}

