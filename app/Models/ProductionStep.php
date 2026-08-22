<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id',
        'step_name',
        'sequence_order',
        'machine_number',
        'operator_id',
        'start_time',
        'end_time',
        'duration_minutes',
        'input_qty',
        'output_qty',
        'status',
        'notes',
    ];

    protected $casts = [
        'sequence_order' => 'integer',
        'duration_minutes' => 'integer',
        'input_qty' => 'integer',
        'output_qty' => 'integer',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }
}
