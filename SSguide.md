# PANDUAN ASET VISUAL, DIAGRAM & DOKUMENTASI LAPORAN KEGIATAN (SSguide.md)
## Sistem Informasi E-Supply Chain Management Klaster IKM Marmer Tulungagung

Panduan ini memuat **matriks inventaris seluruh tangkapan layar (*screenshots*), diagram teknis, foto lapangan, dan data pendukung** untuk dokumen laporan **`.docx`**, **`.pdf`**, dan **`.xlsx`** (Kegiatan 4, 6, 7, 8, dan 9).

---

### 📊 Ringkasan Status Kelengkapan Aset Visual (Update: 24 Agustus 2026)

| Kategori Kegiatan | Total Kebutuhan | Status Tersedia | Status Belum Ada | Persentase Selesai |
| :--- | :---: | :---: | :---: | :---: |
| **Kegiatan 4 (Basis Data & ERD)** | 3 Gambar | 3 Tersedia | 0 | **100%** ✅ |
| **Kegiatan 6 (UI/UX, Sitemap & Usability)** | 7 Aset (5 Gbr + 2 Foto) | 4 Tersedia | 3 Belum Ada | **57.1%** ⚠️ |
| **Kegiatan 7 (Modul Inti MVC Laravel)** | 8 Gambar | 8 Tersedia | 0 | **100%** ✅ |
| **Kegiatan 8 (SOA, Swagger, Cloud VPS)** | 7 Aset (5 Gbr + 2 Foto) | 4 Tersedia | 3 Belum Ada | **57.1%** ⚠️ |
| **Kegiatan 9 (Black-Box & Security Testing)** | 4 Gambar | 4 Tersedia | 0 | **100%** ✅ |
| **TOTAL KESELURUHAN** | **29 Aset** | **23 Tersedia** | **6 Belum Ada** | **79.3% SIAP** 🚀 |

```
📁 Lokasi Penyimpanan Aset:
d:\Documents\Code\Work\web-scm-marmer\Docs\laporan_kegiatan\
```

---

## 🏛️ KEGIATAN 4: Desain Basis Data Relasional & Skema Integrasi Antarmodul

### 1. Matriks Visual & Tangkapan Layar (*Screenshots*)
| Status | Kode & Nama File | Format & Dimensi | Isi Tampilan yang Terlihat | Teks Judul Gambar (Format Skripsi/Jurnal) |
| :---: | :--- | :--- | :--- | :--- |
| <span style="color:green">**[✅ ADA]**</span> | [`IMG-4.1-ERD.png`](./Docs/laporan_kegiatan/IMG-4.1-ERD.png) | PNG (2048 × 1078 px)<br>*200 KB* | Diagram relasi 14 entitas lengkap (`users`, `suppliers`, `materials`, `stock_transactions`, `products`, `categories`, `customers`, `work_orders`, `production_steps`, `qc_logs`, `waste_logs`, `shipments`, `forecasting_logs`, `orders`) beserta relasi 1:N dan tipe datanya. | **Gambar 4.1.** *Entity Relationship Diagram (ERD) Sistem E-Supply Chain Marmer Tulungagung* |
| <span style="color:green">**[✅ ADA]**</span> | [`IMG-4.2-GUI-Database.png`](./Docs/laporan_kegiatan/IMG-4.2-GUI-Database.png) | PNG (1920 × 1080 px)<br>*229 KB* | Tampilan GUI Database Client (**phpMyAdmin / DBeaver / Navicat**) yang menampilkan daftar 14 tabel, engine `InnoDB`, dan collation `utf8mb4_unicode_ci`. | **Gambar 4.2.** *Implementasi Fisik Basis Data Relasional pada Database Management System MySQL 8.0* |
| <span style="color:green">**[✅ ADA]**</span> | [`IMG-4.3-Relasi-Constraint.png`](./Docs/laporan_kegiatan/IMG-4.3-Relasi-Constraint.png) | PNG (1600 × 900 px)<br>*77 KB* | Struktur tabel relasional utama yang memperlihatkan **Foreign Key Constraints** (`cascadeOnDelete`, `restrictOnDelete`, `nullOnDelete`). | **Gambar 4.3.** *Skema Integritas Referensial dan Foreign Key Constraints Antarentitas* |

