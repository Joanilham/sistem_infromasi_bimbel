@extends('errors.layout-card')

@section('page_title', '408 — Request Timeout')

@section('code-color', '#D97706')
@section('badge-bg', '#FEF3C7')
@section('badge-text', '#92400E')
@section('badge-dot', '#D97706')

@section('icon')
    ⏳
@endsection

@section('badge', 'Request Timeout')

@section('title', 'Menunggu Terlalu Lama')

@section('message')
    Wah, prosesnya memakan waktu terlalu lama. Hal ini biasanya terjadi karena koneksi internet sedang lambat. Yuk, coba muat ulang halamannya.
@endsection

@section('actions')
    <a href="javascript:location.reload()" class="btn btn-outline">
        Coba Lagi
    </a>
    <a href="{{ url('/') }}" class="btn btn-outline">
        Ke Beranda
    </a>
@endsection

@section('extra-styles')
<style>
    @media (prefers-color-scheme: dark) {
        :root {
            --bg-badge: rgba(217, 119, 6, 0.1);
            --text-badge: #FCD34D;
        }
    }
</style>
@endsection
