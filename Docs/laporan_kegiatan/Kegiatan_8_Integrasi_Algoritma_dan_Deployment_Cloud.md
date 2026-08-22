# DOKUMEN OUTPUT KEGIATAN 8
## Integrasi Algoritma ke Sistem & Deployment ke Cloud Server
**Proyek:** Rancang Bangun Sistem Informasi E-Supply Chain Terintegrasi untuk Akselerasi Hilirisasi Klaster IKM Marmer di Kabupaten Tulungagung  
**Mitra Studi Kasus:** UD Cahaya Onix & UD Putra Abadi (Kabupaten Tulungagung)  
**Metodologi SDLC:** Tahap Integrasi Sistem & Penerapan Lingkungan Cloud (*System Integration & Cloud Deployment*)

---

## 1. Tujuan Kegiatan
Kegiatan ini bertujuan untuk:
1. Mengintegrasikan *microservice* peramalan permintaan berbasis kecerdasan buatan (*FastAPI Python*) dengan aplikasi inti *Laravel MVC* melalui protokol pertukaran data *RESTful API* berformat JSON secara *real-time*.
2. Melakukan *deployment* dan konfigurasi arsitektur peladen (*cloud server / VPS*) berbasis Linux Ubuntu, Nginx *Reverse Proxy*, PHP 8.3-FPM, MySQL 8.0, dan *SSL/TLS Certificate* untuk menjamin ketersediaan sistem (*high availability*) serta keamanan data operasional IKM.
3. Mengonfigurasi otomatisasi pemeliharaan basis data (*automated daily backup cron job*) dan memvalidasi aksesibilitas responsif sistem pada berbagai perangkat (*multi-device*: *Smartphone Android*, *Tablet*, dan *Desktop/Laptop*).

---

## 2. Arsitektur Integrasi Layanan (Service-Oriented Architecture)

Sistem E-SCM Marmer dibangun menggunakan pendekatan *decoupled service architecture* yang memisahkan beban komputasi transaksi bisnis (*business logic*) dengan beban komputasi analitik deret waktu (*time-series computational engine*):

### 2.1 Skema Komunikasi Antar-Layanan
1. **Frontend Client (Browser):** Pengguna (Pemilik / Manajer IKM) mengakses modul peramalan pada antarmuka web dan memilih parameter target (jenis bahan baku marmer/onyx/batu kali, horizon proyeksi 1–12 bulan, dan model algoritma).
2. **Laravel Backend (API Consumer):** `ForecastingController.php` mengekstraksi data deret waktu historis pemakaian/produksi dari tabel `stock_transactions` dan `work_orders`, memformatnya menjadi *payload* JSON, lalu mengirimkan permintaan via *HTTP POST* ke endpoint microservice.
3. **FastAPI Microservice (Machine Learning Provider):** Layanan Python (`main.py` berjalan pada port internal 8001) menerima payload, menjalankan algoritma (*Holt-Winters Exponential Smoothing*, *Single Moving Average*, atau *ARIMA*), menghitung metrik evaluasi akurasi (*MAPE* dan *RMSE*), dan mengembalikan hasil proyeksi berupa kurva prediksi.
4. **Mekanisme Ketahanan Sistem (*Resilience & Fallback Engine*):** Jika layanan microservice mengalami *timeout* (> 3 detik) atau *unreachable*, *controller* Laravel secara otomatis mengaktifkan *internal local calculation engine* sehingga operasional pengguna tetap berjalan tanpa gangguan (*zero downtime degradation*).
5. **Persistensi Data:** Hasil kalkulasi disimpan ke dalam tabel basis data `forecasting_logs` untuk histori audit dan divisualisasikan menggunakan pustaka *Chart.js*.

---

## 3. Spesifikasi Infrastruktur Server & Konfigurasi Cloud

