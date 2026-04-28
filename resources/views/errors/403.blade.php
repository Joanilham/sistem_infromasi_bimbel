@extends('errors.layout')

@section('code', '403')
@section('title', 'Akses Ditolak')
@section('gradient', '#F59E0B, #EF4444, #DC2626')
@section('btn-gradient', '#DC2626, #EF4444')
@section('btn-shadow', 'rgba(220,38,38,0.4)')

@section('icon')
<svg fill="none" viewBox="0 0 24 24" stroke="#EF4444" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
</svg>
@endsection

@section('message')
    Anda tidak memiliki izin untuk mengakses halaman ini.
    Jika Anda merasa ini adalah kesalahan, silakan hubungi administrator.
@endsection

@section('actions')
<a href="javascript:history.back()" class="btn-secondary">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
    </svg>
    Kembali
</a>
<a href="{{ url('/') }}" class="btn-primary">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
    </svg>
    Beranda
</a>
@endsection
