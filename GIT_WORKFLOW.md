# 🌿 PANDUAN STANDAR GIT WORKFLOW & KOLABORASI TIM (GIT_WORKFLOW.md)
## Proyek Sistem Informasi E-Supply Chain Management (E-SCM) IKM Marmer

Panduan ini mengatur standar branching, format commit, prosedur Pull Request (PR), serta penanganan konflik agar pengembangan kode berjalan rapi, terlacak, dan aman dari bentrokan (*conflict*).

---

## 1. 🌳 Struktur & Hirarki Branch

Proyek ini mengadopsi model **Feature Branch Workflow (GitHub Flow)** dengan dua branch utama yang dilindungi (*protected branches*):

```
[ main / master ] ── (Versi Stabil / Siap Demo / Cloud Production)
       ▲
       │ Merge via Pull Request (setelah lolos testing)
[ dev / staging ] ── (Branch Integrasi Harian Seluruh Fitur)
       ▲
       ├── feature/stock-management ─── (Branch Fitur Baru)
       ├── feature/spk-tracker ──────── (Branch Fitur Baru)
       └── fix/qc-calculation-bug ───── (Branch Perbaikan Bug)
```

### Penjelasan Branch:
1. **`main`:** Branch versi produksi stabil. Hanya boleh di-merge dari `dev` saat satu siklus milestone/kegiatan selesai dan sudah lolos pengujian (*Pass Black-Box Testing*).
2. **`dev`:** Branch integrasi aktif harian. Semua fitur baru dan perbaikan bug di-merge ke sini terlebih dahulu.
3. **`feature/<nama-fitur>`:** Branch khusus untuk mengembangkan satu modul/fitur spesifik. Dibuat bercabang dari `dev` dan di-merge kembali ke `dev`.
4. **`fix/<nama-bug>`:** Branch untuk perbaikan kendala/bug yang ditemukan saat pengujian.
5. **`docs/<nama-dokumen>`:** Branch khusus untuk penambahan atau pembaruan dokumentasi/laporan kegiatan.

---

## 2. 🏷️ Konvensi Penamaan Branch (Naming Conventions)

Gunakan huruf kecil (*lowercase*) dan tanda hubung (*kebab-case*):

| Kategori | Format Nama Branch | Contoh |
| :--- | :--- | :--- |
| **Fitur Baru** | `feature/<modul>-<deskripsi>` | `feature/stock-material-crud`<br>`feature/spk-production-pipeline`<br>`feature/forecast-fastapi-integration` |
| **Perbaikan Bug** | `fix/<modul>-<deskripsi-bug>` | `fix/auth-operator-redirect`<br>`fix/qc-defect-calculation` |
| **Dokumentasi** | `docs/<deskripsi>` | `docs/kegiatan-6-ui-mockup`<br>`docs/update-erd-specification` |
| **Refactoring** | `refactor/<modul>-<deskripsi>` | `refactor/clean-stock-service` |

---

## 3. 💬 Standar Pesan Commit (Conventional Commits)

Format pesan commit wajib mengikuti pola:
```
<type>(<scope>): <deskripsi singkat perubahan>

[opsional: penjelasan detail atau alasan perubahan]
```

### Tipe Commit (`<type>`):
- **`feat`**: Menambahkan fitur fungsional baru.
  - *Contoh:* `feat(stock): tambah validasi kuantitas minimum pada form bahan masuk`
  - *Contoh:* `feat(production): buat antrean stasiun bubut dan slep digital`
- **`fix`**: Memperbaiki bug atau kesalahan logika.
  - *Contoh:* `fix(qc): perbaiki perhitungan persentase reject rate tahap 1`
- **`docs`**: Perubahan atau penambahan dokumentasi/markdown.
  - *Contoh:* `docs(kegiatan4): lengkapi kamus data tabel waste_logs`
- **`style`**: Perubahan format/tampilan UI/CSS tanpa mengubah logika bisnis.
  - *Contoh:* `style(dashboard): perbaiki kontras warna card KPI mobile`
- **`refactor`**: Restrukturisasi kode tanpa mengubah fungsionalitas eksternal.
  - *Contoh:* `refactor(forecasting): optimasi kalkulasi metrik MAPE`
- **`test`**: Menambahkan atau memperbarui skenario automated test / unit test.
  - *Contoh:* `test(material): tambah test case mutasi stok negatif`
- **`chore`**: Pembaruan dependensi, build script, atau konfigurasi tooling.
  - *Contoh:* `chore(deps): update dependensi pandas dan statsmodels`

---

