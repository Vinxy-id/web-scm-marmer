# PANDUAN OPERASIONAL & PENGUJIAN (OPS.md)
## Sistem Informasi E-Supply Chain Management Klaster IKM Marmer

Dokumen ini memuat panduan teknis operasional lengkap: setup lingkungan lokal, prosedur *deployment* ke *cloud server*, strategi pengujian perangkat lunak (*Black-Box Testing*), template pelaporan *bug*, dan manajemen pemeliharaan sistem.

---

## 1. Setup Lingkungan Pengembangan Lokal (Local Dev)

### 1.1 Persyaratan Sistem
- **Sistem Operasi:** Windows 10/11, macOS, atau Linux Ubuntu 22.04+
- **PHP:** Versi 8.2 atau 8.3 dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `bcmath`, `curl`, `gd` aktif.
- **Node.js & NPM:** Node.js v18.x atau v20.x LTS.
- **Python:** Python 3.10 atau 3.11 (untuk microservice forecasting).
- **DBMS:** MySQL 8.0+ atau MariaDB 10.6+ (bisa melalui XAMPP / Laragon).
- **Composer:** Versi 2.5+.

### 1.2 Langkah Instalasi Bertahap

```bash
# 1. Masuk ke direktori proyek
cd "d:/Project Coding/Web SCM"

# 2. Setup dependensi Laravel (Versi Terbaru - Laravel 11+)
composer install --prefer-dist
cp .env.example .env

# 3. Generate Application Encryption Key
php artisan key:generate

# 4. Konfigurasi kredensial database di file .env:
# DB_DATABASE=db_escm_marmer
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Jalankan migrasi tabel dan seeding data dummy (IKM Cahaya Onix & Putra Abadi)
php artisan migrate:fresh --seed

# 6. Build asset frontend
npm install
npm run build

# 7. Jalankan local web server
php artisan serve --port=8000
```

### 1.3 Menjalankan Microservice Forecasting (Python FastAPI)

```bash
# Buka terminal baru di direktori forecasting
cd forecasting_service

# Buat virtual environment
python -m venv venv

# Aktivasi venv
# Windows:
venv\Scripts\activate
# Linux/macOS:
source venv/bin/activate

# Install dependensi
pip install fastapi uvicorn pandas numpy statsmodels scikit-learn

# Jalankan server API
uvicorn main:app --host 127.0.0.1 --port 8001 --reload
```

---

## 2. Panduan Deployment ke Cloud Server (VPS / Railway / Render)

### 2.1 Deployment ke VPS Ubuntu (Nginx + PHP-FPM + MySQL)

#### A. Konfigurasi Virtual Host Nginx (`/etc/nginx/sites-available/escm-marmer.conf`)
```nginx
server {
    listen 80;
    server_name escm-marmer.tulungagungkab.go.id; # atau IP server
    root /var/www/web-scm/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

#### B. Menjalankan Python API sebagai Service Systemd (`/etc/systemd/system/escm-forecast.service`)
```ini
[Unit]
Description=E-SCM Forecasting Microservice
After=network.target

[Service]
User=www-data
WorkingDirectory=/var/www/web-scm/forecasting_service
ExecStart=/var/www/web-scm/forecasting_service/venv/bin/uvicorn main:app --host 127.0.0.1 --port 8001
Restart=always

[Install]
WantedBy=multi-user.target
```

```bash
# Aktifkan service
sudo systemctl daemon-reload
sudo systemctl enable escm-forecast
sudo systemctl start escm-forecast
```

#### C. SSL & Keamanan Domain
```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d escm-marmer.tulungagungkab.go.id
```

---

## 3. Strategi Pengujian (Testing Strategy)

### 3.1 Unit Testing (Automated)
Jalankan pengujian unit controller dan service logika stok:
```bash
php artisan test
```

### 3.2 Skenario Pengujian Black-Box (Uji Fungsionalitas)

Pengujian menggunakan teknik **Equivalence Partitioning (EP)** dan **Boundary Value Analysis (BVA)**.

| ID Uji | Modul | Skenario Pengujian | Data Input | Hasil yang Diharapkan | Status |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-001** | Autentikasi | Login dengan kredensial valid | `email: owner@cahayaonix.com`, `pass: password123` | Berhasil masuk ke dashboard owner | Pass |
| **TC-002** | Autentikasi | Login password salah | `email: owner@cahayaonix.com`, `pass: wrongpass` | Muncul pesan error "Kredensial tidak cocok" | Pass |
| **TC-003** | Bahan Baku | Input stok masuk jumlah valid | Material: Bongkahan Marmer Putih, Qty: 15 Blok | Stok bertambah 15, muncul di tabel transaksi IN | Pass |
| **TC-004** | Bahan Baku | Input stok masuk kuantitas negatif (BVA) | Qty: `-5` Blok | Sistem menolak input dengan validasi form | Pass |
| **TC-005** | SPK Produksi | Buat SPK baru melebihi stok bahan baku | Target: 50 Wastafel (butuh 25 blok), Stok riil: 10 blok | Sistem memberikan peringatan bahan baku kurang | Pass |
| **TC-006** | QC Inspeksi | Input unit lolos + rework + scrap = total unit SPK | Diperiksa: 14, Lolos: 12, Rework: 2, Scrap: 0 | Data tersimpan, stok barang jadi bertambah 12 | Pass |
| **TC-007** | Peramalan | Request prediksi horizon 3 bulan | `item_id: 1`, `horizon: 3`, `model: holt_winters` | Menghasilkan array prediksi 3 periode + MAPE | Pass |
| **TC-008** | Hak Akses | Operator mencoba akses menu Manajemen Akun | URL: `/admin/users` dengan role `produksi` | Muncul response `403 Unauthorized` | Pass |

### 3.3 Format Bug Report Log (Template Pencatatan Kendala)

```markdown
| ID Bug | Deskripsi Kendala | Tingkat Keparahan (Severity) | Modul Terkait | Langkah Reproduksi | Status | PIC |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| BUG-001 | Grafik transaksi bulanan tidak me-refresh saat input data via mobile | Minor | Dashboard UI | Input transaksi di HP, buka dashboard tanpa hard reload | Fixed | Dev |
| BUG-002 | API Forecasting timeout jika data historis < 3 bulan | Major | Forecasting | Request prediksi material baru yang belum ada transaksi | Fixed | Dev |
```

---

## 4. Prosedur Backup & Pemeliharaan Berkala

### 4.1 Backup Database Otomatis (Cron Job Harian)
Tambahkan entri crontab di server:
```bash
# Backup setiap jam 01.00 WIB malam
0 1 * * * mysqldump -u root -p'PASSWORD_DB' db_escm_marmer | gzip > /var/backups/db_escm_$(date +\%Y\%m\%d).sql.gz
```

### 4.2 Logging & Monitoring
- **Laravel Log:** `storage/logs/laravel.log`
- **FastAPI Forecast Log:** `forecasting_service/logs/app.log`
- **Nginx Access & Error Log:** `/var/log/nginx/`
