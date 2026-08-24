# 🔍 Audit Flow Katalog & Landing Page — Web SCM Marmer

> Scope: `public/index.blade.php`, `public/catalog.blade.php`, `public/detail.blade.php`,  
> `public/checkout.blade.php`, `public/tracking.blade.php`, `layouts/public.blade.php`,  
> `PublicCatalogController.php`, `CheckoutController.php`  
> Tanggal: 2026-08-24

---

## ✅ BAGIAN 1 — Peta Alur User (Customer Journey)

```
Beranda (/)
  ├── → Katalog (/katalog)          [tombol hero + tab filter]
  │       ├── → Detail Produk (/katalog/{id})    [klik card]
  │       │       ├── → Checkout (/checkout/{id})     [tombol Beli]
  │       │       │       └── → Invoice (/order/invoice/{no})  [setelah submit]
  │       │       │               └── → Tracking (/lacak-pesanan?order_number=...)
  │       │       └── → WA Pengrajin [tombol Tanya Serat]
  │       └── → [Quick View Modal] → Checkout / Detail / WA
  └── [Quick View Modal di etalase hero] → Checkout / Detail / WA
```

**Alur inti cukup solid.** Namun ada 12 temuan masalah di sepanjang jalur ini.

---

## 🚨 BAGIAN 2 — Temuan Per Halaman

---

### 📄 HALAMAN: Landing Page (`/`)

#### LP-01 🔴 KRITIS — Hero Card "Lihat Spesifikasi" memanggil ID hardcoded = 9
```javascript
// index.blade.php line 113
onclick="openProductModal(9)"
```
- Hero card menampilkan **Wastafel Onyx Honey Translucent** dengan harga **Rp 950.000**  
- Tapi tombol "Lihat Spesifikasi" memanggil `openProductModal(9)` — **ID 9 tidak ada** di fallback data (hanya ada ID 1–8) dan bisa saja tidak ada di DB.
- **Dampak**: Modal muncul kosong/error `fetch` 404. Pembeli klik → modal gagal → langsung cabut.

#### LP-02 🔴 KRITIS — Harga di Hero Card (`Rp 950.000`) Hardcoded, Beda dengan Fallback
```html
<!-- index.blade.php line 111 -->
<p class="text-lg font-black text-amber-400">Rp 950.000</p>
```
- Fallback data `PRD-CO-002` (Wastafel Onix Honey): `selling_price = 1.250.000`
- Hero card menampilkan **Rp 950.000** yang tidak konsisten dengan data manapun.
- **Dampak**: Inconsistency harga antara hero dengan card di grid etalase di bawahnya.

#### LP-03 🟠 TINGGI — Filter Tab di Etalase Tidak Menghitung Badge Angka
```html
<!-- line 258: hanya ada tab "Semua" dengan counter -->
Semua Produk ({{ $featuredProducts->count() }})
<!-- Tapi tab Marmer, Onyx, Batu Kali tidak ada counter -->
```
- Tab "Semua" ada angkanya, tapi tab lainnya tidak menampilkan berapa produk per material.
- **Dampak**: Pembeli tidak tahu ada berapa produk onyx sebelum klik filter.

#### LP-04 🟠 TINGGI — "Lihat Produk UD Cahaya Onix" Filter Hanya ke `onix`, Bukan Semua Produk CO
```php
// index.blade.php line 175
route('catalog', ['material' => 'onix'])
```
- UD Cahaya Onix juga punya produk `marmer` (Wastafel Marmer Putih B1, Meja Marmer).  
- Filter `material=onix` **menghilangkan produk marmer** dari UD Cahaya Onix.  
- **Dampak**: Pembeli yang tertarik UD Cahaya Onix tidak melihat semua koleksinya.

#### LP-05 🟡 SEDANG — Halaman `#kontak` Memakai Email Fiktif
```html
<!-- layouts/public.blade.php line 251 -->
kontak@scm-marmer-tulungagung.id
```
- Ini email placeholder. Belum diisi email aktual IKM.
- **Dampak**: Jika pembeli kirim email ke sini, tidak ada yang menerima.

