@extends('errors.layout')

@section('code', '503')
@section('title', 'Sedang Dalam Pemeliharaan')
@section('gradient', '#06B6D4, #0EA5E9, #4F46E5')
@section('btn-gradient', '#0EA5E9, #06B6D4')
@section('btn-shadow', 'rgba(14,165,233,0.4)')

@section('icon')
<svg fill="none" viewBox="0 0 24 24" stroke="#06B6D4" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
</svg>
@endsection

@section('message')
    Sistem sedang dalam pemeliharaan untuk meningkatkan layanan Anda.
    Silakan kembali beberapa saat lagi. Terima kasih atas kesabaran Anda.
@endsection

@section('actions')
<a href="javascript:location.reload()" class="btn-secondary">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
    </svg>
    Coba Lagi
</a>
@endsection
