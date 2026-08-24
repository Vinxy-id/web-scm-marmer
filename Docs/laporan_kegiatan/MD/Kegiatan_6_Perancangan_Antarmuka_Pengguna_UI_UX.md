# DOKUMEN OUTPUT KEGIATAN 6
## Perancangan Antarmuka Pengguna (UI/UX)
**Proyek:** Rancang Bangun Sistem Informasi E-Supply Chain Terintegrasi untuk Akselerasi Hilirisasi Klaster IKM Marmer di Kabupaten Tulungagung  
**Mitra Studi Kasus:** UD Cahaya Onix & UD Putra Abadi (Kabupaten Tulungagung)  
**Metodologi SDLC:** Tahap Desain Antarmuka (*Interface Design & User Experience Prototyping*)

---

## A. Tujuan Kegiatan
Merancang antarmuka sistem yang mudah digunakan oleh pelaku IKM dengan tingkat literasi digital yang bervariasi, berdasarkan kebutuhan yang sudah digali di Kegiatan 3, sehingga sistem yang dibangun nantinya benar-benar dipakai (adopsi tinggi), bukan sekadar "ada tapi tidak dipakai".

---

## B. Keterkaitan dengan Tahapan Pengembangan Sistem Informasi
Tahap **Desain Antarmuka (*Interface Design*)** — bagian dari *System Design*, berjalan paralel dengan desain database (Kegiatan 4) dan menjadi acuan visual bagi tim pengkodean front-end di Kegiatan 7.

---

## C. Langkah-Langkah Detail Pelaksanaan

### Langkah 1 — Menyusun User Persona dan User Journey Map
* **Yang dilakukan:** berdasarkan hasil FGD (Kegiatan 3), buat 4 persona pengguna representatif (Pak Subani/Pak Joko pemilik IKM, Efri petugas gudang, Suparno mandor bengkel, Misno supir distribusi) beserta perjalanan pengguna saat berinteraksi dengan sistem (Form 6.1 & Form 6.3).
* **Yang harus disiapkan:** hasil FGD/observasi Kegiatan 3.
* **Output:** dokumen user persona dan journey map 5 fase interaksi.

### Langkah 2 — Penyusunan Information Architecture / Site Map
* **Yang dilakukan:** petakan struktur menu dan halaman sistem (dashboard, modul stok bahan baku, modul produksi kanban, modul QC & limbah, modul distribusi, etalase publik, dan peramalan AI).
* **Yang harus disiapkan:** daftar kebutuhan fungsional dari Kegiatan 3.
* **Output:** site map/struktur navigasi sistem (`IMG-6.1-Sitemap.png`).

### Langkah 3 — Pembuatan Wireframe Low-Fidelity
* **Yang dilakukan:** sketsa kasar tiap halaman (bisa di kertas/Figma) untuk menentukan tata letak elemen tanpa detail visual, fokus pada kemudahan input data lapangan.
* **Yang harus disiapkan:** site map dari langkah 2, tools (Figma/kertas+pensil).
* **Output:** kumpulan wireframe seluruh halaman utama.

### Langkah 4 — Pembuatan Mockup High-Fidelity (UI Design)
* **Yang dilakukan:** desain visual lengkap (warna Deep Navy `#1E3A8A`, tipografi Segoe UI, ikon Lucide) di Figma/web, dengan memperhatikan prinsip aksesibilitas (kontras warna rasio WCAG AAA $> 7:1$, ukuran teks terbaca $\ge 14	ext{px}$) dan desain mobile-first karena mayoritas pengguna IKM mengakses via smartphone.
* **Yang harus disiapkan:** wireframe dari langkah 3, referensi brand/identitas IKM.
* **Output:** mockup UI high-fidelity seluruh halaman (`IMG-6.3`, `IMG-6.4`, `IMG-6.5`).

### Langkah 5 — Pembuatan Prototype Interaktif
* **Yang dilakukan:** hubungkan antar-mockup di Figma/Blade template menjadi prototype yang bisa diklik (clickable prototype) sehingga bisa disimulasikan seperti aplikasi nyata.
* **Yang harus disiapkan:** mockup dari langkah 4.
* **Output:** link prototype interaktif / UI terintegrasi.

### Langkah 6 — Usability Testing Awal
* **Yang dilakukan:** minta 4-6 calon pengguna (perwakilan IKM) mencoba prototype untuk menyelesaikan tugas tertentu (input bahan baku, geser kartu Kanban, verifikasi QC, checkout DP 50% QRIS), amati kesulitan yang dialami.
* **Yang harus disiapkan:** prototype dari langkah 5, daftar tugas uji, form pencatatan hasil (Form 6.2).
* **Output:** hasil usability testing (catatan kesulitan, waktu penyelesaian tugas rata-rata $< 45	ext{ detik}$, completion rate 100%).

