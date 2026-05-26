@extends('layouts.admin')

@section('title', 'Edit Guru')

@section('content')
<div class="pd-form-wrap">
    <h2 style="font-size:1.05rem;font-weight:700;color:#111827;margin-bottom:4px;">Edit Data Guru</h2>
    <p style="font-size:.78rem;color:#6b7280;margin-bottom:0;">Perbarui data guru dengan lengkap dan benar.</p>

    <form action="{{ route('manajemen-guru.update', $guru->id) }}" method="POST" id="guruForm">
        @csrf
        @method('PUT')

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const statusSelect = document.querySelector('select[name="status"]');
                const keluarFields = document.getElementById('keluarFields');
                
                function toggleKeluarFields() {
                    if (statusSelect && keluarFields) {
                        keluarFields.style.display = statusSelect.value === 'Keluar' ? 'block' : 'none';
                    }
                }
                
                if (statusSelect) {
                    statusSelect.addEventListener('change', toggleKeluarFields);
                    toggleKeluarFields();
                }
            });
        </script>

        {{-- ── SECTION 1: Data Pribadi ────────────────── --}}
        <div class="pd-section-title">Data Pribadi</div>
        <div class="pd-grid">
            <div class="pd-field full">
                <label>Nama Lengkap <span>*</span></label>
                <input type="text" name="name" required value="{{ old('name', $guru->name) }}" placeholder="Nama lengkap guru">
            </div>
            <div class="pd-field">
                <label>NIP</label>
                <input type="text" name="nip" value="{{ old('nip', $guru->nip) }}" placeholder="Nomor Induk Pegawai"
                    inputmode="numeric"
                    oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                    maxlength="20">
            </div>
            <div class="pd-field">
                <label>No. Telp</label>
                <input type="tel" name="no_telp" value="{{ old('no_telp', $guru->no_telp) }}" placeholder="08xxxxxxxxxx"
                    inputmode="numeric"
                    pattern="[0-9]{8,12}"
                    maxlength="12"
                    oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                    title="Hanya boleh angka (Maksimal 12 digit)">
            </div>
            <div class="pd-field full">
                <label>Alamat</label>
                <textarea name="alamat" placeholder="Alamat lengkap guru">{{ old('alamat', $guru->alamat) }}</textarea>
            </div>
        </div>

        {{-- ── SECTION 2: Data Kepegawaian ───────────── --}}
        <div class="pd-section-title">Data Kepegawaian</div>
        <div class="pd-grid">
            <div class="pd-field full">
                <label>Mata Pelajaran <span>*</span></label>
                <input type="text" name="matapelajaran" required value="{{ old('matapelajaran', $guru->matapelajaran) }}" placeholder="Mata pelajaran yang diajarkan">
            </div>
            <div class="pd-field">
                <label>Status: <span>*</span></label>
                <select name="status" id="statusSelect">
                    <option value="Aktif" {{ old('status', $guru->status ?? 'Aktif') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Keluar" {{ old('status', $guru->status ?? 'Aktif') === 'Keluar' ? 'selected' : '' }}>Keluar</option>
                </select>
            </div>
        </div>

        {{-- Conditional: fields Keluar --}}
        <div id="keluarFields"
            style="margin-top:1.2rem; padding:1.2rem; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; display: {{ old('status', $guru->status ?? 'Aktif') === 'Keluar' ? 'block' : 'none' }}">
            <div style="font-size:.78rem; font-weight:700; color:#dc2626; text-transform:uppercase; margin-bottom:1rem; letter-spacing:0.05em">Informasi Keluar</div>
            <div class="pd-grid">
                <div class="pd-field">
                    <label>Tanggal Keluar: <span style="color:#ef4444">*</span></label>
                    <input type="date" name="tanggal_keluar"
                        value="{{ old('tanggal_keluar', $guru->tanggal_keluar) }}"
                        :required="statusKeluar === 'Keluar'"
                        style="border-color:#fca5a5">
                </div>
                <div class="pd-field full">
                    <label>Alasan Keluar: <span style="color:#ef4444">*</span></label>
                    <textarea name="alasan_keluar" rows="2"
                        :required="statusKeluar === 'Keluar'"
                        placeholder="Tuliskan alasan guru keluar..."
                        style="border-color:#fca5a5">{{ old('alasan_keluar', $guru->alasan_keluar) }}</textarea>
                </div>
            </div>
        </div>

        {{-- ── SECTION 3: Akun ───────────────────────── --}}
        <div class="pd-section-title">Akun Login</div>
        <div class="pd-grid">
            <div class="pd-field full">
                <label>Email <span>*</span></label>
                <input type="email" name="email" required value="{{ old('email', $guru->email) }}" placeholder="Email aktif untuk login">
            </div>
            <div class="pd-field">
                <label>Password <span class="text-slate-400 font-normal">(Kosongkan jika tidak ingin mengubah)</span></label>
                <input type="password" name="password" placeholder="Minimal 8 karakter">
            </div>
            <div class="pd-field">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" placeholder="Ulangi password di atas">
            </div>
        </div>

        <div class="pd-btns">
            <a href="{{ route('manajemen-guru.index') }}" class="pd-btn-back">Batal</a>
            <button type="submit" class="pd-btn-save">Simpan</button>
        </div>
    </form>
</div>
@endsection