#### LP-06 🟡 SEDANG — "Profil IKM" dan "Alur SCM" di Navbar Link ke Hash Anchor (`#`) Beranda
```html
<!-- layouts/public.blade.php line 102-107 -->
<a href="{{ route('home') }}#profil-ikm">Profil IKM</a>
<a href="{{ route('home') }}#alur-rantai-pasok">Alur SCM</a>
```
- **Masalah**: Jika pembeli sedang di halaman `/katalog/{id}` lalu klik "Profil IKM", browser akan navigate ke `/?#profil-ikm` (halaman lain), **bukan smooth scroll di halaman yang sama**.
- Ini perilaku yang benar untuk navigasi antar halaman, tapi **mobile menu juga punya link** `#kontak` yang tidak ada di desktop nav. Inkonsisten.

#### LP-07 🟢 MINOR — Nomor Telepon Berbeda di Controller vs Checkout View
```php
// CheckoutController.php line 39
'account_number' => '048-1928-384',  // BCA UD Cahaya Onix

// checkout.blade.php line 185 (bank_bca label)
Rek: 180-889-7721 a/n UD CAHAYA ONIX / PUTRA ABADI  // ← BEDA!
```
- Nomor rekening di UI berbeda dengan yang ada di controller untuk konfirmasi WA.
- **Dampak**: Bisa membingungkan pembeli saat verifikasi pembayaran.

---

### 📄 HALAMAN: Katalog Lengkap (`/katalog`)

#### CAT-01 🟠 TINGGI — Filter `stock` Ada di Controller tapi Tidak Ada di UI Form
```php
// PublicCatalogController.php line 113-119
if ($request->filled('stock')) {
    if ($request->stock === 'ready') { ... }
    elseif ($request->stock === 'preorder') { ... }
}
```
- Backend support filter `?stock=ready` dan `?stock=preorder`, tapi **tidak ada elemen `<select>` atau toggle di form catalog.blade.php**.
- **Dampak**: Filter stok bisa dipakai via URL manual, tapi pembeli tidak tahu fitur ini ada. Fitur mubazir.

#### CAT-02 🟡 SEDANG — Filter Category Pakai `slug` tapi Fallback Pakai `id` (Double Lookup)
```php
// controller line 102-104
$q->where('slug', $categorySlug)->orWhere('id', $categorySlug);
```
- Desain ini menerima slug **atau** ID. Ini bagus untuk flexibility, tapi berpotensi ambiguous jika ada slug angka yang sama dengan ID lain.

#### CAT-03 🟡 SEDANG — Pagination Default **12 produk** di Katalog, Landing Page Hanya Tampil **8**
- Gap ekspektasi: landing page tease 8 produk, halaman katalog paginate 12. Tidak ada masalah kritis, tapi konsistensi `perPage` bisa lebih baik.

---

### 📄 HALAMAN: Detail Produk (`/katalog/{id}`)

#### DET-01 🟠 TINGGI — Gallery Thumbnail Hanya 1 Foto Real, Sisanya 3 Placeholder Icon
```html
<!-- detail.blade.php line 59-75 -->
<!-- Thumbnail 1: gambar produk -->
<!-- Thumbnail 2: Lubang 4.5cm (icon) -->
<!-- Thumbnail 3: Lolos QC 2 (icon) -->
<!-- Thumbnail 4: Peti Kayu (icon) -->
```
- Hanya thumbnail pertama yang mengarah ke gambar nyata.  
- Tiga lainnya adalah **ikon info statis** yang tidak interaktif secara galeri.  
- **Dampak**: Pembeli tidak bisa melihat foto dari sudut lain — kehilangan kepercayaan untuk produk dengan harga Rp 1,2 Jt+.

#### DET-02 🟠 TINGGI — "Related Products" Tidak Filter by Category/Material yang Sama
```php
// PublicCatalogController.php line 222-225
$relatedProducts = Product::with('category')
    ->where('id', '!=', $product->id)
    ->take(3)
    ->get();  // TIDAK ada filter where category_id atau material_type
```
- Produk "terkait" bisa berisi produk dari kategori sama sekali berbeda.
- **Dampak**: Pembeli yang melihat detail Wastafel Onyx bisa mendapat rekomendasi Stepping Stone — tidak relevan.

