<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisingPackage extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'reach',
        'duration_weeks',
        'price',
        'cta',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function revenueRecords()
    {
        return $this->hasMany(RevenueRecord::class);
    }
}
