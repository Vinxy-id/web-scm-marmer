Berikut adalah **panduan rincian lengkap seluruh foto, tangkapan layar (*screenshots*), diagram teknis, dan data pendukung** yang sangat direkomendasikan untuk disertakan ke dalam dokumen laporan **`.docx`**, **`.pdf`**, dan **`.xlsx`** agar memiliki bobot akademik yang kuat, kaya visual (*rich visual evidence*), serta dinilai sangat matang dan siap uji oleh Dosen Pembimbing, Dewan Penguji, maupun Reviewer Hibah.

---

```
📁 Struktur Folder Rekomendasi Aset Dokumentasi:
d:\Project Coding\Web SCM\Docs\screenshots\
   ├── kegiatan_4\  (ERD, Skema DDL, & GUI Database)
   ├── kegiatan_6\  (Sitemap, Design System, Mockup UI, & Foto Uji Usability)
   ├── kegiatan_7\  (Tangkapan Layar 9 Modul Inti & Terminal PHPUnit)
   ├── kegiatan_8\  (Swagger API, Chart AI, Cloud VPS, SSL, & Foto Multi-Device)
   └── kegiatan_9\  (Test Suite 170 Assertions, Form Validasi, & Uji Anti-Spam)
```

---

## 🏛️ KEGIATAN 4: Desain Basis Data Relasional & Skema Integrasi Antarmodul

### 1. Kebutuhan Visual & Tangkapan Layar (*Screenshots*)
| Kode & Nama File | Format & Dimensi | Isi Tampilan yang Harus Terlihat | Teks Judul Gambar (Format Skripsi/Jurnal) |
| :--- | :--- | :--- | :--- |
| `IMG-4.1-ERD.png` | PNG (2048 × 1078 px)<br>*Sudah Tersedia* | Diagram relasi 14 entitas lengkap (`users`, `suppliers`, `materials`, `stock_transactions`, `products`, `categories`, `customers`, `work_orders`, `production_steps`, `qc_logs`, `waste_logs`, `shipments`, `forecasting_logs`, `orders`) beserta relasi 1:N dan tipe datanya. | **Gambar 4.1.** *Entity Relationship Diagram (ERD) Sistem E-Supply Chain Marmer Tulungagung* |
| `IMG-4.2-GUI-Database.png` | PNG (1920 × 1080 px)<br>*Rasio 16:9* | Tampilan GUI Database Client (**phpMyAdmin / DBeaver / Navicat**) yang menampilkan: <br>• Daftar 14 tabel di database `db_escm_marmer`.<br>• *Engine* `InnoDB`, *Collation* `utf8mb4_unicode_ci`, dan ukuran baris data (*Row counts*). | **Gambar 4.2.** *Implementasi Fisik Basis Data Relasional pada Database Management System MySQL 8.0* |
| `IMG-4.3-Relasi-Constraint.png` | PNG (1600 × 900 px) | Tangkapan layar struktur tabel relasional utama (misal: tabel `orders` atau `production_steps`) yang memperlihatkan **Foreign Key Constraints** (`cascadeOnDelete`, `restrictOnDelete`, `nullOnDelete`). | **Gambar 4.3.** *Skema Integritas Referensial dan Foreign Key Constraints Antarentitas* |

### 2. Instrumen Data Pendukung di Excel ([`Form_Kegiatan_4.xlsx`](file:///d:/Project%20Coding/Web%20SCM/Docs/laporan_kegiatan/Form_Kegiatan_4_Kamus_Data_dan_Integrasi.xlsx))
* **Sheet 1 (`Kamus Data 14 Tabel`):** Tabel rincian kolom (No, Nama Kolom, Tipe Data, Format/Panjang, Constraint, Target Relasi, dan Deskripsi Bisnis Lapangan).
* **Sheet 2 (`Form 4.2 Integrasi Antarmodul`):** Matriks 10 aliran data antarmodul hulu–hilir (Modul Asal, Modul Tujuan, Entitas Data, Metode Transfer, dan Pemicu/Frekuensi).

---

## 🎨 KEGIATAN 6: Perancangan Antarmuka Pengguna (UI/UX) & Prototype Interaktif

