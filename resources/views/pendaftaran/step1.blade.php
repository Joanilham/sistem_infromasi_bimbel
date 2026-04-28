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
                    <option value="{{ $kantor->id }}" {{ old('kantor_id') == $kantor->id ? 'selected' : '' }}>
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
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="contoh@email.com" required>
                @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label>Password <span class="req">*</span></label>
                <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
                @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label>Konfirmasi Password <span class="req">*</span></label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
            </div>
        </div>

        <div class="btn-row">
            <a href="{{ route('welcome') }}" class="btn btn-secondary">← Kembali</a>
            <button type="submit" class="btn btn-primary" @if($kantors->isEmpty()) disabled @endif>Lanjut →</button>
        </div>
    </form>
</div>
@endsection
