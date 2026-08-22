<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users (RBAC)
        DB::table('users')->insert([
            [
                'name' => 'Pak Joko (Pemilik IKM)',
                'email' => 'owner@cahayaonix.com',
                'password' => Hash::make('password123'),
                'role' => 'owner',
                'phone' => '081234567890',
                'ikm_name' => 'UD Cahaya Onix',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mas Budi (Staf Gudang)',
                'email' => 'gudang@cahayaonix.com',
                'password' => Hash::make('password123'),
                'role' => 'gudang',
                'phone' => '081234567891',
                'ikm_name' => 'UD Cahaya Onix',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pak Slamet (Operator Produksi)',
                'email' => 'produksi@cahayaonix.com',
                'password' => Hash::make('password123'),
                'role' => 'produksi',
                'phone' => '081234567892',
                'ikm_name' => 'UD Cahaya Onix',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mbak Rini (Staf Distribusi & Penjualan)',
                'email' => 'distribusi@cahayaonix.com',
                'password' => Hash::make('password123'),
                'role' => 'distribusi',
                'phone' => '081234567893',
                'ikm_name' => 'UD Cahaya Onix',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 2. Seed Suppliers
        DB::table('suppliers')->insert([
            [
                'supplier_code' => 'SUP-BSL-01',
                'name' => 'Tambang Marmer Besole Jaya',
                'contact_person' => 'Pak Haji Munir',
                'phone' => '085211223344',
                'address' => 'Desa Besole, Kec. Besuki, Tulungagung',
                'quarry_location' => 'Besole (Marmer Putih & Trosobo)',
                'material_category' => 'Bongkahan Marmer trosobo & putih',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_code' => 'SUP-CPD-02',
                'name' => 'Pemasok Batu Kali Campurdarat',
                'contact_person' => 'Pak Sukarni',
                'phone' => '085299887766',
                'address' => 'Kec. Campurdarat, Tulungagung',
                'quarry_location' => 'Sungai Campurdarat',
                'material_category' => 'Batu Kali Hitam / Porang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 3. Seed Categories
        DB::table('categories')->insert([
            ['name' => 'Bahan Baku Utuh', 'slug' => 'bahan-baku-utuh', 'type' => 'material', 'description' => 'Bongkahan / Blok Marmer Utuh', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kerajinan Vas & Souvenir', 'slug' => 'kerajinan-vas-souvenir', 'type' => 'product', 'description' => 'Bubut & Poles Kerajinan Vas', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lantai & Dinding', 'slug' => 'lantai-dinding', 'type' => 'product', 'description' => 'Slep ubin marmer potong', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 4. Seed Materials
        DB::table('materials')->insert([
            [
                'supplier_id' => 1,
                'material_code' => 'MAT-MRM-001',
                'name' => 'Blok Marmer Putih Super Besole',
                'type' => 'marmer',
                'grade' => 'grade_a_super',
                'dimension_info' => '100x80x60 cm',
                'unit' => 'blok',
                'current_stock' => 12.00,
                'minimum_stock' => 3.00,
                'unit_cost' => 4500000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_id' => 2,
                'material_code' => 'MAT-BLK-002',
                'name' => 'Batu Kali Porang Campurdarat',
                'type' => 'batu_kali',
                'grade' => 'grade_b_standard',
                'dimension_info' => 'Diameter 40-60 cm',
                'unit' => 'ton',
                'current_stock' => 8.50,
                'minimum_stock' => 2.00,
                'unit_cost' => 1800000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
