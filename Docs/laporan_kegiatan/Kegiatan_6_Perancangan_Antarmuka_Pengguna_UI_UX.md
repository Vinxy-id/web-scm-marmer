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

### Persona 1: M. Ilham Nur Amali (34 Tahun) — Pemilik / Manajer IKM Marmer (UD Cahaya Onix)
- **Tingkat Literasi Digital:** Menengah (terbiasa smartphone Android & laptop).
- **Peran & Tanggung Jawab:** Mengawasi profitabilitas, memantau pengadaan bongkahan marmer/onyx putih & hitam, memantau status pesanan ekspor/luar kota.
- **Pain Points Saat Ini:** Kesulitan merekapitulasi stok blok batuan tanpa verifikasi manual ke gudang; butuh estimasi kebutuhan bahan baku yang akurat.
- **User Journey Map:**
  `Buka Dashboard di HP` $\rightarrow$ `Cek Alert Stok Kritis Marmer/Onyx` $\rightarrow$ `Lihat Grafik Proyeksi Peramalan AI (MAPE/RMSE)` $\rightarrow$ `Pantau SPK Aktif di Mesin Bubut`.

### Persona 2: Efri Saputra (38 Tahun) — Pemilik / Manajer IKM Olahan Batu Kali (UD Putra Abadi)
- **Tingkat Literasi Digital:** Menengah (akses via smartphone & browser tablet).
- **Peran & Tanggung Jawab:** Mengelola penerimaan batu kali dari penambang, mengawasi pembuatan stepping stone, wastafel batu kali, dan kap lampu, serta koordinasi pengiriman.
- **Pain Points Saat Ini:** Tingginya volume batu kali masuk (ratusan biji/minggu) yang belum tercatat digital dan penanganan sisa potongan untuk cladding/urukan.
- **User Journey Map:**
  `Buka Modul Bahan Baku` $\rightarrow$ `Input Penerimaan Batu Kali (TRX-PA)` $\rightarrow$ `Terbitkan SPK Produksi Stepping/Wastafel` $\rightarrow$ `Cek Log Pemanfaatan Residu/Limbah`.

### Persona 3: Suparno (45 Tahun) — Mandor / Penanggung Jawab Produksi (UD Cahaya Onix)
- **Tingkat Literasi Digital:** Rendah-Menengah (akses smartphone di lantai bengkel).
- **Peran & Tanggung Jawab:** Memotong blok di mesin slep, mendistribusikan pembubutan wastafel ke operator mesin, melakukan QC tahap 1 (bentuk mentah).
- **Pain Points Saat Ini:** Rincian spesifikasi pesanan wastafel sering tidak terpampang jelas di dekat mesin bubut; penanganan retak serat batu terlambat.
- **User Journey Map:**
  `Buka Tab Produksi & SPK` $\rightarrow$ `Lihat Kanban Board Pengerjaan` $\rightarrow$ `Update Status Mesin (Slep $\rightarrow$ Bubut)` $\rightarrow$ `Input Hasil QC 1 (Lolos / Tambal Resin)`.

### Persona 4: Misno & Suyanto (40 & 36 Tahun) — Tim Produksi & Distribusi (UD Putra Abadi)
- **Tingkat Literasi Digital:** Rendah-Menengah (smartphone Android).
- **Peran & Tanggung Jawab:** Pengerjaan gerinda halus stepping stone, poles wastafel, verifikasi checklist packing krat kayu, dan pencatatan nomor resi/armada truk pengiriman.
- **Pain Points Saat Ini:** Risiko klaim barang pecah saat pengiriman logistik jarak jauh; perlunya bukti verifikasi packing kayu sebelum muat ke truk.
- **User Journey Map:**
  `Buka Modul Distribusi` $\rightarrow$ `Pilih SPK Selesai` $\rightarrow$ `Centang Checklist Verifikasi Packing Kayu` $\rightarrow$ `Input Nomor Plat Truk & Supir` $\rightarrow$ `Cetak Surat Jalan`.

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
│   └── Pengadaan / Purchase Order (PO ke Pemasok Tambang Campurdarat)
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
| **1** | Membaca status stok kritis marmer/onyx dari dashboard utama | M. Ilham Nur Amali (Owner CO) | Menemukan item kritis dalam $< 5$ detik | Alert banner merah & indikator langsung terbaca | **Berhasil (PASS)** (3.2 detik) |
| **2** | Menavigasikan diagram alur rantai pasok 8 tahap | Efri Saputra (Owner PA) | Mengklik tahap "Gudang Batu" dan membuka tabel bahan | Alur navigasi interaktif berjalan mulus | **Berhasil (PASS)** (2.5 detik) |
| **3** | Melihat status pengerjaan SPK wastafel di mesin bubut | Suparno (Mandor / Produksi CO) | Mengidentifikasi kartu SPK di kolom Kanban "Mesin Bubut" | Kolom visual Kanban mempermudah pemantauan | **Berhasil (PASS)** (4.1 detik) |
| **4** | Memasukkan hasil inspeksi QC tahap 1 (lolos vs perlu tambal) | Petugas QC | Berhasil mengisi form QC dan membedakan unit lolos/rework | Form input sederhana dan tombol kontras | **Berhasil (PASS)** (15.0 detik) |
| **5** | Menjalankan simulasi peramalan kebutuhan bahan baku | Admin / Owner | Klik tombol hitung ramalan dan melihat grafik proyeksi | Muncul kurva ramalan dan nilai MAPE 6.42% & RMSE | **Berhasil (PASS)** (2.0 detik) |

