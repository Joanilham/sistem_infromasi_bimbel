@extends('errors.layout')

@section('code', '429')
@section('title', 'Terlalu Banyak Permintaan')
@section('gradient', '#8B5CF6, #EC4899, #F59E0B')
@section('btn-gradient', '#8B5CF6, #7C3AED')
@section('btn-shadow', 'rgba(139,92,246,0.4)')

@section('icon')
<svg fill="none" viewBox="0 0 24 24" stroke="#8B5CF6" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
</svg>
@endsection

@section('message')
    Anda telah melakukan terlalu banyak percobaan dalam waktu singkat.
    Harap tunggu sebentar sebelum mencoba lagi.
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
