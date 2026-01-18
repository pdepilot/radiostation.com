<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteAnalytics extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'site_analytics';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'ip',
        'city',
        'state',
        'country',
        'page',
        'user_agent',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the analytics record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
