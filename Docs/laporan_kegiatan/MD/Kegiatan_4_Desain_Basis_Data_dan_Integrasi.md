# DOKUMEN OUTPUT KEGIATAN 4
## Desain Arsitektur Basis Data Relasional & Skema Integrasi
**Proyek:** Rancang Bangun Sistem Informasi E-Supply Chain Terintegrasi untuk Akselerasi Hilirisasi Klaster IKM Marmer di Kabupaten Tulungagung  
**Mitra Studi Kasus:** UD Cahaya Onix & UD Putra Abadi (Kabupaten Tulungagung)  
**Metodologi SDLC:** Tahap Desain Sistem (*System Design - Database & Architecture Design*)

---

## A. Tujuan Kegiatan
Merancang struktur basis data yang akan menjadi "tulang punggung" sistem — mencakup seluruh entitas data (bahan baku, produk, stok, produksi, distribusi, pengguna, QC, limbah) — serta skema integrasi antar-modul agar data mengalir konsisten antara modul stok, produksi, distribusi, dan algoritma forecasting.

---

## B. Keterkaitan dengan Tahapan Pengembangan Sistem Informasi
Tahap **Desain Sistem (*System Design*)**, khususnya *Database Design* dan *System Architecture Design* — menjembatani hasil analisis kebutuhan (Kegiatan 3) dengan tahap pengkodean (Kegiatan 7).

---

## C. Langkah-Langkah Detail Pelaksanaan

### Langkah 1 — Identifikasi Entitas Data
* **Yang dilakukan:** dari daftar kebutuhan (Kegiatan 3) dan BPMN to-be (Kegiatan 2), tentukan entitas utama: `users`, `materials`, `material_transactions`, `products`, `orders`, `order_items`, `production_orders`, `production_logs`, `qc_inspections`, `waste_logs`, `shipments`, `shipment_trackings`, `forecast_results`, dan `financial_ledgers`.
* **Yang harus disiapkan:** dokumen kebutuhan sistem, BPMN to-be.
* **Output:** daftar 14 entitas data relasional terdefinisi lengkap.

### Langkah 2 — Penyusunan ERD Konseptual
* **Yang dilakukan:** gambar Entity Relationship Diagram (ERD) yang menunjukkan relasi antar-entitas (one-to-many, many-to-many) beserta atribut utamanya.
* **Yang harus disiapkan:** daftar entitas dari langkah 1, tools (MySQL Workbench, draw.io).
* **Output:** file ERD konseptual (`IMG-4.1-ERD.png`).

### Langkah 3 — Normalisasi Data
* **Yang dilakukan:** terapkan normalisasi (1NF $ightarrow$ 2NF $ightarrow$ 3NF) untuk menghilangkan redundansi data, duplikasi atribut, dan memastikan integritas referensial (Form 4.3).
* **Yang harus disiapkan:** ERD konseptual dari langkah 2.
* **Output:** skema tabel yang sudah ternormalisasi 3NF bebas anomali insert, update, dan delete.

### Langkah 4 — Penyusunan Skema Basis Data Fisik
* **Yang dilakukan:** tentukan nama tabel, nama kolom, tipe data, panjang data, primary key, foreign key, dan constraint (unique, not null, default value, strict integer validation) untuk setiap tabel.
* **Yang harus disiapkan:** hasil normalisasi langkah 3.
* **Output:** skema database fisik (siap diimplementasikan di DDL migrasi).

### Langkah 5 — Desain Skema Integrasi Antar-Modul
* **Yang dilakukan:** tentukan bagaimana data berpindah antar-modul (misal: data transaksi stok dikirim ke microservice forecasting AI, hasil forecasting memicu safety stock alert, verifikasi order memicu SPK Kanban otomatis), termasuk metode integrasi (REST API JSON, database trigger, event-driven).
* **Yang harus disiapkan:** arsitektur modul yang direncanakan (stok, produksi, QC, limbah, distribusi, forecasting).
* **Output:** dokumen skema integrasi / data flow antar-modul (Form 4.2).

