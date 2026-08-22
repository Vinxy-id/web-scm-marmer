<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QcLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id',
        'step_id',
        'stage',
        'inspector_id',
        'inspected_quantity',
        'pass_quantity',
        'rework_quantity',
        'scrap_quantity',
        'defect_type',
        'rework_action',
        'inspection_date',
        'notes',
    ];

    protected $casts = [
        'inspected_quantity' => 'integer',
        'pass_quantity' => 'integer',
        'rework_quantity' => 'integer',
        'scrap_quantity' => 'integer',
        'inspection_date' => 'date',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function step()
    {
        return $this->belongsTo(ProductionStep::class);
    }

    public function inspector()
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }
}
