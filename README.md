# Sistem Informasi Bimbingan Belajar & LMS Terpadu (Platform Nivora)

Sistem Informasi Bimbingan Belajar adalah platform manajemen operasional dan pembelajaran digital (*Learning Management System*) yang dirancang khusus untuk memenuhi kebutuhan lembaga bimbingan belajar (bimbel). Sistem ini mengintegrasikan fungsi administratif bimbel, pengelolaan kelas, pendaftaran peserta didik, penyusunan paket belajar, hingga alur belajar-mengajar dua arah antara pengajar dan siswa secara terpadu.

Aplikasi ini dibangun di atas **Laravel 11** dan dioptimalkan menggunakan arsitektur kontainer berkinerja tinggi melalui **Laravel Octane (RoadRunner)** serta **Nginx Reverse Proxy**. Dengan pemanfaatan worker memori persisten, waktu respons sistem tetap sangat cepat dan stabil saat diakses serentak pada jam-jam sibuk bimbingan belajar.

---

## 🌟 Cakupan Fitur Keseluruhan Sistem

Sistem ini mencakup empat pilar fungsional utama yang saling terhubung:

### 1. Ekosistem Learning Management System (LMS)
Modul pembelajaran yang dirancang untuk memangkas ketergantungan pada grup percakapan manual:
* **Modul LMS Guru:**
  * **Pengelompokan Materi & Tugas:** Guru dapat menyusun bahan ajar (teori, latihan, modul) berdasarkan kelas dan mata pelajaran yang diampu.
  * **Tenggat Waktu Fleksibel:** Pengaturan batas waktu pengerjaan tugas yang secara otomatis mencatat status keterlambatan pengiriman tanpa mematikan hak siswa untuk tetap menyetor tugas.
  * **Panel Monitoring Keaktifan Real-Time:** Menampilkan daftar siswa yang telah membuka (*read*) maupun mengunduh (*download*) modul materi secara langsung, dilengkapi waktu akses dan persentase keterbacaan kelas.
  * **Pemeriksaan Tugas & Input Nilai di Web:** Pengajar dapat membuka berkas pengiriman tugas siswa langsung di peramban, memberikan catatan umpan balik, dan memasukkan nilai akhir tanpa harus mengunduh berkas satu per satu.
* **Portal Belajar Siswa:**
  * **Katalog Materi Interaktif:** Tampilan ringkas berisi daftar modul per mata pelajaran, riwayat belajar, dan status tugas yang belum diselesaikan.
  * **Penampil Dokumen Mandiri (In-Browser Viewer):** Modul format PDF, DOCX, dan PPTX dapat dibuka dan dipelajari langsung di peramban tanpa memicu aplikasi pengunduh eksternal (seperti IDM) yang kerap memenuhi memori penyimpanan perangkat siswa.
  * **Pencatatan Akses Otomatis:** Sistem mendeteksi dan mencatat waktu ketika siswa membuka materi belajar, sehingga siswa tidak dibebani tombol konfirmasi manual.
  * **Pengumpulan Tugas Mandiri:** Siswa dapat mengunggah berkas jawaban mandiri. Begitu tugas dinilai oleh guru, hak ubah berkas otomatis terkunci untuk menjaga keabsahan nilai.
* **Manajemen Mata Pelajaran & Bank Soal:**
  * Tersedia master mata pelajaran lengkap (Matematika, Fisika, Kimia, Biologi, Bahasa Inggris, Bahasa Indonesia, Sejarah, Ekonomi, Sosiologi, Geografi) sebagai fondasi pembelajaran dan latihan soal.

### 2. Manajemen Operasional Bimbel & Administrasi (Admin / Super Admin)
* **Manajemen Multi-Pengguna & Peran:** Pengelolaan hak akses berjenjang mulai dari Super Admin, Admin Cabang, Guru/Tentor, hingga Siswa.
* **Manajemen Peserta Didik & Kelompok Belajar:** Pengelompokan siswa ke dalam rombongan belajar/kelas intensif sesuai paket bimbingan yang diambil.
* **Paket Bimbingan Belajar Dinamis:** Konfigurasi program belajar (misalnya Reguler Intensif UTBK-SNBT, Kedinasan, Akmil/Akpol) lengkap dengan rincian fasilitas, nominal biaya, dan harga promo tanpa bergantung pada data *dummy* statis.
* **Siklus Periode Akademik:** Pengelolaan tahun ajaran dan periode aktif untuk memisahkan data siswa antar-angkatan secara terstruktur.
* **Pendaftaran Siswa Baru (PPDB Online):** Alur pendaftaran mandiri bagi calon siswa baru melalui antarmuka publik hingga tahap verifikasi berkas oleh admin.

