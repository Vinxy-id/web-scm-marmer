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
        'customer_type',
    ];

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }
}
