    {{-- Card Export Kustom --}}
    @if($tab === 'siswa' || $tab === 'keuangan' || $tab === 'absensi' || $tab === 'guru')
    <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 border border-slate-100 dark:border-zinc-800 shadow-sm mt-6 mb-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <h2 class="text-base font-black text-slate-900 dark:text-white">Export Data {{ ucfirst($tab) }}</h2>
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-0.5">Unduh rekapitulasi ke dalam format Excel.</p>
            </div>
        </div>
        
        <form action="{{ $tab === 'guru' ? route('admin.rekapitulasi.export', request()->except('tab') + ['tab' => 'guru']) : route('admin.rekapitulasi.export-kustom') }}" method="{{ $tab === 'guru' ? 'GET' : 'POST' }}" x-data="{ rentang: 'semua_waktu' }" class="bg-slate-50 dark:bg-zinc-950 rounded-2xl p-4 border border-slate-100 dark:border-zinc-800">
            @if($tab !== 'guru')
            @csrf
            
            <div class="flex flex-col sm:flex-row items-end gap-4">
                @if($tab === 'keuangan')
                <div class="flex-1 w-full">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Jenis Laporan</label>
                    <select name="jenis_laporan" class="no-tomselect w-full h-[42px] bg-white dark:bg-zinc-900 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold px-4 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all cursor-pointer hover:border-emerald-300">
                        <option value="pembayaran">Pembayaran Siswa (SPP/Bimbingan)</option>
                        <option value="keuangan_operasional">Operasional (Di Luar Siswa)</option>
                        <option value="keuangan_semua">Semua Transaksi Keuangan</option>
                    </select>
                </div>
                @else
                <input type="hidden" name="jenis_laporan" value="{{ $tab === 'siswa' ? 'peserta_didik' : 'absensi' }}">
                @endif
                
                <div class="flex-1 w-full">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Rentang Waktu</label>
                    <select name="rentang" x-model="rentang" class="no-tomselect w-full h-[42px] bg-white dark:bg-zinc-900 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold px-4 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all cursor-pointer hover:border-emerald-300">
                        <option value="semua_waktu">Semua Waktu</option>
                        <option value="hari_ini">Hari Ini</option>
                        <option value="minggu_ini">Minggu Ini</option>
                        <option value="bulan_ini">Bulan Ini</option>
                        <option value="kustom">Pilih Tanggal Kustom...</option>
                    </select>
                </div>
                <button type="submit" class="w-full sm:w-auto shrink-0 h-[42px] px-8 inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-emerald-500/20 group">
                    <span class="group-hover:-translate-y-0.5 transition-transform">Download Excel</span>
                    <svg class="w-4 h-4 group-hover:translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </button>
            </div>
            
            <div x-show="rentang === 'kustom'" x-transition class="mt-4 pt-4 border-t border-slate-200 dark:border-zinc-800 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Dari Tanggal</label>
                    <input type="date" name="tanggal_awal" :required="rentang === 'kustom'" class="w-full h-[42px] bg-white dark:bg-zinc-900 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold px-4 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all cursor-pointer">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Sampai Tanggal</label>
                    <input type="date" name="tanggal_akhir" :required="rentang === 'kustom'" class="w-full h-[42px] bg-white dark:bg-zinc-900 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold px-4 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all cursor-pointer">
                </div>
            </div>
            @else
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm font-bold text-slate-500 dark:text-slate-400 flex items-center">
                    Klik tombol di samping untuk mengekspor data seluruh guru (berdasarkan filter aktif pada tabel).
                </div>
                <button type="submit" class="w-full sm:w-auto shrink-0 h-[42px] px-8 inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-emerald-500/20 group">
                    <span class="group-hover:-translate-y-0.5 transition-transform">Download Excel</span>
                    <svg class="w-4 h-4 group-hover:translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </button>
            </div>
            @endif
        </form>
    </div>
    @endif
