<div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
                    <div class="absolute -right-6 -top-6 text-emerald-50 dark:text-emerald-900/10 transition-transform group-hover:scale-110">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                    </div>
                    <div class="relative">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Total Guru Aktif</h3>
                                <p class="text-4xl font-black text-emerald-600 dark:text-emerald-400">{{ $total_aktif }}</p>
                            </div>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-100 dark:border-zinc-800/50">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Laki-Laki</span>
                                <span class="text-lg font-black text-indigo-600">{{ $guru_gender_aktif_l }}</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-zinc-800 rounded-full h-3 mb-4">
                                <div class="bg-indigo-500 h-3 rounded-full" style="width: {{ $total_aktif > 0 ? ($guru_gender_aktif_l / $total_aktif) * 100 : 0 }}%"></div>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Perempuan</span>
                                <span class="text-lg font-black text-pink-600">{{ $guru_gender_aktif_p }}</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-zinc-800 rounded-full h-3">
                                <div class="bg-pink-500 h-3 rounded-full" style="width: {{ $total_aktif > 0 ? ($guru_gender_aktif_p / $total_aktif) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
                    <div class="absolute -right-6 -top-6 text-rose-50 dark:text-rose-900/10 transition-transform group-hover:scale-110">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                    </div>
                    <div class="relative">
                        <h3 class="text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Total Guru Keluar</h3>
                        <p class="text-4xl font-black text-rose-600 dark:text-rose-400">{{ $total_keluar }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-[2rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm">
                <h2 class="text-lg font-black text-slate-900 dark:text-white mb-6">Guru Aktif Berdasarkan Mata Pelajaran</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($rekap_mapel as $rm)
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-800 hover:border-emerald-300 dark:hover:border-emerald-500/50 transition-colors">
                            <span class="font-bold text-slate-700 dark:text-slate-300">{{ $rm->matapelajaran ?: 'Tanpa Mapel' }}</span>
                            <span class="text-lg font-black text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 px-3 py-1 rounded-xl">{{ $rm->total }} <span class="text-[10px] ml-0.5">Guru</span></span>
                        </div>
                    @empty
                        <p class="text-slate-500 dark:text-slate-400 text-sm italic col-span-full">Belum ada data mata pelajaran.</p>
                    @endforelse
                </div>
            </div>

<div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-slate-100 dark:border-zinc-800 shadow-sm">
            <form @submit.prevent="fetchData" method="GET" action="{{ route('admin.rekapitulasi.index') }}" class="flex flex-col sm:flex-row gap-4">
                <input type="hidden" name="tab" value="{{ $tab }}">
                
                <div class="flex-1 flex flex-col">
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Batas Data</label>
                    <select name="per_page" @change="fetchData" class="no-tomselect w-full flex-1 bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 Data</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Data</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Data</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Data</option>
                    </select>
                </div>

                <div class="flex-1 flex flex-col">
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Filter Mapel</label>
                    <select name="matapelajaran" @change="fetchData" class="no-tomselect w-full flex-1 bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        <option value="">Semua Mata Pelajaran</option>
                        @foreach($filter_mapel as $m)
                            <option value="{{ $m }}" {{ $selected_mapel == $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 flex flex-col">
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Jenis Kelamin</label>
                    <select name="jenis_kelamin" @change="fetchData" class="no-tomselect w-full flex-1 bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        <option value="">Semua Jenis Kelamin</option>
                        <option value="Laki-Laki" {{ $selected_gender == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                        <option value="Perempuan" {{ $selected_gender == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="flex-1 flex flex-col">
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Status</label>
                    <select name="status" @change="fetchData" class="no-tomselect w-full flex-1 bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ (isset($selected_status) && $selected_status == 'aktif') ? 'selected' : '' }}>Aktif</option>
                        <option value="keluar" {{ (isset($selected_status) && $selected_status == 'keluar') ? 'selected' : '' }}>Keluar</option>
                    </select>
                </div>
                <div class="flex-1 flex flex-col">
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama guru..." class="w-full h-[42px] bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                </div>
                <div class="w-full sm:w-auto shrink-0 flex flex-col">
                    <label class="block text-xs font-black uppercase tracking-widest text-transparent mb-2 hidden sm:block">&nbsp;</label>
                    <button type="submit" class="w-full sm:w-auto flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs px-8 rounded-xl transition-all shadow-lg shadow-indigo-500/20 uppercase tracking-widest h-[42px]">
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

            <div id="table-rincian" class="bg-white dark:bg-zinc-900 rounded-[2rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm mt-6">
                <h2 class="text-lg font-black text-slate-900 dark:text-white mb-6">Daftar Rincian Guru</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border-collapse border border-slate-200 dark:border-zinc-800">
                        <thead class="bg-indigo-600 dark:bg-indigo-900/80 text-[10px] uppercase tracking-widest text-white font-black">
                            <tr>
                                <th class="px-4 py-3 text-left border border-white/20">Nama Guru</th>
                                <th class="px-4 py-3 text-center border border-white/20">L/P</th>
                                <th class="px-4 py-3 text-left border border-white/20">Mata Pelajaran</th>
                                <th class="px-4 py-3 text-center border border-white/20">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-900">
                            @forelse($list_guru as $guru)
                                <tr class="hover:bg-indigo-50/50 dark:hover:bg-indigo-900/10 transition-all even:bg-slate-50/50 dark:even:bg-zinc-800/30">
                                    <td class="px-4 py-3 font-bold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-zinc-800">{{ $guru->name }}</td>
                                    <td class="px-4 py-3 text-center text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-zinc-800">{{ $guru->jenis_kelamin == 'Laki-Laki' ? 'L' : ($guru->jenis_kelamin == 'Perempuan' ? 'P' : '-') }}</td>
                                    <td class="px-4 py-3 font-bold text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-zinc-800">{{ $guru->matapelajaran ?: '-' }}</td>
                                    <td class="px-4 py-3 text-center border border-slate-200 dark:border-zinc-800">
                                        @if(strtolower($guru->status) === 'aktif')
                                            <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Aktif</span>
                                        @else
                                            <span class="bg-rose-100 text-rose-700 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Keluar</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400 italic border border-slate-200 dark:border-zinc-800">Belum ada data guru.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $list_guru->fragment('table-rincian')->links() }}
                </div>
            </div>
