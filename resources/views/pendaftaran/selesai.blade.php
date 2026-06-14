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
        @php
            $masterWa = \App\Models\MasterData\Master::first()?->wa_number ?? '6281234567890';
        @endphp
        Terima kasih! Pendaftaran Anda telah kami terima. Akun Anda saat ini belum diverifikasi oleh Admin. Mohon tunggu beberapa saat untuk proses verifikasi data dan bukti pembayaran Anda. Jika tak kunjung diverifikasi, silakan hubungi Admin Pusat melalui WhatsApp.
    </p>
    <div style="margin-top: 16px;">
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $masterWa) }}" target="_blank" style="display:inline-flex;align-items:center;gap:8px;background-color:#25D366;color:white;padding:10px 20px;border-radius:99px;text-decoration:none;font-weight:bold;font-size:0.9rem;box-shadow:0 4px 6px rgba(37,211,102,0.2);">
            <svg style="width:18px;height:18px;" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
            Hubungi Admin
        </a>
    </div>

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
