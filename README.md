# Dompet Arif 💰

Aplikasi web dompet digital pribadi (*personal finance tracker*) berbasis **Laravel 11+** dan **PHP 8.2+**. Dirancang dengan arsitektur modern, tampilan *mobile-first* elegan (Tailwind CSS), fitur pengenalan suara Bahasa Indonesia (*Web Speech API*), kompresi foto struk berbasis browser (*HTML5 Canvas*), layanan *ReceiptReader* (Null & Vision AI), sistem anggaran bulanan, serta pengujian terisolasi penuh.

---

## 🚀 Fitur Utama

1. **Dashboard & Saldo Utama**:
   - Kartu saldo bergradasi hijau-teal premium (`#0f9d78` ke `#0b6e86`) dengan kalkulasi otomatis: `Total Pemasukan - Total Pengeluaran`.
   - Ringkasan mutasi masuk dan keluar pada bulan berjalan.
   - Format mata uang Rupiah Indonesia baku (`Rp25.000`) melalui Blade Directive `@rupiah(...)` dan `CurrencyHelper`.

2. **Catat Suara Cerdas (Web Speech API - `id-ID`)**:
   - Merekam suara langsung di browser dengan indikator visual berkedip merah pulsing.
   - Parser Bahasa Indonesia untuk nominal: mendukung format angka polos, titik (`25.000`), satuan `ribu`, `rb`, `k`, `juta`, `jt`.
   - Deteksi jenis transaksi otomatis: kata kunci pemasukan (*gaji, terima, masuk, tambah saldo, top up, bonus, dapat*) diarahkan ke **Pemasukan**, lainnya ke **Pengeluaran**.
   - Tebakan kategori otomatis: makanan/minuman, transportasi (bensin, ojek), belanja, tagihan (listrik/wifi), hiburan (nonton/game), dll.
   - Jika nominal terdeteksi, langsung tersimpan ke server via `POST /transactions` (`source: voice`); jika nominal belum jelas, membuka form modal dengan keterangan otomatis terisi.

3. **Foto Struk Belanja & Kompresi Client-Side**:
   - Terhubung langsung ke kamera ponsel atau unggah file struk (`accept="image/*" capture="environment"`).
   - Kompresi canvas client-side sebelum diunggah: sisi terpanjang maksimal 1200px dan kompresi JPEG kualitas 0.7 untuk menghemat kuota dan memangkas waktu unggah.
   - Arsitektur `App\Services\ReceiptReader`:
     - `NullReceiptReader`: Implementasi bawaan (pengguna mengonfirmasi dan mengisi total manual).
     - `AiReceiptReader`: Integrasi vision AI (Google Gemini / OpenAI Vision) server-side via API key di `.env` tanpa bocor ke sisi klien.
   - Berkas struk disimpan aman di `storage/app/public/receipts/{user_id}/` dengan thumbnail dan pratinjau modal *lightbox*.

4. **Monitoring Anggaran (Budgeting) & Peringatan Dini**:
   - Pengaturan batas anggaran pengeluaran bulanan per kategori.
   - Indikator bar persentase dan peringatan visual saat pemakaian melampaui **80%**.

5. **Riwayat, Filter, & Ekspor CSV**:
   - Paginasi riwayat transaksi dengan badge sumber (*manual*, *voice*, *camera*).
   - Filter mutasi berdasarkan bulan (`YYYY-MM`) dan kategori.
   - Ekspor data transaksi ke file CSV berstandar UTF-8 BOM untuk Microsoft Excel.

6. **Keamanan & Kualitas**:
   - Otorisasi ketat via `TransactionPolicy` (pengguna tidak dapat melihat, mengedit, atau menghapus data milik pengguna lain).
   - Validasi data menggunakan `StoreTransactionRequest` & `UpdateTransactionRequest`.
   - CSRF token otomatis pada setiap *fetch/AJAX request*.

---

## 📋 Prasyarat Lingkungan

- **PHP**: 8.2 atau 8.3+ (Ekstensi aktif: `mbstring`, `openssl`, `pdo_sqlite`, `fileinfo`, `gd`, `curl`, `xml`, `zip`)
- **Composer**: 2.x
- **Node.js**: 20+ / 22+ & **NPM**: 10+
- **Database**: SQLite (bawaan) atau MySQL / PostgreSQL

---

## ⚙️ Panduan Instalasi & Setup Lokal

1. **Clone atau Masuk ke Direktori Proyek**:
   ```bash
   cd dompet-arif
   ```

2. **Pasang Dependensi Backend**:
   ```bash
   composer install
   ```

3. **Konfigurasi Environment (`.env`)**:
   Salin berkas template jika belum ada:
   ```bash
   cp .env.example .env
   ```
   Pastikan pengaturan utama:
   ```env
   APP_NAME="Dompet Arif"
   APP_LOCALE=id
   APP_TIMEZONE=Asia/Jakarta
   DB_CONNECTION=sqlite
   RECEIPT_READER=null
   ```
   *(Opsional jika menggunakan MySQL, cukup ubah `DB_CONNECTION=mysql` dan sesuaikan kredensial).*

