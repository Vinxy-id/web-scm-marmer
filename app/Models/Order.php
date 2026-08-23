<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_id',
        'product_id',
        'work_order_id',
        'quantity',
        'payment_scheme',
        'payment_method',
        'unit_price',
        'total_amount',
        'paid_amount',
        'unique_code',
        'payment_status',
        'order_status',
        'shipping_address',
        'shipping_city',
        'receiver_name',
        'receiver_phone',
        'custom_notes',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'quantity' => 'integer',
        'unique_code' => 'integer',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'paid_full' => 'Lunas (100%)',
            'paid_dp' => 'DP Terbayar (50%)',
            default => 'Menunggu Pembayaran',
        };
    }

    public function getOrderStatusLabelAttribute(): string
    {
        return match ($this->order_status) {
            'pending_payment' => 'Menunggu Konfirmasi Pembayaran',
            'in_production' => 'Sedang Dikerjakan di Bengkel',
            'qc_phase' => 'Tahap Pengujian Kualitas (QC)',
            'packing' => 'Packing Peti Kayu Solid',
            'shipped' => 'Dalam Perjalanan Kargo',
            'delivered' => 'Pesanan Telah Diterima',
            default => 'Pesanan Diterima',
        };
    }
}
