# 📑 LAPORAN PENGUJIAN SISTEM & UJI KETAHANAN KEAMANAN (TEST REPORT)
## SISTEM E-SUPPLY CHAIN MANAGEMENT (E-SCM) & E-COMMERCE IKM MARMER & ONYX TULUNGAGUNG
**Versi Aplikasi:** 1.0.0 (Release Candidate)  
**Tanggal Pengujian:** 23 Agustus 2026  
**Testing Framework:** PHPUnit 10.5.64 / PHP 8.3.30 / Laravel 11.x  
**Database Engine:** MySQL 8.0 / InnoDB Engine  
**Domain Uji:** Klaster IKM Kerajinan Marmer, Onyx, dan Batu Kali Tulungagung (UD Cahaya Onix & UD Putra Abadi)  

---

## 1. RINGKASAN EKSEKUTIF PENGUJIAN (EXECUTIVE SUMMARY)

Seluruh modul aplikasi telah melalui pengujian otomatis (*Automated Feature & Unit Testing*), pengujian integrasi rantai pasok (*Supply Chain E2E Testing*), serta pengujian ketahanan keamanan siber dan serangan bot (*Cyber Security & Bot Stress Testing*).

### 📊 Metrik Hasil Eksekusi Uji:
```text
PHPUnit 10.5.64 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.3.30
Configuration: D:\Documents\Code\Work\web-scm-marmer\phpunit.xml

...................................                               35 / 35 (100%)

Time: 00:08.116, Memory: 44.00 MB

OK (35 tests, 170 assertions)
```

- **Total Skenario Pengujian:** 35 Test Cases
- **Total Asersi (*Assertions*):** 170 Assertions
- **Tingkat Kelulusan (*Pass Rate*):** **100.0% (35 Passed, 0 Failed, 0 Error, 0 Warning)**
- **Waktu Eksekusi (*Execution Time*):** 8.11 Detik
- **Status Akhir:** **READY FOR DEPLOYMENT / PRODUCTION (STABLE & SECURE)**

---

## 2. MATRIKS RINCIAN PENGUJIAN PER MODUL

### 📦 Modul 1: Autentikasi & Hak Akses Pengguna (`tests/Feature/E2EButtonFunctionalityTest.php`)
| ID Uji | Nama Skenario Uji | Tindakan / Input | Ekspektasi Sistem | Status |
| :--- | :--- | :--- | :--- | :---: |
| **AUTH-01** | Akses Form Login Tamu | `GET /login` | Mengembalikan HTTP 200 dengan form login interaktif. | **PASS** |
| **AUTH-02** | Proteksi Halaman Internal | `GET /dashboard`, `/orders`, `/materials`, `/production/kanban`, `/qc`, `/distribution`, `/forecasting`, `/reports` | Pengguna yang terautentikasi dapat mengakses seluruh modul tanpa error 403/500. | **PASS** |

---

### 🪨 Modul 2: Bahan Baku & Mutasi Stok Integer Strict (`tests/Feature/E2EButtonFunctionalityTest.php`)
| ID Uji | Nama Skenario Uji | Tindakan / Input | Ekspektasi Sistem | Status |
| :--- | :--- | :--- | :--- | :---: |
| **MAT-01** | Tambah Master Bahan Baku Baru | `POST /materials` dengan data kode material, nama, grade, dan unit | Record baru tersimpan di tabel `materials` dengan alert batas minimum. | **PASS** |
| **MAT-02** | Validasi Mutasi Stok Masuk | `POST /materials/{id}/transaction` tipe `MASUK` Qty 25 blok | Stok bertambah secara atomik, histori tercatat di `stock_transactions`. | **PASS** |
| **MAT-03** | Validasi Mutasi Stok Keluar | `POST /materials/{id}/transaction` tipe `KELUAR` Qty 10 blok | Stok berkurang secara atomik, histori tercatat di `stock_transactions`. | **PASS** |
| **MAT-04** | Validasi Angka Bulat (*Strict Integer*) | Input angka desimal pada kolom stok | Sistem memvalidasi dan mencegah input pecahan untuk konsistensi opname fisik bongkahan. | **PASS** |

---

