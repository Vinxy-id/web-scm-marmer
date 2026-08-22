# ROADMAP & TASK TRACKING (TASKS.md)
## Rencana Kerja & Pelaksanaan Penelitian E-SCM IKM Marmer

Dokumen ini memetakan 14 tahapan kegiatan penelitian SDLC ke dalam *sprint breakdown*, estimasi durasi, *deliverable*, serta status pengerjaan proyek.

---

## 📊 Ringkasan Progress Keseluruhan

```
[====================>--------------------] 50% Selesai (Analisis & Desain Lengkap)
```

- **Jalur Kritis (Critical Path):** `Kegiatan 1 -> 2 -> 3 -> 4 -> 7 -> 8 -> 9 -> 10 -> 11 -> 12`
- **Tugas Paralel:** Kegiatan 5 (Algoritma Forecasting), Kegiatan 13 (Persiapan Dokumen HKI), Kegiatan 14 (Draft Jurnal Sinta 2).

---

## 🗓️ Breakdown Sprint & Tahapan Kerja

### Sprint 1: Analisis Masalah, Pemetaan Proses & Kebutuhan Sistem (Bulan 1)
Fokus: Pengumpulan data empiris lapangan dan perumusan kebutuhan fungsional.

- [x] **Kegiatan 1: Identifikasi Gap Informasi & Pemetaan Pemborosan**
  - [x] Studi literatur dan kompilasi data sekunder klaster IKM Tulungagung.
  - [x] Observasi & wawancara lapangan (UD Cahaya Onix & UD Putra Abadi).
  - [x] Penyusunan tabel 7 Lean Waste & identifikasi Information Gap.
  - [x] Penyusunan VSM Current State (PCE UD Cahaya Onix = 64,58%; Waste handling UD Putra Abadi = 390 mnt/mgg).
- [x] **Kegiatan 2: Pemetaan Proses Bisnis Hulu ke Hilir (BPMN)**
  - [x] Identifikasi aktor rantai pasok (Penambang $\rightarrow$ Gudang $\rightarrow$ Produksi $\rightarrow$ QC $\rightarrow$ Distribusi $\rightarrow$ Pelanggan).
  - [x] Pemodelan diagram BPMN As-Is (kondisi manual eksisting).
  - [x] Pemodelan diagram BPMN To-Be (usulan integrasi sistem digital).
