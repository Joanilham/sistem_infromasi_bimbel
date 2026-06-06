    {{-- --- Modal Detail Peserta --- --}}
    <div x-show="showListModal" 
         class="fixed inset-0 z-[100] overflow-y-auto" 
         x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <div x-show="showListModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" 
                 @click="showListModal = false"></div>

            <div x-show="showListModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-800">
                
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/50">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white" x-text="modalTitle"></h3>
                    <button @click="showListModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="max-h-[60vh] overflow-y-auto p-6 custom-scrollbar">
                    {{-- List Peserta Baru --}}
                    <div x-show="modalType === 'baru'">
                        @if(($listPesertaBaru ?? collect())->count() > 0)
                            <div class="space-y-4">
                                @foreach(($listPesertaBaru ?? collect()) as $p)
                                    <div class="flex items-center gap-4 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700/50 hover:border-emerald-200 dark:hover:border-emerald-800 transition-colors">
                                        <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold">
                                            {{ substr($p->nama_lengkap, 0, 1) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-slate-800 dark:text-slate-200 truncate">{{ $p->nama_lengkap }}</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $p->asal_sekolah }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $p->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-slate-500 dark:text-slate-400">Tidak ada peserta baru</p>
                            </div>
                        @endif
                    </div>

                    {{-- List Peserta Keluar --}}
                    <div x-show="modalType === 'keluar'">
                        @if(($listPesertaKeluar ?? collect())->count() > 0)
                            <div class="space-y-4">
                                @foreach(($listPesertaKeluar ?? collect()) as $p)
                                    <div class="flex items-center gap-4 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700/50 hover:border-rose-200 dark:hover:border-rose-800 transition-colors">
                                        <div class="w-10 h-10 rounded-full bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 flex items-center justify-center font-bold">
                                            {{ substr($p->nama_lengkap, 0, 1) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-slate-800 dark:text-slate-200 truncate">{{ $p->nama_lengkap }}</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $p->asal_sekolah }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[10px] font-bold text-rose-500 uppercase tracking-tighter">{{ $p->tanggal_keluar }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-slate-500 dark:text-slate-400">Tidak ada peserta keluar</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800 text-right">
                    <button @click="showListModal = false" class="px-5 py-2 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors shadow-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

