<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'material_id',
        'user_id',
        'type',
        'quantity',
        'unit',
        'before_stock',
        'after_stock',
        'reference_type',
        'reference_id',
        'notes',
        'transaction_date',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'before_stock' => 'decimal:2',
        'after_stock' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
