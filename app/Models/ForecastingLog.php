<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForecastingLog extends Model
{
    use HasFactory;

    public $timestamps = false; // generated_at & created_at are timestamp columns in DB

    protected $fillable = [
        'item_type',
        'item_id',
        'algorithm_used',
        'forecast_horizon_months',
        'historical_data_points',
        'mape_score',
        'rmse_score',
        'prediction_json',
        'generated_at',
        'created_at',
    ];

    protected $casts = [
        'forecast_horizon_months' => 'integer',
        'historical_data_points' => 'integer',
        'mape_score' => 'decimal:2',
        'rmse_score' => 'decimal:2',
        'prediction_json' => 'array',
        'generated_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class, 'item_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'item_id');
    }

    public function getItemNameAttribute(): string
    {
        if ($this->item_type === 'material') {
            return $this->material?->name ?? ('Bahan Baku #' . $this->item_id);
        }
        return $this->product?->name ?? ('Produk #' . $this->item_id);
    }
}
