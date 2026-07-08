<div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
                    <div class="absolute -right-6 -top-6 text-emerald-50 dark:text-emerald-900/10 transition-transform group-hover:scale-110">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    </div>
                    <div class="relative">
                        <h3 class="text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Total Pemasukan</h3>
                        <p class="text-3xl lg:text-4xl font-black text-emerald-600 dark:text-emerald-400">Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-slate-100 dark:border-zinc-800 shadow-sm relative overflow-hidden group">
                    <div class="absolute -right-6 -top-6 text-rose-50 dark:text-rose-900/10 transition-transform group-hover:scale-110">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                    </div>
                    <div class="relative">
                        <h3 class="text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Total Pengeluaran</h3>
                        <p class="text-3xl lg:text-4xl font-black text-rose-600 dark:text-rose-400">Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-zinc-900 rounded-[2rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm">
                    <h2 class="text-lg font-black text-slate-900 dark:text-white mb-6">Pemasukan Berdasarkan Kategori</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-800 hover:border-emerald-300 transition-colors">
                            <span class="font-bold text-slate-700 dark:text-slate-300">Pembayaran SPP/Bimbingan</span>
                            <span class="text-lg font-black text-emerald-600 dark:text-emerald-400">Rp {{ number_format($pemasukan_spp, 0, ',', '.') }}</span>
                        </div>
                        @forelse($rekap_pemasukan_lain as $rpl)
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-800 hover:border-emerald-300 transition-colors">
                                <span class="font-bold text-slate-700 dark:text-slate-300">{{ $rpl->kategori->nama ?? 'Lainnya' }}</span>
                                <span class="text-lg font-black text-emerald-600 dark:text-emerald-400">Rp {{ number_format($rpl->total, 0, ',', '.') }}</span>
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>
                
                <div class="bg-white dark:bg-zinc-900 rounded-[2rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm">
                    <h2 class="text-lg font-black text-slate-900 dark:text-white mb-6">Pengeluaran Berdasarkan Kategori</h2>
                    <div class="space-y-4">
                        @forelse($rekap_pengeluaran as $rp)
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-800 hover:border-rose-300 transition-colors">
                                <span class="font-bold text-slate-700 dark:text-slate-300">{{ $rp->kategori->nama ?? 'Lainnya' }}</span>
                                <span class="text-lg font-black text-rose-600 dark:text-rose-400">Rp {{ number_format($rp->total, 0, ',', '.') }}</span>
                            </div>
                        @empty
                            <p class="text-slate-500 dark:text-slate-400 text-sm italic">Belum ada data pengeluaran di rentang tanggal ini.</p>
                        @endforelse
                    </div>
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
                    
                    @if($tab === 'absensi')
                        <div class="w-full sm:w-auto shrink-0 flex flex-col">
                            <label class="block text-xs font-black uppercase tracking-widest text-transparent mb-2 hidden sm:block">&nbsp;</label>
                            <button type="submit" class="w-full sm:w-auto flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs px-8 rounded-xl transition-all shadow-lg shadow-indigo-500/20 uppercase tracking-widest h-[42px]">
                                Terapkan
                            </button>
                        </div>
                    @endif
                </div>

                @if($tab === 'keuangan')
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1 flex flex-col">
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Kategori</label>
                        <select name="kategori_id" @change="fetchData" class="no-tomselect w-full h-[42px] flex-1 bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                            <option value="">Semua Kategori</option>
                            <optgroup label="Pemasukan">
                                <option value="spp" {{ request('kategori_id') == 'spp' ? 'selected' : '' }}>SPP/Bimbingan</option>
                                @foreach($kategori_pemasukan_list as $kp)
                                    <option value="in_{{ $kp->id }}" {{ request('kategori_id') == 'in_'.$kp->id ? 'selected' : '' }}>{{ $kp->nama }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Pengeluaran">
                                @foreach($kategori_pengeluaran_list as $kp)
                                    <option value="out_{{ $kp->id }}" {{ request('kategori_id') == 'out_'.$kp->id ? 'selected' : '' }}>{{ $kp->nama }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                    <div class="flex-1 flex flex-col">
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Cari</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari keterangan..." class="w-full h-[42px] bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    </div>
                    <div class="w-full sm:w-auto shrink-0 flex flex-col">
                        <label class="block text-xs font-black uppercase tracking-widest text-transparent mb-2 hidden sm:block">&nbsp;</label>
                        <button type="submit" class="w-full sm:w-auto flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs px-8 rounded-xl transition-all shadow-lg shadow-indigo-500/20 uppercase tracking-widest h-[42px]">
                            Terapkan Filter
                        </button>
                    </div>
                </div>
                @endif
            </form>
        </div>

            
    <div id="table-rincian" class="bg-white dark:bg-zinc-900 rounded-[2rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm mt-6">
                <h2 class="text-lg font-black text-slate-900 dark:text-white mb-6">Rincian Transaksi Keuangan</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border-collapse border border-slate-200 dark:border-zinc-800">
                        <thead class="bg-indigo-600 dark:bg-indigo-900/80 text-[10px] uppercase tracking-widest text-white font-black">
                            <tr>
                                <th class="px-4 py-3 text-left border border-white/20">Tanggal</th>
                                <th class="px-4 py-3 text-center border border-white/20">Tipe</th>
                                <th class="px-4 py-3 text-left border border-white/20">Kategori</th>
                                <th class="px-4 py-3 text-left border border-white/20">Jenis</th>
                                <th class="px-4 py-3 text-left border border-white/20">Keterangan</th>
                                <th class="px-4 py-3 text-right border border-white/20">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-900">
                            @forelse($list_keuangan as $trx)
                                <tr class="hover:bg-indigo-50/50 dark:hover:bg-indigo-900/10 transition-all even:bg-slate-50/50 dark:even:bg-zinc-800/30">
                                    <td class="px-4 py-3 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-zinc-800 whitespace-nowrap">{{ \Carbon\Carbon::parse($trx['tanggal'])->translatedFormat('d M Y') }}</td>
                                    <td class="px-4 py-3 text-center border border-slate-200 dark:border-zinc-800">
                                        @if($trx['tipe'] === 'pemasukan')
                                            <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Pemasukan</span>
                                        @else
                                            <span class="bg-rose-100 text-rose-700 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Pengeluaran</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-xs border border-slate-200 dark:border-zinc-800 font-bold text-slate-700 dark:text-slate-300">
                                        {{ $trx['kategori'] }}
                                    </td>
                                    <td class="px-4 py-3 text-xs border border-slate-200 dark:border-zinc-800 font-bold text-slate-700 dark:text-slate-300">
                                        {{ $trx['jenis'] }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-zinc-800">{{ $trx['keterangan'] }}</td>
                                    <td class="px-4 py-3 text-right font-black text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-zinc-800 whitespace-nowrap">Rp {{ number_format($trx['nominal'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400 italic border border-slate-200 dark:border-zinc-800">Belum ada transaksi keuangan pada rentang tanggal ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $list_keuangan->fragment('table-rincian')->links() }}
                </div>
            </div>
