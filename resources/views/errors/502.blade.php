@extends('errors.layout-card')

@section('page_title', '502 — Bad Gateway')

@section('code-color', '#4F46E5')
@section('badge-bg', '#E0E7FF')
@section('badge-text', '#3730A3')
@section('badge-dot', '#4F46E5')

@section('icon')
    🔌
@endsection

@section('badge', 'Bad Gateway')

@section('title', 'Gangguan Komunikasi Server')

@section('message')
    Terjadi gangguan komunikasi antar server di balik layar. Jangan panik, ini bukan salah Anda kok! Silakan coba muat ulang halamannya.
@endsection

@section('actions')
    <a href="javascript:location.reload()" class="btn btn-outline">
        Mencoba...
    </a>
    <a href="{{ url()->previous() }}" class="btn btn-outline">
        Kembali
    </a>
@endsection

@section('extra-styles')
<style>
    @media (prefers-color-scheme: dark) {
        :root {
            --bg-badge: rgba(79, 70, 229, 0.1);
            --text-badge: #A5B4FC;
        }
    }
</style>
@endsection
