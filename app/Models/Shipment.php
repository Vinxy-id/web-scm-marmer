<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_number',
        'customer_id',
        'shipment_date',
        'expedition_name',
        'vehicle_number',
        'driver_name',
        'wooden_packing_checked',
        'status',
        'tracking_number',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'shipment_date' => 'date',
        'wooden_packing_checked' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
