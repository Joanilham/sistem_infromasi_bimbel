# `routes/` — Rincian

Folder `routes/` berisi definisi rute aplikasi.

- `web.php` : Rute web standar. Pisahkan rute per domain jika perlu (ex: `routes/admin.php`).
- `console.php` : Registrasi perintah artisan berbasis Closure.

Contoh: Memisahkan rute modul:

```php
// di routes/web.php
require base_path('routes/admin.php');
require base_path('routes/guru.php');
```

Tips keamanan:

- Gunakan middleware `auth` dan role-based middleware untuk mengamankan rute sensitif.
- Validasi semua input pada `Request` classes.
