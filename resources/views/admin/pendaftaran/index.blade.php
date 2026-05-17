@extends('layouts.admin')

@section('title', 'Verifikasi Pendaftaran')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Verifikasi Pendaftaran</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm font-medium">Monitoring dan validasi pengajuan pendaftaran calon siswa baru.</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
             <div class="flex -space-x-3 overflow-hidden">
                @foreach($pendaftarans->take(3) as $p)
                    <div class="inline-block h-10 w-10 rounded-2xl ring-4 ring-white dark:ring-zinc-900 bg-indigo-500 flex items-center justify-center text-white text-[10px] font-black uppercase">
                        {{ substr($p->nama_lengkap, 0, 1) }}
                    </div>
                @endforeach
                @if($pendaftarans->total() > 3)
                    <div class="inline-block h-10 w-10 rounded-2xl ring-4 ring-white dark:ring-zinc-900 bg-slate-100 dark:bg-zinc-800 flex items-center justify-center text-slate-500 text-[10px] font-black uppercase">
                        +{{ $pendaftarans->total() - 3 }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Status Filter --}}
    <div class="flex flex-wrap gap-3">
        @foreach(['semua'=>'Semua Pendaftaran','menunggu'=>'Menunggu Verifikasi','diverifikasi'=>'Sudah Diverifikasi','ditolak'=>'Pendaftaran Ditolak'] as $key=>$label)
            <a href="{{ route('admin.pendaftaran.index', $key !== 'semua' ? ['status'=>$key] : []) }}"
               class="px-6 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all border
               {{ (request('status', 'semua') === $key)
                    ? 'bg-indigo-600 text-white border-indigo-600 shadow-xl shadow-indigo-500/30'
                    : 'bg-white dark:bg-zinc-900 text-slate-500 dark:text-slate-400 border-slate-100 dark:border-zinc-800 hover:bg-slate-50 dark:hover:bg-zinc-800 shadow-sm' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Table Card --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden">

        {{-- Toolbar --}}
        <div class="p-6 flex flex-col lg:flex-row lg:items-end justify-between gap-6 border-b border-slate-100 dark:border-zinc-800 bg-slate-50/30 dark:bg-zinc-900/30">
            <form method="GET" class="flex flex-col sm:flex-row sm:items-end gap-4 flex-1">
                {{-- Per Page --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Tampilkan</label>
                    <select name="per_page" onchange="this.form.submit()" 
                        class="bg-white dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-xs font-black py-2 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        @foreach([10,25,50,100] as $n)
                            <option value="{{ $n }}" {{ request('per_page', 10) == $n ? 'selected' : '' }}>{{ $n }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Paket Filter --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Paket Bimbingan</label>
                    <select name="paket_id" onchange="this.form.submit()"
                        class="bg-white dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-xs font-black py-2 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        <option value="">Semua Paket</option>
                        @foreach($pakets as $p)
                            <option value="{{ $p->id }}" {{ request('paket_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_paket }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-1.5 flex-1 max-w-sm sm:ml-auto">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Pencarian</label>
                    <div class="flex gap-2">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        <div class="relative group flex-1">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email…"
                                class="w-full bg-white dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold py-2.5 pl-11 pr-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                            <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <button type="submit" class="bg-slate-900 dark:bg-white dark:text-slate-900 hover:bg-slate-800 dark:hover:bg-slate-100 text-white font-black text-xs px-6 py-2.5 rounded-xl transition-all active:scale-95 shadow-lg shadow-slate-900/10">
                            Cari
                        </button>
                        @if(request()->anyFilled(['search', 'paket_id']))
                            <a href="{{ route('admin.pendaftaran.index', ['status'=>request('status')]) }}" 
                               class="bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 hover:bg-rose-100 p-2.5 rounded-xl transition-all"
                               title="Reset Filter">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse border border-slate-200 dark:border-zinc-800">
                <thead class="bg-indigo-600 dark:bg-indigo-900/80 text-[10px] uppercase tracking-widest text-white font-black">
                    <tr>
                        <th class="px-4 py-3 text-left w-24 border border-white/20">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'id', 'order' => request('sort') == 'id' && request('order') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center justify-between group">
                                ID
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
                                Calon Siswa
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
                        <th class="px-4 py-3 text-left border border-white/20">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'paket_bimbingan_id', 'order' => request('sort') == 'paket_bimbingan_id' && request('order') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center justify-between group">
                                Paket Program
                                <span class="transition-all {{ request('sort') == 'paket_bimbingan_id' ? 'opacity-100' : 'opacity-30 group-hover:opacity-100' }}">
                                    @if(request('sort') == 'paket_bimbingan_id' && request('order') == 'asc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 15l7-7 7 7"/></svg>
                                    @elseif(request('sort') == 'paket_bimbingan_id' && request('order') == 'desc')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7"/></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M7 10l5-5 5 5M7 14l5 5 5-5"/></svg>
                                    @endif
                                </span>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left border border-white/20">Verifikasi Bayar</th>
                        <th class="px-4 py-3 text-left border border-white/20">Status Akun</th>
                        <th class="px-4 py-3 text-center w-32 border border-white/20">Opsi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900">
                    @forelse($pendaftarans as $p)
                        <tr class="hover:bg-indigo-50/50 dark:hover:bg-indigo-900/10 transition-all even:bg-slate-50/50 dark:even:bg-zinc-800/30">
                            <td class="px-4 py-3 text-slate-500 font-bold text-xs border border-slate-200 dark:border-zinc-800 text-center">
                                #{{ $p->id }}
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center shrink-0 ring-1 ring-indigo-100 transition-all">
                                        <span class="text-indigo-600 dark:text-indigo-400 font-black text-xs">{{ substr($p->nama_lengkap, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 dark:text-white leading-tight transition-colors">{{ $p->nama_lengkap }}</p>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ $p->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded bg-slate-50 dark:bg-zinc-800 text-slate-600 dark:text-slate-300 text-[10px] font-black uppercase ring-1 ring-slate-200">
                                    {{ $p->paketBimbingan?->nama_paket ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                @if($p->pembayaran)
                                    @php $ps = $p->pembayaran->status; @endphp
                                    <span class="inline-flex items-center gap-2 px-2 py-0.5 rounded text-[9px] font-black uppercase ring-1
                                        {{ $ps === 'dikonfirmasi' ? 'bg-emerald-50 text-emerald-700 ring-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-400 dark:ring-emerald-800/30' : ($ps === 'ditolak' ? 'bg-rose-50 text-rose-700 ring-rose-100 dark:bg-rose-900/20 dark:text-rose-400 dark:ring-rose-800/30' : 'bg-amber-50 text-amber-700 ring-amber-100 dark:bg-amber-900/20 dark:text-amber-400 dark:ring-amber-800/30') }}">
                                        <span class="w-1 h-1 rounded-full {{ $ps === 'dikonfirmasi' ? 'bg-emerald-500' : ($ps === 'ditolak' ? 'bg-rose-500' : 'bg-amber-500') }} shrink-0"></span>
                                        {{ $ps }}
                                    </span>
                                @else
                                    <span class="text-slate-300 dark:text-zinc-600 font-bold text-[10px] uppercase tracking-widest italic">Belum Ada</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800 text-center">
                                @php $s = $p->status; @endphp
                                <span class="inline-flex items-center justify-center px-3 py-0.5 rounded text-[9px] font-black uppercase tracking-widest shadow-sm
                                    {{ $s === 'diverifikasi' ? 'bg-emerald-600 text-white' : ($s === 'ditolak' ? 'bg-rose-600 text-white' : 'bg-amber-500 text-white') }}">
                                    {{ $s }}
                                </span>
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.pendaftaran.show', $p) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded bg-indigo-50 dark:bg-indigo-900/10 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 transition-all border border-indigo-200/50 shadow-sm"
                                       title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-24 text-center border border-slate-200 dark:border-zinc-800">
                                <div class="w-20 h-20 bg-slate-50 dark:bg-zinc-800 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-4xl shadow-inner">
                                    📝
                                </div>
                                <h3 class="font-black text-slate-900 dark:text-white text-lg">Tidak Ada Antrean</h3>
                                <p class="text-slate-400 text-sm mt-2 font-medium max-w-xs mx-auto">Semua pengajuan pendaftaran telah diproses atau belum ada pendaftar baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer Pagination --}}
        <div class="px-8 py-6 border-t border-slate-100 dark:border-zinc-800 bg-slate-50/30 dark:bg-zinc-900/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <p class="text-xs text-slate-500 dark:text-slate-400 font-bold">
                Menampilkan <span class="text-slate-900 dark:text-white">{{ $pendaftarans->firstItem() ?? 0 }}</span> – <span class="text-slate-900 dark:text-white">{{ $pendaftarans->lastItem() ?? 0 }}</span> dari <span class="text-slate-900 dark:text-white">{{ $pendaftarans->total() ?? 0 }}</span> Pengajuan
            </p>
            @if($pendaftarans->hasPages())
                <div class="flex justify-end">
                    {{ $pendaftarans->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
    </div>

</div>
@endsection
