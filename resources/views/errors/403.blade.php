@extends('errors.layout-card')

@section('page_title', '403 — Forbidden')

@section('code-color', '#E11D48')
@section('badge-bg', '#FFE4E6')
@section('badge-text', '#BE123C')
@section('badge-dot', '#E11D48')

@section('icon')
    🚫
@endsection

@section('badge', 'Forbidden')

@section('title', 'Akses Tidak Diizinkan')

@section('message')
    Mohon maaf, Anda tidak memiliki izin untuk membuka halaman ini. Mungkin halaman ini dikhususkan untuk Admin atau peran tertentu.
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
            --bg-badge: rgba(225, 29, 72, 0.1);
            --text-badge: #FDA4AF;
        }
    }
</style>
@endsection
