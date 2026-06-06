@extends('errors.layout-card')

@section('page_title', '400 — Bad Request')

@section('code-color', '#EA580C')
@section('badge-bg', '#FFEDD5')
@section('badge-text', '#9A3412')
@section('badge-dot', '#EA580C')

@section('icon')
    😕
@endsection

@section('badge', 'Bad Request')

@section('title', 'Oops, Ada yang Salah Ketik')

@section('message')
    Waduh, sepertinya ada yang salah ketik pada alamat URL atau data yang dikirimkan. Yuk, pastikan alamatnya sudah benar dan coba lagi ya.
@endsection

@section('actions')
    <a href="{{ url('/') }}" class="btn btn-outline">
        Ke Beranda
    </a>
    <a href="{{ url()->previous() }}" class="btn btn-outline">
        Kembali
    </a>
@endsection

@section('extra-styles')
<style>
    @media (prefers-color-scheme: dark) {
        :root {
            --bg-badge: rgba(234, 88, 12, 0.1);
            --text-badge: #FDBA74;
        }
    }
</style>
@endsection
