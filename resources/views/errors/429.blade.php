@extends('errors.layout-card')

@section('page_title', '429 — Too Many Requests')

@section('code-color', '#9333EA')
@section('badge-bg', '#F3E8FF')
@section('badge-text', '#6B21A8')
@section('badge-dot', '#9333EA')

@section('icon')
    🚦
@endsection

@section('badge', 'Too Many Requests')

@section('title', 'Wah, Kecepatan Penuh!')

@section('message')
    Sabar ya.. Anda menekan tombol atau memuat halaman terlalu cepat dalam waktu singkat. Mari istirahat sejenak lalu coba lagi nanti.
@endsection

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-outline">
        Kembali
    </a>
    <a href="{{ url('/') }}" class="btn btn-outline">
        Ke Beranda
    </a>
@endsection

@section('extra-styles')
<style>
    @media (prefers-color-scheme: dark) {
        :root {
            --bg-badge: rgba(147, 51, 234, 0.1);
            --text-badge: #D8B4FE;
        }
    }
</style>
@endsection