### Langkah 7 — Revisi Desain Berdasarkan Feedback
* **Yang dilakukan:** perbaiki elemen UI/UX yang menyulitkan pengguna berdasarkan temuan usability testing (misal: penyederhanaan input angka ribuan, pembesaran tombol aksi tap target $\ge 44	ext{px}$), dan dokumentasikan pada matriks perbaikan desain (Form 6.4).
* **Yang harus disiapkan:** hasil usability testing dari langkah 6.
* **Output:** desain UI/UX final, siap menjadi acuan pengkodean front-end di Kegiatan 7.

---

## D. Form/Template Pendukung

### 1. Formulir 6.1: Profil User Persona Pengguna Sistem E-SCM Marmer
| Persona ID | Nama & Peran Pengguna | Usia & Perangkat Utama | Karakteristik & Literasi Digital | Kebutuhan Utama pada Sistem | Pain Point / Kendala Lapangan |
| :---: | :--- | :---: | :--- | :--- | :--- |
| **P-01** | **Pak Subani (Owner IKM)** | 48 Thn / Smartphone & Laptop | Menengah; terbiasa WhatsApp & M-Banking | Dashboard ringkasan stok, omzet penjualan, & AI peramalan | Sulit pantau stok saat di luar bengkel |
| **P-02** | **Efri (Petugas Gudang)** | 29 Thn / Smartphone Android | Menengah; aktif media sosial | Form input cepat penerimaan batu & alert minimum stok | Sering salah hitung tonase bongkahan batu |
| **P-03** | **Suparno (Mandor Produksi)**| 42 Thn / Tablet Bengkel | Rendah-Menengah; preferensi visual | Papan Kanban visual, status 7 mesin bubut, & SPK live | SPK kertas basah & hilang di lantai bengkel |
| **P-04** | **Misno (Supir Distribusi)** | 35 Thn / Smartphone Android | Menengah; navigasi Google Maps | Checklist packing kayu solid & Surat Jalan digital | Sering lupa cek sertifikasi peti kayu fragile |

### 2. Formulir 6.2: Rekapitulasi Hasil Pengujian Kegunaan (Usability Testing)
| ID Tugas | Skenario Tugas Pengujian | Sasaran Aktor | Target Waktu | Waktu Aktual | Completion Rate | Status Kelulusan |
| :---: | :--- | :--- | :---: | :---: | :---: | :---: |
| **UT-01** | Pencatatan penerimaan bongkahan marmer baru dari tambang | Petugas Gudang (Efri) | $< 60	ext{ s}$ | **38 detik** | **100%** | **PASS (Sangat Cepat)** |
| **UT-02** | Pemindahan kartu SPK dari 'Cutting' ke 'Polishing' di Kanban | Mandor (Suparno) | $< 30	ext{ s}$ | **18 detik** | **100%** | **PASS (Intuitif)** |
| **UT-03** | Pengisian formulir inspeksi QC2 lolos kilap dan log residu | Petugas QC (Suparno) | $< 45	ext{ s}$ | **28 detik** | **100%** | **PASS (Valid)** |
| **UT-04** | Verifikasi checklist packing kayu solid dan cetak Surat Jalan | Petugas Distribusi (Misno)| $< 45	ext{ s}$| **25 detik** | **100%** | **PASS (Lengkap)** |
| **UT-05** | Eksplorasi katalog, filter wastafel, & checkout DP 50% QRIS | Pembeli Publik | $< 90	ext{ s}$ | **52 detik** | **100%** | **PASS (Mulus)** |

### 3. Formulir 6.3: Matriks User Journey Map 5 Fase Pengguna
| Persona | Fase 1: Discovery | Fase 2: Input / Task | Fase 3: Processing | Fase 4: Verification | Fase 5: Post-Action |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Pak Subani (Owner)** | Buka dashboard web via smartphone | Membuka grafik peramalan ARIMA | Membandingkan tren konsumsi batu | Menyetujui SPK batch besar | Unduh laporan mingguan PDF |
| **Efri (Gudang)** | Terima notifikasi stok kritis | Buka form penerimaan batu bongkahan | Memasukkan tonase & grade batuan | Cek total kalkulasi otomatis | Cetak bukti mutasi masuk |
| **Suparno (Mandor)**| Buka tablet di area bengkel | Melihat antrean kartu SPK Kanban | Drag-and-drop kartu ke mesin poles | Mengisi durasi pengerjaan mesin | Kirim tiket inspeksi ke QC |
| **Misno (Supir)** | Terima notifikasi order siap kirim | Buka modul checklist packing | Memeriksa 3 syarat keamanan peti | Tanda tangan digital penerimaan | Buka navigasi rute Google Maps |

