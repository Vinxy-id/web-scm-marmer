-- E-SCM Marmer Tulungagung SQL Schema & Seed Data
-- Compatible with MySQL 8.0+ and TiDB Cloud Serverless
-- --------------------------------------------------------

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('material','product') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'product',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `type`, `description`, `created_at`, `updated_at`) VALUES
(14, 'Bahan Baku Marmer', 'bahan-baku-marmer', 'material', 'Bongkahan batu marmer putih & hitam dari Campurdarat', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(15, 'Bahan Baku Batu Kali', 'bahan-baku-batu-kali', 'material', 'Batu kali alam untuk stepping, wastafel, dan kap lampu', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(16, 'Wastafel Marmer', 'wastafel-marmer', 'product', 'Wastafel cuci tangan olahan batu marmer, finishing Hi-Glossy', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(17, 'Wastafel Batu Kali', 'wastafel-batu-kali', 'product', 'Wastafel cuci tangan olahan batu kali, finishing Hi-Glossy', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(18, 'Stepping Batu Kali', 'stepping-batu-kali', 'product', 'Batu pijakan taman dari batu kali, hasil gerinda halus', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(19, 'Kap Lampu Batu Kali', 'kap-lampu-batu-kali', 'product', 'Kap lampu hias dari olahan batu kali', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(20, 'Pedestal Marmer', 'pedestal-marmer', 'product', 'Pedestal/dudukan wastafel olahan batu marmer', '2026-08-22 06:29:04', '2026-08-22 06:29:04');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_type` enum('retail','kontraktor_arsitektur','distributor_ekspor') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'retail',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forecasting_logs`
--

CREATE TABLE `forecasting_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `item_type` enum('material','product') COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `algorithm_used` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Moving Average, Holt-Winters, ARIMA',
  `forecast_horizon_months` int NOT NULL DEFAULT '3',
  `historical_data_points` int NOT NULL,
  `mape_score` decimal(6,2) NOT NULL COMMENT 'Akurasi Persentase Error',
  `rmse_score` decimal(10,2) NOT NULL,
  `prediction_json` json NOT NULL COMMENT 'Hasil proyeksi per periode',
  `generated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE `materials` (
  `id` bigint UNSIGNED NOT NULL,
  `supplier_id` bigint UNSIGNED DEFAULT NULL,
  `material_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('marmer','onix','batu_kali','bahan_penolong') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'marmer',
  `grade` enum('grade_a_super','grade_b_standard','grade_c_ekonomis') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'grade_b_standard',
  `dimension_info` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ukuran bongkahan (misal: 60x60x80 cm)',
  `unit` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'blok' COMMENT 'blok, ton, m3, sak, kg',
  `current_stock` decimal(12,2) NOT NULL DEFAULT '0.00',
  `minimum_stock` decimal(12,2) NOT NULL DEFAULT '5.00' COMMENT 'Threshold alert stok minimum',
  `unit_cost` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Harga perolehan bahan',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `materials`
--

INSERT INTO `materials` (`id`, `supplier_id`, `material_code`, `name`, `type`, `grade`, `dimension_info`, `unit`, `current_stock`, `minimum_stock`, `unit_cost`, `created_at`, `updated_at`) VALUES
(5, NULL, 'MAT-MRM-001', 'Batu Marmer Putih', 'marmer', 'grade_b_standard', NULL, 'Balok', 0.00, 5.00, 0.00, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(6, NULL, 'MAT-MRM-002', 'Batu Marmer Hitam', 'marmer', 'grade_b_standard', NULL, 'Balok', 0.00, 5.00, 0.00, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(7, NULL, 'MAT-BKL-001', 'Batu Kali', 'batu_kali', 'grade_b_standard', NULL, 'biji', 0.00, 5.00, 0.00, '2026-08-22 06:29:04', '2026-08-22 06:29:04');

-- --------------------------------------------------------

--
-- Table structure for table `production_steps`
--

CREATE TABLE `production_steps` (
  `id` bigint UNSIGNED NOT NULL,
  `work_order_id` bigint UNSIGNED NOT NULL,
  `step_name` enum('pembelahan_bongkahan','pemotongan_slep','pembubutan_bentuk','penghalusan_poles','inspeksi_qc') COLLATE utf8mb4_unicode_ci NOT NULL,
  `sequence_order` int NOT NULL DEFAULT '1',
  `machine_number` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mesin Slep / Mesin Bubut 1 s.d 7',
  `operator_id` bigint UNSIGNED DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `duration_minutes` int NOT NULL DEFAULT '0',
  `input_qty` int NOT NULL DEFAULT '0',
  `output_qty` int NOT NULL DEFAULT '0',
  `status` enum('pending','running','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `product_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `material_type` enum('marmer','onix','batu_kali','kombinasi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'marmer',
  `dimension_spec` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Contoh: D=40cm T=15cm',
  `finishing_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Hi-Glossy' COMMENT 'Hi-Glossy, Doff, Bakar, Alami',
  `ready_stock` int NOT NULL DEFAULT '0',
  `safety_stock` int NOT NULL DEFAULT '5',
  `standard_cogs` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'HPP Standar',
  `selling_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `product_code`, `name`, `material_type`, `dimension_spec`, `finishing_type`, `ready_stock`, `safety_stock`, `standard_cogs`, `selling_price`, `image_path`, `created_at`, `updated_at`) VALUES
(4, 16, 'PRD-WSF-MRM-01', 'Wastafel (Sink Marmer/Marble)', 'marmer', NULL, 'Hi-Glossy', 0, 5, 0.00, 0.00, NULL, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(5, 18, 'PRD-STP-BKL-01', 'Stepping Batu Kali', 'batu_kali', NULL, 'Gerinda Halus', 0, 5, 0.00, 0.00, NULL, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(6, 17, 'PRD-WSF-BKL-01', 'Wastafel Batu Kali', 'batu_kali', NULL, 'Hi-Glossy', 0, 5, 0.00, 0.00, NULL, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(7, 19, 'PRD-LMP-BKL-01', 'Kap Lampu', 'batu_kali', NULL, 'Hi-Glossy', 0, 5, 0.00, 0.00, NULL, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(8, 20, 'PRD-PDS-MRM-01', 'Pedestal Wastafel Marmer', 'marmer', NULL, 'Hi-Glossy', 0, 5, 0.00, 0.00, NULL, '2026-08-22 06:29:04', '2026-08-22 06:29:04');

-- --------------------------------------------------------

--
-- Table structure for table `qc_logs`
--

CREATE TABLE `qc_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `work_order_id` bigint UNSIGNED NOT NULL,
  `step_id` bigint UNSIGNED DEFAULT NULL,
  `stage` enum('qc1_raw_shape','qc2_final_polish') COLLATE utf8mb4_unicode_ci NOT NULL,
  `inspector_id` bigint UNSIGNED NOT NULL,
  `inspected_quantity` int NOT NULL DEFAULT '0',
  `pass_quantity` int NOT NULL DEFAULT '0',
  `rework_quantity` int NOT NULL DEFAULT '0',
  `scrap_quantity` int NOT NULL DEFAULT '0',
  `defect_type` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Retak serat alam, lubang afur miring, permukaan tidak rata',
  `rework_action` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tambal resin / poles ulang / potong ulang',
  `inspection_date` date NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipments`
--

CREATE TABLE `shipments` (
  `id` bigint UNSIGNED NOT NULL,
  `shipment_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `work_order_id` bigint UNSIGNED DEFAULT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `expedition_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Armada Sendiri / Truk Ekspedisi',
  `tracking_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `driver_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vehicle_plate` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `packing_verified` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = Checklist packing kayu lolos',
  `shipment_date` date NOT NULL,
  `delivery_status` enum('packed','in_transit','delivered','returned') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'packed',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_transactions`
--

CREATE TABLE `stock_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `transaction_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `material_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `type` enum('opening','in','out','consign') COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(12,2) NOT NULL,
  `unit` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'blok',
  `before_stock` decimal(12,2) NOT NULL DEFAULT '0.00',
  `after_stock` decimal(12,2) NOT NULL DEFAULT '0.00',
  `reference_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'misal: surat_jalan_tambang, spk_produksi, opname',
  `reference_id` bigint UNSIGNED DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `transaction_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_transactions`
--

INSERT INTO `stock_transactions` (`id`, `transaction_code`, `material_id`, `user_id`, `type`, `quantity`, `unit`, `before_stock`, `after_stock`, `reference_type`, `reference_id`, `notes`, `transaction_date`, `created_at`, `updated_at`) VALUES
(1, 'TRX-CO-202601-001', 5, 11, 'in', 20.00, 'Balok', 0.00, 20.00, 'surat_jalan_tambang', NULL, 'Pembelian Batu Marmer Putih bulan Januari 2026', '2026-01-01', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(2, 'TRX-CO-202601-002', 6, 11, 'in', 10.00, 'Balok', 0.00, 10.00, 'surat_jalan_tambang', NULL, 'Pembelian Batu Marmer Hitam bulan Januari 2026', '2026-01-01', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(3, 'TRX-CO-202602-003', 5, 11, 'in', 22.00, 'Balok', 20.00, 42.00, 'surat_jalan_tambang', NULL, 'Pembelian Batu Marmer Putih bulan Februari 2026', '2026-02-01', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(4, 'TRX-CO-202602-004', 6, 11, 'in', 11.00, 'Balok', 10.00, 21.00, 'surat_jalan_tambang', NULL, 'Pembelian Batu Marmer Hitam bulan Februari 2026', '2026-02-01', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(5, 'TRX-CO-202603-005', 5, 11, 'in', 18.00, 'Balok', 42.00, 60.00, 'surat_jalan_tambang', NULL, 'Pembelian Batu Marmer Putih bulan Maret 2026', '2026-03-01', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(6, 'TRX-CO-202603-006', 6, 11, 'in', 9.00, 'Balok', 21.00, 30.00, 'surat_jalan_tambang', NULL, 'Pembelian Batu Marmer Hitam bulan Maret 2026', '2026-03-01', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(7, 'TRX-CO-202604-007', 5, 11, 'in', 21.00, 'Balok', 60.00, 81.00, 'surat_jalan_tambang', NULL, 'Pembelian Batu Marmer Putih bulan April 2026', '2026-04-01', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(8, 'TRX-CO-202604-008', 6, 11, 'in', 10.00, 'Balok', 30.00, 40.00, 'surat_jalan_tambang', NULL, 'Pembelian Batu Marmer Hitam bulan April 2026', '2026-04-01', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(9, 'TRX-CO-202605-009', 5, 11, 'in', 23.00, 'Balok', 81.00, 104.00, 'surat_jalan_tambang', NULL, 'Pembelian Batu Marmer Putih bulan Mei 2026', '2026-05-01', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(10, 'TRX-CO-202605-010', 6, 11, 'in', 11.00, 'Balok', 40.00, 51.00, 'surat_jalan_tambang', NULL, 'Pembelian Batu Marmer Hitam bulan Mei 2026', '2026-05-01', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(11, 'TRX-CO-202606-011', 5, 11, 'in', 20.00, 'Balok', 104.00, 124.00, 'surat_jalan_tambang', NULL, 'Pembelian Batu Marmer Putih bulan Juni 2026', '2026-06-01', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(12, 'TRX-CO-202606-012', 6, 11, 'in', 10.00, 'Balok', 51.00, 61.00, 'surat_jalan_tambang', NULL, 'Pembelian Batu Marmer Hitam bulan Juni 2026', '2026-06-01', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(13, 'TRX-CO-202607-013', 5, 11, 'in', 22.00, 'Balok', 124.00, 146.00, 'surat_jalan_tambang', NULL, 'Pembelian Batu Marmer Putih bulan Juli 2026', '2026-07-01', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(14, 'TRX-CO-202607-014', 6, 11, 'in', 11.00, 'Balok', 61.00, 72.00, 'surat_jalan_tambang', NULL, 'Pembelian Batu Marmer Hitam bulan Juli 2026', '2026-07-01', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(15, 'TRX-CO-202608-015', 5, 11, 'in', 19.00, 'Balok', 146.00, 165.00, 'surat_jalan_tambang', NULL, 'Pembelian Batu Marmer Putih bulan Agustus 2026', '2026-08-01', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(16, 'TRX-CO-202608-016', 6, 11, 'in', 10.00, 'Balok', 72.00, 82.00, 'surat_jalan_tambang', NULL, 'Pembelian Batu Marmer Hitam bulan Agustus 2026', '2026-08-01', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(17, 'TRX-PA-202601-001', 7, 13, 'in', 640.00, 'biji', 0.00, 640.00, 'surat_jalan_tambang', NULL, 'Pengiriman Batu Kali ke-1 bulan Januari 2026', '2026-01-07', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(18, 'TRX-PA-202601-002', 7, 13, 'in', 640.00, 'biji', 640.00, 1280.00, 'surat_jalan_tambang', NULL, 'Pengiriman Batu Kali ke-2 bulan Januari 2026', '2026-01-14', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(19, 'TRX-PA-202601-003', 7, 13, 'in', 800.00, 'biji', 1280.00, 2080.00, 'surat_jalan_tambang', NULL, 'Pengiriman Batu Kali ke-3 bulan Januari 2026', '2026-01-21', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(20, 'TRX-PA-202602-004', 7, 13, 'in', 640.00, 'biji', 2080.00, 2720.00, 'surat_jalan_tambang', NULL, 'Pengiriman Batu Kali ke-1 bulan Februari 2026', '2026-02-07', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(21, 'TRX-PA-202602-005', 7, 13, 'in', 640.00, 'biji', 2720.00, 3360.00, 'surat_jalan_tambang', NULL, 'Pengiriman Batu Kali ke-2 bulan Februari 2026', '2026-02-14', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(22, 'TRX-PA-202602-006', 7, 13, 'in', 640.00, 'biji', 3360.00, 4000.00, 'surat_jalan_tambang', NULL, 'Pengiriman Batu Kali ke-3 bulan Februari 2026', '2026-02-21', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(23, 'TRX-PA-202603-007', 7, 13, 'in', 800.00, 'biji', 4000.00, 4800.00, 'surat_jalan_tambang', NULL, 'Pengiriman Batu Kali ke-1 bulan Maret 2026', '2026-03-07', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(24, 'TRX-PA-202603-008', 7, 13, 'in', 640.00, 'biji', 4800.00, 5440.00, 'surat_jalan_tambang', NULL, 'Pengiriman Batu Kali ke-2 bulan Maret 2026', '2026-03-14', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(25, 'TRX-PA-202603-009', 7, 13, 'in', 640.00, 'biji', 5440.00, 6080.00, 'surat_jalan_tambang', NULL, 'Pengiriman Batu Kali ke-3 bulan Maret 2026', '2026-03-21', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(26, 'TRX-PA-202604-010', 7, 13, 'in', 640.00, 'biji', 6080.00, 6720.00, 'surat_jalan_tambang', NULL, 'Pengiriman Batu Kali ke-1 bulan April 2026', '2026-04-07', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(27, 'TRX-PA-202604-011', 7, 13, 'in', 640.00, 'biji', 6720.00, 7360.00, 'surat_jalan_tambang', NULL, 'Pengiriman Batu Kali ke-2 bulan April 2026', '2026-04-14', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(28, 'TRX-PA-202604-012', 7, 13, 'in', 800.00, 'biji', 7360.00, 8160.00, 'surat_jalan_tambang', NULL, 'Pengiriman Batu Kali ke-3 bulan April 2026', '2026-04-21', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(29, 'TRX-PA-202605-013', 7, 13, 'in', 640.00, 'biji', 8160.00, 8800.00, 'surat_jalan_tambang', NULL, 'Pengiriman Batu Kali ke-1 bulan Mei 2026', '2026-05-07', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(30, 'TRX-PA-202605-014', 7, 13, 'in', 800.00, 'biji', 8800.00, 9600.00, 'surat_jalan_tambang', NULL, 'Pengiriman Batu Kali ke-2 bulan Mei 2026', '2026-05-14', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(31, 'TRX-PA-202605-015', 7, 13, 'in', 640.00, 'biji', 9600.00, 10240.00, 'surat_jalan_tambang', NULL, 'Pengiriman Batu Kali ke-3 bulan Mei 2026', '2026-05-21', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(32, 'TRX-PA-202606-016', 7, 13, 'in', 640.00, 'biji', 10240.00, 10880.00, 'surat_jalan_tambang', NULL, 'Pengiriman Batu Kali ke-1 bulan Juni 2026', '2026-06-07', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(33, 'TRX-PA-202606-017', 7, 13, 'in', 640.00, 'biji', 10880.00, 11520.00, 'surat_jalan_tambang', NULL, 'Pengiriman Batu Kali ke-2 bulan Juni 2026', '2026-06-14', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(34, 'TRX-PA-202606-018', 7, 13, 'in', 800.00, 'biji', 11520.00, 12320.00, 'surat_jalan_tambang', NULL, 'Pengiriman Batu Kali ke-3 bulan Juni 2026', '2026-06-21', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(35, 'TRX-PA-202607-019', 7, 13, 'in', 640.00, 'biji', 12320.00, 12960.00, 'surat_jalan_tambang', NULL, 'Pengiriman Batu Kali ke-1 bulan Juli 2026', '2026-07-07', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(36, 'TRX-PA-202607-020', 7, 13, 'in', 640.00, 'biji', 12960.00, 13600.00, 'surat_jalan_tambang', NULL, 'Pengiriman Batu Kali ke-2 bulan Juli 2026', '2026-07-14', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(37, 'TRX-PA-202607-021', 7, 13, 'in', 800.00, 'biji', 13600.00, 14400.00, 'surat_jalan_tambang', NULL, 'Pengiriman Batu Kali ke-3 bulan Juli 2026', '2026-07-21', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(38, 'TRX-PA-202608-022', 7, 13, 'in', 640.00, 'biji', 14400.00, 15040.00, 'surat_jalan_tambang', NULL, 'Pengiriman Batu Kali ke-1 bulan Agustus 2026', '2026-08-07', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(39, 'TRX-PA-202608-023', 7, 13, 'in', 640.00, 'biji', 15040.00, 15680.00, 'surat_jalan_tambang', NULL, 'Pengiriman Batu Kali ke-2 bulan Agustus 2026', '2026-08-14', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(40, 'TRX-PA-202608-024', 7, 13, 'in', 800.00, 'biji', 15680.00, 16480.00, 'surat_jalan_tambang', NULL, 'Pengiriman Batu Kali ke-3 bulan Agustus 2026', '2026-08-21', '2026-08-22 06:29:04', '2026-08-22 06:29:04');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint UNSIGNED NOT NULL,
  `supplier_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `quarry_location` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lokasi Tambang (misal: Besole, Campurdarat)',
  `material_category` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Jenis bahan yang disuplai',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','owner','gudang','produksi','distribusi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'gudang',
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ikm_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UD Cahaya Onix',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `phone`, `ikm_name`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(11, 'M. Ilham Nur Amali', 'ilham.cahayaonix@placeholder.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'owner', NULL, 'UD Cahaya Onix', 1, NULL, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(12, 'Suparno', 'suparno.cahayaonix@placeholder.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'produksi', NULL, 'UD Cahaya Onix', 1, NULL, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(13, 'Efri Saputra', 'efri.putraabadi@placeholder.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'owner', NULL, 'UD Putra Abadi', 1, NULL, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(14, 'Misno', 'misno.putraabadi@placeholder.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'produksi', NULL, 'UD Putra Abadi', 1, NULL, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(15, 'Suyanto', 'suyanto.putraabadi@placeholder.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'produksi', NULL, 'UD Putra Abadi', 1, NULL, '2026-08-22 06:29:04', '2026-08-22 06:29:04');

-- --------------------------------------------------------

--
-- Table structure for table `waste_logs`
--

CREATE TABLE `waste_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `work_order_id` bigint UNSIGNED NOT NULL,
  `step_id` bigint UNSIGNED DEFAULT NULL,
  `waste_type` enum('sisa_layak_cladding','serbuk_bubut_sludge','bongkahan_urukan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight_kg` decimal(10,2) NOT NULL DEFAULT '0.00',
  `volume_m3` decimal(10,3) DEFAULT '0.000',
  `reuse_status` enum('disimpan_daur_ulang','dijual_ke_pihak3','dibuang_ke_urukan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'disimpan_daur_ulang',
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logged_at` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `waste_logs`
--

INSERT INTO `waste_logs` (`id`, `work_order_id`, `step_id`, `waste_type`, `weight_kg`, `volume_m3`, `reuse_status`, `notes`, `logged_at`, `created_at`, `updated_at`) VALUES
(1, 16, NULL, 'serbuk_bubut_sludge', 0.00, 0.000, 'disimpan_daur_ulang', 'Lumpur campuran air dan serbuk marmer dari proses pemotongan mesin slep. Volume/berat belum diukur pada observasi lapangan.', '2026-04-24', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(2, 16, NULL, 'bongkahan_urukan', 0.00, 0.000, 'disimpan_daur_ulang', 'Sisa potongan kecil dari proses pembubutan/pembentukan wastafel; sebagian dikumpulkan untuk urukan pondasi. Volume/berat belum diukur.', '2026-04-25', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(3, 16, NULL, 'serbuk_bubut_sludge', 0.00, 0.000, 'dibuang_ke_urukan', 'Lumpur bekas pemotongan dikumpulkan sebagai limbah proses pada akhir siklus produksi harian. Volume/berat belum diukur.', '2026-04-26', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(4, 16, NULL, 'bongkahan_urukan', 0.00, 0.000, 'dibuang_ke_urukan', 'Potongan marmer kecil sisa produksi dimanfaatkan untuk urukan pondasi rumah warga sekitar. Volume/berat belum diukur.', '2026-04-26', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(5, 63, NULL, 'sisa_layak_cladding', 0.00, 0.000, 'disimpan_daur_ulang', 'Sisa batu kali hasil pemotongan dipilah dan dikumpulkan sebelum ditentukan pemanfaatannya. Volume/berat belum diukur.', '2026-04-21', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(6, 63, NULL, 'sisa_layak_cladding', 0.00, 0.000, 'dijual_ke_pihak3', 'Sisa batu yang masih memenuhi ukuran dipotong ulang menjadi produk wall cladding/hiasan dinding. Volume/berat belum diukur.', '2026-04-22', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(7, 63, NULL, 'bongkahan_urukan', 0.00, 0.000, 'dibuang_ke_urukan', 'Sisa batu yang tidak sesuai ukuran dipindahkan ke area limbah untuk urukan pondasi. Volume/berat belum diukur.', '2026-04-25', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(8, 63, NULL, 'bongkahan_urukan', 0.00, 0.000, 'disimpan_daur_ulang', 'Sisa material ditumpuk sementara di area penyimpanan tambahan sebelum digunakan untuk urukan pondasi. Volume/berat belum diukur.', '2026-04-25', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(9, 63, NULL, 'bongkahan_urukan', 0.00, 0.000, 'dibuang_ke_urukan', 'Limbah batu kali dipindahkan ke lokasi yang membutuhkan material urukan pondasi. Volume/berat belum diukur.', '2026-04-26', '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(10, 63, NULL, 'sisa_layak_cladding', 0.00, 0.000, 'disimpan_daur_ulang', 'Sisa material yang masih layak diperiksa dan diproses ulang menjadi produk wall cladding. Volume/berat belum diukur.', '2026-04-26', '2026-08-22 06:29:04', '2026-08-22 06:29:04');

-- --------------------------------------------------------

--
-- Table structure for table `work_orders`
--

CREATE TABLE `work_orders` (
  `id` bigint UNSIGNED NOT NULL,
  `spk_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED DEFAULT NULL,
  `target_quantity` int NOT NULL DEFAULT '1',
  `completed_quantity` int NOT NULL DEFAULT '0',
  `scrap_quantity` int NOT NULL DEFAULT '0',
  `status` enum('draft','scheduled','in_progress','qc_phase','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `priority` enum('low','normal','high','urgent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `start_date` date NOT NULL,
  `due_date` date NOT NULL,
  `completion_date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `work_orders`
--

INSERT INTO `work_orders` (`id`, `spk_number`, `product_id`, `customer_id`, `target_quantity`, `completed_quantity`, `scrap_quantity`, `status`, `priority`, `start_date`, `due_date`, `completion_date`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'SPK-CO-202501-001', 4, NULL, 590, 590, 0, 'completed', 'normal', '2025-01-01', '2025-01-31', '2025-01-31', 'Batch produksi bulanan Januari 2025', 12, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(2, 'SPK-CO-202502-002', 4, NULL, 640, 640, 0, 'completed', 'normal', '2025-02-01', '2025-02-28', '2025-02-28', 'Batch produksi bulanan Februari 2025', 12, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(3, 'SPK-CO-202503-003', 4, NULL, 570, 570, 0, 'completed', 'normal', '2025-03-01', '2025-03-31', '2025-03-31', 'Batch produksi bulanan Maret 2025', 12, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(4, 'SPK-CO-202504-004', 4, NULL, 620, 620, 0, 'completed', 'normal', '2025-04-01', '2025-04-30', '2025-04-30', 'Batch produksi bulanan April 2025', 12, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(5, 'SPK-CO-202505-005', 4, NULL, 680, 680, 0, 'completed', 'normal', '2025-05-01', '2025-05-31', '2025-05-31', 'Batch produksi bulanan Mei 2025', 12, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(6, 'SPK-CO-202506-006', 4, NULL, 610, 610, 0, 'completed', 'normal', '2025-06-01', '2025-06-30', '2025-06-30', 'Batch produksi bulanan Juni 2025', 12, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(7, 'SPK-CO-202507-007', 4, NULL, 665, 665, 0, 'completed', 'normal', '2025-07-01', '2025-07-31', '2025-07-31', 'Batch produksi bulanan Juli 2025', 12, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(8, 'SPK-CO-202508-008', 4, NULL, 545, 545, 0, 'completed', 'normal', '2025-08-01', '2025-08-31', '2025-08-31', 'Batch produksi bulanan Agustus 2025', 12, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(9, 'SPK-CO-202509-009', 4, NULL, 600, 600, 0, 'completed', 'normal', '2025-09-01', '2025-09-30', '2025-09-30', 'Batch produksi bulanan September 2025', 12, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(10, 'SPK-CO-202510-010', 4, NULL, 690, 690, 0, 'completed', 'normal', '2025-10-01', '2025-10-31', '2025-10-31', 'Batch produksi bulanan Oktober 2025', 12, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(11, 'SPK-CO-202511-011', 4, NULL, 650, 650, 0, 'completed', 'normal', '2025-11-01', '2025-11-30', '2025-11-30', 'Batch produksi bulanan November 2025', 12, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(12, 'SPK-CO-202512-012', 4, NULL, 615, 615, 0, 'completed', 'normal', '2025-12-01', '2025-12-31', '2025-12-31', 'Batch produksi bulanan Desember 2025', 12, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(13, 'SPK-CO-202601-013', 4, NULL, 625, 625, 0, 'completed', 'normal', '2026-01-01', '2026-01-31', '2026-01-31', 'Batch produksi bulanan Januari 2026', 12, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(14, 'SPK-CO-202602-014', 4, NULL, 700, 700, 0, 'completed', 'normal', '2026-02-01', '2026-02-28', '2026-02-28', 'Batch produksi bulanan Februari 2026', 12, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(15, 'SPK-CO-202603-015', 4, NULL, 550, 550, 0, 'completed', 'normal', '2026-03-01', '2026-03-31', '2026-03-31', 'Batch produksi bulanan Maret 2026', 12, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(16, 'SPK-CO-202604-016', 4, NULL, 675, 675, 0, 'completed', 'normal', '2026-04-01', '2026-04-30', '2026-04-30', 'Batch produksi bulanan April 2026', 12, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(17, 'SPK-CO-202605-017', 4, NULL, 750, 750, 0, 'completed', 'normal', '2026-05-01', '2026-05-31', '2026-05-31', 'Batch produksi bulanan Mei 2026', 12, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(18, 'SPK-PA-202501-001', 5, NULL, 2650, 2650, 0, 'completed', 'normal', '2025-01-01', '2025-01-31', '2025-01-31', 'Batch produksi bulanan Januari 2025 - Stepping Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(19, 'SPK-PA-202501-002', 6, NULL, 235, 235, 0, 'completed', 'normal', '2025-01-01', '2025-01-31', '2025-01-31', 'Batch produksi bulanan Januari 2025 - Wastafel Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(20, 'SPK-PA-202501-003', 7, NULL, 330, 330, 0, 'completed', 'normal', '2025-01-01', '2025-01-31', '2025-01-31', 'Batch produksi bulanan Januari 2025 - Kap Lampu', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(21, 'SPK-PA-202502-004', 5, NULL, 2780, 2780, 0, 'completed', 'normal', '2025-02-01', '2025-02-28', '2025-02-28', 'Batch produksi bulanan Februari 2025 - Stepping Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(22, 'SPK-PA-202502-005', 6, NULL, 260, 260, 0, 'completed', 'normal', '2025-02-01', '2025-02-28', '2025-02-28', 'Batch produksi bulanan Februari 2025 - Wastafel Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(23, 'SPK-PA-202502-006', 7, NULL, 360, 360, 0, 'completed', 'normal', '2025-02-01', '2025-02-28', '2025-02-28', 'Batch produksi bulanan Februari 2025 - Kap Lampu', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(24, 'SPK-PA-202503-007', 5, NULL, 2860, 2860, 0, 'completed', 'normal', '2025-03-01', '2025-03-31', '2025-03-31', 'Batch produksi bulanan Maret 2025 - Stepping Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(25, 'SPK-PA-202503-008', 6, NULL, 285, 285, 0, 'completed', 'normal', '2025-03-01', '2025-03-31', '2025-03-31', 'Batch produksi bulanan Maret 2025 - Wastafel Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(26, 'SPK-PA-202503-009', 7, NULL, 390, 390, 0, 'completed', 'normal', '2025-03-01', '2025-03-31', '2025-03-31', 'Batch produksi bulanan Maret 2025 - Kap Lampu', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(27, 'SPK-PA-202504-010', 5, NULL, 2580, 2580, 0, 'completed', 'normal', '2025-04-01', '2025-04-30', '2025-04-30', 'Batch produksi bulanan April 2025 - Stepping Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(28, 'SPK-PA-202504-011', 6, NULL, 225, 225, 0, 'completed', 'normal', '2025-04-01', '2025-04-30', '2025-04-30', 'Batch produksi bulanan April 2025 - Wastafel Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(29, 'SPK-PA-202504-012', 7, NULL, 320, 320, 0, 'completed', 'normal', '2025-04-01', '2025-04-30', '2025-04-30', 'Batch produksi bulanan April 2025 - Kap Lampu', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(30, 'SPK-PA-202505-013', 5, NULL, 2920, 2920, 0, 'completed', 'normal', '2025-05-01', '2025-05-31', '2025-05-31', 'Batch produksi bulanan Mei 2025 - Stepping Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(31, 'SPK-PA-202505-014', 6, NULL, 280, 280, 0, 'completed', 'normal', '2025-05-01', '2025-05-31', '2025-05-31', 'Batch produksi bulanan Mei 2025 - Wastafel Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(32, 'SPK-PA-202505-015', 7, NULL, 380, 380, 0, 'completed', 'normal', '2025-05-01', '2025-05-31', '2025-05-31', 'Batch produksi bulanan Mei 2025 - Kap Lampu', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(33, 'SPK-PA-202506-016', 5, NULL, 2740, 2740, 0, 'completed', 'normal', '2025-06-01', '2025-06-30', '2025-06-30', 'Batch produksi bulanan Juni 2025 - Stepping Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(34, 'SPK-PA-202506-017', 6, NULL, 255, 255, 0, 'completed', 'normal', '2025-06-01', '2025-06-30', '2025-06-30', 'Batch produksi bulanan Juni 2025 - Wastafel Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(35, 'SPK-PA-202506-018', 7, NULL, 350, 350, 0, 'completed', 'normal', '2025-06-01', '2025-06-30', '2025-06-30', 'Batch produksi bulanan Juni 2025 - Kap Lampu', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(36, 'SPK-PA-202507-019', 5, NULL, 2880, 2880, 0, 'completed', 'normal', '2025-07-01', '2025-07-31', '2025-07-31', 'Batch produksi bulanan Juli 2025 - Stepping Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(37, 'SPK-PA-202507-020', 6, NULL, 275, 275, 0, 'completed', 'normal', '2025-07-01', '2025-07-31', '2025-07-31', 'Batch produksi bulanan Juli 2025 - Wastafel Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(38, 'SPK-PA-202507-021', 7, NULL, 370, 370, 0, 'completed', 'normal', '2025-07-01', '2025-07-31', '2025-07-31', 'Batch produksi bulanan Juli 2025 - Kap Lampu', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(39, 'SPK-PA-202508-022', 5, NULL, 2630, 2630, 0, 'completed', 'normal', '2025-08-01', '2025-08-31', '2025-08-31', 'Batch produksi bulanan Agustus 2025 - Stepping Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(40, 'SPK-PA-202508-023', 6, NULL, 235, 235, 0, 'completed', 'normal', '2025-08-01', '2025-08-31', '2025-08-31', 'Batch produksi bulanan Agustus 2025 - Wastafel Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(41, 'SPK-PA-202508-024', 7, NULL, 335, 335, 0, 'completed', 'normal', '2025-08-01', '2025-08-31', '2025-08-31', 'Batch produksi bulanan Agustus 2025 - Kap Lampu', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(42, 'SPK-PA-202509-025', 5, NULL, 2760, 2760, 0, 'completed', 'normal', '2025-09-01', '2025-09-30', '2025-09-30', 'Batch produksi bulanan September 2025 - Stepping Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(43, 'SPK-PA-202509-026', 6, NULL, 260, 260, 0, 'completed', 'normal', '2025-09-01', '2025-09-30', '2025-09-30', 'Batch produksi bulanan September 2025 - Wastafel Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(44, 'SPK-PA-202509-027', 7, NULL, 355, 355, 0, 'completed', 'normal', '2025-09-01', '2025-09-30', '2025-09-30', 'Batch produksi bulanan September 2025 - Kap Lampu', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(45, 'SPK-PA-202510-028', 5, NULL, 2950, 2950, 0, 'completed', 'normal', '2025-10-01', '2025-10-31', '2025-10-31', 'Batch produksi bulanan Oktober 2025 - Stepping Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(46, 'SPK-PA-202510-029', 6, NULL, 290, 290, 0, 'completed', 'normal', '2025-10-01', '2025-10-31', '2025-10-31', 'Batch produksi bulanan Oktober 2025 - Wastafel Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(47, 'SPK-PA-202510-030', 7, NULL, 395, 395, 0, 'completed', 'normal', '2025-10-01', '2025-10-31', '2025-10-31', 'Batch produksi bulanan Oktober 2025 - Kap Lampu', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(48, 'SPK-PA-202511-031', 5, NULL, 2810, 2810, 0, 'completed', 'normal', '2025-11-01', '2025-11-30', '2025-11-30', 'Batch produksi bulanan November 2025 - Stepping Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(49, 'SPK-PA-202511-032', 6, NULL, 270, 270, 0, 'completed', 'normal', '2025-11-01', '2025-11-30', '2025-11-30', 'Batch produksi bulanan November 2025 - Wastafel Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(50, 'SPK-PA-202511-033', 7, NULL, 375, 375, 0, 'completed', 'normal', '2025-11-01', '2025-11-30', '2025-11-30', 'Batch produksi bulanan November 2025 - Kap Lampu', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(51, 'SPK-PA-202512-034', 5, NULL, 2700, 2700, 0, 'completed', 'normal', '2025-12-01', '2025-12-31', '2025-12-31', 'Batch produksi bulanan Desember 2025 - Stepping Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(52, 'SPK-PA-202512-035', 6, NULL, 245, 245, 0, 'completed', 'normal', '2025-12-01', '2025-12-31', '2025-12-31', 'Batch produksi bulanan Desember 2025 - Wastafel Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(53, 'SPK-PA-202512-036', 7, NULL, 345, 345, 0, 'completed', 'normal', '2025-12-01', '2025-12-31', '2025-12-31', 'Batch produksi bulanan Desember 2025 - Kap Lampu', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(54, 'SPK-PA-202601-037', 5, NULL, 2750, 2750, 0, 'completed', 'normal', '2026-01-01', '2026-01-31', '2026-01-31', 'Batch produksi bulanan Januari 2026 - Stepping Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(55, 'SPK-PA-202601-038', 6, NULL, 250, 250, 0, 'completed', 'normal', '2026-01-01', '2026-01-31', '2026-01-31', 'Batch produksi bulanan Januari 2026 - Wastafel Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(56, 'SPK-PA-202601-039', 7, NULL, 350, 350, 0, 'completed', 'normal', '2026-01-01', '2026-01-31', '2026-01-31', 'Batch produksi bulanan Januari 2026 - Kap Lampu', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(57, 'SPK-PA-202602-040', 5, NULL, 2850, 2850, 0, 'completed', 'normal', '2026-02-01', '2026-02-28', '2026-02-28', 'Batch produksi bulanan Februari 2026 - Stepping Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(58, 'SPK-PA-202602-041', 6, NULL, 275, 275, 0, 'completed', 'normal', '2026-02-01', '2026-02-28', '2026-02-28', 'Batch produksi bulanan Februari 2026 - Wastafel Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(59, 'SPK-PA-202602-042', 7, NULL, 375, 375, 0, 'completed', 'normal', '2026-02-01', '2026-02-28', '2026-02-28', 'Batch produksi bulanan Februari 2026 - Kap Lampu', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(60, 'SPK-PA-202603-043', 5, NULL, 2900, 2900, 0, 'completed', 'normal', '2026-03-01', '2026-03-31', '2026-03-31', 'Batch produksi bulanan Maret 2026 - Stepping Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(61, 'SPK-PA-202603-044', 6, NULL, 300, 300, 0, 'completed', 'normal', '2026-03-01', '2026-03-31', '2026-03-31', 'Batch produksi bulanan Maret 2026 - Wastafel Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(62, 'SPK-PA-202603-045', 7, NULL, 400, 400, 0, 'completed', 'normal', '2026-03-01', '2026-03-31', '2026-03-31', 'Batch produksi bulanan Maret 2026 - Kap Lampu', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(63, 'SPK-PA-202604-046', 5, NULL, 2650, 2650, 0, 'completed', 'normal', '2026-04-01', '2026-04-30', '2026-04-30', 'Batch produksi bulanan April 2026 - Stepping Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(64, 'SPK-PA-202604-047', 6, NULL, 225, 225, 0, 'completed', 'normal', '2026-04-01', '2026-04-30', '2026-04-30', 'Batch produksi bulanan April 2026 - Wastafel Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(65, 'SPK-PA-202604-048', 7, NULL, 325, 325, 0, 'completed', 'normal', '2026-04-01', '2026-04-30', '2026-04-30', 'Batch produksi bulanan April 2026 - Kap Lampu', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(66, 'SPK-PA-202605-049', 5, NULL, 3000, 3000, 0, 'completed', 'normal', '2026-05-01', '2026-05-31', '2026-05-31', 'Batch produksi bulanan Mei 2026 - Stepping Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(67, 'SPK-PA-202605-050', 6, NULL, 290, 290, 0, 'completed', 'normal', '2026-05-01', '2026-05-31', '2026-05-31', 'Batch produksi bulanan Mei 2026 - Wastafel Batu Kali', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04'),
(68, 'SPK-PA-202605-051', 7, NULL, 390, 390, 0, 'completed', 'normal', '2026-05-01', '2026-05-31', '2026-05-31', 'Batch produksi bulanan Mei 2026 - Kap Lampu', 13, '2026-08-22 06:29:04', '2026-08-22 06:29:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_code` (`customer_code`),
  ADD KEY `idx_customers_code` (`customer_code`);

--
-- Indexes for table `forecasting_logs`
--
ALTER TABLE `forecasting_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_forecast_item` (`item_type`,`item_id`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `material_code` (`material_code`),
  ADD KEY `fk_materials_supplier` (`supplier_id`),
  ADD KEY `idx_materials_code` (`material_code`),
  ADD KEY `idx_materials_stock` (`current_stock`,`minimum_stock`);

--
-- Indexes for table `production_steps`
--
ALTER TABLE `production_steps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_step_operator` (`operator_id`),
  ADD KEY `idx_step_wo_status` (`work_order_id`,`status`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_code` (`product_code`),
  ADD KEY `fk_products_category` (`category_id`),
  ADD KEY `idx_products_code` (`product_code`);

--
-- Indexes for table `qc_logs`
--
ALTER TABLE `qc_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_qc_wo` (`work_order_id`),
  ADD KEY `fk_qc_step` (`step_id`),
  ADD KEY `fk_qc_inspector` (`inspector_id`),
  ADD KEY `idx_qc_stage` (`stage`,`inspection_date`);

--
-- Indexes for table `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shipment_code` (`shipment_code`),
  ADD KEY `fk_shipment_wo` (`work_order_id`),
  ADD KEY `fk_shipment_customer` (`customer_id`),
  ADD KEY `fk_shipment_creator` (`created_by`),
  ADD KEY `idx_shipments_code` (`shipment_code`),
  ADD KEY `idx_shipments_status` (`delivery_status`);

--
-- Indexes for table `stock_transactions`
--
ALTER TABLE `stock_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_code` (`transaction_code`),
  ADD KEY `fk_stock_trans_material` (`material_id`),
  ADD KEY `fk_stock_trans_user` (`user_id`),
  ADD KEY `idx_trans_code` (`transaction_code`),
  ADD KEY `idx_trans_type_date` (`type`,`transaction_date`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `supplier_code` (`supplier_code`),
  ADD KEY `idx_suppliers_code` (`supplier_code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_users_role` (`role`),
  ADD KEY `idx_users_email` (`email`);

--
-- Indexes for table `waste_logs`
--
ALTER TABLE `waste_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_waste_wo` (`work_order_id`),
  ADD KEY `fk_waste_step` (`step_id`),
  ADD KEY `idx_waste_type` (`waste_type`,`reuse_status`);

--
-- Indexes for table `work_orders`
--
ALTER TABLE `work_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `spk_number` (`spk_number`),
  ADD KEY `fk_wo_product` (`product_id`),
  ADD KEY `fk_wo_customer` (`customer_id`),
  ADD KEY `fk_wo_creator` (`created_by`),
  ADD KEY `idx_wo_spk_number` (`spk_number`),
  ADD KEY `idx_wo_status` (`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `forecasting_logs`
--
ALTER TABLE `forecasting_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `production_steps`
--
ALTER TABLE `production_steps`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `qc_logs`
--
ALTER TABLE `qc_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipments`
--
ALTER TABLE `shipments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_transactions`
--
ALTER TABLE `stock_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `waste_logs`
--
ALTER TABLE `waste_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `work_orders`
--
ALTER TABLE `work_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `materials`
--
ALTER TABLE `materials`
  ADD CONSTRAINT `fk_materials_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `production_steps`
--
ALTER TABLE `production_steps`
  ADD CONSTRAINT `fk_step_operator` FOREIGN KEY (`operator_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_step_wo` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `qc_logs`
--
ALTER TABLE `qc_logs`
  ADD CONSTRAINT `fk_qc_inspector` FOREIGN KEY (`inspector_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_qc_step` FOREIGN KEY (`step_id`) REFERENCES `production_steps` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_qc_wo` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shipments`
--
ALTER TABLE `shipments`
  ADD CONSTRAINT `fk_shipment_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_shipment_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_shipment_wo` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `stock_transactions`
--
ALTER TABLE `stock_transactions`
  ADD CONSTRAINT `fk_stock_trans_material` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_stock_trans_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `waste_logs`
--
ALTER TABLE `waste_logs`
  ADD CONSTRAINT `fk_waste_step` FOREIGN KEY (`step_id`) REFERENCES `production_steps` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_waste_wo` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `work_orders`
--
ALTER TABLE `work_orders`
  ADD CONSTRAINT `fk_wo_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_wo_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_wo_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

