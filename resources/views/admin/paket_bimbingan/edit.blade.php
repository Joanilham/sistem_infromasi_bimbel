@extends('layouts.admin')

@section('title', 'Edit Paket Bimbingan')

@section('content')
<div class="pd-form-wrap">
    <h2 style="font-size:1.05rem;font-weight:700;color:#111827;margin-bottom:4px;">Edit Paket Bimbingan</h2>
    <p style="font-size:.78rem;color:#6b7280;margin-bottom:0;">Perbarui informasi paket bimbingan <strong>{{ $paketBimbingan->nama_paket }}</strong>.</p>

    @if ($errors->any())
    <div class="pd-error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('paket-bimbingan.update', $paketBimbingan->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- ── SECTION 1: Informasi Dasar ────────────────── --}}
        <div class="pd-section-title">Informasi Dasar</div>
        <div class="pd-grid">
            <div class="pd-field full">
                <label>Nama Paket <span>*</span></label>
                <input type="text" name="nama_paket" value="{{ old('nama_paket', $paketBimbingan->nama_paket) }}" required placeholder="Misal: Paket Intensif UTBK 2024">
            </div>

            <div class="pd-field">
                <label>Harga Promo (Rp) <span>*</span></label>
                <input type="text" name="nominal" value="{{ old('nominal', $paketBimbingan->nominal) }}" required class="nominal-input" placeholder="500.000">
            </div>

            <div class="pd-field">
                <label>Harga Asli / Coret (Rp)</label>
                <input type="text" name="harga_coret" value="{{ old('harga_coret', $paketBimbingan->harga_coret) }}" class="nominal-input" placeholder="750.000">
            </div>

            <div class="pd-field">
                <label>Durasi Jumlah</label>
                <input type="number" name="durasi_jumlah" value="{{ old('durasi_jumlah', $paketBimbingan->durasi_jumlah) }}" placeholder="6">
            </div>

            <div class="pd-field">
                <label>Durasi Satuan</label>
                <select name="durasi_satuan">
                    <option value="Bulan" {{ old('durasi_satuan', $paketBimbingan->durasi_satuan) == 'Bulan' ? 'selected' : '' }}>Bulan</option>
                    <option value="Tahun" {{ old('durasi_satuan', $paketBimbingan->durasi_satuan) == 'Tahun' ? 'selected' : '' }}>Tahun</option>
                </select>
            </div>

            <div class="pd-field full">
                <label>Label Badge</label>
                <input type="text" name="label_populer" value="{{ old('label_populer', $paketBimbingan->label_populer) }}" placeholder="Misal: Best Seller / Promo">
            </div>
        </div>

        {{-- ── SECTION 2: Detail & Keunggulan ────────────────── --}}
        <div class="pd-section-title">Detail & Keunggulan</div>
        <div class="pd-grid">
            <div class="pd-field full">
                <label>Deskripsi Singkat</label>
                <textarea name="deskripsi" placeholder="Jelaskan secara singkat tentang paket ini...">{{ old('deskripsi', $paketBimbingan->deskripsi) }}</textarea>
            </div>

            <div class="pd-field full">
                <label>Benefit / Fasilitas (Satu per baris)</label>
                <textarea name="benefits" rows="4" style="font-family: monospace;" placeholder="Contoh:&#10;Modul Lengkap PDF&#10;Tryout Berkala&#10;Grup Konsultasi WA">{{ old('benefits', $paketBimbingan->benefits) }}</textarea>
            </div>

            <div class="pd-field">
                <label>Target Peserta</label>
                <input type="text" name="target_peserta" value="{{ old('target_peserta', $paketBimbingan->target_peserta) }}" placeholder="Misal: Siswa SMA / Mahasiswa">
            </div>

            <div class="pd-field">
                <label>Fasilitas Tambahan</label>
                <input type="text" name="fasilitas" value="{{ old('fasilitas', $paketBimbingan->fasilitas) }}" placeholder="Misal: AC, WiFi, Co-working Space">
            </div>
        </div>

        {{-- ── SECTION 3: Media & Pengaturan ────────────────── --}}
        <div class="pd-section-title">Media & Pengaturan</div>
        <div class="pd-grid">
            <div class="pd-field">
                <label>Gambar Paket</label>
                @if($paketBimbingan->gambar_paket)
                    <div style="margin-bottom: 12px; width: 120px; height: 120px; border-radius: 8px; overflow: hidden; border: 1px solid #e5e7eb;">
                        <img src="{{ asset('storage/' . $paketBimbingan->gambar_paket) }}" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                @endif
                <input type="file" name="gambar_paket">
            </div>

            <div class="pd-field" style="justify-content: center; padding-top: 20px;">
                <label style="display: flex; align-items: center; cursor: pointer; gap: 8px; font-weight: 600;">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $paketBimbingan->is_featured) ? 'checked' : '' }} style="width: auto; margin-right: 4px;">
                    Tampilkan sebagai Unggulan di Landing Page
                </label>
            </div>
        </div>

        <div class="pd-btns">
            <a href="{{ route('paket-bimbingan.index') }}" class="pd-btn-back">Batal</a>
            <button type="submit" class="pd-btn-save">Simpan</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function formatRupiah(angka, prefix) {
        if (!angka) return '';
        var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

    document.querySelectorAll('.nominal-input').forEach(input => {
        if (input.value) {
            input.value = formatRupiah(input.value);
        }

        input.addEventListener('keyup', function(e) {
            this.value = formatRupiah(this.value);
        });
    });
</script>
@endsection