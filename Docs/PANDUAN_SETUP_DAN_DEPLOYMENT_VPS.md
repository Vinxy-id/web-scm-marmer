# 📘 DOKUMENTASI DEPLOYMENT & SETUP CLOUD VPS
**Proyek:** Sistem Informasi E-SCM & E-Commerce Klaster IKM Marmer Tulungagung  
**Domain Resmi:** https://onyxtulungagung.id  
**IP Server:** 202.155.91.151 (DomaiNesia Data Center Jakarta)  
**Spesifikasi Peladen:** Ubuntu 24.04 LTS, 2 vCPU Core, 2 GB RAM, 30 GB NVMe Storage  

---

## 📑 DAFTAR TAHAPAN INSTALASI & MIGRASI

```
[ DNS Pointing ] ──> [ Install LEMP & Stack ] ──> [ Setup MySQL DB ] ──> [ Deploy Laravel ]
                                                                                │
[ Live HTTPS ] ◄── [ Setup Nginx & SSL ] ◄── [ Python AI Daemon ] ◄─────────────┘
```

---

### 🌐 TAHAP 0: Pointing DNS Domain
Di panel manajemen DNS DomaiNesia, arahkan domain ke IP VPS:
* **Record A:** `@` ➔ `202.155.91.151` (TTL: Auto / 14400)
* **Record A:** `www` ➔ `202.155.91.151` (TTL: Auto / 14400)

---

### 💻 TAHAP 1: Akses SSH & Penanganan Lock Paket
1. **Login ke Server via Terminal:**
   ```bash
   ssh root@202.155.91.151
   ```
2. **Jika terjadi proses instalasi terputus (dpkg lock):**
   ```bash
   sudo kill -9 $(pgrep -f 'apt|dpkg')
   sudo rm /var/lib/dpkg/lock-frontend /var/lib/dpkg/lock /var/lib/apt/lists/lock /var/cache/apt/archives/lock 2>/dev/null
   sudo dpkg --configure -a
   ```

---

### 📦 TAHAP 2: Instalasi Paket Inti Server (All-in-One Script)
Jalankan instalasi stack sistem tanpa jeda dialog interaktif:

```bash
# 1. Update & Upgrade Sistem Operasi
sudo DEBIAN_FRONTEND=noninteractive apt update && sudo DEBIAN_FRONTEND=noninteractive apt upgrade -y

# 2. Install Web Server Nginx, Git, Curl, Unzip, Firewall & MySQL
sudo DEBIAN_FRONTEND=noninteractive apt install -y nginx git curl unzip ufw mysql-server

# 3. Install PHP 8.3-FPM & Seluruh Ekstensi Pendukung Laravel
sudo DEBIAN_FRONTEND=noninteractive apt install -y php8.3-fpm php8.3-mysql php8.3-mbstring php8.3-xml \
php8.3-curl php8.3-bcmath php8.3-zip php8.3-intl php8.3-gd php8.3-cli

# 4. Install Composer (PHP Dependency Manager)
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# 5. Install Node.js 20.x & NPM
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo DEBIAN_FRONTEND=noninteractive apt install -y nodejs

# 6. Install Python 3, Venv, Pip & Certbot SSL
sudo DEBIAN_FRONTEND=noninteractive apt install -y python3 python3-pip python3-venv certbot python3-certbot-nginx
```

---

### 🗄️ TAHAP 3: Konfigurasi Basis Data MySQL
Masuk ke konsol MySQL:
```bash
sudo mysql
```
Eksekusi query berikut:
```sql
CREATE DATABASE db_escm_marmer CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'escm_user'@'127.0.0.1' IDENTIFIED BY 'PasswordKuatDB123!';
GRANT ALL PRIVILEGES ON db_escm_marmer.* TO 'escm_user'@'127.0.0.1';
FLUSH PRIVILEGES;
EXIT;
```

---

### 🚀 TAHAP 4: Deploy Repositori & Konfigurasi Laravel

```bash
# 1. Clone Repositori Proyek ke direktori web
cd /var/www
sudo git clone https://github.com/Vinxy-id/web-scm-marmer.git escm-marmer
cd /var/www/escm-marmer

# 2. Install Dependensi PHP & Kompilasi Aset Frontend
composer install --no-dev --optimize-autoloader
npm install
npm run build

# 3. Setup File Konfigurasi Lingkungan (.env)
cp .env.example .env
nano .env
```

**Konfigurasi Variabel Lingkungan Inti (.env):**
```ini
APP_NAME="E-SCM Marmer Tulungagung"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://onyxtulungagung.id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_escm_marmer
DB_USERNAME=escm_user
DB_PASSWORD=PasswordKuatDB123!

FORECASTING_API_URL=http://127.0.0.1:8001
```