### 1. Kebutuhan Visual & Tangkapan Layar (*Screenshots*)
| Kode & Nama File | Format & Dimensi | Isi Tampilan yang Harus Terlihat | Teks Judul Gambar |
| :--- | :--- | :--- | :--- |
| `IMG-6.1-Sitemap.png` | PNG (1920 × 1080 px) | **Diagram Arsitektur Informasi & Sitemap Navigasi** sistem yang memetakan alur menu untuk 6 hak akses (Owner, Admin, Petugas Gudang, Mandor Produksi, Supir Distribusi, dan Konsumen Publik). | **Gambar 6.1.** *Struktur Arsitektur Informasi dan Hierarki Navigasi Pengguna (Sitemap)* |
| `IMG-6.2-Design-System.png` | PNG (1600 × 900 px) | **Design System & Style Guide**: <br>• Palet warna primer (Deep Navy `#1E3A8A`), sekunder (Emerald Green `#065F46`), Slate `#0F172A`, dan Amber Alert.<br>• Tipografi (*Inter / Segoe UI*) & *Touch Target* tombol (44–48px agar ergonomis bagi pekerja bengkel bersarung tangan). | **Gambar 6.2.** *Design System, Panduan Tipografi, dan Palet Warna Antarmuka E-SCM* |
| `IMG-6.3-Mockup-Public.png` | PNG (1920 × 1080 px) | Tampilan Mockup/Wireframe **Katalog Publik & Halaman Detail Produk**: <br>• Foto kerajinan marmer, harga jual, spesifikasi dimensi, dan tombol CTA "Pesan Sekarang (Direct Checkout)". | **Gambar 6.3.** *Rancangan Mockup Antarmuka Katalog Kerajinan Marmer & Detail Produk* |
| `IMG-6.4-Mockup-Checkout.png` | PNG (1600 × 900 px) | Mockup **Halaman Checkout & Digital Invoice Ber-QRIS**: <br>• Pilihan skema bayar (DP 50% / Lunas 100%), pilihan bank/QRIS, rincian biaya, dan kode unik transaksi. | **Gambar 6.4.** *Rancangan Antarmuka Formulir Checkout dan Invoice Digital Interaktif* |
| `IMG-6.5-Mockup-Admin.png` | PNG (1920 × 1080 px) | Mockup **Dashboard SCM & Kanban Board Produksi**: <br>• Kartu metrik KPI hulu-hilir, grafik pemakaian batu, serta papan kartu pengerjaan mesin bubut/slep. | **Gambar 6.5.** *Rancangan Antarmuka Dashboard Monitoring Operasional dan Kanban Board SPK* |

### 2. Foto Dokumentasi Empiris Lapangan
| Kode & Nama File | Subjek & Lokasi Foto | Aktivitas yang Difoto | Teks Judul Foto |
| :--- | :--- | :--- | :--- |
| `FOTO-6.1-Usability-Testing.jpg` | Pak M. Ilham Nur Amali / Pak Suparno di kantor/galeri **UD Cahaya Onix (Besole)** | Pengguna sedang menguji prototype sistem di layar laptop/tablet untuk mengevaluasi kemudahan navigasi form pesanan. | **Gambar 6.6.** *Dokumentasi Pengujian Usability Testing Prototype Bersama Pemilik IKM UD Cahaya Onix* |
| `FOTO-6.2-Observasi-Bengkel.jpg` | Mandor / Operator mesin di bengkel **UD Putra Abadi (Campurdarat)** | Operator sedang melihat rancangan form SPK di layar smartphone untuk validasi keterbacaan font di lantai produksi. | **Gambar 6.7.** *Validasi Keterbacaan Antarmuka Form Produksi pada Kondisi Pencahayaan Bengkel Marmer* |

