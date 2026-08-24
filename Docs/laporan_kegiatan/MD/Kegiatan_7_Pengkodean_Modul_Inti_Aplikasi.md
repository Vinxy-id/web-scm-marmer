# DOKUMEN OUTPUT KEGIATAN 7
## Pengkodean Modul Manajemen Stok, Produksi, & Distribusi
**Proyek:** Rancang Bangun Sistem Informasi E-Supply Chain Terintegrasi untuk Akselerasi Hilirisasi Klaster IKM Marmer di Kabupaten Tulungagung  
**Mitra Studi Kasus:** UD Cahaya Onix & UD Putra Abadi (Kabupaten Tulungagung)  
**Metodologi SDLC:** Tahap Implementasi (*Coding/Development - Core MVC Architecture*)

---

## A. Tujuan Kegiatan
Mengimplementasikan rancangan basis data (Kegiatan 4) dan desain UI/UX (Kegiatan 6) menjadi kode program nyata untuk tiga modul inti sistem: manajemen stok, produksi, dan distribusi.

---

## B. Keterkaitan dengan Tahapan Pengembangan Sistem Informasi
Tahap **Implementasi (*Coding/Development*)** — bagian terbesar dari *System Implementation*, mengubah seluruh rancangan dari tahap desain menjadi sistem yang berfungsi.

---

## C. Langkah-Langkah Detail Pelaksanaan

### Langkah 1 — Setup Environment Development
* **Yang dilakukan:** pilih framework/bahasa pemrograman (Laravel 11 PHP 8.3 & Blade Tailwind CSS), instal tools pendukung (Composer, Node.js, Git), dan setup version control (Git/GitHub).
* **Yang harus disiapkan:** spesifikasi teknis dari hasil desain, akses komputer development.
* **Output:** environment development siap pakai, repository Git terinisialisasi.

### Langkah 2 — Pembuatan Struktur Project
* **Yang dilakukan:** buat struktur folder/arsitektur aplikasi (MVC — Model, View, Controller) sesuai standar framework Laravel 11.
* **Yang harus disiapkan:** environment dari langkah 1.
* **Output:** struktur project dasar (boilerplate) yang siap diisi fitur (`IMG-7.1-MVC-Architecture.png`).

### Langkah 3 — Implementasi Modul Manajemen Stok
* **Yang dilakukan:** bangun fitur CRUD data bahan baku/bongkahan marmer, kategori/grade batuan, pencatatan mutasi stok in-out dengan validasi *strict integer*, dan alert minimum stock.
* **Yang harus disiapkan:** skema database stok (Kegiatan 4), mockup UI modul stok (Kegiatan 6).
* **Output:** modul stok berfungsi (siap diuji unit) (`IMG-7.2-UI-BahanBaku.png`).

### Langkah 4 — Implementasi Modul Produksi
* **Yang dilakukan:** bangun fitur penerbitan SPK, pemantauan 7 mesin bubut, perpindahan tahapan di Kanban board (Antrean $ightarrow$ Potong $ightarrow$ Bubut $ightarrow$ Polis $ightarrow$ Siap QC), dan pencatatan alokasi bahan baku per batch.
* **Yang harus disiapkan:** skema database produksi, mockup UI modul produksi.
* **Output:** modul produksi berfungsi (`IMG-7.3-UI-Produksi-SPK.png`).

### Langkah 5 — Implementasi Modul Distribusi & QC/Limbah
* **Yang dilakukan:** bangun fitur formulir inspeksi QC 2 tahap, pencatatan residu/limbah batu marmer, checklist keselamatan packing peti kayu solid, pelacakan status pengiriman, dan surat jalan digital.
* **Yang harus disiapkan:** skema database distribusi & QC, mockup UI terkait.
* **Output:** modul distribusi, QC, dan residu limbah berfungsi (`IMG-7.4`, `IMG-7.5`, `IMG-7.6`).

### Langkah 6 — Implementasi Autentikasi dan Otorisasi (RBAC)
* **Yang dilakukan:** bangun sistem login dan hak akses berbasis peran (*Role-Based Access Control*) — Owner, Petugas Gudang, Mandor Produksi, Petugas QC, Supir Distribusi, Petugas Limbah, Admin Pesanan, dan Pembeli (Form 7.3).
* **Yang harus disiapkan:** daftar peran pengguna dari hasil requirement Kegiatan 3.
* **Output:** sistem login dan kontrol akses berfungsi aman dengan middleware.

### Langkah 7 — Unit Testing Tiap Modul
* **Yang dilakukan:** uji setiap fungsi secara terpisah (sebelum integrasi penuh) untuk memastikan logika program berjalan benar (PHPUnit 72 feature tests, 376 assertions, 100% pass).
* **Yang harus disiapkan:** daftar fungsi yang harus diuji, data uji empiris.
* **Output:** hasil unit testing per modul (catatan bug untuk diperbaiki).

