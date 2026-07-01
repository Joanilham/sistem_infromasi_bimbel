# Panduan Proyek — Sistem Informasi Bimbel

Dokumen ini ditujukan untuk dosen penguji dan anggota tim. Berisi penjelasan mendalam tentang tujuan proyek, arsitektur, cara setup, alur kerja pengembangan, dan pengujian.

## 1) Ringkasan Proyek
- **Nama**: Sistem Informasi Bimbel (Genius Education)
- **Tujuan**: Sistem manajemen bimbingan belajar terpadu (pendaftaran, jadwal, CBT, keuangan, dan pelaporan).
- **Arsitektur**: Containerized Micro-services (Docker).
- **Stack Utama**: Laravel 13 (Octane + RoadRunner), PHP 8.2, Nginx WAF (ModSecurity), Redis, MySQL 8.0, Vite, Tailwind CSS v4.

## 2) Tim & Peran
- **Project Owner / Koordinator**: (Isi nama dan kontak)
- **Backend & Infrastruktur**: Bertanggung jawab untuk Laravel API, Docker, Nginx, dan Queue Worker.
- **Frontend**: Bertanggung jawab untuk desain UI/UX, integrasi Tailwind v4, dan Vite.
- **QA / Testing**: Menulis dan menjalankan pengujian (Pest).

## 3) Setup Lingkungan (Untuk Anggota Tim)

Proyek ini **wajib** dijalankan menggunakan Docker agar semua environment (Nginx, Redis, MySQL, Octane) terisolasi dan konsisten.

**Langkah-Langkah:**
```bash
# 1. Kloning repositori
git clone https://github.com/TRPL-JBI/pbl-2026-l6-tim-7
cd sistem_informasi

# 2. Menjalankan Docker Containers di background
sudo docker compose up -d

# 3. Setup Database & Seeding Data Awal
# Ini akan membuat struktur tabel dan mengisi data dummy (seperti akun superadmin)
sudo docker compose exec app php artisan migrate:fresh --seed
```
*Catatan: Anda tidak perlu menginstal PHP atau Node.js di komputer host Anda secara manual, karena semua dependensi (Composer & NPM) sudah dibuild di dalam container Docker!*

## 4) Konvensi Kode & Standar
- **PHP**: Mengikuti PSR-12. Gunakan `laravel/pint` untuk format otomatis.
- **Penamaan**: Model singular (`User`), controller `PascalCaseController`, migrations `snake_case`.
- **Eksekusi Perintah**: Karena menggunakan Docker, setiap perintah artisan harus dijalankan di dalam container `app`. Contoh:
  `sudo docker compose exec app php artisan make:controller NamaController`

## 5) Testing & Troubleshooting Umum
- **Menjalankan Test**:
  `sudo docker compose exec app php artisan test`
- **Error 502 Bad Gateway (Nginx)**:
  Berarti container `app` (Laravel Octane) belum siap atau crash. Cek logs dengan `sudo docker compose logs app`.
- **Masalah Permission (Failed to open stream)**:
  Sering terjadi jika container gagal menulis ke folder cache/log. Jalankan di komputer Anda:
  `chmod -R 777 storage bootstrap/cache`
- **Melihat Log Aplikasi**:
  `sudo docker compose exec app tail -f storage/logs/laravel.log`

## 6) Tips Presentasi ke Dosen
- **Persiapan Demo**: Pastikan Docker sudah berjalan dengan `sudo docker compose up -d`. Tunjukkan bahwa aplikasi menggunakan Nginx WAF dan Laravel Octane untuk keamanan & kecepatan maksimal.
- **Buka Akses Web**: Akses aplikasi di `http://localhost:8080`. Jika harus dipresentasikan secara jarak jauh, gunakan perintah `ngrok http 8080`.
- **Siapkan slide singkat**: Bahas tentang tujuan, fitur unggulan (WAF, Octane, Multi-role), flow user, dan pembagian tugas yang jelas antar anggota kelompok.
- **Akun Demo**: Jangan lupa menyiapkan akun demo seperti `superadmin@admin.com` dengan password yang mudah diingat agar saat presentasi tidak terjadi kendala login.

## 7) Lampiran — Perintah Docker Berguna
```bash
# Melihat status seluruh layanan
sudo docker compose ps

# Mematikan seluruh container
sudo docker compose down

# Merestart hanya aplikasi Laravel (Misal: setelah merubah .env)
sudo docker compose restart app

# Masuk ke dalam terminal container app
sudo docker compose exec app bash
```