### Langkah 6 — Pembuatan Kamus Data (Data Dictionary)
* **Yang dilakukan:** dokumentasikan setiap tabel dan kolom secara rinci agar mudah dirujuk tim developer (Form 4.1).
* **Yang harus disiapkan:** skema database fisik dari langkah 4.
* **Output:** dokumen kamus data lengkap 14 entitas relasional.

### Langkah 7 — Review Desain dengan Pembimbing/Pakar
* **Yang dilakukan:** presentasikan ERD dan skema integrasi untuk mendapat masukan sebelum implementasi, cek potensi kekurangan (skalabilitas index, integritas cascade delete, redundansi tersembunyi), dan catat pada formulir berita acara (Form 4.4).
* **Yang harus disiapkan:** seluruh dokumen desain dari langkah 1-6.
* **Output:** desain database final yang disetujui Dosen Pembimbing dan Pakar Sistem Informasi.

### Langkah 8 — Setup Environment Database
* **Yang dilakukan:** instal dan konfigurasi DBMS (MySQL 8.0 InnoDB), buat database `escm_marmer`, jalankan migrasi skema (`php artisan migrate --seed`), dan verifikasi GUI database.
* **Yang harus disiapkan:** skema final, software DBMS, server/lokal environment.
* **Output:** database MySQL aktif dengan 14 tabel terindeks dan seeder data empiris IKM Tulungagung (`IMG-4.2`, `IMG-4.3`).

---

## D. Form/Template Pendukung

### 1. Formulir 4.1: Kamus Data (Data Dictionary) 14 Entitas Relasional
| No | Nama Tabel | Kolom Kunci / Atribut Kritis | Tipe Data & Constraint | Relasi Antar-Tabel | Fungsi Bisnis dalam E-SCM Marmer |
| :---: | :--- | :--- | :--- | :--- | :--- |
| **1** | `users` | `id`, `email`, `role`, `is_active` | BIGINT PK, VARCHAR(255) UNIQUE, ENUM, TINYINT | Induk dari relasi otentikasi | Autentikasi multi-role RBAC pengguna sistem |
| **2** | `materials` | `id`, `name`, `grade`, `stock`, `unit` | BIGINT PK, VARCHAR(255), ENUM('A','B','C'), INT | 1:M ke `material_transactions` | Master data bahan baku bongkahan batu marmer/kali |
| **3** | `material_transactions` | `id`, `material_id`, `type`, `qty` | BIGINT PK, FK `materials.id`, ENUM('in','out'), INT | M:1 ke `materials`, `users` | Log mutasi stok masuk (tambang) dan keluar (SPK) |
| **4** | `products` | `id`, `name`, `category`, `price`, `stock`| BIGINT PK, VARCHAR, ENUM, DECIMAL(12,2), INT | 1:M ke `order_items`, `production_orders` | Katalog produk kerajinan marmer & batu kali |
| **5** | `orders` | `id`, `order_number`, `buyer_name`, `total`| BIGINT PK, VARCHAR(50) UNIQUE, VARCHAR, DECIMAL | 1:M ke `order_items`, `shipments` | Transaksi pesanan konsumen (DP 50% / Lunas QRIS) |
| **6** | `order_items` | `id`, `order_id`, `product_id`, `qty` | BIGINT PK, FK `orders.id`, FK `products.id`, INT | M:1 ke `orders`, M:1 ke `products` | Rincian kuantitas produk yang dipesan konsumen |
| **7** | `production_orders` | `id`, `spk_number`, `status`, `target_qty` | BIGINT PK, VARCHAR(50) UNIQUE, ENUM, INT | 1:M ke `production_logs`, `qc_inspections` | Surat Perintah Kerja (SPK) produksi di Kanban board |
| **8** | `production_logs` | `id`, `production_order_id`, `stage` | BIGINT PK, FK `production_orders.id`, ENUM | M:1 ke `production_orders` | Riwayat tracking waktu siklus pengerjaan mesin |
| **9** | `qc_inspections` | `id`, `production_order_id`, `status` | BIGINT PK, FK `production_orders.id`, ENUM | 1:1 ke `production_orders` | Form inspeksi kendali mutu QC1 & QC2 |
| **10**| `waste_logs` | `id`, `qc_inspection_id`, `waste_type` | BIGINT PK, FK nullable, ENUM('scrap','dust'), INT | M:1 ke `qc_inspections` | Pencatatan residu/tatal marmer dan lempengan |
| **11**| `shipments` | `id`, `order_id`, `surat_jalan_no`, `status`| BIGINT PK, FK `orders.id`, VARCHAR(50) UNIQUE | 1:M ke `shipment_trackings` | Distribusi logistik dan surat jalan pengiriman peti |
| **12**| `shipment_trackings`| `id`, `shipment_id`, `location`, `note` | BIGINT PK, FK `shipments.id`, VARCHAR(255) | M:1 ke `shipments` | Pelacakan status pengiriman real-time di lapangan |
| **13**| `forecast_results` | `id`, `material_id`, `period`, `forecast_qty`| BIGINT PK, FK `materials.id`, DATE, INT, FLOAT | M:1 ke `materials` | Output proyeksi peramalan kebutuhan stok ARIMA |
| **14**| `financial_ledgers` | `id`, `transaction_type`, `amount`, `ref_id` | BIGINT PK, ENUM('debit','credit'), DECIMAL(15,2) | Referensi ke `orders`, `materials` | Buku kas pencatatan arus kas operasional IKM |

