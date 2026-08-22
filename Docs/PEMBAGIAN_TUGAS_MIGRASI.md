# 📌 PANDUAN & MATRIKS PEMBAGIAN TUGAS MIGRASI HTML KE LARAVEL
## Sistem Informasi E-Supply Chain Management (E-SCM) IKM Marmer Tulungagung
**Studi Kasus:** Klaster IKM Marmer & Onyx (UD Cahaya Onix & UD Putra Abadi)  
**Tim Pengembang:** Alvin & Dapin

---

## 🎯 1. Prinsip & Strategi Kolaborasi

Untuk menghindari konflik kode (*merge conflict*) di Git dan mempercepat pengerjaan, migrasi menggunakan strategi **Modular / Vertical Feature Split**:
1. **Fase Fondasi (Hari 1–2):** Dikerjakan secara paralel untuk menyiapkan kerangka dasar:
   - **Alvin:** Menyiapkan *Master Layout Blade*, *Sidebar*, *Header*, dan *Komponen UI*.
   - **Dapin:** Menyiapkan *Database Migrations*, *Eloquent Models*, dan *Seeders*.
2. **Fase Fitur Modul (Hari 3–8):** Masing-masing developer memegang modul ujung-ke-ujung (*Route + Controller + View + Logic*).
3. **Fase Integrasi & Testing (Hari 9–10):** Penggabungan seluruh modul, uji coba skenario operasional IKM, dan *polish UI/UX*.

---

## 👥 2. Matriks Pembagian Tugas (Task Matrix)

| Area / Modul | Penanggung Jawab | File & Komponen yang Dikerjakan | Output Deliverable |
| :--- | :--- | :--- | :--- |
| **Master Blade Layout & UI Kit** | **Alvin** | `resources/views/layouts/app.blade.php`<br>`resources/views/layouts/sidebar.blade.php`<br>`resources/views/layouts/header.blade.php`<br>`resources/views/components/*` | Kerangka master template, navigasi menu responsif, komponen reusable (Card KPI, Badge). |
| **Database Migrations & Models** | **Dapin** | `database/migrations/*.php`<br>`app/Models/*.php`<br>`database/seeders/DatabaseSeeder.php` | 11 tabel relasional, relasi Eloquent (`hasMany`, `belongsTo`), dan data seed riil IKM. |
| **Auth & RBAC (Hak Akses)** | **Alvin** | `app/Http/Middleware/RoleMiddleware.php`<br>`resources/views/auth/login.blade.php` | Sistem autentikasi & pembatasan akses 5 role (`admin`, `owner`, `gudang`, `produksi`, `distribusi`). |
| **Modul Bahan Baku & Stok** | **Alvin** | `app/Http/Controllers/MaterialController.php`<br>`resources/views/materials/*`<br>`routes/modules/materials.php` | Katalog stok bahan mentah/blok, filter grade, input transaksi IN, OUT, Consign, dan alert stok minimum. |
| **Modul SPK Produksi & WIP** | **Alvin** | `app/Http/Controllers/WorkOrderController.php`<br>`resources/views/production/*`<br>`routes/modules/production.php` | Kanban Board alur SPK (Antrean $\rightarrow$ Slep $\rightarrow$ Bubut $\rightarrow$ Polish), form SPK baru, dan tracking WIP mesin. |
| **Modul QC Dua Tahap & Limbah** | **Dapin** | `app/Http/Controllers/QualityControlController.php`<br>`resources/views/qc/*`<br>`routes/modules/qc.php` | Form inspeksi QC Tahap 1 (Bentuk/Dimensi) & QC Tahap 2 (Finishing/Glossy), serta pencatatan limbah afur. |
| **Modul Distribusi & Packing** | **Dapin** | `app/Http/Controllers/DistributionController.php`<br>`resources/views/distribution/*`<br>`routes/modules/distribution.php` | Checklist packing peti kayu, tracking ekspedisi/truk pengiriman, dan format cetak Surat Jalan. |
| **Dashboard & Peramalan** | **Dapin** | `app/Http/Controllers/DashboardController.php`<br>`app/Http/Controllers/ForecastingController.php`<br>`resources/views/dashboard/*`<br>`resources/views/forecasting/*`<br>`routes/modules/analytics.php` | Ringkasan KPI card, visualisasi grafik Chart.js dinamis (komposisi & tren aliran), dan halaman peramalan permintaan. |

---

## 📝 3. Checklist Detail Tugas per Developer

