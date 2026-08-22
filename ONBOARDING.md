# 🚀 PANDUAN MEMAHAMI REPOSITORI PROYEK (ONBOARDING GUIDE)
## Sistem Informasi E-Supply Chain Management (E-SCM) Klaster IKM Marmer Tulungagung

Dokumen ini dibuat khusus untuk memandu rekan tim / kolaborator baru agar dapat memahami konteks bisnis, arsitektur sistem, struktur berkas, dan cara menjalankan proyek ini dalam waktu singkat.

---

## 1. 📌 Apa Itu Proyek Ini?

Proyek ini adalah sistem informasi **E-Supply Chain Management (E-SCM)** berbasis web yang dirancang untuk mengoptimalkan rantai pasok klaster industri marmer dan onyx di Kabupaten Tulungagung (studi kasus nyata: **UD Cahaya Onix** dan **UD Putra Abadi**).

### Masalah Nyata di Lapangan:
- **Pencatatan Konvensional:** Stok bahan baku (bongkahan marmer/batu kali) dan produk jadi (wastafel, stepping stone) masih dicatat di buku tulis/Excel terpisah.
- **Lantai Produksi (Bengkel Bubut):** Surat Perintah Kerja (SPK) tidak terdistribusi tertulis ke operator, memicu variasi ukuran dan pengerjaan ulang (*rework*).
- **Pemborosan Residu/Limbah:** Waktu pemilahan dan handling sisa potongan mencapai **390 menit/minggu** di UD Putra Abadi.
- **Quality Control (QC) Reaktif:** Pemeriksaan cacat baru dilakukan di akhir, menyebabkan biaya penambalan resin meningkat.

### Solusi Sistem:
Sistem ini menghubungkan **Pemasok Tambang $\rightarrow$ Gudang Bahan Baku $\rightarrow$ Stasiun Produksi (Slep & Bubut) $\rightarrow$ QC 2-Tahap $\rightarrow$ Manajemen Limbah $\rightarrow$ Distribusi & Packing**, dilengkapi **Modul Peramalan Permintaan (*Forecasting*) berbasis Python**.

---

## 2. 🗺️ Peta Navigasi Dokumen (Wajib Dibaca)

Repositori ini menggunakan struktur dokumentasi terpusat:

| File Dokumen | Apa yang Ada di Dalamnya? | Kapan Harus Membacanya? |
| :--- | :--- | :--- |
| **[`README.md`](./README.md)** | Gambaran umum proyek, fitur utama, dan quick start. | Pertama kali membuka repo. |
| **[`DESIGN.md`](./DESIGN.md)** | **"Kitab Suci" Spesifikasi Teknis:** PRD, ERD, Kamus Data 11 tabel, Endpoint REST API, alur bisnis, dan formula forecasting. | Saat akan mendesain database, membuat API, atau memahami alur logika. |
| **[`UI_GUIDE.md`](./UI_GUIDE.md)** | Panduan desain antarmuka, palet warna, ukuran tombol, dan mockup layar. | Saat mengerjakan tampilan frontend / Blade template. |
| **[`OPS.md`](./OPS.md)** | Panduan instalasi lokal, deployment ke server cloud, skenario uji Black-Box, dan template pelaporan bug. | Saat melakukan deployment, konfigurasi server, atau testing. |
| **[`TASKS.md`](./TASKS.md)** | Roadmap pengerjaan berbasis 5 Sprint (14 kegiatan SDLC) dan progres kerja. | Saat ingin tahu fitur apa yang sedang dikerjakan dan target berikutnya. |
| **[`AGENTS.md`](./AGENTS.md)** | Aturan keselamatan agent AI (larangan membaca file `.env` langsung). | Aturan baku lingkungan coding. |

---

## 3. 🏗️ Arsitektur & Tech Stack

Proyek ini menggunakan arsitektur modular yang menggabungkan web monolit MVC dengan microservice analitik:

```
[ Web Browser (Desktop / HP Operator) ]
                   │
                   ▼ (HTTPS)
   [ Backend Laravel 11+ (PHP 8.2+/8.3+) ]  ◀─── Eloquent ───▶  [ Database MySQL 8.0 ]
                   │                                     (db_escm_marmer)
                   ▼ (REST JSON API)
[ Python FastAPI Service (Port 8001) ] ── (Peramalan: Moving Average & Holt-Winters)
```

- **Backend Utama:** PHP 8.2+ / 8.3+ / Laravel 11+ (Latest MVC Framework)
- **Database:** MySQL 8.0+ / MariaDB (`database/schema.sql`)
- **Frontend:** Blade Templates, Bootstrap 5.3+ (Latest), Chart.js / ApexCharts
- **Microservice Peramalan:** Python 3.10+ (FastAPI, Pandas, Statsmodels)

