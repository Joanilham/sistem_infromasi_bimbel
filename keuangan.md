# Fitur Keuangan — Bimbel Genius Education

Dokumentasi lengkap modul keuangan untuk sistem administrasi bimbel.

---

## Daftar Isi

1. [Pembayaran](#1-pembayaran)
2. [Pemasukan](#2-pemasukan)
3. [Pengeluaran](#3-pengeluaran)
4. [Tagihan](#4-tagihan)
5. [Struktur Database](#5-struktur-database)
6. [API Endpoint](#6-api-endpoint)

---

## 1. Pembayaran

### Deskripsi
Menu utama untuk mencatat dan mengelola pembayaran SPP/biaya bimbel per siswa. Admin dapat melihat daftar seluruh siswa, membuka detail pembayaran, mencatat transaksi, dan mencetak kwitansi.

### Halaman: Daftar Siswa (List Pembayaran)

**URL:** `/pembayaran`

**Kolom Tabel:**

| Kolom | Tipe | Keterangan |
|---|---|---|
| No | integer | Nomor urut |
| Nama Lengkap | string | Nama siswa |
| No. Induk | string | Nomor induk siswa |
| L/P | enum | `Laki-laki` / `Perempuan` |
| Paket Bimbel | string | Nama paket yang diambil |
| Opsi | button | Tombol **Bayar** → buka detail |

**Fitur:**
- Show entries (10 / 25 / 50 / 100)
- Search real-time (nama, no. induk, paket)
- Sortable kolom (ascending / descending)
- Pagination

---

### Halaman: Detail Pembayaran Siswa

**URL:** `/pembayaran/{id}`

**Tombol Header:**
- `Kembali` → kembali ke daftar
- `Cetak Rekap/Bukti` → cetak PDF rekap pembayaran

#### Bagian 1: Identitas Peserta Didik

| Field | Tipe | Editable | Keterangan |
|---|---|---|---|
| No. Induk | string | Tidak | Otomatis dari data siswa |
| Nama Lengkap | string | Tidak | Otomatis dari data siswa |
| Paket | string | Tidak | Otomatis dari paket bimbel |
| Biaya Bimbel | currency | Tidak | Harga paket |
| Diskon (%) | number | Ya | Input persen diskon |
| Diskon (Rp) | currency | Ya | Input nominal diskon |
| Keterangan Diskon | string | Ya | Contoh: `DISKON PERIODE JUNI-JULI 2022` |
| Potongan | currency | Tidak | Dihitung otomatis (negatif) |
| Biaya Pendaftaran | currency | Ya | Input biaya pendaftaran |
| Total yang Harus Dibayar | currency | Tidak | `Biaya Bimbel - Diskon + Biaya Pendaftaran` |
| Total Terbayar | currency | Tidak | Jumlah dari semua transaksi |
| Kurang Pembayaran | badge | Tidak | `LUNAS` (hijau) / nominal kekurangan (kuning) |
| Batas Waktu Pembayaran | date | Ya | Deadline pelunasan |

**Tombol:** `Simpan` → update data diskon, biaya pendaftaran, dan batas waktu.

#### Bagian 2: Transaksi Pembayaran (Form Input)

| Field | Tipe | Keterangan |
|---|---|---|
| Nominal Pembayaran | currency | Jumlah yang dibayarkan |
| Tanggal Pembayaran | date | Tanggal transaksi |
| Tipe Pembayaran | enum | `TUNAI` / `TRANSFER` |

**Tombol:** `Bayar` → simpan transaksi, update total terbayar.

#### Bagian 3: Daftar Pembayaran (Riwayat Transaksi)

| Kolom | Tipe | Keterangan |
|---|---|---|
| No | integer | Nomor urut |
| Tanggal | date | Tanggal transaksi |
| Nominal | currency | Jumlah dibayar |
| No. Kwitansi | string | Nomor kwitansi otomatis |
| Penerima | string | Nama admin penerima |
| Tipe Pembayaran | enum | `TUNAI` / `TRANSFER` |
| Opsi | button | `Cetak` / `Hapus` |

**Format No. Kwitansi:** `YYMMDD{urutan}/{kode_admin}`
Contoh: `221231004/e`

---

## 2. Pemasukan

### Deskripsi
Mencatat semua pemasukan non-SPP seperti pendaftaran siswa baru, penjualan modul, donasi, dan lainnya. Mendukung manajemen kategori pemasukan secara fleksibel.

### Halaman: Daftar Pemasukan

**URL:** `/pemasukan`

**Tombol Header:**
- `Tambah` → buka form tambah pemasukan
- `Kategori Pemasukan` → manajemen kategori

**Kolom Tabel:**

| Kolom | Tipe | Keterangan |
|---|---|---|
| Tanggal Pemasukan | date | Tanggal dicatat |
| Kategori Pemasukan | string | Dari tabel kategori |
| Nominal | currency | Jumlah pemasukan |
| Keterangan | string | Deskripsi singkat |
| Opsi | button | `Edit` / `Hapus` |

**Fitur:**
- Show entries & search
- Sortable semua kolom
- Pagination

---

### Form Tambah / Edit Pemasukan

| Field | Tipe | Wajib | Keterangan |
|---|---|---|---|
| Tanggal Pemasukan | date | Ya | |
| Kategori Pemasukan | select | Ya | Dari tabel kategori |
| Nominal | currency | Ya | |
| Keterangan | string | Tidak | |

---

### Sub-menu: Kategori Pemasukan

**URL:** `/pemasukan/kategori`

| Field | Tipe | Keterangan |
|---|---|---|
| Nama Kategori | string | Contoh: `SPP`, `Pendaftaran`, `Modul`, `Donasi` |

---

## 3. Pengeluaran

### Deskripsi
Mencatat semua pengeluaran operasional bimbel seperti listrik, ATK, gaji pengajar, konsumsi, dan lainnya. Mendukung manajemen kategori pengeluaran secara fleksibel.

### Halaman: Daftar Pengeluaran

**URL:** `/pengeluaran`

**Tombol Header:**
- `Tambah` → buka form tambah pengeluaran
- `Kategori Pengeluaran` → manajemen kategori

**Kolom Tabel:**

| Kolom | Tipe | Keterangan |
|---|---|---|
| Tanggal Pengeluaran | date | Tanggal dicatat |
| Kategori Pengeluaran | string | Dari tabel kategori |
| Nominal | currency | Jumlah pengeluaran |
| Keterangan | string | Deskripsi detail |
| Opsi | button | `Edit` (kuning) / `Hapus` (merah) |

**Contoh kategori yang sudah ada:**
- LISTRIK DAN WIFI
- ATK, SPIDOL, TINTA, KERTAS DLL
- TRANSFER KE PIMPINAN
- KONSUMSI DAN TRANSPORT
- GAJI PENGAJAR

---

### Form Tambah / Edit Pengeluaran

| Field | Tipe | Wajib | Keterangan |
|---|---|---|---|
| Tanggal Pengeluaran | date | Ya | |
| Kategori Pengeluaran | select | Ya | Dari tabel kategori |
| Nominal | currency | Ya | |
| Keterangan | string | Tidak | Deskripsi lebih detail |

---

### Sub-menu: Kategori Pengeluaran

**URL:** `/pengeluaran/kategori`

| Field | Tipe | Keterangan |
|---|---|---|
| Nama Kategori | string | Contoh: `LISTRIK DAN WIFI`, `ATK`, `GAJI` |

---

## 4. Tagihan

### Deskripsi
Menampilkan daftar siswa yang masih memiliki kekurangan pembayaran (belum lunas). Data diambil otomatis dari modul Pembayaran berdasarkan selisih antara total yang harus dibayar dan total terbayar.

### Halaman: Daftar Tagihan

**URL:** `/tagihan`

**Kolom Tabel:**

| Kolom | Tipe | Keterangan |
|---|---|---|
| No | integer | Nomor urut |
| Deadline | date | Batas waktu pembayaran |
| Nama Lengkap | string | Nama siswa |
| No. Induk | string | Nomor induk siswa |
| L/P | enum | `Laki-laki` / `Perempuan` |
| Paket Bimbel | string | Nama paket |
| Total yang Harus Dibayar | currency | Total tagihan |
| Total Terbayar | currency | Yang sudah dibayar |
| Kekurangan | currency | Sisa yang belum dibayar (merah) |

**Logika tampil:**
- Hanya menampilkan siswa dengan `kekurangan > 0`
- Diurutkan berdasarkan deadline terlama (paling mendesak di atas)
- Jika `Total Terbayar > Total yang Harus Dibayar` → tampil sebagai nilai negatif (lebih bayar, warna hijau)

**Fitur:**
- Show entries & search
- Sortable semua kolom
- Pagination

---

## 5. Struktur Database

### Tabel `siswa`

```sql
CREATE TABLE siswa (
    id           INT PRIMARY KEY AUTO_INCREMENT,
    no_induk     VARCHAR(20) UNIQUE NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    jenis_kelamin ENUM('Laki-laki', 'Perempuan') NOT NULL,
    paket_id     INT,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Tabel `paket_bimbel`

```sql
CREATE TABLE paket_bimbel (
    id           INT PRIMARY KEY AUTO_INCREMENT,
    nama_paket   VARCHAR(100) NOT NULL,
    biaya        DECIMAL(12,0) NOT NULL,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Tabel `pembayaran_siswa` (header)

```sql
CREATE TABLE pembayaran_siswa (
    id                    INT PRIMARY KEY AUTO_INCREMENT,
    siswa_id              INT NOT NULL,
    diskon_persen         DECIMAL(5,2) DEFAULT 0,
    diskon_nominal        DECIMAL(12,0) DEFAULT 0,
    keterangan_diskon     VARCHAR(255),
    biaya_pendaftaran     DECIMAL(12,0) DEFAULT 0,
    total_harus_dibayar   DECIMAL(12,0) NOT NULL,
    batas_waktu           DATE,
    created_at            TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at            TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id)
);
```

### Tabel `transaksi_pembayaran`

```sql
CREATE TABLE transaksi_pembayaran (
    id                   INT PRIMARY KEY AUTO_INCREMENT,
    pembayaran_siswa_id  INT NOT NULL,
    nominal              DECIMAL(12,0) NOT NULL,
    tanggal              DATE NOT NULL,
    tipe_pembayaran      ENUM('TUNAI', 'TRANSFER') DEFAULT 'TUNAI',
    no_kwitansi          VARCHAR(30) UNIQUE,
    penerima             VARCHAR(100),
    user_id              INT,
    created_at           TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pembayaran_siswa_id) REFERENCES pembayaran_siswa(id)
);
```

### Tabel `kategori_pemasukan`

```sql
CREATE TABLE kategori_pemasukan (
    id           INT PRIMARY KEY AUTO_INCREMENT,
    nama         VARCHAR(100) NOT NULL,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Tabel `pemasukan`

```sql
CREATE TABLE pemasukan (
    id           INT PRIMARY KEY AUTO_INCREMENT,
    tanggal      DATE NOT NULL,
    kategori_id  INT NOT NULL,
    nominal      DECIMAL(12,0) NOT NULL,
    keterangan   VARCHAR(255),
    user_id      INT,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kategori_id) REFERENCES kategori_pemasukan(id)
);
```

### Tabel `kategori_pengeluaran`

```sql
CREATE TABLE kategori_pengeluaran (
    id           INT PRIMARY KEY AUTO_INCREMENT,
    nama         VARCHAR(100) NOT NULL,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Tabel `pengeluaran`

```sql
CREATE TABLE pengeluaran (
    id           INT PRIMARY KEY AUTO_INCREMENT,
    tanggal      DATE NOT NULL,
    kategori_id  INT NOT NULL,
    nominal      DECIMAL(12,0) NOT NULL,
    keterangan   VARCHAR(255),
    user_id      INT,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kategori_id) REFERENCES kategori_pengeluaran(id)
);
```

---

## 6. API Endpoint

### Pembayaran

| Method | Endpoint | Keterangan |
|---|---|---|
| GET | `/api/pembayaran` | Daftar semua siswa + status bayar |
| GET | `/api/pembayaran/{siswa_id}` | Detail pembayaran 1 siswa |
| PUT | `/api/pembayaran/{siswa_id}` | Update diskon, biaya pendaftaran, deadline |
| POST | `/api/pembayaran/{siswa_id}/transaksi` | Tambah transaksi bayar |
| DELETE | `/api/pembayaran/transaksi/{id}` | Hapus transaksi |
| GET | `/api/pembayaran/transaksi/{id}/cetak` | Generate PDF kwitansi |
| GET | `/api/pembayaran/{siswa_id}/cetak-rekap` | Generate PDF rekap |

### Pemasukan

| Method | Endpoint | Keterangan |
|---|---|---|
| GET | `/api/pemasukan` | Daftar pemasukan (support `?search=&page=`) |
| POST | `/api/pemasukan` | Tambah pemasukan |
| PUT | `/api/pemasukan/{id}` | Edit pemasukan |
| DELETE | `/api/pemasukan/{id}` | Hapus pemasukan |
| GET | `/api/pemasukan/kategori` | Daftar kategori |
| POST | `/api/pemasukan/kategori` | Tambah kategori |
| DELETE | `/api/pemasukan/kategori/{id}` | Hapus kategori |

### Pengeluaran

| Method | Endpoint | Keterangan |
|---|---|---|
| GET | `/api/pengeluaran` | Daftar pengeluaran (support `?search=&page=`) |
| POST | `/api/pengeluaran` | Tambah pengeluaran |
| PUT | `/api/pengeluaran/{id}` | Edit pengeluaran |
| DELETE | `/api/pengeluaran/{id}` | Hapus pengeluaran |
| GET | `/api/pengeluaran/kategori` | Daftar kategori |
| POST | `/api/pengeluaran/kategori` | Tambah kategori |
| DELETE | `/api/pengeluaran/kategori/{id}` | Hapus kategori |

### Tagihan

| Method | Endpoint | Keterangan |
|---|---|---|
| GET | `/api/tagihan` | Daftar siswa yang masih punya kekurangan |

**Query params umum:** `?page=1&per_page=10&search=keyword&sort=kolom&order=asc`

---

## Catatan Implementasi

- **Format currency:** semua nominal disimpan sebagai `DECIMAL(12,0)` tanpa desimal (rupiah bulat).
- **No. Kwitansi:** di-generate otomatis saat transaksi disimpan, format `YYMMDD{urutan_harian}/{kode_user}`.
- **Tagihan:** bukan tabel tersendiri, melainkan query `VIEW` dari `pembayaran_siswa` yang menghitung `total_harus_dibayar - SUM(transaksi_pembayaran.nominal)`.
- **Hak akses:** semua menu keuangan hanya dapat diakses oleh role `admin` dan `staff`.
- **Cetak PDF:** gunakan library seperti DomPDF (Laravel) atau Puppeteer (Node.js) untuk generate kwitansi dan rekap.