### 🧑‍💻 Checklist Tugas: ALVIN (Lead / Hulu & Lantai Produksi)

- [ ] **Sprint 0: Setup Layout & Komponen Blade**
  - [ ] Buat `resources/views/layouts/app.blade.php` (Import Tailwind CDN, Lucide Icons, Chart.js, script tab/navigation).
  - [ ] Buat `resources/views/layouts/sidebar.blade.php` (Menu navigasi dinamis dengan penanda aktif dan badge counter).
  - [ ] Buat `resources/views/layouts/header.blade.php` (Header, tombol toggle mobile, profil user, switch status role).
  - [ ] Buat `resources/views/components/kpi-card.blade.php` & `resources/views/components/badge.blade.php`.
- [ ] **Sprint 1: Autentikasi & Modul Bahan Baku (`/materials`)**
  - [ ] Setup `RoleMiddleware.php` untuk membatasi akses URL berdasarkan kolom `role` di tabel `users`.
  - [ ] Buat `MaterialController.php` dengan method `index()`, `storeTransaction()`, `show()`.
  - [ ] Slicing `resources/views/materials/index.blade.php` (Tabel inventaris bahan baku, filter kategori & grade).
  - [ ] Slicing modal form transaksi masuk (IN), keluar (OUT), dan titipan/konsinyasi (Consign).
- [ ] **Sprint 2: Modul SPK Produksi & WIP Tracking (`/production`)**
  - [ ] Buat `WorkOrderController.php` dengan method `index()`, `create()`, `store()`, `updateStatus()`, `wip()`.
  - [ ] Slicing `resources/views/production/index.blade.php` (Kanban Board drag/klik status pengerjaan SPK).
  - [ ] Slicing form modal "Buat SPK Baru" (Pilihan bahan baku, tipe produk wastafel/guci/meja, operator mesin).
  - [ ] Slicing `resources/views/production/wip.blade.php` (Tabel tracking barang dalam proses per stasiun kerja).

---

### 🧑‍💻 Checklist Tugas: DAPIN (Database, Hilir & Analitik Dashboard)

- [ ] **Sprint 0: Database Migrations, Models & Seeders**
  - [ ] Buat file migrasi untuk 11 tabel inti: `users`, `categories`, `suppliers`, `materials`, `material_transactions`, `products`, `work_orders`, `production_steps`, `quality_controls`, `waste_logs`, `shipments`, `forecast_demands`.
  - [ ] Buat Eloquent Models di `app/Models/` lengkap dengan relasi (`belongsTo`, `hasMany`).
  - [ ] Buat `database/seeders/DatabaseSeeder.php` untuk memasukkan data riil IKM dari `database/schema.sql`.
- [ ] **Sprint 1: Modul QC Dua Tahap & Manajemen Limbah (`/qc`)**
  - [ ] Buat `QualityControlController.php` dengan method `index()`, `storeInspection()`, `waste()`, `storeWaste()`.
  - [ ] Slicing `resources/views/qc/index.blade.php` (Daftar antrean inspeksi QC 1 Tahap Pembubutan & QC 2 Finishing).
  - [ ] Slicing form modal input hasil QC (Status: *Lolos / Rework / Scrap*, foto cacat, catatan retakan).
  - [ ] Slicing `resources/views/qc/waste.blade.php` (Pencatatan volume limbah padat/potongan dan lumpur gerinda).
- [ ] **Sprint 2: Modul Distribusi & Pengiriman (`/distribution`)**
  - [ ] Buat `DistributionController.php` dengan method `index()`, `storeShipment()`, `printSuratJalan()`.
  - [ ] Slicing `resources/views/distribution/index.blade.php` (Daftar pengiriman, ekspedisi, status truk).
  - [ ] Slicing checklist kelayakan packing peti kayu (*bubble wrap, styrofoam, label fragile*).
  - [ ] Siapkan template cetak Surat Jalan siap print (`window.print()`).
- [ ] **Sprint 3: Dashboard Monitoring & Modul Peramalan (`/dashboard`, `/forecasting`)**
  - [ ] Buat `DashboardController.php` untuk mengagregasi data KPI (Total Stok Opening, IN, OUT, SPK Berjalan).
  - [ ] Slicing `resources/views/dashboard/index.blade.php` dan integrasikan data dinamis ke Chart.js.
  - [ ] Buat `ForecastingController.php` & slicing `resources/views/forecasting/index.blade.php` (Grafik peramalan deret waktu permintaan vs aktual & evaluasi MAPE).

---

