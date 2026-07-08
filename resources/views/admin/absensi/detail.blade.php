@extends('layouts.admin')

@section('title', 'Detail Absensi - ' . $peserta->nama_lengkap)

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('absensi.rekap', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-zinc-800 flex items-center justify-center text-slate-500 hover:bg-indigo-100 hover:text-indigo-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                </a>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">{{ $peserta->nama_lengkap }}</h1>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium ml-14">
                NISN: {{ $peserta->nisn }} &bull; Paket: {{ optional($peserta->paketBimbingan)->nama_paket ?? '-' }}
            </p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <span class="bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-black text-sm px-5 py-2.5 rounded-xl border border-indigo-100 dark:border-indigo-800/50">
                {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}
            </span>
        </div>
    </div>

    {{-- Detail Table --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden" x-data="{ filterStatus: 'semua' }">
        <div class="p-6 border-b border-slate-100 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-black text-slate-800 dark:text-white">Riwayat Harian</h2>
                <p class="text-xs text-slate-500 mt-1">Klik tombol edit untuk mengubah absensi pada tanggal tertentu secara manual.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                {{-- Limit Per Page --}}
                <form action="{{ route('absensi.detail', $peserta->id) }}" method="GET" class="shrink-0">
                    <input type="hidden" name="bulan" value="{{ request('bulan', now()->month) }}">
                    <input type="hidden" name="tahun" value="{{ request('tahun', now()->year) }}">
                    <x-table.filter-limit />
                </form>
                
                {{-- Filter Status --}}
                <div class="shrink-0">
                    <select x-model="filterStatus" class="no-tomselect bg-white dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold py-2.5 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none text-slate-600 dark:text-slate-300 shadow-sm h-[42px]">
                        <option value="semua">Semua Status</option>
                        <option value="hadir">Hadir</option>
                        <option value="izin">Izin</option>
                        <option value="sakit">Sakit</option>
                        <option value="alpha">Alpha</option>
                        <option value="kosong">Belum Absen (-)</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse border-y border-slate-200 dark:border-zinc-800">
                <thead class="bg-slate-100 dark:bg-zinc-800 text-[10px] uppercase tracking-widest text-slate-500 dark:text-slate-400 font-black">
                    <tr>
                        <th class="px-6 py-4 text-left border-y border-slate-200 dark:border-zinc-700">Tanggal</th>
                        <th class="px-6 py-4 text-center border-y border-slate-200 dark:border-zinc-700">Status</th>
                        <th class="px-6 py-4 text-center border-y border-slate-200 dark:border-zinc-700">Jam Masuk</th>
                        <th class="px-6 py-4 text-center border-y border-slate-200 dark:border-zinc-700">Jam Pulang</th>
                        <th class="px-6 py-4 text-left border-y border-slate-200 dark:border-zinc-700">Keterangan</th>
                        <th class="px-6 py-4 text-center border-y border-slate-200 dark:border-zinc-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                    @foreach($tanggalList as $dateStr => $a)
                        @php 
                            $isWeekend = \Carbon\Carbon::parse($dateStr)->isWeekend();
                            $dateObj = \Carbon\Carbon::parse($dateStr);
                            $statusRecord = $a ? strtolower($a->status_masuk) : 'kosong';
                        @endphp
                        <tr x-show="filterStatus === 'semua' || filterStatus === '{{ $statusRecord }}'" class="{{ $isWeekend ? 'bg-slate-50 dark:bg-zinc-800/30' : 'hover:bg-slate-50 dark:hover:bg-zinc-800/50' }} transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800 dark:text-slate-200">{{ $dateObj->translatedFormat('d M Y') }}</div>
                                <div class="text-[10px] font-black uppercase tracking-widest {{ $isWeekend ? 'text-rose-400' : 'text-slate-400' }}">
                                    {{ $dateObj->translatedFormat('l') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($a)
                                    <span class="inline-flex rounded-lg px-3 py-1 text-[10px] font-black uppercase tracking-wider
                                        {{ $a->status_masuk === 'hadir' ? 'bg-emerald-100 text-emerald-700' :
                                           ($a->status_masuk === 'alpha' ? 'bg-rose-100 text-rose-700' : 
                                           ($a->status_masuk === 'izin' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700')) }}">
                                        {{ $a->status_masuk }}
                                    </span>
                                @else
                                    <span class="text-slate-300 dark:text-slate-600 text-xs font-bold">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center font-mono text-xs font-bold text-slate-600 dark:text-slate-400">
                                {{ $a && $a->jam_masuk ? substr($a->jam_masuk, 0, 5) : '--:--' }}
                            </td>
                            <td class="px-6 py-4 text-center font-mono text-xs font-bold text-slate-600 dark:text-slate-400">
                                {{ $a && $a->jam_pulang ? substr($a->jam_pulang, 0, 5) : '--:--' }}
                            </td>
                            <td class="px-6 py-4 text-xs font-medium text-slate-500 dark:text-slate-400 max-w-[200px] truncate" title="{{ $a->keterangan ?? '' }}">
                                {{ $a->keterangan ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button type="button" 
                                    @click="$dispatch('open-modal', 'edit-absensi'); $dispatch('set-absensi', {
                                        tanggal: '{{ $dateStr }}',
                                        tanggal_format: '{{ $dateObj->translatedFormat('d F Y') }}',
                                        status_masuk: '{{ $a->status_masuk ?? 'hadir' }}',
                                        jam_masuk: '{{ $a && $a->jam_masuk ? substr($a->jam_masuk, 0, 5) : '' }}',
                                        jam_pulang: '{{ $a && $a->jam_pulang ? substr($a->jam_pulang, 0, 5) : '' }}',
                                        keterangan: '{{ $a->keterangan ?? '' }}'
                                    })"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($tanggalList instanceof \Illuminate\Pagination\LengthAwarePaginator && $tanggalList->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-zinc-800">
            {{ $tanggalList->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Modal Edit Manual --}}
<template x-teleport="body">
    <div x-data="{
            show: false,
            form: {
                tanggal: '',
                tanggal_format: '',
                status_masuk: 'hadir',
                jam_masuk: '',
                jam_pulang: '',
                keterangan: ''
            }
        }" 
        x-show="show" 
        @open-modal.window="if ($event.detail === 'edit-absensi') show = true"
        @close-modal.window="show = false"
        @set-absensi.window="
            form.tanggal = $event.detail.tanggal;
            form.tanggal_format = $event.detail.tanggal_format;
            form.status_masuk = $event.detail.status_masuk;
            form.jam_masuk = $event.detail.jam_masuk;
            form.jam_pulang = $event.detail.jam_pulang;
            form.keterangan = $event.detail.keterangan;
        "
        class="fixed inset-0 z-[100] flex items-center justify-center" style="display: none;">
        
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" @click="show = false"></div>

        <div class="relative bg-white dark:bg-zinc-900 rounded-[2rem] shadow-2xl w-full max-w-md p-8 m-4 transform transition-all border border-slate-100 dark:border-zinc-800">
            <h3 class="text-xl font-black text-slate-800 dark:text-white mb-1">Edit Absensi Manual</h3>
            <p class="text-sm font-bold text-slate-400 mb-6" x-text="'Tanggal: ' + form.tanggal_format"></p>

            <form action="{{ route('absensi.store.manual') }}" method="POST">
                @csrf
                <input type="hidden" name="peserta_didik_id" value="{{ $peserta->id }}">
                <input type="hidden" name="tanggal" x-model="form.tanggal">

                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-1">Status Kehadiran</label>
                        <select name="status_masuk" x-model="form.status_masuk" class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                            <option value="hadir">Hadir</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                            <option value="alpha">Alpha</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4" x-show="form.status_masuk === 'hadir'">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-1">Jam Masuk</label>
                            <input type="time" name="jam_masuk" x-model="form.jam_masuk" class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-1">Jam Pulang</label>
                            <input type="time" name="jam_pulang" x-model="form.jam_pulang" class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-1">Keterangan (Opsional)</label>
                        <textarea name="keterangan" x-model="form.keterangan" rows="2" class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" placeholder="Ketik alasan perubahan manual di sini..."></textarea>
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-slate-100 dark:border-zinc-800">
                    <button type="button" @click="show = false" class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-white bg-rose-500 hover:bg-rose-600 rounded-2xl transition-all shadow-xl shadow-rose-500/20 active:scale-95">Batal</button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-black uppercase tracking-widest px-8 py-4 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all active:scale-95">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</template>

@endsection