### 2. Instrumen Data Pendukung di Excel ([`Form_Kegiatan_4.xlsx`](./Docs/laporan_kegiatan/Form_Kegiatan_4_Kamus_Data_dan_Integrasi.xlsx))
* **Sheet 1 (`Kamus Data 14 Tabel`):** Tabel rincian kolom (No, Nama Kolom, Tipe Data, Format/Panjang, Constraint, Target Relasi, dan Deskripsi Bisnis).
* **Sheet 2 (`Form 4.2 Integrasi Antarmodul`):** Matriks 10 aliran data antarmodul hulu–hilir (Modul Asal, Modul Tujuan, Entitas Data, Metode Transfer).

---

## 🎨 KEGIATAN 6: Perancangan Antarmuka Pengguna (UI/UX) & Prototype Interaktif

### 1. Matriks Visual & Tangkapan Layar (*Screenshots*)
| Status | Kode & Nama File | Format & Dimensi | Isi Tampilan yang Terlihat | Teks Judul Gambar |
| :---: | :--- | :--- | :--- | :--- |
| <span style="color:green">**[✅ ADA]**</span> | [`IMG-6.1-Sitemap.png`](./Docs/laporan_kegiatan/IMG-6.1-Sitemap.png) | PNG (1920 × 1080 px)<br>*1.6 MB* | **Diagram Arsitektur Informasi & Sitemap Navigasi** sistem yang memetakan alur menu untuk 6 hak akses (Owner, Admin, Petugas Gudang, Mandor Produksi, Supir Distribusi, dan Konsumen Publik). | **Gambar 6.1.** *Struktur Arsitektur Informasi dan Hierarki Navigasi Pengguna (Sitemap)* |
| <span style="color:red">**[🔴 BELUM]**</span> | `IMG-6.2-Design-System.png` | PNG (1600 × 900 px)<br>*(Prompt ChatGPT Siap)* | **Design System & Style Guide**: <br>• Palet warna primer (`#1E3A8A`), sekunder (`#065F46`), Slate `#0F172A`, dan Amber Alert.<br>• Tipografi (*Inter*) & *Touch Target* tombol (48px ergonomis bengkel). | **Gambar 6.2.** *Design System, Panduan Tipografi, dan Palet Warna Antarmuka E-SCM* |
| <span style="color:green">**[✅ ADA]**</span> | [`IMG-6.3-Mockup-Public.png`](./Docs/laporan_kegiatan/IMG-6.3-Mockup-Public.png) | PNG (1920 × 1080 px)<br>*674 KB* | Mockup **Katalog Publik & Halaman Detail Produk**: Foto kerajinan marmer, harga jual, spesifikasi dimensi, dan tombol CTA Direct Checkout. | **Gambar 6.3.** *Rancangan Mockup Antarmuka Katalog Kerajinan Marmer & Detail Produk* |
| <span style="color:green">**[✅ ADA]**</span> | [`IMG-6.4-Mockup-Checkout.png`](./Docs/laporan_kegiatan/IMG-6.4-Mockup-Checkout.png) | PNG (1600 × 900 px)<br>*159 KB* | Mockup **Halaman Checkout & Digital Invoice Ber-QRIS**: Pilihan skema bayar (DP 50% / Lunas 100%), pilihan bank/QRIS, rincian biaya, dan kode unik. | **Gambar 6.4.** *Rancangan Antarmuka Formulir Checkout dan Invoice Digital Interaktif* |
| <span style="color:green">**[✅ ADA]**</span> | [`IMG-6.5-Mockup-Admin.png`](./Docs/laporan_kegiatan/IMG-6.5-Mockup-Admin.png) | PNG (1920 × 1080 px)<br>*259 KB* | Mockup **Dashboard SCM & Kanban Board Produksi**: Kartu metrik KPI hulu-hilir, grafik pemakaian batu, serta papan kartu pengerjaan mesin bubut/slep. | **Gambar 6.5.** *Rancangan Antarmuka Dashboard Monitoring Operasional dan Kanban Board SPK* |

