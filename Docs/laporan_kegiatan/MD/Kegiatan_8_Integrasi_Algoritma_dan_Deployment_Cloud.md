# DOKUMEN OUTPUT KEGIATAN 8
## Integrasi Algoritma dan Deployment Cloud
**Proyek:** Rancang Bangun Sistem Informasi E-Supply Chain Terintegrasi untuk Akselerasi Hilirisasi Klaster IKM Marmer di Kabupaten Tulungagung  
**Mitra Studi Kasus:** UD Cahaya Onix & UD Putra Abadi (Kabupaten Tulungagung)  
**Metodologi SDLC:** Tahap Integrasi dan Deployment (*Cloud Infrastructure & SOA Microservices*)

---

## A. Tujuan Kegiatan
Menghubungkan algoritma peramalan permintaan (AI Forecasting) dengan sistem informasi utama, serta menerapkan (*deploy*) seluruh sistem ke server cloud (VPS) agar dapat diakses secara live oleh seluruh pemangku kepentingan (IKM, konsumen, manajemen).

---

## B. Keterkaitan dengan Tahapan Pengembangan Sistem Informasi
Tahap **Integrasi dan Deployment** — bagian akhir dari *System Implementation*, menjembatani sistem yang sudah dibangun di lingkungan development menuju lingkungan produksi yang dapat diakses pengguna nyata.

---

## C. Langkah-Langkah Detail Pelaksanaan

### Langkah 1 — Penyusunan API/Wrapper untuk Algoritma Forecasting
* **Yang dilakukan:** bungkus algoritma peramalan deret waktu (ARIMA & Holt-Winters) berbasis Python ke dalam microservice RESTful API modern menggunakan FastAPI + Uvicorn (`IMG-8.2-Swagger-API.png`).
* **Yang harus disiapkan:** script algoritma final dari Kegiatan 5, framework API Python FastAPI.
* **Output:** endpoint API forecasting yang siap dipanggil (`POST /api/v1/forecast/arima`, `POST /api/v1/forecast/holt-winters`).

### Langkah 2 — Integrasi API dengan Modul Stok/Produksi
* **Yang dilakukan:** hubungkan sistem utama Laravel 11 (Kegiatan 7) dengan API forecasting Python via Guzzle HTTP Client — sistem mengirim data historis konsumsi material, API mengembalikan proyeksi kebutuhan bahan baku dan divisualisasikan dengan grafik interaktif Chart.js (`IMG-8.3-UI-Forecasting-Chart.png`).
* **Yang harus disiapkan:** dokumen skema integrasi (Kegiatan 4), endpoint API dari langkah 1.
* **Output:** fitur forecasting yang sudah terhubung dan dapat menampilkan hasil prediksi di dashboard.

### Langkah 3 — Testing Integrasi
* **Yang dilakukan:** uji aliran data antar-modul, latensi pemanggilan API (response time $< 250	ext{ ms}$), sanitasi JSON payload, serta uji ketahanan koneksi fallback saat service offline (Form 8.1 & Form 8.4).
* **Yang harus disiapkan:** skenario uji integrasi, dataset time-series 24 bulan.
* **Output:** hasil integration test (konfirmasi kompatibilitas format data dan ketahanan koneksi).

### Langkah 4 — Pemilihan Provider Cloud
* **Yang dilakukan:** bandingkan opsi cloud VPS (Hostinger KVM 2, DigitalOcean Droplet, AWS EC2, Google Cloud), pertimbangkan resource (2 vCPU, 8 GB RAM, 100 GB NVMe), stabilitas uptime, latensi lokal Indonesia, dan alokasi anggaran penelitian (Form 8.3).
* **Yang harus disiapkan:** estimasi kebutuhan resource berdasarkan skala sistem.
* **Output:** provider dan paket cloud server terpilih (Hostinger KVM 2 Ubuntu 24.04 LTS).

### Langkah 5 — Setup Server
* **Yang dilakukan:** konfigurasi sistem operasi server Linux, install web server Nginx, PHP 8.3-FPM, MySQL 8.0, Python 3.12, Uvicorn Daemon (Systemd), firewall UFW, dan konfigurasi environment variable aman (`IMG-8.4-VPS-Terminal-Status.png`).
* **Yang harus disiapkan:** kredensial akses server VPS, dokumentasi kebutuhan teknis sistem.
* **Output:** server siap menerima deployment aplikasi.

### Langkah 6 — Deployment Aplikasi
* **Yang dilakukan:** deploy kode sumber aplikasi ke server via Git over SSH, setup perizinan direktori storage, jalankan database migration & seeder empiris, serta build asset frontend Vite.
* **Yang harus disiapkan:** source code final dari Kegiatan 7, server yang sudah disiapkan dari langkah 5.
* **Output:** aplikasi berjalan aktif di server cloud.

