# ATURAN AGENT UNTUK PROYEK WEB SCM MARMER (GEMINI.md)

## 🔒 Aturan Keamanan & Penanganan File Lingkungan (`.env`)

1. **DILARANG KERAS MEMBACA FILE `.env` SECARA LANGSUNG:**
   - Agent **TIDAK BOLEH** menggunakan tool apapun (`view_file`, `grep_search`, perintah terminal `cat`, `type`, `Get-Content`, `head`, `tail`, dsb.) untuk membuka atau membaca file `.env` aktual di lingkungan proyek.
   - File `.env` bersifat privat dan rahasia karena memuat kredensial sensitif pengguna (password database, API secret key, encryption key, token, kredensial mail, dll.).

2. **PRINSIP PANDUAN PENGGUNA (GUIDE ONLY):**
   - Jika ada variabel lingkungan yang perlu ditambahkan, diperbarui, atau disesuaikan, Agent **HANYA BOLEH MEMANDU** pengguna secara interaktif.
   - Agent hanya boleh merujuk ke template publik [`.env.example`](./.env.example) atau bagian konfigurasi di [`OPS.md`](./OPS.md).
   - Berikan instruksi yang jelas kepada pengguna mengenai:
     - Nama variabel yang perlu diubah (misal `DB_PASSWORD`, `FORECASTING_API_URL`).
     - Format nilai yang diharapkan.
     - Contoh isian yang aman tanpa meminta pengguna membagikan nilai rahasianya kepada Agent.

3. **LARANGAN MENAMPILKAN ISI `.env` DI TERMINAL MAUPUN LOG:**
   - Jangan pernah membuat skrip atau menjalankan perintah terminal yang mencetak isi atau variabel rahasia dari `.env` ke console / standard output.
   - Jangan pernah mengekspor atau menduplikasi file `.env` ke file publik, log, atau artefak.

---

## 🛠️ Aturan Pengembangan Kode & Arsitektur
- **Dokumentasi Terpusat:** Selalu sinkronkan perubahan dengan 5 file dokumentasi inti (`README.md`, `DESIGN.md`, `UI_GUIDE.md`, `OPS.md`, `TASKS.md`).
- **Domain IKM Marmer Tulungagung:** Pertahankan keselarasan dengan alur proses riil dan data empiris IKM (UD Cahaya Onix & UD Putra Abadi).