- [x] **Kegiatan 3: Observasi Lapangan & FGD Kebutuhan Sistem**
  - [x] Pelaksanaan FGD bersama pengrajin dan perwakilan klaster.
  - [x] Transkrip analisis tematik kebutuhan pengguna.
  - [x] Klasifikasi kebutuhan sistem metode MoSCoW (Must, Should, Could, Won't have).

---

### Sprint 2: Perancangan Arsitektur Basis Data, UI/UX & Algoritma (Bulan 2)
Fokus: Desain teknis detail sebelum pengkodean dimulai.

- [x] **Kegiatan 4: Desain Arsitektur Basis Data Relasional & Skema Integrasi**
  - [x] Perancangan Entity Relationship Diagram (ERD) konseptual & logikal (Mermaid & relational mapping).
  - [x] Normalisasi tabel database (1NF $\rightarrow$ 2NF $\rightarrow$ 3NF).
  - [x] Penyusunan Kamus Data (*Data Dictionary*) lengkap 11 tabel inti (Form 4.1).
  - [x] Desain skema integrasi data antarmodul (Form 4.2: Stok, SPK, QC, Forecasting, Distribusi).
  - [x] Pembuatan DDL basis data fisik & seed data riil ([`database/schema.sql`](file:///d:/Project%20Coding/Web%20SCM/database/schema.sql)).
  - [x] Penyusunan dokumen laporan output ([`Docs/Kegiatan_4_Desain_Basis_Data_dan_Integrasi.md`](file:///d:/Project%20Coding/Web%20SCM/Docs/Kegiatan_4_Desain_Basis_Data_dan_Integrasi.md)).
- [ ] **Kegiatan 5: Pengembangan Logika Algoritma Forecasting**
  - [x] Pengumpulan & pembersihan dataset deret waktu permintaan/produksi (12-24 bulan).
  - [ ] Pembuatan prototipe script Python (Moving Average, Holt-Winters, ARIMA).
  - [ ] Evaluasi metrik akurasi (Target MAPE $< 10\%$, evaluasi RMSE).
  - [ ] Pembuatan REST API endpoint wrapper (FastAPI / Flask).
- [x] **Kegiatan 6: Perancangan Antarmuka Pengguna (UI/UX)**
  - [x] Penyusunan User Persona & User Journey Map (Pak Joko, Mas Budi, Pak Slamet, Mbak Rini) (Form 6.1).
  - [x] Perancangan Information Architecture & Site Map 6 modul terintegrasi.
  - [x] Pembuatan High-Fidelity UI Design System & Prototype Interaktif ([`public/index.html`](file:///d:/Project%20Coding/Web%20SCM/public/index.html)).
  - [x] Pelaksanaan Usability Testing Awal 5 skenario tugas operasional (Form 6.2).
  - [x] Penyusunan dokumen laporan output ([`Docs/laporan_kegiatan/Kegiatan_6_Perancangan_Antarmuka_Pengguna_UI_UX.md`](file:///d:/Project%20Coding/Web%20SCM/Docs/laporan_kegiatan/Kegiatan_6_Perancangan_Antarmuka_Pengguna_UI_UX.md)).

---

### Sprint 3: Pengkodean Sistem & Integrasi Cloud (Bulan 3)
Fokus: Coding modul inti aplikasi web dan deployment server.

- [ ] **Kegiatan 7: Pengkodean Modul Inti (Stok, Produksi, Distribusi, RBAC)**
  - [ ] Inisialisasi struktur proyek Laravel 11+ MVC (Versi Terbaru) & migrasi database.
  - [ ] Implementasi autentikasi & RBAC (Role-Based Access Control).
  - [ ] Implementasi Modul Manajemen Stok Bahan Baku (Opening, In, Out, Consign, Alert Min Stock).
  - [ ] Implementasi Modul SPK Produksi & Tracking Tahapan (Slep, Bubut, Poles).
  - [ ] Implementasi Modul QC 2-Tahap & Pencatatan Limbah/Sisa Potongan.
  - [ ] Implementasi Modul Distribusi & Checklist Packing.
  - [ ] Pembuatan Halaman Dashboard Monitoring Interaktif (KPI Card, Pie, Column, Line Chart).
- [ ] **Kegiatan 8: Integrasi Algoritma ke Sistem & Deployment ke Cloud Server**
  - [ ] Integrasi REST API forecasting Python dengan backend Laravel.
  - [ ] Setup cloud server / VPS (Ubuntu, Nginx, PHP-FPM, MySQL, SSL LetsEncrypt).
  - [ ] Konfigurasi cron job backup database otomatis.
  - [ ] Uji coba akses multi-device (Mobile Android & Desktop).

---

### Sprint 4: Pengujian, Validasi Pakar & Evaluasi Lapangan (Bulan 4)
Fokus: Memastikan keandalan sistem dan pengujian kelayakan.

- [ ] **Kegiatan 9: Uji Fungsionalitas (Black-Box Testing)**
  - [ ] Eksekusi Test Case (Equivalence Partitioning & Boundary Value Analysis).
  - [ ] Pencatatan log bug & siklus *regression testing*.
  - [ ] Penyusunan Test Summary Report.
- [ ] **Kegiatan 10: Validasi Pakar (Sistem & Industri) serta Revisi**
  - [ ] Demo sistem ke Pakar Sistem Informasi (standar ISO/IEC 25010).
  - [ ] Demo sistem ke Pakar Rantai Pasok / Perwakilan Klaster IKM Marmer.
  - [ ] Rekapitulasi skor validasi & perbaikan sistem (*feedback implementation*).
- [ ] **Kegiatan 11: Analisis KPI (Efisiensi Konvensional vs Digital)**
  - [ ] Uji coba operasional sistem di IKM mitra (durasi 2–4 minggu).
  - [ ] Pengukuran ulang parameter KPI (Lead time, akurasi stok, defect rate, waktu pencarian data).
  - [ ] Uji statistik komparasi (Paired sample t-test / Wilcoxon).

---

### Sprint 5: Diseminasi, Luaran Wajib & Finalisasi (Bulan 5)
Fokus: Penyusunan laporan, pendaftaran hak cipta, dan publikasi jurnal.

- [ ] **Kegiatan 12: Evaluasi Akhir & Penyusunan Laporan Penelitian**
  - [ ] Kompilasi seluruh data luaran Kegiatan 1–11 ke format laporan akhir.
  - [ ] Proofreading dan review laporan bersama tim pembimbing.
- [ ] **Kegiatan 13: Pendaftaran Hak Kekayaan Intelektual (HKI) Software**
  - [ ] Penyiapan dokumen pendukung (listing source code, buku panduan, tangkapan layar).
  - [ ] Submit pendaftaran Hak Cipta Program Komputer ke portal DJKI.
- [ ] **Kegiatan 14: Submit & Publikasi Artikel Ilmiah Jurnal Sinta 2**
  - [ ] Penulisan naskah artikel ilmiah berbasis data efisiensi KPI & implementasi E-SCM.
  - [ ] Cek kemiripan Turnitin ($< 20\%$) dan proofreading.
  - [ ] Submit naskah via OJS jurnal target dan pengawalan proses *peer-review* hingga Letter of Acceptance (LoA).
