# DOKUMEN OUTPUT KEGIATAN 9
## Uji Fungsionalitas Sistem (Black-Box Testing)
**Proyek:** Rancang Bangun Sistem Informasi E-Supply Chain Terintegrasi untuk Akselerasi Hilirisasi Klaster IKM Marmer di Kabupaten Tulungagung  
**Mitra Studi Kasus:** UD Cahaya Onix & UD Putra Abadi (Kabupaten Tulungagung)  
**Metodologi SDLC:** Tahap Pengujian Perangkat Lunak (*Software Verification & Quality Assurance*)

---

## 1. Tujuan Kegiatan
Kegiatan ini bertujuan untuk:
1. Memverifikasi seluruh fungsionalitas fitur sistem E-SCM marmer dari sudut pandang pengguna luar (*Black-Box Testing*) tanpa mengacu pada struktur kode internal.
2. Menguji ketahanan validasi logika bisnis (*business rules*) operasional hulu–hilir IKM marmer (manajemen bahan baku, alur SPK pengerjaan bertahap, kendali mutu QC 2-tahap, pencatatan limbah/residu, pengiriman surat jalan, dan peramalan kebutuhan bahan).
3. Mendokumentasikan seluruh siklus identifikasi masalah (*bug finding*), tindakan perbaikan kode (*bug fixing*), dan pengujian regresi (*regression testing*) untuk memastikan stabilitas sistem 100% lulus (*Zero Defect Delivery*).

---

## 2. Metodologi Pengujian Black-Box

Pengujian fungsionalitas dieksekusi secara otomatis (*automated testing*) menggunakan *Laravel Test Suite / PHPUnit Framework* dengan menerapkan 3 teknik formal pengujian perangkat lunak:

1. **Equivalence Partitioning (EP):** Membagi himpunan data masukan ke dalam kelas valid dan invalid. Contoh: Pengujian form autentikasi kredensial benar vs salah, penambahan master bahan baku baru, dan penerbitan Surat Jalan.
2. **Boundary Value Analysis (BVA):** Menguji kondisi batas ekstrem dan nilai di luar batas toleransi sistem. Contoh: Transaksi mutasi stok keluar melebihi stok fisik riil, kuantitas bernilai negatif, serta penetapan tanggal tenggat SPK yang mendahului tanggal mulai produksi.
3. **Decision Table Testing:** Menguji kombinasi logika multi-kondisi pada alur pemeriksaan kualitas. Contoh: Verifikasi kesesuaian unit pada inspeksi QC (aturan wajib: $\text{Pass} + \text{Rework} + \text{Scrap} = \text{Inspected Quantity}$).

Pengujian dijalankan dengan memanfaatkan trait `DatabaseTransactions` sehingga seluruh mutasi data pengujian otomatis di-*rollback* tanpa mengotori integritas data utama pada basis data produksi `db_escm_marmer`.

---

## 3. Rangkuman Siklus Pengujian & Tindakan Korektif (4 Iterasi)

Berdasarkan pengujian berulang pada berkas [`TEST_REPORT.md`](../../TEST_REPORT.md), proses penyempurnaan sistem melalui 4 siklus iterasi:

* **Iterasi 1 (Baseline Fungsionalitas):** Ditemukan kegagalan pada modul pengiriman surat jalan karena belum tersedianya data master pelanggan (`customers`) pada seeder awal. *Tindakan Korektif:* Penambahan data pelanggan empiris pada `DatabaseSeeder.php`.
* **Iterasi 2 (Penyelarasan Alias Rute):** Ditemukan *RouteNotFoundException* karena perbedaan penamaan rute pengujian. *Tindakan Korektif:* Sinkronisasi alias rute `qc.inspect` dan `forecasting.calculate`.
* **Iterasi 3 (Celah Logika QC & Mutasi Stok Minus):** Ditemukan celah di mana form QC menerima data dengan total unit tidak sinkron, dan pengeluaran bahan baku melebihi stok memicu *unhandled exception*. *Tindakan Korektif:* Penambahan validasi kustom `Validator::after()` pada `QcController.php` dan pemeriksaan stok awal pada `MaterialController.php`.
* **Iterasi 4 (Final Verification):** Seluruh 20 skenario (12 Black-Box Test Cases + 8 E2E Button Tests) berhasil dieksekusi dengan tingkat kelulusan 100% (*Pass Rate: 100%*).

---

## 4. Form 9.1: Matriks 12 Test Case Black-Box Komprehensif

