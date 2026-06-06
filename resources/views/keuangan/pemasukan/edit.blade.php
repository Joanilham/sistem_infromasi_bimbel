@extends('layouts.admin')

@section('title', 'Edit Pemasukan')

@section('content')
<div class="pd-form-wrap">
    <h2 style="font-size:1.05rem;font-weight:700;color:#111827;margin-bottom:4px;">Edit Transaksi Pemasukan</h2>
    <p style="font-size:.78rem;color:#6b7280;margin-bottom:0;">Perbarui informasi catatan pemasukan keuangan.</p>

    @if ($errors->any())
    <div class="pd-error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('keuangan.pemasukan.update', $pemasukan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="pd-section-title">Data Transaksi</div>
        <div class="pd-grid">
            <div class="pd-field">
                <label>Tanggal Transaksi <span>*</span></label>
                <input type="date" name="tanggal" value="{{ old('tanggal', $pemasukan->tanggal->format('Y-m-d')) }}" required>
            </div>

            <div class="pd-field">
                <label>Kategori Pemasukan <span>*</span></label>
                <select name="kategori_id" required>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ old('kategori_id', $pemasukan->kategori_id) == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="pd-field">
                <label>Nominal Pemasukan (IDR) <span>*</span></label>
                <input type="text" inputmode="numeric" name="nominal" value="{{ old('nominal', $pemasukan->nominal) }}" class="nominal-format" required placeholder="0">
            </div>

            <div class="pd-field full">
                <label>Keterangan</label>
                <input type="text" name="keterangan" value="{{ old('keterangan', $pemasukan->keterangan) }}" placeholder="Contoh: Pembayaran pendaftaran bimbingan...">
            </div>
        </div>

        <div class="pd-btns">
            <a href="{{ route('keuangan.pemasukan.index') }}" class="pd-btn-back">Batal</a>
            <button type="submit" class="pd-btn-save">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
