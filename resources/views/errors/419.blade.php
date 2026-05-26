@extends('errors.layout')

@section('code', '419')
@section('title', 'Sesi Telah Berakhir')
@section('gradient', '#F59E0B, #F97316, #EF4444')
@section('btn-gradient', '#F59E0B, #F97316')
@section('btn-shadow', 'rgba(245,158,11,0.4)')

@section('icon')
<svg fill="none" viewBox="0 0 24 24" stroke="#F59E0B" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
</svg>
@endsection

@section('message')
    Sesi Anda telah habis atau token keamanan tidak valid.
    Silakan muat ulang halaman dan coba lagi.
@endsection

@section('actions')
<a href="{{ url('/') }}" class="btn-secondary">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
    </svg>
    Beranda
</a>
<a href="{{ url()->previous() !== url()->current() ? url()->previous() : url('/') }}" class="btn-primary">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
    </svg>
    Muat Ulang Formulir
</a>
@endsection
