<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'material_code',
        'name',
        'type',
        'grade',
        'dimension_info',
        'unit',
        'current_stock',
        'minimum_stock',
        'unit_cost',
    ];

    protected $casts = [
        'current_stock' => 'decimal:2',
        'minimum_stock' => 'decimal:2',
        'unit_cost' => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function transactions()
    {
        return $this->hasMany(StockTransaction::class);
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->current_stock <= $this->minimum_stock * 0.8) {
            return 'kritis';
        } elseif ($this->current_stock <= $this->minimum_stock) {
            return 'rendah';
        }
        return 'normal';
    }
}
