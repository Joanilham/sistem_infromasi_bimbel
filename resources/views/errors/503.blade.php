@extends('errors.layout-card')

@section('page_title', '503 — Service Unavailable')

@section('code-color', '#0891B2')
@section('badge-bg', '#CFFAFE')
@section('badge-text', '#155E75')
@section('badge-dot', '#0891B2')

@section('icon')
    🛠️
@endsection

@section('badge', 'Service Unavailable')

@section('title', 'Sedang Dalam Perawatan')

@section('message')
    Saat ini kami sedang melakukan perawatan sistem agar aplikasi ini bisa berjalan lebih lancar untuk Anda. Kami akan segera kembali secepatnya!
@endsection

@section('actions')
    <a href="javascript:location.reload()" class="btn btn-outline">
        Muat Ulang
    </a>
@endsection

@section('extra-styles')
<style>
    @media (prefers-color-scheme: dark) {
        :root {
            --bg-badge: rgba(8, 145, 178, 0.1);
            --text-badge: #67E8F9;
        }
    }
</style>
@endsection
