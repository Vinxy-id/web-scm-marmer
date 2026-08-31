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
        'ikm_name',
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

    /**
     * Virtual Accessor untuk informasi toko / pengrajin mitra IKM.
     */
    public function getArtisanAttribute(): array
    {
        $ikm = $this->ikm_name ?? '';

        // Fallback jika belum di-set di database
        if (empty($ikm)) {
            $name = strtolower($this->name ?? '');
            $code = strtoupper($this->product_code ?? '');

            // Spesialisasi UD Putra Abadi
            $isPutraAbadi = in_array(strtolower($this->material_type ?? ''), ['batu_kali']) || 
                           str_contains($code, '-PA-') || 
                           str_contains($code, 'PA-') || 
                           str_contains($name, 'putra abadi') ||
                           str_contains($name, 'kali') || 
                           str_contains($name, 'stepping') || 
                           str_contains($name, 'lampu') || 
                           str_contains($name, 'tapak') || 
                           str_contains($name, 'dokar') || 
                           str_contains($name, 'burung') || 
                           str_contains($name, 'sabun') || 
                           str_contains($name, 'shampo') || 
                           str_contains($name, 'surat') || 
                           str_contains($name, 'lilin') || 
                           str_contains($name, 'toples') || 
                           str_contains($name, 'bangku') || 
                           str_contains($name, 'kursi') || 
                           str_contains($name, 'bak ikan') || 
                           str_contains($name, 'pot bunga') || 
                           str_contains($name, 'tusuk sate') ||
                           str_contains($name, 'pijakan') ||
                           str_contains($name, 'cladding');

            $ikm = $isPutraAbadi ? 'UD Putra Abadi' : 'UD Cahaya Onix';
        }

        if ($ikm === 'UD Putra Abadi') {
            return [
                'name' => 'UD Putra Abadi',
                'owner' => 'Efri Saputra',
                'phone' => '6281335022012',
                'location' => 'Campurdarat, Tulungagung',
                'badge' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                'badge_simple' => 'bg-emerald-100 text-emerald-800',
                'bank_name' => 'Bank Mandiri',
                'account_number' => '144-00-1928374-1',
                'account_holder' => 'UD Putra Abadi - Efri Saputra',
            ];
        }

        // Default: UD Cahaya Onix
        return [
            'name' => 'UD Cahaya Onix',
            'owner' => 'M. Ilham Nur Amali',
            'phone' => '6281340231737',
            'location' => 'Campurdarat, Tulungagung',
            'badge' => 'bg-blue-50 text-blue-800 border-blue-200',
            'badge_simple' => 'bg-blue-100 text-blue-800',
            'bank_name' => 'Bank BCA',
            'account_number' => '048-1928-384',
            'account_holder' => 'UD Cahaya Onix - M. Ilham',
        ];
    }

    public function getShopNameAttribute(): string
    {
        return $this->ikm_name ?: $this->artisan['name'];
    }
}
