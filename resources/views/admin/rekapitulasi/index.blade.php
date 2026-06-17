@extends('layouts.admin')

@section('title', 'Rekapitulasi Data')

@section('content')
<div class="space-y-6" x-data="ajaxTable()">

    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-4 mb-2">
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Rekapitulasi Data</h1>
                <a href="{{ route('admin.rekapitulasi.export', array_merge(['tab' => $tab], request()->except('tab'))) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold transition-all shadow-md shadow-emerald-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export Excel
                </a>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Ringkasan agregat data operasional harian dan bulanan.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 mt-4 md:mt-0">
            @php
                $tabs = [
                    'siswa' => [
                        'label' => 'Siswa', 
                        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /></svg>'
                    ],
                    'guru' => [
                        'label' => 'Guru', 
                        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
                    ],
                    'keuangan' => [
                        'label' => 'Keuangan', 
                        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
                    ],
                    'absensi' => [
                        'label' => 'Absensi', 
                        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>'
                    ],
                ];
            @endphp
            @foreach($tabs as $key => $t)
                <a href="{{ route('admin.rekapitulasi.index', ['tab' => $key]) }}"
                   class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl text-xs font-black uppercase tracking-widest transition-all border
                   {{ $tab === $key 
                        ? 'bg-indigo-600 text-white border-indigo-600 shadow-xl shadow-indigo-500/30'
                        : 'bg-white dark:bg-zinc-900 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-zinc-800 hover:bg-slate-50 dark:hover:bg-zinc-800 shadow-sm' }}">
                    <span class="text-base">{!! $t['icon'] !!}</span>
                    {{ $t['label'] }}
                </a>
            @endforeach
        </div>
    </div>


    {{-- Content Area --}}
    <div id="ajax-table-body" class="space-y-6 relative" @click="if($event.target.closest('th a')) { navigate($event, $event.target.closest('a').href) }; if($event.target.closest('nav[role=navigation] a')) { navigate($event, $event.target.closest('a').href) }">
        {{-- Loading Overlay --}}
        <x-table.loading-overlay />
        
        @if($tab === 'siswa')
            @include('admin.rekapitulasi.partials.siswa')
        @elseif($tab === 'guru')
            @include('admin.rekapitulasi.partials.guru')
        @elseif($tab === 'keuangan')
            @include('admin.rekapitulasi.partials.keuangan')
        @elseif($tab === 'absensi')
            @include('admin.rekapitulasi.partials.absensi')
        @endif
    </div>

</div>
@endsection