### 3.1 Rincian Konfigurasi Server Produksi
* **Sistem Operasi:** Ubuntu 22.04 / 24.04 LTS (64-bit)
* **Web Server & Reverse Proxy:** Nginx 1.24+ dengan enkripsi HTTPS (*Let's Encrypt SSL/TLS*)
* **Backend Runtime:** PHP 8.3-FPM (Memory Limit: 256MB, Max Execution Time: 60s)
* **Microservice Runtime:** Python 3.10+ dikelola sebagai layanan daemon *Systemd* (`escm-forecasting.service`) dengan *ASGI Server Uvicorn*
* **Database Engine:** MySQL 8.0 / 8.4 Community Server dengan optimasi *InnoDB Buffer Pool*
* **Manajemen Dependency:** Composer 2.7+ dan Python Virtual Environment (`venv`)

### 3.2 Skema Otomatisasi Pencadangan (*Automated Backup Cron Job*)
Untuk mencegah kehilangan data riil IKM (*accidental data loss*), peladen dilengkapi dengan skrip pencadangan berkala yang dieksekusi secara otomatis setiap hari pada pukul 02.00 WIB:
* **Perintah Cron:** `0 2 * * * /var/www/web-scm/scripts/backup-db.sh > /dev/null 2>&1`
* **Format Arsip:** Berkas dump SQL terkompresi (`db_escm_marmer_YYYYMMDD_HHMMSS.sql.gz`)
* **Retensi Cadangan:** Rotasi otomatis penghapusan berkas cadangan yang berusia lebih dari 14 hari untuk menghemat ruang penyimpanan server.

---

## 4. Form 8.1: Matriks Pengujian Integrasi REST API Algoritma

| No | Endpoint API | Metode | Payload Masukan (JSON) | Hasil Respon & Metrik | Waktu Respon | Status |
| :--- | :--- | :---: | :--- | :--- | :---: | :---: |
| **1** | `/api/health` | GET | Tanpa Payload (Healthcheck) | `{"status": "online", "cluster": "IKM Marmer"}` | 12 ms | **PASS** |
| **2** | `/api/forecast/predict` | POST | Item: `material`, ID: `1`, Horizon: `3`, Model: `holt_winters` | Proyeksi 3 bulan ke depan, `MAPE: 6.42%`, `RMSE: 14.82` | 185 ms | **PASS** |
| **3** | `/api/forecast/predict` | POST | Item: `product`, ID: `4`, Horizon: `6`, Model: `moving_average` | Proyeksi 6 bulan ke depan, `MAPE: 8.15%`, `RMSE: 18.30` | 142 ms | **PASS** |
| **4** | `/api/forecast/predict` | POST | Payload historis tidak lengkap ($< 4$ titik data) | Microservice otomatis menyuntikkan *empirical baseline data* | 160 ms | **PASS** |
| **5** | `/forecasting/calculate` | POST | Simulasi Microservice Mati / *Port Blocked* | Laravel *fallback engine* aktif, data tetap tersimpan di DB | 45 ms | **PASS** |

---

## 5. Form 8.2: Hasil Pengujian Akses Multi-Device (Cross-Device Testing)

| No | Kategori Perangkat | Spesifikasi / Resolusi | Lingkungan Pengujian | Hasil Uji Antarmuka & Responsivitas | Status |
| :--- | :--- | :--- | :--- | :--- | :---: |
| **1** | **Smartphone Android** | Layar 6.5" ($393 	imes 851\text{ px}$), Chrome Mobile | Lantai Bengkel Bubut & Gudang Bongkahan | Navigasi *touch-friendly*, tombol aksi $\ge 44\text{px}$, modal form pas di layar tanpa *horizontal scrolling*. | **PASS** |
| **2** | **Smartphone Android** | Layar 6.1" ($360 	imes 800\text{ px}$), Samsung Browser | Area Bongkar Muat Distribusi (Supir Truk) | Tabel surat jalan dapat di-*scroll* mulus, tombol checklist packing kayu mudah disentuh jari. | **PASS** |
| **3** | **Tablet Android / iPad** | Layar 10.5" ($820 	imes 1180\text{ px}$), Safari / Chrome | Pos Mandor Produksi (Suparno) | Kanban board 5 kolom tampil proporsional, perpindahan status pengerjaan SPK berjalan lancar. | **PASS** |
| **4** | **Laptop / Komputer PC** | Layar 14"–24" ($1366 	imes 768$ s.d. $1920 	imes 1080\text{ px}$) | Kantor Manajemen IKM (M. Ilham / Efri) | Dashboard eksekutif menampilkan 4 KPI card, grafik Chart.js, dan tabel data master secara komprehensif. | **PASS** |

---

## 6. Output Akhir & Deliverable Kegiatan 8 (Checklist)

- [x] **Microservice Forecasting Python Terintegrasi:** Berjalan mandiri via FastAPI dengan dukungan *Holt-Winters*, *Moving Average*, dan *ARIMA*.
- [x] **Koneksi RESTful API Laravel $\leftrightarrow$ Python:** Terhubung dengan mekanisme *fail-safe local engine fallback*.
- [x] **Konfigurasi Cloud Server & Nginx Reverse Proxy:** Siap produksi dengan enkripsi SSL/TLS HTTPS dan proteksi firewall.
- [x] **Otomatisasi Backup Basis Data:** Script shell pencadangan harian terkonfigurasi pada *cron job* server.
- [x] **Validasi Aksesibilitas Multi-Device 100% PASS:** Teruji optimal pada perangkat seluler lantai bengkel maupun komputer kantor manajemen.
