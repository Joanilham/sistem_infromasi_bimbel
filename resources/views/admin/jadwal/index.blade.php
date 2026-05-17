@extends('layouts.admin')

@section('title', 'Manajemen Jadwal')

@section('content')
<div x-data="jadwalManager()" class="space-y-6">

    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Manajemen Jadwal</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm font-medium">Kelola jadwal bimbingan belajar guru dan kelompok belajar secara efisien.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 shrink-0">
            <button @click="showDuplikasi = true"
                class="inline-flex items-center gap-3 bg-white dark:bg-zinc-800 hover:bg-slate-50 dark:hover:bg-zinc-700 text-slate-700 dark:text-slate-200 font-black text-xs px-6 py-4 rounded-2xl shadow-sm border border-slate-200 dark:border-zinc-700 transition-all active:scale-95">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                Duplikasi Jadwal
            </button>
            <button @click="openAddModal()"
                class="inline-flex items-center gap-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs px-8 py-4 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all active:scale-95 group">
                <svg class="w-5 h-5 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Tambah Jadwal
            </button>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/5 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-150 duration-700"></div>
        <form method="GET" action="{{ route('admin.jadwal.index') }}" class="flex flex-col lg:flex-row items-end gap-6 relative">
            <div class="flex-1 w-full space-y-3">
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Filter Tenaga Pengajar</label>
                <div class="relative">
                    <select name="guru_id" class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all appearance-none">
                        <option value="">Semua Guru</option>
                        @foreach($guruList as $g)
                        <option value="{{ $g->id }}" {{ $filterGuru == $g->id ? 'selected' : '' }}>{{ $g->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>
            <div class="flex-1 w-full space-y-3">
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Filter Kelompok Belajar</label>
                <div class="relative">
                    <select name="rombel_id" class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all appearance-none">
                        <option value="">Semua Rombel</option>
                        @foreach($rombelList as $r)
                        <option value="{{ $r->id }}" {{ $filterRombel == $r->id ? 'selected' : '' }}>{{ $r->nama_kelompok }}</option>
                        @endforeach
                    </select>
                    <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>
            <div class="flex gap-3 w-full lg:w-auto">
                <button type="submit" class="flex-1 lg:flex-none bg-slate-900 dark:bg-white dark:text-slate-900 text-white font-black text-xs px-10 py-4 rounded-2xl hover:bg-slate-800 dark:hover:bg-slate-100 transition-all active:scale-95 shadow-lg shadow-slate-900/10 uppercase tracking-widest">Terapkan</button>
                @if($filterGuru || $filterRombel)
                    <a href="{{ route('admin.jadwal.index') }}" class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-rose-50 dark:bg-rose-900/20 text-rose-500 hover:bg-rose-100 dark:hover:bg-rose-900/40 transition-all active:scale-95 border border-rose-100 dark:border-rose-900/30" title="Reset Filter">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Kalender Grid --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1200px] text-sm table-fixed border-collapse border border-slate-200 dark:border-zinc-800">
                <thead class="bg-indigo-600 dark:bg-indigo-900/80 text-[10px] uppercase tracking-widest text-white font-black">
                    <tr>
                        <th class="py-4 px-4 text-left w-28 border border-white/20">Jam</th>
                        @foreach(\App\Models\Jadwal::HARI_LIST as $index => $hari)
                        <th class="py-4 px-2 text-center border border-white/20 {{ now()->locale('id')->isoFormat('dddd') === $hari ? 'bg-white/10' : '' }}">
                            {{ $hari }}
                            @if(now()->locale('id')->isoFormat('dddd') === $hari)
                                <div class="w-1 h-1 rounded-full bg-white mx-auto mt-1"></div>
                            @endif
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900">
                    @php
                        $timeSlots = [];
                        for ($h = 7; $h <= 20; $h++) { $timeSlots[] = sprintf('%02d:00', $h); }
                        $hariList = \App\Models\Jadwal::HARI_LIST;
                    @endphp
                    @foreach($timeSlots as $slot)
                    <tr class="hover:bg-slate-50/30 dark:hover:bg-zinc-800/20 transition-colors group">
                        <td class="py-4 px-8 text-[11px] font-black font-mono text-slate-400 dark:text-slate-500 align-top border border-slate-200 dark:border-zinc-800 bg-slate-50/20 dark:bg-zinc-900/20 text-center">{{ $slot }}</td>
                        @foreach($hariList as $index => $hari)
                        <td class="p-3 align-top h-40 {{ now()->locale('id')->isoFormat('dddd') === $hari ? 'bg-indigo-50/10 dark:bg-indigo-900/5' : '' }} relative border border-slate-200 dark:border-zinc-800">
                            @php
                                $slotJadwals = $jadwals->filter(function($j) use ($hari, $slot) {
                                    return $j->hari === $hari && \Carbon\Carbon::parse($j->jam_mulai)->format('H:00') === $slot;
                                });
                            @endphp
                            @foreach($slotJadwals as $j)
                            <div class="mb-3 rounded-2xl p-4 cursor-pointer transition-all hover:scale-[1.03] hover:shadow-xl active:scale-95 ring-1 ring-black/5 dark:ring-white/5
                                {{ ['bg-white dark:bg-zinc-800 border-l-[6px] border-indigo-500 shadow-sm',
                                    'bg-white dark:bg-zinc-800 border-l-[6px] border-emerald-500 shadow-sm',
                                    'bg-white dark:bg-zinc-800 border-l-[6px] border-amber-500 shadow-sm',
                                    'bg-white dark:bg-zinc-800 border-l-[6px] border-rose-500 shadow-sm',
                                    'bg-white dark:bg-zinc-800 border-l-[6px] border-purple-500 shadow-sm',
                                    'bg-white dark:bg-zinc-800 border-l-[6px] border-cyan-500 shadow-sm',
                                   ][$j->id % 6] }}"
                                 @click="openEditModal({{ $j->toJson() }})">
                                <div class="flex items-start justify-between gap-2">
                                    <p class="font-black text-xs text-slate-900 dark:text-white leading-tight break-words">{{ $j->mataPelajaran?->nama ?? '-' }}</p>
                                    <span class="shrink-0 w-2 h-2 rounded-full {{ ['bg-indigo-500', 'bg-emerald-500', 'bg-amber-500', 'bg-rose-500', 'bg-purple-500', 'bg-cyan-500'][$j->id % 6] }}"></span>
                                </div>
                                <div class="flex items-center gap-1.5 mt-2.5">
                                    <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">
                                        {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} – {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}
                                    </p>
                                </div>
                                <div class="mt-3 space-y-1">
                                    <p class="text-[9px] font-bold text-slate-500 dark:text-slate-400 truncate flex items-center gap-1">
                                        <svg class="w-2.5 h-2.5 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        {{ $j->guru?->name ?? '-' }}
                                    </p>
                                    <p class="text-[9px] font-bold text-slate-500 dark:text-slate-400 truncate flex items-center gap-1">
                                        <svg class="w-2.5 h-2.5 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        {{ $j->rombel?->nama_kelompok ?? '-' }} {{ $j->ruangan ? '· '.$j->ruangan : '' }}
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Tambah/Edit --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4 overflow-y-auto" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md" @click="showModal = false"></div>
        <div class="relative bg-white dark:bg-zinc-900 rounded-[3rem] shadow-2xl border border-slate-200/50 dark:border-zinc-700/50 w-full max-w-xl overflow-hidden" @click.stop>
            <div class="p-10">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight" x-text="editId ? 'Edit Sesi Jadwal' : 'Tambah Sesi Baru'"></h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mt-1">Lengkapi informasi jadwal bimbingan belajar.</p>
                    </div>
                    <button @click="showModal = false" class="w-10 h-10 rounded-2xl bg-slate-50 dark:bg-zinc-800 flex items-center justify-center text-slate-400 hover:text-rose-500 transition-all">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form :action="editId ? '{{ url('admin/jadwal') }}/' + editId : '{{ route('admin.jadwal.store') }}'" method="POST" class="space-y-6">
                    @csrf
                    <template x-if="editId"><input type="hidden" name="_method" value="PUT"></template>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="sm:col-span-2 space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tenaga Pengajar <span class="text-rose-500">*</span></label>
                            <select name="guru_id" x-model="form.guru_id" required class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                <option value="">Pilih Guru</option>
                                @foreach($guruList as $g)<option value="{{ $g->id }}">{{ $g->name }}</option>@endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kelompok Belajar</label>
                            <select name="rombel_id" x-model="form.rombel_id" class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                <option value="">— Opsional —</option>
                                @foreach($rombelList as $r)<option value="{{ $r->id }}">{{ $r->nama_kelompok }}</option>@endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Mata Pelajaran</label>
                            <select name="mata_pelajaran_id" x-model="form.mata_pelajaran_id" class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                <option value="">— Opsional —</option>
                                @foreach($mapelList as $m)<option value="{{ $m->id }}">{{ $m->nama }}</option>@endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-2 space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Hari Pelaksanaan <span class="text-rose-500">*</span></label>
                            <select name="hari" x-model="form.hari" required class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                <option value="">Pilih Hari</option>
                                @foreach(\App\Models\Jadwal::HARI_LIST as $h)<option value="{{ $h }}">{{ $h }}</option>@endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Jam Mulai <span class="text-rose-500">*</span></label>
                            <input type="time" name="jam_mulai" x-model="form.jam_mulai" required class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Jam Selesai <span class="text-rose-500">*</span></label>
                            <input type="time" name="jam_selesai" x-model="form.jam_selesai" required class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        </div>
                        <div class="sm:col-span-2 space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Ruangan / Tempat</label>
                            <input type="text" name="ruangan" x-model="form.ruangan" placeholder="Contoh: Lab IPA, R.101" class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-10 mt-6 border-t border-slate-100 dark:border-zinc-800">
                        <template x-if="editId">
                            <button type="button" @click="hapusJadwal()" class="inline-flex items-center gap-2 text-rose-500 hover:text-rose-700 text-[10px] font-black uppercase tracking-widest transition-all">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Hapus
                            </button>
                        </template>
                        <template x-if="!editId"><span></span></template>
                        <div class="flex gap-4">
                            <button type="button" @click="showModal = false" class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 hover:bg-slate-50 dark:hover:bg-zinc-800 rounded-2xl transition-all">Batal</button>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-black uppercase tracking-widest px-10 py-4 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all active:scale-95">Simpan Jadwal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Duplikasi --}}
    <div x-show="showDuplikasi" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md" @click="showDuplikasi = false"></div>
        <div class="relative bg-white dark:bg-zinc-900 rounded-[3rem] shadow-2xl border border-slate-200/50 dark:border-zinc-700/50 w-full max-w-md overflow-hidden" @click.stop>
            <div class="p-10">
                <div class="w-16 h-16 bg-amber-50 dark:bg-amber-900/20 rounded-[1.5rem] flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Duplikasi Jadwal</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 font-medium">Salin seluruh struktur jadwal dari periode saat ini ke periode tujuan.</p>
                
                <form action="{{ route('admin.jadwal.duplikasi') }}" method="POST" class="mt-8 space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih Periode Tujuan</label>
                        <select name="target_periode_id" required class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all appearance-none">
                            <option value="">-- Pilih Periode --</option>
                            @foreach(\App\Models\Periode::all() as $p)
                            <option value="{{ $p->id }}">{{ $p->tahun_periode ?? 'Periode #'.$p->id }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-4 pt-4">
                        <button type="button" @click="showDuplikasi = false" class="flex-1 px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 hover:bg-slate-50 dark:hover:bg-zinc-800 rounded-2xl transition-all">Batal</button>
                        <button type="submit" class="flex-1 bg-amber-500 hover:bg-amber-600 text-white text-[10px] font-black uppercase tracking-widest px-8 py-4 rounded-2xl shadow-xl shadow-amber-500/20 transition-all active:scale-95">Mulai Salin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Hidden Delete Form --}}
    <form x-ref="deleteForm" method="POST" style="display:none;">
        @csrf @method('DELETE')
    </form>