### Langkah 7 — Konfigurasi Domain dan Keamanan Dasar
* **Yang dilakukan:** hubungkan domain resmi (`https://onyxtulungagung.id`), pasang sertifikat SSL Let's Encrypt TLS 1.3 (Grade A), aktifkan rate-limiting Nginx (60 req/min), dan jadwalkan cron backup database harian (`IMG-8.5-SSL-Cert-Browser.png`).
* **Yang harus disiapkan:** nama domain aktif, kebijakan backup.
* **Output:** sistem dapat diakses via domain resmi ber-HTTPS dengan pengamanan aktif.

### Langkah 8 — Uji Coba Akses dari Berbagai Device/Lokasi
* **Yang dilakukan:** tes akses sistem dari berbagai perangkat (Laptop Desktop, Tablet, Smartphone Android/iOS) dan multi-jaringan (Telkomsel 4G, Indihome Fiber, XL Axiata) di lokasi bengkel IKM Tulungagung (Form 8.2).
* **Yang harus disiapkan:** daftar device uji, akses internet di lokasi IKM.
* **Output:** konfirmasi sistem dapat diakses responsif dari semua perangkat — siap untuk pengujian fungsional menyeluruh di Kegiatan 9.

---

## D. Form/Template Pendukung

### 1. Formulir 8.1: Spesifikasi RESTful API Microservice Peramalan (FastAPI)
| Method | Endpoint URL | Request Payload (JSON) | Response Data (JSON) | Latensi Rata-rata | Status Integrasi |
| :---: | :--- | :--- | :--- | :---: | :---: |
| `POST` | `/api/v1/forecast/arima` | `{"material_id": 1, "history": [...], "steps": 3}` | `{"status": "success", "forecast": [120, 135, 128], "mape": 5.73}` | **142 ms** | **100% TERHUBUNG** |
| `POST` | `/api/v1/forecast/holt-winters` | `{"material_id": 2, "history": [...], "alpha": 0.2}` | `{"status": "success", "forecast": [85, 90, 88], "rmse": 4.12}` | **128 ms** | **100% TERHUBUNG** |
| `GET` | `/api/v1/health` | None | `{"status": "healthy", "service": "forecasting-engine"}` | **18 ms** | **100% AKTIF** |
| `POST` | `/api/v1/evaluate` | `{"actual": [...], "predicted": [...]}` | `{"mape": 5.73, "rmse": 3.84, "mae": 2.91}` | **65 ms** | **100% TERHUBUNG** |
| `GET` | `/docs` | None | Swagger UI OpenAPI Documentation Page | **35 ms** | **100% TERSEDIA** |

### 2. Formulir 8.2: Rekapitulasi Pengujian Akses Multi-Device & Jaringan Lapangan
| Perangkat Uji | Spesifikasi Layar & OS | Browser Uji | Jaringan Koneksi | Load Time | Hasil Render Visual | Status |
| :---: | :--- | :--- | :--- | :---: | :--- | :---: |
| **Laptop Asus Vivobook** | 15.6" Full HD / Windows 11 | Chrome 127 | Indihome Fiber 50 Mbps | **0.82 s** | Desktop Layout Sempurna | **PASS** |
| **Tablet Samsung Tab A** | 10.1" WUXGA / Android 13 | Samsung Internet | Telkomsel 4G LTE | **1.14 s** | Tablet Kanban 5 Kolom Mulus | **PASS** |
| **HP Xiaomi Redmi Note**| 6.67" AMOLED / Android 14 | Chrome Mobile | XL Axiata 4G | **1.28 s** | Mobile-First Responsive | **PASS** |
| **iPhone 13** | 6.1" Super Retina / iOS 17.5| Safari Mobile | Telkomsel Orbit WiFi | **0.95 s** | Touch Target & Font Tajam | **PASS** |

### 3. Formulir 8.3: Analisis Komparasi Kelayakan Provider Cloud Server VPS
| Provider Cloud | Spesifikasi Hardware (vCPU / RAM / Storage) | Lokasi Data Center | Estimasi Biaya / Bulan | Kestabilan Uptime SLA | Skor Kelayakan | Keputusan Pemilihan |
| :--- | :--- | :--- | :--- | :---: | :---: | :---: |
| **Hostinger KVM 2** | **2 vCPU / 8 GB RAM / 100 GB NVMe** | **Jakarta, Indonesia (ID)** | **Rp 149.000 / bln** | **99.9%** | **94 / 100** | **TERPILIH (Utama)** |
| **DigitalOcean Droplet** | 2 vCPU / 4 GB RAM / 80 GB SSD | Singapura (SGP1) | Rp 380.000 / bln | 99.99% | 85 / 100 | Cadangan (Backup) |
| **AWS EC2 t3.large** | 2 vCPU / 8 GB RAM / EBS Storage | Jakarta (ap-southeast-3) | Rp 750.000 / bln | 99.99% | 80 / 100 | Terlalu Mahal |
| **Google Cloud e2-std** | 2 vCPU / 8 GB RAM / 100 GB Disk | Jakarta (asia-southeast2)| Rp 820.000 / bln | 99.95% | 78 / 100 | Terlalu Mahal |

