# Laporan Pengujian dan Perbaikan Kode (TEST_REPORT.md)
## Sistem Informasi E-Supply Chain Management (E-SCM) Klaster IKM Marmer Tulungagung

Dokumen ini mencatat secara sistematis hasil pengujian perangkat lunak, evaluasi peninjauan kode (Activity 7), serta pelaksanaan dan perbaikan skenario pengujian otomatis Black-Box (Activity 9) pada sistem E-SCM Marmer Tulungagung.

---

## 1. Ringkasan Pelaksanaan Pengujian

Pengujian dilakukan secara bertahap menggunakan framework otomatis PHPUnit / Laravel Testing Suite dengan bantuan trait `DatabaseTransactions` untuk menjamin bahwa seluruh data dummy yang dihasilkan selama pengujian langsung dibersihkan (*rollback*) secara otomatis tanpa mengotori atau merusak data utama pada basis data.

- **Total Iterasi Pengujian**: 4 Iterasi
- **Total Skenario Pengujian**: 20 Skenario (12 Black-Box Test Suite + 8 End-to-End Functionality Test)
- **Status Akhir Pengujian**: Lulus 100% (20 / 20 Assertions Passed)

---

## 2. Rincian Iterasi Pengujian dan Perbaikan Kode

### 2.1. Iterasi 1: Pengujian Baseline Fungsionalitas Modul (E2EButtonFunctionalityTest.php)

- **Perintah**: `php artisan test tests/Feature/E2EButtonFunctionalityTest.php`
- **Status**: Gagal (1 Gagal / 7 Lulus)

#### Deskripsi Kegagalan
```text
FAILED Tests\Feature\E2EButtonFunctionalityTest > distribution shipment and status buttons work
ErrorException: Attempt to read property "id" on null
at tests/Feature/E2EButtonFunctionalityTest.php:153
```

#### Analisis Akar Masalah
Data master pelanggan (`customers`) belum dimasukkan pada seeder `DatabaseSeeder.php`. Ketika skenario pengujian distribusi memanggil `Customer::first()`, nilai yang dikembalikan adalah `null` sehingga pengiriman Surat Jalan gagal dibuat.

#### Perbaikan yang Diterapkan
1. Memperbarui seeder `DatabaseSeeder.php` dengan menambahkan data sampel pelanggan (`CUST-BALI-01` dan `CUST-SBY-02`).
2. Menjalankan ulang seeder menggunakan metode `upsert` tanpa menghapus struktur basis data eksisting:
   ```bash
   php artisan db:seed --class=DatabaseSeeder --force
   ```

---

### 2.2. Iterasi 2: Eksekusi Perdana Black-Box Test Suite (BlackBoxTestSuiteTest.php)

- **Perintah**: `php artisan test tests/Feature/BlackBoxTestSuiteTest.php`
- **Status**: Gagal (4 Gagal / 8 Lulus)

#### Deskripsi Kegagalan
1. `RouteNotFoundException: Route [qc.store] not defined.`
2. `RouteNotFoundException: Route [analytics.calculate] not defined.`
3. Uncaught `Exception: Stok bahan baku tidak mencukupi untuk transaksi keluar.` pada pelemparan error mutasi stok.

#### Analisis Akar Masalah
- Terdapat ketidaksesuaian penamaan rute antara skrip pengujian dengan definisi rute aplikasi pada `routes/modules/qc.php` (rute resmi: `qc.inspect`) dan `routes/modules/analytics.php` (rute resmi: `forecasting.calculate`).
- Pengeluaran stok berlebih pada `MaterialController.php` menggunakan pelemparan `throw new \Exception(...)` tanpa penanganan pada pengontrol sehingga memicu HTTP 500 Unhandled Exception.

#### Perbaikan yang Diterapkan
Menyesuaikan penamaan alias rute di dalam berkas pengujian `tests/Feature/BlackBoxTestSuiteTest.php` sesuai dengan pendaftaran rute aplikasi.

---

### 2.3. Iterasi 3: Identifikasi dan Perbaikan Bug Logika Bisnis (QC & Mutasi Stok)

