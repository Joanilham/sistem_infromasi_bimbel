<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
                    <div class="absolute -right-6 -top-6 text-indigo-50 dark:text-indigo-900/10 transition-transform group-hover:scale-110">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
                    </div>
                    <div class="relative">
                        <h3 class="text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Total Siswa Aktif</h3>
                        <p class="text-4xl font-black text-indigo-600 dark:text-indigo-400">{{ $total_aktif }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
                    <div class="absolute -right-6 -top-6 text-rose-50 dark:text-rose-900/10 transition-transform group-hover:scale-110">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                    </div>
                    <div class="relative">
                        <h3 class="text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Total Siswa Keluar</h3>
                        <p class="text-4xl font-black text-rose-600 dark:text-rose-400">{{ $total_keluar }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group sm:col-span-2">
                    <h3 class="text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-4">Distribusi Jenis Kelamin (Siswa Aktif)</h3>
                    <div class="flex items-center gap-6">
                        <div class="flex-1">
                            <div class="flex justify-between items-end mb-2">
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Laki-Laki</span>
                                <span class="text-lg font-black text-sky-600">{{ $gender_aktif_l }}</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-zinc-800 rounded-full h-3">
                                <div class="bg-sky-500 h-3 rounded-full" style="width: {{ $total_aktif > 0 ? ($gender_aktif_l / $total_aktif) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-end mb-2">
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Perempuan</span>
                                <span class="text-lg font-black text-pink-600">{{ $gender_aktif_p }}</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-zinc-800 rounded-full h-3">
                                <div class="bg-pink-500 h-3 rounded-full" style="width: {{ $total_aktif > 0 ? ($gender_aktif_p / $total_aktif) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-zinc-900 rounded-2xl p-5 border border-slate-100 dark:border-zinc-800 shadow-sm">
                    <h2 class="text-base font-black text-slate-900 dark:text-white mb-4">Siswa Aktif Berdasarkan Paket Bimbingan</h2>
                    <div class="space-y-3 max-h-[300px] overflow-y-auto pr-2" style="scrollbar-width: thin;">
                        @forelse($rekap_paket as $rp)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-800 hover:border-indigo-300 dark:hover:border-indigo-500/50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-black">
                                        {{ substr($rp->paketBimbingan->nama_paket ?? '?', 0, 1) }}
                                    </div>
                                    <span class="font-bold text-slate-700 dark:text-slate-300">{{ $rp->paketBimbingan->nama_paket ?? 'Tanpa Paket' }}</span>
                                </div>
                                <span class="text-xl font-black text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20 px-4 py-1.5 rounded-xl">{{ $rp->total }} <span class="text-[10px] text-indigo-400 dark:text-indigo-500 ml-1 uppercase">Siswa</span></span>
                            </div>
                        @empty
                            <p class="text-slate-500 dark:text-slate-400 text-sm italic">Belum ada data distribusi paket.</p>
                        @endforelse
                    </div>
                </div>
                
                <div class="bg-white dark:bg-zinc-900 rounded-2xl p-5 border border-slate-100 dark:border-zinc-800 shadow-sm">
                    <h2 class="text-base font-black text-slate-900 dark:text-white mb-4">Siswa Aktif Berdasarkan Kelas</h2>
                    <div class="space-y-3 max-h-[300px] overflow-y-auto pr-2" style="scrollbar-width: thin;">
                        @forelse($rekap_kelas as $rk)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-800 hover:border-teal-300 dark:hover:border-teal-500/50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-teal-100 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 flex items-center justify-center font-black">
                                        {{ substr($rk->kelompokBelajar->nama_kelompok ?? '?', 0, 1) }}
                                    </div>
                                    <span class="font-bold text-slate-700 dark:text-slate-300">{{ $rk->kelompokBelajar->nama_kelompok ?? 'Tanpa Kelas' }}</span>
                                </div>
                                <span class="text-xl font-black text-teal-600 dark:text-teal-400 bg-teal-50 dark:bg-teal-900/20 px-4 py-1.5 rounded-xl">{{ $rk->total }} <span class="text-[10px] text-teal-400 dark:text-teal-500 ml-1 uppercase">Siswa</span></span>
                            </div>
                        @empty
                            <p class="text-slate-500 dark:text-slate-400 text-sm italic">Belum ada data distribusi kelas.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            @include('admin.rekapitulasi.partials.export-card')

<div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-slate-100 dark:border-zinc-800 shadow-sm mt-6">
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
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Filter Kelas</label>
                    <select name="kelompok_belajar_id" autocomplete="off" @change="fetchData" class="no-tomselect w-full flex-1 bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        <option value="" {{ request('kelompok_belajar_id') == '' ? 'selected' : '' }}>Semua Kelas</option>
                        @php
                            // Mengambil semua kelas yang memiliki siswa aktif di context ini
                            // Kita gunakan query independen agar opsi tidak hilang saat tabel difilter
                            $cacheKey = 'filter_kelas_available_' . session('kantor_id', 'all') . '_' . session('periode_id', 'all');
                            $availableClasses = \Illuminate\Support\Facades\Cache::remember($cacheKey, 60, function() {
                                return \App\Models\Akademik\PesertaDidik::inContext()
                                    ->aktif()
                                    ->whereNotNull('kelompok_belajar_id')
                                    ->select('kelompok_belajar_id')
                                    ->distinct()
                                    ->with('kelompokBelajar')
                                    ->get();
                            });
                        @endphp
                        @foreach($availableClasses as $ac)
                            <option value="{{ $ac->kelompok_belajar_id }}" {{ (string)request('kelompok_belajar_id') === (string)$ac->kelompok_belajar_id ? 'selected' : '' }}>
                                {{ $ac->kelompokBelajar->nama_kelompok ?? 'Tanpa Kelas' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 flex flex-col">
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Jenis Kelamin</label>
                    <select name="jenis_kelamin" @change="fetchData" class="no-tomselect w-full flex-1 bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        <option value="">Semua Jenis Kelamin</option>
                        <option value="L" {{ $selected_gender == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                        <option value="P" {{ $selected_gender == 'P' ? 'selected' : '' }}>Perempuan</option>
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
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / NISN..." class="w-full h-[42px] bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
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
                <h2 class="text-lg font-black text-slate-900 dark:text-white mb-6">Daftar Rincian Siswa</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border-collapse border border-slate-200 dark:border-zinc-800">
                        <thead class="bg-indigo-600 dark:bg-indigo-900/80 text-[10px] uppercase tracking-widest text-white font-black">
                            <tr>
                                <th class="px-4 py-3 text-left border border-white/20">Nama Lengkap</th>
                                <th class="px-4 py-3 text-center border border-white/20">L/P</th>
                                <th class="px-4 py-3 text-left border border-white/20">Kelas</th>
                                <th class="px-4 py-3 text-left border border-white/20">Paket</th>
                                <th class="px-4 py-3 text-center border border-white/20">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-900">
                            @forelse($list_siswa as $siswa)
                                <tr class="hover:bg-indigo-50/50 dark:hover:bg-indigo-900/10 transition-all even:bg-slate-50/50 dark:even:bg-zinc-800/30">
                                    <td class="px-4 py-3 font-bold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-zinc-800">{{ $siswa->nama_lengkap }}</td>
                                    <td class="px-4 py-3 text-center text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-zinc-800">{{ $siswa->jenis_kelamin }}</td>
                                    <td class="px-4 py-3 text-xs border border-slate-200 dark:border-zinc-800 font-bold text-slate-600 dark:text-slate-400">
                                        {{ $siswa->kelompokBelajar->nama_kelompok ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-xs border border-slate-200 dark:border-zinc-800 font-bold text-slate-600 dark:text-slate-400">
                                        {{ $siswa->paketBimbingan->nama_paket ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-center border border-slate-200 dark:border-zinc-800">
                                        @if(strtolower($siswa->status) === 'aktif')
                                            <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Aktif</span>
                                        @else
                                            <span class="bg-rose-100 text-rose-700 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Keluar</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400 italic border border-slate-200 dark:border-zinc-800">Belum ada data siswa.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $list_siswa->fragment('table-rincian')->links() }}
                </div>
            </div>
