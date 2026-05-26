# Panduan Proyek — Sistem Informasi Bimbel

Dokumen ini ditujukan untuk: dosen penguji dan anggota tim. Berisi penjelasan lengkap tentang tujuan proyek, arsitektur, cara setup, alur kerja pengembangan, cara menambahkan fitur, pengujian, dan troubleshooting.

1) Ringkasan Proyek
- Nama: Sistem Informasi Bimbel
- Tujuan: Sistem manajemen bimbingan belajar termasuk pendaftaran, jadwal, CBT, keuangan, dan laporan.
- Stack utama: Laravel (PHP 8.2, laravel/framework ^12), Vite, Tailwind CSS, Pest untuk testing.

2) Tim & Peran
- Project Owner / Koordinator: Nama (hubungi via email/WA)
- Backend: bertanggung jawab untuk `app/`, `database/`, API
- Frontend: bertanggung jawab untuk `resources/`, styling, integrasi Vite
- QA / Testing: menulis dan menjalankan test di `tests/`
- DevOps (opsional): deployment, konfigurasi Docker/nginx

3) Struktur & Alur Navigasi Cepat
- Lihat [docs/STRUCTURE.md](STRUCTURE.md) untuk peta direktori lengkap.
- File entrypoint: `public/index.php` dan perintah developer utama `artisan`.

4) Setup Lingkungan (Langkah untuk anggota tim)

- Prasyarat: PHP 8.2+, Composer, Node.js & npm, database (MySQL/Postgres/SQLite), Git
- Langkah:

```bash
git clone <repo-url>
cd sistem_informasi
composer install
cp .env.example .env
php artisan key:generate
# atur konfigurasi DB di .env
php artisan migrate --seed
npm install
npm run dev
php artisan serve
```

Catatan: Jika menggunakan Laragon, sesuaikan virtual host dan paths.

5) Konvensi Kode & Standar
- PHP: PSR-12. Gunakan `laravel/pint` untuk format.
- Penamaan: Model singular (`User`), controller `PascalCaseController`, migrations snake_case.
- Routes: pisahkan rute domain di `routes/` (mis. `routes/admin.php`) bila perlu.

6) Menambahkan Fitur — Langkah Praktis (contoh: fitur Pendaftaran)

a. Buat migration dan model:

```bash
php artisan make:model Pendaftaran -m
```

b. Buat controller dan request validation:

```bash
php artisan make:controller PendaftaranController --resource
php artisan make:request StorePendaftaranRequest
```

c. Tambah route di `routes/web.php`:

```php
Route::resource('pendaftaran', PendaftaranController::class)->middleware('auth');
```

d. Buat view di `resources/views/pendaftaran/` (index, create, edit)
e. Tambah seeder jika butuh data awal dan jalankan `php artisan db:seed --class=PendaftaranSeeder`

7) Testing
- Jalankan semua test:

```bash
composer test
// atau
php artisan test
```

- Buat test baru dengan Pest/PhpUnit di `tests/Feature` atau `tests/Unit`.

8) Deployment singkat
- Build assets: `npm run build`
- Taruh aplikasi di server PHP + nginx/Apache, set `APP_ENV=production` dan jalankan migrasi.
- Pastikan `storage/` dan `bootstrap/cache` writable.

9) Troubleshooting umum
- Error koneksi DB: periksa `.env` dan jalankan `php artisan migrate` untuk melihat pesan error.
- Error 500: cek `storage/logs/laravel.log` untuk stack trace.
- Missing dependency: jalankan `composer install` / `npm install`.

10) Dokumentasi & Referensi kode
- Dokumentasi struktur: `docs/STRUCTURE.md`
- Dokumen per-folder: `docs/app.md`, `docs/resources.md`, `docs/database.md`, `docs/routes.md`

11) Tips presentasi ke dosen
- Siapkan demo: jalankan `php artisan serve` dan tunjukkan fitur utama (pendaftaran, login, pembuatan ujian CBT, laporan keuangan).
- Siapkan slide singkat: tujuan, fitur, arsitektur, flow user, pembagian tugas anggota.
- Sertakan catatan tentang area yang belum lengkap dan rencana pengembangan lanjutan.

12) Lampiran — Perintah Berguna

```bash
# Setup sekali (script composer 'setup')
composer run setup

# Jalankan dev environment (dengan concurrently di package.json)
composer run dev

# Menjalankan Pint (format)
./vendor/bin/pint

# Menjalankan larastan/analysis
./vendor/bin/phpstan analyse
```

