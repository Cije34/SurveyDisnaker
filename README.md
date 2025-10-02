
# Survey Disnaker

Survey Disnaker adalah aplikasi Laravel untuk mengelola jadwal kegiatan, peserta, mentor, penanggung jawab, serta distribusi dan pengumpulan survey. Proyek ini menggunakan Laravel 10, Tailwind melalui Vite, dan basis data MySQL.

![Desain database](docs/img/DsainDatabase.jpeg)

## Kebutuhan Sistem

- PHP 8.2+
- Composer 2+
- Node.js 18+ & NPM/Yarn (untuk asset build)
- MySQL/MariaDB
- Ekstensi PHP umum: `mbstring`, `openssl`, `pdo_mysql`, `json`, `curl`

## Langkah Instalasi

1. **Clone repository**
   ```bash
   git clone <repo-url> && cd SurveyDisnaker
   ```

2. **Salin berkas environment**
   ```bash
   cp .env.example .env
   ```
   Sesuaikan isi `.env` dengan kredensial basis data dan konfigurasi mail jika diperlukan.

3. **Install dependensi backend**
   ```bash
   composer install
   ```

4. **Generate application key**
   ```bash
   php artisan key:generate
   ```

5. **Jalankan migrasi dan seeder (opsional, jika disediakan)**
   ```bash
   php artisan migrate --seed
   ```

6. **Install dependensi frontend**
   ```bash
   npm install
   ```

7. **Build/Vite dev server**
   - Mode pengembangan: `npm run dev`
   - Build produksi: `npm run build`

8. **Jalankan aplikasi**
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui `http://127.0.0.1:8000` secara default.

## Perintah Penting Lainnya

- **Sync storage link**: `php artisan storage:link`
- **Queue worker (jika digunakan)**: `php artisan queue:work`
- **Pengujian**: `php artisan test`

## Struktur Direktori Utama

- `app/` — kode domain & controller Laravel
- `resources/views/` — tampilan Blade
- `resources/js`, `resources/css` — asset yang dibuild oleh Vite
- `database/migrations/` — schema DB
- `docs/` — dokumentasi tambahan, termasuk desain database

## Konvensi Import Data

Proyek mendukung import XLSX untuk peserta, mentor, dan penjab. Pastikan setiap file mengikuti header yang telah ditentukan. Proses import menggunakan upsert dengan kunci unik sesuai model (mis. email untuk mentor) sehingga file dapat diimpor ulang tanpa membuat duplikasi data.

## Lisensi

Silakan menambahkan informasi lisensi jika diperlukan.
