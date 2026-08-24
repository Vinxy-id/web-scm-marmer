# DOKUMEN OUTPUT KEGIATAN 9
## Uji Fungsionalitas (Black-Box Testing)
**Proyek:** Rancang Bangun Sistem Informasi E-Supply Chain Terintegrasi untuk Akselerasi Hilirisasi Klaster IKM Marmer di Kabupaten Tulungagung  
**Mitra Studi Kasus:** UD Cahaya Onix & UD Putra Abadi (Kabupaten Tulungagung)  
**Metodologi SDLC:** Tahap Pengujian Sistem (*System Testing & Quality Assurance*)

---

## A. Tujuan Kegiatan
Memastikan seluruh fitur sistem berfungsi sesuai kebutuhan yang telah ditentukan (Kegiatan 3), dengan menguji sistem dari sudut pandang pengguna akhir (tanpa melihat kode sumber), agar bug atau ketidaksesuaian fitur dapat ditemukan dan diperbaiki sebelum sistem divalidasi pakar (Kegiatan 10).

---

## B. Keterkaitan dengan Tahapan Pengembangan Sistem Informasi
Tahap **Pengujian Sistem (*System Testing*)** — bagian dari *Testing* dalam SDLC, khususnya pengujian *black-box* yang berfokus pada kesesuaian fungsi dengan spesifikasi kebutuhan, bukan pada struktur kode.

---

## C. Langkah-Langkah Detail Pelaksanaan

### Langkah 1 — Penyusunan Test Plan dan Test Case
* **Yang dilakukan:** buat test case untuk setiap fitur berdasarkan dokumen kebutuhan (Kegiatan 3) — setiap fitur minimal punya 1 skenario uji normal dan 1 skenario uji kondisi khusus/error (Form 9.1).
* **Yang harus disiapkan:** dokumen kebutuhan sistem (SRS), daftar fitur sistem yang sudah jadi.
* **Output:** dokumen test plan dan 16 skenario test case black-box lengkap.

### Langkah 2 — Penentuan Teknik Testing
* **Yang dilakukan:** tentukan teknik yang dipakai sesuai jenis fitur — *equivalence partitioning* (mengelompokkan input valid/tidak valid), *boundary value analysis* (menguji nilai batas integer stok), atau *decision table* (untuk logika kombinasi status SPK dan pembayaran) (Form 9.3).
* **Yang harus disiapkan:** daftar fitur dan kompleksitas logikanya.
* **Output:** rancangan pemetaan teknik pengujian per fitur.

### Langkah 3 — Penyiapan Data Uji
* **Yang dilakukan:** siapkan data dummy yang realistis (nama produk wastafel marmer, jumlah stok fisik, data pelanggan fiktif) untuk dipakai saat eksekusi test case.
* **Yang harus disiapkan:** format data sesuai skema database (Kegiatan 4).
* **Output:** dataset uji siap pakai (seeder empiris IKM Tulungagung).

### Langkah 4 — Eksekusi Test Case
* **Yang dilakukan:** jalankan setiap test case satu per satu, catat hasil aktual dan bandingkan dengan hasil yang diharapkan (*expected result*) (`IMG-9.1-PHPUnit-Terminal.png` s.d. `IMG-9.4-Test-RateLimiter.png`).
* **Yang harus disiapkan:** test case dari langkah 1, data uji dari langkah 3, akses ke sistem live.
* **Output:** hasil eksekusi tiap test case (100% PASS).