### 3. Instrumen Data Pendukung di Excel ([`Form_Kegiatan_6.xlsx`](file:///d:/Project%20Coding/Web%20SCM/Docs/laporan_kegiatan/Form_Kegiatan_6_Persona_dan_Usability_Testing.xlsx))
* **Sheet 1 (`User Persona Empiris`):** Data demografi, tanggung jawab, kendala (*pain points*), dan target kebutuhan 4 persona nyata (*M. Ilham Nur Amali, Efri Saputra, Suparno, Misno & Suyanto*).
* **Sheet 2 (`Form 6.2 Usability Testing`):** Hasil pengujian 5 skenario tugas operasional (Tingkat Keberhasilan 100%, Durasi Rata-rata Tugas, Skor Efisiensi, dan Status Lolos).

---

## 💻 KEGIATAN 7: Pengkodean Modul Inti Aplikasi (MVC Laravel 11)

### 1. Kebutuhan Visual & Tangkapan Layar (*Screenshots*)
| Kode & Nama File | Format & Dimensi | Isi Tampilan yang Harus Terlihat | Teks Judul Gambar |
| :--- | :--- | :--- | :--- |
| `IMG-7.1-MVC-Architecture.png` | PNG (1600 × 900 px) | **Diagram Arsitektur Perangkat Lunak MVC Laravel 11**: <br>Aliran data dari *Client Request* $\rightarrow$ *Web Routes* $\rightarrow$ *Auth Middleware* $\rightarrow$ *Controllers* $\rightarrow$ *Eloquent Models* $\leftrightarrow$ *Database (MySQL)* $\rightarrow$ *Blade Views / JSON Response*. | **Gambar 7.1.** *Arsitektur Perangkat Lunak Model-View-Controller (MVC) pada Framework Laravel 11* |
| `IMG-7.2-UI-BahanBaku.png` | PNG (1920 × 1080 px) | Tampilan **Modul Inventaris Bahan Baku (`/materials`)**: <br>• Tabel master bongkahan marmer/onyx, badge alert stok minimum, dan modal transaksi mutasi batu masuk/keluar. | **Gambar 7.2.** *Antarmuka Modul Manajemen Inventaris dan Log Mutasi Bahan Baku Marmer* |
| `IMG-7.3-UI-Produksi-SPK.png` | PNG (1920 × 1080 px) | Tampilan **Modul Produksi & SPK Digital (`/production`)**: <br>• Daftar nomor SPK (`SPK-2026-xxx`), target unit wastafel/stepping stone, prioritas kerja, dan Kanban status pengerjaan mesin. | **Gambar 7.3.** *Antarmuka Modul Surat Perintah Kerja (SPK) Digital dan Pelacakan Stasiun Produksi* |
| `IMG-7.4-UI-QC-Inspeksi.png` | PNG (1920 × 1080 px) | Tampilan **Modul Quality Control 2-Tahap (`/qc`)**: <br>• Form pemeriksaan QC 1 (bentuk kasar bubut) & QC 2 (poles halus), kuantitas lolos (*Pass*), unit retak (*Scrap*), dan unit tambal resin (*Rework*). | **Gambar 7.4.** *Antarmuka Modul Pengendalian Kualitas (Quality Control) Dua Tahap dan Integrasi Stok* |
| `IMG-7.5-UI-Waste-Residu.png` | PNG (1920 × 1080 px) | Tampilan **Modul Manajemen Residu / Limbah (`/waste`)**: <br>• Tabel pencatatan berat (kg) sisa slep marmer yang layak dijadikan ornamen dinding (*cladding/teraso*). | **Gambar 7.5.** *Antarmuka Modul Pencatatan Residu Pemotongan Marmer untuk Hilirisasi Limbah* |
| `IMG-7.6-UI-Distribusi.png` | PNG (1920 × 1080 px) | Tampilan **Modul Distribusi & Logistik (`/distribution`)**: <br>• Penerbitan Surat Jalan (SJ), verifikasi packing peti kayu, armada truk ekspedisi, dan update status pengiriman. | **Gambar 7.6.** *Antarmuka Modul Surat Jalan Pengiriman dan Checklist Verifikasi Packing Peti Kayu* |
| `IMG-7.7-UI-Ecommerce-Public.png` | PNG (1920 × 1080 px) | Tampilan **Katalog Publik, Form Direct Checkout, & QR Invoice**: <br>• Form pemesanan langsung produk oleh konsumen, perhitungan kode unik, serta invoice digital dengan QRIS. | **Gambar 7.7.** *Antarmuka Modul E-Commerce Publik, Formulir Checkout Mandiri, dan Invoice QRIS* |
| `IMG-7.8-UI-Orders-Admin.png` | PNG (1920 × 1080 px) | Tampilan **Modul Manajemen Pesanan Masuk Admin (`/orders`)**: <br>• Tabel verifikasi pembayaran pembeli, tombol "Verifikasi & Terbitkan SPK", filter status pesanan, dan modal pembatalan spam. | **Gambar 7.8.** *Antarmuka Panel Admin Manajemen Pesanan Masuk dan Konversi Otomatis ke SPK* |