### 2. Formulir 4.2: Matriks Skema Integrasi Antar-Modul E-SCM Marmer
| Modul Sumber | Modul Tujuan | Objek Data yang Dikirim | Mekanisme Integrasi | Frekuensi / Pemicu Integrasi | Validasi & Integritas Relasional |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Katalog & Orders** | **Manajemen Produksi** | `order_id`, `product_id`, `qty` | Database Transaction Trigger | Real-time saat DP 50% atau lunas diverifikasi | Auto-generate SPK jika stok produk tidak mencukupi |
| **Manajemen Produksi** | **Inventaris Bahan Baku** | `material_id`, `qty_used`, `spk_id` | Foreign Key Constraint | Saat kartu SPK ditarik ke stage 'Cutting' | Pengurangan stok fisik batuan (strict integer) |
| **Manajemen Produksi** | **Quality Control** | `production_order_id`, `qty_output` | Event Listener | Saat pengerjaan 'Polishing' selesai | Pembentukan tiket QC inspeksi otomatis |
| **Quality Control** | **Hilirisasi Residu** | `qc_id`, `scrap_weight`, `defect_type`| Database Cascade Insertion | Saat formulir QC mencatat produk scrap/reject | Akumulasi inventaris residu untuk kerajinan teraso |
| **Quality Control** | **Distribusi & Ekspedisi** | `production_order_id`, `passed_qty` | State Machine Transition | Saat status QC dinyatakan 'Passed' | Produk siap di-packing peti kayu dan diterbitkan Surat Jalan |
| **Inventaris Bahan Baku**| **AI Forecasting** | `material_id`, `consumption_history` | RESTful API Client (FastAPI) | On-demand / Cron Job Mingguan | Sanitasi payload array JSON 24 periode historis |

### 3. Formulir 4.3: Matriks Tahapan Normalisasi Basis Data (1NF s.d. 3NF)
| Entitas / Kasus Data | Bentuk Tidak Normal (UNF) | Bentuk Normal Pertama (1NF) | Bentuk Normal Kedua (2NF) | Bentuk Normal Ketiga (3NF) | Status Eliminasi Anomali |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Pesanan & Produk** | Data pemesan memuat array multi-produk dalam 1 kolom `items` | Pemisahan baris per item (`order_number`, `buyer`, `product`, `qty`, `price`) | Pemecahan tabel: `orders` (PK `id`) dan `order_items` (FK `order_id`, FK `product_id`) | Pemisahan harga produk ke tabel master `products`, bebas dependensi transitif | **100% BEBAS ANOMALI** |
| **Bahan Baku & Mutasi**| Stok digabung dengan riwayat bongkahan dan nama suplier | Nilai kolom bersifat atomik (tidak ada multivalue) | Pemisahan master `materials` dan transaksi `material_transactions` | Atribut turunan stok total dihitung via agregasi log mutasi | **100% BEBAS ANOMALI** |
| **Produksi & QC** | Log mesin dan hasil QC dicatat dalam 1 tabel SPK | Kolom dipisahkan menjadi atribut tunggal per baris | Pemisahan `production_orders`, `production_logs`, dan `qc_inspections` | Kolom `defect_type` dan `waste_logs` dipisah ke relasi tersendiri | **100% BEBAS ANOMALI** |

