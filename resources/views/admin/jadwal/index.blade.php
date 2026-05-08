@extends('layouts.admin')
@section('title', 'Manajemen Jadwal')
@section('content')

<div x-data="jadwalManager()" class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 dark:text-white">Manajemen Jadwal</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola jadwal pelajaran seluruh guru & rombel</p>
        </div>
        <div class="flex flex-wrap gap-2">
            {{-- Tombol Duplikasi --}}
            <button @click="showDuplikasi = true"
                class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2.5 rounded-xl flex items-center gap-2 transition-all text-sm font-semibold shadow-lg shadow-amber-200 dark:shadow-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                Duplikasi
            </button>
            {{-- Tombol Tambah --}}
            <button @click="openAddModal()"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl flex items-center gap-2 transition-all text-sm font-semibold shadow-lg shadow-indigo-200 dark:shadow-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Jadwal
            </button>
        </div>
    </div>

    {{-- FILTER BAR --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-slate-100 dark:border-zinc-800 p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.jadwal.index') }}" class="flex flex-col sm:flex-row gap-3">
            <select name="guru_id" class="flex-1 border border-slate-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Guru</option>
                @foreach($guruList as $g)
                <option value="{{ $g->id }}" {{ $filterGuru == $g->id ? 'selected' : '' }}>{{ $g->name }}</option>
                @endforeach
            </select>
            <select name="rombel_id" class="flex-1 border border-slate-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Rombel</option>
                @foreach($rombelList as $r)
                <option value="{{ $r->id }}" {{ $filterRombel == $r->id ? 'selected' : '' }}>{{ $r->nama_kelompok }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-slate-700 hover:bg-slate-800 text-white px-5 py-2 rounded-lg text-sm font-semibold transition-colors">Filter</button>
            @if($filterGuru || $filterRombel)
            <a href="{{ route('admin.jadwal.index') }}" class="text-slate-500 hover:text-red-500 px-3 py-2 text-sm font-medium transition-colors">Reset</a>
            @endif
        </form>
    </div>

    {{-- KALENDER GRID --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px]">
                <thead>
                    <tr class="bg-slate-50 dark:bg-zinc-800/50">
                        <th class="py-3 px-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider w-20">Jam</th>
                        @foreach(\App\Models\Jadwal::HARI_LIST as $hari)
                        <th class="py-3 px-3 text-center text-xs font-bold uppercase tracking-wider {{ now()->locale('id')->isoFormat('dddd') === $hari ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20' : 'text-slate-500 dark:text-slate-400' }}">
                            {{ $hari }}
                            @if(now()->locale('id')->isoFormat('dddd') === $hari)
                            <span class="block text-[10px] text-indigo-500 font-medium">Hari Ini</span>
                            @endif
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        $timeSlots = [];
                        for ($h = 7; $h <= 20; $h++) {
                            $timeSlots[] = sprintf('%02d:00', $h);
                        }
                        $hariList = \App\Models\Jadwal::HARI_LIST;
                    @endphp
                    @foreach($timeSlots as $slot)
                    <tr class="border-t border-slate-100 dark:border-zinc-800 hover:bg-slate-50/50 dark:hover:bg-zinc-800/30 transition-colors">
                        <td class="py-2 px-4 text-xs font-mono text-slate-400 dark:text-slate-500 align-top whitespace-nowrap">{{ $slot }}</td>
                        @foreach($hariList as $hari)
                        <td class="py-1 px-1 align-top {{ now()->locale('id')->isoFormat('dddd') === $hari ? 'bg-indigo-50/50 dark:bg-indigo-900/10' : '' }}">
                            @php
                                $slotJadwals = $jadwals->filter(function($j) use ($hari, $slot) {
                                    return $j->hari === $hari
                                        && \Carbon\Carbon::parse($j->jam_mulai)->format('H:00') === $slot;
                                });
                            @endphp
                            @foreach($slotJadwals as $j)
                            <div class="mb-1 rounded-lg p-2 text-xs cursor-pointer transition-all hover:scale-[1.02] hover:shadow-md
                                {{ ['bg-indigo-100 dark:bg-indigo-900/40 border-l-4 border-indigo-500 text-indigo-800 dark:text-indigo-200',
                                    'bg-emerald-100 dark:bg-emerald-900/40 border-l-4 border-emerald-500 text-emerald-800 dark:text-emerald-200',
                                    'bg-amber-100 dark:bg-amber-900/40 border-l-4 border-amber-500 text-amber-800 dark:text-amber-200',
                                    'bg-rose-100 dark:bg-rose-900/40 border-l-4 border-rose-500 text-rose-800 dark:text-rose-200',
                                    'bg-purple-100 dark:bg-purple-900/40 border-l-4 border-purple-500 text-purple-800 dark:text-purple-200',
                                    'bg-cyan-100 dark:bg-cyan-900/40 border-l-4 border-cyan-500 text-cyan-800 dark:text-cyan-200',
                                   ][$j->id % 6] }}"
                                 @click="openEditModal({{ $j->toJson() }})">
                                <div class="font-bold truncate">{{ $j->mataPelajaran?->nama ?? '-' }}</div>
                                <div class="text-[10px] opacity-75 mt-0.5">{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</div>
                                <div class="text-[10px] opacity-75 truncate">{{ $j->guru?->name ?? '-' }}</div>
                                <div class="text-[10px] opacity-75 truncate">{{ $j->rombel?->nama_kelompok ?? '-' }} {{ $j->ruangan ? '· '.$j->ruangan : '' }}</div>
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

    {{-- MODAL TAMBAH/EDIT --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-black/50" @click="showModal = false"></div>
        <div class="relative bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-zinc-700 w-full max-w-lg max-h-[90vh] overflow-y-auto" @click.stop>
            <div class="p-6">
                <h3 class="text-lg font-bold dark:text-white mb-4" x-text="editId ? 'Edit Jadwal' : 'Tambah Jadwal'"></h3>
                <form :action="editId ? '{{ url('admin/jadwal') }}/' + editId : '{{ route('admin.jadwal.store') }}'" method="POST" class="space-y-4">
                    @csrf
                    <template x-if="editId"><input type="hidden" name="_method" value="PUT"></template>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Guru <span class="text-red-500">*</span></label>
                        <select name="guru_id" x-model="form.guru_id" required class="w-full border border-slate-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white rounded-lg px-3 py-2 text-sm">
                            <option value="">Pilih Guru</option>
                            @foreach($guruList as $g)
                            <option value="{{ $g->id }}">{{ $g->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Rombel / Kelompok Belajar</label>
                        <select name="rombel_id" x-model="form.rombel_id" class="w-full border border-slate-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white rounded-lg px-3 py-2 text-sm">
                            <option value="">— Opsional —</option>
                            @foreach($rombelList as $r)
                            <option value="{{ $r->id }}">{{ $r->nama_kelompok }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Mata Pelajaran</label>
                        <select name="mata_pelajaran_id" x-model="form.mata_pelajaran_id" class="w-full border border-slate-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white rounded-lg px-3 py-2 text-sm">
                            <option value="">— Opsional —</option>
                            @foreach($mapelList as $m)
                            <option value="{{ $m->id }}">{{ $m->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Hari <span class="text-red-500">*</span></label>
                        <select name="hari" x-model="form.hari" required class="w-full border border-slate-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white rounded-lg px-3 py-2 text-sm">
                            <option value="">Pilih Hari</option>
                            @foreach(\App\Models\Jadwal::HARI_LIST as $h)
                            <option value="{{ $h }}">{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Jam Mulai <span class="text-red-500">*</span></label>
                            <input type="time" name="jam_mulai" x-model="form.jam_mulai" required class="w-full border border-slate-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Jam Selesai <span class="text-red-500">*</span></label>
                            <input type="time" name="jam_selesai" x-model="form.jam_selesai" required class="w-full border border-slate-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white rounded-lg px-3 py-2 text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Ruangan</label>
                        <input type="text" name="ruangan" x-model="form.ruangan" placeholder="Contoh: Lab IPA, R.101" class="w-full border border-slate-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white rounded-lg px-3 py-2 text-sm">
                    </div>

                    <div class="flex justify-between items-center pt-4 border-t border-slate-100 dark:border-zinc-800">
                        <template x-if="editId">
                            <button type="button" @click="hapusJadwal()" class="text-red-500 hover:text-red-700 text-sm font-semibold transition-colors">Hapus Jadwal</button>
                        </template>
                        <template x-if="!editId"><span></span></template>
                        <div class="flex gap-2">
                            <button type="button" @click="showModal = false" class="px-4 py-2 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-zinc-800 rounded-lg transition-colors">Batal</button>
                            <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors">Simpan</button>
                        </div>
                    </div>
                </form>

                {{-- Hidden delete form --}}
                <form x-ref="deleteForm" method="POST" style="display:none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL DUPLIKASI --}}
    <div x-show="showDuplikasi" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition>
        <div class="fixed inset-0 bg-black/50" @click="showDuplikasi = false"></div>
        <div class="relative bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-zinc-700 w-full max-w-md" @click.stop>
            <div class="p-6">
                <h3 class="text-lg font-bold dark:text-white mb-2">Duplikasi Jadwal</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Copy seluruh jadwal dari periode saat ini ke periode lain.</p>
                <form action="{{ route('admin.jadwal.duplikasi') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Periode Tujuan</label>
                        <select name="target_periode_id" required class="w-full border border-slate-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white rounded-lg px-3 py-2 text-sm">
                            <option value="">Pilih Periode Tujuan</option>
                            @foreach(\App\Models\Periode::all() as $p)
                            <option value="{{ $p->id }}">{{ $p->tahun_periode ?? 'Periode #'.$p->id }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2 justify-end pt-2">
                        <button type="button" @click="showDuplikasi = false" class="px-4 py-2 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-zinc-800 rounded-lg transition-colors">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-lg transition-colors">Duplikasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
            form.action = '/admin/jadwal/' + this.editId;
            form.submit();
        }
    };
}
</script>

@endsection
