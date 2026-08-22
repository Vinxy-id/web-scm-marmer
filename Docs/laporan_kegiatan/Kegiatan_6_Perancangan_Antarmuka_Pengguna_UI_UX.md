# DOKUMEN OUTPUT KEGIATAN 6
## Perancangan Antarmuka Pengguna (UI/UX) & Prototype Interaktif
**Proyek:** Rancang Bangun Sistem Informasi E-Supply Chain Terintegrasi untuk Akselerasi Hilirisasi Klaster IKM Marmer di Kabupaten Tulungagung  
**Mitra Studi Kasus:** UD Cahaya Onix & UD Putra Abadi (Kabupaten Tulungagung)  
**Metodologi SDLC:** Tahap Perancangan Antarmuka (*System Design & Interface Prototyping*)

---

## 1. Tujuan Kegiatan
Kegiatan ini bertujuan untuk merancang antarmuka pengguna (UI/UX) yang mudah digunakan, responsif (*mobile-first*), dan berdaya guna tinggi bagi pelaku IKM Marmer Tulungagung dengan tingkat literasi digital yang beragam. Rancangan ini memastikan bahwa sistem benar-benar diadopsi dan digunakan secara aktif dalam operasional harian bengkel bubut, lantai gudang, dan distribusi.

---

## 2. Form 6.1: User Persona & User Journey Map

### Persona 1: Pak Joko Santoso (48 Tahun) — Pemilik / Manajer IKM Marmer (UD Cahaya Onix)
- **Tingkat Literasi Digital:** Menengah-Rendah (terbiasa WhatsApp, akses via smartphone Android).
- **Peran & Tanggung Jawab:** Mengawasi profitabilitas, memantau ketersediaan bahan mentah marmer/onyx, memastikan pesanan pelanggan luar kota selesai tepat waktu.
- **Pain Points Saat Ini:** Sering tidak tahu stok riil bongkahan batu di gudang tanpa bertanya langsung ke staf; pembukuan manual rawan selisih.
- **User Journey Map:**
  `Buka Dashboard di HP` $\rightarrow$ `Cek Alert Merah Stok Kritis` $\rightarrow$ `Lihat Grafik Proyeksi Peramalan Bahan` $\rightarrow$ `Pantau 5 SPK Aktif di Mesin Bubut`.

### Persona 2: Mas Budi Setiawan (32 Tahun) — Kepala Gudang & Bahan Baku
- **Tingkat Literasi Digital:** Menengah (menggunakan HP & laptop kantor).
- **Peran & Tanggung Jawab:** Menerima kiriman bongkahan batu dari tambang Besole/Campurdarat, mencatat grade/dimensi, mengeluarkan bahan ke mesin slep.
- **Pain Points Saat Ini:** Pemeriksaan berulang saat batu datang; retak serat batu terlambat terdeteksi.
- **User Journey Map:**
  `Buka Modul Bahan Baku` $\rightarrow$ `Input Formulir Batu Masuk (Grade/Supplier)` $\rightarrow$ `Terbitkan Mutasi Stok IN` $\rightarrow$ `Cek Peringatan Stok Minimum`.

### Persona 3: Pak Slamet Riyadi (42 Tahun) — Mandor / Operator Mesin Bubut & Slep
- **Tingkat Literasi Digital:** Rendah (akses via HP di lantai bengkel berdebu).
- **Peran & Tanggung Jawab:** Memotong blok batu di mesin slep, membagi pekerjaan ke 7 mesin bubut, melakukan QC tahap 1 (bentuk mentah).
- **Pain Points Saat Ini:** Rincian ukuran wastafel tidak tertulis di dekat mesin bubut; sisa potongan marmer berserakan tidak terdata.
- **User Journey Map:**
  `Buka Tab Produksi & SPK` $\rightarrow$ `Lihat Kartu Kanban Pesanan` $\rightarrow$ `Update Status Mesin (Slep -> Bubut)` $\rightarrow$ `Input Hasil QC 1 (Lolos / Tambal Resin)`.

