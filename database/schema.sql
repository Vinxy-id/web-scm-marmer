-- ============================================================================
-- SKEMA BASIS DATA RELASIONAL (DDL)
-- SISTEM INFORMASI E-SUPPLY CHAIN MANAGEMENT (E-SCM) IKM MARMER TULUNGAGUNG
-- Studi Kasus: Klaster IKM Marmer & Onyx (UD Cahaya Onix & UD Putra Abadi)
-- Database Engine: InnoDB (MySQL 8.0+ / MariaDB 10.6+)
-- Charset: utf8mb4 / Collation: utf8mb4_unicode_ci
-- ============================================================================

CREATE DATABASE IF NOT EXISTS `db_escm_marmer` 
DEFAULT CHARACTER SET utf8mb4 
DEFAULT COLLATE utf8mb4_unicode_ci;

USE `db_escm_marmer`;

SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------------------------------------------------------
-- 1. TABEL PENGGUNA & RBAC (users)
-- ----------------------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'owner', 'gudang', 'produksi', 'distribusi') NOT NULL DEFAULT 'gudang',
    `phone` VARCHAR(20) NULL,
    `ikm_name` VARCHAR(100) NOT NULL DEFAULT 'UD Cahaya Onix',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `remember_token` VARCHAR(100) NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_users_role` (`role`),
    INDEX `idx_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 2. TABEL PEMASOK / PENAMBANG BAHAN BAKU (suppliers)
-- ----------------------------------------------------------------------------
DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE `suppliers` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `supplier_code` VARCHAR(50) NOT NULL UNIQUE,
    `name` VARCHAR(150) NOT NULL,
    `contact_person` VARCHAR(100) NULL,
    `phone` VARCHAR(20) NULL,
    `address` TEXT NULL,
    `quarry_location` VARCHAR(150) NULL COMMENT 'Lokasi Tambang (misal: Besole, Campurdarat)',
    `material_category` VARCHAR(100) NULL COMMENT 'Jenis bahan yang disuplai',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_suppliers_code` (`supplier_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 3. TABEL KATEGORI PRODUK & BAHAN (categories)
-- ----------------------------------------------------------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `type` ENUM('material', 'product') NOT NULL DEFAULT 'product',
    `description` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 4. TABEL MASTER BAHAN BAKU (materials)