| No | ID Uji | Modul Sistem | Skenario Pengujian | Teknik Uji | Data Masukan (Input) | Hasil yang Diharapkan | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :---: |
| **1** | **TC-AUTH-01** | Autentikasi | Login dengan kredensial valid | EP | `email: owner@cahayaonix.com`, `password: role123` | Login berhasil, redirect ke Dashboard | **PASS** |
| **2** | **TC-AUTH-02** | Autentikasi | Login dengan password salah | EP | `email: owner@cahayaonix.com`, `password: wrong123` | Login ditolak, pesan error muncul | **PASS** |
| **3** | **TC-STK-01** | Bahan Baku | Input material baru data valid | EP | `code: MAT-TEST-01`, `qty: 20`, `cost: 250000` | Data tersimpan, log mutasi tercatat | **PASS** |
| **4** | **TC-STK-02** | Bahan Baku | Mutasi keluar melebihi stok fisik | BVA | `material_id: 5`, `type: out`, `qty: > stock` | Transaksi ditolak dengan error stok kurang | **PASS** |
| **5** | **TC-STK-03** | Bahan Baku | Input kuantitas mutasi negatif | BVA | `quantity: -5`, `type: in` | Form menolak dengan error validasi | **PASS** |
| **6** | **TC-PRD-01** | SPK Produksi | Penerbitan SPK baru data valid | EP | `product_id: 4`, `target: 10`, `priority: high` | SPK terbit & 3 tahapan otomatis terbuat | **PASS** |
| **7** | **TC-PRD-02** | SPK Produksi | Tanggal tenggat mendahului mulai | BVA | `start: 2026-08-25`, `due: 2026-08-20` | Form menolak dengan error tanggal | **PASS** |
| **8** | **TC-QC-01** | Quality Control | Input inspeksi QC2 Final Polish | Decision Table | `stage: qc2_final_polish`, `inspected: 10`, `pass: 10` | Log QC tersimpan, stok barang jadi bertambah | **PASS** |
| **9** | **TC-QC-02** | Quality Control | Total unit inspeksi tidak sinkron | Decision Table | `inspected: 10`, `pass: 8`, `rework: 5`, `scrap: 2` | Form menolak dengan error diskrepansi unit | **PASS** |
| **10** | **TC-DST-01** | Distribusi | Penerbitan Surat Jalan pengiriman | EP | `customer_id: 1`, `expedition: Express`, `date: Valid` | Surat Jalan terbit status awal `packed` | **PASS** |
| **11** | **TC-DST-02** | Distribusi | Pembaruan status pengiriman | EP | `shipment_id: 1`, `status: delivered` | Status pengiriman terupdate `delivered` | **PASS** |
| **12** | **TC-FOR-01** | AI Forecasting | Peramalan algoritma Holt-Winters | EP | `target: product`, `id: 4`, `horizon: 3` | Proyeksi 3 bulan terhitung & tersimpan | **PASS** |

---

## 5. Form 9.2: Matriks Pengujian End-to-End Tombol & Alur Kerja (E2E Button Tests)

| No | Skenario Interaksi Tombol | Komponen / Halaman | Aksi Pengujian | Hasil Respon Sistem | Status |
| :--- | :--- | :--- | :--- | :--- | :---: |
| **1** | Akses Halaman Login Tamu | `resources/views/auth/login.blade.php` | Kunjungi route `/login` | Form login dan token CSRF tampil lengkap | **PASS** |
| **2** | Akses Seluruh Modul Pengguna Terotentikasi | Seluruh Tampilan Blade | Navigasi menu dashboard, stok, SPK, QC, distribusi | Seluruh layar merespons dengan HTTP Status 200 OK | **PASS** |
| **3** | Tombol Simpan Bahan & Mutasi Cepat | Modul Master Bahan Baku | Klik tombol simpan modal transaksi | Data tabel otomatis ter-refresh dengan stok baru | **PASS** |
| **4** | Tombol Progresi Kartu Kanban SPK | Modul Kanban Produksi | Pindahkan kartu antar kolom pengerjaan | Status tahapan terupdate *in_progress* ke *qc_phase* | **PASS** |
| **5** | Tombol Simpan Form Inspeksi QC | Modul Pengendalian Kualitas | Submit form inspeksi QC Tahap 1 & 2 | Validasi unit lolos, modal tertutup dengan notifikasi | **PASS** |
| **6** | Tombol Catat Residu / Limbah | Modul Manajemen Residu | Submit catatan sisa potongan batuan | Residu tercatat dengan volume $m^3$ terestimasi | **PASS** |
| **7** | Tombol Checklist Packing & Cetak Surat Jalan | Modul Distribusi & Logistik | Centang verifikasi packing kayu dan simpan | Surat jalan siap cetak dengan nomor resmi terbit | **PASS** |
| **8** | Tombol Hitung Ulang Peramalan AI | Modul Peramalan Permintaan | Klik tombol "Hitung Proyeksi" | Kurva grafik Chart.js terupdate dalam $< 2.5$ detik | **PASS** |

---

## 6. Output Akhir & Deliverable Kegiatan 9 (Checklist)

- [x] **Test Suite Otomatis Black-Box:** Berkas `tests/Feature/BlackBoxTestSuiteTest.php` (12 Test Cases, 34 Assertions Passed).
- [x] **Test Suite Otomatis E2E Buttons:** Berkas `tests/Feature/E2EButtonFunctionalityTest.php` (8 Test Cases, 37 Assertions Passed).
- [x] **Dokumentasi 4 Siklus Iterasi & Perbaikan Bug:** Terdokumentasi lengkap di Form 9.1 & Form 9.2 serta berkas `TEST_REPORT.md`.
- [x] **Tingkat Kelulusan Pengujian 100% PASS:** Bebas dari *fatal error*, *unhandled exception*, dan celah logika bisnis.
- [x] **Integritas Basis Data Terjaga:** Seluruh eksekusi unit test terisolasi menggunakan transaksi rollback database.
