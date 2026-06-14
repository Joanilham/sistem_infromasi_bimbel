@extends('layouts.admin')

@section('title', 'Edit Jadwal Bimbingan')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('admin.jadwal.index') }}" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-zinc-800 flex items-center justify-center text-slate-500 hover:bg-indigo-100 hover:text-indigo-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Edit Sesi Jadwal</h1>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium ml-11">Perbarui data jadwal belajar mengajar untuk guru dan kelas.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-rose-50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-800 text-rose-800 dark:text-rose-400 rounded-3xl p-6 text-sm font-bold">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm" x-data="jadwalForm()">
        <form action="{{ route('admin.jadwal.update', $jadwal->id) }}" method="POST" class="space-y-8 max-w-4xl mx-auto">
            @csrf
            @method('PUT')

            <!-- Alert Warning Tabrakan -->
            <div class="bg-indigo-50/50 dark:bg-indigo-950/15 border border-indigo-100 dark:border-indigo-900/30 rounded-[2rem] p-5 flex gap-4">
                <div class="w-10 h-10 rounded-2xl bg-indigo-500/10 flex items-center justify-center shrink-0 text-indigo-600 dark:text-indigo-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h4 class="text-xs font-black text-slate-800 dark:text-slate-200 uppercase tracking-wider">Validasi Bentrok Pintar</h4>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 font-bold leading-normal">Sistem akan secara otomatis memeriksa dan memberikan peringatan jika ada jadwal mengajar guru yang bentrok/tumpang tindih.</p>
                </div>
            </div>

            <div class="space-y-6">
                {{-- Section: Tenaga Pengajar & Hari --}}
                <div class="bg-slate-50 dark:bg-zinc-950/40 p-6 rounded-[2rem] border border-slate-100 dark:border-zinc-800 space-y-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 dark:text-slate-500 uppercase tracking-widest ml-1">Tenaga Pengajar <span class="text-rose-500">*</span></label>
                        <div class="relative" x-data="{ open: false, search: '' }" @click.away="open = false">
                            <!-- Tombol Trigger -->
                            <div @click="open = !open" 
                                class="w-full bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 flex justify-between items-center cursor-pointer focus:ring-2 focus:ring-indigo-500/20 transition-all text-slate-800 dark:text-white group hover:border-indigo-300 dark:hover:border-indigo-700">
                                <div class="flex flex-col gap-0.5">
                                    <span x-text="form.guru_id ? guruList.find(g => g.id == form.guru_id)?.name : 'Pilih Tenaga Pengajar'" 
                                        :class="form.guru_id ? 'text-slate-900 dark:text-white' : 'text-slate-400 dark:text-slate-500'"></span>
                                    <template x-if="form.guru_id && guruList.find(g => g.id == form.guru_id)?.matapelajaran">
                                        <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest" x-text="'Mapel: ' + guruList.find(g => g.id == form.guru_id)?.matapelajaran"></span>
                                    </template>
                                </div>
                                <div class="text-slate-400 group-hover:text-indigo-500 transition-colors">
                                    <svg class="w-4 h-4 transform transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>

                            <!-- Dropdown Pop-up Premium -->
                            <div x-show="open" 
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-[-10px]"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 translate-y-[-10px]"
                                class="absolute z-50 w-full mt-2 bg-white dark:bg-zinc-800 rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] dark:shadow-none border border-slate-100 dark:border-zinc-700 overflow-hidden flex flex-col">
                                
                                <!-- Search Bar dalam Dropdown -->
                                <div class="p-3 border-b border-slate-100 dark:border-zinc-700 bg-slate-50/50 dark:bg-zinc-900/50">
                                    <div class="relative">
                                        <input type="text" x-model="search" placeholder="Cari nama guru..." class="w-full bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-700 rounded-xl text-xs py-2.5 pl-9 pr-3 focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-500 text-slate-700 dark:text-slate-300">
                                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                </div>

                                <!-- List Guru -->
                                <div class="max-h-60 overflow-y-auto custom-scrollbar p-2">
                                    <template x-for="g in guruList.filter(g => g.name.toLowerCase().includes(search.toLowerCase()))" :key="g.id">
                                        <div @click="form.guru_id = g.id; open = false; search = ''" 
                                            :class="form.guru_id == g.id ? 'bg-indigo-50 dark:bg-indigo-900/20 border-indigo-100 dark:border-indigo-800/30' : 'hover:bg-slate-50 dark:hover:bg-zinc-700/50 border-transparent'"
                                            class="p-3 rounded-xl cursor-pointer transition-all border flex items-center gap-3 group/item mb-1">
                                            <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-zinc-800 flex items-center justify-center shrink-0 text-slate-500 font-black group-hover/item:bg-indigo-100 group-hover/item:text-indigo-600 transition-colors">
                                                <span x-text="g.name.charAt(0)"></span>
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-bold text-sm text-slate-800 dark:text-slate-200" x-text="g.name"></div>
                                                <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 mt-0.5 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                                    <span x-text="g.matapelajaran ? g.matapelajaran : 'Belum diatur mapel'"></span>
                                                </div>
                                            </div>
                                            <div x-show="form.guru_id == g.id" class="text-indigo-600 dark:text-indigo-400">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            </div>
                                        </div>
                                    </template>
                                    
                                    <!-- Empty State -->
                                    <div x-show="guruList.filter(g => g.name.toLowerCase().includes(search.toLowerCase())).length === 0" class="py-6 text-center">
                                        <p class="text-xs text-slate-400 font-bold">Guru tidak ditemukan</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Hidden input to submit form -->
                            <input type="hidden" name="guru_id" x-model="form.guru_id" required>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 dark:text-slate-500 uppercase tracking-widest ml-1">Hari Pelaksanaan <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <select name="hari" x-model="form.hari" required class="w-full bg-white dark:bg-zinc-900 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white appearance-none">
                                <option value="">Pilih Hari</option>
                                @foreach($hariList as $h)<option value="{{ $h }}">{{ $h }}</option>@endforeach
                            </select>
                            <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section: Detail Kelas --}}
                <div class="bg-slate-50 dark:bg-zinc-950/40 p-6 rounded-[2rem] border border-slate-100 dark:border-zinc-800 space-y-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 dark:text-slate-500 uppercase tracking-widest ml-1">Kelompok Belajar</label>
                        <div class="relative">
                            <select name="rombel_id" x-model="form.rombel_id" class="w-full bg-white dark:bg-zinc-900 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white appearance-none">
                                <option value="">— Opsional —</option>
                                @foreach($rombelList as $r)<option value="{{ $r->id }}">{{ $r->nama_kelompok }}</option>@endforeach
                            </select>
                            <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section: Waktu & Tempat --}}
                <div class="bg-slate-50 dark:bg-zinc-950/40 p-6 rounded-[2rem] border border-slate-100 dark:border-zinc-800 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 dark:text-slate-500 uppercase tracking-widest ml-1">Jam Mulai <span class="text-rose-500">*</span></label>
                        <input type="time" name="jam_mulai" x-model="form.jam_mulai" required class="w-full bg-white dark:bg-zinc-900 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 dark:text-slate-500 uppercase tracking-widest ml-1">Jam Selesai <span class="text-rose-500">*</span></label>
                        <input type="time" name="jam_selesai" x-model="form.jam_selesai" required class="w-full bg-white dark:bg-zinc-900 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                    </div>

                    <div class="sm:col-span-2 space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 dark:text-slate-500 uppercase tracking-widest ml-1">Ruangan / Tempat</label>
                        <input type="text" name="ruangan" x-model="form.ruangan" placeholder="Contoh: Lab Komputer, Ruang Kelas A" class="w-full bg-white dark:bg-zinc-900 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-4 px-5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between gap-4 pt-6 border-t border-slate-100 dark:border-zinc-800 mt-8">
                <button type="button" onclick="confirmDelete()" class="inline-flex items-center gap-2 text-rose-500 hover:text-rose-700 text-xs font-black uppercase tracking-widest transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Hapus
                </button>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.jadwal.index') }}" class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-white bg-rose-500 hover:bg-rose-600 rounded-2xl transition-all shadow-xl shadow-rose-500/20 active:scale-95">
                        Batal
                    </a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-black uppercase tracking-widest px-8 py-4 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all active:scale-95">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
        
        <form id="delete-form" action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>

</div>

<script>
function jadwalForm() {
    return {
        guruList: @json($guruList),
        form: { 
            guru_id: '{{ old("guru_id", $jadwal->guru_id) }}', 
            rombel_id: '{{ old("rombel_id", $jadwal->rombel_id) }}', 
            hari: '{{ old("hari", $jadwal->hari) }}', 
            jam_mulai: '{{ old("jam_mulai", $jadwal->jam_mulai ? substr($jadwal->jam_mulai, 0, 5) : "") }}', 
            jam_selesai: '{{ old("jam_selesai", $jadwal->jam_selesai ? substr($jadwal->jam_selesai, 0, 5) : "") }}', 
            ruangan: '{{ old("ruangan", $jadwal->ruangan) }}' 
        }
    }
}

function confirmDelete() {
    Swal.fire({
        title: 'Hapus Jadwal?',
        text: "Yakin ingin menghapus jadwal ini? Data tidak dapat dikembalikan.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#4f46e5',
        cancelButtonColor: '#ef4444',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal',
        background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
        color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a',
        customClass: {
            popup: 'rounded-2xl border border-slate-100 dark:border-slate-700',
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form').submit();
        }
    });
}
</script>
@endsection