-- ----------------------------------------------------------------------------
DROP TABLE IF EXISTS `materials`;
CREATE TABLE `materials` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `supplier_id` BIGINT UNSIGNED NULL,
    `material_code` VARCHAR(50) NOT NULL UNIQUE,
    `name` VARCHAR(150) NOT NULL,
    `type` ENUM('marmer', 'onix', 'batu_kali', 'bahan_penolong') NOT NULL DEFAULT 'marmer',
    `grade` ENUM('grade_a_super', 'grade_b_standard', 'grade_c_ekonomis') NOT NULL DEFAULT 'grade_b_standard',
    `dimension_info` VARCHAR(100) NULL COMMENT 'Ukuran bongkahan (misal: 60x60x80 cm)',
    `unit` VARCHAR(20) NOT NULL DEFAULT 'blok' COMMENT 'blok, ton, m3, sak, kg',
    `current_stock` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `minimum_stock` DECIMAL(12, 2) NOT NULL DEFAULT 5.00 COMMENT 'Threshold alert stok minimum',
    `unit_cost` DECIMAL(15, 2) NOT NULL DEFAULT 0.00 COMMENT 'Harga perolehan bahan',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_materials_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX `idx_materials_code` (`material_code`),
    INDEX `idx_materials_stock` (`current_stock`, `minimum_stock`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 5. TABEL TRANSAKSI ALIRAN MATERIAL (stock_transactions)
-- ----------------------------------------------------------------------------
DROP TABLE IF EXISTS `stock_transactions`;
CREATE TABLE `stock_transactions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `transaction_code` VARCHAR(50) NOT NULL UNIQUE,
    `material_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `type` ENUM('opening', 'in', 'out', 'consign') NOT NULL,
    `quantity` DECIMAL(12, 2) NOT NULL,
    `unit` VARCHAR(20) NOT NULL DEFAULT 'blok',
    `before_stock` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `after_stock` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `reference_type` VARCHAR(50) NULL COMMENT 'misal: surat_jalan_tambang, spk_produksi, opname',
    `reference_id` BIGINT UNSIGNED NULL,
    `notes` TEXT NULL,
    `transaction_date` DATE NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_stock_trans_material` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_stock_trans_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX `idx_trans_code` (`transaction_code`),
    INDEX `idx_trans_type_date` (`type`, `transaction_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 6. TABEL MASTER PRODUK JADI (products)
-- ----------------------------------------------------------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `category_id` BIGINT UNSIGNED NOT NULL,
    `product_code` VARCHAR(50) NOT NULL UNIQUE,
    `name` VARCHAR(150) NOT NULL,
    `material_type` ENUM('marmer', 'onix', 'batu_kali', 'kombinasi') NOT NULL DEFAULT 'marmer',
    `dimension_spec` VARCHAR(100) NULL COMMENT 'Contoh: D=40cm T=15cm',
    `finishing_type` VARCHAR(50) NOT NULL DEFAULT 'Hi-Glossy' COMMENT 'Hi-Glossy, Doff, Bakar, Alami',
    `ready_stock` INT NOT NULL DEFAULT 0,
    `safety_stock` INT NOT NULL DEFAULT 5,
    `standard_cogs` DECIMAL(15, 2) NOT NULL DEFAULT 0.00 COMMENT 'HPP Standar',
    `selling_price` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `image_path` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX `idx_products_code` (`product_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 7. TABEL PELANGGAN / BUYER (customers)
-- ----------------------------------------------------------------------------
DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `customer_code` VARCHAR(50) NOT NULL UNIQUE,
    `name` VARCHAR(150) NOT NULL,
    `company_name` VARCHAR(150) NULL,
    `phone` VARCHAR(20) NOT NULL,
    `email` VARCHAR(100) NULL,
    `address` TEXT NOT NULL,
    `city` VARCHAR(100) NOT NULL,
    `customer_type` ENUM('retail', 'kontraktor_arsitektur', 'distributor_ekspor') NOT NULL DEFAULT 'retail',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_customers_code` (`customer_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 8. TABEL SURAT PERINTAH KERJA (work_orders)
-- ----------------------------------------------------------------------------
DROP TABLE IF EXISTS `work_orders`;
CREATE TABLE `work_orders` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `spk_number` VARCHAR(50) NOT NULL UNIQUE,
    `product_id` BIGINT UNSIGNED NOT NULL,
    `customer_id` BIGINT UNSIGNED NULL,
    `target_quantity` INT NOT NULL DEFAULT 1,
    `completed_quantity` INT NOT NULL DEFAULT 0,
    `scrap_quantity` INT NOT NULL DEFAULT 0,
    `status` ENUM('draft', 'scheduled', 'in_progress', 'qc_phase', 'completed', 'cancelled') NOT NULL DEFAULT 'draft',
    `priority` ENUM('low', 'normal', 'high', 'urgent') NOT NULL DEFAULT 'normal',
    `start_date` DATE NOT NULL,
    `due_date` DATE NOT NULL,
    `completion_date` DATE NULL,
    `notes` TEXT NULL,
    `created_by` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_wo_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_wo_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk_wo_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX `idx_wo_spk_number` (`spk_number`),
    INDEX `idx_wo_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 9. TABEL TAHAPAN STASIUN PRODUKSI (production_steps)
-- ----------------------------------------------------------------------------
DROP TABLE IF EXISTS `production_steps`;
CREATE TABLE `production_steps` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `work_order_id` BIGINT UNSIGNED NOT NULL,
    `step_name` ENUM('pembelahan_bongkahan', 'pemotongan_slep', 'pembubutan_bentuk', 'penghalusan_poles', 'inspeksi_qc') NOT NULL,
    `sequence_order` INT NOT NULL DEFAULT 1,
    `machine_number` VARCHAR(30) NULL COMMENT 'Mesin Slep / Mesin Bubut 1 s.d 7',
    `operator_id` BIGINT UNSIGNED NULL,
    `start_time` DATETIME NULL,
    `end_time` DATETIME NULL,
    `duration_minutes` INT NOT NULL DEFAULT 0,
    `input_qty` INT NOT NULL DEFAULT 0,
    `output_qty` INT NOT NULL DEFAULT 0,
    `status` ENUM('pending', 'running', 'completed') NOT NULL DEFAULT 'pending',
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_step_wo` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_step_operator` FOREIGN KEY (`operator_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX `idx_step_wo_status` (`work_order_id`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 10. TABEL QUALITY CONTROL DUA TAHAP (qc_logs)
-- ----------------------------------------------------------------------------
DROP TABLE IF EXISTS `qc_logs`;
CREATE TABLE `qc_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `work_order_id` BIGINT UNSIGNED NOT NULL,
    `step_id` BIGINT UNSIGNED NULL,
    `stage` ENUM('qc1_raw_shape', 'qc2_final_polish') NOT NULL,
    `inspector_id` BIGINT UNSIGNED NOT NULL,
    `inspected_quantity` INT NOT NULL DEFAULT 0,
    `pass_quantity` INT NOT NULL DEFAULT 0,
    `rework_quantity` INT NOT NULL DEFAULT 0,
    `scrap_quantity` INT NOT NULL DEFAULT 0,
    `defect_type` VARCHAR(150) NULL COMMENT 'Retak serat alam, lubang afur miring, permukaan tidak rata',
    `rework_action` VARCHAR(255) NULL COMMENT 'Tambal resin / poles ulang / potong ulang',
    `inspection_date` DATE NOT NULL,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_qc_wo` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_qc_step` FOREIGN KEY (`step_id`) REFERENCES `production_steps` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk_qc_inspector` FOREIGN KEY (`inspector_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX `idx_qc_stage` (`stage`, `inspection_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 11. TABEL PENCATATAN LIMBAH & SISA POTONGAN (waste_logs)
-- ----------------------------------------------------------------------------
DROP TABLE IF EXISTS `waste_logs`;
CREATE TABLE `waste_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `work_order_id` BIGINT UNSIGNED NOT NULL,
    `step_id` BIGINT UNSIGNED NULL,
    `waste_type` ENUM('sisa_layak_cladding', 'serbuk_bubut_sludge', 'bongkahan_urukan') NOT NULL,
    `weight_kg` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    `volume_m3` DECIMAL(10, 3) NULL DEFAULT 0.000,
    `reuse_status` ENUM('disimpan_daur_ulang', 'dijual_ke_pihak3', 'dibuang_ke_urukan') NOT NULL DEFAULT 'disimpan_daur_ulang',
    `notes` VARCHAR(255) NULL,
    `logged_at` DATE NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_waste_wo` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_waste_step` FOREIGN KEY (`step_id`) REFERENCES `production_steps` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX `idx_waste_type` (`waste_type`, `reuse_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 12. TABEL PENGIRIMAN & PACKING (shipments)
-- ----------------------------------------------------------------------------
DROP TABLE IF EXISTS `shipments`;
CREATE TABLE `shipments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `shipment_code` VARCHAR(50) NOT NULL UNIQUE,
    `work_order_id` BIGINT UNSIGNED NULL,
    `customer_id` BIGINT UNSIGNED NOT NULL,
    `expedition_name` VARCHAR(100) NOT NULL COMMENT 'Armada Sendiri / Truk Ekspedisi',
    `tracking_number` VARCHAR(100) NULL,
    `driver_name` VARCHAR(100) NULL,
    `vehicle_plate` VARCHAR(20) NULL,
    `packing_verified` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1 = Checklist packing kayu lolos',
    `shipment_date` DATE NOT NULL,
    `delivery_status` ENUM('packed', 'in_transit', 'delivered', 'returned') NOT NULL DEFAULT 'packed',
    `notes` TEXT NULL,
    `created_by` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_shipment_wo` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk_shipment_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_shipment_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX `idx_shipments_code` (`shipment_code`),
    INDEX `idx_shipments_status` (`delivery_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 13. TABEL LOG HASIL PERAMALAN (forecasting_logs)
-- ----------------------------------------------------------------------------
DROP TABLE IF EXISTS `forecasting_logs`;
CREATE TABLE `forecasting_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `item_type` ENUM('material', 'product') NOT NULL,
    `item_id` BIGINT UNSIGNED NOT NULL,
    `algorithm_used` VARCHAR(50) NOT NULL COMMENT 'Moving Average, Holt-Winters, ARIMA',
    `forecast_horizon_months` INT NOT NULL DEFAULT 3,
    `historical_data_points` INT NOT NULL,
    `mape_score` DECIMAL(6, 2) NOT NULL COMMENT 'Akurasi Persentase Error',
    `rmse_score` DECIMAL(10, 2) NOT NULL,
    `prediction_json` JSON NOT NULL COMMENT 'Hasil proyeksi per periode',
    `generated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_forecast_item` (`item_type`, `item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================================
-- SEED DATA AWAL (BERDASARKAN DATA SURVEI IKM TULUNGAGUNG)
-- ============================================================================

-- 1. Insert Users
INSERT INTO `users` (`name`, `email`, `password`, `role`, `phone`, `ikm_name`) VALUES
('Pak Joko Santoso', 'owner@cahayaonix.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'owner', '081234567890', 'UD Cahaya Onix'),
('Budi Setiawan', 'gudang@cahayaonix.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'gudang', '081234567891', 'UD Cahaya Onix'),
('Slamet Riyadi', 'produksi@cahayaonix.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'produksi', '081234567892', 'UD Cahaya Onix'),
('Rini Wulandari', 'distribusi@cahayaonix.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'distribusi', '081234567893', 'UD Cahaya Onix'),
('Admin Sistem', 'admin@escm-marmer.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '081234567899', 'Pusat Klaster Tulungagung');

-- 2. Insert Suppliers
INSERT INTO `suppliers` (`supplier_code`, `name`, `contact_person`, `phone`, `quarry_location`, `material_category`) VALUES
('SUP-BSL-01', 'Tambang Marmer Besole Jaya', 'Pak Sukir', '085233112233', 'Kecamatan Besole, Tulungagung', 'Bongkahan Marmer Putih & Bintik'),
('SUP-CPD-02', 'Penambang Onyx Campurdarat', 'Pak Wahyu', '085244556677', 'Campurdarat, Tulungagung', 'Bongkahan Onyx Tembus Cahaya'),
('SUP-KL-03', 'Paguyuban Batu Kali Boyolangu', 'Pak Yatno', '085277889900', 'Boyolangu, Tulungagung', 'Batu Kali Bulat & Pipih');

-- 3. Insert Categories
INSERT INTO `categories` (`name`, `slug`, `type`, `description`) VALUES
('Bahan Baku Marmer', 'bahan-baku-marmer', 'material', 'Bongkahan batu marmer tambang alam'),
('Bahan Baku Onyx', 'bahan-baku-onix', 'material', 'Bongkahan batu onyx kristal tembus pandang'),
('Bahan Baku Batu Kali', 'bahan-baku-batu-kali', 'material', 'Batu kali alam sungai Tulungagung'),
('Wastafel Marmer', 'wastafel-marmer', 'product', 'Wastafel cuci tangan batu marmer olahan'),
('Wastafel Onyx', 'wastafel-onix', 'product', 'Wastafel mewah batu onyx poles mengkilap'),
('Stepping Stone & Cladding', 'stepping-stone-cladding', 'product', 'Batu pijakan taman & pelapis dinding');

-- 4. Insert Materials
INSERT INTO `materials` (`supplier_id`, `material_code`, `name`, `type`, `grade`, `dimension_info`, `unit`, `current_stock`, `minimum_stock`, `unit_cost`) VALUES
(1, 'MAT-MRM-001', 'Bongkahan Marmer Putih Campurdarat', 'marmer', 'grade_a_super', 'P:80cm L:60cm T:60cm', 'blok', 18.00, 5.00, 450000.00),
(1, 'MAT-MRM-002', 'Bongkahan Marmer Bintik Abu', 'marmer', 'grade_b_standard', 'P:70cm L:50cm T:50cm', 'blok', 24.00, 8.00, 320000.00),
(2, 'MAT-ONX-001', 'Bongkahan Onyx Kuning Kristal', 'onix', 'grade_a_super', 'P:60cm L:45cm T:45cm', 'blok', 12.00, 4.00, 850000.00),
(3, 'MAT-BKL-001', 'Batu Kali Alam Kali Song', 'batu_kali', 'grade_b_standard', 'Diameter 35-50cm', 'blok', 35.00, 10.00, 95000.00);

-- 5. Insert Products
INSERT INTO `products` (`category_id`, `product_code`, `name`, `material_type`, `dimension_spec`, `finishing_type`, `ready_stock`, `safety_stock`, `standard_cogs`, `selling_price`) VALUES
(4, 'PRD-WSF-MRM-01', 'Wastafel Mangkok Marmer Putih D40', 'marmer', 'Diameter: 40cm, Tinggi: 15cm', 'Hi-Glossy', 14, 5, 220000.00, 450000.00),
(5, 'PRD-WSF-ONX-01', 'Wastafel Oval Onyx Tembus Cahaya D45', 'onix', 'Panjang: 45cm, Lebar: 35cm, T: 14cm', 'Hi-Glossy', 8, 3, 480000.00, 950000.00),
(6, 'PRD-STP-BKL-01', 'Stepping Stone Batu Kali Bulat Slep', 'batu_kali', 'Diameter: 30-40cm, Tebal: 3cm', 'Doff Halus', 45, 15, 35000.00, 75000.00);

-- 6. Insert Customers
INSERT INTO `customers` (`customer_code`, `name`, `company_name`, `phone`, `email`, `address`, `city`, `customer_type`) VALUES
('CUST-BALI-01', 'Bapak Ketut Sukerta', 'Bali Natural Living Gallery', '081338877665', 'ketut@balinaturalliving.com', 'Jl. Sunset Road No. 88, Seminyak', 'Badung - Bali', 'distributor_ekspor'),
('CUST-SBY-02', 'Ibu Hendra Wijaya', 'PT Citra Griya Indah', '081231122334', 'purchasing@citragriya.co.id', 'Jl. Raya Darmo Permai III No. 12', 'Surabaya', 'kontraktor_arsitektur');