### Langkah 8 — Code Review dan Dokumentasi
* **Yang dilakukan:** lakukan review kode (standar PSR-12, clean code), tulis dokumentasi teknis (README, komentar kode penting, Swagger API spec) dan verifikasi kepatuhan (Form 7.4).
* **Yang harus disiapkan:** kode dari langkah 3-7.
* **Output:** kode yang sudah direview dan terdokumentasi, siap untuk integrasi (Kegiatan 8) dan pengujian fungsional (Kegiatan 9).

---

## D. Form/Template Pendukung

### 1. Formulir 7.1: Checklist Pengembangan Modul Aplikasi E-SCM Marmer
| No | Modul Sistem | Fitur Utama yang Dikembangkan | Pengembang (PIC) | Tanggal Selesai | Status Pengembangan |
| :---: | :--- | :--- | :--- | :---: | :---: |
| **1** | **Bahan Baku** | CRUD bongkahan batu, filter grade A/B/C, alert stok kritis, log mutasi | Tim Backend | 20 Agt 2026 | **100% SELESAI** |
| **2** | **Produksi & SPK**| Penerbitan SPK, monitoring 7 mesin bubut, Kanban board 5 kolom | Tim Backend & UI | 21 Agt 2026 | **100% SELESAI** |
| **3** | **Quality Control**| Form inspeksi QC1 & QC2 kilap $> 95	ext{ GU}$, approval lolos/rework | Tim QA & Backend | 21 Agt 2026 | **100% SELESAI** |
| **4** | **Residu Limbah** | Logging residu tatal marmer, akumulasi teraso/cladding hulu-hilir | Tim Backend | 22 Agt 2026 | **100% SELESAI** |
| **5** | **Distribusi** | Checklist packing kayu solid, terbit Surat Jalan, tracking GPS live | Tim Fullstack | 22 Agt 2026 | **100% SELESAI** |
| **6** | **Katalog Publik** | Etalase wastafel/lantai, search, detail produk, konsultasi WA | Tim Frontend | 22 Agt 2026 | **100% SELESAI** |
| **7** | **Checkout & Bayar**| Direct checkout DP 50%/100%, invoice QRIS otomatis, bukti bayar | Tim Fullstack | 23 Agt 2026 | **100% SELESAI** |
| **8** | **Pesanan Masuk** | Panel admin verifikasi transfer, konversi otomatis ke SPK pabrik | Tim Backend | 23 Agt 2026 | **100% SELESAI** |
| **9** | **Autentikasi RBAC**| Middleware auth multi-role (8 peran), proteksi CSRF & session | Tim Keamanan | 23 Agt 2026 | **100% SELESAI** |

### 2. Formulir 7.2: Verifikasi 36 Rute HTTP & Controller Antarmuka
| Grup Rute | Jumlah Rute | Controller Utama | Middleware Keamanan | Status Verifikasi Rute |
| :--- | :---: | :--- | :--- | :---: |
| **Publik & Katalog** | 6 Rute | `CatalogController`, `CheckoutController` | Guest / Rate Limiter (60/min) | **100% PASS** |
| **Bahan Baku & Stok** | 5 Rute | `MaterialController`, `InventoryController` | `auth`, `role:gudang,owner` | **100% PASS** |
| **Produksi & Kanban**| 6 Rute | `ProductionOrderController` | `auth`, `role:mandor,owner` | **100% PASS** |
| **QC & Residu Limbah**| 6 Rute | `QCInspectionController`, `WasteLogController` | `auth`, `role:qc,mandor` | **100% PASS** |
| **Distribusi Logistik**| 5 Rute | `ShipmentController`, `TrackingController` | `auth`, `role:distribusi,owner` | **100% PASS** |
| **Orders & Admin** | 5 Rute | `OrderAdminController`, `DashboardController` | `auth`, `role:admin,owner` | **100% PASS** |
| **AI Forecasting** | 3 Rute | `ForecastingController` | `auth`, `role:owner,admin` | **100% PASS** |

### 3. Formulir 7.3: Matriks Otorisasi Hak Akses RBAC 8 Peran vs 9 Modul (CRUD Matrix)
| Peran Pengguna (Role) | Bahan Baku | SPK Produksi | Quality Control | Residu Limbah | Distribusi | Katalog Publik | Checkout Order | Panel Admin | AI Forecast |
| :--- | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: |
| **Owner / Pimpinan IKM** | CRUD / View | CRUD / View | View / Approve | View / Export | View / Export | View | View | Full Access | Full Access |
| **Petugas Gudang** | CRUD | View | No Access | View | No Access | View | No Access | No Access | View |
| **Mandor Produksi** | View / Deduct| CRUD (Kanban)| View | Create (Scrap) | No Access | View | No Access | No Access | No Access |
| **Petugas QC** | No Access | View | CRUD (Inspeksi)| Create | View | View | No Access | No Access | No Access |
| **Supir Distribusi** | No Access | No Access | No Access | No Access | Update Tracking| View | No Access | No Access | No Access |
| **Petugas Limbah** | No Access | No Access | No Access | CRUD | No Access | View | No Access | No Access | No Access |
| **Admin Pesanan** | View | Create (SPK) | View | No Access | Create (SJ) | View | Manage Order | CRUD | View |
| **Pembeli Publik** | No Access | No Access | No Access | No Access | View Tracking | Full Browse | Checkout DP/Full| No Access | No Access |