### Langkah 5 — Pencatatan Bug/Defect
* **Yang dilakukan:** untuk setiap test case yang gagal/menemukan celah anomali, catat detail bug (deskripsi, tingkat keparahan/*severity*, modul terkait, screenshot/bukti) pada bug report log (Form 9.2).
* **Yang harus disiapkan:** hasil eksekusi langkah 4.
* **Output:** bug report log (8 security & edge test cases).

### Langkah 6 — Retest Setelah Perbaikan (Regression Testing)
* **Yang dilakukan:** setelah developer memperbaiki bug, uji ulang test case yang sebelumnya gagal, dan pastikan perbaikan tidak menimbulkan bug baru di fitur lain.
* **Yang harus disiapkan:** bug report yang sudah ditandai "selesai diperbaiki" oleh developer.
* **Output:** konfirmasi bug teratasi, hasil regression test 100% bersih.

### Langkah 7 — Penyusunan Laporan Hasil Testing
* **Yang dilakukan:** kompilasi seluruh hasil pengujian menjadi laporan ringkas (jumlah 72 test cases, 376 assertions, persentase pass rate 100%, 0 critical bug) dan evaluasi kesiapan rilis (Form 9.4).
* **Yang harus disiapkan:** seluruh hasil dari langkah 1-6.
* **Output:** dokumen test summary report — menjadi bukti kelayakan fungsional sistem sebelum masuk ke validasi pakar (Kegiatan 10).

---

## D. Form/Template Pendukung

### 1. Formulir 9.1: Matriks 16 Kasus Uji Fungsionalitas (Black-Box Test Cases)
| ID Test Case | Modul & Fitur yang Diuji | Skenario Pengujian | Masukan Data (Input) | Hasil yang Diharapkan | Hasil Aktual | Status |
| :---: | :--- | :--- | :--- | :--- | :--- | :---: |
| **TC-001** | **Bahan Baku** | Input stok valid | Bongkahan Onix Grade A, `qty=150` | Data tersimpan, stok bertambah | Stok tercatat `150 kg` | **PASS** |
| **TC-002** | **Bahan Baku** | Input string invalid | Nama material, `qty='abc'` | Validasi error: *must be integer* | Error validasi muncul | **PASS** |
| **TC-003** | **Bahan Baku** | Input desimal invalid | Nama material, `qty=12.5` | Validasi error: *strict integer* | Error validasi muncul | **PASS** |
| **TC-004** | **Produksi SPK**| Terbitkan SPK baru | Target `50 pcs`, Material ID 1 | SPK status 'Draft' di antrean | SPK terbit di Kanban | **PASS** |
| **TC-005** | **Produksi SPK**| Tarik kartu Kanban | Geser kartu 'Cutting' ke 'Polishing' | Status SPK update realtime | Status terupdate live | **PASS** |
| **TC-006** | **Quality Control** | Inspeksi QC lolos | Input `pass=50`, `rework=0`, `scrap=0` | Status QC 'Passed', pemicu packing | Tiket QC 'Passed' | **PASS** |
| **TC-007** | **Quality Control** | Neraca massa invalid | Input `pass=40`, `rework=5`, `scrap=0` (tot 45/50) | Validasi error: *neraca kuantitas tidak klop* | Error validasi muncul | **PASS** |
| **TC-008** | **Residu Limbah** | Pencatatan residu | Tatal marmer `240 kg`, cluster Onix | Residu bertambah di gudang limbah | Residu tercatat valid | **PASS** |
| **TC-009** | **Distribusi** | Checklist peti kayu | Centang 3 syarat (*foam, peti, fragile*) | Tombol 'Terbitkan Surat Jalan' aktif | Surat Jalan terbit | **PASS** |
| **TC-010** | **Distribusi** | Checklist belum lengkap | Hanya centang 1 syarat keselamatan | Peringatan: *packing belum memenuhi standar* | Peringatan aktif | **PASS** |
| **TC-011** | **Katalog Publik**| Search produk | Kata kunci: "Wastafel Onix" | Tampil daftar wastafel onix relevan | 4 produk tampil tepat | **PASS** |
| **TC-012** | **Checkout DP** | Checkout DP 50% | Pilih DP 50%, upload bukti transfer | Invoice berstatus 'Pending Verification' | Invoice terbit valid | **PASS** |
| **TC-013** | **Checkout Lunas**| Checkout Lunas QRIS | Pilih Lunas 100%, scan QRIS | Pesanan terkonfirmasi otomatis | Pesanan terkonfirmasi | **PASS** |
| **TC-014** | **Admin Orders** | Verifikasi pembayaran | Klik 'Approve Pembayaran DP' | Status order 'Processing' -> SPK terbit | SPK terbit otomatis | **PASS** |
| **TC-015** | **AI Forecasting** | Eksekusi ARIMA | Horizon 3 bulan, Produk ID 1 | Grafik Chart.js tampil (MAPE 5.73%) | Proyeksi tampil tepat | **PASS** |
| **TC-016** | **Autentikasi** | Proteksi route admin | Akses `/admin` tanpa login | Redirect otomatis ke halaman login | Redirect 302 sukses | **PASS** |

### 2. Formulir 9.2: Hasil Pengujian Keamanan & Regression Testing (8 Skenario)
| ID Kasus | Kategori Pengujian | Skenario Keamanan & Batas | Metodologi Uji | Hasil Verifikasi Keamanan | Status |
| :---: | :--- | :--- | :--- | :--- | :---: |
| **SEC-01** | **Otentikasi & RBAC** | Supir akses menu Manajemen SPK Produksi | Direct URL Manipulation | **403 Forbidden Access Denied** | **PASS** |
| **SEC-02** | **Proteksi Form** | Submit form tanpa token CSRF valid | POST Request Tampering | **419 Page Expired (CSRF Rejected)** | **PASS** |
| **SEC-03** | **Anti Polusi Data** | Transaksi gagal saat SPK terbit (Simulasi DB Crash)| Mock Exception Trigger | **Database Rollback (Zero Pollution)** | **PASS** |
| **SEC-04** | **Keamanan Input** | Injeksi SQL `' OR '1'='1` pada filter katalog | SQL Injection Payload | **Sanitasi Parameter (Query Aman)** | **PASS** |
| **SEC-05** | **Keamanan XSS** | Input `<script>alert('xss')</script>` di ulasan | XSS Payload Testing | **Blade Escaped String (Aman)** | **PASS** |
| **SEC-06** | **Rate Limiter API**| Request 70 kali dalam 1 menit ke endpoint publik | DoS Flood Simulation | **429 Too Many Requests** | **PASS** |
| **SEC-07** | **Validasi File** | Upload file `.php` terselubung di bukti transfer | Malicious File Upload | **Validasi MIME Type (Reject 422)** | **PASS** |
| **SEC-08** | **Session Timeout** | Diamkan sesi login selama 120 menit | Inactivity Expiry Test | **Auto Logout & Session Invalidated** | **PASS** |

### 3. Formulir 9.3: Pemetaan Teknik Pengujian Black-Box per Modul Sistem
| Modul Sistem | Fitur yang Diuji | Teknik Pengujian yang Diterapkan | Kelas Partisi / Nilai Batas Uji | Justifikasi Pemilihan Teknik |
| :--- | :--- | :--- | :--- | :--- |
| **Bahan Baku** | Input kuantitas mutasi | **Boundary Value Analysis (BVA)** | Valid: `1` s.d. `99999`, Invalid: `0`, `-1`, `10.5` | Mencegah anomali stok negatif dan desimal |
| **Quality Control** | Neraca massa inspeksi | **Decision Table Testing** | `Pass + Rework + Scrap = Target SPK` | Memastikan validitas hukum kekekalan kuantitas fisik |
| **Checkout Pembayaran**| Opsi DP 50% vs Lunas | **Equivalence Partitioning (EP)** | Partisi Valid: [DP 50%, Full 100%], Invalid: [Lainnya] | Memastikan akurasi persentase tagihan invoice |
| **Autentikasi RBAC** | Hak akses menu admin | **State Transition Testing** | Guest $ightarrow$ Auth User $ightarrow$ Specific Role Action | Menguji transisi status otorisasi pengguna |

### 4. Formulir 9.4: Dokumen Executive Test Summary Report & Metrik Kelayakan Sistem
| Parameter Metrik Evaluasi | Target Metrik Kelulusan | Realisasi Hasil Pengujian | Status Kelayakan |
| :--- | :---: | :---: | :---: |
| **Total Test Case Fungsional** | $\ge 50$ Kasus Uji | **72 Test Cases (376 Assertions)** | **MEMENUHI TARGET** |
| **Tingkat Kelulusan (*Pass Rate*)**| $100\%$ | **100% (0 Failed Cases)** | **SEMPURNA** |
| **Jumlah Bug Kritis (*Blocker/Critical*)** | 0 Cacat Kritis | **0 Bug Kritis Ditemukan** | **LOLOS** |
| **Keamanan Proteksi (*Security & RBAC*)** | $100\%$ Lolos | **100% Lolos (8 Security Cases)** | **LOLOS** |
| **Waktu Respon Rata-rata (*Latency*)** | $< 1.5	ext{ detik}$ | **0.85 detik** | **SANGAT RESPONSIF** |
| **Kesimpulan Kelayakan Sistem** | Siap Uji Pakar | **SISTEM DINYATAKAN SIAP MASUK KEGIATAN 10 (VALIDASI PAKAR)** | **ACC / SIAP** |

### 5. Tangkapan Layar Bukti Pengujian Sistem (Black-Box Testing)
![Gambar 9.1: Eksekusi Otomatis PHPUnit Test Suite di Terminal](IMG-9.1-PHPUnit-Terminal.png)
![Gambar 9.2: Pengujian Validasi Error Otentikasi dan Form Input](IMG-9.2-Test-Auth-Validation.png)
![Gambar 9.3: Pengujian Database Rollback & Zero Pollution Test](IMG-9.3-Test-ZeroPollution.png)
![Gambar 9.4: Pengujian Keamanan HTTP Rate Limiter (429 Too Many Requests)](IMG-9.4-Test-RateLimiter.png)

---

## E. Output Akhir Kegiatan
- [x] **16 Skenario Black-Box Test Cases Lengkap:** Seluruh modul operasional hulu-hilir teruji di Form 9.1 dengan hasil 100% PASS.
- [x] **8 Pengujian Keamanan & Regression Testing:** Verifikasi RBAC, proteksi CSRF, Anti-Polusi DB, dan Rate Limiting teruji di Form 9.2.
- [x] **Tabel Pemetaan Teknik Black-Box:** Penerapan BVA, EP, Decision Table, dan State Transition terpetakan di Form 9.3.
- [x] **Executive Test Summary Report:** Rekapitulasi metrik pengujian dan rekomendasi kelayakan sistem terangkum di Form 9.4.
- [x] **Bukti Terminal PHPUnit Suite:** 72 automated feature tests dengan 376 assertions lulus tanpa anomali (`IMG-9.1`).
- [x] **Log Pengujian Bebas Defect:** Seluruh potensi error input desimal dan otorisasi telah teratasi tuntas.

---

## F. Tips & Best Practice
1. **Lakukan Pengujian Positif & Negatif Secara Seimbang:** Jangan hanya menguji alur sukses (*happy path*), tetapi prioritaskan uji nilai batas (*boundary*) dan input salah.
2. **Gunakan Automated Testing Suite (PHPUnit):** Menghemat waktu regresi saat ada penambahan fitur baru di masa depan.
3. **Simulasikan Skenario Lapangan Riil:** Uji pengisian form dengan kondisi koneksi lambat dan karakter input khusus.
4. **Dokumentasikan Seluruh Log Defect Secara Transparan:** Catat akar penyebab (*root cause*) dan solusi kode perbaikannya.