### 2. Foto Dokumentasi Empiris Lapangan
| Status | Kode & Nama File | Subjek & Lokasi Foto | Aktivitas yang Difoto | Teks Judul Foto |
| :---: | :--- | :--- | :--- | :--- |
| <span style="color:red">**[🔴 BELUM]**</span> | `FOTO-6.1-Usability-Testing.jpg` | Pak M. Ilham / Pak Suparno di galeri **UD Cahaya Onix (Besole)** | Pengguna sedang menguji prototype sistem di layar laptop/tablet untuk evaluasi kemudahan navigasi form pesanan. | **Gambar 6.6.** *Dokumentasi Pengujian Usability Testing Prototype Bersama Pemilik IKM UD Cahaya Onix* |
| <span style="color:red">**[🔴 BELUM]**</span> | `FOTO-6.2-Observasi-Bengkel.jpg` | Mandor / Operator mesin di bengkel **UD Putra Abadi (Campurdarat)** | Operator sedang melihat rancangan form SPK di layar smartphone untuk validasi keterbacaan font di lantai produksi. | **Gambar 6.7.** *Validasi Keterbacaan Antarmuka Form Produksi pada Kondisi Pencahayaan Bengkel Marmer* |

### 3. Instrumen Data Pendukung di Excel ([`Form_Kegiatan_6.xlsx`](./Docs/laporan_kegiatan/Form_Kegiatan_6_Persona_dan_Usability_Testing.xlsx))
* **Sheet 1 (`User Persona Empiris`):** Data demografi, tanggung jawab, kendala, dan kebutuhan 4 persona nyata (*Ilham, Efri, Suparno, Misno & Suyanto*).
* **Sheet 2 (`Form 6.2 Usability Testing`):** Hasil pengujian 5 skenario tugas operasional (Tingkat Keberhasilan 100%, Durasi Rata-rata Tugas, Skor Efisiensi).

---

## 💻 KEGIATAN 7: Pengkodean Modul Inti Aplikasi (MVC Laravel 11)