- **Perintah**: `php artisan test tests/Feature/BlackBoxTestSuiteTest.php`
- **Status**: Gagal (1 Gagal / 11 Lulus)

#### Deskripsi Kegagalan
```text
FAILED Tests\Feature\BlackBoxTestSuiteTest > tc qc 02 store inspection mismatched quantities
Session is missing expected key [errors].
at tests/Feature/BlackBoxTestSuiteTest.php:199
```

#### Analisis Akar Masalah
1. **Celah Logika Pengendalian Kualitas (QC Log)**:
   Pada pengontrol `QcController.php`, sistem menerima dan menyimpan data inspeksi meskipun jumlah unit yang diperiksa (`inspected_quantity = 10`) tidak sama dengan penjumlahan unit hasil inspeksi (`pass = 8, rework = 5, scrap = 2`, total 15 unit). Hal ini merupakan celah logika bisnis yang dapat merusak akurasi pencatatan stok dan laporan QC.
2. **Penanganan Error Mutasi Stok Keluar**:
   Pengeluaran bahan baku melebihi stok yang tersedia tidak memberikan respon validasi formulir yang ramah pengguna.

#### Perbaikan Kode yang Diterapkan

##### 1. Pembaruan pada `app/Http/Controllers/QcController.php`
Menambahkan validasi kustom `Validator::after()` untuk memverifikasi kesesuaian akumulasi unit:
```php
$validator->after(function ($validator) use ($request) {
    $inspected = (int) $request->input('inspected_quantity', 0);
    $pass = (int) $request->input('pass_quantity', 0);
    $rework = (int) $request->input('rework_quantity', 0);
    $scrap = (int) $request->input('scrap_quantity', 0);

    if (($pass + $rework + $scrap) !== $inspected) {
        $validator->errors()->add(
            'inspected_quantity',
            'Jumlah total unit (Lolos + Rework + Scrap) harus sama dengan Jumlah yang Diperiksa.'
        );
    }
});
```

##### 2. Pembaruan pada `app/Http/Controllers/MaterialController.php`
Memeriksa ketersediaan stok sebelum transaksi dilaksanakan dan mengembalikan respon validasi formulir:
```php
$material = Material::findOrFail($validated['material_id']);
if ($validated['type'] === 'out' && $material->current_stock < $validated['quantity']) {
    return back()->withErrors([
        'quantity' => 'Stok bahan baku tidak mencukupi untuk transaksi keluar.'
    ])->withInput();
}
```

---

### 2.4. Iterasi 4: Verifikasi Akhir Seluruh Skenario Pengujian (Final Verification)

- **Perintah**:
  ```bash
  php artisan test tests/Feature/BlackBoxTestSuiteTest.php
  php artisan test tests/Feature/E2EButtonFunctionalityTest.php
  ```
- **Status**: Lulus 100% (20 / 20 Skenario Lulus)

#### Rekap Hasil Pengujian Akhir

##### A. Black-Box Test Suite (`BlackBoxTestSuiteTest.php`)
```text
  PASS  Tests\Feature\BlackBoxTestSuiteTest
  ✓ tc auth 01 login success valid credentials                                   0.48s  
  ✓ tc auth 02 login fail invalid password                                       0.23s  
  ✓ tc stk 01 store material valid                                               0.04s  
  ✓ tc stk 02 record transaction exceed stock                                    0.03s  
  ✓ tc stk 03 record transaction negative qty                                    0.04s  
  ✓ tc prd 01 store work order valid                                             0.04s  
  ✓ tc prd 02 store work order invalid dates                                     0.02s  
  ✓ tc qc 01 store inspection qc2 valid                                          0.04s  
  ✓ tc qc 02 store inspection mismatched quantities                              0.02s  
  ✓ tc dst 01 store shipment valid                                               0.04s  
  ✓ tc dst 02 update shipment status                                             0.03s  
  ✓ tc for 01 calculate forecasting                                              2.09s  

  Tests:    12 passed (34 assertions)
  Duration: 3.27s
```

