<div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
                    <h3 class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Hadir</h3>
                    <p class="text-3xl font-black text-emerald-600 dark:text-emerald-400">{{ $total_hadir }}</p>
                    <div class="mt-2 text-xs font-bold text-slate-400">{{ $total_absensi > 0 ? round(($total_hadir / $total_absensi) * 100) : 0 }}% dari total</div>
                </div>
                <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
                    <h3 class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Sakit</h3>
                    <p class="text-3xl font-black text-amber-500">{{ $total_sakit }}</p>
                    <div class="mt-2 text-xs font-bold text-slate-400">{{ $total_absensi > 0 ? round(($total_sakit / $total_absensi) * 100) : 0 }}% dari total</div>
                </div>
                <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
                    <h3 class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Izin</h3>
                    <p class="text-3xl font-black text-blue-500">{{ $total_izin }}</p>
                    <div class="mt-2 text-xs font-bold text-slate-400">{{ $total_absensi > 0 ? round(($total_izin / $total_absensi) * 100) : 0 }}% dari total</div>
                </div>
                <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
                    <h3 class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Alpha</h3>
                    <p class="text-3xl font-black text-rose-500">{{ $total_alpha }}</p>
                    <div class="mt-2 text-xs font-bold text-slate-400">{{ $total_absensi > 0 ? round(($total_alpha / $total_absensi) * 100) : 0 }}% dari total</div>
                </div>
            </div>

            @include('admin.rekapitulasi.partials.export-card')

<div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-slate-100 dark:border-zinc-800 shadow-sm mt-6">
            <form @submit.prevent="fetchData" method="GET" action="{{ route('admin.rekapitulasi.index') }}" class="flex flex-col gap-4">
                <input type="hidden" name="tab" value="{{ $tab }}">
                
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1 flex flex-col">
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Batas Data</label>
                        <select name="per_page" @change="fetchData" class="no-tomselect w-full h-[42px] flex-1 bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 Data</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Data</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Data</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Data</option>
                        </select>
                    </div>
                    <div class="flex-1 flex flex-col">
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ request('start_date', $start_date) }}" class="w-full h-[42px] bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    </div>
                    <div class="flex-1 flex flex-col">
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Tanggal Akhir</label>
                        <input type="date" name="end_date" value="{{ request('end_date', $end_date) }}" class="w-full h-[42px] bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    </div>
                    @if($tab === 'absensi')
                        <div class="flex-1 flex flex-col">
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Cari</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa..." class="w-full h-[42px] bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        </div>
                    @endif
                    <div class="w-full sm:w-auto shrink-0 flex flex-col">
                        <label class="block text-xs font-black uppercase tracking-widest text-transparent mb-2 hidden sm:block">&nbsp;</label>
                        <button type="submit" class="w-full sm:w-auto flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs px-8 rounded-xl transition-all shadow-lg shadow-indigo-500/20 uppercase tracking-widest h-[42px]">
                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div id="table-rincian" class="bg-white dark:bg-zinc-900 rounded-[2rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm mt-6">
            <h2 class="text-lg font-black text-slate-900 dark:text-white mb-6">Daftar Rincian Kehadiran Siswa</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse border border-slate-200 dark:border-zinc-800">
                    <thead class="bg-indigo-600 dark:bg-indigo-900/80 text-[10px] uppercase tracking-widest text-white font-black">
                        <tr>
                            <th class="px-4 py-3 text-left border border-white/20">Nama Siswa</th>
                            <th class="px-4 py-3 text-left border border-white/20">Kelas</th>
                            <th class="px-4 py-3 text-center border border-white/20">Hadir</th>
                            <th class="px-4 py-3 text-center border border-white/20">Izin</th>
                            <th class="px-4 py-3 text-center border border-white/20">Sakit</th>
                            <th class="px-4 py-3 text-center border border-white/20">Alpha</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-900">
                        @forelse($rekap_siswa as $siswa)
                            <tr class="hover:bg-indigo-50/50 dark:hover:bg-indigo-900/10 transition-all even:bg-slate-50/50 dark:even:bg-zinc-800/30">
                                <td class="px-4 py-3 font-bold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-zinc-800">{{ $siswa->nama_lengkap }}</td>
                                <td class="px-4 py-3 text-xs border border-slate-200 dark:border-zinc-800">
                                    <div class="font-bold text-slate-600 dark:text-slate-400">{{ $siswa->kelompokBelajar->nama_kelompok ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-3 text-center border border-slate-200 dark:border-zinc-800">
                                    <span class="inline-flex w-7 h-7 rounded-full items-center justify-center font-bold {{ $siswa->hadir_count > 0 ? 'bg-emerald-100 text-emerald-700' : 'text-slate-400' }}">{{ $siswa->hadir_count }}</span>
                                </td>
                                <td class="px-4 py-3 text-center border border-slate-200 dark:border-zinc-800">
                                    <span class="inline-flex w-7 h-7 rounded-full items-center justify-center font-bold {{ $siswa->izin_count > 0 ? 'bg-blue-100 text-blue-700' : 'text-slate-400' }}">{{ $siswa->izin_count }}</span>
                                </td>
                                <td class="px-4 py-3 text-center border border-slate-200 dark:border-zinc-800">
                                    <span class="inline-flex w-7 h-7 rounded-full items-center justify-center font-bold {{ $siswa->sakit_count > 0 ? 'bg-amber-100 text-amber-700' : 'text-slate-400' }}">{{ $siswa->sakit_count }}</span>
                                </td>
                                <td class="px-4 py-3 text-center border border-slate-200 dark:border-zinc-800">
                                    <span class="inline-flex w-7 h-7 rounded-full items-center justify-center font-bold {{ $siswa->alpha_count > 0 ? 'bg-rose-100 text-rose-700' : 'text-slate-400' }}">{{ $siswa->alpha_count }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400 italic border border-slate-200 dark:border-zinc-800">Belum ada data absensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $rekap_siswa->fragment('table-rincian')->links() }}
            </div>
        </div>