---

---

## 7. Rincian Output Setiap Langkah Pelaksanaan (Kegiatan 6)

Berikut adalah rekapitulasi luaran (*deliverable*) konkret dari setiap tahapan langkah kerja pada Kegiatan 6:

| No | Tahapan Langkah Kerja | Deskripsi Pelaksanaan | Bentuk Luaran Nyata (Output Deliverable) | Status |
| :---: | :--- | :--- | :--- | :---: |
| **1** | **Langkah 1: Analisis User Persona & User Journey Map** | Mengidentifikasi profil 4 pengguna riil IKM (Owner/Manajer, Mandor Bubut, Logistik Distribusi, dan Pembeli Publik) beserta *pain points* dan alur interaksinya. | • Formulir 6.1: Profil 4 User Persona & User Journey Map<br>• Matriks kebutuhan fungsional per peran pengguna | **100% SELESAI** |
| **2** | **Langkah 2: Perancangan Arsitektur Informasi & Sitemap** | Menyusun struktur hierarki navigasi 6 modul utama operasional hulu-hilir, dashboard monitoring, serta etalase katalog publik. | • Diagram Information Architecture (IA) & Sitemap<br>• Skema *Role-Based Access Navigation* | **100% SELESAI** |
| **3** | **Langkah 3: Pembuatan Design System & Prototype Interaktif** | Mengembangkan sistem komponen visual bertema batuan marmer & onyx (palet warna slate/emerald/amber, tipografi, kartu KPI, kanban) dan prototipe web interaktif. | • UI Design System & Panduan Gaya ([`UI_GUIDE.md`](../../UI_GUIDE.md))<br>• Berkas Prototipe Interaktif [`public/index.html`](file:///d:/Project%20Coding/Web%20SCM/public/index.html) | **100% SELESAI** |
| **4** | **Langkah 4: Pelaksanaan Usability Testing Skenario Tugas** | Menguji kemudahan antarmuka pada 5 skenario tugas operasional utama bersama perwakilan pengrajin dan manajer IKM mitra di Campurdarat. | • Formulir 6.2: Matriks Hasil Usability Testing 5 Skenario<br>• Dokumen Excel [`Form_Kegiatan_6_Persona_dan_Usability_Testing.xlsx`](./Form_Kegiatan_6_Persona_dan_Usability_Testing.xlsx) | **100% SELESAI** |

---

## 8. Output Akhir Kegiatan 6 (Checklist)

- [x] **Dokumen User Persona & Journey Map Lengkap (Form 6.1):** 4 profil pengguna riil IKM mitra di Campurdarat.
- [x] **Information Architecture & Sitemap:** 6 modul utama dengan navigasi intuitif.
- [x] **Design System & Standar Warna:** Sesuai karakteristik lingkungan pengrajin marmer.
- [x] **Prototype Interaktif Siap Uji:** File [`public/index.html`](file:///d:/Project%20Coding/Web%20SCM/public/index.html) berbasis HTML5, Tailwind, Chart.js, dan Lucide Icons.
- [x] **Hasil Usability Testing Awal (Form 6.2):** 100% skenario tugas dasar berhasil diselesaikan dengan tingkat kelulusan *100% PASS*.

