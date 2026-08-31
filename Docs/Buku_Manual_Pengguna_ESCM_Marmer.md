# 📑 BUKU MANUAL PENGGUNA (USER GUIDE)
## SISTEM E-SUPPLY CHAIN MANAGEMENT (E-SCM) & E-COMMERCE IKM MARMER & ONYX TULUNGAGUNG
**Versi Sistem:** 1.2.0 (Updated Release)  
**Teknologi:** Laravel 11 (PHP 8.3) + FastAPI (Python 3.10) + Tailwind CSS + Chart.js + E-Commerce Checkout QRIS/Transfer + WhatsApp Deep-link  
**Domain Aplikasi:** Klaster IKM Kerajinan Marmer, Onyx, dan Batu Kali Tulungagung (Mitra Empiris: UD Cahaya Onix & UD Putra Abadi)  

---

## DAFTAR ISI

1. [Bab I: Pendahuluan & Gambaran Umum Sistem](#bab-i-pendahuluan--gambaran-umum-sistem)
2. [Bab II: Persyaratan Sistem, Autentikasi & Manajemen Pengguna (RBAC)](#bab-ii-persyaratan-sistem-autentikasi--manajemen-pengguna-rbac)
3. [Bab III: Modul E-Commerce Etalase Publik, Checkout & Pelacakan Pesanan](#bab-iii-modul-e-commerce-etalase-publik-checkout--pelacakan-pesanan)
4. [Bab IV: Modul Verifikasi Pesanan Masuk & Manajemen Order (Admin/Owner)](#bab-iv-modul-verifikasi-pesanan-masuk--manajemen-order-adminowner)
5. [Bab V: Modul Manajemen Master Produk & Kategori (Multi-Toko IKM)](#bab-v-modul-manajemen-master-produk--kategori-multi-toko-ikm)
6. [Bab VI: Modul Dashboard Utama & Visualisasi Alur Rantai Pasok](#bab-vi-modul-dashboard-utama--visualisasi-alur-rantai-pasok)
7. [Bab VII: Modul Manajemen Bahan Baku & Mutasi Stok Gudang](#bab-vii-modul-manajemen-bahan-baku--mutasi-stok-gudang)
8. [Bab VIII: Modul Manajemen Produksi, SPK & Papan Kanban](#bab-viii-modul-manajemen-produksi-spk--papan-kanban)
9. [Bab IX: Modul Quality Control (QC) & Pengendalian Limbah (Waste)](#bab-ix-modul-quality-control-qc--pengendalian-limbah-waste)
10. [Bab X: Modul Distribusi & Checklist Packing Peti Kayu](#bab-x-modul-distribusi--checklist-packing-peti-kayu)
11. [Bab XI: Modul Peramalan Permintaan AI (Forecasting Assistant)](#bab-xi-modul-peramalan-permintaan-ai-forecasting-assistant)
12. [Bab XII: Laporan Manajerial & Panduan Pemecahan Masalah (FAQ)](#bab-xii-laporan-manajerial--panduan-pemecahan-masalah-faq)

---

## BAB I: PENDAHULUAN & GAMBARAN UMUM SISTEM

### 1.1 Latar Belakang
Industri Kecil dan Menengah (IKM) kerajinan marmer, onyx, dan batu kali di Kabupaten Tulungagung (khususnya wilayah sentra Campurdarat) memiliki karakteristik proses bisnis yang khas. Produk kerajinan seperti *Wastafel Batu Kali*, *Wastafel Onyx Transparan*, *Pedestal Luxury*, dan *Stepping Stone Taman* merupakan barang bernilai seni (*craftsmanship*) dengan sifat serat alam yang unik pada setiap unitnya.

Aplikasi **E-SCM & E-Commerce Marmer & Onyx Tulungagung** dibangun untuk memfasilitasi transaksi digital e-commerce sekaligus mengintegrasikan seluruh alur operasional rantai pasok (*Supply Chain Management*) IKM:
- **Etalase E-Commerce Multi-IKM Publik:** Menampilkan katalog produk terkurasi dari **UD Cahaya Onix** (Spesialis Marmer & Onyx) dan **UD Putra Abadi** (Spesialis Batu Kali & Kerajinan Taman) dengan fitur **Checkout Online (Opsi DP 50% / Lunas 100%)** dan **Konsultasi WhatsApp**.
- **Sistem Verifikasi 2-Gate SPK:** Mencegah pesanan belum bayar/spam mencemari lantai bengkel kerja.
- **Penerbitan Invoice Digital & Live Tracking:** Invoice digital otomatis ber-QR Code dan halaman **Pelacakan Pesanan Live (`/lacak-pesanan`)**.
- **Pencatatan Stok Bahan Baku Tambang:** Monitoring persediaan bongkahan batu dari penambang lokal Campurdarat dan Boyolangu.
- **Penjadwalan Produksi Digital (Kanban Board):** Manajemen alur kerja kartu SPK melalui 5 tahapan produksi terstandarisasi.
- **Pengendalian Mutu & Limbah (QC & Waste):** Inspeksi 2-tahap (Bentuk & Polesan) dan pencatatan limbah padat serta lumpur poles untuk *clean production*.
- **Verifikasi Packing Peti Kayu & Distribusi:** Standardisasi pengemasan peti kayu solid (*wooden crate pallet*) dan penerbitan Surat Jalan.
- **Peramalan Permintaan Berbasis AI:** Komputasi time-series deret waktu menggunakan model terbaik **ARIMA(2,0,2)** (Akurasi MAPE 5.73%), *Single Exponential Smoothing*, *Holt-Winters*, dan *Moving Average*.

### 1.2 Arsitektur Sistem
Sistem menggunakan arsitektur *Decoupled System*:
- **Web Application Core (Laravel 11+):** Mengelola transaksi e-commerce, basis data relasional, autentikasi multi-role RBAC, master produk & kategori, stok material, SPK produksi, Kanban, QC, distribusi, dan laporan.
- **AI Forecasting Microservice (FastAPI Python):** Layanan komputasi time-series yang mengeksekusi algoritma ARIMA dan pemulusan deret waktu dari 17 bulan data observasi lapangan.

```
+---------------------------------------------------------------------------------+
|                              PELANGGAN / BUYER                                  |
|         (Katalog Publik, Checkout DP 50%/Lunas, Live Tracking Pesanan)          |
+----------------------------------------┬----------------------------------------+
                                         │
                                         ▼
+---------------------------------------------------------------------------------+
|                          LARAVEL 11 CORE APPLICATION                            |
| (Auth/RBAC, Orders, Master Produk, Materials, Kanban, QC, Distribusi, Reports) |
+----------------------------------------┬----------------------------------------+
                                         │
                                         ▼
+---------------------------------------------------------------------------------+
|                     PYTHON FASTAPI FORECASTING MICROSERVICE                     |
|           (ARIMA(2,0,2), Single Exp Smoothing, Holt-Winters, Moving Average)   |
+---------------------------------------------------------------------------------+
```

---

## BAB II: PERSYARATAN SISTEM, AUTENTIKASI & MANAJEMEN PENGGUNA (RBAC)

### 2.1 Persyaratan Perangkat Keras & Lunak
- **Sisi Pengguna (Client):** Komputer desktop, laptop, tablet, atau smartphone dengan peramban modern (Google Chrome, Mozilla Firefox, Microsoft Edge, Safari) yang terhubung internet.
- **Sisi Server (Server Environment):**
  - Web Server: Nginx / Apache
  - PHP: Versi 8.2 atau 8.3 (Extension: `pdo_mysql`, `mbstring`, `openssl`, `curl`, `gd`, `bcmath`)
  - Python: Versi 3.10+ (Library: `fastapi`, `uvicorn`, `pandas`, `numpy`, `statsmodels`, `scikit-learn`)
  - Database: MySQL 8.0+ / MariaDB 10.6+

### 2.2 Hak Akses Pengguna (Role-Based Access Control / RBAC)
Sistem membagi wewenang ke dalam 5 peran (*role*) terproteksi:

| Peran (Role) | Hak Akses & Tanggung Jawab Utama | Akses Menu |
| :--- | :--- | :--- |
| **Owner (Pemilik IKM)** | Akses penuh ke seluruh modul, monitoring KPI bisnis eksekutif, audit laporan finansial, peramalan AI, dan manajemen akun pengguna. | Semua Menu (`/dashboard`, `/products`, `/orders`, `/materials`, `/production`, `/qc`, `/distribution`, `/forecasting`, `/reports`, `/users`) |
| **Admin** | Mengelola verifikasi pesanan masuk (*Order Management*), master katalog produk & kategori, serta administrasi data master. | `/dashboard`, `/products`, `/categories`, `/orders`, `/materials`, `/production`, `/qc`, `/distribution`, `/reports`, `/users` |
| **Gudang** | Mengelola penerimaan pasokan bahan baku dari penambang, pencatatan mutasi stok (In/Out/Opname), dan pemantauan *safety stock*. | `/dashboard`, `/materials`, `/reports` |
| **Produksi** | Mengelola Surat Perintah Kerja (SPK), memajukan kartu kerja di papan Kanban, monitoring WIP, dan mencatat log limbah produksi. | `/dashboard`, `/production`, `/production/kanban`, `/production/wip`, `/waste` |
| **Distribusi (Driver)** | Melakukan verifikasi checklist *wooden crate packing*, menerbitkan Surat Jalan pengiriman, dan memperbarui status ekspedisi kargo. | `/dashboard`, `/distribution` |

### 2.3 Manajemen Akun Pengguna (`/users`) *(Khusus Owner & Admin)*
Halaman untuk mengelola identitas seluruh personil IKM yang beroperasi di dalam sistem.

**Langkah Menambah Akun Pengguna Baru:**
1. Masuk ke menu **Manajemen Pengguna** (`/users`).
2. Klik tombol **"+ Tambah Akun Baru"**.
3. Lengkapi formulir:
   - **Nama Lengkap:** Nama pengguna (misal: *Budi Santoso*).
   - **Email Login:** Alamat email valid untuk autentikasi sistem.
   - **Password Awal:** Minimal 6 karakter.
   - **Peran (Role):** Pilih antara `Owner`, `Admin`, `Gudang`, `Produksi`, atau `Distribusi`.
   - **Nama IKM:** Pilih unit usaha penempatan (`UD Cahaya Onix` atau `UD Putra Abadi`).
   - **Nomor Telepon / WhatsApp:** Kontak aktif operator.
4. Klik **"Simpan Pengguna"**.

**Fitur Kontrol Pengguna:**
- **Toggle Status Aktif/Nonaktif:** Menghentikan sementara akses login staf yang sedang cuti/nonaktif tanpa menghapus riwayat pekerjaannya.
- **Proteksi Akun Mandiri:** Pengguna tidak dapat menonaktifkan atau menghapus akun miliknya sendiri yang sedang login.

---

## BAB III: MODUL E-COMMERCE ETALASE PUBLIK, CHECKOUT & PELACAKAN PESANAN

### 3.1 Halaman Etalase & Katalog Produk (`/`, `/katalog`, `/katalog/{id}`)
Menampilkan etalase produk kerajinan marmer, onyx, dan batu kali hasil karya pengrajin IKM Tulungagung.

**Fitur Utama Katalog:**
1. **Pencarian & Multi-Filter:**
   - Pencarian kata kunci (nama produk, kode SKU `PRD-xxx`, spesifikasi dimensi).
   - Filter Kategori (*Wastafel*, *Batuan Kali*, *Pedestal*, *Dekorasi*).
   - Filter Bahan Alam (*Marmer*, *Onyx*, *Batu Kali*).
   - Filter Toko / Mitra IKM (*UD Cahaya Onix* vs *UD Putra Abadi*).
   - Filter Ketersediaan (*Ready Stock* vs *Pre-Order*).
2. **Kartu Produk E-Commerce:**
   - Menampilkan foto produk asli 1:1, kode SKU, badge toko IKM, harga jual standar, dimensi teknis, dan stok siap kirim.
   - **Tombol "Beli / Checkout":** Membuka formulir pemesanan langsung.
   - **Tombol "Tanya WA":** Membuka deep-link obrolan WhatsApp pengrajin dengan pesan otomatis terisi.

### 3.2 Alur Transaksi Checkout E-Commerce (`/checkout/{id}`)
1. Pembeli memilih produk dari etalase dan mengeklik **"Beli"**.
2. Mengisi formulir data pesanan:
   - **Data Penerima:** Nama Lengkap, Nomor WhatsApp aktif (format `08xxx` / `628xxx`), Kota Tujuan, dan Alamat Lengkap.
   - **Catatan Kustom:** (Opsional) Permintaan corak urat batu atau ukuran khusus.
   - **Skema Pembayaran:**
     - **DP 50% (Uang Muka Produksi):** Opsi fleksibel untuk produk pesanan kustom / Pre-Order.
     - **Lunas 100% (Full Payment):** Opsi untuk produk ready stock dengan prioritas pengiriman instan.
   - **Metode Pembayaran:**
     - **QRIS Instan:** Mendukung semua e-wallet (GoPay, DANA, OVO, ShopeePay) dan Mobile Banking.
     - **Transfer Bank Resmi IKM:** Bank BCA, Bank BRI, atau Bank Mandiri (sesuai nomor rekening pemilik IKM terkait).
3. Klik **"Konfirmasi & Buat Pesanan"**.

### 3.3 Halaman Faktur Tagihan Digital (`/order/invoice/{orderNumber}`)
Setelah pesanan dibuat, pembeli langsung diarahkan ke faktur digital yang menyajikan:
- **Nomor Pesanan Unik:** Format `ORD-YYYYMMDD-XXXX`.
- **Rincian Tagihan & Kode Verifikasi:** Nominal pembayaran disertai 3 digit kode unik transfer untuk verifikasi otomatis.
- **Informasi Pembayaran:** QRIS dinamis atau nomor rekening bank IKM tujuan beserta tombol salin nomor rekening.
- **Tombol Aksi:** **"Cetak Invoice"**, **"Konfirmasi via WhatsApp"**, dan **"Lacak Progres Pesanan"**.

### 3.4 Halaman Pelacakan Pesanan Real-Time (`/lacak-pesanan`)
Pelanggan dapat memantau status pengerjaan barang secara transparan hanya dengan memasukkan Nomor Pesanan (`ORD-xxx`) atau Nomor SPK (`SPK-xxx`):
1. `Tahap 1: Pesanan Masuk (Verifikasi Bukti Pembayaran DP/Lunas)`
2. `Tahap 2: Proses Produksi (Pemotongan Blok, Pembubutan & Pemolesan di Bengkel)`
3. `Tahap 3: Inspeksi Kualitas QC (Uji Bebas Retak & Kilap Polesan)`
4. `Tahap 4: Pengemasan Peti Kayu Solid (Wooden Crate Pallet Packing)`
5. `Tahap 5: Dalam Pengiriman Kargo Logistik / Telah Diterima Pembeli`.

---

## BAB IV: MODUL VERIFIKASI PESANAN MASUK & MANAJEMEN ORDER (ADMIN/OWNER)

### 4.1 Prinsip Keamanan 2-Gate SPK Verification
Untuk mencegah pesanan palsu (*spam/bot*) mencemari antrean produksi di bengkel pengrajin, sistem menerapkan mekanisme verifikasi dua pintu (*2-Gate Pattern*):
- **Gate 1 (Checkout Masuk):** Pesanan baru berstatus `pending_payment` dan hanya tersimpan di daftar pesanan masuk admin. Tidak ada SPK produksi yang dibuat sebelum ada pembayaran.
- **Gate 2 (Verifikasi Admin):** Setelah admin memverifikasi bukti transfer pembayaran DP atau lunas, admin mengeklik tombol verifikasi untuk menerbitkan SPK resmi ke lantai bengkel.

### 4.2 Halaman Manajemen Pesanan (`/orders`)
Modul bagi Admin dan Owner untuk memvalidasi pembayaran dan mengontrol siklus hidup pesanan pembeli.

**Fitur & Aksi Admin:**
1. **Verifikasi Pembayaran & Terbitkan SPK:**
   - Klik tombol **"Verifikasi & Terbitkan SPK"** pada pesanan yang telah valid.
   - Status pesanan berubah menjadi `paid` / `paid_dp`.
   - Sistem secara otomatis menerbitkan dokumen **Surat Perintah Kerja (SPK)** berstatus `scheduled` ke papan Kanban bengkel.
2. **Batalkan Pesanan (Cancel):**
   - Digunakan jika pembeli membatalkan pesanan atau tidak menyelesaikan pembayaran melewati batas 24 jam.
3. **Hapus Pesanan (Delete):**
   - Menghapus rekaman pesanan yang tidak valid atau pesanan sampah (*spam*).

---

## BAB V: MODUL MANAJEMEN MASTER PRODUK & KATEGORI (MULTI-TOKO IKM)

### 5.1 Halaman Master Produk (`/products`)
Modul untuk mengelola katalog produk jadi milik unit usaha IKM (UD Cahaya Onix & UD Putra Abadi).

**Fitur Utama:**
- **Indikator Statistik SKU:** Total produk terdaftar, unit siap kirim, item stok kritis, dan total SKU per bahan alam.
- **Filter Multi-Kriteria:** Filter berdasarkan Toko/IKM (`UD Cahaya Onix` / `UD Putra Abadi`), Kategori, dan Jenis Bahan Alam.
- **Penomoran Kode Produk Otomatis:** Kode SKU dibuat berurutan dan terstandarisasi (`PRD-MRM-xxx`, `PRD-ONX-xxx`, `PRD-BKL-xxx`).

### 5.2 Menambah Produk Baru (Modal Tambah Produk)
1. Klik tombol **"Tambah Produk Baru"**.
2. Lengkapi formulir:
   - **Pilihan Toko / Mitra IKM (Wajib):** Pilih secara manual antara `UD Cahaya Onix` (Spesialis Marmer & Onyx) atau `UD Putra Abadi` (Spesialis Batu Kali & Kerajinan Taman).
   - **Nama Produk:** Nama barang (Contoh: *Wastafel Marmer Putih B1 Polished*).
   - **Kategori Produk:** Pilih kategori produk yang sesuai.
   - **Jenis Bahan Alam:** `Batu Marmer`, `Batu Onix`, atau `Batu Kali Alami`.
   - **Spesifikasi Dimensi:** Ukuran fisik (Contoh: *D: 40 cm, T: 15 cm*).
   - **Tipe Finishing:** Standar polesan (Contoh: *Hi-Glossy 95 GU*, *Honed Doff*, *Natural Rustic*).
   - **Stok Awal & Safety Stock:** Jumlah stok fisik saat ini dan batas minimum peringatan stok.
   - **HPP Standar (COGS) & Harga Jual:** Biaya pokok produksi standar dan harga jual retail resmi.
   - **Upload Foto Produk Asli:** Foto riil produk berasio kotak 1:1 format JPG/PNG/WebP.
3. Klik **"Simpan Produk"**.

### 5.3 Mengubah & Menghapus Produk
- **Edit Produk:** Klik ikon pensil untuk memperbarui nama, toko IKM pemilik, spesifikasi, harga, atau foto produk.
- **Hapus Produk:** Produk yang belum pernah terhubung dengan SPK produksi dapat dihapus. Produk yang sudah memiliki riwayat SPK diproteksi dari penghapusan demi menjaga integritas data audit.

### 5.4 Pengelolaan Kategori Produk (Modal Kelola Kategori)
Admin dapat menambah kategori baru (misal: *Lampu Taman*, *Meja Ornamen*), mengedit nama kategori, atau memantau jumlah produk aktif pada setiap kategori dalam satu antarmuka terpadu.

---

## BAB VI: MODUL DASHBOARD UTAMA & VISUALISASI ALUR RANTAI PASOK

### 6.1 Dashboard Analitik Eksekutif (`/dashboard`)
Menyajikan ringkasan performa operasional IKM secara visual dan interaktif:
- **Kartu KPI Utama:** Total Valuasi Stok Bahan Baku, Jumlah SPK Aktif di Bengkel, Rasio Kelulusan QC (%), dan Total Nilai Penjualan E-Commerce.
- **Alert Pesanan Baru:** Notifikasi kuning menyala apabila terdapat pesanan e-commerce baru yang membutuhkan verifikasi admin.
- **Grafik Komposisi Material & Tren Produksi:** Diagram Chart.js yang menyajikan proporsi bahan marmer/onyx/batu kali dan tren penyelesaian SPK bulanan.

### 6.2 Visualisasi Alur Rantai Pasok Hulu-ke-Hilir (`/supply-chain-flow`)
Memetakan 7 tahapan proses bisnis rantai pasok secara komprehensif:
1. `Bahan Baku Tambang Lokal (Campurdarat & Boyolangu)` ➔
2. `Penerimaan & Sortir Mutu Balok Batu` ➔
3. `Pesanan E-Commerce & Penerbitan SPK` ➔
4. `Pemotongan Gergaji & Pembubutan Manual` ➔
5. `Penghalusan & Polesan Hi-Glossy` ➔
6. `Inspeksi QC & Logging Limbah Industri` ➔
7. `Packing Peti Kayu Solid & Distribusi Ekspedisi`.

---

## BAB VII: MODUL MANAJEMEN BAHAN BAKU & MUTASI STOK GUDANG

### 7.1 Master Bahan Baku (`/materials`)
Digunakan oleh petugas gudang untuk memonitor persediaan bongkahan batu dari penambang mitra.

**Fitur:**
- **Status Batas Stok (Alert Badges):**
  - *Hijau (Normal):* Persediaan aman di atas batas minimum.
  - *Kuning (Rendah):* Persediaan mendekati batas minimum.
  - *Merah (Kritis):* Persediaan di bawah batas minimum (segera ajukan PO ke penambang).
- **Aturan Satuan Integer:** Stok bahan baku fisik (balok/biji/bongkahan) dikunci menggunakan bilangan bulat (*integer*) untuk menjaga konsistensi opname fisik.

### 7.2 Pencatatan Mutasi Stok (Masuk / Keluar / Opname)
1. Klik tombol **"Catat Mutasi Stok"**.
2. Pilih Material target.
3. Tentukan Jenis Transaksi:
   - **MASUK (IN):** Pasokan baru dari penambang mitra.
   - **KELUAR (OUT):** Pengambilan material untuk dialokasikan ke bengkel kerja.
   - **OPNAME (OPN):** Penyesuaian fisik berkala.
4. Masukkan jumlah dan catatan nomor PO/SPK terkait, lalu klik **"Simpan Transaksi"**.

---

## BAB VIII: MODUL MANAJEMEN PRODUKSI, SPK & PAPAN KANBAN

### 8.1 Papan Kanban Produksi Digital (`/production/kanban`)
Antarmuka visual kartu kerja yang mencerminkan lantai bengkel pengrajin marmer:

```
+-----------------------------------------------------------------------------------------+
| ANTREAN (SPK) | POTONG BLOK    | BUBUT / PAHAT  | POLES FINISHING | SIAP QC             |
+---------------+----------------+----------------+-----------------+---------------------+
| SPK-202608-01 | SPK-202608-03  | SPK-202608-05  | SPK-202608-07   | SPK-202608-09       |
| Wastafel B1   | Stepping Stone | Kap Lampu Onyx | Pedestal Luxury | Wastafel Batu Kali  |
+-----------------------------------------------------------------------------------------+
```

### 8.2 Penerbitan SPK Produksi Baru (`/production`)
1. Klik tombol **"+ Terbitkan SPK Produksi Baru"**.
2. Tentukan:
   - Nomor SPK (dihasilkan otomatis: `SPK-YYYYMM-xxx`).
   - Produk yang diproduksi dan target kuantitas (Unit).
   - Bahan baku yang dialokasikan dari gudang.
   - Prioritas kerja (*Normal*, *Tinggi*, *Urgent*).
   - Tanggal Mulai dan Target Selesai (*Due Date*).
3. Klik **"Simpan & Terbitkan SPK"**. Kartu SPK otomatis muncul pada kolom **ANTREAN** papan Kanban.

### 8.3 Memajukan Progres Pengerjaan (Stage Progression)
Mandor bengkel dapat memajukan stasiun kerja SPK secara berurutan:
`Antrean` ➔ `Pemotongan Blok` ➔ `Pembubutan & Pemahatan` ➔ `Poles Finishing` ➔ `Siap QC`.

### 8.4 Monitoring Barang Dalam Proses (`/production/wip`)
Halaman pelacakan durasi kerja per stasiun dan pemantauan utilisasi mesin bubut/slep pengrajin.

---

## BAB IX: MODUL QUALITY CONTROL (QC) & PENGENDALIAN LIMBAH (WASTE)

### 9.1 Inspeksi Kualitas 2-Tahap (`/qc`)
Setiap unit kerajinan wajib lulus pengujian kualitas sebelum diserahkan ke bagian packing:
1. Pilih kartu SPK yang berstatus *Siap QC*.
2. Masukkan **Jumlah Lulus (Passed)** dan **Jumlah Reject (Gagal)**.
3. Checklist Parameter Uji Kualitas:
   - [x] Bebas Retak Struktur Rambut/Bongkah (*Structural Crack Free*)
   - [x] Kesesuaian Dimensi & Ketebalan Dinding Wastafel
   - [x] Tingkat Kilap Polesan (*Hi-Gloss Smoothness*)
4. Klik **"Simpan Hasil Inspeksi"**. Unit yang lulus otomatis menambah stok barang jadi (*Ready Stock*).

### 9.2 Pencatatan & Pengendalian Limbah Industri (`/waste`)
Mendukung prinsip produksi bersih (*clean production*) dan hilirisasi residu batuan:
- **Kategori Limbah:** Tatal batu pecahan (*rock chips*) dan lumpur polesan air (*slurry*).
- **Pencatatan Residu:** Volume limbah (Kg/Karung) dan stasiun kerja penghasil limbah.
- **Rencana Pemanfaatan:** Didaur ulang menjadi *wall cladding*, batu taman sikat, atau bahan teraso.

---

## BAB X: MODUL DISTRIBUSI & CHECKLIST PACKING PETI KAYU

### 10.1 Manajemen Pengiriman Kargo (`/distribution`)
Produk marmer dan batu kali memiliki bobot berat (20 kg s.d. 150 kg per unit) dan berisiko pecah apabila tidak dipacking sesuai standar ekspedisi kargo.

### 10.2 Prosedur Penerbitan Surat Jalan & Verifikasi Packing
1. Pada antrean barang siap kirim, klik **"ACC & Terbitkan Surat Jalan"**.
2. Masukkan informasi pengiriman:
   - Ekspedisi Kargo, Nama Supir, dan Nomor Polisi Kendaraan (Contoh: *AG 8492 UT*).
   - Kota Tujuan Pengiriman dan Alamat Penerima.
3. Wajib menyelesaikan **Checklist Verifikasi Packing:**
   - [x] Lapisan Pembungkus *Foam Sheet* Tebal / *Bubble Wrap*
   - [x] Rangka Peti Kayu Solid Terpaku Rapat (*Wooden Crate Pallet*)
   - [x] Stiker Peringatan *"Barang Pecah Belah / Fragile"*
4. Klik **"Terbitkan Surat Jalan"** (Nomor Surat Jalan: `SJ-YYYYMM-xxx`).

### 10.3 Pembaruan Status Distribusi
Status pengiriman diperbarui bertahap oleh petugas distribusi:
`Packing / Siap Muat` ➔ `Dalam Perjalanan Kargo` ➔ `Telah Diterima Pelanggan`.

---

## BAB XI: MODUL PERAMALAN PERMINTAAN AI (FORECASTING ASSISTANT)

### 11.1 Halaman Forecasting AI (`/forecasting`)
Modul kecerdasan buatan terintegrasi untuk memproyeksikan kebutuhan bahan baku dan permintaan produk jadi 1 hingga 12 bulan ke depan.

```
+---------------------------------------------------------------------------------+
| MODEL AI TERBAIK: ARIMA(2,0,2) Model AI (Tingkat Error Presisi MAPE: 5.73%)     |
+---------------------------------------------------------------------------------+
| Basis Data: 17 Bulan Data Empiris IKM Tulungagung (Januari 2025 - Mei 2026)     |
| Horizon Default: 3 Bulan Proyeksi ke Depan                                      |
+---------------------------------------------------------------------------------+
```

### 11.2 Pilihan Algoritma Peramalan Terintegrasi
1. **ARIMA(2,0,2) [Sangat Direkomendasikan]:** Model deret waktu terbaik hasil uji empiris notebook riset (*Bima2026.ipynb*) dengan akurasi presisi tinggi (**MAPE = 5.73%**).
2. **Single Exponential Smoothing (SES):** Pemulusan eksponensial tunggal dengan parameter $\alpha$ optimal.
3. **Holt-Winters Smoothing:** Mengakomodasi tren pertumbuhan linier.
4. **Moving Average (SMA k=3):** Model rata-rata bergerak sederhana.

### 11.3 Langkah Menjalankan Peramalan
1. Pilih **Target Entitas:** `Produk Jadi` atau `Bahan Baku`.
2. Pilih **Item Sasaran** (Contoh: *Wastafel Marmer Putih B1* atau *Batu Kali Alami*).
3. Pilih **Model Algoritma** (Pilih *ARIMA(2,0,2)*).
4. Tentukan rentang horizon (Default: 3 bulan).
5. Klik tombol **"Hitung Ulang Peramalan"**.
6. Sistem menampilkan grafik interaktif data historis vs hasil prediksi (beserta batas kepercayaan 95%) dan mencatat riwayat peramalan ke log audit.

---

## BAB XII: LAPORAN MANAJERIAL & PANDUAN PEMECAHAN MASALAH (FAQ)

### 12.1 Laporan & Rekapitulasi (`/reports`)
Menyediakan rekapitulasi data periodik yang dapat difilter berdasarkan rentang tanggal:
- Laporan Mutasi Stok Bahan Baku (In/Out/Opname).
- Laporan Penyelesaian SPK & Efisiensi Stasiun Produksi.
- Rekapitulasi Produk Lolos QC vs Tingkat Reject.
- Logistik Distribusi & Riwayat Surat Jalan Pengiriman.

### 12.2 Panduan Pemecahan Masalah Umum (FAQ & Troubleshooting)

* **Q: Mengapa pesanan pembeli dari checkout e-commerce tidak langsung muncul di papan Kanban?**  
  *A: Ini adalah fitur keamanan 2-Gate. Pesanan baru berstatus 'Pending Payment' di menu `/orders`. Admin/Owner wajib memvalidasi bukti transfer terlebih dahulu, kemudian klik 'Verifikasi & Terbitkan SPK' agar SPK resmi masuk ke antrean bengkel.*

* **Q: Bagaimana cara membedakan produk milik UD Cahaya Onix dan UD Putra Abadi?**  
  *A: Pada menu `/products`, setiap produk memiliki atribut toko IKM yang dapat dipilih manual saat tambah/edit produk, serta dilengkapi badge warna pembeda (Biru untuk UD Cahaya Onix, Hijau untuk UD Putra Abadi).*

* **Q: Mengapa input stok bahan baku menolak angka desimal/koma (misal: 10.5)?**  
  *A: Sistem E-SCM Marmer mengunci satuan unit fisik bongkahan batu dalam bilangan bulat (integer) demi menjaga akurasi opname gudang.*

* **Q: Apakah pembeli dapat membayar dengan uang muka (DP 50%)?**  
  *A: Ya. Pembeli dapat memilih opsi DP 50% pada form checkout. Sistem mencatat status pesanan 'paid_dp' dan SPK dapat mulai dikerjakan. Pelunasan diselesaikan sebelum barang diberangkatkan oleh kargo.*

---
*Buku Manual Pengguna ini disusun sebagai Dokumen Panduan Resmi Sistem E-SCM Marmer Tulungagung, Luaran Kegiatan Pengabdian/Penelitian SDLC, dan Lampiran Kelengkapan Pendaftaran Hak Cipta (HKI) DJKI Kemenkumham RI.*