#### DET-03 🟡 SEDANG — "Estimasi Bobot Fisik" Hardcoded `14-18 kg` untuk Semua Produk
```html
<!-- detail.blade.php line 134-136 -->
<b class="text-slate-900">14 - 18 kg (Padat Murni)</b>
```
- Stepping Stone (D:30cm, 3cm tebal) pasti jauh lebih ringan dari Wastafel Mangkok (D:40cm, T:15cm).
- Data bobot sama untuk semua produk tidak akurat.

#### DET-04 🟢 MINOR — Tidak Ada Tombol "Bagikan Produk" (Share)
- Tidak ada tombol share ke WhatsApp/copy URL — penting untuk produk premium di mana pembeli sering konsultasi dulu ke keluarga.

---

### 📄 HALAMAN: Checkout (`/checkout/{id}`)

#### CHK-01 🔴 KRITIS — Tidak Ada Validasi Kuantitas terhadap Stok Tersedia
```php
// CheckoutController store(), line 71-86
'quantity' => ['required', 'integer', 'min:1', 'max:50'],
// ← Tidak ada validasi: quantity <= product->ready_stock
```
- Pembeli bisa order 50 unit padahal stok hanya 3 unit.
- Tidak ada cek `if ($qty > $product->ready_stock && $product->ready_stock > 0)`.
- **Dampak**: Admin menerima pesanan yang tidak bisa dipenuhi → refund/cancel → bad experience.

#### CHK-02 🔴 KRITIS — Nomor Rekening di UI Berbeda dengan Data di Controller
```html
<!-- checkout.blade.php line 185 -->
Rek: 180-889-7721 a/n UD CAHAYA ONIX / PUTRA ABADI

<!-- CheckoutController.php line 39 -->
'account_number' => '048-1928-384'
```
- **Dua nomor BCA berbeda** di dua tempat berbeda untuk IKM yang sama.
- `$banks` yang dipass dari controller ke view tidak dipakai — view memakai nomor hardcoded sendiri!

#### CHK-03 🟠 TINGGI — Variabel `$banks` Dikirim dari Controller tapi TIDAK DIRENDER di View
```php
// CheckoutController show(), line 61
return view('public.checkout', compact('product', 'artisan', 'banks'));
```
```html
<!-- checkout.blade.php: tidak ada satupun {{ $banks[...] }} -->
<!-- Semua nomor rekening hardcoded langsung di HTML -->
```
- Ini mirip dengan GAP-09 di dashboard — wasted data pass.

#### CHK-04 🟠 TINGGI — Tidak Ada Konfirmasi / Penjelasan Saat Pre-Order
- Jika `ready_stock = 0` (status Pre-Order), **tidak ada info estimasi waktu pengerjaan** yang ditampilkan di form checkout.
- Lead time produksi bisa 3–7 hari kerja namun pembeli tidak tahu dari halaman ini.

#### CHK-05 🟡 SEDANG — `quantity` Input Type `readonly` — Tidak Bisa Diketik Manual
```html
<!-- checkout.blade.php line 248 -->
<input type="number" ... readonly>
```
- Hanya bisa dikurangi/tambah dengan tombol `+/-`. Untuk order 20+ unit, pembeli harus klik 20x.
- Seharusnya bisa diketik langsung namun tetap divalidasi min/max dengan JS.

---

### 📄 HALAMAN: Invoice & Tracking

#### TRK-01 🟡 SEDANG — Tracking Breadcrumb Kembali ke Katalog, Bukan ke Invoice
```html
<!-- tracking.blade.php line 12 -->
<a href="{{ route('catalog') }}">Katalog</a>
```
- Pembeli yang mengakses tracking dari invoice harusnya bisa kembali ke Invoice mereka, bukan ke Katalog umum.
- **Solusi**: Tambahkan query param `?from=invoice&order=ORD-...` atau simpan `order_number` di session.

