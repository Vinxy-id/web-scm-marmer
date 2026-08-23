<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class CodeGeneratorService
{
    /**
     * 1. Surat Jalan Distribusi: SJ-YYYYMM-001
     */
    public static function generateShipmentCode(): string
    {
        $prefix = 'SJ-' . date('Ym') . '-';
        
        $lastCode = DB::table('shipments')
            ->where('shipment_code', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->value('shipment_code');

        $seq = 1;
        if ($lastCode && preg_match('/-(\d+)$/', $lastCode, $matches)) {
            $seq = intval($matches[1]) + 1;
        }

        do {
            $code = $prefix . str_pad($seq, 3, '0', STR_PAD_LEFT);
            $seq++;
        } while (DB::table('shipments')->where('shipment_code', $code)->exists());

        return $code;
    }

    /**
     * 2. SPK Produksi (Manual Kanban & Web Order): SPK-YYYYMM-001
     */
    public static function generateSpkNumber(): string
    {
        $prefix = 'SPK-' . date('Ym') . '-';
        
        $lastCode = DB::table('work_orders')
            ->where('spk_number', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->value('spk_number');

        $seq = 1;
        if ($lastCode && preg_match('/-(\d+)$/', $lastCode, $matches)) {
            $seq = intval($matches[1]) + 1;
        } else {
            $countThisMonth = DB::table('work_orders')
                ->whereMonth('created_at', date('m'))
                ->whereYear('created_at', date('Y'))
                ->count();
            $seq = max(1, $countThisMonth + 1);
        }

        do {
            $spkNumber = $prefix . str_pad($seq, 3, '0', STR_PAD_LEFT);
            $seq++;
        } while (DB::table('work_orders')->where('spk_number', $spkNumber)->exists());

        return $spkNumber;
    }

    /**
     * 3. Mutasi Bahan Baku: TRX-IN-YYYYMM-001, TRX-OUT-YYYYMM-001, TRX-OPN-YYYYMM-001
     */
    public static function generateStockTransactionCode(string $type = 'in'): string
    {
        $cleanType = strtoupper($type);
        if (!in_array($cleanType, ['IN', 'OUT', 'OPN', 'OPENING'])) {
            $cleanType = 'TRX';
        }
        if ($cleanType === 'OPENING') {
            $cleanType = 'OPN';
        }

        $prefix = 'TRX-' . $cleanType . '-' . date('Ym') . '-';

        $lastCode = DB::table('stock_transactions')
            ->where('transaction_code', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->value('transaction_code');

        $seq = 1;
        if ($lastCode && preg_match('/-(\d+)$/', $lastCode, $matches)) {
            $seq = intval($matches[1]) + 1;
        }

        do {
            $code = $prefix . str_pad($seq, 3, '0', STR_PAD_LEFT);
            $seq++;
        } while (DB::table('stock_transactions')->where('transaction_code', $code)->exists());

        return $code;
    }

    /**
     * 4. Kode Pelanggan: CUST-YYYYMM-001
     */
    public static function generateCustomerCode(): string
    {
        $prefix = 'CUST-' . date('Ym') . '-';

        $lastCode = DB::table('customers')
            ->where('customer_code', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->value('customer_code');

        $seq = 1;
        if ($lastCode && preg_match('/-(\d+)$/', $lastCode, $matches)) {
            $seq = intval($matches[1]) + 1;
        } else {
            $seq = DB::table('customers')->count() + 1;
        }

        do {
            $code = $prefix . str_pad($seq, 3, '0', STR_PAD_LEFT);
            $seq++;
        } while (DB::table('customers')->where('customer_code', $code)->exists());

        return $code;
    }

    /**
     * 5. Kode Bahan Baku: MAT-{MRM|ONX|BKL|RES}-001
     */
    public static function generateMaterialCode(string $type = 'marmer'): string
    {
        $typePrefix = match (strtolower($type)) {
            'marmer' => 'MAT-MRM-',
            'onix', 'onyx' => 'MAT-ONX-',
            'batu_kali' => 'MAT-BKL-',
            'bahan_penolong' => 'MAT-RES-',
            default => 'MAT-GEN-',
        };

        $lastCode = DB::table('materials')
            ->where('material_code', 'like', "{$typePrefix}%")
            ->orderBy('id', 'desc')
            ->value('material_code');

        $seq = 1;
        if ($lastCode && preg_match('/-(\d+)$/', $lastCode, $matches)) {
            $seq = intval($matches[1]) + 1;
        } else {
            $seq = DB::table('materials')->where('material_code', 'like', "{$typePrefix}%")->count() + 1;
        }

        do {
            $code = $typePrefix . str_pad($seq, 3, '0', STR_PAD_LEFT);
            $seq++;
        } while (DB::table('materials')->where('material_code', $code)->exists());

        return $code;
    }

    /**
     * 6. Kode Produk: PRD-YYYYMM-001 atau PRD-{KAT}-001
     */
    public static function generateProductCode(?string $materialType = null): string
    {
        $prefix = match (strtolower($materialType ?? '')) {
            'marmer' => 'PRD-MRM-',
            'onix', 'onyx' => 'PRD-ONX-',
            'batu_kali' => 'PRD-BKL-',
            default => 'PRD-' . date('Ym') . '-',
        };

        $lastCode = DB::table('products')
            ->where('product_code', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->value('product_code');

        $seq = 1;
        if ($lastCode && preg_match('/-(\d+)$/', $lastCode, $matches)) {
            $seq = intval($matches[1]) + 1;
        } else {
            $seq = DB::table('products')->count() + 1;
        }

        do {
            $code = $prefix . str_pad($seq, 3, '0', STR_PAD_LEFT);
            $seq++;
        } while (DB::table('products')->where('product_code', $code)->exists());

        return $code;
    }
}
