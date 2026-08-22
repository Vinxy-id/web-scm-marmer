# PANDUAN DESAIN ANTARMUKA & UI/UX (UI_GUIDE.md)
## Sistem Informasi E-Supply Chain Management Klaster IKM Marmer

Panduan ini berisi standarisasi visual, komponen antarmuka, prinsip aksesibilitas, dan tata letak layar untuk sistem E-SCM Marmer Tulungagung.

---

## 1. Prinsip Desain UI/UX IKM

1. **Mobile-First & Responsif:**
   - Mayoritas pengguna di lantai bengkel bubut (mandor/operator) dan gudang mengakses sistem via *smartphone* atau tablet. Tampilan harus fleksibel dari layar kecil ($360\text{px}$) hingga monitor desktop ($1920\text{px}$).
2. **High Contrast & Touch Target Besar:**
   - Lingkungan bengkel marmer berdebu dan pencahayaan dinamis. Tombol aksi utama (misal: *Lolos QC*, *Mulai Bubut*, *Simpan Transaksi*) memiliki tinggi minimal **$48\text{px}$** dengan kontras warna rasio minimal $4.5:1$ (standar WCAG AA).
3. **Aturan 3-Klik (Three-Click Rule):**
   - Pengguna dapat mengakses fungsi utama (seperti input transaksi stok masuk atau melihat status SPK) dalam maksimal 3 ketukan layar dari dashboard.
4. **Bahasa Operasional yang Familiar:**
   - Menggunakan istilah sehari-hari pengrajin marmer (misal: *Mesin Slep*, *Pembubutan*, *Bongkahan*, *Lubang Afur*, *Hi-Glossy*) dan menghindari istilah teknis IT yang membingungkan.

---

## 2. Design System & Style Guide

### 2.1 Palet Warna (Color Palette)

| Kategori Warna | Kode Hex | Variabel CSS | Penggunaan Utama |
| :--- | :--- | :--- | :--- |
| **Primary (Marble Blue)** | `#1E3A8A` | `--primary-color` | Header, sidebar navigasi, tombol utama |
| **Primary Accent** | `#3B82F6` | `--primary-accent` | Hover state, tab aktif, link interaktif |
| **Success (Green Emerald)** | `#10B981` | `--success-color` | Transaksi Masuk (In), Lolos QC, Status Selesai |
| **Warning (Amber/Orange)** | `#F59E0B` | `--warning-color` | Transaksi Opening, Perlu Rework/Tambal, Alert Stok Rendah |
| **Danger (Ruby Red)** | `#EF4444` | `--danger-color` | Transaksi Keluar (Out), Cacat Total (Scrap), Alert Kritis |
| **Consign (Purple Indigo)** | `#8B5CF6` | `--consign-color` | Transaksi Titipan/Konsinyasi |
| **Neutral Dark (Text)** | `#1F2937` | `--text-main` | Teks judul dan isi utama |
| **Neutral Light (BG)** | `#F9FAFB` | `--bg-page` | Latar belakang halaman |
| **Card / Surface** | `#FFFFFF` | `--bg-card` | Kartu konten, modal, container tabel |
| **Border / Divider** | `#E5E7EB` | `--border-color` | Garis tabel, batas kartu |

### 2.2 Tipografi (Typography)
- **Font Utama:** `Inter`, `system-ui`, `-apple-system`, `sans-serif` (bersih, mudah dibaca di layar HP).
- **Hirarki Ukuran Teks:**
  - `H1 / Judul Dashboard:` $24\text{px}$ / $1.5\text{rem}$ (Bold - 700)
  - `H2 / Judul Bagian Modul:` $20\text{px}$ / $1.25\text{rem}$ (Semi-Bold - 600)
  - `H3 / Judul Card KPI:` $16\text{px}$ / $1.0\text{rem}$ (Medium - 500)
  - `Angka Metrik / KPI Metric:` $28\text{px}$ - $32\text{px}$ (Bold - 700)
  - `Body Text (Isi):` $14\text{px}$ / $0.875\text{rem}$ (Regular - 400, Line-height: 1.5)
  - `Keterangan / Badge / Helper:` $12\text{px}$ / $0.75\text{rem}$ (Medium - 500)

### 2.3 Komponen Antarmuka Standar

#### A. Card KPI Dashboard
Setiap kartu metrik menampilkan:
- Ikon berwarna sesuai jenis metrik.
- Label kategori transaksi/stok.
- Angka nilai total transaksi/kuantitas.
- Indikator perbandingan tren bulan ini vs bulan lalu.

#### B. Status Badges
- `<span class="badge bg-success">Selesai / Lolos QC</span>`
- `<span class="badge bg-warning">Dalam Pengerjaan</span>`
- `<span class="badge bg-danger">Stok Menipis (< Min)</span>`
- `<span class="badge bg-secondary">Draft / Menunggu</span>`