### 3. Portal Publik (Landing Page)
* Halaman depan modern yang menyajikan direktori program bimbel, paket pilihan terlaris, penggeser galeri kegiatan (*hero multi-slide*), testimoni, serta informasi kontak lembaga yang terintegrasi langsung dengan identitas institusi.

### 4. Keamanan Sistem, Otentikasi, dan Pemantauan Server
* **Penguatan Sesi & Logout (Aman untuk Komputer Lab Bersama):**
  * Penerapan header *anti-cache* ketat (*no-cache, no-store*) dan pembersihan data peramban (*Clear-Site-Data*).
  * Mekanisme penahan riwayat peramban (*back-history trap*): ketika pengguna telah menekan tombol *Logout*, menekan tombol *Back* pada peramban tidak akan menampilkan data sesi sebelumnya dan secara otomatis melempar pengguna kembali ke gerbang login.
* **Multi-Kredensial Login:** Memudahkan pengguna masuk sistem dengan identitas yang paling mudah diingat: siswa dapat login memakai NISN atau email, sementara guru dapat memakai NIP atau email.
* **Monitoring Server via Bot Telegram:** Integrasi pelaporan otomatis unhandled error/crash sistem ke Bot Telegram administrator, serta perintah interaktif untuk memantau penggunaan CPU, *uptime* server, dan ringkasan operasional bimbel secara langsung dari aplikasi Telegram.
* **Proteksi Lalu Lintas Web:** Dilengkapi lapisan Nginx reverse proxy dengan aturan pengamanan ketat untuk menyaring lalu lintas data sebelum menyentuh aplikasi utama.

---

## 🛠 Kebutuhan Sistem (Prasyarat)

Untuk menjalankan proyek ini, pastikan komputer Anda telah terpasang salah satu lingkungan berikut:

* **Pilihan Rekomendasi (Paling Praktis & Bebas Konflik Versi):**
  * **Docker** & **Docker Compose** *(atau **Podman** & Podman Compose)*.
* **Pilihan Lokal (Tanpa Kontainer):**
  * PHP >= 8.2 (ekstensi wajib: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `curl`)
  * Composer >= 2.5
  * Node.js >= 18 & NPM
  * MySQL >= 8.0

---

## 🚀 Panduan Menjalankan Proyek Saat Pertama Kali Clone

### Cara 1: Menggunakan Docker / Podman (Sangat Disarankan)

Metode ini paling direkomendasikan karena seluruh kebutuhan runtime (PHP 8.2, RoadRunner Octane, MySQL 8, Redis, dan Nginx) sudah dikonfigurasi otomatis di dalam kontainer.

1. **Clone Repositori dan Masuk ke Direktori Proyek:**
   ```bash
   git clone https://github.com/Joanilham/sistem_infromasi_bimbel.git
   cd sistem_infromasi_bimbel
   ```

2. **Siapkan Berkas Konfigurasi Lingkungan (`.env`):**
   ```bash
   cp .env.example .env
   ```
   *(Konfigurasi bawaan pada `.env.example` sudah disesuaikan langsung dengan nama service database kontainer).*

3. **Nyalakan Seluruh Kontainer:**
   * Jika menggunakan **Docker**:
     ```bash
     docker compose up -d
     ```
   * Jika menggunakan **Podman** di Linux/Windows:
     ```bash
     podman-compose up -d
     ```
     *(Pengguna Windows juga dapat memanfaatkan file batch pembantu: `podman-start.bat`).*

4. **Jalankan Migrasi Database dan Isi Data Awal (Seeder):**
   Tunggu sekitar 15–20 detik agar container database selesai melakukan *bootstrap*, kemudian jalankan:
   * Pada Docker:
     ```bash
     docker compose exec app php artisan migrate:fresh --seed
     ```
   * Pada Podman:
     ```bash
     podman-compose exec app php artisan migrate:fresh --seed
     ```
     *(atau jalankan `podman-artisan.bat migrate:fresh --seed`).*

   > **Penting:** Perintah seeder ini akan otomatis mengisi master mata pelajaran, contoh modul materi guru, paket bimbingan, kelompok belajar, dan seluruh akun demo untuk pengujian.

5. **Buka Aplikasi di Peramban:**
   Buka browser Anda dan akses:
   👉 **`http://localhost:8080`**

---

### Cara 2: Menjalankan Secara Manual di Lingkungan Lokal (Tanpa Kontainer)

Jika Anda ingin menjalankan aplikasi langsung menggunakan PHP dan MySQL lokal di komputer:

1. **Clone Repositori dan Masuk ke Direktori:**
   ```bash
   git clone https://github.com/Joanilham/sistem_infromasi_bimbel.git
   cd sistem_infromasi_bimbel
   ```

