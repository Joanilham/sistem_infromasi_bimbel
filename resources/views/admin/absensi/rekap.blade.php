@extends('layouts.admin')

@section('title', 'Rekap Absensi')

@section('content')
<div class="space-y-6" x-data="{ autoAlphaModal: false, isRunning: false, ...ajaxTable() }">

    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Rekap Absensi</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm font-medium">Monitoring tingkat kehadiran dan kedisiplinan siswa secara periodik.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 shrink-0">
            {{-- Tombol Pengaturan Auto Alpha --}}
            <button type="button" @click="autoAlphaModal = true"
                    class="inline-flex items-center gap-2.5 bg-white dark:bg-zinc-800 hover:bg-slate-50 dark:hover:bg-zinc-700 text-slate-700 dark:text-slate-200 font-bold text-xs px-5 py-4 rounded-2xl shadow-sm border border-slate-200 dark:border-zinc-700 transition-all active:scale-95"
                    title="Buka Pengaturan Sistem Auto Alpha">
                @if($autoAlphaSettings['auto_alpha_enabled'] ?? true)
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                    <span>Auto Alpha: <span class="text-emerald-600 dark:text-emerald-400 font-black">Aktif</span> ({{ $autoAlphaSettings['auto_alpha_time'] ?? '23:00' }})</span>
                @else
                    <span class="inline-flex rounded-full h-2.5 w-2.5 bg-rose-500"></span>
                    <span>Auto Alpha: <span class="text-rose-500 font-black">Nonaktif</span></span>
                @endif
                <svg class="w-4 h-4 text-slate-400 ml-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </button>

            <a href="{{ route('absensi.export.rekap', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
               class="inline-flex items-center gap-3 bg-white dark:bg-zinc-800 hover:bg-slate-50 dark:hover:bg-zinc-700 text-slate-700 dark:text-slate-200 font-black text-xs px-6 py-4 rounded-2xl shadow-sm border border-slate-200 dark:border-zinc-700 transition-all active:scale-95">
                <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Export Rekap
            </a>
            <a href="{{ route('absensi.scan.masuk.page') }}"
               class="inline-flex items-center gap-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs px-8 py-4 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Scan Kehadiran
            </a>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="bg-white dark:bg-zinc-900 rounded-3xl p-8 lg:p-10 border border-slate-100 dark:border-zinc-800 shadow-sm mb-8 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/5 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-150 duration-700"></div>

        {{-- Loading Overlay --}}
        <x-table.loading-overlay />

        <form @submit.prevent="fetchData" method="GET" action="{{ route('absensi.rekap') }}" class="flex flex-col gap-4 sm:gap-5">
                
                {{-- Top Row: Universal Controls --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full">
                    {{-- Per Page --}}
                    <x-table.filter-limit :alpine="true" />

                    {{-- Search & Reset --}}
                    <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full md:w-auto shrink-0">
                        <x-table.search :alpine="true" placeholder="Cari nama atau NISN…" />
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-colors shadow-sm shadow-indigo-500/30 shrink-0">
                            Filter
                        </button>
                        @if(request()->anyFilled(['search', 'paket_id', 'kelompok_id']))
                            <a href="{{ route('absensi.rekap') }}" 
                               class="flex items-center gap-2 bg-slate-100 dark:bg-zinc-800 hover:bg-rose-100 dark:hover:bg-rose-900/40 text-slate-600 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 px-4 py-2.5 rounded-xl text-sm font-bold transition-all shrink-0"
                               title="Reset Filter">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Clear
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Bottom Row: Data Filters --}}
                <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-slate-100 dark:border-zinc-800/50">
                    
                    {{-- Bulan Filter --}}
                    <div class="flex items-stretch bg-white dark:bg-zinc-950 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all overflow-hidden">
                        <div class="px-3 py-2 bg-slate-50 dark:bg-zinc-900 border-r border-slate-200 dark:border-zinc-800 flex items-center justify-center">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Bulan</span>
                        </div>
                        <select name="bulan" @change="fetchData" class="no-tomselect bg-transparent border-none text-xs font-bold focus:ring-0 py-2 pl-3 pr-8 text-slate-800 dark:text-white cursor-pointer h-full hover:bg-slate-50 dark:hover:bg-zinc-900/50 transition-colors">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tahun Filter --}}
                    <div class="flex items-stretch bg-white dark:bg-zinc-950 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all overflow-hidden">
                        <div class="px-3 py-2 bg-slate-50 dark:bg-zinc-900 border-r border-slate-200 dark:border-zinc-800 flex items-center justify-center">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Tahun</span>
                        </div>
                        <select name="tahun" @change="fetchData" class="no-tomselect bg-transparent border-none text-xs font-bold focus:ring-0 py-2 pl-3 pr-8 text-slate-800 dark:text-white cursor-pointer h-full hover:bg-slate-50 dark:hover:bg-zinc-900/50 transition-colors">
                            @php $yNow = date('Y'); @endphp
                            @for($y = $yNow - 2; $y <= $yNow + 1; $y++)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    {{-- Paket Filter --}}
                    <div class="flex items-stretch bg-white dark:bg-zinc-950 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all overflow-hidden">
                        <div class="px-3 py-2 bg-slate-50 dark:bg-zinc-900 border-r border-slate-200 dark:border-zinc-800 flex items-center justify-center">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Program</span>
                        </div>
                        <select name="paket_id" @change="fetchData"
                            class="no-tomselect bg-transparent border-none text-xs font-bold focus:ring-0 py-2 pl-3 pr-8 text-slate-800 dark:text-white cursor-pointer h-full hover:bg-slate-50 dark:hover:bg-zinc-900/50 transition-colors max-w-[150px] sm:max-w-[200px] truncate">
                            <option value="">Semua Program</option>
                            @foreach($pakets as $p)
                                <option value="{{ $p->id }}" {{ request('paket_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_paket }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Kelompok Filter --}}
                    <div class="flex items-stretch bg-white dark:bg-zinc-950 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all overflow-hidden">
                        <div class="px-3 py-2 bg-slate-50 dark:bg-zinc-900 border-r border-slate-200 dark:border-zinc-800 flex items-center justify-center">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Kelas</span>
                        </div>
                        <select name="kelompok_id" @change="fetchData"
                            class="no-tomselect bg-transparent border-none text-xs font-bold focus:ring-0 py-2 pl-3 pr-8 text-slate-800 dark:text-white cursor-pointer h-full hover:bg-slate-50 dark:hover:bg-zinc-900/50 transition-colors max-w-[100px] sm:max-w-[140px] truncate">
                            <option value="">Semua Kelas</option>
                            @foreach($kelompoks as $k)
                                <option value="{{ $k->id }}" {{ request('kelompok_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelompok }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </form>
    </div>

    {{-- Table Card --}}
    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div id="ajax-table-body" class="overflow-x-auto" @click="if($event.target.closest('th a')) { navigate($event, $event.target.closest('a').href) }">
            <table class="w-full text-sm border-collapse border border-slate-200 dark:border-zinc-800">
                <thead class="bg-indigo-600 dark:bg-indigo-900/80 text-[10px] uppercase tracking-widest text-white font-black">
                    <tr>
                        <th class="px-4 py-3 text-left w-28 border border-white/20">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'id', 'order' => request('sort') == 'id' && request('order') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center justify-between group">
                                No.
                                <span class="transition-all {{ request('sort') == 'id' ? 'opacity-100' : 'opacity-30 group-hover:opacity-100' }}">
                                    @if(request('sort') == 'id' && request('order') == 'asc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 15l7-7 7 7"/></svg>
                                    @elseif(request('sort') == 'id' && request('order') == 'desc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7"/></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M7 10l5-5 5 5M7 14l5 5 5-5"/></svg>
                                    @endif
                                </span>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left border border-white/20">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'nama_lengkap', 'order' => request('sort') == 'nama_lengkap' && request('order') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center justify-between group">
                                Nama Siswa
                                <span class="transition-all {{ request('sort') == 'nama_lengkap' ? 'opacity-100' : 'opacity-30 group-hover:opacity-100' }}">
                                    @if(request('sort') == 'nama_lengkap' && request('order') == 'asc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 15l7-7 7 7"/></svg>
                                    @elseif(request('sort') == 'nama_lengkap' && request('order') == 'desc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7"/></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M7 10l5-5 5 5M7 14l5 5 5-5"/></svg>
                                    @endif
                                </span>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-center border border-white/20">Hadir</th>
                        <th class="px-4 py-3 text-center border border-white/20">Izin</th>
                        <th class="px-4 py-3 text-center border border-white/20">Sakit</th>
                        <th class="px-4 py-3 text-center border border-white/20">Alpha</th>
                        <th class="px-4 py-3 text-center w-40 border border-white/20">Tingkat Kehadiran</th>
                        <th class="px-4 py-3 text-center w-24 border border-white/20">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900">
                    @forelse($pesertaDidiks as $p)
                        @php
                            $target = $p->target_days ?? 1;
                            $percent = round(($p->total_hadir / $target) * 100);
                            if ($percent > 100) $percent = 100;
                        @endphp
                        <tr class="rekap-row hover:bg-indigo-50/50 dark:hover:bg-indigo-900/10 transition-all even:bg-slate-50/50 dark:even:bg-zinc-800/30"
                            data-nama="{{ strtolower($p->nama_lengkap) }}" data-nisn="{{ $p->nisn }}">
                            <td class="px-4 py-3 text-slate-500 font-bold text-xs font-mono border border-slate-200 dark:border-zinc-800 text-center">
                                @php
                                    $offset = method_exists($pesertaDidiks, 'firstItem') ? ($pesertaDidiks->firstItem() - 1) : 0;
                                @endphp
                                #{{ $offset + $loop->iteration }}
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <a href="{{ route('absensi.detail', ['id' => $p->id, 'bulan' => $bulan, 'tahun' => $tahun]) }}" class="flex items-center gap-3 group/link block">
                                    <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center shrink-0 ring-1 ring-indigo-100 transition-all group-hover/link:bg-indigo-600 group-hover/link:text-white group-hover/link:ring-indigo-600">
                                        <span class="font-black text-xs transition-colors">{{ substr($p->nama_lengkap, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 dark:text-white leading-tight transition-colors group-hover/link:text-indigo-600 dark:group-hover/link:text-indigo-400">@highlight($p->nama_lengkap)</p>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">@highlight($p->nisn)</p>
                                    </div>
                                </a>
                            </td>
                            <td class="px-4 py-3 text-center border border-slate-200 dark:border-zinc-800">
                                <span class="inline-flex items-center justify-center min-w-[2.5rem] px-2 py-0.5 rounded bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 font-black text-xs ring-1 ring-emerald-100 shadow-sm">
                                    {{ $p->total_hadir }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center border border-slate-200 dark:border-zinc-800">
                                <span class="inline-flex items-center justify-center min-w-[2.5rem] px-2 py-0.5 rounded bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-black text-xs ring-1 ring-blue-100 shadow-sm">
                                    {{ $p->total_izin }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center border border-slate-200 dark:border-zinc-800">
                                <span class="inline-flex items-center justify-center min-w-[2.5rem] px-2 py-0.5 rounded bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 font-black text-xs ring-1 ring-amber-100 shadow-sm">
                                    {{ $p->total_sakit }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center border border-slate-200 dark:border-zinc-800">
                                <span class="inline-flex items-center justify-center min-w-[2.5rem] px-2 py-0.5 rounded bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 font-black text-xs ring-1 ring-rose-100 shadow-sm">
                                    {{ $p->total_alpha }}
                                </span>
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <div class="flex flex-col items-center gap-1.5">
                                    <div class="w-full bg-slate-100 dark:bg-zinc-800 rounded-full h-1.5 shadow-inner">
                                        <div class="h-1.5 rounded-full transition-all duration-1000 ease-out {{ $percent >= 90 ? 'bg-emerald-500' : ($percent >= 70 ? 'bg-indigo-500' : 'bg-rose-500') }}"
                                             style="width: {{ $percent }}%"></div>
                                    </div>
                                    <span class="text-[9px] font-black {{ $percent >= 90 ? 'text-emerald-600' : ($percent >= 70 ? 'text-indigo-600' : 'text-rose-600') }} tracking-widest uppercase">{{ $percent }}% HADIR</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center border border-slate-200 dark:border-zinc-800">
                                <a href="{{ route('absensi.detail', ['id' => $p->id, 'bulan' => $bulan, 'tahun' => $tahun]) }}" class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-600 text-indigo-600 hover:text-white text-xs font-bold transition-all shadow-sm">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-8 py-24 text-center border border-slate-200 dark:border-zinc-800">
                                <div class="w-16 h-16 bg-slate-100 dark:bg-zinc-800 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-400 dark:text-zinc-500">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <h3 class="font-black text-slate-900 dark:text-white text-lg">Data Kosong</h3>
                                <p class="text-slate-400 text-sm mt-2 font-medium max-w-xs mx-auto">Belum ada catatan kehadiran siswa untuk periode yang dipilih.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        {{-- Footer Pagination --}}
        <div id="ajax-pagination" class="px-8 py-6 border-t border-slate-100 dark:border-zinc-800 bg-slate-50/30 dark:bg-zinc-900/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4"
             @click="if($event.target.closest('nav[role=navigation] a')) { navigate($event, $event.target.closest('a').href) }">
            <p class="text-xs text-slate-500 dark:text-slate-400 font-bold">
                @if(method_exists($pesertaDidiks, 'total'))
                    Menampilkan <span class="text-slate-900 dark:text-white">{{ $pesertaDidiks->firstItem() ?? 0 }}</span> – <span class="text-slate-900 dark:text-white">{{ $pesertaDidiks->lastItem() ?? 0 }}</span> dari <span class="text-slate-900 dark:text-white">{{ $pesertaDidiks->total() ?? 0 }}</span> Siswa
                @else
                    Menampilkan <span class="text-slate-900 dark:text-white">{{ $pesertaDidiks->count() }}</span> Siswa
                @endif
            </p>
            @if(method_exists($pesertaDidiks, 'hasPages') && $pesertaDidiks->hasPages())
                <div class="flex justify-end">
                    {{ $pesertaDidiks->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Modal Pengaturan Auto Alpha --}}
    <div x-show="autoAlphaModal" 
         x-cloak 
         @keydown.escape.window="autoAlphaModal = false"
         class="fixed inset-0 z-[100] overflow-y-auto"
         aria-labelledby="modal-title" role="dialog" aria-modal="true">
        
        {{-- Backdrop with explicit z-10 behind the card --}}
        <div x-show="autoAlphaModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity z-10" 
             @click="autoAlphaModal = false"
             aria-hidden="true"></div>

        {{-- Centering wrapper with explicit z-20 on top of backdrop, pointer-events-none --}}
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0 relative z-20 pointer-events-none">

            {{-- Modal Card: pointer-events-auto, solid white background, guaranteed opaque --}}
            <div x-show="autoAlphaModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 @click.stop
                 class="pointer-events-auto relative w-full sm:max-w-lg transform overflow-hidden rounded-3xl bg-white dark:bg-zinc-900 text-left shadow-2xl border border-slate-200 dark:border-zinc-800 transition-all sm:my-8"
                 style="background-color: #ffffff;">
                
                {{-- Form Settings --}}
                <form action="{{ route('absensi.auto-alpha.settings') }}" method="POST"
                      x-data="{ 
                          enabled: {{ ($autoAlphaSettings['auto_alpha_enabled'] ?? true) ? 'true' : 'false' }},
                          timeVal: '{{ $autoAlphaSettings['auto_alpha_time'] ?? '23:00' }}',
                          excludeSun: {{ ($autoAlphaSettings['exclude_sunday'] ?? false) ? 'true' : 'false' }}
                      }">
                    @csrf
                    {{-- Hidden input to ensure value is always submitted even if switch is off --}}
                    <input type="hidden" name="auto_alpha_enabled" :value="enabled ? '1' : '0'">

                    <div class="p-6 sm:p-8 bg-white dark:bg-zinc-900">
                        {{-- Modal Header --}}
                        <div class="flex items-center justify-between pb-5 border-b border-slate-100 dark:border-zinc-800">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white leading-tight">Pengaturan Auto Alpha</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Otomatisasi penandaan status Alpha harian</p>
                                </div>
                            </div>
                            <button type="button" @click="autoAlphaModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors p-1.5 rounded-lg cursor-pointer">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        {{-- Modal Body --}}
                        <div class="py-6 space-y-5">
                            {{-- Toggle Card --}}
                            <div class="rounded-2xl p-4 transition-colors border"
                                 :class="enabled ? 'bg-emerald-50/80 dark:bg-emerald-950/40 border-emerald-300 dark:border-emerald-800' : 'bg-slate-50 dark:bg-zinc-800/40 border-slate-200 dark:border-zinc-800'">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <span class="text-sm font-bold text-slate-900 dark:text-white block">
                                            Status Otomatisasi
                                        </span>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-medium" 
                                           x-text="enabled ? 'Sistem Aktif: Siswa tanpa presensi otomatis ditandai Alpha' : 'Sistem Nonaktif: Penandaan Alpha otomatis dimatikan'"></p>
                                    </div>
                                    <button type="button" 
                                            @click="enabled = !enabled"
                                            role="switch"
                                            :aria-checked="enabled"
                                            class="relative inline-flex h-7 w-12 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                                            :class="enabled ? 'bg-emerald-600' : 'bg-slate-300 dark:bg-zinc-700'">
                                        <span class="sr-only">Toggle Auto Alpha</span>
                                        <span aria-hidden="true" 
                                              class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow-md ring-0 transition duration-200 ease-in-out"
                                              :class="enabled ? 'translate-x-5' : 'translate-x-0'"></span>
                                    </button>
                                </div>
                            </div>

                            {{-- Jam Eksekusi --}}
                            <div class="space-y-1.5">
                                <label for="auto_alpha_time" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                    Waktu Eksekusi Harian
                                </label>
                                <div class="relative">
                                    <input type="time" id="auto_alpha_time" name="auto_alpha_time" required
                                           x-model="timeVal"
                                           class="w-full bg-white dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                </div>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">Jadwal server saat penandaan Alpha otomatis dijalankan setiap harinya.</p>
                            </div>

                            {{-- Pengecualian Hari Minggu --}}
                            <label class="flex items-start gap-3 p-3.5 rounded-2xl bg-slate-50 dark:bg-zinc-800/40 border border-slate-200 dark:border-zinc-700 hover:border-indigo-300 dark:hover:border-indigo-700 cursor-pointer transition-all">
                                <input type="checkbox" name="exclude_sunday" value="1"
                                       x-model="excludeSun"
                                       class="mt-0.5 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-zinc-700 dark:bg-zinc-800">
                                <div class="flex-1">
                                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">Lewati Hari Minggu</span>
                                    <span class="text-[11px] text-slate-500 dark:text-slate-400">Jangan menandai siswa Alpha jika hari tersebut adalah hari Minggu.</span>
                                </div>
                            </label>

                            {{-- Status Terakhir --}}
                            <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-zinc-800/50 border border-slate-100 dark:border-zinc-800 text-xs text-slate-600 dark:text-slate-400 space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <span class="font-medium text-slate-500">Eksekusi Terakhir:</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200">
                                        {{ !empty($autoAlphaSettings['last_run_at']) ? \Carbon\Carbon::parse($autoAlphaSettings['last_run_at'])->translatedFormat('d M Y, H:i') : 'Belum pernah' }}
                                    </span>
                                </div>
                                @if(!empty($autoAlphaSettings['last_run_count']))
                                <div class="flex items-center justify-between">
                                    <span class="font-medium text-slate-500">Siswa Ditandai Alpha:</span>
                                    <span class="font-bold text-emerald-600 dark:text-emerald-400">
                                        {{ $autoAlphaSettings['last_run_count'] }} Siswa
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Modal Footer --}}
                        <div class="pt-5 border-t border-slate-100 dark:border-zinc-800 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                            <button type="button" 
                                    @click="runAutoAlphaInstant()"
                                    :disabled="isRunning"
                                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/40 border border-amber-200 dark:border-amber-800 text-xs font-bold transition-all disabled:opacity-50 cursor-pointer">
                                <svg x-show="!isRunning" class="w-4 h-4 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <svg x-show="isRunning" class="animate-spin w-4 h-4 text-amber-600 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span x-text="isRunning ? 'Memproses...' : 'Jalankan Sekarang'"></span>
                            </button>

                            <div class="flex items-center gap-2.5 justify-end">
                                <button type="button" @click="autoAlphaModal = false"
                                        class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-zinc-800 transition-colors cursor-pointer">
                                    Batal
                                </button>
                                <button type="submit"
                                        class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-sm shadow-indigo-500/30 transition-colors cursor-pointer">
                                    Simpan Pengaturan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

</div>

<script>
function runAutoAlphaInstant() {
    Swal.fire({
        title: 'Jalankan Auto Alpha Sekarang?',
        text: 'Semua siswa aktif yang belum memiliki catatan absensi hari ini akan langsung ditandai sebagai Alpha.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4f46e5',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Jalankan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            Swal.fire({
                title: 'Sedang Memproses...',
                text: 'Memeriksa kehadiran siswa aktif hari ini.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('{{ route("absensi.auto-alpha.run") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        confirmButtonColor: '#4f46e5'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message || 'Terjadi kesalahan pada sistem.',
                        confirmButtonColor: '#4f46e5'
                    });
                }
            })
            .catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Jaringan',
                    text: 'Gagal menghubungi server.',
                    confirmButtonColor: '#4f46e5'
                });
            });
        }
    });
}
</script>
@endsection