</div>

@push('head')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

<script>
function jadwalManager() {
    return {
        showModal: false,
        showDuplikasi: false,
        editId: null,
        form: { guru_id: '', rombel_id: '', mata_pelajaran_id: '', hari: '', jam_mulai: '', jam_selesai: '', ruangan: '' },

        openAddModal() {
            this.editId = null;
            this.form = { guru_id: '', rombel_id: '', mata_pelajaran_id: '', hari: '', jam_mulai: '', jam_selesai: '', ruangan: '' };
            this.showModal = true;
        },

        openEditModal(jadwal) {
            this.editId = jadwal.id;
            this.form = {
                guru_id: jadwal.guru_id || '',
                rombel_id: jadwal.rombel_id || '',
                mata_pelajaran_id: jadwal.mata_pelajaran_id || '',
                hari: jadwal.hari || '',
                jam_mulai: jadwal.jam_mulai ? jadwal.jam_mulai.substring(0, 5) : '',
                jam_selesai: jadwal.jam_selesai ? jadwal.jam_selesai.substring(0, 5) : '',
                ruangan: jadwal.ruangan || '',
            };
            this.showModal = true;
        },

        hapusJadwal() {
            if (!confirm('Yakin ingin menghapus jadwal ini?')) return;
            let form = this.$refs.deleteForm;
            form.action = '{{ url("admin/jadwal") }}/' + this.editId;
            form.submit();
        }
    };
}
</script>
@endsection