2. **Pasang Dependensi PHP dan Node.js:**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Berkas Lingkungan:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Buka berkas `.env`, lalu sesuaikan parameter koneksi basis data:
   ```ini
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sistem_bimbel
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Jalankan Migrasi dan Seeder:**
   Pastikan database kosong dengan nama sesuai di `.env` sudah dibuat di MySQL Anda, lalu jalankan:
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Kompilasi Aset Frontend dan Jalankan Server:**
   ```bash
   npm run build
   php artisan serve
   ```
   Buka peramban di **`http://localhost:8000`**.

---

## 👥 Akun Bawaan untuk Uji Coba Sistem (Demo Kredensial)

Setelah proses `migrate:fresh --seed` selesai, Anda dapat langsung login menggunakan akun uji coba berikut untuk mencoba masing-masing alur pengguna:

| Peran (Role) | Kredensial Login (Email / ID) | Kata Sandi | Alur & Fitur yang Dapat Diuji |
| :--- | :--- | :--- | :--- |
| **Super Admin** | `superadmin@admin.com` | `password` | Mengelola data master bimbel, kelas, guru, paket bimbingan, dan pengaturan sistem. |
| **Guru (Matematika)** | `guru.matematika@bimbel.com`<br>*(atau NIP: `198503122010011002`)* | `password123` | Mengunggah modul pelajaran, melihat panel pemantau pembaca materi, memeriksa jawaban tugas siswa, dan menginput nilai. |
| **Guru (Biologi)** | `guru.biologi@bimbel.com`<br>*(atau NIP: `199004152015032004`)* | `password123` | Uji coba mata pelajaran IPA lainnya. |
| **Siswa (Reguler)** | `siswa1@example.com`<br>*(atau NISN: `0081234501`)* | `password123` | Melihat modul pelajaran di peramban tanpa terganggu unduhan paksa, dan mengunggah tugas mandiri. |
| **Siswa (Alternatif)** | `siswa2@example.com`<br>*(atau NISN: `0081234502`)* | `password123` | Pengujian akses multi-siswa untuk memvalidasi persentase keaktifan membaca di dasbor guru. |

---

## 📂 Struktur Direktori Utama

* `app/Http/Controllers/Guru/LmsController.php` — Logika penyusunan materi, batas waktu, pemantauan pembaca/pengunduh, dan input nilai guru.
* `app/Http/Controllers/Siswa/LmsController.php` — Logika dasbor siswa, pembaca dokumen, dan pengumpulan tugas mandiri.
* `app/Http/Controllers/Auth/AuthController.php` — Autentikasi multi-kredensial, pembersihan sesi pasca-logout, dan aturan anti-cache.
* `app/Http/Middleware/SecurityHeaders.php` — Header keamanan peramban dan penolak riwayat cache.
* `resources/views/guru/lms/` — Antarmuka LMS guru (pembuatan materi, matriks pengumpulan nilai, daftar pembaca).
* `resources/views/siswa/lms/` — Antarmuka portal belajar siswa dan penampil dokumen in-browser.
* `public/vendor/` — Pustaka pembaca dokumen lokal (PDF, DOCX, PPTX) yang tertanam mandiri.
* `docker/` & `podman-compose.yml` — Konfigurasi orkestrasi kontainer, Nginx, dan Laravel Octane RoadRunner.

---

## 📌 Catatan Teknis & Praktik Pengembang

1. **Perilaku Tombol Back Setelah Logout:**  
   Jika Anda mencoba menekan tombol *Back* pada peramban setelah logout, layar akan secara otomatis memuat ulang dan mengarahkan kembali ke halaman login. Hal ini merupakan perilaku yang sengaja dirancang demi keamanan privasi akun di komputer bersama (misalnya laboratorium komputer bimbel).
2. **Reload Worker Octane saat Memperbarui Kode:**  
   Karena Laravel Octane memuat kode aplikasi ke dalam memori kerja (*persistent worker*), setiap perubahan pada berkas kontroler atau konfigurasi saat menggunakan kontainer perlu di-reload agar diterapkan:
   ```bash
   docker compose exec app php artisan octane:reload
   ```
3. **Penyajian Dokumen Mandiri (Anti-IDM):**  
   Modul berkas disajikan sebagai aliran data biner (*binary stream*) langsung ke kanvas penampil dokumen peramban. Cara ini mencegah ekstensi seperti *Internet Download Manager* (IDM) mengambil alih proses baca, sehingga siswa dapat langsung belajar tanpa harus menguras kuota dan memori perangkat.

---

## ⚖️ Lisensi & Hak Cipta

Hak Cipta (c) 2026 Joan Ilham Dwi Putra. Seluruh hak cipta dilindungi undang-undang.  
Source code ini didokumentasikan dan diunggah untuk keperluan evaluasi akademik serta pengembangan sistem informasi bimbingan belajar.
