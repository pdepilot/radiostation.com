<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevenueRecord extends Model
{
    use HasFactory;
    protected $fillable = [
        'advertising_package_id',
        'sponsor_name',
        'contact_email',
        'amount',
        'currency',
        'status',
        'invoice_number',
        'due_date',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    public function package()
    {
        return $this->belongsTo(AdvertisingPackage::class, 'advertising_package_id');
    }
}