### 2. Instrumen Data Pendukung di Excel ([`Form_Kegiatan_7.xlsx`](file:///d:/Project%20Coding/Web%20SCM/Docs/laporan_kegiatan/Form_Kegiatan_7_Matriks_Modul_dan_Pengujian_Rute.xlsx))
* **Sheet 1 (`Matriks 9 Modul & Controller`):** Rincian 9 modul sistem, nama file controller terkait, model Eloquent yang diakses, file Blade View, serta fungsionalitas bisnisnya.
* **Sheet 2 (`Form 7.2 Uji 36 Rute HTTP`):** Hasil verifikasi 36 rute URL sistem (URI, Method GET/POST/PUT/DELETE, Middleware Auth/Role, Status HTTP 200 OK / 302 Redirect, dan Responsivitas).

---

## ☁️ KEGIATAN 8: Integrasi Algoritma ke Sistem & Deployment ke Cloud Server

### 1. Kebutuhan Visual & Tangkapan Layar (*Screenshots*)
| Kode & Nama File | Format & Dimensi | Isi Tampilan yang Harus Terlihat | Teks Judul Gambar |
| :--- | :--- | :--- | :--- |
| `IMG-8.1-SOA-Architecture.png` | PNG (1600 × 900 px) | **Diagram Arsitektur Layanan Terpisah (*Decoupled SOA*)**: <br>Aliran data JSON antara Laravel Backend (Port 80/443) $\leftrightarrow$ Python FastAPI Microservice (Port 8001) $\leftrightarrow$ *Built-in Local Calculation Engine Fallback*. | **Gambar 8.1.** *Arsitektur Integrasi Layanan Algoritma Peramalan Berbasis Microservice dan Fail-Safe Fallback* |
| `IMG-8.2-Swagger-API.png` | PNG (1920 × 1080 px) | Tangkapan layar **FastAPI Interactive API Documentation (Swagger UI)** di `/docs`: <br>• Endpoint `/api/forecast/predict` (Model ARIMA, SES, Holt-Winters), Request Schema (data deret waktu historis), dan Response JSON (prediksi stok + metrik evaluasi). | **Gambar 8.2.** *Dokumentasi Interaktif REST API Microservice Peramalan pada OpenAPI/Swagger UI* |
| `IMG-8.3-UI-Forecasting-Chart.png` | PNG (1920 × 1080 px) | Tangkapan layar **Halaman Peramalan SCM (`/forecasting`)**: <br>• Grafik visual garis Chart.js perbandingan data historis vs proyeksi kebutuhan bahan 3–6 bulan ke depan beserta tabel skor evaluasi **MAPE (%)** dan **RMSE**. | **Gambar 8.3.** *Visualisasi Hasil Komputasi Algoritma Peramalan Kebutuhan Bahan Baku pada Dashboard Web* |
| `IMG-8.4-VPS-Terminal-Status.png` | PNG (1600 × 900 px) | Tangkapan layar terminal **SSH Server Cloud VPS Ubuntu 24.04 LTS (IP: `202.155.91.151`)**: <br>• Eksekusi perintah `systemctl status nginx php8.3-fpm mysql` menunjukkan status `active (running)`.<br>• Bukti konfigurasi direktori `/var/www/web-scm-marmer`. | **Gambar 8.4.** *Status Pelayanan Web Server Nginx, PHP-FPM, dan MySQL pada Cloud Server VPS Ubuntu Linux* |
| `IMG-8.5-SSL-Cert-Browser.png` | PNG (1280 × 720 px) | Tangkapan layar **URL Bar Browser & Jendela Sertifikat Keamanan SSL**: <br>• Menampilkan alamat resmi `https://onyxtulungagung.id` dengan ikon gembok aman, dikeluarkan oleh *Let's Encrypt Authority* (Protokol TLS 1.3 Terenkripsi). | **Gambar 8.5.** *Verifikasi Implementasi Protokol Keamanan HTTPS dan Sertifikat SSL Let's Encrypt* |