## 🗺️ 4. Panduan Mapping: Dari `public/index.html` ke Blade Views

| ID Bagian di `index.html` | Nama Fitur Asli | File Blade Tujuan | PIC |
| :--- | :--- | :--- | :---: |
| `<aside id="sidebar">` | Navigasi Menu | `resources/views/layouts/sidebar.blade.php` | Alvin |
| `<header class="...">` | Top Navigation Bar | `resources/views/layouts/header.blade.php` | Alvin |
| `<div id="tab-dashboard">` | Dashboard Utama & KPI | `resources/views/dashboard/index.blade.php` | Dapin |
| `<div id="tab-materials">` | Inventaris Bahan Baku | `resources/views/materials/index.blade.php` | Alvin |
| `<div id="tab-production-kanban">` | Kanban Board SPK | `resources/views/production/index.blade.php` | Alvin |
| `<div id="tab-wip-tracking">` | Tracking Stasiun WIP | `resources/views/production/wip.blade.php` | Alvin |
| `<div id="tab-qc-inspection">` | Inspeksi QC Dua Tahap | `resources/views/qc/index.blade.php` | Dapin |
| `<div id="tab-waste-management">` | Pencatatan Limbah Afur | `resources/views/qc/waste.blade.php` | Dapin |
| `<div id="tab-distribution">` | Distribusi & Packing | `resources/views/distribution/index.blade.php` | Dapin |
| `<div id="tab-forecasting">` | Peramalan Permintaan | `resources/views/forecasting/index.blade.php` | Dapin |

---

## 🛠️ 5. Standarisasi Git & Penanganan Route (Anti-Konflik)

### A. Struktur Pemisahan Route di `routes/web.php`
Untuk menghindari konflik edit file `routes/web.php` secara bersamaan, gunakan pemisahan file modul:

```php
// routes/web.php
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Load routes per modul (Dikerjakan terpisah oleh Alvin & Dapin)
require __DIR__ . '/modules/auth.php';
require __DIR__ . '/modules/materials.php';    // Dikelola Alvin
require __DIR__ . '/modules/production.php';   // Dikelola Alvin
require __DIR__ . '/modules/qc.php';           // Dikelola Dapin
require __DIR__ . '/modules/distribution.php'; // Dikelola Dapin
require __DIR__ . '/modules/analytics.php';    // Dikelola Dapin
```

### B. Aturan Penamaan Branch Git
- **Alvin:**
  - `git checkout -b feat/alvin-master-layout`
  - `git checkout -b feat/alvin-materials-module`
  - `git checkout -b feat/alvin-production-spk`
- **Dapin:**
  - `git checkout -b feat/dapin-database-migrations`
  - `git checkout -b feat/dapin-qc-waste-module`
  - `git checkout -b feat/dapin-distribution-module`
  - `git checkout -b feat/dapin-dashboard-forecasting`

---

## 📅 6. Jadwal & Alur Kerja Harian (10 Hari Sprint)

| Hari | Target Alvin | Target Dapin | Titik Sinkronisasi / Sync Point |
| :---: | :--- | :--- | :--- |
| **H1** | Slicing Master Layout (`app`, `sidebar`, `header`, `components`). | Membuat 11 Migrations & Eloquent Models. | **Sync 1:** Pastikan layout tampil di browser & migrasi database sukses. |
| **H2** | Setup Login, Auth Middleware & Route modules. | Membuat Seeder data riil IKM & tes query model. | **Sync 2:** Merge fondasi awal ke branch `main`. |
| **H3 - H4** | Slicing Modul Bahan Baku & Transaksi IN/OUT. | Slicing Modul QC Dua Tahap & Form Inspeksi. | Mandiri per branch fitur. |
| **H5 - H6** | Slicing Kanban SPK Produksi & Tracking WIP. | Slicing Modul Distribusi, Packing & Surat Jalan. | Mandiri per branch fitur. |
| **H7 - H8** | Validasi form SPK & integrasi bahan baku. | Slicing Dashboard KPI, Chart.js & Forecasting. | Mandiri per branch fitur. |
| **H9** | Uji coba alur dari Bahan Baku $\rightarrow$ SPK. | Uji coba alur dari QC $\rightarrow$ Distribusi $\rightarrow$ Dashboard. | **Sync 3:** Merge semua fitur ke `main`. |
| **H10** | End-to-End Testing alur hulu ke hilir bersama. | Perbaikan bug, styling responsif mobile & verifikasi. | **Ready for Review / Demo.** |
