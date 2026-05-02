# GeniusEdu System

Sistem Informasi Manajemen Bimbingan Belajar (Genius Education) menggunakan Laravel 12 & Docker.

## Persyaratan Sistem
- [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- Git

## Cara Install (Setup Awal)

Jika Anda baru melakukan `git clone`, ikuti langkah berikut:

1. **Clone Repositori**
   ```bash
   git clone [URL_REPO_ANDA]
   cd sistem_informasi
   ```

2. **Siapkan File Environment**
   Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```

3. **Build & Jalankan Docker Container**
   Pastikan Docker Desktop sudah berjalan, lalu jalankan:
   ```bash
   docker-compose up -d --build
   ```

4. **Install Dependencies (Composer)**
   Jalankan composer install di dalam container `app`:
   ```bash
   docker-compose exec app composer install
   ```

5. **Generate App Key**
   ```bash
   docker-compose exec app php artisan key:generate
   ```

6. **Migrate & Seed Database**
   Lakukan migrasi database dan isi data awal (seed):
   ```bash
   docker-compose exec app php artisan migrate --seed
   ```

7. **Install Node Modules & Build Assets**
   ```bash
   docker-compose exec app npm install
   docker-compose exec app npm run build
   ```

8. **Akses Aplikasi**
   Buka browser dan akses: `http://localhost:8080`

---

## Perintah Penting Lainnya

- **Menghentikan Container**: `docker-compose down`
- **Menjalankan Container**: `docker-compose up -d`
- **Melihat Log Nginx**: `docker-compose logs -f nginx`
- **Masuk ke Terminal Container**: `docker-compose exec app sh`
- **Clear Cache (jika ada error tampilan)**:
  ```bash
  docker-compose exec app php artisan optimize:clear
  ```

## Catatan untuk Developer
Jika Anda melakukan perubahan pada file `.blade.php` atau `.php` dan tidak langsung muncul, pastikan OpCache sudah di-restart atau diclear (sudah dikonfigurasi otomatis di `docker/php/opcache.ini` untuk mendeteksi perubahan).
