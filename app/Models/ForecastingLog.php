<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForecastingLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'product_id',
        'model_type',
        'period_start',
        'period_end',
        'horizon_months',
        'input_data_json',
        'forecast_result_json',
        'mape_score',
        'rmse_score',
        'generated_by',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'horizon_months' => 'integer',
        'input_data_json' => 'array',
        'forecast_result_json' => 'array',
        'mape_score' => 'decimal:4',
        'rmse_score' => 'decimal:4',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