#### C. Tombol Aksi (Action Buttons)
- `.btn-primary` $\rightarrow$ Simpan Data / Buat SPK Baru.
- `.btn-outline-secondary` $\rightarrow$ Batal / Kembali.
- `.btn-sm` $\rightarrow$ Aksi tabel (Detail, Edit, Cetak).

---

## 3. Tata Letak Layar Utama (Layout & Mockup Wireframe)

### 3.1 Layar Dashboard Utama (`/dashboard`)
```
+-----------------------------------------------------------------------------------+
| [Logo E-SCM]  Klaster IKM Marmer Tulungagung                 [Profil: Pak Joko v] |
+-----------------------------------------------------------------------------------+
| [Side Nav]    | [Dashboard Header: Ringkasan Rantai Pasok & Efisiensi]            |
| - Dashboard   |                                                                   |
| - Bahan Baku  | [Card 1: Mat. Opening] [Card 2: Transaksi IN] [Card 3: Trans. OUT] [Card 4: Consign]|
| - Produksi SPK| (15 Blok - Jan)        (34 Blok - Bulan ini)  (28 Unit Terkirim)   (4 Unit Titipan)  |
| - QC & Limbah +-------------------------------------------------------------------+
| - Distribusi  | [Grafik 1: Komposisi Stok Bahan] | [Grafik 2: Distribusi Gudang]  |
| - Peramalan   | (Pie Chart: Marmer vs Onyx)      | (Column Chart: Gd. A vs Gd. B) |
| - Laporan     +-------------------------------------------------------------------+
|               | [Grafik 3: Tren Aliran Bulanan]  | [Tabel: SPK Berjalan Hari Ini] |
|               | (Line Chart In vs Out)           | (5 SPK aktif di mesin bubut)   |
+---------------+-------------------------------------------------------------------+
```

### 3.2 Layar Penerimaan & Stok Bahan Baku (`/materials`)
- **Filter Bar:** Filter kategori (Marmer, Onyx, Batu Kali), Grade (Super, B, C), dan Status Stok (Aman, Minimum, Habis).
- **Tabel Interaktif:** Kode Bahan, Nama Material, Grade, Stok Terkini, Satuan, Ambang Batas Min, dan Aksi Cepat (+ Input In / - Input Out).
- **Form Modal Input Bahan Masuk:** Tanggal, Supplier, No. Truk/Penerimaan, Jumlah Blok/Ton, Catatan Cacat Awal (Foto retakan jika ada).

### 3.3 Layar Tracking SPK Produksi (`/work-orders`)
- **Tampilan Kanban / Pipeline Stasiun:**
  1. *Kolom 1 - Antrean (Draft/Scheduled)*
  2. *Kolom 2 - Pembelahan & Slep*
  3. *Kolom 3 - Pembubutan / Gerinda*
  4. *Kolom 4 - QC Tahap 1 (Cek Bentuk)*
  5. *Kolom 5 - Finishing Poles Hi-Glossy*
  6. *Kolom 6 - QC Tahap 2 & Gudang Jadi*
- Setiap kartu SPK memuat: No. SPK, Nama Produk/Wastafel, Target Qty, Operator Penanggung Jawab, dan Tombol Cepat "Lanjut ke Tahap Berikutnya".

### 3.4 Layar Inspeksi Kualitas (QC) & Limbah (`/qc-inspection`)
- **Pilihan Tahap:** QC 1 (Setelah Bubut) atau QC 2 (Setelah Poles Kilap).
- **Form Input Terstruktur:**
  - Jumlah Unit Diperiksa.
  - Jumlah Lolos (Otomatis update stok barang jadi).
  - Jumlah Rework / Perlu Tambal (Input catatan jenis perbaikan resin).
  - Jumlah Cacat Total (Scrap).
  - **Pencatatan Sisa Bahan:** Kuantitas potongan marmer layak *stepping stone* / *wall cladding* (kg) dan limbah urukan.

### 3.5 Layar Modul Forecasting (`/forecasting`)
- **Selector:** Pilih Material atau Produk Jadi.
- **Visualisasi Hasil Ramalan:** Line chart memuat data aktual historis 12 bulan terakhir vs garis proyeksi 3–6 bulan ke depan (disertai batas atas & batas bawah confidence interval).
- **Tabel Rekomendasi Pengadaan:** Rekomendasi volume order blok batu marmer sebelum tanggal kritis kehabisan stok.

---

## 4. Standar Breakpoint Responsif

| Device | Rentang Lebar Layar | Perilaku Layout |
| :--- | :--- | :--- |
| **Mobile Portrait** | $< 576\text{px}$ | Sidebar otomatis *collapsible* (drawer menu), Card KPI 1 kolom penuh, tabel horizontal scrollable. |
| **Mobile Landscape / Tablet** | $576\text{px} - 991\text{px}$ | Card KPI 2 kolom, form modal compact, tab navigasi swipeable. |
| **Desktop / Laptop** | $\ge 992\text{px}$ | Sidebar fixed statis, Card KPI 4 kolom sejajar, visualisasi grafik grid 2x2. |
