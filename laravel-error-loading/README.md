# 🚀 Loading & Error Handler — Laravel

Sistem loading animasi dan error handling yang lengkap untuk website Laravel Anda.

---

## 📁 Struktur File

```
public/
  css/
    loading.css          ← Semua CSS: loading, skeleton, toast
  js/
    app-loader.js        ← Logic JS: progress bar, toast, offline detection

resources/views/
  layouts/
    app.blade.php        ← Layout utama (sudah include loader & toast)
  errors/
    404.blade.php        ← Halaman tidak ditemukan
    500.blade.php        ← Kesalahan server
    403.blade.php        ← Akses ditolak
    503.blade.php        ← Maintenance mode
  components/
    skeleton.blade.php   ← Komponen skeleton loader
```

---

## ⚡ Instalasi

### 1. Salin file ke project Laravel

```bash
# Salin CSS dan JS ke public/
cp loading.css  your-project/public/css/
cp app-loader.js your-project/public/js/

# Salin Blade views
cp -r resources/views/ your-project/resources/views/
```

### 2. Tambahkan ke layout Blade Anda

Di bagian `<head>` layout:
```html
<link rel="stylesheet" href="{{ asset('css/loading.css') }}">
```

Sebelum `</body>`:
```html
<script src="{{ asset('js/app-loader.js') }}"></script>
```

Atau jika menggunakan Vite (Laravel 9+), import di `resources/js/app.js`:
```javascript
import '../css/loading.css';
import './app-loader.js';
```

### 3. Tambahkan elemen HTML di layout

```html
<!-- Top progress bar (di atas semua konten) -->
<div id="top-progress"></div>

<!-- Page loader overlay -->
<div id="page-loader" role="status" aria-label="Memuat halaman">
    <div class="loader-spinner">
        <div class="ring ring-1"></div>
        <div class="ring ring-2"></div>
        <div class="ring ring-3"></div>
    </div>
    <div class="loader-bar-wrap">
        <div class="loader-bar"></div>
    </div>
    <p class="loader-text">
        Memuat halaman<span class="loader-dots">
            <span>.</span><span>.</span><span>.</span>
        </span>
    </p>
</div>

<!-- Toast container -->
<div id="toast-container" role="region" aria-live="polite"></div>
```

---

## 🎯 Cara Penggunaan

### Toast Notifikasi

```javascript
// Di JavaScript halaman Anda
App.Toast.success('Berhasil!', 'Data telah disimpan.');
App.Toast.error('Gagal!', 'Terjadi kesalahan, coba lagi.');
App.Toast.warning('Perhatian', 'Stok hampir habis.');
App.Toast.info('Info', 'Update tersedia.');
```

### Toast dari Flash Session (Blade)

Di Controller Laravel:
```php
// Controller.php
return redirect()->back()
    ->with('success', 'Data berhasil disimpan!');
    
return redirect()->back()
    ->with('error', 'Gagal menyimpan data.');

return redirect()->back()
    ->with('warning', 'Stok hampir habis!');
```

Toast akan otomatis muncul karena layout `app.blade.php` sudah menanganinya.

### Fetch dengan Error Handling Otomatis

```javascript
// Ganti fetch() biasa dengan App.Http
async function simpanData() {
    try {
        const data = await App.Http.post('/api/produk', {
            nama: 'Produk A',
            harga: 50000
        });
        App.Toast.success('Berhasil', 'Produk tersimpan!');
    } catch (err) {
        // Error sudah ditangani otomatis (toast + progress bar)
        console.log('Status:', err.status);
    }
}

// GET request
const produk = await App.Http.get('/api/produk/1');
```

### Skeleton Loader di Blade

```blade
{{-- Tampilkan skeleton saat loading --}}
<div id="konten-produk">
    @include('components.skeleton', ['type' => 'card', 'rows' => 3])
</div>

{{-- Tipe tersedia: 'card', 'list', 'table', 'profile', 'article' --}}
@include('components.skeleton', ['type' => 'list', 'rows' => 5])
@include('components.skeleton', ['type' => 'table', 'rows' => 4])
@include('components.skeleton', ['type' => 'profile'])
@include('components.skeleton', ['type' => 'article', 'rows' => 6])
```

### Skeleton Loader via JavaScript

```javascript
const container = document.getElementById('konten');

// Tampilkan skeleton
App.Skeleton.show(container);

// Setelah data siap
const data = await App.Http.get('/api/data');
App.Skeleton.hide(container);
// Render data...
```

### Progress Bar Manual

```javascript
App.Progress.start(); // mulai progress
App.Progress.done();  // selesai (hijau)
App.Progress.fail();  // gagal (merah)
```

---

## ⚠️ Error yang Ditangani Otomatis

| Error | Pesan |
|-------|-------|
| 400 | Permintaan Tidak Valid |
| 401 | Sesi Habis → redirect ke /login |
| 403 | Akses Ditolak |
| 404 | Tidak Ditemukan |
| 419 | Token Kedaluwarsa → reload otomatis |
| 422 | Validasi Gagal |
| 429 | Terlalu Banyak Permintaan |
| 500 | Kesalahan Server |
| 502 | Server Tidak Tersedia |
| 503 | Layanan Tidak Tersedia |
| 504 | Waktu Habis |
| Offline | Banner + Toast offline/online |

---

## 🎨 Kustomisasi Warna

Edit CSS variable di `loading.css`:

```css
/* Ganti warna spinner & progress bar */
.loader-spinner .ring-1 { border-top-color: #WARNA-ANDA; }
.loader-bar { background: linear-gradient(90deg, #WARNA1, #WARNA2); }

/* Warna toast border kiri */
.toast-success { border-left-color: #WARNA; }
```

---

## 📱 Dark Mode

Semua komponen mendukung dark mode otomatis via `@media (prefers-color-scheme: dark)`.

---

## 🔧 Maintenance Mode

```bash
# Aktifkan maintenance mode
php artisan down

# Nonaktifkan
php artisan up

# Dengan secret (bypass maintenance untuk admin)
php artisan down --secret="rahasia-anda"
# Akses: https://website.com/rahasia-anda
```

Halaman `503.blade.php` otomatis tampil saat `php artisan down`.