### 1. Matriks Visual & Tangkapan Layar (*Screenshots*)
| Status | Kode & Nama File | Format & Dimensi | Isi Tampilan yang Terlihat | Teks Judul Gambar |
| :---: | :--- | :--- | :--- | :--- |
| <span style="color:green">**[✅ ADA]**</span> | [`IMG-7.1-MVC-Architecture.png`](./Docs/laporan_kegiatan/IMG-7.1-MVC-Architecture.png) | PNG (1600 × 900 px)<br>*1.3 MB* | **Diagram Arsitektur Perangkat Lunak MVC Laravel 11**: Aliran data dari *Client Request* $\rightarrow$ *Web Routes* $\rightarrow$ *Auth Middleware* $\rightarrow$ *Controllers* $\rightarrow$ *Eloquent Models* $\leftrightarrow$ *Database (MySQL)* $\rightarrow$ *Blade Views / JSON*. | **Gambar 7.1.** *Arsitektur Perangkat Lunak Model-View-Controller (MVC) pada Framework Laravel 11* |
| <span style="color:green">**[✅ ADA]**</span> | [`IMG-7.2-UI-BahanBaku.png`](./Docs/laporan_kegiatan/IMG-7.2-UI-BahanBaku.png) | PNG (1920 × 1080 px)<br>*187 KB* | Tampilan **Modul Inventaris Bahan Baku (`/materials`)**: Tabel master bongkahan marmer/onyx, badge alert stok minimum, dan modal transaksi mutasi. | **Gambar 7.2.** *Antarmuka Modul Manajemen Inventaris dan Log Mutasi Bahan Baku Marmer* |
| <span style="color:green">**[✅ ADA]**</span> | [`IMG-7.3-UI-Produksi-SPK.png`](./Docs/laporan_kegiatan/IMG-7.3-UI-Produksi-SPK.png) | PNG (1920 × 1080 px)<br>*156 KB* | Tampilan **Modul Produksi & SPK Digital (`/production`)**: Daftar nomor SPK (`SPK-2026-xxx`), target unit wastafel, dan Kanban status pengerjaan mesin. | **Gambar 7.3.** *Antarmuka Modul Surat Perintah Kerja (SPK) Digital dan Pelacakan Stasiun Produksi* |
| <span style="color:green">**[✅ ADA]**</span> | [`IMG-7.4-UI-QC-Inspeksi.png`](./Docs/laporan_kegiatan/IMG-7.4-UI-QC-Inspeksi.png) | PNG (1920 × 1080 px)<br>*140 KB* | Tampilan **Modul Quality Control 2-Tahap (`/qc`)**: Form pemeriksaan QC 1 (bentuk mentah bubut) & QC 2 (poles halus), kuantitas pass, rework, dan scrap. | **Gambar 7.4.** *Antarmuka Modul Pengendalian Kualitas (Quality Control) Dua Tahap dan Integrasi Stok* |
| <span style="color:green">**[✅ ADA]**</span> | [`IMG-7.5-UI-Waste-Residu.png`](./Docs/laporan_kegiatan/IMG-7.5-UI-Waste-Residu.png) | PNG (1920 × 1080 px)<br>*173 KB* | Tampilan **Modul Manajemen Residu / Limbah (`/waste`)**: Tabel pencatatan berat (kg) sisa slep marmer layak ornamen dinding (*cladding/teraso*). | **Gambar 7.5.** *Antarmuka Modul Pencatatan Residu Pemotongan Marmer untuk Hilirisasi Limbah* |
| <span style="color:green">**[✅ ADA]**</span> | [`IMG-7.6-UI-Distribusi.png`](./Docs/laporan_kegiatan/IMG-7.6-UI-Distribusi.png) | PNG (1920 × 1080 px)<br>*337 KB* | Tampilan **Modul Distribusi & Logistik (`/distribution`)**: Penerbitan Surat Jalan (SJ), verifikasi packing peti kayu, armada truk, dan tracking pengiriman. | **Gambar 7.6.** *Antarmuka Modul Surat Jalan Pengiriman dan Checklist Verifikasi Packing Peti Kayu* |
| <span style="color:green">**[✅ ADA]**</span> | [`IMG-7.7-UI-Ecommerce-Public.png`](./Docs/laporan_kegiatan/IMG-7.7-UI-Ecommerce-Public.png) | PNG (1920 × 1080 px)<br>*421 KB* | Tampilan **Katalog Publik, Form Direct Checkout, & QR Invoice**: Form pemesanan mandiri oleh konsumen, kode unik, serta invoice digital dengan QRIS. | **Gambar 7.7.** *Antarmuka Modul E-Commerce Publik, Formulir Checkout Mandiri, dan Invoice QRIS* |
| <span style="color:green">**[✅ ADA]**</span> | [`IMG-7.8-UI-Orders-Admin.png`](./Docs/laporan_kegiatan/IMG-7.8-UI-Orders-Admin.png) | PNG (1920 × 1080 px)<br>*179 KB* | Tampilan **Modul Manajemen Pesanan Masuk Admin (`/orders`)**: Tabel verifikasi pembayaran, tombol "Verifikasi & Terbitkan SPK", dan filter order. | **Gambar 7.8.** *Antarmuka Panel Admin Manajemen Pesanan Masuk dan Konversi Otomatis ke SPK* |