### 2. Foto Pengujian Akses Multi-Device di Lapangan
| Kode & Nama File | Subjek & Perangkat Pengujian | Lokasi & Skenario Pengujian | Teks Judul Foto |
| :--- | :--- | :--- | :--- |
| `FOTO-8.1-Test-Smartphone.jpg` | Smartphone Android supir distribusi / mandor | Di area muat barang galeri marmer: Menampilkan form ceklis surat jalan yang responsif dan mudah ditekan di layar sentuh HP. | **Gambar 8.6.** *Pengujian Aksesibilitas dan Responsivitas Sistem pada Perangkat Smartphone Lapangan* |
| `FOTO-8.2-Test-PC-Office.jpg` | Laptop / PC desktop manajemen IKM | Di ruang kantor administrasi UD Cahaya Onix: Menampilkan dashboard monitoring analitik multi-kolom di layar lebar. | **Gambar 8.7.** *Verifikasi Tampilan Dashboard Monitoring Manajemen SCM pada Perangkat PC Desktop Kantor* |

### 3. Instrumen Data Pendukung di Excel ([`Form_Kegiatan_8.xlsx`](file:///d:/Project%20Coding/Web%20SCM/Docs/laporan_kegiatan/Form_Kegiatan_8_Integrasi_API_dan_Uji_Multi_Device.xlsx))
* **Sheet 1 (`Form 8.1 Uji REST API`):** Matriks pengujian 6 skenario integrasi API (Healthcheck, Prediksi Holt-Winters, ARIMA, SES, Error Handling data kosong, dan Simulasi Fail-safe saat microservice offline).
* **Sheet 2 (`Form 8.2 Uji Multi-Device`):** Matriks pengujian pada 4 kategori resolusi layar (Smartphone Android 390px, Tablet Mandor 768px, Laptop 1366px, dan PC Monitor 1920px) dengan hasil uji rendering tata letak 100% Lolos.

---

## 🧪 KEGIATAN 9: Uji Fungsionalitas Sistem (Black-Box Testing)

### 1. Kebutuhan Visual & Tangkapan Layar (*Screenshots*)
| Kode & Nama File | Format & Dimensi | Isi Tampilan yang Harus Terlihat | Teks Judul Gambar |
| :--- | :--- | :--- | :--- |
| `IMG-9.1-PHPUnit-Terminal.png` | PNG (1600 × 900 px) | Tangkapan layar terminal eksekusi **PHPUnit Test Suite (`php artisan test tests/Feature/`)**: <br>• Baris hijau kelulusan pengujian: **`Tests: 35 passed (170 assertions)`** yang mencakup pengujian autentikasi, transaksi stok, SPK, QC, distribusi, checkout publik, admin order, serta bot security. | **Gambar 9.1.** *Hasil Eksekusi Otomatis Test Suite Black-Box Testing Menggunakan PHPUnit Framework (100% PASS)* |
| `IMG-9.2-Test-Auth-Validation.png` | PNG (1400 × 800 px) | Tangkapan layar **Uji Kasus Kunci Validasi Input & Flash Message**: <br>• Penolakan login kredensial salah dengan pesan *alert* merah.<br>• Validasi form mutasi stok bahan baku yang menolak input kuantitas minus atau melebihi stok fisik. | **Gambar 9.2.** *Pengujian Boundary Value Analysis dan Penanganan Kesalahan Input pada Form Transaksi* |
| `IMG-9.3-Test-ZeroPollution.png` | PNG (1600 × 900 px) | Tangkapan layar bukti **Arsitektur Keamanan 2-Gate**: <br>• Menampilkan bahwa pesanan checkout konsumen yang belum dibayar tidak masuk ke tabel `work_orders` bengkel (*Zero Workshop Pollution*). | **Gambar 9.3.** *Verifikasi Isolasi Antrean Produksi terhadap Transaksi Belum Terverifikasi (2-Gate Order System)* |
| `IMG-9.4-Test-RateLimiter.png` | PNG (1400 × 800 px) | Tangkapan layar penolakan **Anti-Spam Bot Flooding**: <br>• Tampilan respons `HTTP 429 Too Many Requests` saat form checkout diserang lebih dari 5 kali berturut-turut dalam 1 menit. | **Gambar 9.4.** *Pengujian Ketahanan Sistem terhadap Serangan Bot Spamming Menggunakan IP Rate Limiting* |

