# Sistem Informasi Bimbingan Belajar (Genius Education Platform)

Sistem Informasi Bimbingan Belajar adalah platform manajemen edukasi berbasis web yang dirancang khusus untuk mempermudah operasional tempat bimbingan belajar (bimbel). Sistem ini menggunakan arsitektur containerized dengan performa tinggi yang disajikan melalui Laravel Octane dan Nginx Reverse Proxy.

## 🚀 Fitur-Fitur Tersedia

- **Manajemen Autentikasi & Otorisasi**: Login aman dengan manajemen akses multi-role (misal: Super Admin, Admin, Siswa, Tentor).
- **Manajemen Pengguna**: Pendaftaran, pengelolaan, dan penugasan pengguna.
- **Sistem Periode & Penjadwalan**: Mendukung berbagai siklus tahun ajaran atau periode belajar (PeriodeSeeder).
- **Keamanan Lanjut**: Dilengkapi dengan ModSecurity Web Application Firewall (WAF) dari Nginx untuk menangkal serangan eksternal.
- **Antarmuka Responsif**: UI modern menggunakan TailwindCSS v4.
- **Pemrosesan Latar Belakang**: Manajemen antrean tugas dan cron scheduler berjalan otomatis (Redis & Queue Worker).

## 🛠 Tech Stack & Framework

- **Backend**: Laravel 13, PHP 8.2 (menjalankan Laravel Octane dengan server RoadRunner)
- **Frontend**: Vite 7, TailwindCSS 4 (Vanilla JS & Blade Templates)
- **Database**: MySQL 8.0
- **Caching & Queue**: Redis (Alpine)
- **Web Server & Security**: Nginx 1.30.3 (dengan OWASP ModSecurity)
- **DevOps**: Docker & Docker Compose

## 📂 Struktur Folder Penting

- `/app` - Logika utama aplikasi (Controllers, Models, Middleware).
- `/docker` - Berisi file konfigurasi kustom untuk Nginx, PHP, dan MySQL.
- `/public` - Aset statis dan hasil build Vite (`public/build`).
- `/database/seeders` - Data dummy dan konfigurasi awal (seperti Super Admin dan Periode).
- `/docs` - Berisi dokumentasi arsitektur aplikasi lebih lanjut.

## ⚙️ Panduan Instalasi dan Menjalankan Aplikasi

Pastikan sistem Anda sudah terinstal **Docker** dan **Docker Compose**.

1. **Clone dan Masuk ke Direktori Proyek**
   ```bash
   git clone https://github.com/TRPL-JBI/pbl-2026-l6-tim-7
   cd sistem_informasi
   ```

2. **Jalankan Aplikasi dengan Docker**
   Karena aplikasi ini sepenuhnya menggunakan container, Anda hanya perlu menjalankan:
   ```bash
   sudo docker compose up -d
   ```
   *Perintah ini akan secara otomatis mem-build image, menginstal dependensi Composer & NPM, serta menjalankan Nginx, App (RoadRunner), MySQL, Redis, Queue, dan Scheduler.*

3. **Inisialisasi Database (Migrasi & Seeder)**
   Setelah semua container berjalan (status `Up`), jalankan perintah ini untuk membangun tabel dan memasukkan data admin awal:
   ```bash
   sudo docker compose exec app php artisan migrate:fresh --seed
   ```

4. **Akses Aplikasi**
   Buka browser Anda dan akses aplikasi melalui:
   - URL Lokal: `http://localhost:8080`
   - URL Ngrok (jika menggunakan tunneling): `https://<domain-ngrok>.ngrok-free.dev`

## 👤 Informasi Akun Demo

Untuk masuk ke dalam sistem, gunakan kredensial bawaan berikut (hasil dari proses seeder):
- **Email**: `superadmin@admin.com`
- **Password**: `password` *(atau sesuai konfigurasi seeder Anda)*
- **Role**: Super Admin

## 👨‍💻 Anggota Kelompok / Tim Pengembang

- **Joan Ilham Dwi Putra** - [362458302077]
- **[Stefano Tesari Abur]** - [362458302014]
- **[Rizwar Ardian Pradana]** - [362458302076]
- **[Darius Bagaskara Josandro A.]** - [362458302074]

---
*Dokumentasi arsitektur lebih detail dapat dilihat di [docs/PROJECT_GUIDE.md](docs/PROJECT_GUIDE.md).*

## ⚖️ Lisensi

Hak Cipta (c) 2026 Joan Ilham Dwi Putra. Seluruh hak cipta dilindungi undang-undang.

Source code ini diunggah **murni untuk keperluan evaluasi akademik/penilaian tugas**. Dilarang keras menggunakan, menyalin, memodifikasi, atau mendistribusikan kode ini untuk tujuan komersial atau proyek lain tanpa izin tertulis dari pemegang hak cipta. Silakan baca file [LICENSE](LICENSE) untuk detail lebih lanjut.
