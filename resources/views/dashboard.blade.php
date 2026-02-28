@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<!-- Stat Cards: Ultra Clean & Minimalist -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Card 1: Peserta Didik -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-900/80 rounded-[1.25rem] p-6 shadow-[0_2px_12px_-4px_rgba(0,0,0,0.04)] dark:shadow-none border border-slate-100 dark:border-transparent transition-all hover:shadow-md flex flex-col justify-between min-h-[140px] group cursor-default">
        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Peserta Didik</p>

        <div class="flex items-end justify-between mt-4">
            <p class="text-4xl font-bold text-slate-800 dark:text-slate-100 tracking-tight">110</p>
            <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-900/30 flex flex-shrink-0 items-center justify-center text-blue-500 dark:text-blue-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Card 2: Guru -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-900/80 rounded-[1.25rem] p-6 shadow-[0_2px_12px_-4px_rgba(0,0,0,0.04)] dark:shadow-none border border-slate-100 dark:border-transparent transition-all hover:shadow-md flex flex-col justify-between min-h-[140px] group cursor-default">
        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Guru</p>

        <div class="flex items-end justify-between mt-4">
            <p class="text-4xl font-bold text-slate-800 dark:text-slate-100 tracking-tight">0</p>
            <div class="w-12 h-12 rounded-full bg-amber-50 dark:bg-amber-900/30 flex flex-shrink-0 items-center justify-center text-amber-500 dark:text-amber-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Card 3: Income -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-900/80 rounded-[1.25rem] p-6 shadow-[0_2px_12px_-4px_rgba(0,0,0,0.04)] dark:shadow-none border border-slate-100 dark:border-transparent transition-all hover:shadow-md flex flex-col justify-between min-h-[140px] group cursor-default">
        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Income {{ now()->translatedFormat('F Y') }}</p>

        <div class="flex items-end justify-between mt-4">
            <p class="text-4xl font-bold text-slate-800 dark:text-slate-100 tracking-tight">Rp. 0</p>
            <div class="w-12 h-12 rounded-full bg-emerald-50 dark:bg-emerald-900/30 flex flex-shrink-0 items-center justify-center text-emerald-500 dark:text-emerald-400 border border-emerald-500/20 dark:border-emerald-400/20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Welcome List Panel: Minimalist Rows -->
<div class="bg-white dark:bg-slate-900/80 rounded-[1.25rem] shadow-[0_2px_12px_-4px_rgba(0,0,0,0.04)] border border-slate-100 dark:border-transparent overflow-hidden mb-8">
    <div class="p-4 sm:p-6 lg:p-8 space-y-1">

        <!-- User Info Row -->
        <div class="flex items-center px-4 py-3.5 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800/60 transition duration-200">
            <div class="w-12 h-12 rounded-full bg-indigo-50/80 dark:bg-indigo-900/50 flex flex-shrink-0 items-center justify-center mr-5">
                <svg class="w-5 h-5 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400">Nama Pengguna</p>
                <p class="text-[15px] font-semibold text-slate-800 dark:text-white mt-0.5">{{ auth()->user()->name ?? 'AHMAD RIFAN FAUZI' }}</p>
            </div>
        </div>

        <!-- User Info Row (Selected Style Example) -->
        <div class="flex items-center px-4 py-3.5 rounded-2xl bg-emerald-50/40 dark:bg-emerald-900/30 hover:bg-emerald-50 dark:hover:bg-emerald-900/40 transition duration-200">
            <div class="w-12 h-12 rounded-full bg-emerald-100/60 dark:bg-emerald-800/50 flex flex-shrink-0 items-center justify-center mr-5">
                <span class="text-xl font-bold text-emerald-500 dark:text-emerald-400">@</span>
            </div>
            <div class="flex-1">
                <p class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400">Alamat Surel (Username)</p>
                <p class="text-[15px] font-semibold text-emerald-800 dark:text-emerald-300 mt-0.5">{{ auth()->user()->email ?? 'ADMINISTRASI PUSAT' }}</p>
            </div>
        </div>

        <!-- User Info Row -->
        <div class="flex items-center px-4 py-3.5 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800/60 transition duration-200">
            <div class="w-12 h-12 rounded-full flex flex-shrink-0 items-center justify-center mr-5 bg-slate-50 dark:bg-slate-800/80">
                <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400">Jabatan Hak Akses</p>
                <p class="text-[15px] font-semibold text-slate-800 dark:text-white mt-0.5">Administrator</p>
            </div>
        </div>

        <!-- User Info Row -->
        <div class="flex items-center px-4 py-3.5 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800/60 transition duration-200">
            <div class="w-12 h-12 rounded-full flex flex-shrink-0 items-center justify-center mr-5 bg-slate-50 dark:bg-slate-800/80">
                <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400">Nama Kantor Utama</p>
                <p class="text-[15px] font-semibold text-slate-800 dark:text-white mt-0.5">{{ \App\Models\Kantor::first()->nama_kantor ?? 'Genius Education' }}</p>
            </div>
        </div>

        <!-- User Info Row -->
        <div class="flex items-center px-4 py-3.5 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800/60 transition duration-200">
            <div class="w-12 h-12 rounded-full flex flex-shrink-0 items-center justify-center mr-5 bg-slate-50 dark:bg-slate-800/80">
                <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400">Alamat Kantor</p>
                <p class="text-[15px] font-semibold text-slate-800 dark:text-white mt-0.5">{{ \App\Models\Kantor::first()->alamat ?? 'Cluring - Banyuwangi' }}</p>
            </div>
        </div>

        <!-- User Info Row -->
        <div class="flex items-center px-4 py-3.5 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800/60 transition duration-200">
            <div class="w-12 h-12 rounded-full flex flex-shrink-0 items-center justify-center mr-5 bg-slate-50 dark:bg-slate-800/80">
                <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400">Periode Aktif</p>
                <p class="text-[15px] font-semibold text-slate-800 dark:text-white mt-0.5">{{ \App\Models\Periode::first()->nama_periode ?? '2024-2025' }}</p>
            </div>
        </div>

    </div>
</div>

@endsection