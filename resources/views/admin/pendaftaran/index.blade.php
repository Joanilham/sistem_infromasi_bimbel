@extends('layouts.admin')

@section('title', 'Verifikasi Pendaftaran')

@section('content')
<div class="space-y-6" x-data="ajaxTable()">

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

        {{-- Toolbar Filter & Search --}}
        <div class="p-5 sm:p-6 border-b border-slate-100 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/50 relative">

            {{-- Loading Overlay --}}
            <x-table.loading-overlay />

            <form @submit.prevent="fetchData" method="GET" action="{{ route('admin.pendaftaran.index') }}" class="flex flex-col gap-4 sm:gap-5">
                <input type="hidden" name="status" value="{{ request('status') }}">
                
                {{-- Top Row: Universal Controls --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full">
                    {{-- Per Page --}}
                    <x-table.filter-limit :alpine="true" />

                    {{-- Search & Reset --}}
                    <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full md:w-auto shrink-0">
                        <x-table.search :alpine="true" placeholder="Cari nama atau email…" />
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-colors shadow-sm shadow-indigo-500/30 shrink-0">
                            Filter
                        </button>
                        @if(request()->anyFilled(['search', 'paket_id']))
                            <a href="{{ route('admin.pendaftaran.index', ['status' => request('status')]) }}" 
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
                    {{-- Paket Filter --}}
                    <div class="flex items-stretch bg-white dark:bg-zinc-950 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all overflow-hidden">
                        <div class="px-3 py-2 bg-slate-50 dark:bg-zinc-900 border-r border-slate-200 dark:border-zinc-800 flex items-center justify-center">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Paket</span>
                        </div>
                        <select name="paket_id" @change="fetchData"
                            class="no-tomselect bg-transparent border-none text-xs font-bold focus:ring-0 py-2 pl-3 pr-8 text-slate-800 dark:text-white cursor-pointer h-full hover:bg-slate-50 dark:hover:bg-zinc-900/50 transition-colors max-w-[150px] sm:max-w-[200px] truncate">
                            <option value="">Semua Paket</option>
                            @foreach($pakets as $p)
                                <option value="{{ $p->id }}" {{ request('paket_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_paket }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </form>
        </div>

        <div id="ajax-table-body" class="overflow-x-auto" @click="if($event.target.closest('th a')) { navigate($event, $event.target.closest('a').href) }">
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
        <div id="ajax-pagination" class="px-8 py-6 border-t border-slate-100 dark:border-zinc-800 bg-slate-50/30 dark:bg-zinc-900/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4"
             @click="if($event.target.closest('nav[role=navigation] a')) { navigate($event, $event.target.closest('a').href) }">
            <p class="text-xs text-slate-500 dark:text-slate-400 font-bold">
                @if(method_exists($pendaftarans, 'total'))
                    Menampilkan <span class="text-slate-900 dark:text-white">{{ $pendaftarans->firstItem() ?? 0 }}</span> – <span class="text-slate-900 dark:text-white">{{ $pendaftarans->lastItem() ?? 0 }}</span> dari <span class="text-slate-900 dark:text-white">{{ $pendaftarans->total() ?? 0 }}</span> Pengajuan
                @else
                    Menampilkan <span class="text-slate-900 dark:text-white">{{ $pendaftarans->count() }}</span> Pengajuan
                @endif
            </p>
            @if(method_exists($pendaftarans, 'hasPages') && $pendaftarans->hasPages())
                <div class="flex justify-end">
                    {{ $pendaftarans->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
    </div>

</div>
@endsection
