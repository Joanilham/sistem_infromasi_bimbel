@extends('layouts.admin')

@section('title', 'Edit Transaksi Pembayaran')

@section('content')
<div class="pd-form-wrap">
    <h2 style="font-size:1.05rem;font-weight:700;color:#111827;margin-bottom:4px;">Edit Transaksi Pembayaran</h2>
    <p style="font-size:.78rem;color:#6b7280;margin-bottom:0;">Perbarui detail transaksi pembayaran SPP untuk siswa <strong>{{ $pesertaDidik->nama_lengkap }}</strong>.</p>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-2xl px-6 py-4 text-sm font-bold mt-4">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('keuangan.transaksi.update', $transaksiPembayaran->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- ── SECTION 1: Detail Transaksi ────────────────── --}}
        <div class="pd-section-title">Detail Transaksi</div>
        <div class="pd-grid">
            <div class="pd-field">
                <label>Nominal Pembayaran (Rp) <span>*</span></label>
                <input type="number" name="nominal" required value="{{ old('nominal', $transaksiPembayaran->nominal) }}" placeholder="Masukkan nominal pembayaran...">
            </div>
            
            <div class="pd-field">
                <label>Tanggal Transaksi <span>*</span></label>
                <input type="date" name="tanggal" required value="{{ old('tanggal', $transaksiPembayaran->tanggal->format('Y-m-d')) }}">
            </div>

            <div class="pd-field">
                <label>Metode Pembayaran <span>*</span></label>
                <select name="tipe_pembayaran" required>
                    <option value="TUNAI" {{ old('tipe_pembayaran', $transaksiPembayaran->tipe_pembayaran) === 'TUNAI' ? 'selected' : '' }}>TUNAI</option>
                    <option value="TRANSFER" {{ old('tipe_pembayaran', $transaksiPembayaran->tipe_pembayaran) === 'TRANSFER' ? 'selected' : '' }}>TRANSFER</option>
                </select>
            </div>

            <div class="pd-field">
                <label>No. Kwitansi (Otomatis)</label>
                <input type="text" value="{{ $transaksiPembayaran->no_kwitansi }}" readonly style="background-color:#f3f4f6; color:#9ca3af; cursor:not-allowed;">
            </div>
        </div>

        <div class="pd-btns">
            <a href="{{ route('keuangan.pembayaran.show', $pesertaDidik->id) }}" class="pd-btn-back">Batal</a>
            <button type="submit" class="pd-btn-save">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