### ⚙️ Modul 3: Lantai Produksi, SPK & Papan Kanban (`tests/Feature/E2EButtonFunctionalityTest.php`)
| ID Uji | Nama Skenario Uji | Tindakan / Input | Ekspektasi Sistem | Status |
| :--- | :--- | :--- | :--- | :---: |
| **PRD-01** | Penerbitan SPK Produksi Baru | `POST /production/work-order` | SPK diterbitkan dengan format `SPK-YYYY-XXX`, kartu masuk kolom **ANTREAN**. | **PASS** |
| **PRD-02** | Pergeseran Status Kanban | `PATCH /production/work-order/{id}/status` ke `in_progress` | Status SPK berpindah ke kolom pengerjaan aktif di bengkel. | **PASS** |
| **PRD-03** | Monitoring WIP Stasiun Mesin | `GET /production/wip` | Menampilkan beban stasiun gergaji slep, mesin bubut, dan mesin poles. | **PASS** |

---

### 🔍 Modul 4: Quality Control (QC) & Pengendalian Limbah (`tests/Feature/E2EButtonFunctionalityTest.php`)
| ID Uji | Nama Skenario Uji | Tindakan / Input | Ekspektasi Sistem | Status |
| :--- | :--- | :--- | :--- | :---: |
| **QC-01** | Pencatatan Hasil Inspeksi Mutu | `POST /qc` dengan parameter passed_qty & rejected_qty | Log inspeksi tersimpan di `qc_logs`, menguji bebas retak dan kilap polisan. | **PASS** |
| **WST-01** | Pencatatan Limbah Industri (*Slurry/Chips*) | `POST /waste` dengan jenis limbah dan rencana daur ulang | Data volume limbah tersimpan di `waste_logs` untuk hilirisasi teraso/uruk. | **PASS** |

---

### 🚚 Modul 5: Pengemasan Peti Kayu & Distribusi Logistik (`tests/Feature/E2EButtonFunctionalityTest.php`)
| ID Uji | Nama Skenario Uji | Tindakan / Input | Ekspektasi Sistem | Status |
| :--- | :--- | :--- | :--- | :---: |
| **DST-01** | Verifikasi Packing Peti Kayu Solid | `POST /distribution` dengan checklist foam wrap & pallet peti | Surat jalan diterbitkan dengan nomor resi pengiriman logistik. | **PASS** |
| **DST-02** | Pembaruan Status Kargo Truk | `PATCH /distribution/{id}/status` ke `delivered` | Status pengiriman terupdate dan tercatat waktu penerimaan barang. | **PASS** |

---

### 📈 Modul 6: AI Time Series Forecasting Assistant (`tests/Feature/E2EButtonFunctionalityTest.php`)
| ID Uji | Nama Skenario Uji | Tindakan / Input | Ekspektasi Sistem | Status |
| :--- | :--- | :--- | :--- | :---: |
| **AI-01** | Eksekusi Model ARIMA(2,0,2) | `POST /forecasting/recalculate` model `arima` (17 bulan data empiris) | Menghasilkan proyeksi deret waktu dengan skor akurasi MAPE 5.73%. | **PASS** |
| **AI-02** | Eksekusi Model SES (Exponential Smoothing) | `POST /forecasting/recalculate` model `ses` | Menghitung pemulusan dengan optimasi parameter alpha dinamis. | **PASS** |
| **AI-03** | Eksekusi Holt-Winters & Moving Average | `POST /forecasting/recalculate` model `holt_winters` & `moving_average` | Menghasilkan proyeksi trend dan rata-rata bergerak horizon 3 bulan. | **PASS** |

---