### 2. Instrumen Data Pendukung di Excel ([`Form_Kegiatan_7.xlsx`](./Docs/laporan_kegiatan/Form_Kegiatan_7_Matriks_Modul_dan_Pengujian_Rute.xlsx))
* **Sheet 1 (`Matriks 9 Modul & Controller`):** Rincian 9 modul sistem, file controller terkait, model Eloquent, dan file Blade View.
* **Sheet 2 (`Form 7.2 Uji 36 Rute HTTP`):** Hasil verifikasi 36 rute URL sistem (URI, Method, Middleware Auth/Role, Status HTTP 200 OK / 302 Redirect).

---

## ☁️ KEGIATAN 8: Integrasi Algoritma ke Sistem & Deployment ke Cloud Server

### 1. Matriks Visual & Tangkapan Layar (*Screenshots*)
| Status | Kode & Nama File | Format & Dimensi | Isi Tampilan yang Terlihat | Teks Judul Gambar |
| :---: | :--- | :--- | :--- | :--- |
| <span style="color:green">**[✅ ADA]**</span> | [`IMG-8.1-SOA-Architecture.png`](./Docs/laporan_kegiatan/IMG-8.1-SOA-Architecture.png) | PNG (1600 × 900 px)<br>*1.4 MB* | **Diagram Arsitektur Layanan Terpisah (*Decoupled SOA*)**: Aliran data JSON antara Laravel Backend (Port 80/443) $\leftrightarrow$ Python FastAPI Microservice (Port 8001) $\leftrightarrow$ *Local Calculation Fallback*. | **Gambar 8.1.** *Arsitektur Integrasi Layanan Algoritma Peramalan Berbasis Microservice dan Fail-Safe Fallback* |
| <span style="color:green">**[✅ ADA]**</span> | [`IMG-8.2-Swagger-API.png`](./Docs/laporan_kegiatan/IMG-8.2-Swagger-API.png) | PNG (1920 × 1080 px)<br>*74 KB* | Tangkapan layar **FastAPI Interactive API Documentation (Swagger UI)** di `/docs`: Endpoint `/api/forecast/predict` (ARIMA, SES, Holt-Winters), Request Schema, dan Response JSON. | **Gambar 8.2.** *Dokumentasi Interaktif REST API Microservice Peramalan pada OpenAPI/Swagger UI* |
| <span style="color:green">**[✅ ADA]**</span> | [`IMG-8.3-UI-Forecasting-Chart.png`](./Docs/laporan_kegiatan/IMG-8.3-UI-Forecasting-Chart.png) | PNG (1920 × 1080 px)<br>*353 KB* | Tangkapan layar **Halaman Peramalan SCM (`/forecasting`)**: Grafik Chart.js perbandingan historis vs proyeksi kebutuhan bahan baku beserta tabel evaluasi **MAPE (%)** dan **RMSE**. | **Gambar 8.3.** *Visualisasi Hasil Komputasi Algoritma Peramalan Kebutuhan Bahan Baku pada Dashboard Web* |
| <span style="color:red">**[🔴 BELUM]**</span> | `IMG-8.4-VPS-Terminal-Status.png` | PNG (1600 × 900 px)<br>*(Screenshot Terminal VPS)* | Tangkapan layar terminal **SSH Server Cloud VPS Ubuntu 24.04 LTS (IP: `202.155.91.151`)**: Status `systemctl status nginx php8.3-fpm mysql` menunjukkan `active (running)`. | **Gambar 8.4.** *Status Pelayanan Web Server Nginx, PHP-FPM, dan MySQL pada Cloud Server VPS Ubuntu Linux* |
| <span style="color:green">**[✅ ADA]**</span> | [`IMG-8.5-SSL-Cert-Browser.png`](./Docs/laporan_kegiatan/IMG-8.5-SSL-Cert-Browser.png) | PNG (1280 × 720 px)<br>*43 KB* | Tangkapan layar **URL Bar Browser & Jendela Sertifikat Keamanan SSL**: Alamat resmi `https://onyxtulungagung.id` dengan gembok aman (*Let's Encrypt TLS 1.3*). | **Gambar 8.5.** *Verifikasi Implementasi Protokol Keamanan HTTPS dan Sertifikat SSL Let's Encrypt* |

### 2. Foto Pengujian Akses Multi-Device di Lapangan
| Status | Kode & Nama File | Subjek & Perangkat Pengujian | Lokasi & Skenario Pengujian | Teks Judul Foto |
| :---: | :--- | :--- | :--- | :--- |
| <span style="color:red">**[🔴 BELUM]**</span> | `FOTO-8.1-Test-Smartphone.jpg` | Smartphone Android supir / mandor | Di area muat galeri: Form ceklis surat jalan yang responsif dan mudah ditekan di layar sentuh HP. | **Gambar 8.6.** *Pengujian Aksesibilitas dan Responsivitas Sistem pada Perangkat Smartphone Lapangan* |
| <span style="color:red">**[🔴 BELUM]**</span> | `FOTO-8.2-Test-PC-Office.jpg` | Laptop / PC desktop manajemen IKM | Di kantor administrasi UD Cahaya Onix: Dashboard monitoring analitik multi-kolom di layar monitor PC. | **Gambar 8.7.** *Verifikasi Tampilan Dashboard Monitoring Manajemen SCM pada Perangkat PC Desktop Kantor* |

### 3. Instrumen Data Pendukung di Excel ([`Form_Kegiatan_8.xlsx`](./Docs/laporan_kegiatan/Form_Kegiatan_8_Integrasi_API_dan_Uji_Multi_Device.xlsx))
* **Sheet 1 (`Form 8.1 Uji REST API`):** Matriks pengujian 6 skenario integrasi API (Healthcheck, Holt-Winters, ARIMA, SES, Error Handling, Fallback).
* **Sheet 2 (`Form 8.2 Uji Multi-Device`):** Matriks pengujian pada 4 kategori resolusi layar (Smartphone 390px, Tablet 768px, Laptop 1366px, PC 1920px).

---

## 🧪 KEGIATAN 9: Uji Fungsionalitas Sistem (Black-Box Testing)

### 1. Matriks Visual & Tangkapan Layar (*Screenshots*)
| Status | Kode & Nama File | Format & Dimensi | Isi Tampilan yang Terlihat | Teks Judul Gambar |
| :---: | :--- | :--- | :--- | :--- |
| <span style="color:green">**[✅ ADA]**</span> | [`IMG-9.1-PHPUnit-Terminal.png`](./Docs/laporan_kegiatan/IMG-9.1-PHPUnit-Terminal.png) | PNG (1600 × 900 px)<br>*187 KB* | Tangkapan layar terminal eksekusi **PHPUnit Test Suite (`php artisan test tests/Feature/`)**: Baris hijau kelulusan pengujian **`Tests: 35 passed (170 assertions)`** (100% PASS). | **Gambar 9.1.** *Hasil Eksekusi Otomatis Test Suite Black-Box Testing Menggunakan PHPUnit Framework (100% PASS)* |
| <span style="color:green">**[✅ ADA]**</span> | [`IMG-9.2-Test-Auth-Validation.png`](./Docs/laporan_kegiatan/IMG-9.2-Test-Auth-Validation.png) | PNG (1400 × 800 px)<br>*65 KB* | Tangkapan layar **Uji Kasus Kunci Validasi Input & Flash Message**: Penolakan login kredensial salah dengan pesan alert merah. | **Gambar 9.2.** *Pengujian Boundary Value Analysis dan Penanganan Kesalahan Input pada Form Transaksi* |
| <span style="color:green">**[✅ ADA]**</span> | [`IMG-9.3-Test-ZeroPollution.png`](./Docs/laporan_kegiatan/IMG-9.3-Test-ZeroPollution.png) | PNG (1600 × 900 px)<br>*172 KB* | Tangkapan layar bukti **Arsitektur Keamanan 2-Gate**: Bukti pesanan checkout konsumen yang belum dibayar tidak mencemari antrean produksi di bengkel (*Zero Workshop Pollution*). | **Gambar 9.3.** *Verifikasi Isolasi Antrean Produksi terhadap Transaksi Belum Terverifikasi (2-Gate Order System)* |
| <span style="color:green">**[✅ ADA]**</span> | [`IMG-9.4-Test-RateLimiter.png`](./Docs/laporan_kegiatan/IMG-9.4-Test-RateLimiter.png) | PNG (1400 × 800 px)<br>*14 KB* | Tangkapan layar penolakan **Anti-Spam Bot Flooding**: Tampilan respons `HTTP 429 Too Many Requests` saat form checkout diserang berturut-turut dari 1 IP. | **Gambar 9.4.** *Pengujian Ketahanan Sistem terhadap Serangan Bot Spamming Menggunakan IP Rate Limiting* |

### 2. Instrumen Data Pendukung di Excel ([`Form_Kegiatan_9.xlsx`](./Docs/laporan_kegiatan/Form_Kegiatan_9_Matriks_BlackBox_dan_Regression_Testing.xlsx))
* **Sheet 1 (`Form 9.1 Matriks 20 Test Case Backend`):** Detail 20 skenario backend (TC-AUTH, TC-STK, TC-PRD, TC-QC, TC-DST, TC-FOR, Input Uji, Ekspektasi, Status PASS).
* **Sheet 2 (`Form 9.2 Matriks 15 Test Case E2E & Security`):** Detail 15 skenario UI, alur checkout publik, konversi admin order, proteksi SQLi, XSS, dan Anti-Spam.

---

## 🎯 DAFTAR 6 ASET YANG BELUM TERSEDIA (ACTION PLAN)

Berikut adalah daftar 6 item yang masih berstatus **🔴 BELUM TERSEDIA** beserta petunjuk penyelesaiannya:

1. **`IMG-6.2-Design-System.png`** (Kegiatan 6):
   - **Tindakan:** Masukkan prompt ChatGPT untuk Design System yang telah dibuat sebelumnya ke DALL·E 3 / AI Image Generator, unduh hasilnya (format 16:9), dan simpan ke `Docs/laporan_kegiatan/IMG-6.2-Design-System.png`.
2. **`IMG-8.4-VPS-Terminal-Status.png`** (Kegiatan 8):
   - **Tindakan:** Buka terminal SSH ke Cloud VPS (`202.155.91.151`), jalankan perintah: `systemctl status nginx php8.3-fpm mysql`, ambil screenshot terminal, dan simpan ke `Docs/laporan_kegiatan/IMG-8.4-VPS-Terminal-Status.png`.
3. **`FOTO-6.1-Usability-Testing.jpg`** (Kegiatan 6):
   - **Tindakan:** Foto dokumentasi saat pemilik/mandor IKM (Pak Ilham / Pak Suparno) mencoba prototype di laptop/tablet di galeri UD Cahaya Onix Besole.
4. **`FOTO-6.2-Observasi-Bengkel.jpg`** (Kegiatan 6):
   - **Tindakan:** Foto dokumentasi operator/mandor melihat antarmuka form SPK di smartphone di area bengkel bubut UD Putra Abadi Campurdarat.
5. **`FOTO-8.1-Test-Smartphone.jpg`** (Kegiatan 8):
   - **Tindakan:** Foto fisik smartphone yang membuka modul surat jalan/distribusi di area muat barang galeri marmer.
6. **`FOTO-8.2-Test-PC-Office.jpg`** (Kegiatan 8):
   - **Tindakan:** Foto fisik laptop/PC kantor yang menampilkan dashboard eksekutif SCM.

---

### 📦 Rekapitulasi Berkas Dokumen Laporan Siap Kumpul:

| Dokumen Kegiatan | Microsoft Word (`.docx`) | Adobe PDF (`.pdf`) | Formulir Excel (`.xlsx`) | Markdown Master (`.md`) |
| :--- | :---: | :---: | :---: | :---: |
| **Kegiatan 4 (Desain Basis Data & Integrasi)** | [📝 `Kegiatan_4.docx`](./Docs/laporan_kegiatan/Kegiatan_4_Desain_Basis_Data_dan_Integrasi.docx) | [📄 `Kegiatan_4.pdf`](./Docs/laporan_kegiatan/Kegiatan_4_Desain_Basis_Data_dan_Integrasi.pdf) | [📊 `Form_Kegiatan_4.xlsx`](./Docs/laporan_kegiatan/Form_Kegiatan_4_Kamus_Data_dan_Integrasi.xlsx) | [📄 `Kegiatan_4.md`](./Docs/laporan_kegiatan/Kegiatan_4_Desain_Basis_Data_dan_Integrasi.md) |
| **Kegiatan 6 (UI/UX & Prototype)** | [📝 `Kegiatan_6.docx`](./Docs/laporan_kegiatan/Kegiatan_6_Perancangan_Antarmuka_Pengguna_UI_UX.docx) | [📄 `Kegiatan_6.pdf`](./Docs/laporan_kegiatan/Kegiatan_6_Perancangan_Antarmuka_Pengguna_UI_UX.pdf) | [📊 `Form_Kegiatan_6.xlsx`](./Docs/laporan_kegiatan/Form_Kegiatan_6_Persona_dan_Usability_Testing.xlsx) | [📄 `Kegiatan_6.md`](./Docs/laporan_kegiatan/Kegiatan_6_Perancangan_Antarmuka_Pengguna_UI_UX.md) |
| **Kegiatan 7 (Coding Modul Inti MVC)** | [📝 `Kegiatan_7.docx`](./Docs/laporan_kegiatan/Kegiatan_7_Pengkodean_Modul_Inti_Aplikasi.docx) | [📄 `Kegiatan_7.pdf`](./Docs/laporan_kegiatan/Kegiatan_7_Pengkodean_Modul_Inti_Aplikasi.pdf) | [📊 `Form_Kegiatan_7.xlsx`](./Docs/laporan_kegiatan/Form_Kegiatan_7_Matriks_Modul_dan_Pengujian_Rute.xlsx) | [📄 `Kegiatan_7.md`](./Docs/laporan_kegiatan/Kegiatan_7_Pengkodean_Modul_Inti_Aplikasi.md) |
| **Kegiatan 8 (Integrasi & Cloud Deployment)** | [📝 `Kegiatan_8.docx`](./Docs/laporan_kegiatan/Kegiatan_8_Integrasi_Algoritma_dan_Deployment_Cloud.docx) | [📄 `Kegiatan_8.pdf`](./Docs/laporan_kegiatan/Kegiatan_8_Integrasi_Algoritma_dan_Deployment_Cloud.pdf) | [📊 `Form_Kegiatan_8.xlsx`](./Docs/laporan_kegiatan/Form_Kegiatan_8_Integrasi_API_dan_Uji_Multi_Device.xlsx) | [📄 `Kegiatan_8.md`](./Docs/laporan_kegiatan/Kegiatan_8_Integrasi_Algoritma_dan_Deployment_Cloud.md) |
| **Kegiatan 9 (Black-Box Testing)** | [📝 `Kegiatan_9.docx`](./Docs/laporan_kegiatan/Kegiatan_9_Uji_Fungsionalitas_Black_Box_Testing.docx) | [📄 `Kegiatan_9.pdf`](./Docs/laporan_kegiatan/Kegiatan_9_Uji_Fungsionalitas_Black_Box_Testing.pdf) | [📊 `Form_Kegiatan_9.xlsx`](./Docs/laporan_kegiatan/Form_Kegiatan_9_Matriks_BlackBox_dan_Regression_Testing.xlsx) | [📄 `Kegiatan_9.md`](./Docs/laporan_kegiatan/Kegiatan_9_Uji_Fungsionalitas_Black_Box_Testing.md) |