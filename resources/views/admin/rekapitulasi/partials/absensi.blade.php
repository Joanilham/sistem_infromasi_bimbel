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

<div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-slate-100 dark:border-zinc-800 shadow-sm">
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
