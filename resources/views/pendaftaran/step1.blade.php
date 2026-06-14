@extends('layouts.pendaftaran')
@section('title', 'Langkah 1 - Buat Akun')
@section('step1_class', 'active')
@section('step1_label_class', 'active')
@section('step2_class', '')
@section('step3_class', '')
@section('step4_class', '')

@section('content')
<div class="card">
    <div class="card-title">Buat Akun Siswa</div>
    <div class="card-sub">Masukkan email, password, dan pilih kantor/cabang yang ingin Anda daftarkan.</div>

    @if($errors->any())
    <div class="alert-danger">
        <strong>Mohon periksa kembali:</strong>
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('daftar.step1.store') }}">
        @csrf

        {{-- Pilih Kantor --}}
        <p class="section-label">KANTOR / CABANG TUJUAN</p>
        <div class="form-grid full">
            <div class="form-group">
                <label>Kantor / Cabang <span class="req">*</span></label>
                <select name="kantor_id" class="form-control" required>
                    <option value="">— Pilih Kantor / Cabang —</option>
                    @foreach($kantors as $kantor)
                    <option value="{{ $kantor->id }}" {{ old('kantor_id', Session::get('daftar_kantor_id')) == $kantor->id ? 'selected' : '' }}>
                        {{ $kantor->nama_kantor }}
                        @if($kantor->alamat) – {{ Str::limit($kantor->alamat, 40) }}@endif
                    </option>
                    @endforeach
                </select>
                @error('kantor_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                @if($kantors->isEmpty())
                <span class="invalid-feedback">Belum ada kantor tersedia. Hubungi administrator.</span>
                @endif
            </div>
        </div>

        {{-- Data Akun --}}
        <p class="section-label">DATA AKUN</p>
        <div class="form-grid full">
            <div class="form-group">
                <label>Email <span class="req">*</span></label>
                <input type="email" name="email" class="form-control" value="{{ old('email', Session::get('daftar_email')) }}" placeholder="contoh: genius@gmail.com" required>
                <div style="font-size: 0.75rem; font-weight: 600; margin-top: 4px; color: #F59E0B; display: flex; align-items: center; gap: 4px;">
                    <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Pastikan email aktif untuk verifikasi dan reset password.
                </div>
                @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label>Password <span class="req">*</span></label>
                <input type="password" name="password" id="reg-password" class="form-control" value="{{ old('password', Session::get('daftar_password')) }}" placeholder="Minimal 8 karakter (Huruf besar, kecil, angka, simbol)" required>
                <div class="pw-strength-bar" style="height: 6px; background: #E2E8F0; border-radius: 4px; margin-top: 8px; overflow: hidden;">
                    <div id="pw-fill" style="height: 100%; width: 0%; transition: all 0.3s ease;"></div>
                </div>
                <div style="font-size: 0.75rem; font-weight: 600; margin-top: 4px; display: flex; justify-content: space-between;">
                    <span id="pw-text" style="color: #94A3B8;">Kekuatan Sandi</span>
                </div>
                @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label>Konfirmasi Password <span class="req">*</span></label>
                <input type="password" name="password_confirmation" id="reg-password-confirm" class="form-control" value="{{ old('password_confirmation', Session::get('daftar_password')) }}" placeholder="Ulangi password" required>
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: 8px; margin-top: 16px; margin-bottom: 8px;">
            <input type="checkbox" id="show-passwords" style="cursor: pointer; width: 16px; height: 16px;" onclick="document.getElementById('reg-password').type = this.checked ? 'text' : 'password'; document.getElementById('reg-password-confirm').type = this.checked ? 'text' : 'password';">
            <label for="show-passwords" style="font-size: 0.85rem; color: #64748B; cursor: pointer; user-select: none;">Tampilkan kata sandi</label>
        </div>

        <div class="btn-row">
            <a href="{{ route('welcome') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary" @if($kantors->isEmpty()) disabled @endif>Lanjut</button>
        </div>
    </form>
</div>

<script>
    const pwInput = document.getElementById('reg-password');
    const pwFill = document.getElementById('pw-fill');
    const pwText = document.getElementById('pw-text');

    pwInput.addEventListener('input', function() {
        const val = this.value;
        let score = 0;
        
        if (val.length >= 8) score += 1;
        if (/[A-Z]/.test(val)) score += 1;
        if (/[a-z]/.test(val)) score += 1;
        if (/[0-9]/.test(val)) score += 1;
        if (/[^A-Za-z0-9]/.test(val)) score += 1;

        let pct = (score / 5) * 100;
        pwFill.style.width = pct + '%';

        if (val.length === 0) {
            pwFill.style.background = 'transparent';
            pwText.textContent = 'Kekuatan Sandi';
            pwText.style.color = '#94A3B8';
        } else if (score <= 2) {
            pwFill.style.background = '#EF4444';
            pwText.textContent = 'Lemah (Gunakan huruf, angka & simbol)';
            pwText.style.color = '#EF4444';
        } else if (score <= 4) {
            pwFill.style.background = '#F59E0B';
            pwText.textContent = 'Sedang (Tambahkan simbol/angka)';
            pwText.style.color = '#F59E0B';
        } else {
            pwFill.style.background = '#10B981';
            pwText.textContent = 'Sangat Kuat';
            pwText.style.color = '#10B981';
        }
    });
</script>
@endsection
