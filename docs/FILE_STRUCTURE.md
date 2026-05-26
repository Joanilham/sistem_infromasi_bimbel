# File Structure — Daftar File Penting

Dokumen ini merinci file-file penting pada root proyek dan per-folder, beserta fungsi singkat tiap file.

Root files:

- `artisan` : CLI Laravel.
- `composer.json` : Dependency PHP dan scripts composer.
- `package.json` : Dependency JS (Vite, Tailwind, dll.).
- `vite.config.js` : Konfigurasi build frontend.
- `.env.example` / `.env` : Konfigurasi environment.
- `README.md` : Tautan cepat ke dokumentasi.
- `docs/PROJECT_GUIDE.md` : Panduan lengkap untuk dosen dan tim.
- `docs/STRUCTURE.md` : Gambaran struktur direktori.
- `docs/FILE_STRUCTURE.md` : (file ini) penjelasan file-file penting.

Penting di `app/`:

- `app/Models/` : Model Eloquent per tabel (mis: `User.php`, `PesertaDidik.php`).
- `app/Http/Controllers/` : Controller pengatur request/response.
- `app/Http/Requests/` : Request validation classes.
- `app/Services/` : Integrasi eksternal (WhatsAppService.php, CacheService.php).

Penting di `config/`:

- `config/app.php`, `config/database.php`, `config/mail.php` : konfigurasi aplikasi.

Penting di `database/`:

- `database/migrations/` : file migrasi (skema tabel).
- `database/seeders/DatabaseSeeder.php` : seeder utama.
- `database/factories/` : factories untuk testing.

Penting di `public/`:

- `public/index.php` : entry point HTTP.
- `public/build/` : hasil build assets Vite.

Penting di `resources/`:

- `resources/views/` : Blade templates (dashboard, auth, admin, siswa, guru).
- `resources/css/` : sumber styling (Tailwind).
- `resources/js/` : sumber JS (axios, alpine, entry JS untuk Vite).

Penting di `routes/`:

- `routes/web.php` : rute web utama.
- `routes/console.php` : definisi perintah artisan berbasis Closure.

Penting di `storage/`:

- `storage/logs/laravel.log` : file log; pertama dicek saat error.

Penting di `tests/`:

- `tests/Feature/` dan `tests/Unit/` : test automated.

Tips penjelasan singkat per file:

- Saat memperkenalkan file kepada dosen, tunjukkan contoh isi file model (`app/Models/User.php`), satu controller (`app/Http/Controllers/Auth/LoginController.php` atau sejenis), dan satu migration dari `database/migrations/`.
- Jelaskan alur: request -> route (`routes/web.php`) -> controller -> service/model -> view (blade) atau JSON response.

Butuh daftar file lengkap (rekursif) atau ekspor ke teks/CSV/PDF? Beri tahu saya format yang diinginkan, dan saya akan membuatkannya.
