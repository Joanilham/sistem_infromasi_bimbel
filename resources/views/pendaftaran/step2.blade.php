@extends('layouts.pendaftaran')
@section('title', 'Langkah 2 - Data Diri')
@section('step1_class', 'done') @section('step1_label_class', 'done')
@section('line1_class', 'done')
@section('step2_class', 'active') @section('step2_label_class', 'active')
@section('step3_class', '') @section('step4_class', '')

@section('content')
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
                <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap') }}" placeholder="Nama lengkap siswa" required>
                @error('nama_lengkap')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label>No. Induk (NISN)</label>
                <input type="text" name="nisn" class="form-control" value="{{ old('nisn') }}" placeholder="NISN (opsional)"
                    inputmode="numeric"
                    maxlength="10"
                    oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                    title="NISN hanya boleh berisi angka">
            </div>
            <div class="form-group">
                <label>Jenis Kelamin <span class="req">*</span></label>
                <select name="jenis_kelamin" class="form-control" required>
                    <option value="">— Pilih —</option>
                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label>Tempat Lahir</label>
                <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir') }}" placeholder="Kota tempat lahir">
            </div>
            <div class="form-group">
                <label>Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}">
            </div>
            <div class="form-group">
                <label>Agama</label>
                <select name="agama" class="form-control">
                    <option value="">— Pilih Agama —</option>
                    @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $ag)
                    <option value="{{ $ag }}" {{ old('agama') == $ag ? 'selected' : '' }}>{{ $ag }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>No. Telepon</label>
                <input type="tel" name="no_telepon" class="form-control" value="{{ old('no_telepon') }}" placeholder="08xxxxxxxxxx"
                    inputmode="numeric"
                    pattern="[0-9]{8,15}"
                    maxlength="15"
                    oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                    title="Hanya boleh angka (8-15 digit)">
            </div>
            <div class="form-group form-col-full">
                <label>Alamat Lengkap</label>
                <textarea name="alamat_lengkap" class="form-control" placeholder="Alamat rumah lengkap">{{ old('alamat_lengkap') }}</textarea>
            </div>
        </div>

        {{-- DATA AKADEMIK --}}
        <p class="section-label">DATA AKADEMIK</p>
        <div class="form-grid">
            <div class="form-group form-col-full">
                <label>Asal Sekolah <span class="req">*</span></label>
                <input type="text" name="asal_sekolah" class="form-control" value="{{ old('asal_sekolah') }}" placeholder="Nama sekolah asal" required>
                @error('asal_sekolah')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label>Paket Bimbingan Belajar</label>
                <select name="paket_bimbingan_id" class="form-control">
                    <option value="">— Pilih Paket —</option>
                    @foreach($pakets as $p)
                    <option value="{{ $p->id }}" {{ old('paket_bimbingan_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->nama_paket }} @if($p->nominal) (Rp {{ number_format($p->nominal,0,',','.') }}) @endif
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Kelompok Belajar</label>
                <select name="kelompok_belajar_id" class="form-control">
                    <option value="">— Pilih Kelompok —</option>
                    @foreach($kelompoks as $k)
                    <option value="{{ $k->id }}" {{ old('kelompok_belajar_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelompok }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group form-col-full">
                <label>Memperoleh Informasi Dari</label>
                <input type="text" name="informasi_dari" class="form-control" value="{{ old('informasi_dari') }}" placeholder="Media sosial, teman, brosur, dll">
            </div>
        </div>

        {{-- DATA ORANG TUA --}}
        <p class="section-label">DATA ORANG TUA / WALI</p>
        <div class="form-grid">
            <div class="form-group">
                <label>Nama Ayah</label>
                <input type="text" name="nama_ayah" class="form-control" value="{{ old('nama_ayah') }}">
            </div>
            <div class="form-group">
                <label>Pekerjaan Ayah</label>
                <input type="text" name="pekerjaan_ayah" class="form-control" value="{{ old('pekerjaan_ayah') }}">
            </div>
            <div class="form-group">
                <label>No. Telepon Ayah</label>
                <input type="tel" name="no_telepon_ayah" class="form-control" value="{{ old('no_telepon_ayah') }}" placeholder="08xxxxxxxxxx"
                    inputmode="numeric"
                    pattern="[0-9]{8,15}"
                    maxlength="15"
                    oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                    title="Hanya boleh angka (8-15 digit)">
            </div>
            <div class="form-group">
                <label>Nama Ibu</label>
                <input type="text" name="nama_ibu" class="form-control" value="{{ old('nama_ibu') }}">
            </div>
            <div class="form-group">
                <label>Pekerjaan Ibu</label>
                <input type="text" name="pekerjaan_ibu" class="form-control" value="{{ old('pekerjaan_ibu') }}">
            </div>
            <div class="form-group">
                <label>No. Telepon Ibu</label>
                <input type="tel" name="no_telepon_ibu" class="form-control" value="{{ old('no_telepon_ibu') }}" placeholder="08xxxxxxxxxx"
                    inputmode="numeric"
                    pattern="[0-9]{8,15}"
                    maxlength="15"
                    oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                    title="Hanya boleh angka (8-15 digit)">
            </div>
        </div>

        <div class="btn-row">
            <a href="{{ route('daftar.step1') }}" class="btn btn-secondary">← Kembali</a>
            <button type="submit" class="btn btn-primary">Lanjut ke Pembayaran →</button>
        </div>
    </form>
</div>
@endsection
