# 🎓 GeniusEdu - Sistem Informasi Manajemen Bimbel

GeniusEdu adalah platform manajemen Bimbingan Belajar modern yang dirancang untuk mengelola operasional akademik, administrasi, dan pelaksanaan ujian secara terintegrasi. Dibangun dengan **Laravel 11**, sistem ini mengedepankan performa, kemudahan penggunaan, dan arsitektur yang bersih.

---

## 🚀 Fitur Utama

### 1. 🛡️ Manajemen Administrator & Konteks
*   **Multi-Konteks**: Mendukung pengelolaan data berdasarkan **Kantor** dan **Periode** aktif.
*   **Role Based Access Control**: Pemisahan hak akses yang ketat antara Administrator, Staff, Guru, dan Siswa.

### 2. 📚 Akademik & Penjadwalan
*   **Manajemen Jadwal (S3-F4)**: Pengaturan slot jadwal (Hari, Jam, Guru, Mapel, Rombel) dengan deteksi konflik guru.
*   **Kelompok Belajar**: Pengelolaan rombel/kelas secara dinamis.
*   **Paket Bimbingan**: Konfigurasi paket belajar siswa.

### 3. 📝 Computer Based Test (CBT)
*   **Bank Soal**: Pengelolaan soal berdasarkan mata pelajaran dan bab.
*   **Pelaksanaan Ujian**: Antarmuka ujian yang intuitif untuk siswa dengan fitur anti-cheat (log blur detection).
*   **Monitoring Real-time**: Guru dapat memantau progres siswa saat ujian berlangsung.

### 4. 👤 Dashboard Siswa & Guru
*   **Siswa**: Lihat jadwal, riwayat nilai, dan pelaksanaan ujian online.
*   **Guru**: Kelola bank soal, jadwal mengajar, dan monitoring ujian.

### 5. 🕒 Absensi & Operasional
*   **QR Code Attendance**: Absensi siswa menggunakan QR Code dinamis.
*   **Laporan Absensi**: Rekapitulasi kehadiran harian dan bulanan.

---

## 🛠️ Tech Stack

*   **Framework**: [Laravel 11](https://laravel.com/)
*   **Frontend**: [Blade Templates](https://laravel.com/docs/11.x/blade), [Tailwind CSS](https://tailwindcss.com/), [Alpine.js](https://alpinejs.dev/)
*   **Database**: MySQL 8.0
*   **Infrastructure**: Docker (Nginx, PHP-FPM, MySQL, Redis)

---

## 📦 Instalasi (Docker)

Pastikan Anda sudah menginstal **Docker Desktop** dan **Git** di mesin Anda.

1.  **Clone Repositori**
    ```bash
    git clone https://github.com/Joanilham/sistem_infromasi_bimbel.git
    cd sistem_informasi
    ```

2.  **Siapkan Environment**
    ```bash
    cp .env.example .env
    ```

3.  **Jalankan Container**
    ```bash
    docker-compose up -d --build
    ```

4.  **Setup Aplikasi**
    ```bash
    docker-compose exec app composer install
    docker-compose exec app php artisan key:generate
    docker-compose exec app php artisan migrate --seed
    docker-compose exec app npm install
    docker-compose exec app npm run build
    ```

5.  **Akses Aplikasi**
    Buka [http://localhost:8080](http://localhost:8080) di browser Anda.

---

## 🔑 Akun Default (Seeder)

| Role | Username / Email | Password |
| :--- | :--- | :--- |
| **Administrator** | `admin@admin.com` | `password` |
| **Staff** | `staff@staff.com` | `password` |

---

## 💻 Perintah Pengembangan

| Perintah | Deskripsi |
| :--- | :--- |
| `docker-compose up -d` | Menjalankan sistem di background |
| `docker-compose down` | Menghentikan semua container |
| `docker-compose exec app php artisan ...` | Menjalankan perintah artisan |
| `docker-compose exec app npm run dev` | Menjalankan Vite dev server |
| `docker-compose logs -f app` | Melihat log aplikasi secara real-time |

---

## 📝 Roadmap & Sprint
Informasi detail mengenai progres pengembangan dapat dilihat pada file berikut:
*   [Sprint Selesai](./Sprint_selesai.md)
*   [Sprint 4 (Ongoing)](./Sprint_4.md)
*   [Spesifikasi Fitur](./sprint-spec.md)

---
Developed with ❤️ by **Antigravity AI** for **Genius Education**.