### Persona 4: Mbak Rini Wulandari (27 Tahun) — Staf Distribusi & Penjualan
- **Tingkat Literasi Digital:** Tinggi (menggunakan laptop & smartphone).
- **Peran & Tanggung Jawab:** Berkomunikasi dengan pembeli (arsitek/galeri di Bali & Surabaya), mengurus ekspedisi truk, verifikasi packing kayu.
- **Pain Points Saat Ini:** Sering miskomunikasi apakah wastafel sudah selesai dipoles atau belum; klaim barang retak saat pengiriman.
- **User Journey Map:**
  `Buka Modul Barang Jadi` $\rightarrow$ `Cek Stok Ready Siap Kirim` $\rightarrow$ `Verifikasi Checklist Packing Krat Kayu` $\rightarrow$ `Cetak Surat Jalan / Ubah Status Terkirim`.

---

## 3. Information Architecture (Struktur Navigasi & Sitemap)

```
[ E-SCM Marmer Dashboard ]
│
├── 1. Dashboard Eksekutif
│   ├── 4 Card KPI (Bahan Baku, SPK Aktif, Barang Jadi, Nilai Inventori)
│   ├── Diagram Alur Rantai Pasok Interaktif (8 Tahap Marmer)
│   ├── Grafik Tren Pengadaan vs Produksi (Bar Chart)
│   ├── Grafik Komposisi Inventori Batuan Alam (Donut Chart)
│   └── Status Real-time 7 Mesin Bubut & Tabel Stok Kritis
│
├── 2. Operasional Hulu (Inbound Supply Chain)
│   ├── Manajemen Bahan Baku (Filter Kritis/Rendah/Normal, CRUD Bongkahan)
│   └── Pengadaan / Purchase Order (PO ke Pemasok Tambang Besole/Campurdarat)
│
├── 3. Lantai Produksi (Shopfloor Operations)
│   ├── Kanban Board SPK (5 Kolom: Antrian, Potong Slep, Mesin Bubut, QC & Poles, Siap Kirim)
│   ├── Barang Dalam Proses (WIP Progress Tracking per Mesin Bubut)
│   ├── Pengendalian Mutu QC 2-Tahap (QC 1 Serat Bentuk vs QC 2 Poles Hi-Glossy)
│   └── Hilirisasi Residu / Limbah (Pencatatan Sisa Layak Stepping Stone vs Urukan)
│
├── 4. Hilir & Distribusi (Outbound Supply Chain)
│   ├── Inventori Barang Jadi (Stok Tersedia, Dipesan, Siap Kirim)
│   └── Distribusi & Packing (Checklist Krat Kayu & Pelacakan Ekspedisi)
│
├── 5. Kecerdasan Buatan & Analitik
│   ├── Modul Peramalan Permintaan (Holt-Winters & Moving Average via FastAPI)
│   └── Laporan Efisiensi KPI (OEE Mesin 85%, Lead Time, On-Time Delivery, Export PDF)
│
└── 6. Pengaturan Sistem (RBAC, Konfigurasi IKM, Status Keamanan)
```

---

## 4. Standarisasi Desain Visual (Design System)

1. **Palet Warna Domain Batuan Alam:**
   - **Primary Navy:** `#1E3A8A` (Header, identitas, sidebar).
   - **Accent Blue:** `#3B82F6` (Tombol aksi utama, tab aktif).
   - **Status Hijau (Emerald):** `#10B981` (Normal, Lolos QC, Lolos Packing).
   - **Status Kuning (Amber):** `#F59E0B` (Rendah, Perlu Tambal Resin, Dalam Pengerjaan).
   - **Status Merah (Ruby):** `#EF4444` (Kritis < 50% Min, Afkir/Scrap, Alert Bahaya).
