# Design Document: Sistem Informasi Bimbingan Belajar (Genius Education)

## 1. Deskripsi Proyek
Sistem Informasi Bimbingan Belajar ini dirancang untuk mendigitalisasi operasional lembaga pendidikan, mulai dari manajemen data akademik hingga pelaksanaan evaluasi belajar. Sistem ini berfokus pada integrasi data yang mulus, performa yang stabil, dan kemudahan akses bagi seluruh pengguna.

## 2. Arsitektur & Teknologi (Tech Stack)
Infrastruktur dibangun untuk mendukung skalabilitas dan kemudahan integrasi.

*   **Backend Framework:** Laravel (untuk RESTful API dan logika bisnis utama)
*   **Web Server & Reverse Proxy:** NGINX
*   **Containerization:** Docker (memastikan konsistensi environment *development* hingga *production*)
*   **Database:** MySQL / PostgreSQL
*   **UI/UX Design:** Figma

## 3. Hak Akses & Aktor Sistem
Sistem ini menggunakan *Role-Based Access Control* (RBAC) dengan pemisahan wewenang yang tegas.

| Role | Deskripsi Wewenang |
| :--- | :--- |
| **Admin** | Mengelola master data, jadwal kelas, data transaksi, dan monitoring aktivitas CBT. |
| **Tutor** | Mengunggah materi, membuat bank soal CBT, memberikan nilai, dan memantau progres siswa. |
| **Siswa** | Mengakses materi pembelajaran, melihat jadwal kelas, dan mengikuti Computer Based Test (CBT). |

## 4. Modul Utama Sistem

### 4.1. Manajemen Siswa & Akademik
*   Pendaftaran dan pendataan profil siswa secara digital.
*   Pemetaan siswa ke dalam kelas berdasarkan jenjang pendidikan dan program bimbel.
*   Pencatatan presensi terintegrasi.

### 4.2. Computer Based Test (CBT)
*   Sistem ujian online terintegrasi dengan pengatur waktu (*timer*) yang akurat.
*   Dukungan untuk berbagai tipe soal (Pilihan ganda, esai singkat).
*   *Auto-grading* untuk soal pilihan ganda dengan *live report* hasil ujian.

### 4.3. Integrasi Data & API
*   Sentralisasi dan normalisasi data dari berbagai modul atau layanan pihak ketiga.
*   Tugas integrasi dikelola secara ketat untuk memastikan tidak ada redundansi data antar tabel.

## 5. Panduan Desain UI/UX (Figma Guidelines)
Desain antarmuka mengusung tema pendidikan yang modern, bersih, dan memfasilitasi konsentrasi belajar.

*   **Palet Warna:**
    *   *Primary:* Biru Edukasi (`#1A56DB`) - Melambangkan profesionalisme dan kecerdasan.
    *   *Secondary:* Kuning Cerah (`#FACA15`) - Sebagai aksen untuk tombol *Call to Action* (CTA).
    *   *Background:* Putih dan Abu-abu Terang (`#F3F4F6`) - Untuk menjaga mata agar tidak cepat lelah saat membaca materi atau mengerjakan CBT.
*   **Tipografi:** *Inter* atau *Roboto* (bersih, sans-serif, sangat terbaca pada layar resolusi kecil maupun besar).
*   **Layout:** *Responsive design* dengan orientasi *mobile-first* untuk tampilan akses siswa.

## 6. Struktur Diagram
Sesuai dengan arsitektur peran, alur logika sistem (*Flowchart*) tidak digabungkan, melainkan dipisah secara terstruktur:

1.  **Flowchart Admin:** Alur kompleks mencakup manajemen master data, penjadwalan, dan pelaporan keseluruhan.
2.  **Flowchart Tutor:** Alur pengelolaan kelas, input materi, dan pembuatan sesi CBT.
3.  **Flowchart Siswa:** Alur dari proses *login*, akses materi, hingga penyelesaian sesi ujian CBT.

---
*Dokumen ini merupakan draf *High-Level Design*. Pembaruan akan dilakukan secara berkala sesuai dengan iterasi pengembangan.*