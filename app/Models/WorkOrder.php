<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'spk_number',
        'product_id',
        'customer_id',
        'target_quantity',
        'completed_quantity',
        'scrap_quantity',
        'status',
        'priority',
        'start_date',
        'due_date',
        'completion_date',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'target_quantity' => 'integer',
        'completed_quantity' => 'integer',
        'scrap_quantity' => 'integer',
        'start_date' => 'date',
        'due_date' => 'date',
        'completion_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function steps()
    {
        return $this->hasMany(ProductionStep::class)->orderBy('sequence_order');
    }

    public function qcLogs()
    {
        return $this->hasMany(QcLog::class);
    }

    public function wasteLogs()
    {
        return $this->hasMany(WasteLog::class);
    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class);
    }

    public function getProgressPercentageAttribute(): int
    {
        if ($this->target_quantity == 0) return 0;
        return min(100, (int) round(($this->completed_quantity / $this->target_quantity) * 100));
    }
}
