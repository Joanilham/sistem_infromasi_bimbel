@extends('layouts.pendaftaran')
@section('title', 'Pendaftaran Selesai')
@section('step1_class', 'done') @section('step1_label_class', 'done')
@section('line1_class', 'done')
@section('step2_class', 'done') @section('step2_label_class', 'done')
@section('line2_class', 'done')
@section('step3_class', 'done') @section('step3_label_class', 'done')
@section('line3_class', 'done')
@section('step4_class', 'done') @section('step4_label_class', 'done')

@section('extra_style')
<style>
.success-icon { width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #10B981, #059669); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 2.5rem; }
.success-card { text-align: center; }
.steps-list { text-align: left; max-width: 420px; margin: 24px auto 0; }
.step-item { display: flex; align-items: flex-start; gap: 12px; padding: 12px 0; border-bottom: 1px solid #F1F5F9; }
.step-item:last-child { border-bottom: none; }
.step-dot { width: 28px; height: 28px; border-radius: 50%; background: #EEF2FF; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 700; color: #4F46E5; flex-shrink: 0; }
.step-item-text { font-size: 0.85rem; color: #64748B; line-height: 1.5; }
.step-item-text strong { color: #0F172A; }
</style>
@endsection

@section('content')
<div class="card success-card">
    <div class="success-icon">✓</div>
    <h2 style="font-size:1.5rem;font-weight:900;color:#0F172A;margin-bottom:10px;">Pendaftaran Berhasil Dikirim!</h2>
    <p style="color:#64748B;font-size:0.95rem;line-height:1.7;max-width:480px;margin:0 auto;">
        Terima kasih! Pendaftaran Anda telah kami terima. Tim admin akan memverifikasi data dan bukti pembayaran Anda.
    </p>

    @if(isset($pendaftaran) && $pendaftaran->pembayaran)
    <div style="background-color: #F8FAFC; border: 1px dashed #CBD5E1; border-radius: 16px; padding: 24px; margin: 32px auto 0; max-width: 420px; text-align: left;">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #E2E8F0; padding-bottom: 12px; margin-bottom: 16px;">
            <h3 style="font-size: 1rem; font-weight: 800; color: #334155; margin: 0;">Nota Pendaftaran</h3>
            <span style="background-color: #FEF3C7; color: #D97706; font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 99px; text-transform: uppercase;">
                {{ $pendaftaran->pembayaran->status }}
            </span>
        </div>
        
        <div style="display: flex; flex-direction: column; gap: 12px; font-size: 0.9rem;">
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #64748B;">Nama Pendaftar</span>
                <span style="font-weight: 600; color: #0F172A;">{{ $pendaftaran->nama_lengkap }}</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #64748B;">Paket Bimbingan</span>
                <span style="font-weight: 600; color: #0F172A;">{{ $pendaftaran->paketBimbingan?->nama_paket ?? '-' }}</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #64748B;">Metode Pembayaran</span>
                <span style="font-weight: 600; color: #0F172A;">{{ $pendaftaran->pembayaran->metode_pembayaran }}</span>
            </div>
            
            <div style="border-top: 1px dashed #CBD5E1; margin-top: 4px; padding-top: 16px; display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #64748B; font-weight: 600;">Total Dibayar</span>
                <span style="font-weight: 800; color: #10B981; font-size: 1.1rem;">Rp {{ number_format($pendaftaran->pembayaran->jumlah, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
    @endif

    <div class="steps-list">
        <div class="step-item">
            <div class="step-dot">1</div>
            <div class="step-item-text"><strong>Verifikasi Data</strong><br>Admin akan memeriksa data dan bukti pembayaran Anda.</div>
        </div>
        <div class="step-item">
            <div class="step-dot">2</div>
            <div class="step-item-text"><strong>Konfirmasi via WhatsApp</strong><br>Kami akan menghubungi Anda jika ada informasi tambahan.</div>
        </div>
        <div class="step-item">
            <div class="step-dot">3</div>
            <div class="step-item-text"><strong>Aktivasi Akun</strong><br>Setelah terverifikasi, akun Anda akan diaktifkan dan Anda bisa menggunakan sistem</div>
        </div>
    </div>

    <div style="margin-top:28px;display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
        <a href="{{ route('welcome') }}" class="btn btn-secondary">Kembali ke Beranda</a>
        <a href="{{ route('login') }}" class="btn btn-primary">Login ke Akun</a>
    </div>
</div>
@endsection
