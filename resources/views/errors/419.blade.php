@extends('errors.layout-card')

@section('page_title', '419 — Page Expired')

@section('code-color', '#0D9488')
@section('badge-bg', '#CCFBF1')
@section('badge-text', '#115E59')
@section('badge-dot', '#0D9488')

@section('icon')
    ⌛
@endsection

@section('badge', 'Page Expired')

@section('title', 'Sesi Habis')

@section('message')
    Karena sudah lama tidak ada aktivitas, sesi Anda telah kedaluwarsa demi keamanan. Jangan khawatir, cukup login kembali untuk melanjutkan.
@endsection

@section('actions')
    <a href="javascript:location.reload()" class="btn btn-outline">
        Muat Ulang
    </a>
    <a href="{{ route('login') ?? url('/login') }}" class="btn btn-outline">
        Login Kembali
    </a>
@endsection

@section('extra-styles')
<style>
    @media (prefers-color-scheme: dark) {
        :root {
            --bg-badge: rgba(13, 148, 136, 0.1);
            --text-badge: #5EEAD4;
        }
    }
</style>
@endsection
