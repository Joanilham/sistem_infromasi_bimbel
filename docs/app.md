# `app/` — Rincian

Folder `app/` menyimpan logika aplikasi utama. Struktur dan penjelasan bagian-bagian penting:

- `Console/` : Perintah artisan khusus. Letakkan perintah CLI yang Anda buat di sini.
- `Http/Controllers/` : Semua controller HTTP. Contoh controller: `AbsensiController`, `PembayaranController`.
- `Http/Middleware/` : Middleware untuk memfilter request (role checking, context scoping).
- `Http/Requests/` : Form Request classes untuk validasi input. Gunakan `php artisan make:request`.
- `Listeners/` : Event listeners yang merespon event (ex: after-payment listener).
- `Models/` : Eloquent models untuk setiap tabel. Nama kelas biasanya singular (contoh `User`, `PesertaDidik`).
- `Observers/` : Observers untuk menangani hook model (created, updated, deleted).
- `Providers/` : Service providers untuk binding service container, event listener registration.
- `Services/` : Layanan yang mengenkapsulasi logika kompleks atau integrasi eksternal (WhatsApp API, caching layer).
- `Traits/` : Traits yang dipakai lintas model/kelas (ex: `Auditable`, `HandlesImageUpload`).

Tips pengembangan:

- Ikuti PSR-12 coding standard; gunakan `composer require --dev laravel/pint` lalu jalankan `./vendor/bin/pint`.
- Untuk menambahkan fitur baru, buat `Controller`, `Request`, `Model`, dan register route pada `routes/web.php`.