#### INV-01 🟡 SEDANG — Invoice Tidak Menampilkan **Batas Waktu Pembayaran** secara Prominent
- `expires_at` ada di model Order dan dihitung dengan benar, tapi tidak ada countdown timer atau pesan jelas "Bayar sebelum: [tanggal/jam]" di invoice.
- **Dampak**: Pembeli tidak terdesak bayar → order expired → SPK tidak pernah dibuat.

---

## 📊 BAGIAN 3 — Ringkasan Temuan

| Prioritas | Kode | Halaman | Temuan |
|---|---|---|---|
| 🔴 Kritis | LP-01 | Landing | Hero modal panggil ID=9 yang tidak ada |
| 🔴 Kritis | LP-02 | Landing | Harga hero hardcoded Rp950k, tidak sinkron |
| 🔴 Kritis | CHK-01 | Checkout | Tidak ada validasi qty vs stok tersedia |
| 🔴 Kritis | CHK-02 | Checkout | Nomor rekening BCA inkonsisten (2 versi) |
| 🟠 Tinggi | LP-03 | Landing | Tab filter tidak ada counter per material |
| 🟠 Tinggi | LP-04 | Landing | Link UD Cahaya Onix hanya filter `onix`, skip `marmer` |
| 🟠 Tinggi | CAT-01 | Katalog | Filter `?stock=ready` tidak ada di UI form |
| 🟠 Tinggi | DET-01 | Detail | Gallery 3 dari 4 thumbnail tidak fungsional |
| 🟠 Tinggi | DET-02 | Detail | Related products tidak filter by kategori/material |
| 🟠 Tinggi | CHK-03 | Checkout | `$banks` di controller tidak dirender di view (wasted) |
| 🟠 Tinggi | CHK-04 | Checkout | Tidak ada info estimasi lead time saat Pre-Order |
| 🟡 Sedang | LP-05 | Footer | Email kontak fiktif/placeholder |
| 🟡 Sedang | LP-07 | Checkout | Nomor rekening berbeda di controller vs view |
| 🟡 Sedang | DET-03 | Detail | Bobot produk hardcoded sama semua (14-18 kg) |
| 🟡 Sedang | CHK-05 | Checkout | Input qty `readonly`, tidak bisa ketik manual |
| 🟡 Sedang | TRK-01 | Tracking | Breadcrumb salah konteks |
| 🟡 Sedang | INV-01 | Invoice | Tidak ada countdown batas waktu pembayaran |
| 🟢 Minor | LP-06 | Navbar | Inkonsistency menu mobile vs desktop (link `#kontak`) |
| 🟢 Minor | DET-04 | Detail | Tidak ada tombol share produk |
| 🟢 Minor | CAT-02 | Katalog | Double lookup slug/id bisa ambiguous |

---

## 🛠️ BAGIAN 4 — Rekomendasi Perbaikan (Urutan Prioritas)

```
1. [KRITIS] LP-01  Fix hero modal: ganti openProductModal(9) → pakai $featuredProducts->first()->id
2. [KRITIS] LP-02  Sinkronkan harga hero card dengan data DB/fallback
3. [KRITIS] CHK-01 Tambah validasi: 'quantity' => ['max:' . $product->ready_stock] di controller
4. [KRITIS] CHK-02+03 Hapus nomor rek hardcoded di view, pakai $banks yang sudah dikirim controller
5. [TINGGI] LP-04  Ubah link UD Cahaya Onix → route('catalog') tanpa filter (atau filter by artisan)
6. [TINGGI] CAT-01 Tambah filter toggle "Ready Stock / Pre-Order" di form catalog
7. [TINGGI] DET-02 Filter related products: where('category_id', $product->category_id)->orWhere('material_type', $product->material_type)
8. [TINGGI] CHK-04 Tampilkan info lead time "3-7 hari kerja" di checkout saat stok = 0
9. [SEDANG] INV-01 Tambahkan countdown / text expires_at di invoice
10.[SEDANG] CHK-05 Ubah qty input dari readonly → editable dengan validasi JS onchange
```