### 4. Formulir 4.4: Berita Acara Catatan Review Desain Basis Data
| Aspek Evaluasi | Rekomendasi Reviewer / Dosen Pembimbing | Tindak Lanjut Tim Pengembang | Status Validasi |
| :--- | :--- | :--- | :---: |
| **Integritas Relasional** | Pasang constraint `RESTRICT` pada relasi master bahan baku agar tidak terhapus saat ada transaksi aktif | Menambahkan constraint `->onDelete('restrict')` pada seluruh FK transaksional | **DISETUJUI (ACC)** |
| **Tipe Data Fisik** | Hindari tipe `FLOAT` untuk kuantitas batuan fisik guna mencegah anomali floating-point | Mengubah seluruh kuantitas bahan dan target produksi menjadi `UNSIGNED INTEGER` | **DISETUJUI (ACC)** |
| **Indeks & Performa** | Tambahkan indeks komposit pada tabel log transaksi untuk mempercepat query deret waktu ARIMA | Menambahkan `$table->index(['material_id', 'created_at'])` pada skema migrasi | **DISETUJUI (ACC)** |
| **Keamanan Data** | Kolom password dan token API wajib terenkripsi menggunakan algoritma bcrypt/Argon2id | Menerapkan `Hashed` cast pada model `User` dan proteksi session token | **DISETUJUI (ACC)** |

### 5. Tangkapan Layar Bukti Desain Basis Data
![Gambar 4.1: ERD Konseptual dan Relasional 14 Entitas E-SCM Marmer](IMG-4.1-ERD.png)
![Gambar 4.2: Tampilan GUI Database MySQL 8.0 InnoDB](IMG-4.2-GUI-Database.png)
![Gambar 4.3: Skema Relasi Constraint Foreign Key dan Indexing](IMG-4.3-Relasi-Constraint.png)

---

## E. Output Akhir Kegiatan
- [x] **Dokumen Kamus Data Lengkap:** 14 tabel database relasional beserta seluruh tipe data, primary key, foreign key, dan constraint terdefinisi rapi di Form 4.1.
- [x] **File Skema ERD Konseptual & Fisik:** Diagram relasi antar-entitas terstandarisasi Crows Foot notation (`IMG-4.1-ERD.png`).
- [x] **Skema Integrasi Antar-Modul:** Aliran data hulu-hilir (Pesanan $ightarrow$ Produksi $ightarrow$ QC $ightarrow$ Limbah $ightarrow$ Distribusi $ightarrow$ AI Forecasting) terpetakan di Form 4.2.
- [x] **Matriks Pembuktian Normalisasi 3NF:** Dokumentasi eliminasi dependensi fungsional parsial dan transitif di Form 4.3.
- [x] **Berita Acara Review Desain Basis Data:** Catatan persetujuan Dosen Pembimbing dan Pakar Sistem Informasi terangkum di Form 4.4.
- [x] **Script DDL & Migrasi Database Siap Pakai:** 14 file migration Laravel modular dan DatabaseSeeder data empiris IKM Tulungagung.
- [x] **Setup Database Environment:** Database `escm_marmer` aktif pada MySQL 8.0 InnoDB terverifikasi siap pakai untuk tahap pengkodean (Kegiatan 7).

---

## F. Tips & Best Practice
1. **Gunakan tipe data integer murni** untuk kuantitas fisik bahan baku marmer dan produk guna mencegah anomali desimal pada stok fisik.
2. **Terapkan Foreign Key Constraint dengan `onDelete('restrict')`** pada tabel transaksi krusial (`orders`, `production_orders`) untuk mencegah kehilangan riwayat historis secara tidak sengaja.
3. **Tambahkan index komposit** pada kolom `(material_id, transaction_date)` dan `(status, created_at)` untuk mengoptimalkan kecepatan agregasi data pada modul peramalan deret waktu.
4. **Validasi integritas data di dua lapis:** Lapis database via constraints (NOT NULL, UNIQUE, CHECK) dan lapis aplikasi via Laravel Form Request Validation.
