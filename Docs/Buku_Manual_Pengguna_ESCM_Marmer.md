# 📑 BUKU MANUAL PENGGUNA (USER GUIDE)
## SISTEM E-SUPPLY CHAIN MANAGEMENT (E-SCM) & E-COMMERCE IKM MARMER & ONYX TULUNGAGUNG
**Versi Sistem:** 1.0.0 (Release Candidate)  
**Teknologi:** Laravel 11 (PHP 8.3) + FastAPI (Python 3.10) + Tailwind CSS + Chart.js + E-Commerce Checkout QRIS/Transfer + WhatsApp Deep-link  
**Domain Aplikasi:** Klaster IKM Kerajinan Marmer, Onyx, dan Batu Kali Tulungagung (Mitra Empiris: UD Cahaya Onix & UD Putra Abadi)  

---

## DAFTAR ISI

1. [Bab I: Pendahuluan & Gambaran Umum Sistem](#bab-i-pendahuluan--gambaran-umum-sistem)
2. [Bab II: Persyaratan Sistem & Hak Akses User](#bab-ii-persyaratan-sistem--hak-akses-user)
3. [Bab III: Modul E-Commerce Etalase Publik, Checkout & Pelacakan Pesanan](#bab-iii-modul-e-commerce-etalase-publik-checkout--pelacakan-pesanan)
4. [Bab IV: Modul Dashboard Utama & Visualisasi Alur Rantai Pasok](#bab-iv-modul-dashboard-utama--visualisasi-alur-rantai-pasok)
5. [Bab V: Modul Manajemen Bahan Baku & Mutasi Stok (Admin/Owner)](#bab-v-modul-manajemen-bahan-baku--mutasi-stok-adminowner)
6. [Bab VI: Modul Manajemen Produksi, SPK & Papan Kanban](#bab-vi-modul-manajemen-produksi-spk--papan-kanban)
7. [Bab VII: Modul Quality Control (QC) & Pengendalian Limbah (Waste)](#bab-vii-modul-quality-control-qc--pengendalian-limbah-waste)
8. [Bab VIII: Modul Distribusi & Checklist Packing Peti Kayu](#bab-viii-modul-distribusi--checklist-packing-peti-kayu)
9. [Bab IX: Modul Peramalan Permintaan AI (Forecasting Assistant)](#bab-ix-modul-peramalan-permintaan-ai-forecasting-assistant)
10. [Bab X: Laporan, Audit Rekap & Panduan Troubleshooting](#bab-x-laporan-audit-rekap--panduan-troubleshooting)

---

## BAB I: PENDAHULUAN & GAMBARAN UMUM SISTEM

### 1.1 Latar Belakang
Industri Kecil dan Menengah (IKM) kerajinan marmer, onyx, dan batu kali di Kabupaten Tulungagung (khususnya wilayah Campurdarat dan Besole) memiliki karakteristik proses bisnis yang khas. Produk kerajinan seperti *Wastafel Batu Kali*, *Kap Lampu Onyx Transparan*, dan *Stepping Stone* merupakan barang bernilai seni (*craftsmanship*) dengan sifat serat alam yang unik pada setiap unitnya.

Aplikasi **E-SCM & E-Commerce Marmer & Onyx Tulungagung** dibangun untuk memfasilitasi transaksi digital e-commerce sekaligus mengintegrasikan seluruh alur operasional rantai pasok (*Supply Chain Management*) IKM:
- Etalase e-commerce publik dengan fitur **Checkout Langsung (Opsi DP 50% / Lunas 100%)** dan **Konsultasi WhatsApp**.
- Penerbitan Invoice digital otomatis ber-QR Code dan halaman **Pelacakan Pesanan Live (`/lacak-pesanan`)**.
- Pencatatan dan monitoring stok bahan baku bongkahan batu dari penambang lokal.
- Penjadwalan alur kerja produksi menggunakan papan Kanban digital.
- Pelacakan progres barang dalam proses (*Work in Progress / WIP*).
- Pengendalian kualitas (*Quality Control*) dan pencatatan limbah industri (*waste management*).
- Verifikasi pengemasan peti kayu solid (*wooden crate packing*) dan surat jalan distribusi.
- Peramalan kebutuhan bahan baku dan permintaan produk berbasis Kecerdasan Buatan (AI Time Series Forecasting).

### 1.2 Arsitektur Sistem
Sistem ini menggunakan arsitektur *Decoupled System*:
- **Web Application Core (Laravel 11):** Mengelola transaksi e-commerce checkout, basis data relasional MySQL, autentikasi multi-role, CRUD bahan baku, penerbitan SPK, papan Kanban, log inspeksi QC, surat jalan pengiriman, dan laporan manajerial.
- **AI Forecasting Microservice (FastAPI Python):** Layanan komputasi deret waktu (*time-series forecasting*) yang menguji dan mengeksekusi algoritma *ARIMA(2,0,2)*, *Single Exponential Smoothing (SES)*, *Holt-Winters*, dan *Moving Average* menggunakan dataset empiris IKM.

```
+-----------------------------------------------------------------------+
|                         PELANGGAN / BUYER                             |
|    (Katalog E-Commerce, Checkout DP 50%/Lunas, Tracking Pesanan)     |
+-----------------------------------┬-----------------------------------+
                                    │
                                    ▼
+-----------------------------------------------------------------------+
|                    LARAVEL 11 CORE APPLICATION                        |
|  (Auth, Orders, Material, Production, Kanban, QC, Distribution, Log)  |
+-----------------------------------┬-----------------------------------+
                                    │
                                    ▼
+-----------------------------------------------------------------------+
|             PYTHON FASTAPI FORECASTING MICROSERVICE                   |
|     (ARIMA(2,0,2), SES, Holt-Winters, Moving Average Machine)          |
+-----------------------------------------------------------------------+
```

---

## BAB II: PERSYARATAN SISTEM & HAK AKSES USER

### 2.1 Persyaratan Perangkat Keras & Lunak
- **Sisi Pengguna (Client):** Komputer, Laptop, Tablet, atau Smartphone dengan Browser Modern (Google Chrome, Mozilla Firefox, Microsoft Edge, Safari) yang terhubung internet.
- **Sisi Server (Server Environment):**
  - Web Server: Nginx / Apache
  - PHP: Versi 8.2 atau 8.3 (Extension: pdo_mysql, ctype, curl, mbstring, openssl)
  - Python: Versi 3.10+ (Library: fastapi, uvicorn, pandas, numpy, statsmodels)
  - Database: MySQL 8.0 / MariaDB 10.4+

### 2.2 Hak Akses Pengguna (Role User)
1. **Public / Pengunjung & Pembeli:** Mengakses katalog etalase produk online (`/`, `/katalog`, `/katalog/{id}`), melakukan checkout pemesanan e-commerce (`/checkout/{id}`), memperoleh invoice tagihan (`/order/invoice/{orderNumber}`), melacak progres pengerjaan barang (`/lacak-pesanan`), atau berkonsultasi via WhatsApp.
2. **Operator & Pengawas Lapangan:** Mengelola pencatatan mutasi stok harian (`/materials`), memproses order masuk menjadi kartu SPK pada papan Kanban (`/production/kanban`), mencatat hasil inspeksi QC (`/qc`), menginput limbah batu (`/waste`), dan menerbitkan surat jalan pengiriman (`/distribution`).
3. **Pemilik IKM / Owner (Manajerial):** Mengakses dashboard analitik bisnis (`/dashboard`), visualisasi alur pasok end-to-end (`/supply-chain-flow`), laporan rekapitulasi (`/reports`), serta menjalankan peramalan stok AI (`/forecasting`).

---

## BAB III: MODUL E-COMMERCE ETALASE PUBLIK, CHECKOUT & PELACAKAN PESANAN

### 3.1 Halaman Etalase & Detail Kerajinan (`/`, `/katalog`, `/katalog/{id}`)
Menampilkan etalase produk kerajinan marmer, onyx, dan batu kali hasil karya pengrajin IKM Tulungagung (UD Cahaya Onix & UD Putra Abadi).

**Fitur yang Tersedia:**
1. **Filter Kategori & Pencarian:** Filter berdasarkan kategori produk (*Wastafel*, *Kap Lampu Onyx*, *Pedestal*, *Aksesoris*) dan kata kunci nama barang.
2. **Kartu Produk E-Commerce:** Menampilkan foto produk, nama kerajinan, nama IKM pembuat, harga perolehan, spesifikasi dimensi, tipe polesan, serta tombol ganda:
   - **Tombol "Beli" (Warna Biru):** Membuka halaman formulir checkout e-commerce langsung.
   - **Tombol "Tanya WA" (Warna Hijau):** Membuka konsultasi langsung via WhatsApp pengrajin dengan draf pesan otomatis.

### 3.2 Alur Transaksi Checkout E-Commerce (`/checkout/{id}`)
1. Calon pembeli memilih produk dan mengeklik tombol **"Beli / Checkout Online"**.
2. Mengisi formulir pemesanan:
   - **Data Penerima:** Nama Lengkap, Nomor WhatsApp / Telepon Aktif, Kota Tujuan, dan Alamat Lengkap Pengiriman.
   - **Catatan Kustom Serat:** (Opsional) Permintaan motif batu khusus (misal: *Onyx tembus cahaya dominan cokelat madu*).
   - **Pilihan Skema Pembayaran:**
     - **DP 50% (Uang Muka Produksi):** Cocok untuk pesanan kustom / Pre-Order.
     - **Lunas 100% (Full Payment):** Untuk produk ready stock dengan prioritas pengiriman kilat.
   - **Pilihan Metode Pembayaran:**
     - **QRIS Instan (Semua Bank & E-Wallet):** BCA, Mandiri, BRI, BNI, GoPay, OVO, ShopeePay, DANA.
     - **Transfer Bank Resmi IKM:** Bank BCA, Bank BRI, atau Bank Mandiri.
3. Klik **"Konfirmasi & Buat Pesanan"**.
4. Sistem secara otomatis membuat rekaman data pesanan di tabel `orders` dan menerbitkan Surat Perintah Kerja (SPK) di antrean produksi.

### 3.3 Halaman Invoice & Tagihan Digital (`/order/invoice/{orderNumber}`)
Setelah checkout berhasil, pembeli diarahkan ke halaman invoice digital yang memuat:
- Nomor Pesanan Unik (Contoh: `ORD-20260823-A1B2`).
- Rincian nominal tagihan dengan kode verifikasi unik (+Rp 100 s.d. 999).
- Kode QRIS otomatis / Nomor rekening tujuan transfer beserta tombol salin nomor rekening.
- Tombol **"Cetak Invoice"**, **"Konfirmasi via WhatsApp"**, dan **"Lacak Progres Pesanan"**.

### 3.4 Halaman Pelacakan Pesanan Live (`/lacak-pesanan`)
Pembeli dapat memantau progres pengerjaan pesanan secara transparan dengan memasukkan Nomor Order atau Nomor SPK:
1. `Tahap 1: Pesanan Masuk (Verifikasi Invoice & Uang Muka)`
2. `Tahap 2: Papan Produksi (Pemotongan Blok, Bubut & Pahat di Bengkel)`
3. `Tahap 3: Inspeksi Quality Control (Uji Bebas Retak & Kilap Polesan)`
4. `Tahap 4: Packing Peti Kayu Solid (Pallet Krat Kayu Standar Ekspedisi)`
5. `Tahap 5: Dalam Pengiriman Kargo Logistik / Diterima Pelanggan`.

---

## BAB IV: MODUL DASHBOARD UTAMA & VISUALISASI ALUR RANTAI PASOK

### 4.1 Dashboard Eksekutif (`/dashboard`)
Halaman ringkasan operasional yang menyajikan metrik penting secara real-time:
- **Total Nilai & Jumlah Stok Bahan Baku Terkini:** Blok marmer, onyx, dan batu kali di gudang.
- **Status SPK Aktif:** Jumlah SPK dalam proses di lantai produksi.
- **Rasio Kelulusan QC:** Persentase produk lulus vs reject.
- **Status Pengiriman Kargo:** Jumlah surat jalan dalam perjalanan kargo.

### 4.2 Visualisasi Alur Rantai Pasok (`/supply-chain-flow`)
Halaman pemetaan interaktif alur rantai pasok marmer & onyx dari hulu ke hilir:
1. `Bahan Baku Tambang (Besole/Campurdarat)` ➔
2. `Penerimaan & Pengujian Kualitas Blok` ➔
3. `Pesanan E-Commerce / Penerbitan SPK` ➔
4. `Pemotongan Gergaji & Pembubutan Manual` ➔
5. `Penghalusan & Polesan Hi-Glossy` ➔
6. `Inspeksi QC & Logging Limbah` ➔
7. `Packing Peti Kayu Solid & Surat Jalan Logistik`.

---

## BAB V: MODUL MANAJEMEN BAHAN BAKU & MUTASI STOK (ADMIN/OWNER)

### 5.1 Halaman Master Material (`/materials`)
Modul ini digunakan untuk mengelola persediaan bongkahan batu dari tambang lokal.

**Fitur Utama:**
1. **Daftar Stok Bahan Baku:** Menampilkan Kode Material, Nama Bahan, Jenis (Marmer, Onyx, Batu Kali), Grade Kualitas (Grade A Super, Grade B, Grade C), Stok Terkini, dan Status Batas Minimum.
2. **Indikator Peringatan Stok (Alert Badges):**
   - *Hijau (Normal):* Stok aman di atas batas minimum.
   - *Kuning (Rendah):* Stok mendekati batas minimum.
   - *Merah (Kritis):* Stok di bawah batas minimum (segera pesan ke penambang).

### 5.2 Menambah Bahan Baku Baru
1. Klik tombol **"+ Tambah Material Baru"**.
2. Isi formulir:
   - **Kode Material:** Alfanumerik (Contoh: `MAT-MRM-003`).
   - **Nama Material:** (Contoh: *Bongkahan Batu Kali Bulat*).
   - **Jenis & Grade Batuan:** Pilihan jenis batuan dan kelas mutunya.
   - **Stok Awal & Batas Minimum (Alert):** Wajib diisi **angka bulat (integer)**. Sistem memiliki validasi real-time yang memblokir karakter desimal/koma.
   - **Harga Satuan (Rp):** Harga beli per unit/blok dari penambang.
3. Klik **"Simpan Material"**.

### 5.3 Mencatat Mutasi Stok (Masuk / Keluar)
1. Klik tombol **"Catat Mutasi Stok Masuk / Keluar"**.
2. Pilih Material target.
3. Tentukan Tipe Transaksi:
   - **MASUK:** Penambahan pasokan dari tambang penambang.
   - **KELUAR:** Pengambilan bahan untuk lantai bengkel produksi.
4. Masukkan Jumlah (Qty) dalam angka bulat.
5. Masukkan keterangan catatan transaksi, lalu klik **"Simpan Transaksi"**.

---

## BAB VI: MODUL MANAJEMEN PRODUKSI, SPK & PAPAN KANBAN

### 6.1 Papan Kanban Produksi (`/production/kanban`)
Papan visual interaktif untuk memonitor dan memindahkan alur pengerjaan Surat Perintah Kerja (SPK):

```
+---------------------------------------------------------------------------------+
| ANTREAN (3)   | POTONG BLOK (2) | BUBUT/PAHAT (4) | POLIS (2)   | SIAP QC (1)   |
+---------------+-----------------+-----------------+-------------+---------------+
| SPK-2026-001  | SPK-2026-003    | SPK-2026-005    | ...         | ...           |
| Wastafel Kali | Kap Lampu Onyx  | Pedestal Marmer |             |               |
+---------------------------------------------------------------------------------+
```

### 6.2 Alur Pembuatan SPK Baru (`/production`)
1. Klik **"+ Terbitkan SPK Produksi Baru"**.
2. Masukkan rincian:
   - **Nomor SPK:** Nomor unik surat perintah kerja.
   - **Target Produk & Jumlah:** Jumlah unit yang akan dikerjakan.
   - **Alokasi Bahan Baku:** Pilihan material dan jumlah blok yang digunakan.
   - **Tanggal Mulai & Target Selesai (Due Date).**
3. Klik **"Simpan & Terbitkan SPK"**. Kartu SPK otomatis masuk ke kolom **ANTREAN**.

### 6.3 Progres Tahapan Pengerjaan (Stage Progression)
Operator dapat memajukan tahapan produksi secara teratur:
- `Antrean` ➔ `Pemotongan Blok (Gergaji Batu)` ➔ `Pembubutan & Pemahatan` ➔ `Penghalusan / Polesan` ➔ `Siap Inspeksi QC`.

### 6.4 Pelacakan Barang Dalam Proses (`/production/wip`)
Halaman monitoring beban kerja setiap stasiun kerja dan durasi pengerjaan masing-masing unit kerajinan.

---

## BAB VII: MODUL QUALITY CONTROL (QC) & PENGENDALIAN LIMBAH (WASTE)

### 7.1 Modul Inspeksi Kualitas (`/qc`)
Setiap kerajinan yang selesai dipoles wajib melalui pengujian mutu sebelum disiapkan untuk pengemasan:
1. Pilih SPK yang berstatus *Siap QC*.
2. Masukkan **Jumlah Lulus QC (Passed)** dan **Jumlah Gagal (Rejected)**.
3. Verifikasi Parameter Kualitas:
   - [x] Bebas Retak Struktur Berbahaya (*Structural Crack Free*)
   - [x] Ketepatan Ukuran & Ketebalan Dinding Wastafel/Lampu
   - [x] Kehalusan Kilap Permukaan (*Hi-Gloss Finish*)
4. Masukkan catatan evaluasi mutu dan simpan log inspeksi.

### 7.2 Modul Pengendalian Limbah Industri (`/waste`)
Mencatat limbah sisa hasil olahan batu untuk mendukung *clean production* dan daur ulang:
- **Jenis Limbah:** Tatal batu pecahan (*rock chips*) dan lumpur polesan air (*slurry*).
- **Volume & Satuan:** Berat (Kg) atau karung limbah.
- **Rencana Pemanfaatan / Disposisi:** Digunakan kembali untuk bahan uruk pondasi atau produk turunan teraso.

---

## BAB VIII: MODUL DISTRIBUSI & CHECKLIST PACKING PETI KAYU

### 8.1 Halaman Pengiriman & Logistik (`/distribution`)
Produk marmer dan batu kali memiliki bobot berat dan rentan rusak jika tidak dikemas secara tepat.

### 8.2 Prosedur Penerbitan Surat Jalan & Verifikasi Packing
1. Pada antrean barang yang telah Lulus QC, klik **"ACC & Buat Surat Jalan"**.
2. Masukkan identitas pengiriman:
   - Nama Ekspedisi Kargo & Nama Supir Truk.
   - Nomor Polisi Kendaraan (Contoh: *AG 8492 UT*).
   - Kota Tujuan Pengiriman & Nama Penerima.
3. Check-list **Verifikasi Standar Pengemasan:**
   - [x] Pembungkus Foam Sheet Tebal / Bubble Wrap
   - [x] Peti Kayu Solid Terpaku Kuat (Pallet Crate)
   - [x] Stiker Label *"Fragile / Barang Pecah Belah"*
4. Klik **"Terbitkan Surat Jalan"**.

### 8.3 Pelacakan Status Pengiriman
Status surat jalan dapat diperbarui secara bertahap:
- `Packing / Siap Muat` ➔ `Dalam Perjalanan Kargo` ➔ `Telah Diterima Pelanggan`.

---

## BAB IX: MODUL PERAMALAN PERMINTAAN AI (FORECASTING ASSISTANT)

### 9.1 Halaman Forecasting AI (`/forecasting`)
Modul kecerdasan buatan terintegrasi untuk memperkirakan kebutuhan bahan baku bongkahan marmer dan permintaan kerajinan 1 sampai 12 bulan ke depan.

```
+---------------------------------------------------------------------------------+
| MODEL AI UTAMA: ARIMA(2,0,2) Model AI (Akurasi Presisi MAPE: 5.73%)              |
+---------------------------------------------------------------------------------+
| Dataset Historis: 17 Bulan Empiris IKM Tulungagung (Januari 2025 - Mei 2026)    |
| Horizon Prediksi: 3 Bulan ke Depan                                              |
+---------------------------------------------------------------------------------+
```

### 9.2 Pilihan Model Algoritma Terintegrasi
1. **ARIMA(2,0,2) [Model AI Terbaik]:** Model Auto-Regressive Integrated Moving Average terbaik hasil evaluasi empiris dataset IKM (*Bima2026.ipynb*) dengan tingkat kesalahan terkecil (**MAPE = 5.73%**).
2. **Single Exponential Smoothing (SES):** Metode peramalan pemulusan eksponensial tunggal dengan pencarian parameter $\alpha$ optimal ($\alpha \in [0.1 .. 0.9]$).
3. **Holt-Winters Exponential Smoothing:** Menghitung komponen tren linear historis.
4. **Moving Average (Single Moving Average k=3):** Model rata-rata bergerak sederhana.

### 9.3 Langkah Menjalankan Peramalan
1. Pilih **Target Entitas:** `Bahan Baku` atau `Produk Jadi`.
2. Pilih **Nama Item** (Contoh: *Stepping Batu Kali* atau *Marmer Trotol*).
3. Pilih **Model Algoritma** (Direkomendasikan memilih *ARIMA(2,0,2)*).
4. Tentukan horizon proyeksi (Default 3 bulan).
5. Klik **"Hitung Ulang"**.
6. Sistem menampilkan grafik interaktif Chart.js deret waktu (Data Aktual vs Hasil Ramalan 95% Confidence Interval) serta mencatat log riwayat kalkulasi.

---

## BAB X: LAPORAN, AUDIT REKAP & PANDUAN TROUBLESHOOTING

### 10.1 Halaman Laporan & Rekapitulasi (`/reports`)
Menyediakan rekapitulasi bulanan pergerakan stok bahan baku masuk/keluar, utilisasi material per SPK, dan histori mutasi gudang.

### 10.2 Panduan Troubleshooting Umum
- **Q: Mengapa input stok menolak angka pecahan (misal 12,5)?**  
  *A: Sistem E-SCM Marmer menerapkan aturan integer ketat untuk unit bongkahan fisik (blok/pcs) guna memastikan konsistensi opname fisik di gudang.*
- **Q: Bagaimana jika pembeli memilih pembayaran DP 50%?**  
  *A: Sistem mencatat status pesanan 'paid_dp' saat uang muka diterima dan otomatis memasukkan SPK ke antrean pengerjaan bengkel. Sisa pelunasan diselesaikan sebelum barang dikirim.*

---
*Buku Manual Pengguna ini disusun sebagai Deliverable Resmi Kegiatan Pengabdian/Penelitian IKM Marmer Tulungagung dan Lampiran Berkas Pendaftaran HKI DJKI Kemenkumham RI.*