### 🛒 Modul 7: E-Commerce Checkout & 2-Pintu Validasi (`tests/Feature/EcommerceCheckoutTest.php`)
| ID Uji | Nama Skenario Uji | Tindakan / Input | Ekspektasi Sistem | Status |
| :--- | :--- | :--- | :--- | :---: |
| **CHK-01** | Akses Halaman Checkout Publik | `GET /checkout/{product_id}` | Menampilkan form pemesanan, opsi skema DP 50% vs Lunas, QRIS & Bank Transfer. | **PASS** |
| **CHK-02** | Submit Checkout Pintu 1 (Zero Workshop Pollution) | `POST /checkout` oleh pengunjung web | Order dibuat dengan status `pending_payment`, `work_order_id = null`, `expires_at = 24 jam`. **TIDAK membuat SPK fiktif di bengkel.** | **PASS** |
| **CHK-03** | Penerbitan Invoice Digital | `GET /order/invoice/{order_number}` | Menampilkan QRIS dinamis, kode unik verifikasi, rincian biaya, dan tombol WhatsApp. | **PASS** |
| **CHK-04** | Pelacakan Pesanan Live | `GET /lacak-pesanan?order_number=ORD-...` | Menampilkan progres transparan 5-tahap pengerjaan barang. | **PASS** |

---

### 👨‍💼 Modul 8: Admin Order Management & Verifikasi SPK (`tests/Feature/AdminOrderManagementTest.php`)
| ID Uji | Nama Skenario Uji | Tindakan / Input | Ekspektasi Sistem | Status |
| :--- | :--- | :--- | :--- | :---: |
| **ADM-01** | Monitoring Daftar Pesanan Masuk | `GET /orders` oleh Admin | Menampilkan tabel pesanan lengkap dengan 4 metrik ringkasan status. | **PASS** |
| **ADM-02** | Verifikasi Pembayaran & Penerbitan SPK (Pintu 2) | `POST /orders/{id}/verify-spk` | Status pembayaran berubah `paid_dp`/`paid_full`, **SPK resmi diterbitkan** dan masuk ke Kanban bengkel. | **PASS** |
| **ADM-03** | Pembatalan Pesanan Bermasalah | `POST /orders/{id}/cancel` dengan alasan pembatalan | Status order menjadi `cancelled`, tidak mengotori lantai pengerjaan bengkel. | **PASS** |
| **ADM-04** | Pembersihan Pesanan Spam | `DELETE /orders/{id}` | Rekaman data spam terhapus permanen dari sistem. | **PASS** |

---

## 🛡️ 3. LAPORAN UJI KETAHANAN KEAMANAN SIBER & SERANGAN BOT
### File Test: `tests/Feature/BotSpamAndSecurityStressTest.php`

Pengujian stres dan penetrasi keamanan (*penetration test*) dilakukan untuk membuktikan sistem kebal terhadap bot flooding, manipulasi parameter harga, injeksi database, dan XSS.

```
+---------------------------------------------------------------------------------------+
| HASIL UJI KETAHANAN SIBER: 7 / 7 SKENARIO AMAN & TERPROTEKSI PENUH (100% PASS)        |
+---------------------------------------------------------------------------------------+
```

### Rincian 7 Skenario Uji Keamanan Khusus:

#### Skenario 1: Bot Flooding & Spam Checkout Attack (Rate Limiter)
- **Vektor Serangan:** Bot otomatis mengirim request checkout berulang-ulang tanpa jeda waktu.
- **Mekanisme Pertahanan:** Laravel Route Rate Limiter `throttle:5,10`.
- **Hasil Uji:** 5 request pertama diproses normal, **request ke-6 dan seterusnya dari IP yang sama langsung diblokir total dengan HTTP 429 Too Many Requests**.
- **Hasil:** **PASS (TERLINDUNGI)**

#### Skenario 2: Price & Status Tampering Attack (Client Manipulation)
- **Vektor Serangan:** Hacker memodifikasi payload HTTP POST dengan menyisipkan `'unit_price' => 1.00`, `'total_amount' => 2.00`, `'paid_amount' => 1000000`, dan `'order_status' => 'delivered'`.
- **Mekanisme Pertahanan:** Server-side price resolution (`Product::findOrFail()`) & Eloquent guarded attributes.
- **Hasil Uji:** Sistem mengabaikan 100% parameter harga dari client. Harga perolehan dan total tagihan dihitung murni dari database server (Rp 450.000 x 2 = Rp 900.000). Status tetap terkunci di `pending_payment` dan `unpaid`.
- **Hasil:** **PASS (TERLINDUNGI)**

