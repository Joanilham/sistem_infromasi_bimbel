@extends('errors.layout-card')

@section('page_title', '401 — Unauthorized')

@section('code-color', '#475569')
@section('badge-bg', '#F1F5F9')
@section('badge-text', '#334155')
@section('badge-dot', '#475569')

@section('icon')
    🔒
@endsection

@section('badge', 'Unauthorized')

@section('title', 'Anda Harus Login Dulu')

@section('message')
    Sstt.. halaman ini butuh akses masuk khusus. Sepertinya Anda belum login atau sesi Anda sudah habis. Yuk, login dulu!
@endsection

@section('actions')
    <a href="{{ route('login') ?? url('/login') }}" class="btn btn-outline">
        Ke Halaman Login
    </a>
    <a href="{{ url()->previous() }}" class="btn btn-outline">
        Kembali
    </a>
@endsection

@section('extra-styles')
<style>
    @media (prefers-color-scheme: dark) {
        :root {
            --bg-badge: rgba(71, 85, 105, 0.2);
            --text-badge: #CBD5E1;
        }
    }
</style>
@endsection
