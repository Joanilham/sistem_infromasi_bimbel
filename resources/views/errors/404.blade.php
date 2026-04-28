@extends('errors.layout')

@section('code', '404')
@section('title', 'Halaman Tidak Ditemukan')
@section('gradient', '#4F46E5, #06B6D4, #8B5CF6')
@section('btn-gradient', '#4F46E5, #6366F1')
@section('btn-shadow', 'rgba(79,70,229,0.4)')

@section('icon')
<svg fill="none" viewBox="0 0 24 24" stroke="#4F46E5" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
</svg>
@endsection

@section('message')
    Halaman yang Anda cari tidak tersedia atau telah dipindahkan.
    Periksa kembali alamat URL atau kembali ke halaman utama.
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
