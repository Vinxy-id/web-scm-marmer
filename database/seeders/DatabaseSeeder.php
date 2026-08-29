<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with 100% empirical data from field observation Excel files:
     * 1. Bahan Baku Cahaya Onix.xlsx
     * 2. Hasil Produksi Cahaya Onix.xlsx (17 bulan: Jan 2025 - Mei 2026)
     * 3. Bahan Baku Putra Abadi.xlsx
     * 4. Hasil Produksi Putra Abadi.xlsx
     * 5. Hasil Kerja 1.1 & 1.2 Observasi Lapangan Pemborosan (UD Cahaya Onix & UD Putra Abadi)
     * Supervisi: Pak Pangki S.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // 1. SEED USERS
        DB::table('users')->upsert([
            [
                'id' => 1,
                'name' => 'M. Ilham Nur Amali (Owner)',
                'email' => 'owner@cahayaonix.com',
                'password' => Hash::make('role123'),
                'role' => 'owner',
                'phone' => '081340231737',
                'ikm_name' => 'UD Cahaya Onix',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'name' => 'Budi Santoso (Gudang)',
                'email' => 'gudang@cahayaonix.com',
                'password' => Hash::make('role123'),
                'role' => 'gudang',
                'phone' => '081234567891',
                'ikm_name' => 'UD Cahaya Onix',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'name' => 'Suparno / Pak Slamet (Produksi)',
                'email' => 'produksi@cahayaonix.com',
                'password' => Hash::make('role123'),
                'role' => 'produksi',
                'phone' => '081234567892',
                'ikm_name' => 'UD Cahaya Onix',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'name' => 'Rini Astuti (Distribusi)',
                'email' => 'distribusi@cahayaonix.com',
                'password' => Hash::make('role123'),
                'role' => 'distribusi',
                'phone' => '081234567893',
                'ikm_name' => 'UD Cahaya Onix',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 5,
                'name' => 'Administrator Klaster Marmer',
                'email' => 'admin@escm-marmer.id',
                'password' => Hash::make('role123'),
                'role' => 'admin',
                'phone' => '081234567899',
                'ikm_name' => 'Pusat Klaster Tulungagung',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 13,
                'name' => 'Efri Saputra (Owner Putra Abadi)',
                'email' => 'efri.putraabadi@placeholder.local',
                'password' => Hash::make('role123'),
                'role' => 'owner',
                'phone' => '081335022012',
                'ikm_name' => 'UD Putra Abadi',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 14,
                'name' => 'Misno (Mandor Putra Abadi)',
                'email' => 'misno.putraabadi@placeholder.local',
                'password' => Hash::make('role123'),
                'role' => 'produksi',
                'phone' => '085233998878',
                'ikm_name' => 'UD Putra Abadi',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ], ['email'], ['name', 'password', 'role', 'phone', 'ikm_name', 'is_active', 'updated_at']);

        // 2. SEED SUPPLIERS (Penambang Lokal Tulungagung)
        DB::table('suppliers')->insertOrIgnore([
            ['id' => 1, 'supplier_code' => 'SUP-CPD-01', 'name' => 'Tambang Marmer Campurdarat Jaya', 'contact_person' => 'Pak Sukir', 'phone' => '085233112233', 'address' => 'Desa Campurdarat', 'quarry_location' => 'Kecamatan Campurdarat, Tulungagung', 'material_category' => 'Bongkahan Marmer Putih & Bintik', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'supplier_code' => 'SUP-CPD-02', 'name' => 'Penambang Onyx Campurdarat', 'contact_person' => 'Pak Wahyu', 'phone' => '085244556677', 'address' => 'Desa Campurdarat', 'quarry_location' => 'Campurdarat, Tulungagung', 'material_category' => 'Bongkahan Onyx Tembus Cahaya', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'supplier_code' => 'SUP-KL-03', 'name' => 'Paguyuban Batu Kali Boyolangu', 'contact_person' => 'Pak Yatno', 'phone' => '085277889900', 'address' => 'Desa Boyolangu', 'quarry_location' => 'Boyolangu, Tulungagung', 'material_category' => 'Batu Kali Bulat & Pipih', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 3. SEED CATEGORIES
        DB::table('categories')->insertOrIgnore([
            ['id' => 14, 'name' => 'Bahan Baku Marmer', 'slug' => 'bahan-baku-marmer', 'type' => 'material', 'description' => 'Bongkahan batu marmer putih & bintik dari Campurdarat', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 15, 'name' => 'Bahan Baku Batu Kali', 'slug' => 'bahan-baku-batu-kali', 'type' => 'material', 'description' => 'Batu kali alam untuk stepping, wastafel, dan kap lampu', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 16, 'name' => 'Wastafel Marmer', 'slug' => 'wastafel-marmer', 'type' => 'product', 'description' => 'Wastafel cuci tangan olahan batu marmer, finishing Hi-Glossy', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 17, 'name' => 'Wastafel Batu Kali', 'slug' => 'wastafel-batu-kali', 'type' => 'product', 'description' => 'Wastafel cuci tangan olahan batu kali, finishing Hi-Glossy Alami', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 18, 'name' => 'Stepping Batu Kali', 'slug' => 'stepping-batu-kali', 'type' => 'product', 'description' => 'Batu pijakan taman dari batu kali, hasil gerinda halus', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 19, 'name' => 'Kerajinan', 'slug' => 'kerajinan', 'type' => 'product', 'description' => 'Aneka kerajinan dari Batu Kali, Marmer & Onyx', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 20, 'name' => 'Pedestal Marmer', 'slug' => 'pedestal-marmer', 'type' => 'product', 'description' => 'Pedestal/dudukan wastafel olahan batu marmer', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 21, 'name' => 'Wastafel Onix', 'slug' => 'wastafel-onix', 'type' => 'product', 'description' => 'Wastafel mewah olahan batu onix tembus cahaya, finishing Super Glossy', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 22, 'name' => 'Meja & Ornamen Marmer', 'slug' => 'meja-ornamen-marmer', 'type' => 'product', 'description' => 'Meja bundar marmer, pot hias, dan ornamen interior', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 4. SEED MATERIALS (Bahan Baku dari Excel Bahan Baku Cahaya Onix & Putra Abadi)
        DB::table('materials')->insertOrIgnore([
            ['id' => 5, 'supplier_id' => 1, 'material_code' => 'MAT-MRM-001', 'name' => 'Batu Marmer Putih Campurdarat', 'type' => 'marmer', 'grade' => 'grade_b_standard', 'dimension_info' => '60x60x80 cm', 'unit' => 'Balok', 'current_stock' => 19.00, 'minimum_stock' => 5.00, 'unit_cost' => 180000.00, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'supplier_id' => 1, 'material_code' => 'MAT-MRM-002', 'name' => 'Batu Marmer Bintik Hitam', 'type' => 'marmer', 'grade' => 'grade_b_standard', 'dimension_info' => '50x50x70 cm', 'unit' => 'Balok', 'current_stock' => 10.00, 'minimum_stock' => 5.00, 'unit_cost' => 210000.00, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'supplier_id' => 3, 'material_code' => 'MAT-BKL-001', 'name' => 'Batu Kali Alami Boyolangu', 'type' => 'batu_kali', 'grade' => 'grade_b_standard', 'dimension_info' => 'Diameter 30-50 cm', 'unit' => 'Biji', 'current_stock' => 800.00, 'minimum_stock' => 100.00, 'unit_cost' => 25000.00, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 8, 'supplier_id' => 2, 'material_code' => 'MAT-ONX-001', 'name' => 'Bongkahan Onyx Tembus Cahaya', 'type' => 'onix', 'grade' => 'grade_a_super', 'dimension_info' => '40x40x50 cm', 'unit' => 'Bongkahan', 'current_stock' => 8.00, 'minimum_stock' => 2.00, 'unit_cost' => 450000.00, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 9, 'supplier_id' => 1, 'material_code' => 'MAT-RES-001', 'name' => 'Resin & Katalis Penambal', 'type' => 'bahan_penolong', 'grade' => 'grade_b_standard', 'dimension_info' => 'Kaleng 1 kg', 'unit' => 'Kaleng', 'current_stock' => 12.00, 'minimum_stock' => 3.00, 'unit_cost' => 75000.00, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 5. SEED PRODUCTS (Master Model Inti Cahaya Onix & Putra Abadi + Data VPS)
        DB::table('products')->where('id', '>=', 54)->delete();
        DB::table('products')->upsert([
            ['id' => 4, 'category_id' => 16, 'product_code' => 'PRD-WSF-MRM-01', 'name' => 'Wastafel Marmer Putih B1 (B-One)', 'material_type' => 'marmer', 'dimension_spec' => 'D: 40 cm, T: 15 cm', 'finishing_type' => 'Hi-Glossy', 'ready_stock' => 14, 'safety_stock' => 5, 'standard_cogs' => 280000.00, 'selling_price' => 450000.00, 'image_path' => 'images/products/wastafel-marmer-putih-b1.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'category_id' => 18, 'product_code' => 'PRD-STP-PA-01', 'name' => 'Batu Tapak Pijakan Taman (Stepping Stone)', 'material_type' => 'batu_kali', 'dimension_spec' => 'D: 30-35 cm, Tebal: 4 cm', 'finishing_type' => 'Gerinda Halus Anti-Slip', 'ready_stock' => 50, 'safety_stock' => 10, 'standard_cogs' => 25000.00, 'selling_price' => 45000.00, 'image_path' => 'images/products/batu-tapak-stepping-stone.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'category_id' => 17, 'product_code' => 'PRD-WSF-BKL-01', 'name' => 'Wastafel Batu Kali Alami Campurdarat', 'material_type' => 'batu_kali', 'dimension_spec' => 'D: 45 cm, T: 16 cm', 'finishing_type' => 'Alami Luar / Halus Dalam', 'ready_stock' => 8, 'safety_stock' => 4, 'standard_cogs' => 220000.00, 'selling_price' => 380000.00, 'image_path' => 'images/products/wastafel-batu-kali-alami.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'category_id' => 19, 'product_code' => 'PRD-LMP-PA-01', 'name' => 'Lampu Dokar Batu Kali Antik', 'material_type' => 'batu_kali', 'dimension_spec' => 'T: 40-45 cm, D: 25 cm', 'finishing_type' => 'Rustic Pahat Dokar Tradisional', 'ready_stock' => 8, 'safety_stock' => 3, 'standard_cogs' => 160000.00, 'selling_price' => 275000.00, 'image_path' => 'images/products/lampu-dokar-batu-kali.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 8, 'category_id' => 20, 'product_code' => 'PRD-PDS-MRM-01', 'name' => 'Pedestal Wastafel Marmer Luxury', 'material_type' => 'marmer', 'dimension_spec' => 'T: 85 cm, D: 45 cm', 'finishing_type' => 'Hi-Glossy Kaca', 'ready_stock' => 3, 'safety_stock' => 2, 'standard_cogs' => 1100000.00, 'selling_price' => 1850000.00, 'image_path' => 'images/products/pedestal-wastafel-marmer-luxury.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 9, 'category_id' => 21, 'product_code' => 'PRD-WSF-ONX-01', 'name' => 'Wastafel Onyx Tembus Cahaya Eksklusif', 'material_type' => 'onix', 'dimension_spec' => 'D: 42 cm, T: 14 cm', 'finishing_type' => 'Super Hi-Glossy Translucent', 'ready_stock' => 5, 'safety_stock' => 2, 'standard_cogs' => 550000.00, 'selling_price' => 950000.00, 'image_path' => 'images/products/wastafel-onyx-tembus-cahaya.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 10, 'category_id' => 16, 'product_code' => 'PRD-WSF-MRM-02', 'name' => 'Wastafel Marmer Bakar Antik', 'material_type' => 'marmer', 'dimension_spec' => 'D: 40 cm, T: 15 cm', 'finishing_type' => 'Tekstur Bakar Kasar Eksotis', 'ready_stock' => 6, 'safety_stock' => 3, 'standard_cogs' => 300000.00, 'selling_price' => 490000.00, 'image_path' => 'images/products/wastafel-marmer-bakar-antik.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 11, 'category_id' => 22, 'product_code' => 'PRD-MJA-MRM-01', 'name' => 'Meja Kopi Bundar Marmer Campurdarat', 'material_type' => 'marmer', 'dimension_spec' => 'D: 60 cm, T: 45 cm', 'finishing_type' => 'Hi-Glossy Urat Abu', 'ready_stock' => 4, 'safety_stock' => 2, 'standard_cogs' => 750000.00, 'selling_price' => 1350000.00, 'image_path' => 'images/products/meja-marmer-bundar.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 12, 'category_id' => 20, 'product_code' => 'PRD-PDS-MRM-02', 'name' => 'Pedestal Wastafel Marmer Kotak Minimalis', 'material_type' => 'marmer', 'dimension_spec' => 'T: 85 cm, P: 40 cm, L: 40 cm', 'finishing_type' => 'Pahat Alami & Poles Atas', 'ready_stock' => 4, 'safety_stock' => 2, 'standard_cogs' => 950000.00, 'selling_price' => 1650000.00, 'image_path' => 'images/products/pedestal-marmer-kotak.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 13, 'category_id' => 16, 'product_code' => 'PRD-WSF-MRM-03', 'name' => 'Wastafel Marmer Bintik Silinder', 'material_type' => 'marmer', 'dimension_spec' => 'D: 38 cm, T: 16 cm', 'finishing_type' => 'Full Polished Hi-Glossy', 'ready_stock' => 7, 'safety_stock' => 3, 'standard_cogs' => 270000.00, 'selling_price' => 460000.00, 'image_path' => 'images/products/wastafel-marmer-bintik-silinder.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 14, 'category_id' => 19, 'product_code' => 'PRD-ACC-PA-01', 'name' => 'Tempat Surat & Menu Holder Batu Kali', 'material_type' => 'batu_kali', 'dimension_spec' => 'D: 15-20 cm, Tebal: 6-8 cm', 'finishing_type' => 'Smooth Grafir Custom', 'ready_stock' => 25, 'safety_stock' => 5, 'standard_cogs' => 45000.00, 'selling_price' => 85000.00, 'image_path' => 'images/products/tempat-surat-menu-batu-kali.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 15, 'category_id' => 19, 'product_code' => 'PRD-ACC-PA-02', 'name' => 'Dispenser Sabun & Shampo Batu Kali Natural (Set 2 Pcs)', 'material_type' => 'batu_kali', 'dimension_spec' => 'T: 18 cm, D: 8-10 cm (Set 2 Pcs)', 'finishing_type' => 'Kulit Alami x Pump Chrome', 'ready_stock' => 20, 'safety_stock' => 5, 'standard_cogs' => 90000.00, 'selling_price' => 165000.00, 'image_path' => 'images/products/dispenser-sabun-shampo-batu-kali.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 16, 'category_id' => 19, 'product_code' => 'PRD-ACC-PA-03', 'name' => 'Dispenser Sabun & Shampo Marmer Silinder (Set 2 Pcs)', 'material_type' => 'marmer', 'dimension_spec' => 'T: 18 cm, D: 7 cm (Set 2 Pcs)', 'finishing_type' => 'Full Polished Hi-Glossy', 'ready_stock' => 15, 'safety_stock' => 4, 'standard_cogs' => 100000.00, 'selling_price' => 185000.00, 'image_path' => 'images/products/dispenser-sabun-shampo-marmer.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 17, 'category_id' => 19, 'product_code' => 'PRD-ACC-PA-04', 'name' => 'Set Perlengkapan Kamar Mandi Marmer Mewah (Set 5 Pcs)', 'material_type' => 'marmer', 'dimension_spec' => 'Dispenser T: 18 cm, Tumbler, Soap Dish, Nampan Marmer', 'finishing_type' => 'Hotel Grade Polished', 'ready_stock' => 10, 'safety_stock' => 2, 'standard_cogs' => 250000.00, 'selling_price' => 425000.00, 'image_path' => 'images/products/set-aksesoris-kamar-mandi-marmer-krem.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 18, 'category_id' => 22, 'product_code' => 'PRD-MJA-PA-01', 'name' => 'Meja Kopi Batu Kali Alami (Kaki Utuh & Tusuk Sate)', 'material_type' => 'batu_kali', 'dimension_spec' => 'D: 60-80 cm, T: 65-75 cm', 'finishing_type' => 'Top Polished x Boulder Base', 'ready_stock' => 6, 'safety_stock' => 2, 'standard_cogs' => 450000.00, 'selling_price' => 850000.00, 'image_path' => 'images/products/meja-batu-kali-kopi-set.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 19, 'category_id' => 22, 'product_code' => 'PRD-MJA-PA-02', 'name' => 'Set Meja & Bangku Taman Batu Kali Outdoor', 'material_type' => 'batu_kali', 'dimension_spec' => 'Meja: 100x60x65 cm, Bangku: 90x35x45 cm', 'finishing_type' => 'Flat Polished Top', 'ready_stock' => 3, 'safety_stock' => 1, 'standard_cogs' => 1200000.00, 'selling_price' => 2150000.00, 'image_path' => 'images/products/meja-kursi-taman-batu-kali-set.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 20, 'category_id' => 19, 'product_code' => 'PRD-BKL-012', 'name' => 'Tissue Box', 'material_type' => 'onix', 'dimension_spec' => 'P: 26 cm, L: 14 cm, T: 10 cm', 'finishing_type' => 'Honed', 'ready_stock' => 15, 'safety_stock' => 5, 'standard_cogs' => 65000.00, 'selling_price' => 100000.00, 'image_path' => 'images/products/tissue-box-1787845092.png', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 21, 'category_id' => 21, 'product_code' => 'PRD-ONX-013', 'name' => 'Wastafel Onix Full Polish', 'material_type' => 'onix', 'dimension_spec' => 'D: 40 cm, T: 15 cm', 'finishing_type' => 'Hi-Glossy', 'ready_stock' => 20, 'safety_stock' => 5, 'standard_cogs' => 325000.00, 'selling_price' => 500000.00, 'image_path' => 'images/products/wastafel-onix-full-polish-1787845285.png', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 22, 'category_id' => 16, 'product_code' => 'PRD-MRM-014', 'name' => 'Erosi Batu Marmer Putih', 'material_type' => 'marmer', 'dimension_spec' => 'D: 40 cm, T: 15 cm', 'finishing_type' => 'Polished Dalam', 'ready_stock' => 10, 'safety_stock' => 3, 'standard_cogs' => 195000.00, 'selling_price' => 300000.00, 'image_path' => 'images/products/erosi-batu-marmer-putih-1787846030.png', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 23, 'category_id' => 17, 'product_code' => 'PRD-BKL-013', 'name' => 'Wastafel Kotak Batu Andesit', 'material_type' => 'marmer', 'dimension_spec' => 'P: 60 cm, L: 40 cm, T: 15 cm', 'finishing_type' => 'Honed Matte', 'ready_stock' => 20, 'safety_stock' => 5, 'standard_cogs' => 400000.00, 'selling_price' => 600000.00, 'image_path' => 'images/products/wastafel-kotak-batu-andesit-1787846198.png', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 24, 'category_id' => 20, 'product_code' => 'PRD-MRM-015', 'name' => 'Pedistal Marmer Sumber Agung', 'material_type' => 'marmer', 'dimension_spec' => 'D: 40 cm, T: 90 cm', 'finishing_type' => 'Rock Face Pahat Luar', 'ready_stock' => 10, 'safety_stock' => 3, 'standard_cogs' => 850000.00, 'selling_price' => 1300000.00, 'image_path' => 'images/products/pedistal-marmer-sumber-agung-1787846345.png', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 25, 'category_id' => 16, 'product_code' => 'PRD-MRM-016', 'name' => 'Wastafel Marmer Tamper Prik Hitam', 'material_type' => 'marmer', 'dimension_spec' => 'D: 40 cm, T: 15 cm', 'finishing_type' => 'Honed Doff', 'ready_stock' => 15, 'safety_stock' => 5, 'standard_cogs' => 200000.00, 'selling_price' => 300000.00, 'image_path' => 'images/products/wastafel-marmer-tamper-prik-hitam-1787846641.png', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 26, 'category_id' => 20, 'product_code' => 'PRD-MRM-017', 'name' => 'Pedistal Kotak Alur', 'material_type' => 'marmer', 'dimension_spec' => 'P: 40 cm, L: 40 cm, T: 90 cm', 'finishing_type' => 'Polish Bowl', 'ready_stock' => 15, 'safety_stock' => 5, 'standard_cogs' => 800000.00, 'selling_price' => 1200000.00, 'image_path' => 'images/products/pedistal-kotak-alur-1787846730.png', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 27, 'category_id' => 19, 'product_code' => 'PRD-ONX-014', 'name' => 'Tempat Payung Kaki Andesit', 'material_type' => 'marmer', 'dimension_spec' => 'D: 20 cm, T: 60 cm', 'finishing_type' => 'Full Polish', 'ready_stock' => 5, 'safety_stock' => 2, 'standard_cogs' => 160000.00, 'selling_price' => 250000.00, 'image_path' => 'images/products/tempat-payung-kaki-andesit-1787846872.png', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 28, 'category_id' => 16, 'product_code' => 'PRD-MRM-018', 'name' => 'Wastafel Marmer Full Polish Cream', 'material_type' => 'marmer', 'dimension_spec' => 'D: 45 cm, T: 12 cm', 'finishing_type' => 'Hi-Glossy', 'ready_stock' => 15, 'safety_stock' => 5, 'standard_cogs' => 195000.00, 'selling_price' => 300000.00, 'image_path' => 'images/products/wastafel-marmer-full-polish-cream-1787847011.png', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 29, 'category_id' => 16, 'product_code' => 'PRD-MRM-019', 'name' => 'Oval Abu Marmer', 'material_type' => 'marmer', 'dimension_spec' => 'P: 60 cm, L: 40 cm, T: 12 cm', 'finishing_type' => 'Hi-Glossy', 'ready_stock' => 15, 'safety_stock' => 5, 'standard_cogs' => 265000.00, 'selling_price' => 410000.00, 'image_path' => 'images/products/oval-abu-marmer-1787847091.png', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 30, 'category_id' => 20, 'product_code' => 'PRD-MRM-020', 'name' => 'Pedistal Kotak Marmo', 'material_type' => 'marmer', 'dimension_spec' => 'P: 40 cm, L: 40 cm, T: 90 cm', 'finishing_type' => 'Polish Atas', 'ready_stock' => 15, 'safety_stock' => 5, 'standard_cogs' => 720000.00, 'selling_price' => 1100000.00, 'image_path' => 'images/products/pedistal-kotak-marmo-1787847203.png', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 31, 'category_id' => 16, 'product_code' => 'PRD-MRM-021', 'name' => 'Kotak Marmo Abu', 'material_type' => 'marmer', 'dimension_spec' => 'P: 40 cm, L: 40 cm, T: 12 cm', 'finishing_type' => 'Hi-Glossy', 'ready_stock' => 15, 'safety_stock' => 5, 'standard_cogs' => 225000.00, 'selling_price' => 350000.00, 'image_path' => 'images/products/kotak-marmo-abu-1787847285.png', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 32, 'category_id' => 16, 'product_code' => 'PRD-MRM-022', 'name' => 'Oval Marmer', 'material_type' => 'marmer', 'dimension_spec' => 'P: 45 cm, L: 35 cm, T: 15 cm', 'finishing_type' => 'Hi-Glossy', 'ready_stock' => 15, 'safety_stock' => 5, 'standard_cogs' => 225000.00, 'selling_price' => 350000.00, 'image_path' => 'images/products/oval-marmer-1787847376.png', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 33, 'category_id' => 16, 'product_code' => 'PRD-MRM-023', 'name' => 'Wastafel Kotak Full Polish', 'material_type' => 'marmer', 'dimension_spec' => 'P: 40 cm, L: 30 cm, T: 12 cm', 'finishing_type' => 'Hi-Glossy', 'ready_stock' => 20, 'safety_stock' => 10, 'standard_cogs' => 200000.00, 'selling_price' => 300000.00, 'image_path' => 'images/products/wastafel-kotak-full-polish-1787847498.png', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 34, 'category_id' => 21, 'product_code' => 'PRD-ONX-015', 'name' => 'Wastafel Onix Samset Dop', 'material_type' => 'onix', 'dimension_spec' => 'D: 40 cm, T: 15 cm', 'finishing_type' => 'Honed Doff', 'ready_stock' => 20, 'safety_stock' => 5, 'standard_cogs' => 295000.00, 'selling_price' => 450000.00, 'image_path' => 'images/products/wastafel-onix-samset-dop-1787847561.png', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 35, 'category_id' => 16, 'product_code' => 'PRD-MRM-025', 'name' => 'Kotak Luncur Marmer', 'material_type' => 'marmer', 'dimension_spec' => 'P: 40 cm, L: 30 cm, T: 10 cm', 'finishing_type' => 'Hi-Glossy', 'ready_stock' => 15, 'safety_stock' => 5, 'standard_cogs' => 180000.00, 'selling_price' => 260000.00, 'image_path' => 'images/products/kotak-luncur-marmer-1787847630.png', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 36, 'category_id' => 16, 'product_code' => 'PRD-MRM-026', 'name' => 'Drum Full Marmo', 'material_type' => 'marmer', 'dimension_spec' => 'D: 40 cm, T: 15 cm', 'finishing_type' => 'Hi-Glossy', 'ready_stock' => 15, 'safety_stock' => 5, 'standard_cogs' => 195000.00, 'selling_price' => 300000.00, 'image_path' => 'images/products/drum-full-marmo-1787847694.png', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 37, 'category_id' => 16, 'product_code' => 'PRD-MRM-027', 'name' => 'Kotak Acak Hitam', 'material_type' => 'marmer', 'dimension_spec' => 'P: 50 cm, L: 40 cm, T: 15 cm', 'finishing_type' => 'Polished Atas & Dalam / Acak Luar', 'ready_stock' => 15, 'safety_stock' => 5, 'standard_cogs' => 360000.00, 'selling_price' => 550000.00, 'image_path' => 'images/products/kotak-acak-hitam.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 38, 'category_id' => 16, 'product_code' => 'PRD-MRM-028', 'name' => 'Oval Andesit Full Dop Hitam', 'material_type' => 'marmer', 'dimension_spec' => 'P: 70 cm, L: 35 cm, T: 13 cm', 'finishing_type' => 'Honed Matte Full Doff', 'ready_stock' => 15, 'safety_stock' => 5, 'standard_cogs' => 330000.00, 'selling_price' => 500000.00, 'image_path' => 'images/products/oval-andesit-full-dop-hitam.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 39, 'category_id' => 16, 'product_code' => 'PRD-MRM-029', 'name' => 'Wastafel Marmer Full Polish Cream (45x15)', 'material_type' => 'marmer', 'dimension_spec' => 'D: 45 cm, T: 15 cm', 'finishing_type' => 'Hi-Glossy', 'ready_stock' => 15, 'safety_stock' => 5, 'standard_cogs' => 225000.00, 'selling_price' => 350000.00, 'image_path' => 'images/products/wastafel-marmer-full-polish-cream-45x15.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 40, 'category_id' => 20, 'product_code' => 'PRD-MRM-030', 'name' => 'Pedistal Marmo Andesit', 'material_type' => 'marmer', 'dimension_spec' => 'D: 40 cm, T: 90 cm', 'finishing_type' => 'Polish Bowl Atas / Pahat Marmo Alur Kaki', 'ready_stock' => 10, 'safety_stock' => 3, 'standard_cogs' => 950000.00, 'selling_price' => 1500000.00, 'image_path' => 'images/products/pedistal-marmo-andesit.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 41, 'category_id' => 20, 'product_code' => 'PRD-ONX-016', 'name' => 'Pedistal Onyx Full Polish', 'material_type' => 'onix', 'dimension_spec' => 'D: 40 cm, T: 90 cm', 'finishing_type' => 'Super Hi-Glossy Translucent', 'ready_stock' => 8, 'safety_stock' => 2, 'standard_cogs' => 1100000.00, 'selling_price' => 1750000.00, 'image_path' => 'images/products/pedistal-onyx-full-polish.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 42, 'category_id' => 20, 'product_code' => 'PRD-MRM-031', 'name' => 'Pedistal Kendang Polis - Alur Geret', 'material_type' => 'marmer', 'dimension_spec' => 'D: 45 cm, T: 90 cm', 'finishing_type' => 'Top Polish x Alur Geret Luar', 'ready_stock' => 10, 'safety_stock' => 3, 'standard_cogs' => 950000.00, 'selling_price' => 1500000.00, 'image_path' => 'images/products/pedistal-kendang-polis-alur-geret.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 43, 'category_id' => 20, 'product_code' => 'PRD-MRM-032', 'name' => 'Pedistal Andesit', 'material_type' => 'marmer', 'dimension_spec' => 'D: 40 cm, T: 90 cm', 'finishing_type' => 'Full Polish Kerucut', 'ready_stock' => 10, 'safety_stock' => 3, 'standard_cogs' => 1100000.00, 'selling_price' => 1700000.00, 'image_path' => 'images/products/pedistal-andesit.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 44, 'category_id' => 20, 'product_code' => 'PRD-PDS-PA-01', 'name' => 'Pedistal Wastafel Batu Kali Alami', 'material_type' => 'batu_kali', 'dimension_spec' => 'D: 45-55 cm, T: 80 cm', 'finishing_type' => 'Alami Luar x Polish Bowl Dalam', 'ready_stock' => 6, 'safety_stock' => 2, 'standard_cogs' => 700000.00, 'selling_price' => 1200000.00, 'image_path' => 'images/products/pedistal-batu-kali.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 45, 'category_id' => 19, 'product_code' => 'PRD-LMP-PA-02', 'name' => 'Lampu Taman Batu Kali Alami', 'material_type' => 'batu_kali', 'dimension_spec' => 'T: 30-50 cm, D: 20-25 cm', 'finishing_type' => 'Natural x Celah Keratan Garis Cahaya', 'ready_stock' => 20, 'safety_stock' => 5, 'standard_cogs' => 120000.00, 'selling_price' => 210000.00, 'image_path' => 'images/products/lampu-taman-batu-kali.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 46, 'category_id' => 19, 'product_code' => 'PRD-ACC-PA-05', 'name' => 'Tempat Mandi Burung Batu Kali (Bird Bath)', 'material_type' => 'batu_kali', 'dimension_spec' => 'D: 35-45 cm, T: 50-60 cm', 'finishing_type' => 'Alami Luar x Polish Halus Mangkok Dalam', 'ready_stock' => 15, 'safety_stock' => 4, 'standard_cogs' => 180000.00, 'selling_price' => 320000.00, 'image_path' => 'images/products/tempat-mandi-burung-batu-kali.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 47, 'category_id' => 19, 'product_code' => 'PRD-ACC-PA-06', 'name' => 'Tempat Sabun Batang Alami (Soap Dish)', 'material_type' => 'batu_kali', 'dimension_spec' => 'P: 13-15 cm, L: 9-11 cm, T: 2-3 cm', 'finishing_type' => 'Oval Polish Cekung x 3 Lubang Air', 'ready_stock' => 30, 'safety_stock' => 8, 'standard_cogs' => 20000.00, 'selling_price' => 40000.00, 'image_path' => 'images/products/tempat-sabun-batang-batu-kali.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 48, 'category_id' => 19, 'product_code' => 'PRD-ACC-PA-07', 'name' => 'Tempat Lilin Batu Alam & Marmer (Candle Holder)', 'material_type' => 'batu_kali', 'dimension_spec' => 'D: 8-10 cm, T: 7-9 cm (Lubang D: 4-5 cm)', 'finishing_type' => 'Natural Riverstone & Rockface Marble x Core Bore', 'ready_stock' => 25, 'safety_stock' => 6, 'standard_cogs' => 18000.00, 'selling_price' => 35000.00, 'image_path' => 'images/products/tempat-lilin-batu-alam.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 49, 'category_id' => 19, 'product_code' => 'PRD-ACC-PA-08', 'name' => 'Toples Permen & Serbaguna Batu Kali (Stone Jar)', 'material_type' => 'batu_kali', 'dimension_spec' => 'D: 15-20 cm, T: 15-18 cm', 'finishing_type' => 'Natural Riverstone Outside x Polished Lid & Cavity', 'ready_stock' => 20, 'safety_stock' => 5, 'standard_cogs' => 55000.00, 'selling_price' => 95000.00, 'image_path' => 'images/products/toples-batu-kali.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 50, 'category_id' => 19, 'product_code' => 'PRD-LMP-PA-03', 'name' => 'Lampu Taman Batu Erosi Natural', 'material_type' => 'batu_kali', 'dimension_spec' => 'T: 40-50 cm, D: 20-25 cm', 'finishing_type' => 'Rough Natural Erosion x Celah Garis Cahaya', 'ready_stock' => 16, 'safety_stock' => 4, 'standard_cogs' => 135000.00, 'selling_price' => 235000.00, 'image_path' => 'images/products/lampu-taman-batu-erosi.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 51, 'category_id' => 19, 'product_code' => 'PRD-ACC-PA-09', 'name' => 'Pot Bunga & Tanaman Batu Kali Alami (Riverstone Planter)', 'material_type' => 'batu_kali', 'dimension_spec' => 'D: 25-35 cm, T: 20-25 cm', 'finishing_type' => 'Natural Riverstone Crust x Hollow Cavity & Drain Hole', 'ready_stock' => 20, 'safety_stock' => 5, 'standard_cogs' => 65000.00, 'selling_price' => 120000.00, 'image_path' => 'images/products/pot-bunga-batu-kali.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 52, 'category_id' => 19, 'product_code' => 'PRD-ACC-PA-10', 'name' => 'Bak Ikan & Gentong Air Batu Kali Alami (Riverstone Water Basin)', 'material_type' => 'batu_kali', 'dimension_spec' => 'P: 60-80 cm, L: 45-60 cm, T: 35-45 cm', 'finishing_type' => 'Natural Riverstone Outside x Smooth Honed Interior', 'ready_stock' => 8, 'safety_stock' => 2, 'standard_cogs' => 350000.00, 'selling_price' => 620000.00, 'image_path' => 'images/products/bak-ikan-gentong-batu-kali.webp', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 53, 'category_id' => 22, 'product_code' => 'PRD-MJA-PA-03', 'name' => 'Kursi Stool Taman Batu Kali Alami (Riverstone Stool)', 'material_type' => 'batu_kali', 'dimension_spec' => 'D: 30-40 cm, T: 45-55 cm', 'finishing_type' => 'Natural Riverstone Outside x Flat Polished Top', 'ready_stock' => 25, 'safety_stock' => 6, 'standard_cogs' => 95000.00, 'selling_price' => 175000.00, 'image_path' => 'images/products/kursi-stool-taman-batu-kali.webp', 'created_at' => $now, 'updated_at' => $now],
        ], ['id'], ['category_id', 'product_code', 'name', 'material_type', 'dimension_spec', 'finishing_type', 'ready_stock', 'safety_stock', 'standard_cogs', 'selling_price', 'image_path', 'updated_at']);

        // 6. SEED CUSTOMERS
        DB::table('customers')->insertOrIgnore([
            ['id' => 1, 'customer_code' => 'CUST-BALI-01', 'name' => 'Bapak Ketut Sukerta', 'company_name' => 'Bali Natural Living Gallery', 'phone' => '081338877665', 'email' => 'ketut@balinaturalliving.com', 'address' => 'Jl. Sunset Road No. 88, Seminyak', 'city' => 'Badung - Bali', 'customer_type' => 'distributor_ekspor', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'customer_code' => 'CUST-SBY-02', 'name' => 'Ibu Hendra Wijaya', 'company_name' => 'PT Citra Griya Indah', 'phone' => '081231122334', 'email' => 'purchasing@citragriya.co.id', 'address' => 'Jl. Raya Darmo Permai III No. 12', 'city' => 'Surabaya', 'customer_type' => 'kontraktor_arsitektur', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'customer_code' => 'CUST-KDR-03', 'name' => 'Pak Gunawan', 'company_name' => 'Toko Marmer Sumber Rejeki', 'phone' => '081255667788', 'email' => 'sumberrejeki.kdr@gmail.com', 'address' => 'Jl. Dhoho No. 45', 'city' => 'Kediri', 'customer_type' => 'retail', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 7. SEED WORK ORDERS (17 Bulan Data Observasi Lengkap dari Excel 'Hasil Produksi Cahaya Onix.xlsx')
        $defaultUserId = DB::table('users')->value('id') ?? 1;
        $ownerPaId = DB::table('users')->where('email', 'like', '%putraabadi%')->value('id') ?? $defaultUserId;

        $historicalProduction = [
            // 2025
            ['m' => '2025-01', 'qty' => 590, 'spk' => 'SPK-CO-202501-001'],
            ['m' => '2025-02', 'qty' => 640, 'spk' => 'SPK-CO-202502-002'],
            ['m' => '2025-03', 'qty' => 570, 'spk' => 'SPK-CO-202503-003'],
            ['m' => '2025-04', 'qty' => 620, 'spk' => 'SPK-CO-202504-004'],
            ['m' => '2025-05', 'qty' => 680, 'spk' => 'SPK-CO-202505-005'],
            ['m' => '2025-06', 'qty' => 610, 'spk' => 'SPK-CO-202506-006'],
            ['m' => '2025-07', 'qty' => 665, 'spk' => 'SPK-CO-202507-007'],
            ['m' => '2025-08', 'qty' => 545, 'spk' => 'SPK-CO-202508-008'],
            ['m' => '2025-09', 'qty' => 600, 'spk' => 'SPK-CO-202509-009'],
            ['m' => '2025-10', 'qty' => 690, 'spk' => 'SPK-CO-202510-010'],
            ['m' => '2025-11', 'qty' => 650, 'spk' => 'SPK-CO-202511-011'],
            ['m' => '2025-12', 'qty' => 615, 'spk' => 'SPK-CO-202512-012'],
            // 2026
            ['m' => '2026-01', 'qty' => 625, 'spk' => 'SPK-CO-202601-013'],
            ['m' => '2026-02', 'qty' => 700, 'spk' => 'SPK-CO-202602-014'],
            ['m' => '2026-03', 'qty' => 550, 'spk' => 'SPK-CO-202603-015'],
            ['m' => '2026-04', 'qty' => 675, 'spk' => 'SPK-CO-202604-016'],
            ['m' => '2026-05', 'qty' => 690, 'spk' => 'SPK-CO-202605-017'],
        ];

        foreach ($historicalProduction as $prod) {
            $startDate = Carbon::createFromFormat('Y-m', $prod['m'])->startOfMonth()->toDateString();
            $endDate = Carbon::createFromFormat('Y-m', $prod['m'])->endOfMonth()->toDateString();
            $isLast = ($prod['m'] === '2026-05');

            DB::table('work_orders')->upsert([
                [
                    'spk_number' => $prod['spk'],
                    'product_id' => 4, // Wastafel Marmer Putih B1
                    'customer_id' => 1,
                    'target_quantity' => $prod['qty'],
                    'completed_quantity' => $isLast ? 14 : $prod['qty'],
                    'scrap_quantity' => 0,
                    'status' => $isLast ? 'qc_phase' : 'completed',
                    'priority' => 'normal',
                    'start_date' => $startDate,
                    'due_date' => $endDate,
                    'completion_date' => $isLast ? null : $endDate,
                    'notes' => 'Batch produksi bulanan ' . Carbon::parse($startDate)->translatedFormat('F Y') . ' (Data Observasi Lapangan)',
                    'created_by' => $defaultUserId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            ], ['spk_number'], ['product_id', 'customer_id', 'target_quantity', 'completed_quantity', 'scrap_quantity', 'status', 'priority', 'start_date', 'due_date', 'completion_date', 'notes', 'created_by', 'updated_at']);
        }

        // Additional Work Orders for Active Kanban Flow
        DB::table('work_orders')->upsert([
            ['spk_number' => 'SPK-CO-202608-008', 'product_id' => 9, 'customer_id' => 1, 'target_quantity' => 5, 'completed_quantity' => 0, 'scrap_quantity' => 0, 'status' => 'scheduled', 'priority' => 'urgent', 'start_date' => '2026-08-20', 'due_date' => '2026-08-30', 'completion_date' => null, 'notes' => 'Pesanan khusus 5 unit wastafel onyx tembus cahaya ke Bali', 'created_by' => $defaultUserId, 'created_at' => $now, 'updated_at' => $now],
            ['spk_number' => 'SPK-CO-202608-007', 'product_id' => 4, 'customer_id' => 2, 'target_quantity' => 14, 'completed_quantity' => 0, 'scrap_quantity' => 0, 'status' => 'in_progress', 'priority' => 'normal', 'start_date' => '2026-08-18', 'due_date' => '2026-08-26', 'completion_date' => null, 'notes' => 'Pembelahan balok marmer putih di mesin slep utama (Kapasitas 14 Biji/Hari)', 'created_by' => $defaultUserId, 'created_at' => $now, 'updated_at' => $now],
            ['spk_number' => 'SPK-CO-202608-006', 'product_id' => 6, 'customer_id' => 3, 'target_quantity' => 8, 'completed_quantity' => 4, 'scrap_quantity' => 0, 'status' => 'in_progress', 'priority' => 'normal', 'start_date' => '2026-08-15', 'due_date' => '2026-08-24', 'completion_date' => null, 'notes' => 'Pembubutan bentuk luar & lubang afur wastafel batu kali di Mesin Bubut 1-4', 'created_by' => $defaultUserId, 'created_at' => $now, 'updated_at' => $now],
            ['spk_number' => 'SPK-PA-202604-046', 'product_id' => 5, 'customer_id' => 2, 'target_quantity' => 2650, 'completed_quantity' => 2650, 'scrap_quantity' => 20, 'status' => 'completed', 'priority' => 'normal', 'start_date' => '2026-04-01', 'due_date' => '2026-04-30', 'completion_date' => '2026-04-30', 'notes' => 'Produksi 2.650 pcs Stepping Stone Batu Kali UD Putra Abadi', 'created_by' => $ownerPaId, 'created_at' => $now, 'updated_at' => $now],
        ], ['spk_number'], ['product_id', 'customer_id', 'target_quantity', 'completed_quantity', 'scrap_quantity', 'status', 'priority', 'start_date', 'due_date', 'completion_date', 'notes', 'created_by', 'updated_at']);

        // Resolve dynamic IDs
        $wo17Id = DB::table('work_orders')->where('spk_number', 'SPK-CO-202605-017')->value('id') ?? 1;
        $wo16Id = DB::table('work_orders')->where('spk_number', 'SPK-CO-202604-016')->value('id') ?? 1;
        $wo07Id = DB::table('work_orders')->where('spk_number', 'SPK-CO-202608-007')->value('id') ?? 1;
        $wo06Id = DB::table('work_orders')->where('spk_number', 'SPK-CO-202608-006')->value('id') ?? 1;
        $woPaId = DB::table('work_orders')->where('spk_number', 'SPK-PA-202604-046')->value('id') ?? 1;

        // 8. SEED PRODUCTION STEPS
        DB::table('production_steps')->upsert([
            ['id' => 1, 'work_order_id' => $wo07Id, 'step_name' => 'pemotongan_slep', 'sequence_order' => 1, 'machine_number' => 'Mesin Slep Utama', 'input_qty' => 14, 'output_qty' => 0, 'status' => 'running', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'work_order_id' => $wo07Id, 'step_name' => 'pembubutan_bentuk', 'sequence_order' => 2, 'machine_number' => 'Mesin Bubut 1-4', 'input_qty' => 14, 'output_qty' => 0, 'status' => 'pending', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'work_order_id' => $wo07Id, 'step_name' => 'penghalusan_poles', 'sequence_order' => 3, 'machine_number' => 'Mesin Bubut Poles', 'input_qty' => 14, 'output_qty' => 0, 'status' => 'pending', 'created_at' => $now, 'updated_at' => $now],

            ['id' => 4, 'work_order_id' => $wo06Id, 'step_name' => 'pemotongan_slep', 'sequence_order' => 1, 'machine_number' => 'Mesin Slep Utama', 'input_qty' => 8, 'output_qty' => 8, 'status' => 'completed', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'work_order_id' => $wo06Id, 'step_name' => 'pembubutan_bentuk', 'sequence_order' => 2, 'machine_number' => 'Mesin Bubut 1-4', 'input_qty' => 8, 'output_qty' => 4, 'status' => 'running', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'work_order_id' => $wo06Id, 'step_name' => 'penghalusan_poles', 'sequence_order' => 3, 'machine_number' => 'Mesin Bubut Poles', 'input_qty' => 8, 'output_qty' => 0, 'status' => 'pending', 'created_at' => $now, 'updated_at' => $now],

            ['id' => 7, 'work_order_id' => $wo17Id, 'step_name' => 'pemotongan_slep', 'sequence_order' => 1, 'machine_number' => 'Mesin Slep Utama', 'input_qty' => 14, 'output_qty' => 14, 'status' => 'completed', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 8, 'work_order_id' => $wo17Id, 'step_name' => 'pembubutan_bentuk', 'sequence_order' => 2, 'machine_number' => 'Mesin Bubut 1-4', 'input_qty' => 14, 'output_qty' => 14, 'status' => 'completed', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 9, 'work_order_id' => $wo17Id, 'step_name' => 'penghalusan_poles', 'sequence_order' => 3, 'machine_number' => 'Mesin Bubut Poles', 'input_qty' => 14, 'output_qty' => 14, 'status' => 'running', 'created_at' => $now, 'updated_at' => $now],
        ], ['id'], ['work_order_id', 'step_name', 'sequence_order', 'machine_number', 'input_qty', 'output_qty', 'status', 'updated_at']);

        // 9. SEED QC LOGS (Inspeksi 2-Tahap Sesuai Temuan Pengujian Form 1.1)
        DB::table('qc_logs')->upsert([
            [
                'id' => 1,
                'work_order_id' => $wo17Id,
                'step_id' => 7,
                'stage' => 'qc1_raw_shape',
                'inspected_quantity' => 14,
                'pass_quantity' => 14,
                'rework_quantity' => 0,
                'scrap_quantity' => 0,
                'defect_type' => null,
                'rework_action' => null,
                'inspection_date' => '2026-05-05',
                'notes' => 'QC Tahap 1: Bentuk mentah simetris, urat batu alami Campurdarat kokoh tanpa retak tembus.',
                'inspector_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'work_order_id' => $wo17Id,
                'step_id' => 9,
                'stage' => 'qc2_final_polish',
                'inspected_quantity' => 14,
                'pass_quantity' => 13,
                'rework_quantity' => 1,
                'scrap_quantity' => 0,
                'defect_type' => 'Pori-pori mikro pada bibir luar wastafel',
                'rework_action' => 'Tambal resin bening & poles ulang 15 menit',
                'inspection_date' => '2026-05-10',
                'notes' => 'QC Tahap 2: 13 unit lolos standar Hi-Glossy, 1 unit perlu sedikit tambal resin di bibir luar.',
                'inspector_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ], ['id'], ['work_order_id', 'step_id', 'stage', 'inspected_quantity', 'pass_quantity', 'rework_quantity', 'scrap_quantity', 'defect_type', 'rework_action', 'inspection_date', 'notes', 'inspector_id', 'updated_at']);

        // 10. SEED WASTE LOGS (Hilirisasi Residu Sesuai Temuan 390 mnt/mgg)
        DB::table('waste_logs')->upsert([
            ['id' => 1, 'work_order_id' => $wo16Id, 'step_id' => null, 'waste_type' => 'serbuk_bubut_sludge', 'weight_kg' => 120.00, 'volume_m3' => 0.080, 'reuse_status' => 'disimpan_daur_ulang', 'notes' => 'Lumpur campuran air dan serbuk marmer dari pemotongan mesin slep UD Cahaya Onix.', 'logged_at' => '2026-04-24', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'work_order_id' => $wo16Id, 'step_id' => null, 'waste_type' => 'bongkahan_urukan', 'weight_kg' => 85.00, 'volume_m3' => 0.055, 'reuse_status' => 'disimpan_daur_ulang', 'notes' => 'Sisa potongan sudut blok marmer untuk urukan pondasi bangunan.', 'logged_at' => '2026-04-25', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'work_order_id' => $woPaId, 'step_id' => null, 'waste_type' => 'sisa_layak_cladding', 'weight_kg' => 240.00, 'volume_m3' => 0.160, 'reuse_status' => 'disimpan_daur_ulang', 'notes' => 'Sisa potongan pipih batu kali dipilah untuk hiasan dinding / wall cladding (UD Putra Abadi).', 'logged_at' => '2026-04-21', 'created_at' => $now, 'updated_at' => $now],
        ], ['id'], ['work_order_id', 'waste_type', 'weight_kg', 'volume_m3', 'reuse_status', 'notes', 'logged_at', 'updated_at']);

        // 11. SEED STOCK TRANSACTIONS (Data Pembelian dari Excel 'Bahan Baku Cahaya Onix.xlsx')
        $rawPurchases = [
            ['m' => '2026-01', 'putih' => 20, 'hitam' => 10],
            ['m' => '2026-02', 'putih' => 22, 'hitam' => 11],
            ['m' => '2026-03', 'putih' => 18, 'hitam' => 9],
            ['m' => '2026-04', 'putih' => 21, 'hitam' => 10],
            ['m' => '2026-05', 'putih' => 23, 'hitam' => 11],
            ['m' => '2026-06', 'putih' => 20, 'hitam' => 10],
            ['m' => '2026-07', 'putih' => 22, 'hitam' => 11],
            ['m' => '2026-08', 'putih' => 19, 'hitam' => 10],
        ];

        $trxId = 1;
        $runningStockPutih = 20.0;
        foreach ($rawPurchases as $p) {
            // IN: Marmer Putih
            DB::table('stock_transactions')->upsert([
                [
                    'id' => $trxId++,
                    'transaction_code' => 'TRX-IN-MRM-' . str_replace('-', '', $p['m']) . '-01',
                    'material_id' => 5, // Marmer Putih
                    'user_id' => 2,
                    'type' => 'in',
                    'quantity' => $p['putih'],
                    'unit' => 'Balok',
                    'before_stock' => $runningStockPutih,
                    'after_stock' => $runningStockPutih + $p['putih'],
                    'reference_type' => 'supplier_invoice',
                    'reference_id' => 1,
                    'notes' => 'Penerimaan ' . $p['putih'] . ' Balok Batu Marmer Putih dari Tambang Campurdarat',
                    'transaction_date' => $p['m'] . '-05',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            ], ['id'], ['transaction_code', 'material_id', 'user_id', 'type', 'quantity', 'unit', 'before_stock', 'after_stock', 'reference_type', 'reference_id', 'notes', 'transaction_date', 'updated_at']);

            $runningStockPutih += $p['putih'];

            // OUT: Pengeluaran ke Mesin Slep
            $outQty = round($p['putih'] * 0.85); // 85% diproses
            DB::table('stock_transactions')->upsert([
                [
                    'id' => $trxId++,
                    'transaction_code' => 'TRX-OUT-MRM-' . str_replace('-', '', $p['m']) . '-02',
                    'material_id' => 5,
                    'user_id' => 2,
                    'type' => 'out',
                    'quantity' => $outQty,
                    'unit' => 'Balok',
                    'before_stock' => $runningStockPutih,
                    'after_stock' => $runningStockPutih - $outQty,
                    'reference_type' => 'work_order',
                    'reference_id' => 1,
                    'notes' => 'Pengeluaran ' . $outQty . ' Balok ke Mesin Slep & Bubut Lantai Produksi',
                    'transaction_date' => $p['m'] . '-18',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            ], ['id'], ['transaction_code', 'material_id', 'user_id', 'type', 'quantity', 'unit', 'before_stock', 'after_stock', 'reference_type', 'reference_id', 'notes', 'transaction_date', 'updated_at']);

            $runningStockPutih -= $outQty;
        }

        // 12. SEED SHIPMENTS (Surat Jalan & Packing Peti Kayu)
        DB::table('shipments')->upsert([
            [
                'id' => 1,
                'shipment_code' => 'SJ-202604-001',
                'work_order_id' => $wo16Id,
                'customer_id' => 1,
                'expedition_name' => 'Ekspedisi Bali Mandiri Express',
                'tracking_number' => 'RESI-BALI-99881',
                'driver_name' => 'Pak Yatno',
                'vehicle_plate' => 'AG 8899 AB',
                'packing_verified' => true,
                'shipment_date' => '2026-04-28',
                'delivery_status' => 'delivered',
                'notes' => 'Pengiriman 14 unit wastafel marmer putih ke Seminyak Bali - Packing Peti Kayu Lolos QC',
                'created_by' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'shipment_code' => 'SJ-202605-002',
                'work_order_id' => $woPaId,
                'customer_id' => 2,
                'expedition_name' => 'Kobra Express Surabaya',
                'tracking_number' => 'RESI-KOBRA-11029',
                'driver_name' => 'Pak Sugeng',
                'vehicle_plate' => 'L 9021 UX',
                'packing_verified' => true,
                'shipment_date' => '2026-05-12',
                'delivery_status' => 'in_transit',
                'notes' => 'Pengiriman 2.650 pcs stepping stone batu kali untuk proyek villa Surabaya',
                'created_by' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ], ['id'], ['shipment_code', 'work_order_id', 'customer_id', 'expedition_name', 'tracking_number', 'driver_name', 'vehicle_plate', 'packing_verified', 'shipment_date', 'delivery_status', 'notes', 'created_by', 'updated_at']);
    }
}