## 4. 🔄 Alur Kerja Harian Langkah Demi Langkah (Step-by-Step Daily Workflow)

### Langkah 1: Sinkronisasi Branch `dev` Lokal
Sebelum mulai membuat fitur baru, selalu pastikan branch `dev` Anda adalah versi terbaru:
```bash
git checkout dev
git pull origin dev
```

### Langkah 2: Buat Branch Fitur Baru
```bash
git checkout -b feature/stock-material-crud
```

### Langkah 3: Coding, Testing & Commit Teratur
Lakukan commit kecil dan terfokus (jangan menumpuk ratusan perubahan dalam satu commit raksasa di akhir):
```bash
# Cek file yang berubah
git status

# Stage file yang relevan
git add app/Http/Controllers/MaterialController.php
git add resources/views/materials/index.blade.php

# Commit dengan pesan yang jelas
git commit -m "feat(material): implementasi CRUD master bahan baku marmer"
```

### Langkah 4: Sinkronisasi Ulang Sebelum Push (*Avoid Conflicts*)
Tarik perubahan terbaru dari `dev` untuk memastikan kode Anda tidak bentrok:
```bash
git fetch origin dev
git merge origin/dev
# (Selesaikan konflik jika ada, lalu commit)
```

### Langkah 5: Push Branch ke Remote (GitHub/GitLab)
```bash
git push -u origin feature/stock-material-crud
```

### Langkah 6: Buka Pull Request (PR)
1. Buka repositori di GitHub/GitLab.
2. Klik tombol **"Compare & pull request"**.
3. Pastikan target branch adalah **`base: dev`** $\leftarrow$ **`compare: feature/stock-material-crud`**.
4. Isi deskripsi PR menggunakan template di bawah.
5. Minta rekan tim untuk melakukan *Code Review*.
6. Setelah disetujui, lakukan **Squash and Merge** atau **Rebase and Merge**, lalu hapus branch fitur.

---

## 5. 📝 Template Deskripsi Pull Request (PR)

Salin template ini saat membuka Pull Request:

```markdown
## 📌 Ringkasan Perubahan
- Mengimplementasikan form input bahan baku marmer masuk.
- Menambahkan validasi batas stok minimum (alert indicator).

## 🎯 Modul / Kegiatan Terkait
- **Kegiatan SDLC:** Kegiatan 7 (Pengkodean Modul Stok)
- **Tabel Terkait:** `materials`, `stock_transactions`

## 🧪 Pengujian yang Telah Dilakukan
- [x] Berhasil menambah data bahan baku baru (Marmer Putih Super).
- [x] Mutasi stok otomatis bertambah saat status IN disimpan.
- [x] Form menolak kuantitas bernilai minus (TC-004 Pass).
- [x] Tampilan responsif di layar mobile (resolusi 375px).

## 📸 Bukti Tangkapan Layar (Screenshot)
*(Lampirkan screenshot jika ada perubahan UI)*
```

---

## 6. ⚠️ Aturan Keselamatan (DOs & DON'Ts)

### ⛔ DILARANG KERAS (DON'TS):
- ❌ **DILARANG** melakukan `git push --force` ke branch `main` atau `dev`.
- ❌ **DILARANG** melakukan commit file `.env` (file memuat kredensial dan password privat).
- ❌ **DILARANG** melakukan commit folder dependensi (`vendor/`, `node_modules/`, `forecasting_service/venv/`).
- ❌ **DILARANG** melakukan *direct commit* langsung ke branch `main` tanpa melalui Pull Request & testing.

### ✅ SANGAT DIANJURKAN (DOS):
- ✔️ Buat commit secara bertahap dengan deskripsi yang komunikatif.
- ✔️ Selalu jalankan aplikasi dan pastikan tidak ada error sebelum membuka PR.
- ✔️ Update dokumentasi terkait jika Anda mengubah skema database (`DESIGN.md` & `schema.sql`).

---

## 7. 🛠️ Git Cheat Sheet & Penanganan Kendala

| Kebutuhan / Masalah | Perintah Git yang Digunakan |
| :--- | :--- |
| **Melihat status file yang berubah** | `git status` |
| **Melihat riwayat commit ringkas** | `git log --oneline -n 10` |
| **Menyimpan perubahan sementara saat ingin ganti branch** | `git stash`<br>*(Mengembalikan: `git stash pop`)* |
| **Membatalkan commit terakhir (file tetap aman)** | `git reset --soft HEAD~1` |
| **Membatalkan perubahan file yang belum di-stage** | `git restore <nama-file>` |
| **Menghapus branch lokal yang sudah di-merge** | `git branch -d feature/nama-fitur` |
