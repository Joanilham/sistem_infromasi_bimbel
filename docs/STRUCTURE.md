# Struktur Proyek (Lengkap)

Berisi gambaran struktur direktori dan penjelasan singkat setiap bagian. Sumber: `struktur.txt`.

Folder Utama (Root):

├── `app/`                      --> Logika utama aplikasi (Backend Laravel)
│   ├── `Console/`              --> Command artisan custom (benchmark, job pruning, dll.)
│   ├── `Http/`                 --> HTTP Layer (Controllers, Middleware, Requests)
│   │   ├── `Controllers/`      --> Controller untuk fitur (Absensi, Guru, Keuangan, Siswa, dll.)
│   │   ├── `Middleware/`       --> Filter request HTTP (EnsureCorrectRole, dll.)
│   │   └── `Requests/`         --> Form Request Validation
│   ├── `Listeners/`            --> Event Listeners
│   ├── `Models/`               --> Eloquent Models (representasi tabel DB)
│   ├── `Observers/`            --> Model Observers
│   ├── `Providers/`            --> Service Providers (inisialisasi layanan)
│   ├── `Services/`             --> Layanan / integrasi pihak ketiga (WhatsAppService, CacheService)
│   └── `Traits/`               --> Traits dan kode reusable (Auditable, ExportsExcel, dll.)
│
├── `bootstrap/`                --> Bootstrap framework & cache (bootstrap/app.php)
│
├── `config/`                   --> Konfigurasi aplikasi (database, mail, queue, dll.)
│
├── `database/`                 --> Manajemen DB
│   ├── `factories/`            --> Database factories untuk testing
│   ├── `migrations/`           --> Migration files
│   └── `seeders/`              --> Database seeders
│
├── `docker/`                   --> Konfigurasi Docker (nginx, php, dll.)
│
├── `public/`                   --> Aset publik (index.php, build, images)
│
├── `resources/`                --> Frontend sumber
│   ├── `css/`                  --> Styling (Tailwind CSS)
│   ├── `js/`                   --> Javascript (Axios, Alpine, dll.)
│   └── `views/`                --> Blade templates
│       ├── `admin/`            --> Dashboard & admin UI
│       ├── `auth/`             --> Login, reset password
│       ├── `components/`       --> Komponen UI reusable
│       ├── `errors/`           --> Error pages (404, 500)
│       ├── `guru/`             --> Fitur guru (CBT, bank soal)
│       ├── `keuangan/`         --> Fitur keuangan (pemasukan, pengeluaran)
│       ├── `layouts/`          --> Layout halaman
│       ├── `pendaftaran/`      --> Pendaftaran siswa baru
│       ├── `siswa/`            --> Fitur siswa (CBT, QR, jadwal)
│       ├── `welcome.blade.php` --> Halaman utama
│       └── `dashboard.blade.php`--> Dashboard utama
│
├── `routes/`                   --> Web & console routes
│   ├── `web.php`               --> Rute web (admin, guru, siswa, keuangan)
│   └── `console.php`           --> Rute CLI
│
├── `storage/`                  --> Penyimpanan writable (logs, cache, uploads)
│
├── `tests/`                    --> Unit & Feature tests (Pest/PhpUnit)
│
└── `.env`                      --> Konfigurasi environment lokal (JANGAN commit ke Git)

File penting di root:

- `composer.json`               : dependency PHP (Laravel, Pest, Larastan, dll.)
- `package.json`                : dependency JS (Vite, Tailwind, axios)
- `artisan`                     : CLI helper untuk artisan commands
- `vite.config.js`              : konfigurasi Vite

Tips cepat:

- Log: `storage/logs/laravel.log`
- Environment: pastikan `.env` terisi dengan benar dan tidak di-commit
- Permission: pastikan `storage/` dan `bootstrap/cache` dapat ditulis oleh webserver
- Menjalankan setup awal:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm run dev
php artisan serve
```

