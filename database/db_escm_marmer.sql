-- E-SCM Marmer Tulungagung SQL Schema & Seed Data
-- 100% Compatible with TiDB Cloud Serverless (No ALTER TABLE)
-- Generated for Web SCM Marmer Project
-- --------------------------------------------------------

CREATE DATABASE IF NOT EXISTS `test`;
USE `test`;

CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `type` enum('material','product') NOT NULL DEFAULT 'product',
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `customers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_code` varchar(50) NOT NULL,
  `name` varchar(150) NOT NULL,
  `company_name` varchar(150) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `customer_type` enum('retail','kontraktor_arsitektur','distributor_ekspor') NOT NULL DEFAULT 'retail',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customer_code` (`customer_code`),
  KEY `idx_customers_code` (`customer_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `supplier_code` varchar(50) NOT NULL,
  `name` varchar(150) NOT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text,
  `quarry_location` varchar(150) DEFAULT NULL,
  `material_specialization` enum('marmer','onix','batu_kali','bahan_penolong') NOT NULL DEFAULT 'marmer',
  `lead_time_days` int NOT NULL DEFAULT '3',
  `rating` decimal(3,2) NOT NULL DEFAULT '5.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `supplier_code` (`supplier_code`),
  KEY `idx_suppliers_code` (`supplier_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('owner','gudang','produksi','qc','distribusi','admin') NOT NULL DEFAULT 'owner',
  `phone` varchar(20) DEFAULT NULL,
  `ikm_name` varchar(100) NOT NULL DEFAULT 'UD Cahaya Onix',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_users_role` (`role`),
  KEY `idx_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `materials` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `supplier_id` bigint UNSIGNED DEFAULT NULL,
  `material_code` varchar(50) NOT NULL,
  `name` varchar(150) NOT NULL,
  `type` enum('marmer','onix','batu_kali','bahan_penolong') NOT NULL DEFAULT 'marmer',
  `grade` enum('grade_a_super','grade_b_standard','grade_c_ekonomis') NOT NULL DEFAULT 'grade_b_standard',
  `dimension_info` varchar(100) DEFAULT NULL,
  `unit` varchar(20) NOT NULL DEFAULT 'blok',
  `current_stock` decimal(12,2) NOT NULL DEFAULT '0.00',
  `minimum_stock` decimal(12,2) NOT NULL DEFAULT '5.00',
  `unit_cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `material_code` (`material_code`),
  KEY `fk_materials_supplier` (`supplier_id`),
  KEY `idx_materials_code` (`material_code`),
  KEY `idx_materials_stock` (`current_stock`,`minimum_stock`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` bigint UNSIGNED NOT NULL,
  `product_code` varchar(50) NOT NULL,
  `name` varchar(150) NOT NULL,
  `material_type` enum('marmer','onix','batu_kali','kombinasi') NOT NULL DEFAULT 'marmer',
  `dimension_spec` varchar(100) DEFAULT NULL,
  `finishing_type` varchar(50) NOT NULL DEFAULT 'Hi-Glossy',
  `ready_stock` int NOT NULL DEFAULT '0',
  `safety_stock` int NOT NULL DEFAULT '5',
  `standard_cogs` decimal(15,2) NOT NULL DEFAULT '0.00',
  `selling_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_code` (`product_code`),
  KEY `fk_products_category` (`category_id`),
  KEY `idx_products_code` (`product_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `work_orders` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `spk_number` varchar(50) NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED DEFAULT NULL,
  `target_quantity` int NOT NULL,
  `completed_quantity` int NOT NULL DEFAULT '0',
  `rework_quantity` int NOT NULL DEFAULT '0',
  `scrap_quantity` int NOT NULL DEFAULT '0',
  `start_date` date NOT NULL,
  `target_finish_date` date NOT NULL,
  `actual_finish_date` date DEFAULT NULL,
  `status` enum('scheduled','in_progress','qc_phase','completed','cancelled') NOT NULL DEFAULT 'scheduled',
  `priority` enum('low','normal','high','urgent') NOT NULL DEFAULT 'normal',
  `batch_code` varchar(50) DEFAULT NULL,
  `notes` text,
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `spk_number` (`spk_number`),
  KEY `fk_wo_product` (`product_id`),
  KEY `fk_wo_customer` (`customer_id`),
  KEY `fk_wo_creator` (`created_by`),
  KEY `idx_wo_spk_number` (`spk_number`),
  KEY `idx_wo_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `production_steps` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `work_order_id` bigint UNSIGNED NOT NULL,
  `step_name` enum('pembelahan_bongkahan','pemotongan_slep','pembubutan_bentuk','penghalusan_poles','inspeksi_qc') NOT NULL,
  `sequence_order` int NOT NULL DEFAULT '1',
  `machine_number` varchar(30) DEFAULT NULL,
  `operator_id` bigint UNSIGNED DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `duration_minutes` int NOT NULL DEFAULT '0',
  `input_qty` int NOT NULL DEFAULT '0',
  `output_qty` int NOT NULL DEFAULT '0',
  `status` enum('pending','running','completed') NOT NULL DEFAULT 'pending',
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_step_operator` (`operator_id`),
  KEY `idx_step_wo_status` (`work_order_id`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `qc_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `work_order_id` bigint UNSIGNED NOT NULL,
  `step_id` bigint UNSIGNED DEFAULT NULL,
  `stage` enum('qc1_raw_shape','qc2_final_polish') NOT NULL,
  `inspector_id` bigint UNSIGNED NOT NULL,
  `inspected_quantity` int NOT NULL DEFAULT '0',
  `pass_quantity` int NOT NULL DEFAULT '0',
  `rework_quantity` int NOT NULL DEFAULT '0',
  `scrap_quantity` int NOT NULL DEFAULT '0',
  `defect_type` varchar(150) DEFAULT NULL,
  `rework_action` varchar(255) DEFAULT NULL,
  `inspection_date` date NOT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_qc_wo` (`work_order_id`),
  KEY `fk_qc_step` (`step_id`),
  KEY `fk_qc_inspector` (`inspector_id`),
  KEY `idx_qc_stage` (`stage`,`inspection_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `waste_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `work_order_id` bigint UNSIGNED NOT NULL,
  `step_id` bigint UNSIGNED DEFAULT NULL,
  `waste_type` varchar(50) NOT NULL,
  `weight_kg` decimal(10,2) NOT NULL DEFAULT '0.00',
  `volume_m3` decimal(10,3) NOT NULL DEFAULT '0.000',
  `reuse_status` varchar(50) NOT NULL DEFAULT 'disimpan',
  `notes` text,
  `logged_at` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_waste_wo` (`work_order_id`),
  KEY `fk_waste_step` (`step_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `shipments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `shipment_code` varchar(50) NOT NULL,
  `work_order_id` bigint UNSIGNED DEFAULT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `expedition_name` varchar(100) NOT NULL,
  `tracking_number` varchar(100) DEFAULT NULL,
  `driver_name` varchar(100) DEFAULT NULL,
  `vehicle_plate` varchar(20) DEFAULT NULL,
  `packing_verified` tinyint(1) NOT NULL DEFAULT '0',
  `shipment_date` date NOT NULL,
  `delivery_status` enum('packed','in_transit','delivered','returned') NOT NULL DEFAULT 'packed',
  `notes` text,
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shipment_code` (`shipment_code`),
  KEY `fk_shipment_wo` (`work_order_id`),
  KEY `fk_shipment_customer` (`customer_id`),
  KEY `fk_shipment_creator` (`created_by`),
  KEY `idx_shipments_code` (`shipment_code`),
  KEY `idx_shipments_status` (`delivery_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `stock_transactions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `transaction_code` varchar(50) NOT NULL,
  `material_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `type` enum('opening','in','out','consign') NOT NULL,
  `quantity` decimal(12,2) NOT NULL,
  `unit` varchar(20) NOT NULL DEFAULT 'blok',
  `before_stock` decimal(12,2) NOT NULL DEFAULT '0.00',
  `after_stock` decimal(12,2) NOT NULL DEFAULT '0.00',
  `reference_type` varchar(50) DEFAULT NULL,
  `reference_id` bigint UNSIGNED DEFAULT NULL,
  `notes` text,
  `transaction_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaction_code` (`transaction_code`),
  KEY `fk_stock_trans_material` (`material_id`),
  KEY `fk_stock_trans_user` (`user_id`),
  KEY `idx_trans_code` (`transaction_code`),
  KEY `idx_trans_type_date` (`type`,`transaction_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `forecasting_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_type` enum('material','product') NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `algorithm_used` varchar(50) NOT NULL,
  `forecast_horizon_months` int NOT NULL DEFAULT '3',
  `historical_data_points` int NOT NULL,
  `mape_score` decimal(6,2) NOT NULL,
  `rmse_score` decimal(10,2) NOT NULL,
  `prediction_json` json NOT NULL,
  `generated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_forecast_item` (`item_type`,`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- SEED DATA (USERS, SUPPLIERS, CATEGORIES, MATERIALS, PRODUCTS)
-- --------------------------------------------------------

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `phone`, `ikm_name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Owner Cahaya Onix', 'owner@cahayaonix.com', '$2y$12$K1rKevr6LpQ1Z6Ovh0qPbe58xYQ9Z3j11Wk.v.dF6B.1C7QzFzY1y', 'owner', '081234567890', 'UD Cahaya Onix', 1, NOW(), NOW()),
(2, 'Staf Gudang Cahaya Onix', 'gudang@cahayaonix.com', '$2y$12$K1rKevr6LpQ1Z6Ovh0qPbe58xYQ9Z3j11Wk.v.dF6B.1C7QzFzY1y', 'gudang', '081234567891', 'UD Cahaya Onix', 1, NOW(), NOW()),
(3, 'Operator Produksi Cahaya Onix', 'produksi@cahayaonix.com', '$2y$12$K1rKevr6LpQ1Z6Ovh0qPbe58xYQ9Z3j11Wk.v.dF6B.1C7QzFzY1y', 'produksi', '081234567892', 'UD Cahaya Onix', 1, NOW(), NOW()),
(4, 'Staf Distribusi Cahaya Onix', 'distribusi@cahayaonix.com', '$2y$12$K1rKevr6LpQ1Z6Ovh0qPbe58xYQ9Z3j11Wk.v.dF6B.1C7QzFzY1y', 'distribusi', '081234567893', 'UD Cahaya Onix', 1, NOW(), NOW()),
(5, 'Administrator Sistem', 'admin@escm-marmer.id', '$2y$12$K1rKevr6LpQ1Z6Ovh0qPbe58xYQ9Z3j11Wk.v.dF6B.1C7QzFzY1y', 'admin', '081234567899', 'Pusat Klaster Tulungagung', 1, NOW(), NOW()),
(11, 'M. Ilham Nur Amali', 'ilham.cahayaonix@placeholder.local', '$2y$12$K1rKevr6LpQ1Z6Ovh0qPbe58xYQ9Z3j11Wk.v.dF6B.1C7QzFzY1y', 'owner', '081234567890', 'UD Cahaya Onix', 1, NOW(), NOW()),
(12, 'Suparno', 'suparno.cahayaonix@placeholder.local', '$2y$12$K1rKevr6LpQ1Z6Ovh0qPbe58xYQ9Z3j11Wk.v.dF6B.1C7QzFzY1y', 'produksi', '081234567892', 'UD Cahaya Onix', 1, NOW(), NOW()),
(13, 'Efri Saputra', 'efri.putraabadi@placeholder.local', '$2y$12$K1rKevr6LpQ1Z6Ovh0qPbe58xYQ9Z3j11Wk.v.dF6B.1C7QzFzY1y', 'owner', '081298765432', 'UD Putra Abadi', 1, NOW(), NOW()),
(14, 'Misno', 'misno.putraabadi@placeholder.local', '$2y$12$K1rKevr6LpQ1Z6Ovh0qPbe58xYQ9Z3j11Wk.v.dF6B.1C7QzFzY1y', 'produksi', '081298765433', 'UD Putra Abadi', 1, NOW(), NOW()),
(15, 'Suyanto', 'suyanto.putraabadi@placeholder.local', '$2y$12$K1rKevr6LpQ1Z6Ovh0qPbe58xYQ9Z3j11Wk.v.dF6B.1C7QzFzY1y', 'produksi', '081298765434', 'UD Putra Abadi', 1, NOW(), NOW());

INSERT INTO `suppliers` (`id`, `supplier_code`, `name`, `contact_person`, `phone`, `address`, `quarry_location`, `material_specialization`, `lead_time_days`, `rating`, `created_at`, `updated_at`) VALUES
(1, 'SUP-CPD-01', 'Tambang Marmer Campurdarat Jaya', 'Pak Sukir', '085233112233', 'Desa Campurdarat', 'Kecamatan Campurdarat, Tulungagung', 'marmer', 3, 4.90, NOW(), NOW()),
(2, 'SUP-CPD-02', 'Penambang Onyx Campurdarat', 'Pak Wahyu', '085244556677', 'Desa Campurdarat', 'Campurdarat, Tulungagung', 'onix', 4, 4.85, NOW(), NOW()),
(3, 'SUP-KL-03', 'Paguyuban Batu Kali Boyolangu', 'Pak Yatno', '085277889900', 'Desa Boyolangu', 'Boyolangu, Tulungagung', 'batu_kali', 2, 4.95, NOW(), NOW());

INSERT INTO `categories` (`id`, `name`, `slug`, `type`, `description`, `created_at`, `updated_at`) VALUES
(14, 'Bahan Baku Marmer', 'bahan-baku-marmer', 'material', 'Bongkahan batu marmer putih & hitam dari Campurdarat', NOW(), NOW()),
(15, 'Bahan Baku Batu Kali', 'bahan-baku-batu-kali', 'material', 'Batu kali alam untuk stepping, wastafel, dan kap lampu', NOW(), NOW()),
(16, 'Wastafel Marmer', 'wastafel-marmer', 'product', 'Wastafel cuci tangan olahan batu marmer, finishing Hi-Glossy', NOW(), NOW()),
(17, 'Wastafel Batu Kali', 'wastafel-batu-kali', 'product', 'Wastafel cuci tangan olahan batu kali, finishing Hi-Glossy', NOW(), NOW()),
(18, 'Stepping Batu Kali', 'stepping-batu-kali', 'product', 'Batu pijakan taman dari batu kali, hasil gerinda halus', NOW(), NOW()),
(19, 'Kap Lampu Batu Kali', 'kap-lampu-batu-kali', 'product', 'Kap lampu hias dari olahan batu kali', NOW(), NOW()),
(20, 'Pedestal Marmer', 'pedestal-marmer', 'product', 'Pedestal/dudukan wastafel olahan batu marmer', NOW(), NOW()),
(21, 'Wastafel Onix', 'wastafel-onix', 'product', 'Wastafel mewah olahan batu onix tembus cahaya, finishing Super Glossy', NOW(), NOW()),
(22, 'Meja & Ornamen Marmer', 'meja-ornamen-marmer', 'product', 'Meja bundar marmer, pot hias, dan ornamen interior', NOW(), NOW());

INSERT INTO `materials` (`id`, `supplier_id`, `material_code`, `name`, `type`, `grade`, `dimension_info`, `unit`, `current_stock`, `minimum_stock`, `unit_cost`, `created_at`, `updated_at`) VALUES
(5, 1, 'MAT-MRM-001', 'Batu Marmer Putih', 'marmer', 'grade_b_standard', '60x60x80 cm', 'Balok', 28.00, 5.00, 180000.00, NOW(), NOW()),
(6, 1, 'MAT-MRM-002', 'Batu Marmer Hitam', 'marmer', 'grade_b_standard', '50x50x70 cm', 'Balok', 14.00, 5.00, 210000.00, NOW(), NOW()),
(7, 3, 'MAT-BKL-001', 'Batu Kali', 'batu_kali', 'grade_b_standard', 'Diameter 30-50 cm', 'biji', 65.00, 10.00, 25000.00, NOW(), NOW());

INSERT INTO `products` (`id`, `category_id`, `product_code`, `name`, `material_type`, `dimension_spec`, `finishing_type`, `ready_stock`, `safety_stock`, `standard_cogs`, `selling_price`, `image_path`, `created_at`, `updated_at`) VALUES
(4, 16, 'PRD-WSF-MRM-01', 'Wastafel Marmer Putih B1 (B-One)', 'marmer', 'D: 40 cm, T: 15 cm', 'Hi-Glossy', 14, 5, 280000.00, 450000.00, 'images/products/WastafelMarmerPutihB1.jpg', NOW(), NOW()),
(5, 18, 'PRD-STP-BKL-01', 'Stepping Stone Pijakan Taman Batu Kali', 'batu_kali', 'D: 30-35 cm, Tebal: 4 cm', 'Gerinda Halus Anti-Slip', 50, 10, 25000.00, 45000.00, 'images/products/stepping-stone.svg', NOW(), NOW()),
(6, 17, 'PRD-WSF-BKL-01', 'Wastafel Batu Kali Alami Campurdarat', 'batu_kali', 'D: 45 cm, T: 16 cm', 'Alami Luar / Halus Dalam', 8, 4, 220000.00, 380000.00, 'images/products/WastafelBatuKaliAlamiCampurdarat.jpg', NOW(), NOW()),
(7, 19, 'PRD-LMP-BKL-01', 'Kap Lampu Hias Batu Kali Minimalis', 'batu_kali', 'T: 25 cm, D: 18 cm', 'Hi-Glossy Alami', 12, 3, 95000.00, 175000.00, 'images/products/kap-lampu.svg', NOW(), NOW()),
(8, 20, 'PRD-PDS-MRM-01', 'Pedestal Wastafel Marmer Luxury', 'marmer', 'T: 85 cm, D: 45 cm', 'Hi-Glossy Kaca', 3, 2, 1100000.00, 1850000.00, 'images/products/PedestalWastafelMarmerLuxury.jpg', NOW(), NOW()),
(9, 21, 'PRD-WSF-ONX-01', 'Wastafel Onyx Tembus Cahaya Eksklusif', 'onix', 'D: 42 cm, T: 14 cm', 'Super Hi-Glossy Translucent', 5, 2, 550000.00, 950000.00, 'images/products/wastafel-onyx.svg', NOW(), NOW()),
(10, 16, 'PRD-WSF-MRM-02', 'Wastafel Marmer Bakar Antik', 'marmer', 'D: 40 cm, T: 15 cm', 'Tekstur Bakar Kasar Eksotis', 6, 3, 300000.00, 490000.00, 'images/products/WastafelMarmerBakarAntik.jpg', NOW(), NOW()),
(11, 22, 'PRD-MJA-MRM-01', 'Meja Kopi Bundar Marmer Campurdarat', 'marmer', 'D: 60 cm, T: 45 cm', 'Hi-Glossy Urat Abu', 4, 2, 750000.00, 1350000.00, 'images/products/meja-marmer.svg', NOW(), NOW());

INSERT INTO `customers` (`id`, `customer_code`, `name`, `company_name`, `phone`, `email`, `address`, `city`, `customer_type`, `created_at`, `updated_at`) VALUES
(1, 'CUST-BALI-01', 'Bapak Ketut Sukerta', 'Bali Natural Living Gallery', '081338877665', 'ketut@balinaturalliving.com', 'Jl. Sunset Road No. 88, Seminyak', 'Badung - Bali', 'distributor_ekspor', NOW(), NOW()),
(2, 'CUST-SBY-02', 'Ibu Hendra Wijaya', 'PT Citra Griya Indah', '081231122334', 'purchasing@citragriya.co.id', 'Jl. Raya Darmo Permai III No. 12', 'Surabaya', 'kontraktor_arsitektur', NOW(), NOW());

INSERT INTO `work_orders` (`id`, `spk_number`, `product_id`, `customer_id`, `target_quantity`, `completed_quantity`, `rework_quantity`, `scrap_quantity`, `start_date`, `target_finish_date`, `actual_finish_date`, `status`, `priority`, `batch_code`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'SPK-CO-202501-001', 4, NULL, 590, 590, 0, 0, '2025-01-01', '2025-01-31', '2025-01-31', 'completed', 'normal', 'BATCH-202501', 'Batch produksi bulanan Januari 2025', 12, NOW(), NOW()),
(2, 'SPK-CO-202502-002', 4, NULL, 640, 640, 0, 0, '2025-02-01', '2025-02-28', '2025-02-28', 'completed', 'normal', 'BATCH-202502', 'Batch produksi bulanan Februari 2025', 12, NOW(), NOW()),
(16, 'SPK-CO-202604-016', 4, NULL, 675, 675, 0, 0, '2026-04-01', '2026-04-30', '2026-04-30', 'completed', 'normal', 'BATCH-202604', 'Batch produksi bulanan April 2026', 12, NOW(), NOW()),
(17, 'SPK-CO-202605-017', 4, NULL, 750, 750, 0, 0, '2026-05-01', '2026-05-31', NULL, 'in_progress', 'normal', 'BATCH-202605', 'Batch produksi bulanan Mei 2026', 12, NOW(), NOW()),
(63, 'SPK-PA-202604-046', 5, NULL, 2650, 2650, 0, 0, '2026-04-01', '2026-04-30', '2026-04-30', 'completed', 'normal', 'BATCH-202604-PA', 'Batch produksi bulanan April 2026 - Stepping Batu Kali', 13, NOW(), NOW());

INSERT INTO `waste_logs` (`id`, `work_order_id`, `step_id`, `waste_type`, `weight_kg`, `volume_m3`, `reuse_status`, `notes`, `logged_at`, `created_at`, `updated_at`) VALUES
(1, 16, NULL, 'serbuk_bubut_sludge', 45.00, 0.030, 'disimpan_daur_ulang', 'Lumpur campuran air dan serbuk marmer dari proses pemotongan mesin slep.', '2026-04-24', NOW(), NOW()),
(2, 16, NULL, 'bongkahan_urukan', 120.00, 0.080, 'disimpan_daur_ulang', 'Sisa potongan kecil dari proses pembubutan/pembentukan wastafel.', '2026-04-25', NOW(), NOW()),
(5, 63, NULL, 'sisa_layak_cladding', 85.00, 0.050, 'disimpan_daur_ulang', 'Sisa batu kali hasil pemotongan dipilah untuk wall cladding.', '2026-04-21', NOW(), NOW());

INSERT INTO `shipments` (`id`, `shipment_code`, `work_order_id`, `customer_id`, `expedition_name`, `tracking_number`, `driver_name`, `vehicle_plate`, `packing_verified`, `shipment_date`, `delivery_status`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'SJ-202604-001', 16, 1, 'Kargo Express Tulungagung', 'RESI-EXP-001', 'Pak Yatno', 'AG 8899 AB', 1, '2026-04-28', 'delivered', 'Pengiriman 14 unit wastafel marmer ke Bali dengan packing krat kayu', 4, NOW(), NOW());
