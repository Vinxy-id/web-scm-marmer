<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id',
        'step_id',
        'waste_type',
        'weight_kg',
        'volume_m3',
        'reuse_status',
        'notes',
        'logged_at',
    ];

    protected $casts = [
        'weight_kg' => 'decimal:2',
        'volume_m3' => 'decimal:3',
        'logged_at' => 'date',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function step()
    {
        return $this->belongsTo(ProductionStep::class);
    }
}
