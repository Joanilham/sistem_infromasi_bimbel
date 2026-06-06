@extends('errors.layout-card')

@section('page_title', '404 — Halaman Tidak Ditemukan')

@section('code-color', '#B45309')
@section('badge-bg', '#FEF3C7')
@section('badge-text', '#92400E')
@section('badge-dot', '#D97706')

@section('icon')
    🔍
@endsection

@section('badge', 'Halaman Tidak Ditemukan')

@section('title', 'Halaman Tidak Ditemukan')

@section('message')
    Hmm, halaman yang Anda cari sepertinya sedang bersembunyi atau mungkin sudah dihapus. Mari kembali ke beranda untuk mencari hal lain.
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
            --bg-badge: rgba(217, 119, 6, 0.1);
        }
    }
</style>
@endsection
