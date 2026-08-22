# E-SCM Klaster IKM Marmer Tulungagung

Sistem Informasi *E-Supply Chain Management* (E-SCM) Terintegrasi berbasis Web untuk akselerasi hilirisasi dan optimalisasi rantai pasok klaster Industri Kecil Menengah (IKM) Marmer dan Onyx di Kabupaten Tulungagung.

---

## 📌 Ringkasan Proyek

Proyek ini dibangun untuk mengatasi kendala kesenjangan informasi (*information gap*) dan pemborosan operasional (*lean waste*) pada klaster pengrajin marmer dan batu kali di Tulungagung (studi kasus: **UD Cahaya Onix** & **UD Putra Abadi**). Sistem ini menghubungkan aliran material, proses produksi bertahap, peramalan kebutuhan bahan baku (*forecasting*), hingga manajemen pengiriman/distribusi produk jadi secara *real-time*.

### 🚀 Fitur Utama

- **Dashboard Monitoring Terintegrasi:** Ringkasan KPI aliran material (Opening, In, Out, Consign), grafik stok per produk & gudang, metrik efisiensi siklus (VA vs NVA).
- **Manajemen Bahan Baku & Stok:** Pencatatan bahan baku masuk (bongkahan marmer/batu kali), spesifikasi dimensi/grade, nomor batch/blok, dan *stock alert* otomatis.
- **Tracking Alur Produksi & SPK Digital:** Surat Perintah Kerja (SPK) digital terdistribusi per stasiun kerja (Pembelahan, Pemotongan Slep, Pembubutan/Gerinda, Finishing/Poles, dan QC 2-Tahap).
- **Modul Peramalan Permintaan (Forecasting):** Integrasi model peramalan *time-series* (Moving Average / Holt-Winters Exponential Smoothing / ARIMA) untuk estimasi kebutuhan bahan baku dan kapasitas mesin.
- **Distribusi, Packing Checklist & Pengiriman:** Verifikasi fisik produk siap kirim, pencatatan logistik, dan riwayat pesanan pelanggan.
- **Role-Based Access Control (RBAC):** Hak akses berjenjang untuk Admin/Pemilik IKM, Staf Gudang/Bahan Baku, Operator Produksi, dan Bagian Distribusi/Penjualan.

---

## 🛠️ Tech Stack

| Lapisan | Teknologi yang Digunakan |
| :--- | :--- |
| **Backend Utama** | PHP 8.2+ / 8.3+ / Laravel 11+ (Latest MVC Architecture) |
| **Database** | MySQL 8.0+ / MariaDB |
| **Frontend UI** | Blade Template, Bootstrap 5.3+ (Latest), AdminLTE / Tabler, Chart.js / ApexCharts |
| **Microservice Forecasting** | Python 3.10+ (FastAPI Latest, Pandas, Statsmodels, Scikit-Learn) |
| **Web Server & Tools** | Nginx / Apache, XAMPP, Git, Composer (Latest), NPM (Latest) |

---

## 📚 Struktur Dokumentasi Proyek

| Dokumen | Deskripsi & Isi Utama |
| :--- | :--- |
| **[`ONBOARDING.md`](./ONBOARDING.md)** | **Panduan Anggota Tim / Rekan Kerja:** Onboarding cepat memahami konteks bisnis IKM, alur data, tech stack, dan setup lokal dalam 5 menit. |
| **[`GIT_WORKFLOW.md`](./GIT_WORKFLOW.md)** | **Standar Git & Kolaborasi:** Aturan branching (`main`, `dev`, `feature/`), format conventional commit, template PR, dan cheat sheet. |
| **[`DESIGN.md`](./DESIGN.md)** | **Spesifikasi Teknis & Bisnis Lengkap:** PRD, temuan VSM lapangan, User Persona & MoSCoW, Arsitektur Sistem, Skema Basis Data (ERD & Kamus Data), Spesifikasi REST API, serta Desain Algoritma Forecasting. |
| **[`UI_GUIDE.md`](./UI_GUIDE.md)** | **Panduan Antarmuka (UI/UX):** Design system (warna, tipografi, komponen tombol, card, tabel, status badge), tata letak mobile-first, dan panduan visual dashboard. |
| **[`OPS.md`](./OPS.md)** | **Operasional & Pengujian:** Panduan instalasi lokal (XAMPP/Docker), deployment ke server cloud/VPS, konfigurasi environment, skenario pengujian Black-Box, dan form bug report. |
| **[`TASKS.md`](./TASKS.md)** | **Roadmap & Sprint Tracking:** Breakdown 14 kegiatan penelitian/SDLC dari problem definition hingga publikasi jurnal Sinta 2 dan pendaftaran HKI. |
| **[`.env.example`](./.env.example)** | **Template Variabel Konfigurasi:** Pengaturan database, port server, secret key, dan URL service forecasting. |

---

## ⚡ Instalasi Cepat (Local Development)

### 1. Prasyarat
- PHP >= 8.2 & Composer
- MySQL >= 8.0
- Python >= 3.10 & pip (untuk modul forecasting)
- Node.js >= 18.x & NPM

### 2. Langkah Instalasi Aplikasi Utama
```bash
# 1. Clone repositori
git clone https://github.com/Vinxy-id/web-scm-marmer.git
cd web-scm-marmer

# 2. Install dependensi PHP & Node
composer install
npm install && npm run build

# 3. Setup environment file
cp .env.example .env
php artisan key:generate

# 4. Konfigurasi database di .env lalu jalankan migrasi & seeder
php artisan migrate --seed

# 5. Jalankan server Laravel
php artisan serve
```

### 3. Menjalankan Service Forecasting (Python)
```bash
cd forecasting_service
python -m venv venv
# Windows:
venv\Scripts\activate
# Linux/macOS:
source venv/bin/activate

pip install -r requirements.txt
uvicorn main:app --host 127.0.0.1 --port 8001 --reload
```

---

## 📋 Riwayat Perubahan (Changelog)

- **v1.1.0 (2026-08-22):**
  - Pembaruan acuan arsitektur backend ke Laravel 11+ (Versi Terbaru/Latest) dan PHP 8.2+/8.3+.
  - Pembaruan dependensi library Python microservice peramalan (FastAPI, PyDantic v2, Pandas, Statsmodels, Scikit-learn) dan library frontend ke versi terbaru.
- **v1.0.0 (2026-08-22):** 
  - Inisialisasi struktur dokumentasi terintegrasi (5 file inti).
  - Penyusunan baseline data gap informasi & VSM current state berdasarkan survei lapangan UD Cahaya Onix & UD Putra Abadi.
  - Perancangan spesifikasi sistem E-SCM, ERD, dan skema integrasi forecasting.