---

## 4. 📂 Struktur Folder Proyek

```
Web SCM/
├── .env.example                     # Template konfigurasi environment
├── README.md, DESIGN.md, ...        # 5 Dokumen inti panduan proyek
├── database/
│   └── schema.sql                   # Skrip DDL 11 tabel lengkap + data awal pengrajin
├── Docs/
│   ├── referensi/                   # File PDF jurnal & panduan penelitian
│   └── laporan_kegiatan/            # Laporan output resmi per kegiatan (Kegiatan 4, dll.)
├── forecasting_service/             # Microservice Python untuk prediksi stok
│   ├── main.py                      # Endpoint API forecasting
│   └── requirements.txt             # Library Python (FastAPI, Pandas, Statsmodels)
└── public/
    └── assets/                      # File statis frontend (CSS, JS, Gambar)
```

---

## 5. ⚡ Panduan Setup Cepat dalam 5 Menit

### Langkah 1: Setup Basis Data (MySQL)
1. Buka **phpMyAdmin** atau MySQL CLI.
2. Buat database baru bernama `db_escm_marmer` (opsional, script akan membuat otomatis).
3. Import file [`database/schema.sql`](./database/schema.sql). Seluruh 11 tabel dan data awal simulasi IKM akan langsung terbuat!

### Langkah 2: Setup Aplikasi Web (Laravel)
```bash
# 1. Masuk ke root direktori
cd "Web SCM"

# 2. Copy konfigurasi environment
cp .env.example .env

# 3. Buka file .env dan sesuaikan kredensial database Anda:
# DB_DATABASE=db_escm_marmer
# DB_USERNAME=root
# DB_PASSWORD=

# 4. Install dependensi & generate key
composer install
php artisan key:generate

# 5. Jalankan server lokal
php artisan serve
# Aplikasi berjalan di: http://127.0.0.1:8000
```

### Langkah 3: Menjalankan Service Forecasting (Python)
Buka terminal baru:
```bash
cd forecasting_service

# Buat dan aktifkan virtual environment
python -m venv venv
# Windows:
venv\Scripts\activate
# Linux/Mac:
source venv/bin/activate

# Install dependensi
pip install -r requirements.txt

# Jalankan API server
uvicorn main:app --host 127.0.0.1 --port 8001 --reload
# Cek dokumentasi API Swagger di: http://127.0.0.1:8001/docs
```

---

## 6. 👥 Peran Pengguna di Sistem (Akun Demo)

Untuk login dan mencoba alur sistem di database awal:
- **Owner / Pemilik IKM (`owner`):** `owner@cahayaonix.com` / `password123` (Akses Dashboard & Laporan Lengkap)
- **Staf Gudang (`gudang`):** `gudang@cahayaonix.com` / `password123` (Input Bahan Baku Masuk/Keluar)
- **Operator Produksi (`produksi`):** `produksi@cahayaonix.com` / `password123` (Tracking SPK & Stasiun Bubut)
- **Staf Distribusi (`distribusi`):** `distribusi@cahayaonix.com` / `password123` (Checklist Packing & Pengiriman)
- **Administrator (`admin`):** `admin@escm-marmer.id` / `password123` (Manajemen User & Master Data)

---

## 7. 🤝 Aturan Kolaborasi & Git Workflow

Panduan alur kerja Git lengkap dapat dibaca di **[`GIT_WORKFLOW.md`](./GIT_WORKFLOW.md)**. Ringkasan poin penting:
1. **Gunakan Branch Khusus:**
   - Fitur baru: `git checkout -b feature/nama-fitur` (dari branch `dev`)
   - Perbaikan bug: `git checkout -b fix/nama-kendala`
2. **Format Pesan Commit yang Jelas:**
   - Gunakan standar *Conventional Commits*: `feat(...)`, `fix(...)`, `docs(...)`, `test(...)`.
   - Contoh: `feat(stock): tambah validasi kuantitas minimum pada form bahan masuk`
3. **🔒 Keamanan File `.env`:**
   - **JANGAN PERNAH** melakukan commit file `.env` yang berisi password atau secret key ke Git. Selalu gunakan `.env.example` sebagai referensi bersama. File `.gitignore` sudah dikonfigurasi untuk mencegah kebocoran file privat.
4. **Sinkronisasi Dokumentasi:**
   - Jika mengubah struktur tabel database, pastikan memperbarui `DESIGN.md` (bagian 5) dan `database/schema.sql`.

---

*Selamat bergabung dan selamat berkarya di proyek E-SCM Marmer Tulungagung! Jika ada kendala, diskusikan langsung di grup tim atau buka issue.* 🚀