### 2. Instrumen Data Pendukung di Excel ([`Form_Kegiatan_9.xlsx`](file:///d:/Project%20Coding/Web%20SCM/Docs/laporan_kegiatan/Form_Kegiatan_9_Matriks_BlackBox_dan_Regression_Testing.xlsx))
* **Sheet 1 (`Form 9.1 Matriks 20 Test Case Backend`):** Detail 20 skenario pengujian backend (Kode Kasus Uji `TC-AUTH`, `TC-STK`, `TC-PRD`, `TC-QC`, `TC-DST`, `TC-FOR`, Input Uji, Ekspektasi Sistem, Output Aktual, dan Status PASS).
* **Sheet 2 (`Form 9.2 Matriks 15 Test Case E2E & Security`):** Detail 15 skenario pengujian tombol UI, alur checkout publik, konversi admin order, proteksi SQL Injection, XSS, dan Stress Test Bot Spam.

---

### 📦 Matriks Rekapitulasi Standar Dokumen Siap Kumpul:

```
Standar Format Dokumen Laporan:
• Kertas: A4 (Margin Baku 2.54 cm / 1 inch)
• Tipografi: Calibri / Arial / Segoe UI (Ukuran 10.5 pt, Spasi 1.15 - 1.20, Justify)
• Aksen Visual: Deep Navy (#1E3A8A) untuk Kotak Judul & Header Tabel
• Tabel: Header Navy Teks Putih Tebal, Baris Bergantian Halus (#F8FAFC), Border Halus (#CBD5E1)
• Badge Kelulusan: Emerald Green (#065F46 / #D1FAE5) untuk status "PASS" / "Lolos"
```

