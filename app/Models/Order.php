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
        'snap_token',
        'midtrans_transaction_id',
        'midtrans_payment_type',
        'midtrans_status',
        'midtrans_response',
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
        'cancellation_reason',
        'cancelled_at',
        'expires_at',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'quantity' => 'integer',
        'unique_code' => 'integer',
        'midtrans_response' => 'array',
        'expires_at' => 'datetime',
        'cancelled_at' => 'datetime',
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

    public function isExpired(): bool
    {
        if ($this->order_status === 'expired') {
            return true;
        }

        return $this->order_status === 'pending_payment' && $this->expires_at && $this->expires_at->isPast();
    }

    public function isCancelled(): bool
    {
        return in_array($this->order_status, ['cancelled', 'expired']);
    }

    public function canBeVerified(): bool
    {
        return in_array($this->order_status, ['pending_payment', 'verified']) && is_null($this->work_order_id);
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'paid_full' => 'Lunas (100%)',
            'paid_dp' => 'DP Terbayar (50%)',
            'refunded' => 'Uang Dikembalikan (Refund)',
            default => 'Menunggu Pembayaran',
        };
    }

    public function getPaymentSchemeLabelAttribute(): string
    {
        return match ($this->payment_scheme) {
            'dp_50' => 'Uang Muka (DP 50%)',
            'full_100' => 'Pelunasan Penuh (100%)',
            default => ucwords(str_replace('_', ' ', $this->payment_scheme ?? '')),
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'midtrans' => 'Pembayaran Digital (Midtrans)',
            'qris' => 'QRIS Standar Nasional',
            'bank_bca' => 'Transfer Bank BCA',
            'bank_bri' => 'Transfer Bank BRI',
            'bank_mandiri' => 'Transfer Bank Mandiri',
            'bank_bni' => 'Transfer Bank BNI',
            'bank_cimb' => 'Transfer Bank CIMB Niaga',
            'bank_permata' => 'Transfer Bank Permata',
            'bank_bsi' => 'Transfer Bank Syariah Indonesia (BSI)',
            default => ucwords(str_replace(['_', '-'], ' ', $this->payment_method ?? '')),
        };
    }

    public function getFormattedPaymentTypeAttribute(): string
    {
        $type = strtolower($this->midtrans_payment_type ?? '');

        if (!empty($type)) {
            return match ($type) {
                'bank_transfer' => 'Transfer Virtual Account / Bank',
                'bca_va' => 'BCA Virtual Account',
                'bni_va' => 'BNI Virtual Account',
                'bri_va' => 'BRI Virtual Account',
                'permata_va' => 'Permata Virtual Account',
                'cimb_va' => 'CIMB Niaga Virtual Account',
                'other_va' => 'Virtual Account Bank Lainnya',
                'echannel', 'mandiri_bill', 'mandiri_clickpay' => 'Mandiri Bill Payment',
                'qris', 'qris_gopay', 'gopay' => 'QRIS Dinamis (GoPay / OVO / DANA)',
                'shopeepay' => 'ShopeePay / SPayLater',
                'credit_card' => 'Kartu Kredit / Debit Online',
                'cstore', 'indomaret' => 'Gerai Indomaret',
                'alfamart' => 'Gerai Alfamart',
                'akulaku' => 'Akulaku PayLater',
                'kredivo' => 'Kredivo PayLater',
                'bca_klikbca' => 'BCA KlikBCA',
                'bca_klikpay' => 'BCA KlikPay',
                'cimb_clicks' => 'CIMB Clicks',
                'danamon_online' => 'Danamon Online Banking',
                'bri_epay' => 'BRI E-Pay',
                'dana' => 'DANA',
                'ovo' => 'OVO',
                'linkaja' => 'LinkAja',
                default => ucwords(str_replace(['_', '-'], ' ', $type)),
            };
        }

        return $this->payment_method_label;
    }

    public function getOrderStatusLabelAttribute(): string
    {
        if ($this->isExpired()) {
            return 'Kadaluarsa (Melewati 24 Jam)';
        }

        if ($this->order_status === 'pending_payment') {
            if (in_array($this->payment_status, ['paid_dp', 'paid_full'])) {
                return 'Pembayaran Diterima (Menunggu Konfirmasi)';
            }
            if ($this->payment_method === 'midtrans' && $this->midtrans_status === 'pending') {
                return 'Menunggu Penyelesaian Pembayaran';
            }
            if ($this->payment_method === 'midtrans') {
                return 'Menunggu Pembayaran Online';
            }
            return 'Menunggu Transfer Pembayaran';
        }

        return match ($this->order_status) {
            'verified' => 'Pembayaran Terverifikasi (Siap SPK)',
            'in_production' => 'Sedang Dikerjakan di Bengkel',
            'qc_phase' => 'Tahap Pengujian Kualitas (QC)',
            'packing' => 'Pengemasan Peti Kayu Solid',
            'shipped' => 'Dalam Pengiriman Ekspedisi Kargo',
            'delivered' => 'Pesanan Telah Diterima',
            'cancelled' => 'Pesanan Dibatalkan',
            'expired' => 'Kadaluarsa',
            default => ucfirst(str_replace('_', ' ', $this->order_status)),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        if ($this->isExpired()) {
            return 'bg-rose-100 text-rose-800 border-rose-200';
        }

        if ($this->order_status === 'pending_payment') {
            if (in_array($this->payment_status, ['paid_dp', 'paid_full'])) {
                return 'bg-emerald-100 text-emerald-800 border-emerald-200';
            }
            if ($this->payment_method === 'midtrans' && $this->midtrans_status === 'pending') {
                return 'bg-amber-100 text-amber-800 border-amber-200';
            }
            return 'bg-blue-100 text-blue-800 border-blue-200';
        }

        return match ($this->order_status) {
            'verified' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'in_production' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
            'qc_phase' => 'bg-purple-100 text-purple-800 border-purple-200',
            'packing' => 'bg-cyan-100 text-cyan-800 border-cyan-200',
            'shipped' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'delivered' => 'bg-green-100 text-green-800 border-green-200',
            'cancelled', 'expired' => 'bg-rose-100 text-rose-800 border-rose-200',
            default => 'bg-slate-100 text-slate-800 border-slate-200',
        };
    }
}
