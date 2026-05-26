@extends('layouts.admin')

@section('title', 'Tambah Paket Bimbingan')

@section('content')
<div class="pd-form-wrap">
    <h2 style="font-size:1.05rem;font-weight:700;color:#111827;margin-bottom:4px;">Tambah Paket Bimbingan</h2>
    <p style="font-size:.78rem;color:#6b7280;margin-bottom:0;">Buat paket bimbingan baru untuk ditampilkan di landing page dan sistem pendaftaran.</p>

    @if ($errors->any())
    <div class="pd-error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('paket-bimbingan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- ── SECTION 1: Informasi Dasar ────────────────── --}}
        <div class="pd-section-title">Informasi Dasar</div>
        <div class="pd-grid">
            <div class="pd-field full">
                <label>Nama Paket <span>*</span></label>
                <input type="text" name="nama_paket" value="{{ old('nama_paket') }}" required placeholder="Misal: Paket Intensif UTBK 2024">
            </div>

            <div class="pd-field">
                <label>Harga Promo (Rp) <span>*</span></label>
                <input type="text" name="nominal" value="{{ old('nominal') }}" required class="nominal-input" placeholder="500.000">
            </div>

            <div class="pd-field">
                <label>Harga Asli / Coret (Rp)</label>
                <input type="text" name="harga_coret" value="{{ old('harga_coret') }}" class="nominal-input" placeholder="750.000">
            </div>

            {{-- TAMBAHAN: Kolom DP Minimal --}}
            <div class="pd-field">
                <label>Minimal DP (%) <span>*</span></label>
                <input type="number" name="dp_persen_minimal" value="{{ old('dp_persen_minimal', 10) }}" min="1" max="100" required placeholder="10">
                <small style="font-size: 0.7rem; color: #6b7280; margin-top: 4px; display: block;">Persentase DP wajib saat mendaftar.</small>
            </div>

            {{-- TAMBAHAN: Pengaturan Cicilan --}}
            <div class="pd-field">
                <label>Bisa Dicicil?</label>
                <select name="bisa_dicicil" style="margin-bottom: 0;">
                    <option value="1" {{ old('bisa_dicicil') == '1' ? 'selected' : '' }}>Ya, Bisa Dicicil</option>
                    <option value="0" {{ old('bisa_dicicil') == '0' ? 'selected' : '' }}>Tidak (Hanya Lunas)</option>
                </select>
            </div>

            <div class="pd-field">
                <label>Maksimal Cicilan (Tenor) <span>*</span></label>
                <input type="number" name="max_cicilan" value="{{ old('max_cicilan', 1) }}" min="1" required placeholder="Misal: 6">
                <small style="font-size: 0.7rem; color: #6b7280; margin-top: 4px; display: block;">Berapa kali cicilan maksimal? (Misal: 6)</small>
            </div>

            <div class="pd-field">
                <label>Durasi Paket (Masa Aktif)</label>
                <div style="display: flex; gap: 10px;">
                    <input type="number" name="durasi_jumlah" value="{{ old('durasi_jumlah') }}" placeholder="Angka (Misal: 6)" style="flex: 1; margin-bottom: 0;">
                    <select name="durasi_satuan" style="flex: 1; margin-bottom: 0;">
                        <option value="Bulan" {{ old('durasi_satuan') == 'Bulan' ? 'selected' : '' }}>Bulan</option>
                        <option value="Tahun" {{ old('durasi_satuan') == 'Tahun' ? 'selected' : '' }}>Tahun</option>
                    </select>
                </div>
            </div>

            <div class="pd-field full">
                <label>Label Badge</label>
                <input type="text" name="label_populer" value="{{ old('label_populer') }}" placeholder="Misal: Best Seller / Promo">
            </div>
        </div>

        {{-- ── SECTION 2: Detail & Keunggulan ────────────────── --}}
        <div class="pd-section-title">Detail & Keunggulan</div>
        <div class="pd-grid">
            <div class="pd-field full">
                <label>Deskripsi Singkat</label>
                <textarea name="deskripsi" placeholder="Jelaskan secara singkat tentang paket ini...">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="pd-field full">
                <label>Benefit / Fasilitas (Satu per baris)</label>
                <textarea name="benefits" rows="4" style="font-family: monospace;" placeholder="Contoh:&#10;Modul Lengkap PDF&#10;Tryout Berkala&#10;Grup Konsultasi WA">{{ old('benefits') }}</textarea>
            </div>

            <div class="pd-field">
                <label>Target Peserta</label>
                <input type="text" name="target_peserta" value="{{ old('target_peserta') }}" placeholder="Misal: Siswa SMA / Mahasiswa">
            </div>

            <div class="pd-field">
                <label>Fasilitas Tambahan</label>
                <input type="text" name="fasilitas" value="{{ old('fasilitas') }}" placeholder="Misal: AC, WiFi, Co-working Space">
            </div>
        </div>

        {{-- ── SECTION 3: Media & Pengaturan ────────────────── --}}
        <div class="pd-section-title">Media & Pengaturan</div>
        <div class="pd-grid">
            <div class="pd-field">
                <label>Gambar Paket</label>
                <input type="file" name="gambar_paket">
            </div>

            <div class="pd-field" style="justify-content: center; padding-top: 20px;">
                <label style="display: flex; align-items: center; cursor: pointer; gap: 8px; font-weight: 600;">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} style="width: auto; margin-right: 4px;">
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