### 4. Formulir 8.4: Log Uji Ketahanan Integrasi API Microservice (Fallback Test)
| Skenario Pengujian Integrasi | Kondisi Layanan Microservice | Respon Sistem Utama (Laravel) | Tindakan Otomatis (*Fallback Rule*) | Pengalaman Pengguna (UX) | Status Uji |
| :--- | :--- | :--- | :--- | :--- | :---: |
| **Normal Request** | FastAPI Service Online (Port 8000) | Menerima payload JSON prediksi | Render visualisasi Chart.js live | Grafik prediksi tampil mulus | **PASS** |
| **Service Timeout (> 5s)**| FastAPI mengalami lonjakan CPU | Catch `RequestException` (timeout 5s)| Gunakan nilai rata-rata historis (SMA) | Notifikasi peringatan oranye | **PASS** |
| **Service Offline (Down)** | Daemon Uvicorn dimatikan manual | Catch `ConnectException` (Connection Refused)| Mengambil data cache prediksi terakhir | Dashboard tetap bisa diakses | **PASS** |
| **Auto-Recovery Service** | Systemd me-restart daemon Uvicorn | Koneksi API pulih otomatis | Switch kembali ke live inference | Grafik kembali real-time | **PASS** |

### 5. Tangkapan Layar Bukti Integrasi API & Deployment Cloud
![Gambar 8.1: Topologi Arsitektur Microservices Cloud VPS dan Integrasi AI](IMG-8.1-SOA-Architecture.png)
![Gambar 8.2: Dokumentasi Interaktif Swagger UI OpenAPI FastAPI Microservice](IMG-8.2-Swagger-API.png)
![Gambar 8.3: Visualisasi Hasil Peramalan AI Forecasting di Dashboard Laravel](IMG-8.3-UI-Forecasting-Chart.png)
![Gambar 8.4: Status Aktif Web Server Nginx, PHP 8.3-FPM, dan Uvicorn](IMG-8.4-VPS-Terminal-Status.png)
![Gambar 8.5: Sertifikat Keamanan SSL/TLS 1.3 Let's Encrypt Terverifikasi](IMG-8.5-SSL-Cert-Browser.png)

---

## E. Output Akhir Kegiatan
- [x] **Microservice AI Forecasting Berfungsi:** Endpoint REST API Python FastAPI aktif melayani inferensi deret waktu dengan latensi $< 250	ext{ ms}$ (Form 8.1).
- [x] **Integrasi Antarmuka Dashboard Visual:** Grafik interaktif Chart.js berhasil menampilkan proyeksi stok di panel backoffice (`IMG-8.3`).
- [x] **Server VPS Ubuntu 24.04 LTS Terkonfigurasi:** Nginx, PHP 8.3-FPM, MySQL 8.0, dan Uvicorn Systemd service aktif (`IMG-8.4`).
- [x] **Tabel Komparasi Pemilihan Cloud:** Evaluasi teknis dan efisiensi biaya provider VPS di Form 8.3.
- [x] **Uji Ketahanan Fallback API 100% Lolos:** Sistem terbukti tangguh saat microservice offline di Form 8.4.
- [x] **Domain Ber-HTTPS & Keamanan Aktif:** Akses live [https://onyxtulungagung.id](https://onyxtulungagung.id) dengan sertifikat SSL Grade A (`IMG-8.5`).
- [x] **Pengujian Multi-Device Lolos 100%:** Sistem terbukti responsif di laptop, tablet, dan smartphone pada jaringan lapangan (Form 8.2).

---

## F. Tips & Best Practice
1. **Gunakan Systemd Service & Gunicorn/Uvicorn Workers** untuk menjaga microservice AI Python selalu aktif (*auto-restart*) jika terjadi lonjakan memori.
2. **Aktifkan Gzip Compression & HTTP/2 di Nginx** untuk mempercepat pengiriman asset CSS/JS ke perangkat smartphone di lapangan.
3. **Terapkan Asynchronous Non-Blocking Request** saat memanggil API forecasting agar tidak membebani waktu render antarmuka web utama.
4. **Otomatiskan Pembaruan Sertifikat SSL (`certbot renew --quiet`)** melalui Cron Job setiap 60 hari.