### 4. Formulir 7.4: Checklist Kepatuhan Standar Kode PSR-12 & Clean Code
| Aspek Standar Kode | Deskripsi Aturan / Best Practice | Tool Evaluasi | Hasil Audit Kode | Status Kepatuhan |
| :--- | :--- | :--- | :--- | :---: |
| **Format Standar PHP** | Kepatuhan PSR-12 (Indentation 4 spasi, deklarasi typehint, namespace) | PHP_CodeSniffer | 0 Error / 0 Warning | **100% COMPLIANT** |
| **Type Safety** | Deklarasi return type dan parameter typing pada seluruh Controller & Service | PHPStan (Level 6) | 100% Strict Type Passed | **100% COMPLIANT** |
| **Dependency Injection**| Penggunaan Service Layer dan Constructor Injection (bukan hardcoded class) | Architecture Review | Seluruh Controller decoupled | **100% COMPLIANT** |
| **Keamanan Query** | Menggunakan Eloquent ORM & Parameterized Binding (Anti-SQL Injection) | Laravel Security Audit | Tidak ada raw SQL injection | **100% SECURE** |

### 5. Tangkapan Layar Bukti Pengkodean & Antarmuka Sistem
![Gambar 7.1: Arsitektur MVC Laravel 11 dan Struktur Direktori Modular](IMG-7.1-MVC-Architecture.png)
![Gambar 7.2: Antarmuka Modul Inventaris Bahan Baku](IMG-7.2-UI-BahanBaku.png)
![Gambar 7.3: Antarmuka Modul Produksi & Kanban Board SPK](IMG-7.3-UI-Produksi-SPK.png)
![Gambar 7.4: Antarmuka Modul Quality Control (QC Inspeksi)](IMG-7.4-UI-QC-Inspeksi.png)
![Gambar 7.5: Antarmuka Modul Hilirisasi Residu & Limbah Marmer](IMG-7.5-UI-Waste-Residu.png)
![Gambar 7.6: Antarmuka Modul Distribusi & Checklist Packing Peti Kayu](IMG-7.6-UI-Distribusi.png)
![Gambar 7.7: Antarmuka Modul E-Commerce Katalog Publik](IMG-7.7-UI-Ecommerce-Public.png)
![Gambar 7.8: Antarmuka Modul Manajemen Pesanan Backoffice](IMG-7.8-UI-Orders-Admin.png)

---

## E. Output Akhir Kegiatan
- [x] **Source Code 9 Modul Inti:** Controller, Model Eloquent, Blade Views, dan Form Requests terimplementasi penuh.
- [x] **Arsitektur MVC Modular:** Pemisahan logic Controller, Service Layer, dan Database Repository terstruktur rapi di `IMG-7.1`.
- [x] **36 Rute HTTP Terverifikasi:** Seluruh endpoint terproteksi middleware RBAC dan lolos uji di Form 7.2.
- [x] **Matriks Otorisasi RBAC 8 Peran:** Hak akses CRUD antar-modul terdefinisi ketat di Form 7.3.
- [x] **Audit Kepatuhan Kode PSR-12:** Clean code bebas error linting terverifikasi di Form 7.4.
- [x] **8 Antarmuka Sistem Berfungsi Live:** Tampilan live bahan baku, SPK kanban, QC, residu, distribusi, dan etalase (`IMG-7.2` s.d. `IMG-7.8`).
- [x] **Hasil Unit Testing 100% Bersih:** 72 unit test PHPUnit dengan 376 assertions lulus tanpa error.

---

## F. Tips & Best Practice
1. **Gunakan Form Request Khusus** untuk setiap operasi POST/PUT agar controller tetap ramping (*skinny controller*) dan validasi terpusat.
2. **Bungkus Operasi Multi-Tabel dalam Database Transaction (`DB::transaction`)** saat konversi pesanan menjadi SPK atau pengurangan stok bahan.
3. **Manfaatkan Eloquent Mutators/Casts** untuk konversi otomatis tipe data (misal: JSON metadata, casting integer kuantitas).
4. **Terapkan Eager Loading (`with()`)** pada query relasional untuk menghindari masalah performa *N+1 Query*.