### 4. Formulir 6.4: Matriks Perbaikan Desain UI/UX Sebelum vs Sesudah Feedback
| Komponen UI yang Diuji | Masalah yang Ditemukan (Sebelum) | Masukan Responden Penguji | Perbaikan Desain (Sesudah) | Hasil Verifikasi Akhir |
| :--- | :--- | :--- | :--- | :---: |
| **Tombol Aksi Kanban** | Ukuran tombol aksi terlalu kecil ($32	ext{px}$), sulit disentuh jari berdebu | Mandor minta tombol lebih besar dan berwarna tegas | Membesarkan target sentuh menjadi $\ge 48	ext{px}$ dengan warna kontras | **Sangat Nyaman Disentuh** |
| **Input Nominal Uang** | Pengguna harus mengetik angka manual tanpa pemisah ribuan | Petugas sering keliru jumlah angka nol | Menambahkan auto-formatting titik ribuan (`Rp 1.500.000`) | **Nol Kesalahan Input** |
| **Modal Konfirmasi QC** | Tidak ada peringatan saat menandai produk sebagai limbah/scrap | Petugas khawatir salah tekan tombol scrap | Menambahkan dialog modal konfirmasi bermotif oranye | **Mencegah Salah Klik** |
| **Checklist Packing Peti**| Teks checklist terlalu rapat pada layar HP 5.5 inci | Supir meminta checklist berbentuk saklar toggle besar | Mengubah checkbox standar menjadi card toggle switch responsif | **Cepat & Jelas** |

### 5. Tangkapan Layar Bukti Desain UI/UX
![Gambar 6.1: Information Architecture & Sitemap Navigasi Sistem E-SCM](IMG-6.1-Sitemap.png)
![Gambar 6.3: Mockup Antarmuka Publik - Landing Page & Katalog Produk](IMG-6.3-Mockup-Public.png)
![Gambar 6.4: Mockup Antarmuka Checkout & Pembayaran Digital DP 50% QRIS](IMG-6.4-Mockup-Checkout.png)
![Gambar 6.5: Mockup Antarmuka Backoffice - Kanban SPK & Manajemen Stok](IMG-6.5-Mockup-Admin.png)

---

## E. Output Akhir Kegiatan
- [x] **Dokumen User Persona & Journey Map:** 4 profil pengguna dan pemetaan 5 fase interaksi teridentifikasi di Form 6.1 & Form 6.3.
- [x] **Sitemap & Struktur Navigasi:** Arsitektur hierarki menu responsif terdokumentasi di `IMG-6.1-Sitemap.png`.
- [x] **Kumpulan Mockup High-Fidelity UI:** 24 artboard antarmuka publik dan backoffice berstandar WCAG AAA (`IMG-6.3`, `IMG-6.4`, `IMG-6.5`).
- [x] **Prototype Interaktif Teruji:** Alur kerja sistem terverifikasi dengan completion rate 100% pada pengujian kegunaan Form 6.2.
- [x] **Matriks Perbaikan Desain UI:** Catatan iterasi sebelum vs sesudah feedback terangkum di Form 6.4.
- [x] **Desain Sistem & Komponen UI:** Panduan token warna Deep Navy, tipografi Segoe UI, dan komponen form siap dikonversi ke Blade Tailwind CSS di Kegiatan 7.

---

## F. Tips & Best Practice
1. **Prioritaskan Desain Mobile-First:** Mayoritas pengrajin dan mandor berinteraksi menggunakan smartphone di area produksi yang berdebu.
2. **Gunakan Elemen Visual Berkontras Tinggi:** Tombol aksi utama harus memiliki ukuran minimal $44	ext{px} 	imes 44	ext{px}$ agar mudah disentuh tangan pekerja lapangan.
3. **Sediakan Konfirmasi Aksi Krusial:** Gunakan modal konfirmasi saat membatalkan pesanan atau menghapus data stok untuk mencegah salah pencet.
4. **Pertahankan Konsistensi Bahasa:** Gunakan istilah lokal yang dipahami pengrajin marmer Tulungagung (misal: "Bongkahan", "Tatal Marmer", "Peti Kayu", "Surat Jalan").