##### B. E2E Button Functionality Test (`E2EButtonFunctionalityTest.php`)
```text
  PASS  Tests\Feature\E2EButtonFunctionalityTest
  ✓ guest can access login page                                                   0.26s  
  ✓ authenticated user can access all module views                                0.18s  
  ✓ material crud and transaction buttons work                                    0.05s  
  ✓ production spk buttons and kanban progression work                            0.05s  
  ✓ qc inspection button works                                                    0.03s  
  ✓ waste log button works                                                        0.03s  
  ✓ distribution shipment and status buttons work                                 0.04s  
  ✓ forecasting recalculate button works                                          2.07s  

  Tests:    8 passed (37 assertions)
  Duration: 2.93s
```

---

## 3. Matriks Skenario Pengujian Black-Box Terverifikasi

| ID Uji | Modul | Skenario Pengujian | Teknik Pengujian | Data Input Test | Hasil yang Diharapkan | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-AUTH-01** | Auth & RBAC | Login sukses dengan kredensial valid | Equivalence Partitioning | `email: owner@cahayaonix.com`<br>`password: role123` | Autentikasi berhasil dan diarahkan ke Dashboard. | Lulus |
| **TC-AUTH-02** | Auth & RBAC | Login gagal dengan kata sandi salah | Equivalence Partitioning | `email: owner@cahayaonix.com`<br>`password: wrong123` | Sistem menolak login dan menampilkan pesan error. | Lulus |
| **TC-STK-01** | Stok Material | Input bahan baku baru data valid | Equivalence Partitioning | Code: `MAT-TEST-xx`, Qty: `20`, Cost: `250000` | Data tersimpan dan transaksi awal otomatis dicatat. | Lulus |
| **TC-STK-02** | Stok Material | Input mutasi keluar melebihi stok riil | Boundary Value Analysis | Material ID: `5`, Type: `out`, Quantity: Exceed | Transaksi ditolak dengan pesan validasi stok kurang. | Lulus |
| **TC-STK-03** | Stok Material | Input kuantitas mutasi negatif | Boundary Value Analysis | Quantity: `-5` | Form menolak input dengan error validasi kuantitas. | Lulus |
| **TC-PRD-01** | SPK Produksi | Penerbitan SPK baru data valid | Equivalence Partitioning | Product ID: `4`, Target: `10`, Priority: `high` | SPK nomor terbit dan 3 tahapan produksi otomatis terbuat. | Lulus |
| **TC-PRD-02** | SPK Produksi | Tanggal tenggat sebelum tanggal mulai | Boundary Value Analysis | Start: `2026-08-25`, Due: `2026-08-20` | Form menolak dengan error validasi tanggal tenggat. | Lulus |
| **TC-QC-01** | Quality Control | Input inspeksi QC2 Final Polish valid | Decision Table | Stage: `qc2_final_polish`, Inspected: `10`, Pass: `10` | Log tersimpan, SPK selesai, stok barang jadi bertambah. | Lulus |
| **TC-QC-02** | Quality Control | Total unit inspeksi tidak sesuai | Decision Table | Inspected: `10`, Pass: `8`, Rework: `5`, Scrap: `2` | Sistem menolak input dengan error diskrepansi unit. | Lulus |
| **TC-DST-01** | Distribusi | Penerbitan Surat Jalan pengiriman | Equivalence Partitioning | Customer ID: `1`, Date: Valid, Expedition: Express | Surat Jalan terbit dengan status awal `packed`. | Lulus |
| **TC-DST-02** | Distribusi | Update status pengiriman | Equivalence Partitioning | Shipment ID: `1`, Delivery Status: `delivered` | Status pengiriman diperbarui menjadi `delivered`. | Lulus |
| **TC-FOR-01** | Peramalan | Kalkulasi peramalan Holt-Winters | Equivalence Partitioning | Item: `product`, ID: `4`, Horizon: `3` | Proyeksi 3 periode dihasilkan dan dicatat ke log. | Lulus |

---

## 4. Kesimpulan

Seluruh skenario pengujian pada Activity 7 (Code Review & Pengetatan Validasi) dan Activity 9 (Pengujian Black-Box Otomatis) telah terverifikasi dengan hasil 100% lulus. Sistem E-SCM Marmer Tulungagung dinyatakan stabil, memiliki validasi data yang konsisten, serta bebas dari data pengujian sementara pada lingkungan basis data utama.