#### Skenario 3: Quantity Boundary & Exploit Fuzzing
- **Vektor Serangan:** Bot mengirim input kuantitas ekstrem: negatif (`-5`), nol (`0`), angka raksasa (`999999`), teks (`'abc'`), atau desimal (`1.5`).
- **Mekanisme Pertahanan:** Laravel Validator `integer|min:1|max:50`.
- **Hasil Uji:** Seluruh input kuantitas non-valid ditolak dengan Session Error Validation `quantity` tanpa mengeksekusi order.
- **Hasil:** **PASS (TERLINDUNGI)**

#### Skenario 4: SQL Injection Probing di Seluruh Form Input
- **Vektor Serangan:** Injeksi query berbahaya seperti `'; DROP TABLE orders; --`, `1' OR '1'='1`, dan `UNION SELECT null, username, password FROM users --` pada kolom nama, alamat, nomor HP, dan catatan kustom.
- **Mekanisme Pertahanan:** PDO Parameterized Queries & Eloquent ORM.
- **Hasil Uji:** Seluruh karakter berbahaya diperlakukan sebagai string literal biasa. Tidak ada query yang bocor dan seluruh tabel database tetap utuh.
- **Hasil:** **PASS (TERLINDUNGI)**

#### Skenario 5: Cross-Site Scripting (XSS) Injection Payload
- **Vektor Serangan:** Penyisipan tag script berbahaya `<script>alert("XSS_PWNED")</script><img src=x onerror=alert(1)>` pada form nama pemesan dan catatan serat.
- **Mekanisme Pertahanan:** Blade Templating Auto-Escaping Engine `{{ ... }}`.
- **Hasil Uji:** Script berhasil diubah menjadi entitas aman (`&lt;script&gt;`). Tidak ada script yang tereksekusi baik di invoice publik maupun dashboard admin.
- **Hasil:** **PASS (TERLINDUNGI)**

#### Skenario 6: Validasi Nomor WhatsApp Bot (Anti-Fake Phone)
- **Vektor Serangan:** Bot menginput nomor HP acak seperti `12345`, `+1-555-0199` (nomor luar negeri), atau string teks `phone_bot_spam`.
- **Mekanisme Pertahanan:** Regex Validator `regex:/^(\+62|62|0)8[1-9][0-9]{6,11}$/`.
- **Hasil Uji:** Seluruh nomor non-standar ditolak dengan pesan peringatan yang ramah bagi pengguna.
- **Hasil:** **PASS (TERLINDUNGI)**

#### Skenario 7: Zero Workshop Pollution Guarantee (Gate 1 Isolation)
- **Vektor Serangan:** Ratusan pengunjung web melakukan checkout tanpa membayar uang muka.
- **Mekanisme Pertahanan:** 2-Gate Validation Architecture.
- **Hasil Uji:** `WorkOrder::count()` terbukti tetap bernilai 0 (papan Kanban bengkel sama sekali tidak bertambah draf fiktif). SPK baru terbit ketika Admin secara sadar mengklik *"Verifikasi & Terbitkan SPK"*.
- **Hasil:** **PASS (TERLINDUNGI)**

---

## 4. CARA MENJALANKAN ULANG SUITE PENGUJIAN (REPRODUCTION COMMANDS)

Untuk menjalankan seluruh rangkaian pengujian kapan saja di terminal proyek:

```bash
# Menjalankan seluruh 35 Feature Tests
vendor/bin/phpunit tests/Feature

# Menjalankan khusus Uji Keamanan & Ketahanan Bot
vendor/bin/phpunit tests/Feature/BotSpamAndSecurityStressTest.php

# Menjalankan khusus Uji Alur Checkout & Tracking
vendor/bin/phpunit tests/Feature/EcommerceCheckoutTest.php

# Menjalankan khusus Uji Manajemen Pesanan Admin & Verifikasi SPK
vendor/bin/phpunit tests/Feature/AdminOrderManagementTest.php
```

---
*Laporan Pengujian ini disusun secara formal sebagai dokumentasi jaminan mutu perangkat lunak (*Software Quality Assurance*), kelengkapan berkas HKI DJKI Kemenkumham RI, dan laporan kemajuan penelitian/pengabdian IKM Marmer Tulungagung.*
