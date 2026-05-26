@extends('layouts.admin')

@section('title', 'Rekap Absensi')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Rekap Absensi</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm font-medium">Monitoring tingkat kehadiran dan kedisiplinan siswa secara periodik.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 shrink-0">
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
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/5 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-150 duration-700"></div>
        <form method="GET" class="flex flex-col lg:flex-row items-end gap-6 relative">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 w-full">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Bulan</label>
                    <select name="bulan" class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Tahun</label>
                    <select name="tahun" class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        @php $yNow = date('Y'); @endphp
                        @for($y = $yNow - 2; $y <= $yNow + 1; $y++)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Paket Program</label>
                    <select name="paket_id" class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        <option value="">Semua Paket</option>
                        @foreach($pakets as $p)
                            <option value="{{ $p->id }}" {{ request('paket_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_paket }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Kelompok Belajar</label>
                    <select name="kelompok_id" class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        <option value="">Semua Kelompok</option>
                        @foreach($kelompoks as $k)
                            <option value="{{ $k->id }}" {{ request('kelompok_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelompok }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="flex gap-2 w-full lg:w-auto">
                <button type="submit" class="flex-1 lg:flex-none bg-slate-900 dark:bg-white dark:text-slate-900 text-white font-black text-xs px-10 py-4 rounded-2xl hover:bg-slate-800 dark:hover:bg-slate-100 transition-all active:scale-95 shadow-lg shadow-slate-900/10 uppercase tracking-widest">
                    Tampilkan
                </button>
                @if(request()->anyFilled(['paket_id', 'kelompok_id']))
                    <a href="{{ route('absensi.rekap') }}" class="bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 hover:bg-rose-100 p-4 rounded-2xl transition-all" title="Reset Filter">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                    </a>
                @endif
            </div>
        </form>
        
        <div class="mt-8 border-t border-slate-100 dark:border-zinc-800 pt-6">
            <div class="w-full space-y-3">
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Cari Cepat (Nama/NISN)</label>
                <div class="relative group">
                    <input type="text" id="search-rekap" placeholder="Ketik nama atau NISN untuk memfilter tabel di bawah..."
                        class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 pl-12 pr-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all group">
                    <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
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
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900">
                    @forelse($pesertaDidiks as $p)
                        @php
                            $total = $p->total_hadir + $p->total_izin + $p->total_sakit + $p->total_alpha;
                            $percent = $total > 0 ? round(($p->total_hadir / $total) * 100) : 0;
                        @endphp
                        <tr class="rekap-row hover:bg-indigo-50/50 dark:hover:bg-indigo-900/10 transition-all even:bg-slate-50/50 dark:even:bg-zinc-800/30"
                            data-nama="{{ strtolower($p->nama_lengkap) }}" data-nisn="{{ $p->nisn }}">
                            <td class="px-4 py-3 text-slate-500 font-bold text-xs row-no font-mono border border-slate-200 dark:border-zinc-800 text-center">
                                #{{ str_pad($loop->iteration, 3, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center shrink-0 ring-1 ring-indigo-100 transition-all">
                                        <span class="font-black text-xs">{{ substr($p->nama_lengkap, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 dark:text-white leading-tight transition-colors">{{ $p->nama_lengkap }}</p>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ $p->nisn }}</p>
                                    </div>
                                </div>
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-8 py-24 text-center border border-slate-200 dark:border-zinc-800">
                                <div class="w-20 h-20 bg-slate-50 dark:bg-zinc-800 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-4xl shadow-inner">
                                    📅
                                </div>
                                <h3 class="font-black text-slate-900 dark:text-white text-lg">Data Kosong</h3>
                                <p class="text-slate-400 text-sm mt-2 font-medium max-w-xs mx-auto">Belum ada catatan kehadiran siswa untuk periode yang dipilih.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div id="empty-rekap" class="hidden px-8 py-24 text-center">
                <div class="w-20 h-20 bg-slate-50 dark:bg-zinc-800 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-4xl shadow-inner">
                    🔍
                </div>
                <h3 class="font-black text-slate-900 dark:text-white text-lg">Siswa Tidak Ditemukan</h3>
                <p class="text-slate-400 text-sm mt-2 font-medium">Coba gunakan kata kunci pencarian yang berbeda.</p>
            </div>
        </div>
    </div>

</div>

<script>
document.getElementById('search-rekap').addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    const rows = document.querySelectorAll('.rekap-row');
    let visible = 0;
    rows.forEach(row => {
        const nama = row.dataset.nama || '';
        const nisn = row.dataset.nisn || '';
        const match = !q || nama.includes(q) || nisn.includes(q);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    document.getElementById('empty-rekap').classList.toggle('hidden', visible > 0 || !q);
    
    // Re-index number
    let no = 1;
    rows.forEach(row => {
        if (row.style.display !== 'none') {
            const noCell = row.querySelector('.row-no');
            if (noCell) noCell.textContent = no++;
        }
    });
});
</script>
@endsection
