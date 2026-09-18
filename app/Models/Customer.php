<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_code',
        'name',
        'company_name',
        'phone',
        'email',
        'address',
        'city',
        'latitude',
        'longitude',
        'maps_url',
        'customer_type',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    public function getGoogleMapsUrlAttribute(): ?string
    {
        if (!empty($this->maps_url)) {
            return $this->maps_url;
        }

        if (!empty($this->latitude) && !empty($this->longitude)) {
            return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
        }

        if (!empty($this->address)) {
            $query = urlencode($this->address . ($this->city ? ', ' . $this->city : ''));
            return "https://www.google.com/maps/search/?api=1&query={$query}";
        }

        return null;
    }
}
