@extends('layouts.admin')

@section('title', 'Tambah Peserta Didik')

@section('content')
<div class="pd-form-wrap">
    <h2 style="font-size:1.05rem;font-weight:700;color:#111827;margin-bottom:4px;">Tambah Peserta Didik Aktif</h2>
    <p style="font-size:.78rem;color:#6b7280;margin-bottom:0;">Isi seluruh data peserta didik dengan lengkap dan benar.</p>

    <form action="{{ route('peserta-didik.store') }}" method="POST">
        @csrf

        {{-- ── SECTION 1: Data Pribadi ────────────────── --}}
        <div class="pd-section-title">Data Pribadi</div>
        <div class="pd-grid">
            <div class="pd-field full">
                <label>Nama Lengkap <span>*</span></label>
                <input type="text" name="nama_lengkap" required value="{{ old('nama_lengkap') }}" placeholder="Nama lengkap peserta didik">
            </div>
            <div class="pd-field">
                <label>No. Induk <span>*</span></label>
                <input type="text" name="nomor_induk" required value="{{ old('nomor_induk') }}" placeholder="Nomor induk unik">
            </div>
            <div class="pd-field">
                <label>Jenis Kelamin <span>*</span></label>
                <select name="jenis_kelamin" required>
                    <option value="">— Pilih Jenis Kelamin —</option>
                    <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div class="pd-field">
                <label>Tempat Lahir</label>
                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" placeholder="Kota tempat lahir">
            </div>
            <div class="pd-field">
                <label>Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
            </div>
            <div class="pd-field">
                <label>Agama</label>
                <select name="agama">
                    <option value="">— Pilih Agama —</option>
                    @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $agama)
                    <option value="{{ $agama }}" {{ old('agama') === $agama ? 'selected' : '' }}>{{ $agama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="pd-field">
                <label>No. Telepon</label>
                <input type="text" name="no_telepon" value="{{ old('no_telepon') }}" placeholder="08xxxxxxxxxx">
            </div>
            <div class="pd-field full">
                <label>Alamat Lengkap</label>
                <textarea name="alamat_lengkap" placeholder="Alamat rumah lengkap">{{ old('alamat_lengkap') }}</textarea>
            </div>
        </div>

        {{-- ── SECTION 2: Data Akademik ───────────────── --}}
        <div class="pd-section-title">Data Akademik</div>
        <div class="pd-grid">
            <div class="pd-field full">
                <label>Asal Sekolah <span>*</span></label>
                <input type="text" name="asal_sekolah" required value="{{ old('asal_sekolah') }}" placeholder="Nama sekolah asal">
            </div>
            <div class="pd-field">
                <label>Paket Bimbingan Belajar <span>*</span></label>
                <select name="paket_bimbingan_id" required>
                    <option value="">— Pilih Paket —</option>
                    @foreach($paketBimbingans as $paket)
                    <option value="{{ $paket->id }}" {{ old('paket_bimbingan_id') == $paket->id ? 'selected' : '' }}>
                        {{ $paket->nama_paket }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="pd-field">
                <label>Kelompok Belajar <span>*</span></label>
                <select name="kelompok_belajar" required>
                    <option value="">— Pilih Kelompok —</option>
                    @foreach($kelompokBelajars as $kb)
                    <option value="{{ $kb->nama_kelompok }}" {{ old('kelompok_belajar') === $kb->nama_kelompok ? 'selected' : '' }}>
                        {{ $kb->nama_kelompok }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="pd-field full">
                <label>Memperoleh Informasi Dari</label>
                <input type="text" name="informasi_dari" value="{{ old('informasi_dari') }}" placeholder="Misal: media sosial, teman, brosur, dll">
            </div>
        </div>

        {{-- ── SECTION 3: Data Orang Tua ──────────────── --}}
        <div class="pd-section-title">Data Orang Tua / Wali</div>
        <div class="pd-grid">
            <div class="pd-field">
                <label>Nama Ayah</label>
                <input type="text" name="nama_ayah" value="{{ old('nama_ayah') }}">
            </div>
            <div class="pd-field">
                <label>Pekerjaan Ayah</label>
                <input type="text" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah') }}">
            </div>
            <div class="pd-field">
                <label>No. Telepon Ayah</label>
                <input type="text" name="no_telepon_ayah" value="{{ old('no_telepon_ayah') }}" placeholder="08xxxxxxxxxx">
            </div>
            <div class="pd-field">
                <label>Nama Ibu</label>
                <input type="text" name="nama_ibu" value="{{ old('nama_ibu') }}">
            </div>
            <div class="pd-field">
                <label>Pekerjaan Ibu</label>
                <input type="text" name="pekerjaan_ibu" value="{{ old('pekerjaan_ibu') }}">
            </div>
            <div class="pd-field">
                <label>No. Telepon Ibu</label>
                <input type="text" name="no_telepon_ibu" value="{{ old('no_telepon_ibu') }}" placeholder="08xxxxxxxxxx">
            </div>
        </div>

        {{-- Error messages --}}
        @if($errors->any())
        <div class="pd-error">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Buttons --}}
        <div class="pd-btns">
            <a href="{{ route('peserta-didik.index') }}" class="pd-btn-back">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
            <button type="submit" class="pd-btn-save">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection