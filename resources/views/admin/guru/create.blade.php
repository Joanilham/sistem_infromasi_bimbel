@extends('layouts.admin')

@section('title', 'Tambah Guru')

@section('content')
<div class="pd-form-wrap">
    <h2 style="font-size:1.05rem;font-weight:700;color:#111827;margin-bottom:4px;">Tambah Guru Baru</h2>
    <p style="font-size:.78rem;color:#6b7280;margin-bottom:0;">Isi seluruh data guru dengan lengkap dan benar.</p>

    <form action="{{ route('guru.store') }}" method="POST">
        @csrf

        {{-- ── SECTION 1: Data Pribadi ────────────────── --}}
        <div class="pd-section-title">Data Pribadi</div>
        <div class="pd-grid">
            <div class="pd-field full">
                <label>Nama Lengkap <span>*</span></label>
                <input type="text" name="name" required value="{{ old('name') }}" placeholder="Nama lengkap guru">
            </div>
            <div class="pd-field">
                <label>NIP</label>
                <input type="text" name="nip" value="{{ old('nip') }}" placeholder="Nomor Induk Pegawai"
                    inputmode="numeric"
                    oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                    maxlength="20">
            </div>
            <div class="pd-field">
                <label>No. Telp</label>
                <input type="tel" name="no_telp" value="{{ old('no_telp') }}" placeholder="08xxxxxxxxxx"
                    inputmode="numeric"
                    pattern="[0-9]{8,15}"
                    maxlength="15"
                    oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                    title="Hanya boleh angka (8-15 digit)">
            </div>
            <div class="pd-field full">
                <label>Alamat</label>
                <textarea name="alamat" placeholder="Alamat lengkap guru">{{ old('alamat') }}</textarea>
            </div>
        </div>

        {{-- ── SECTION 2: Data Kepegawaian ───────────── --}}
        <div class="pd-section-title">Data Kepegawaian</div>
        <div class="pd-grid">
            <div class="pd-field full">
                <label>Mata Pelajaran <span>*</span></label>
                <input type="text" name="matapelajaran" required value="{{ old('matapelajaran') }}" placeholder="Mata pelajaran yang diajarkan">
            </div>
        </div>

        {{-- ── SECTION 3: Akun ───────────────────────── --}}
        <div class="pd-section-title">Akun Login</div>
        <div class="pd-grid">
            <div class="pd-field full">
                <label>Email <span>*</span></label>
                <input type="email" name="email" required value="{{ old('email') }}" placeholder="Email aktif untuk login">
            </div>
            <div class="pd-field">
                <label>Password <span>*</span></label>
                <input type="password" name="password" required placeholder="Minimal 8 karakter">
            </div>
            <div class="pd-field">
                <label>Konfirmasi Password <span>*</span></label>
                <input type="password" name="password_confirmation" required placeholder="Ulangi password di atas">
            </div>
        </div>

        <div class="pd-action">
            <a href="{{ route('guru.index') }}" class="btn-batal">Batal</a>
            <button type="submit" class="btn-simpan">Simpan</button>
        </div>
    </form>
</div>
@endsection