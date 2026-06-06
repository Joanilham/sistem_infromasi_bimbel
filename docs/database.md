# `database/` — Rincian

Folder `database/` menangani skema dan data awal.

- `migrations/` : File migrasi untuk pembuatan tabel (gunakan `php artisan make:migration`).
- `seeders/` : Seeder untuk data awal (contoh: `DatabaseSeeder`, `AdminSeeder`).
- `factories/` : Model factories untuk testing (Pest/PhpUnit). Buat faker data di sini.

Praktik baik:

- Jangan ubah migrasi yang sudah dijalankan pada environment publik; buat migrasi baru.
- Seeders dapat dijalankan via `php artisan db:seed --class=AdminSeeder`.
