# Sistem Informasi Bimbingan Belajar & LMS Terpadu (Platform Nivora)

Aplikasi ini saya kembangkan untuk menjawab kebutuhan nyata operasional di tempat bimbingan belajar (bimbel). Masalah umum yang sering terjadi di lapangan adalah pembagian materi dan penugasan yang masih mengandalkan grup WhatsApp, sehingga modul gampang tertimbun chat dan guru kesulitan memantau siapa saja siswa yang benar-benar belajar sebelum kelas tatap muka dimulai. 

Sistem ini menggabungkan pengelolaan operasional bimbel dengan modul *Learning Management System* (LMS) dua arah. Dibangun menggunakan **Laravel 11** dan dijalankan di atas **Laravel Octane (RoadRunner)** dalam kontainer Docker/Podman, aplikasi ini dirancang agar tetap ringan, cepat, dan tidak mengalami perlambatan saat diakses serentak oleh puluhan siswa di jam belajar.

---

## 💡 Fitur Utama & Pertimbangan di Balik Pengembangannya

Setiap fitur yang dibangun di sistem ini berangkat dari kendala riil yang kami temukan saat uji coba:

### 1. Ekosistem LMS untuk Guru
* **Distribusi Materi & Penugasan:** Guru dapat mengunggah modul teori maupun tugas terstruktur yang otomatis dikelompokkan berdasarkan tingkatan kelas dan mata pelajaran yang diampu.
* **Tabel Pemantauan Bacaan Real-Time:** Guru bisa memantau secara transparan siapa saja siswa yang sudah membuka (*read*) atau mengunduh (*download*) modul materi, lengkap dengan catatan waktu akses dan persentase keterbacaan kelas.
* **Kenapa Pelacakan Dibuat Otomatis?** Kami sempat mempertimbangkan tombol manual *"Tandai Selesai"*, tetapi cara tersebut sering tidak efektif karena siswa cenderung langsung mengklik tombol demi status hijau tanpa membaca modulnya. Sistem ini mencatat keaktifan begitu halaman materi dibuka siswa.
* **Pemeriksaan Tugas & Input Nilai Langsung di Web:** Guru tidak perlu lagi mengunduh berkas jawaban siswa satu per satu ke komputer lokal. Dokumen jawaban bisa langsung dilihat di peramban, lalu guru dapat langsung mengisi nilai dan catatan koreksi di layar yang sama.
* **Tenggat Waktu Fleksibel:** Sistem otomatis menandai pengiriman yang terlambat tanpa memutus hak siswa untuk tetap menyetor tugasnya.

### 2. Portal Belajar Mandiri untuk Siswa
* **Katalog Modul yang Rapi:** Dasbor siswa menampilkan daftar materi per mata pelajaran dan indikator status tugas yang belum diselesaikan.
* **Penampil Dokumen Mandiri (In-Browser Viewer):** 
  * *Masalah yang diselesaikan:* Saat uji coba awal, membuka berkas modul di peramban sering memicu aplikasi downloader pihak ketiga (seperti IDM) yang langsung menyedot file ke penyimpanan lokal. Ini memberatkan siswa yang kuotanya terbatas atau memori ponselnya sudah hampir penuh.
  * *Solusi:* Modul (PDF, DOCX, PPTX) dialirkan sebagai aliran data biner (*binary stream*) langsung ke kanvas peramban. Siswa bisa langsung membaca materi di layar tanpa harus mengunduh file berkali-kali.
* **Pengumpulan Tugas Mandiri:** Siswa dapat melampirkan berkas jawaban mandiri. Untuk menjaga kejujuran dan kerapian administrasi, berkas tugas akan otomatis terkunci begitu guru selesai memberikan penilaian.