| Dokumen Kegiatan | Microsoft Word (`.docx`) | Adobe PDF (`.pdf`) | Formulir Excel (`.xlsx`) | Markdown Master (`.md`) |
| :--- | :---: | :---: | :---: | :---: |
| **Kegiatan 4 (Desain Basis Data & Integrasi)** | [📝 `Kegiatan_4.docx`](file:///d:/Project%20Coding/Web%20SCM/Docs/laporan_kegiatan/Kegiatan_4_Desain_Basis_Data_dan_Integrasi.docx) | [📄 `Kegiatan_4.pdf`](file:///d:/Project%20Coding/Web%20SCM/Docs/laporan_kegiatan/Kegiatan_4_Desain_Basis_Data_dan_Integrasi.pdf) | [📊 `Form_Kegiatan_4.xlsx`](file:///d:/Project%20Coding/Web%20SCM/Docs/laporan_kegiatan/Form_Kegiatan_4_Kamus_Data_dan_Integrasi.xlsx) | [📄 `Kegiatan_4.md`](file:///d:/Project%20Coding/Web%20SCM/Docs/laporan_kegiatan/Kegiatan_4_Desain_Basis_Data_dan_Integrasi.md) |
| **Kegiatan 6 (UI/UX & Prototype)** | [📝 `Kegiatan_6.docx`](file:///d:/Project%20Coding/Web%20SCM/Docs/laporan_kegiatan/Kegiatan_6_Perancangan_Antarmuka_Pengguna_UI_UX.docx) | [📄 `Kegiatan_6.pdf`](file:///d:/Project%20Coding/Web%20SCM/Docs/laporan_kegiatan/Kegiatan_6_Perancangan_Antarmuka_Pengguna_UI_UX.pdf) | [📊 `Form_Kegiatan_6.xlsx`](file:///d:/Project%20Coding/Web%20SCM/Docs/laporan_kegiatan/Form_Kegiatan_6_Persona_dan_Usability_Testing.xlsx) | [📄 `Kegiatan_6.md`](file:///d:/Project%20Coding/Web%20SCM/Docs/laporan_kegiatan/Kegiatan_6_Perancangan_Antarmuka_Pengguna_UI_UX.md) |
| **Kegiatan 7 (Coding Modul Inti MVC)** | [📝 `Kegiatan_7.docx`](file:///d:/Project%20Coding/Web%20SCM/Docs/laporan_kegiatan/Kegiatan_7_Pengkodean_Modul_Inti_Aplikasi.docx) | [📄 `Kegiatan_7.pdf`](file:///d:/Project%20Coding/Web%20SCM/Docs/laporan_kegiatan/Kegiatan_7_Pengkodean_Modul_Inti_Aplikasi.pdf) | [📊 `Form_Kegiatan_7.xlsx`](file:///d:/Project%20Coding/Web%20SCM/Docs/laporan_kegiatan/Form_Kegiatan_7_Matriks_Modul_dan_Pengujian_Rute.xlsx) | [📄 `Kegiatan_7.md`](file:///d:/Project%20Coding/Web%20SCM/Docs/laporan_kegiatan/Kegiatan_7_Pengkodean_Modul_Inti_Aplikasi.md) |
| **Kegiatan 8 (Integrasi & Cloud Deployment)** | [📝 `Kegiatan_8.docx`](file:///d:/Project%20Coding/Web%20SCM/Docs/laporan_kegiatan/Kegiatan_8_Integrasi_Algoritma_dan_Deployment_Cloud.docx) | [📄 `Kegiatan_8.pdf`](file:///d:/Project%20Coding/Web%20SCM/Docs/laporan_kegiatan/Kegiatan_8_Integrasi_Algoritma_dan_Deployment_Cloud.pdf) | [📊 `Form_Kegiatan_8.xlsx`](file:///d:/Project%20Coding/Web%20SCM/Docs/laporan_kegiatan/Form_Kegiatan_8_Integrasi_API_dan_Uji_Multi_Device.xlsx) | [📄 `Kegiatan_8.md`](file:///d:/Project%20Coding/Web%20SCM/Docs/laporan_kegiatan/Kegiatan_8_Integrasi_Algoritma_dan_Deployment_Cloud.md) |
| **Kegiatan 9 (Black-Box Testing)** | [📝 `Kegiatan_9.docx`](file:///d:/Project%20Coding/Web%20SCM/Docs/laporan_kegiatan/Kegiatan_9_Uji_Fungsionalitas_Black_Box_Testing.docx) | [📄 `Kegiatan_9.pdf`](file:///d:/Project%20Coding/Web%20SCM/Docs/laporan_kegiatan/Kegiatan_9_Uji_Fungsionalitas_Black_Box_Testing.pdf) | [📊 `Form_Kegiatan_9.xlsx`](file:///d:/Project%20Coding/Web%20SCM/Docs/laporan_kegiatan/Form_Kegiatan_9_Matriks_BlackBox_dan_Regression_Testing.xlsx) | [📄 `Kegiatan_9.md`](file:///d:/Project%20Coding/Web%20SCM/Docs/laporan_kegiatan/Kegiatan_9_Uji_Fungsionalitas_Black_Box_Testing.md) |

---

### 💡 Tindak Lanjut:
1. Anda dapat menaruh foto-foto lapangan / screenshot yang Anda miliki ke dalam subfolder `Docs/screenshots/` di atas.
2. Untuk diagram teknis (Sitemap, MVC Architecture, SOA Integration) dan tangkapan layar antarmuka sistem yang belum ada, **dapat saya bantu generasikan dan susun secara otomatis**.
3. Silakan beri aba-aba jika Anda ingin saya langsung mengeksekusi integrasi seluruh tabel **"Rincian Output Setiap Langkah"** dan pembaruan visual ke berkas **`.docx`**, **`.pdf`**, dan **`.xlsx`**!