<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'product_code',
        'name',
        'material_type',
        'dimension_spec',
        'finishing_type',
        'ready_stock',
        'safety_stock',
        'standard_cogs',
        'selling_price',
        'image_path',
    ];

    protected $casts = [
        'ready_stock' => 'integer',
        'safety_stock' => 'integer',
        'standard_cogs' => 'decimal:2',
        'selling_price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }
}
