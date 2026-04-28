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
<a href="javascript:history.back()" class="btn-secondary">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
    </svg>
    Kembali
</a>
<a href="javascript:location.reload()" class="btn-primary">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
    </svg>
    Muat Ulang
</a>
@endsection