### 3. Keamanan Sesi untuk Komputer Bersama (Lab Bimbel)
* Di bimbel, komputer laboratorium atau laptop kelas sering dipakai bergantian oleh beberapa siswa. Kami menemukan celah di mana siswa yang selesai belajar sudah mengklik *Logout*, namun siswa berikutnya yang duduk di meja tersebut menekan tombol *Back* pada peramban dan masih bisa melihat sisa tampilan dasbor akun sebelumnya karena tersimpan di memori cache.
* Untuk mengatasinya, sistem menerapkan pembersihan sesi menyeluruh (*Clear-Site-Data*), larangan penyimpanan cache (*no-cache, no-store*), serta perangkap riwayat peramban (*back-history trap*). Jika tombol *Back* ditekan setelah keluar, sistem langsung membatalkan akses dan mengarahkan kembali ke halaman login.
* **Login Fleksibel:** Pengguna tidak diwajibkan mengingat email; siswa dapat masuk menggunakan NISN dan guru dapat masuk menggunakan NIP.

### 4. Manajemen Operasional Bimbel & Administrasi
* **Pengelolaan Peserta Didik & Kelas:** Pengelompokan rombongan belajar sesuai tingkatan dan paket bimbingan.
* **Paket Bimbingan Dinamis:** Konfigurasi paket belajar (seperti Reguler UTBK-SNBT, Kedinasan, dll.) dengan rincian biaya, harga coret, dan fasilitas belajar yang terhubung langsung ke database.
* **Siklus Periode Akademik:** Pemisahan data tahun ajaran aktif agar data siswa tidak tercampur antar-angkatan.
* **Master Mata Pelajaran Lengkap:** Tersedia mata pelajaran pokok (Matematika, Biologi, Kimia, Fisika, Bahasa Inggris, Bahasa Indonesia, Sejarah, Ekonomi, Sosiologi, Geografi).

---

## 🛠 Prasyarat Menjalankan Aplikasi

Anda dapat menjalankan proyek ini menggunakan salah satu dari dua cara berikut:

* **Rekomendasi Utama (Paling Praktis):**
  * **Docker** & **Docker Compose** *(atau **Podman** / Podman Desktop)*.
* **Pilihan Lokal (Manual):**
  * PHP >= 8.2 (ekstensi: pdo_mysql, mbstring, openssl, tokenizer, xml, ctype, json, bcmath, curl)
  * Composer >= 2.5
  * Node.js >= 18 & NPM
  * MySQL >= 8.0

---

## 🚀 Panduan Menjalankan Proyek (Pertama Kali Clone)

### Cara 1: Menggunakan Docker / Podman (Sangat Disarankan)

Semua kebutuhan server (PHP 8.2, RoadRunner Octane, MySQL 8, Redis, dan Nginx) sudah siap di dalam kontainer, jadi Anda tidak perlu pusing menyamakan versi software di komputer lokal.

1. **Clone repositori dan masuk ke folder proyek:**
   ```bash
   git clone https://github.com/Joanilham/sistem_infromasi_bimbel.git
   cd sistem_infromasi_bimbel
   ```

2. **Salin berkas konfigurasi lingkungan:**
   ```bash
   cp .env.example .env
   ```
   *(Pengaturan database di `.env.example` sudah disesuaikan langsung dengan nama service kontainer).*

3. **Nyalakan seluruh kontainer di latar belakang:**
   * Jika memakai **Docker**:
     ```bash
     docker compose up -d
     ```
   * Jika memakai **Podman**:
     ```bash
     podman-compose up -d
     ```
     *(Di Windows, Anda juga bisa klik dua kali file pembantu `podman-start.bat`).*

4. **Jalankan migrasi database dan isi data awal (Seeder):**
   Tunggu sekitar 15–20 detik sampai MySQL di kontainer selesai booting, lalu jalankan:
   ```bash
   docker compose exec app php artisan migrate:fresh --seed
   ```
   *(Bagi pengguna Podman: ganti `docker compose` dengan `podman-compose` atau gunakan `podman-artisan.bat migrate:fresh --seed`).*

   > **Catatan Penting:** Perintah ini wajib dijalankan agar database tidak kosong. Data mata pelajaran, kelas, akun admin, serta contoh materi guru dan akun siswa akan otomatis dibuat.

5. **Akses aplikasi melalui peramban:**
   Buka peramban dan tuju ke alamat:
   👉 **`http://localhost:8080`**

---