4. **Generate Key & Migrasi Database**:
   ```bash
   php artisan key:generate
   php artisan migrate --seed
   php artisan storage:link
   ```

5. **Kompilasi Aset Frontend**:
   ```bash
   npm install
   npm run build
   ```

6. **Jalankan Aplikasi Web**:
   ```bash
   php artisan serve
   ```
   Buka peramban di [http://127.0.0.1:8000](http://127.0.0.1:8000).

---

## 👤 Akun Uji Coba (Seeder Demo)

Database telah disiapkan dengan data contoh realistis:
- **Email**: `arif@dompetarif.test`
- **Kata Sandi**: `password`

---

## 📱 Cara Menguji Mikrofon & Kamera dari HP (HTTPS Tunnel)

Fitur Web Speech API (`SpeechRecognition`) dan akses kamera langsung dari peramban ponsel memerlukan koneksi aman (**HTTPS** atau **localhost**).

Untuk menguji langsung dari HP Anda melalui jaringan lokal:

### Opsi 1: Menggunakan Cloudflare Tunnel (Gratis & Cepat)
1. Unduh `cloudflared` (atau jalankan via `winget install Cloudflare.cloudflared`).
2. Jalankan:
   ```bash
   cloudflared tunnel --url http://localhost:8000
   ```
3. Salin URL HTTPS yang dihasilkan (misal `https://xxx.trycloudflare.com`) dan buka di browser Google Chrome pada HP Anda.

### Opsi 2: Menggunakan Ngrok
1. Jalankan server Laravel: `php artisan serve --port=8000`
2. Jalankan ngrok:
   ```bash
   ngrok http 8000
   ```
3. Buka tautan HTTPS yang diberikan pada HP Anda. Berikan izin mikrofon dan kamera saat diminta.

---

## 🧪 Menjalankan Pengujian (Automated Feature Tests)

Semua pengujian telah ditulis untuk menguji CRUD transaksi, isolasi data antar-pengguna, kalkulasi saldo, upload struk, ekspor CSV, dan validasi form.

Jalankan perintah pengujian:
```bash
php artisan test
```
*Hasil: 38 tests, 100 assertions — 100% Passed (Hijau).*

Untuk merapikan standar kode menggunakan Laravel Pint:
```bash
vendor/bin/pint
```

---

## 🌐 Panduan Deployment ke Production

1. **Server Requirements**:
   - VPS (Ubuntu 22.04 / 24.04 LTS) atau Platform as a Service (Fly.io, Railway, DigitalOcean).
   - Nginx / Apache + PHP 8.3-FPM + Composer.
   - Sertifikat SSL gratis dari Let's Encrypt (`certbot`).

2. **Langkah Produksi**:
   ```bash
   composer install --no-dev --optimize-autoloader
   npm run build
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan migrate --force
   php artisan storage:link
   ```

---

## 📊 Ringkasan Status & Saran Pengembangan

### Fitur yang Selesai
- [x] Autentikasi lengkap (Register, Login, Remember Me, Logout) menggunakan Laravel Breeze dengan teks Bahasa Indonesia.
- [x] Desain mobile-first responsif (lebar maks ~480px di tengah layar), mode terang/gelap otomatis.
- [x] Kartu saldo utama gradasi hijau-teal dengan ringkasan mutasi bulanan.
- [x] Pencatatan manual, tombol Suara berkedip merah (Web Speech API + parser cerdas kata kunci), dan Foto Struk (kompresi Canvas 1200px 0.7 JPEG).
- [x] Arsitektur `ReceiptReader` modular (`NullReceiptReader` bawaan & `AiReceiptReader` Vision AI).
- [x] Anggaran bulanan per kategori dan peringatan visual jika pemakaian melampaui 80%.
- [x] Modal saldo awal untuk pengguna baru.
- [x] Paginasi riwayat transaksi, filter bulan & kategori, dan ekspor CSV.
- [x] Otorisasi `TransactionPolicy`, pengujian otomatis 38 tests hijau, serta formatting kode dengan Pint.

### Batasan Saat Ini & Saran Pengembangan Lanjutan
1. **PWA (Progressive Web App)**: Tambahkan service worker dan `manifest.json` agar aplikasi dapat dipasang (*Add to Home Screen*) di Android & iOS layaknya aplikasi native.
2. **Kategori Kustom**: Izinkan pengguna menambahkan kategori pengeluaran dan pemasukan sendiri di luar kategori standar.
3. **Grafik Visual Interaktif**: Integrasikan Chart.js atau ApexCharts untuk melihat tren pengeluaran harian dan mingguan.
4. **Notifikasi Telegram / WhatsApp**: Integrasi pengingat saldo mingguan atau peringatan ketika batas anggaran terlampaui.
