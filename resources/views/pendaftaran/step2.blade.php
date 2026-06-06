@extends('layouts.pendaftaran')
@section('title', 'Langkah 2 - Data Diri')
@section('step1_class', 'done') @section('step1_label_class', 'done')
@section('line1_class', 'done')
@section('step2_class', 'active') @section('step2_label_class', 'active')
@section('step3_class', '') @section('step4_class', '')

@section('extra_style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.4/build/css/intlTelInput.css">
<style>
    .iti { width: 100%; }
</style>
@endsection

@section('content')
@php
    $sessData = Session::get('daftar_data', []);
@endphp
<div class="card">
    <div class="card-title">Data Diri Siswa</div>
    <div class="card-sub">Lengkapi data diri Anda dengan benar sesuai dokumen resmi.</div>

    @if($errors->any())
    <div class="alert-danger">
        <strong>Mohon periksa kembali:</strong>
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('daftar.step2.store') }}">
        @csrf
        {{-- DATA PRIBADI --}}
        <p class="section-label">DATA PRIBADI</p>
        <div class="form-grid">
            <div class="form-group form-col-full">
                <label>Nama Lengkap <span class="req">*</span></label>
                <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $sessData['nama_lengkap'] ?? '') }}" placeholder="Nama lengkap siswa" required>
                @error('nama_lengkap')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label>No. Induk (NISN)</label>
                <input type="text" name="nisn" class="form-control" value="{{ old('nisn', $sessData['nisn'] ?? '') }}" placeholder="NISN (opsional)"
                    inputmode="numeric"
                    maxlength="10"
                    oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                    title="NISN hanya boleh berisi angka">
            </div>
            <div class="form-group">
                <label>Jenis Kelamin <span class="req">*</span></label>
                <select name="jenis_kelamin" class="form-control" required>
                    <option value="">— Pilih —</option>
                    <option value="L" {{ old('jenis_kelamin', $sessData['jenis_kelamin'] ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin', $sessData['jenis_kelamin'] ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label>Tempat Lahir</label>
                <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $sessData['tempat_lahir'] ?? '') }}" placeholder="Kota tempat lahir">
            </div>
            <div class="form-group">
                <label>Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $sessData['tanggal_lahir'] ?? '') }}">
            </div>
            <div class="form-group">
                <label>Agama</label>
                <select name="agama" class="form-control">
                    <option value="">— Pilih Agama —</option>
                    @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $ag)
                    <option value="{{ $ag }}" {{ old('agama', $sessData['agama'] ?? '') == $ag ? 'selected' : '' }}>{{ $ag }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>No. Telepon / WhatsApp <span class="req">*</span></label>
                <input type="tel" name="no_telepon" id="no_telepon" class="form-control" value="{{ old('no_telepon', $sessData['no_telepon'] ?? '') }}" maxlength="15" required>
                @error('no_telepon')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group form-col-full">
                <label>Alamat Lengkap <span class="req">*</span></label>
                <textarea name="alamat_lengkap" class="form-control" placeholder="Alamat rumah lengkap" required>{{ old('alamat_lengkap', $sessData['alamat_lengkap'] ?? '') }}</textarea>
                @error('alamat_lengkap')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
        </div>

        {{-- DATA AKADEMIK --}}
        <p class="section-label">DATA AKADEMIK</p>
        <div class="form-grid">
            <div class="form-group form-col-full">
                <label>Asal Sekolah <span class="req">*</span></label>
                <input type="text" name="asal_sekolah" class="form-control" value="{{ old('asal_sekolah', $sessData['asal_sekolah'] ?? '') }}" placeholder="Nama sekolah asal" required>
                @error('asal_sekolah')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group form-col-full">
                <label>Memperoleh Informasi Dari</label>
                <input type="text" name="informasi_dari" class="form-control" value="{{ old('informasi_dari', $sessData['informasi_dari'] ?? '') }}" placeholder="Media sosial, teman, brosur, dll">
            </div>
        </div>

        {{-- DATA ORANG TUA --}}
        <p class="section-label">DATA ORANG TUA / WALI</p>
        <div style="background: #EFF6FF; border: 1.5px solid #BFDBFE; border-radius: 10px; padding: 10px 14px; margin-bottom: 14px; font-size: 0.8rem; color: #1E40AF; font-weight: 600; display: flex; align-items: flex-start; gap: 8px;">
            <svg style="width:18px;height:18px;flex-shrink:0;margin-top:2px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <div>Wajib mengisi minimal data <strong>salah satu</strong> orang tua/wali (Nama + No. Telepon).</div>
        </div>
        @error('nama_ayah')<div class="alert-danger" style="margin-bottom:10px;padding:8px 14px;font-size:0.82rem;">{{ $message }}</div>@enderror
        @error('no_telepon_ayah')<div class="alert-danger" style="margin-bottom:10px;padding:8px 14px;font-size:0.82rem;">{{ $message }}</div>@enderror
        <div class="form-grid">
            <div class="form-group">
                <label>Nama Ayah <span class="req">*</span></label>
                <input type="text" name="nama_ayah" class="form-control" value="{{ old('nama_ayah', $sessData['nama_ayah'] ?? '') }}" placeholder="Nama lengkap ayah">
            </div>
            <div class="form-group">
                <label>Pekerjaan Ayah</label>
                <input type="text" name="pekerjaan_ayah" class="form-control" value="{{ old('pekerjaan_ayah', $sessData['pekerjaan_ayah'] ?? '') }}">
            </div>
            <div class="form-group">
                <label>No. Telepon Ayah <span class="req">*</span></label>
                <input type="tel" name="no_telepon_ayah" id="no_telepon_ayah" class="form-control" value="{{ old('no_telepon_ayah', $sessData['no_telepon_ayah'] ?? '') }}" maxlength="15">
            </div>
            <div class="form-group">
                <label>Nama Ibu <span class="req">*</span></label>
                <input type="text" name="nama_ibu" class="form-control" value="{{ old('nama_ibu', $sessData['nama_ibu'] ?? '') }}" placeholder="Nama lengkap ibu">
            </div>
            <div class="form-group">
                <label>Pekerjaan Ibu</label>
                <input type="text" name="pekerjaan_ibu" class="form-control" value="{{ old('pekerjaan_ibu', $sessData['pekerjaan_ibu'] ?? '') }}">
            </div>
            <div class="form-group">
                <label>No. Telepon Ibu <span class="req">*</span></label>
                <input type="tel" name="no_telepon_ibu" id="no_telepon_ibu" class="form-control" value="{{ old('no_telepon_ibu', $sessData['no_telepon_ibu'] ?? '') }}" maxlength="15">
            </div>
        </div>

        <div class="btn-row">
            <a href="{{ route('daftar.step1') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Lanjut ke Pembayaran</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.4/build/js/intlTelInput.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const phoneInputs = [
        document.querySelector("#no_telepon"),
        document.querySelector("#no_telepon_ayah"),
        document.querySelector("#no_telepon_ibu")
    ];
    
    const itiInstances = [];

    phoneInputs.forEach(input => {
        if(input) {
            const iti = window.intlTelInput(input, {
                initialCountry: "id",
                preferredCountries: ["id", "my", "sg", "au"],
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.4/build/js/utils.js",
                showSelectedDialCode: true,
                countrySearch: true,
                strictMode: true
            });
            itiInstances.push({ input: input, iti: iti });
        }
    });

    const form = document.querySelector('form');
    form.addEventListener('submit', function() {
        itiInstances.forEach(item => {
            if (item.input.value.trim() !== '') {
                item.input.value = item.iti.getNumber();
            }
        });
    });
});
</script>
@endsection