### Cara 2: Menjalankan Manual di Komputer Lokal (Tanpa Kontainer)

Jika Anda ingin menjalankan langsung menggunakan PHP dan MySQL yang sudah terpasang di komputer:

1. **Clone dan siapkan dependensi:**
   ```bash
   git clone https://github.com/Joanilham/sistem_infromasi_bimbel.git
   cd sistem_infromasi_bimbel
   composer install
   npm install
   ```

2. **Pengaturan `.env` dan kunci aplikasi:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Buka file `.env`, lalu pastikan nama database, user, dan password MySQL lokal Anda sudah sesuai:
   ```ini
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sistem_bimbel
   DB_USERNAME=root
   DB_PASSWORD=
   ```

3. **Migrasi database dan compile aset frontend:**
   Pastikan database kosong sudah dibuat di MySQL lokal, lalu jalankan:
   ```bash
   php artisan migrate:fresh --seed
   npm run build
   ```

4. **Jalankan server pengembangan:**
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses di **`http://localhost:8000`**.

---

## 👥 Akun Demo untuk Pengujian Fitur

Setelah proses *seeder* selesai, gunakan akun-akun bawaan berikut untuk mencoba alur kerja dari berbagai sudut pandang pengguna:

| Peran | Kredensial Login (Email / ID) | Kata Sandi | Skenario Pengujian |
| :--- | :--- | :--- | :--- |
| **Super Admin** | `superadmin@admin.com` | `password` | Mengelola data master bimbel, staf, paket bimbingan, dan pengaturan sistem. |
| **Guru (Matematika)** | `guru.matematika@bimbel.com`<br>*(atau NIP: `198503122010011002`)* | `password123` | Mengunggah materi pelajaran, memantau siapa yang membaca modul, dan mengoreksi tugas siswa. |
| **Guru (Biologi)** | `guru.biologi@bimbel.com`<br>*(atau NIP: `199004152015032004`)* | `password123` | Pengujian pembuatan materi pada mata pelajaran lain. |
| **Siswa (Reguler)** | `siswa1@example.com`<br>*(atau NISN: `0081234501`)* | `password123` | Membaca modul langsung di peramban dan mengunggah tugas mandiri. |
| **Siswa (Alternatif)** | `siswa2@example.com`<br>*(atau NISN: `0081234502`)* | `password123` | Memvalidasi persentase keterbacaan kelas di dasbor guru dengan multi-akun siswa. |

---

## 📌 Catatan Praktis & Pengalaman Pengembang

Beberapa catatan teknis yang berguna saat Anda mencoba atau mengembangkan proyek ini:

1. **Kenapa tombol Back tidak bisa kembali ke halaman sebelumnya setelah Logout?**  
   Ini bukan eror, melainkan fitur proteksi sesi yang sengaja dipasang. Saat pengguna keluar, halaman sebelumnya langsung dinonaktifkan di memori peramban agar akun tidak bisa diakses orang lain di komputer bersama.
2. **Reload Worker Octane saat Edit Kode:**  
   Karena Laravel Octane memuat aplikasi ke dalam worker memori persisten (RoadRunner), perubahan pada file PHP tertentu saat memakai kontainer kadang tidak langsung terlihat di browser. Untuk memperbaruinya, jalankan:
   ```bash
   docker compose exec app php artisan octane:reload
   ```
3. **Catatan Performa Dokumen Tebal:**  
   Dari hasil uji coba kami, berkas PDF di atas 30 halaman dengan banyak ilustrasi grafik pada ponsel dengan RAM rendah bisa membutuhkan waktu render awal sekitar 2 hingga 4 detik di peramban. Dokumen tetap dapat dibuka tanpa eror, namun ke depannya disarankan menerapkan optimasi kompresi berkas di sisi server sebelum modul disimpan permanen.

---

## ⚖️ Lisensi & Hak Cipta

Hak Cipta (c) 2026 Joan Ilham Dwi Putra. Seluruh hak cipta dilindungi undang-undang.  
Source code ini didokumentasikan dan dipublikasikan untuk keperluan evaluasi akademik dan pengembangan sistem manajemen bimbingan belajar.