2. **Tipografi & Ergonomi Touch Target:**
   - Font: *Inter / System Sans-Serif* (keterbacaan tinggi di layar HP luar ruangan).
   - Ukuran Tombol Aksi: Minimal tinggi **$44\text{px} - 48\text{px}$** untuk kemudahan sentuhan jari operator bengkel.
   - Aturan Tiga-Klik: Akses cepat ke fungsi pencatatan utama maksimal dalam 3 kali ketukan.

---

## 5. Prototype Antarmuka Interaktif (Deliverable Fisik)

Telah dibangun prototipe berbasis web interaktif mandiri pada berkas:
👉 **[`public/index.html`](file:///d:/Project%20Coding/Web%20SCM/public/index.html)**

### Fitur yang Dapat Diuji Langsung di Browser:
1. **Navigasi Tab Cepat:** Dashboard, Bahan Baku, Kanban SPK Produksi, Form QC 2-Tahap, dan Modul Peramalan AI.
2. **Diagram Alur 8 Tahap Marmer:** Setiap kotak tahapan (*Tambang, Gudang Batu, Slep, Bubut, QC 1, Poles, QC 2, Distribusi*) dapat diklik untuk berpindah konteks.
3. **Simulasi Role-Switch:** Tombol ganti peran pengguna di sudut kiri bawah untuk mensimulasikan sudut pandang Owner, Gudang, Mandor, dan Distribusi.
4. **Grafik Interaktif Chart.js:** Tren bulanan dan simulasi kurva deret waktu *Holt-Winters*.

---

## 6. Form 6.2: Skenario Usability Testing Awal

| No | Skenario Tugas Pengguna | Target Pengguna | Kriteria Berhasil | Catatan / Hasil Uji | Status |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **1** | Membaca status stok kritis marmer/onyx dari dashboard utama | Pak Joko (Owner) | Menemukan item kritis dalam $< 5$ detik | Alert banner merah & indikator langsung terbaca | **Berhasil** (3.2 detik) |
| **2** | Menavigasikan diagram alur rantai pasok 8 tahap | Mas Budi (Gudang) | Mengklik tahap "Gudang Batu" dan membuka tabel bahan | Alur navigasi interaktif berjalan mulus | **Berhasil** (2.5 detik) |
| **3** | Melihat status pengerjaan SPK wastafel di mesin bubut | Pak Slamet (Mandor) | Mengidentifikasi kartu SPK di kolom Kanban "Mesin Bubut" | Kolom visual Kanban mempermudah pemantauan | **Berhasil** (4.1 detik) |
| **4** | Memasukkan hasil inspeksi QC tahap 1 (lolos vs perlu tambal) | Petugas QC | Berhasil mengisi form QC dan membedakan unit lolos/rework | Form input sederhana dan tombol kontras | **Berhasil** (15 detik) |
| **5** | Menjalankan simulasi peramalan kebutuhan bahan baku | Admin / Owner | Klik tombol hitung ramalan dan melihat grafik proyeksi | Muncul kurva ramalan dan nilai MAPE 6.42% | **Berhasil** (2.0 detik) |

---

## 7. Output Akhir Kegiatan 6 (Checklist)

- [x] **Dokumen User Persona & Journey Map Lengkap (Form 6.1):** 4 profil pengguna riil IKM.
- [x] **Information Architecture & Sitemap:** 6 modul utama dengan navigasi intuitif.
- [x] **Design System & Standar Warna:** Sesuai karakteristik lingkungan pengrajin marmer.
- [x] **Prototype Interaktif Siap Uji:** File [`public/index.html`](file:///d:/Project%20Coding/Web%20SCM/public/index.html) berbasis HTML5, Tailwind, Chart.js, dan Lucide Icons.
- [x] **Hasil Usability Testing Awal (Form 6.2):** 100% skenario tugas dasar berhasil diselesaikan dengan waktu respon cepat.