**Finalisasi Framework Laravel:**
```bash
# 4. Generate App Key & Migrasi Database
php artisan key:generate
php artisan migrate:fresh --seed --force

# 5. Buat Folder Storage & Symlink Upload Berkas
mkdir -p storage/framework/views storage/framework/sessions storage/framework/cache/data storage/app/public storage/logs
php artisan storage:link

# 6. Optimasi Cache Production
php artisan optimize

# 7. Berikan Izin Akses Direktori ke Nginx (www-data)
sudo chown -R www-data:www-data /var/www/escm-marmer/storage /var/www/escm-marmer/bootstrap/cache
sudo chmod -R 775 /var/www/escm-marmer/storage /var/www/escm-marmer/bootstrap/cache
```

---

### 🤖 TAHAP 5: Setup Microservice Python AI (FastAPI Background Daemon)

```bash
# 1. Masuk ke direktori microservice
cd /var/www/escm-marmer/forecasting_service

# 2. Buat Virtual Environment & Install Library AI
python3 -m venv venv
./venv/bin/pip install -r requirements.txt

# 3. Buat Unit Service Systemd
sudo nano /etc/systemd/system/escm-forecast.service
```

**Isi Konfigurasi Service (/etc/systemd/system/escm-forecast.service):**
```ini
[Unit]
Description=E-SCM Forecasting AI Microservice
After=network.target

[Service]
User=www-data
Group=www-data
WorkingDirectory=/var/www/escm-marmer/forecasting_service
ExecStart=/var/www/escm-marmer/forecasting_service/venv/bin/uvicorn main:app --host 127.0.0.1 --port 8001
Restart=always
RestartSec=3

[Install]
WantedBy=multi-user.target
```

**Aktifkan Service:**
```bash
sudo systemctl daemon-reload
sudo systemctl enable escm-forecast
sudo systemctl start escm-forecast
sudo systemctl status escm-forecast
```

---

### 🌍 TAHAP 6: Konfigurasi Web Server Nginx & SSL HTTPS

1. **Buat File Virtual Host Nginx:**
   ```bash
   sudo nano /etc/nginx/sites-available/onyxtulungagung.id
   ```

   **Isi Konfigurasi Nginx:**
   ```nginx
   server {
       listen 80;
       listen [::]:80;
       server_name onyxtulungagung.id www.onyxtulungagung.id;
       root /var/www/escm-marmer/public;

       add_header X-Frame-Options "SAMEORIGIN";
       add_header X-Content-Type-Options "nosniff";
       add_header X-XSS-Protection "1; mode=block";

       index index.php index.html;
       charset utf-8;

       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }

       location = /favicon.ico { access_log off; log_not_found off; }
       location = /robots.txt  { access_log off; log_not_found off; }

       error_page 404 /index.php;

       location ~ \.php$ {
           fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
           fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
           include fastcgi_params;
           fastcgi_hide_header X-Powered-By;
       }

       location ~ /\.(?!well-known).* {
           deny all;
       }
   }
   ```

2. **Aktifkan Konfigurasi & Restart Web Server:**
   ```bash
   sudo ln -s /etc/nginx/sites-available/onyxtulungagung.id /etc/nginx/sites-enabled/
   sudo rm -f /etc/nginx/sites-enabled/default
   sudo nginx -t
   sudo systemctl restart nginx
   ```

3. **Pasang Enkripsi SSL Gembok Hijau Gratis (Let's Encrypt):**
   ```bash
   sudo certbot --nginx -d onyxtulungagung.id -d www.onyxtulungagung.id
   ```

---

### 💾 TAHAP 7: Otomasi Backup Harian (Cron Job)
Buka crontab:
```bash
sudo crontab -e
```
Tambahkan perintah backup otomatis setiap jam 02.00 WIB:
```text
0 2 * * * mysqldump -u escm_user -p'PasswordKuatDB123!' db_escm_marmer | gzip > /var/backups/db_escm_$(date +\%Y\%m\%d).sql.gz
```

---

### 🔄 TAHAP 8: SOP Menambah Fitur Baru di Masa Depan (Deploy Script)
Setiap kali ada pembaruan kode baru dari GitHub di masa depan:
```bash
cd /var/www/escm-marmer
git pull origin main
composer install --no-dev
npm run build
php artisan migrate --force
php artisan optimize
sudo systemctl restart escm-forecast
```

---
**Status Akhir:** ✅ **SISTEM TELAH LIVE & PRODUCTION READY**  
**URL:** [https://onyxtulungagung.id](https://onyxtulungagung.id)