<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with empirical data from UD Cahaya Onix & UD Putra Abadi.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // 1. SEED USERS
        DB::table('users')->insertOrIgnore([
            [
                'id' => 11,
                'name' => 'M. Ilham Nur Amali',
                'email' => 'ilham.cahayaonix@placeholder.local',
                'password' => Hash::make('password'),
                'role' => 'owner',
                'phone' => null,
                'ikm_name' => 'UD Cahaya Onix',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 12,
                'name' => 'Suparno',
                'email' => 'suparno.cahayaonix@placeholder.local',
                'password' => Hash::make('password'),
                'role' => 'produksi',
                'phone' => null,
                'ikm_name' => 'UD Cahaya Onix',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 13,
                'name' => 'Efri Saputra',
                'email' => 'efri.putraabadi@placeholder.local',
                'password' => Hash::make('password'),
                'role' => 'owner',
                'phone' => null,
                'ikm_name' => 'UD Putra Abadi',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 14,
                'name' => 'Misno',
                'email' => 'misno.putraabadi@placeholder.local',
                'password' => Hash::make('password'),
                'role' => 'produksi',
                'phone' => null,
                'ikm_name' => 'UD Putra Abadi',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 15,
                'name' => 'Suyanto',
                'email' => 'suyanto.putraabadi@placeholder.local',
                'password' => Hash::make('password'),
                'role' => 'produksi',
                'phone' => null,
                'ikm_name' => 'UD Putra Abadi',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // 2. SEED CATEGORIES
        DB::table('categories')->insertOrIgnore([
            ['id' => 14, 'name' => 'Bahan Baku Marmer', 'slug' => 'bahan-baku-marmer', 'type' => 'material', 'description' => 'Bongkahan batu marmer putih & hitam dari Campurdarat', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 15, 'name' => 'Bahan Baku Batu Kali', 'slug' => 'bahan-baku-batu-kali', 'type' => 'material', 'description' => 'Batu kali alam untuk stepping, wastafel, dan kap lampu', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 16, 'name' => 'Wastafel Marmer', 'slug' => 'wastafel-marmer', 'type' => 'product', 'description' => 'Wastafel cuci tangan olahan batu marmer, finishing Hi-Glossy', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 17, 'name' => 'Wastafel Batu Kali', 'slug' => 'wastafel-batu-kali', 'type' => 'product', 'description' => 'Wastafel cuci tangan olahan batu kali, finishing Hi-Glossy', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 18, 'name' => 'Stepping Batu Kali', 'slug' => 'stepping-batu-kali', 'type' => 'product', 'description' => 'Batu pijakan taman dari batu kali, hasil gerinda halus', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 19, 'name' => 'Kap Lampu Batu Kali', 'slug' => 'kap-lampu-batu-kali', 'type' => 'product', 'description' => 'Kap lampu hias dari olahan batu kali', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 20, 'name' => 'Pedestal Marmer', 'slug' => 'pedestal-marmer', 'type' => 'product', 'description' => 'Pedestal/dudukan wastafel olahan batu marmer', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 21, 'name' => 'Wastafel Onix', 'slug' => 'wastafel-onix', 'type' => 'product', 'description' => 'Wastafel mewah olahan batu onix tembus cahaya, finishing Super Glossy', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 22, 'name' => 'Meja & Ornamen Marmer', 'slug' => 'meja-ornamen-marmer', 'type' => 'product', 'description' => 'Meja bundar marmer, pot hias, dan ornamen interior', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 3. SEED MATERIALS
        DB::table('materials')->insertOrIgnore([
            ['id' => 5, 'supplier_id' => null, 'material_code' => 'MAT-MRM-001', 'name' => 'Batu Marmer Putih', 'type' => 'marmer', 'grade' => 'grade_b_standard', 'dimension_info' => '60x60x80 cm', 'unit' => 'Balok', 'current_stock' => 28.00, 'minimum_stock' => 5.00, 'unit_cost' => 180000.00, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'supplier_id' => null, 'material_code' => 'MAT-MRM-002', 'name' => 'Batu Marmer Hitam', 'type' => 'marmer', 'grade' => 'grade_b_standard', 'dimension_info' => '50x50x70 cm', 'unit' => 'Balok', 'current_stock' => 14.00, 'minimum_stock' => 5.00, 'unit_cost' => 210000.00, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'supplier_id' => null, 'material_code' => 'MAT-BKL-001', 'name' => 'Batu Kali', 'type' => 'batu_kali', 'grade' => 'grade_b_standard', 'dimension_info' => 'Diameter 30-50 cm', 'unit' => 'biji', 'current_stock' => 65.00, 'minimum_stock' => 10.00, 'unit_cost' => 25000.00, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 4. SEED PRODUCTS
        DB::table('products')->insertOrIgnore([
            ['id' => 4, 'category_id' => 16, 'product_code' => 'PRD-WSF-MRM-01', 'name' => 'Wastafel Marmer Putih B1 (B-One)', 'material_type' => 'marmer', 'dimension_spec' => 'D: 40 cm, T: 15 cm', 'finishing_type' => 'Hi-Glossy', 'ready_stock' => 14, 'safety_stock' => 5, 'standard_cogs' => 280000.00, 'selling_price' => 450000.00, 'image_path' => 'images/products/wastafel-marmer-putih.svg', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'category_id' => 18, 'product_code' => 'PRD-STP-BKL-01', 'name' => 'Stepping Stone Pijakan Taman Batu Kali', 'material_type' => 'batu_kali', 'dimension_spec' => 'D: 30-35 cm, Tebal: 4 cm', 'finishing_type' => 'Gerinda Halus Anti-Slip', 'ready_stock' => 50, 'safety_stock' => 10, 'standard_cogs' => 25000.00, 'selling_price' => 45000.00, 'image_path' => 'images/products/stepping-stone.svg', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'category_id' => 17, 'product_code' => 'PRD-WSF-BKL-01', 'name' => 'Wastafel Batu Kali Alami Campurdarat', 'material_type' => 'batu_kali', 'dimension_spec' => 'D: 45 cm, T: 16 cm', 'finishing_type' => 'Alami Luar / Halus Dalam', 'ready_stock' => 8, 'safety_stock' => 4, 'standard_cogs' => 220000.00, 'selling_price' => 380000.00, 'image_path' => 'images/products/wastafel-batu-kali.svg', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'category_id' => 19, 'product_code' => 'PRD-LMP-BKL-01', 'name' => 'Kap Lampu Hias Batu Kali Minimalis', 'material_type' => 'batu_kali', 'dimension_spec' => 'T: 25 cm, D: 18 cm', 'finishing_type' => 'Hi-Glossy Alami', 'ready_stock' => 12, 'safety_stock' => 3, 'standard_cogs' => 95000.00, 'selling_price' => 175000.00, 'image_path' => 'images/products/kap-lampu.svg', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 8, 'category_id' => 20, 'product_code' => 'PRD-PDS-MRM-01', 'name' => 'Pedestal Wastafel Marmer Luxury', 'material_type' => 'marmer', 'dimension_spec' => 'T: 85 cm, D: 45 cm', 'finishing_type' => 'Hi-Glossy Kaca', 'ready_stock' => 3, 'safety_stock' => 2, 'standard_cogs' => 1100000.00, 'selling_price' => 1850000.00, 'image_path' => 'images/products/pedestal-marmer.svg', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 9, 'category_id' => 21, 'product_code' => 'PRD-WSF-ONX-01', 'name' => 'Wastafel Onyx Tembus Cahaya Eksklusif', 'material_type' => 'onix', 'dimension_spec' => 'D: 42 cm, T: 14 cm', 'finishing_type' => 'Super Hi-Glossy Translucent', 'ready_stock' => 5, 'safety_stock' => 2, 'standard_cogs' => 550000.00, 'selling_price' => 950000.00, 'image_path' => 'images/products/wastafel-onyx.svg', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 10, 'category_id' => 16, 'product_code' => 'PRD-WSF-MRM-02', 'name' => 'Wastafel Marmer Bakar Antik', 'material_type' => 'marmer', 'dimension_spec' => 'D: 40 cm, T: 15 cm', 'finishing_type' => 'Tekstur Bakar Kasar Eksotis', 'ready_stock' => 6, 'safety_stock' => 3, 'standard_cogs' => 300000.00, 'selling_price' => 490000.00, 'image_path' => 'images/products/wastafel-marmer-bakar.svg', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 11, 'category_id' => 22, 'product_code' => 'PRD-MJA-MRM-01', 'name' => 'Meja Kopi Bundar Marmer Besole', 'material_type' => 'marmer', 'dimension_spec' => 'D: 60 cm, T: 45 cm', 'finishing_type' => 'Hi-Glossy Urat Abu', 'ready_stock' => 4, 'safety_stock' => 2, 'standard_cogs' => 750000.00, 'selling_price' => 1350000.00, 'image_path' => 'images/products/meja-marmer.svg', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
