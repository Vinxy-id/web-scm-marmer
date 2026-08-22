<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_code',
        'work_order_id',
        'customer_id',
        'expedition_name',
        'tracking_number',
        'driver_name',
        'vehicle_plate',
        'packing_verified',
        'shipment_date',
        'delivery_status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'shipment_date' => 'date',
        'packing_verified' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accessors for backward compatibility
    public function getShipmentNumberAttribute(): ?string
    {
        return $this->shipment_code;
    }

    public function getStatusAttribute(): ?string
    {
        return $this->delivery_status;
    }

    public function getWoodenPackingCheckedAttribute(): bool
    {
        return (bool) $this->packing_verified;
    }

    public function getVehicleNumberAttribute(): ?string
    {
        return $this->vehicle_plate;
    }
}
