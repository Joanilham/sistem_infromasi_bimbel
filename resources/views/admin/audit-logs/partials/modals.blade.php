<!-- DETAIL POPUP MODAL -->
    <template x-teleport="body">
        <div x-show="showModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
         
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm z-40" @click="showModal = false"></div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div class="relative z-50 inline-block align-bottom bg-white dark:bg-zinc-900 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-slate-100 dark:border-zinc-800">
                 
                <!-- Modal Header -->
                <div class="bg-slate-50 dark:bg-zinc-800/40 px-6 py-5 border-b border-slate-100 dark:border-zinc-800 flex justify-between items-center">
                    <div>
                        <span class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">Detail Jejak Audit</span>
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white mt-0.5" x-text="selectedLog ? selectedLog.formatted_model_name + (selectedLog.auditable_id ? ' (ID: ' + selectedLog.auditable_id + (selectedLog.record_title ? ' - ' + selectedLog.record_title : '') + ')' : '') : ''"></h3>
                    </div>
                    <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors cursor-pointer">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="p-6 space-y-6">
                    <!-- Metadata Info Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4 bg-slate-50 dark:bg-zinc-850 rounded-2xl border border-slate-100 dark:border-zinc-800 text-xs">
                        <div>
                            <span class="text-slate-400 block mb-0.5">Pelaku Utama</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200" x-text="selectedLog?.user?.name || 'System/Guest'"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-0.5">Alamat IP</span>
                            <span class="font-mono font-bold text-slate-800 dark:text-slate-200" x-text="selectedLog?.ip_address"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-0.5">Sistem Pengoperasi / Browser</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200" x-text="selectedLog?.formatted_user_agent"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-0.5">Waktu Eksekusi</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200" x-text="selectedLog ? new Date(selectedLog.created_at).toLocaleString('id-ID') : ''"></span>
                        </div>
                    </div>

                    <!-- URL Web Info -->
                    <div class="text-xs">
                        <span class="text-slate-400 block mb-1">Halaman URL Yang Diakses:</span>
                        <span class="font-mono bg-slate-100 dark:bg-zinc-850 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-zinc-700 block text-slate-700 dark:text-slate-300 overflow-x-auto" x-text="selectedLog?.url"></span>
                    </div>

                    <!-- COMPARATIVE VALUE VIEWER (Github-Style side-by-side diff) -->
                    <div>
                        <span class="text-xs font-semibold text-slate-400 block mb-2.5">Rekaman Perubahan Data:</span>
                        
                        <div class="space-y-4">
                            <!-- IF EVENT IS UPDATE (Unified Diff Table) -->
                            <template x-if="selectedLog?.event === 'updated' && ((selectedLog?.old_values && Object.keys(selectedLog.old_values).length > 0) || (selectedLog?.new_values && Object.keys(selectedLog.new_values).length > 0))">
                                <div class="bg-white dark:bg-zinc-800 rounded-2xl border border-slate-200 dark:border-zinc-700 shadow-sm overflow-hidden">
                                    <div class="overflow-x-auto custom-scrollbar">
                                        <table class="w-full text-left text-xs border-collapse">
                                            <thead>
                                                <tr>
                                                    <th class="p-4 bg-slate-50 dark:bg-zinc-800/80 border-b border-r border-slate-200 dark:border-zinc-700 font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest w-1/4">
                                                        Modul / Kolom
                                                    </th>
                                                    <th class="p-4 bg-rose-50/50 dark:bg-rose-950/20 border-b border-r border-slate-200 dark:border-zinc-700 font-black text-rose-600 dark:text-rose-400 uppercase tracking-widest w-[37.5%]">
                                                        Data Sebelumnya <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded text-[9px] bg-rose-100 dark:bg-rose-900/50 text-rose-700 dark:text-rose-300">- Hapus</span>
                                                    </th>
                                                    <th class="p-4 bg-emerald-50/50 dark:bg-emerald-950/20 border-b border-slate-200 dark:border-zinc-700 font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest w-[37.5%]">
                                                        Data Terbaru <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded text-[9px] bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300">+ Tambah</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100 dark:divide-zinc-700/50">
                                                <!-- Loop keys that changed -->
                                                <template x-for="key in Array.from(new Set([...Object.keys(selectedLog?.old_values || {}), ...Object.keys(selectedLog?.new_values || {})]))" :key="key">
                                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-700/20 transition-colors group">
                                                        <!-- Field Name -->
                                                        <td class="p-4 border-r border-slate-100 dark:border-zinc-700/50 bg-slate-50/30 dark:bg-zinc-800/40">
                                                            <div class="flex items-center gap-2">
                                                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                                <span class="font-bold text-slate-700 dark:text-slate-300 tracking-wider uppercase text-[10px]" x-text="formatKey(key)"></span>                                                            </div>
                                                        </td>
                                                        <!-- Old Value -->
                                                        <td class="p-4 border-r border-slate-100 dark:border-zinc-700/50 bg-rose-50/10 dark:bg-rose-950/10 relative">
                                                            <div class="font-medium text-rose-600 dark:text-rose-400 break-all line-through decoration-rose-200 dark:decoration-rose-900/50" 
                                                                 x-text="selectedLog?.old_values?.[key] !== undefined ? formatValue(selectedLog.old_values[key], key) : '—'">
                                                            </div>
                                                        </td>
                                                        <!-- New Value -->
                                                        <td class="p-4 bg-emerald-50/10 dark:bg-emerald-950/10 relative">
                                                            <div class="absolute inset-y-0 left-0 w-0.5 bg-emerald-400/50 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                                            <div class="font-bold text-emerald-600 dark:text-emerald-400 break-all"
                                                                 x-text="selectedLog?.new_values?.[key] !== undefined ? formatValue(selectedLog.new_values[key], key) : '—'">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </template>

                            <!-- IF EVENT IS CREATED -->
                            <template x-if="selectedLog?.event === 'created' && (selectedLog?.new_values && Object.keys(selectedLog.new_values).length > 0)">
                                <div class="bg-white dark:bg-zinc-800 rounded-2xl border border-emerald-200 dark:border-emerald-900/50 shadow-sm overflow-hidden">
                                    <div class="overflow-x-auto custom-scrollbar">
                                        <table class="w-full text-left text-xs border-collapse">
                                            <thead>
                                                <tr>
                                                    <th class="p-4 bg-emerald-50 dark:bg-emerald-950/30 border-b border-r border-emerald-100 dark:border-emerald-900/50 font-black text-emerald-700 dark:text-emerald-400 uppercase tracking-widest w-1/3">Modul / Kolom</th>
                                                    <th class="p-4 bg-emerald-50 dark:bg-emerald-950/30 border-b border-emerald-100 dark:border-emerald-900/50 font-black text-emerald-700 dark:text-emerald-400 uppercase tracking-widest w-2/3">Data Baru Ditambahkan</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-emerald-50 dark:divide-emerald-900/20">
                                                <template x-for="(value, key) in selectedLog?.new_values" :key="key">
                                                    <tr class="hover:bg-emerald-50/30 dark:hover:bg-emerald-900/10 transition-colors">
                                                        <td class="p-4 border-r border-emerald-50 dark:border-emerald-900/20 bg-emerald-50/10 dark:bg-emerald-950/10">
                                                            <span class="font-bold text-slate-700 dark:text-slate-300 tracking-wider uppercase text-[10px]" x-text="formatKey(key)"></span>
                                                        </td>
                                                        <td class="p-4 font-medium text-emerald-600 dark:text-emerald-400 break-all"
                                                            x-text="formatValue(value, key)">
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </template>

                            <!-- IF EVENT IS DELETED -->
                            <template x-if="selectedLog?.event === 'deleted' && (selectedLog?.old_values && Object.keys(selectedLog.old_values).length > 0)">
                                <div class="bg-white dark:bg-zinc-800 rounded-2xl border border-rose-200 dark:border-rose-900/50 shadow-sm overflow-hidden">
                                    <div class="overflow-x-auto custom-scrollbar">
                                        <table class="w-full text-left text-xs border-collapse">
                                            <thead>
                                                <tr>
                                                    <th class="p-4 bg-rose-50 dark:bg-rose-950/30 border-b border-r border-rose-100 dark:border-rose-900/50 font-black text-rose-700 dark:text-rose-400 uppercase tracking-widest w-1/3">Modul / Kolom</th>
                                                    <th class="p-4 bg-rose-50 dark:bg-rose-950/30 border-b border-rose-100 dark:border-rose-900/50 font-black text-rose-700 dark:text-rose-400 uppercase tracking-widest w-2/3">Data Dihapus</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-rose-50 dark:divide-rose-900/20">
                                                <template x-for="(value, key) in selectedLog?.old_values" :key="key">
                                                    <tr class="hover:bg-rose-50/30 dark:hover:bg-rose-900/10 transition-colors">
                                                        <td class="p-4 border-r border-rose-50 dark:border-rose-900/20 bg-rose-50/10 dark:bg-rose-950/10">
                                                            <span class="font-bold text-slate-700 dark:text-slate-300 tracking-wider uppercase text-[10px]" x-text="formatKey(key)"></span>
                                                        </td>
                                                        <td class="p-4 font-medium text-rose-600 dark:text-rose-400 break-all line-through decoration-rose-200"
                                                            x-text="formatValue(value, key)">
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </template> 
                        </div>

                            <!-- FALLBACK IF CHANGES ARE EMPTY (Authentication events or filtered sensitive attributes) -->
                            <template x-if="(!selectedLog?.old_values || Object.keys(selectedLog.old_values).length === 0) && (!selectedLog?.new_values || Object.keys(selectedLog.new_values).length === 0)">
                                <div class="p-8 bg-slate-50/50 dark:bg-zinc-850 rounded-[2rem] border border-dashed border-slate-200 dark:border-zinc-800 text-center space-y-4">
                                    <div class="w-14 h-14 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 rounded-2xl flex items-center justify-center mx-auto text-2xl shadow-inner">
                                        🔒
                                    </div>
                                    <div class="space-y-1">
                                        <h4 class="font-black text-slate-800 dark:text-white text-sm">Aktivitas Non-Substantif / Data Terlindungi</h4>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 max-w-lg mx-auto leading-relaxed">
                                            Operasi ini tidak mengubah nilai data substantif, atau seluruh perubahan data bersifat sangat sensitif (seperti token akses, kata sandi, atau kunci keamanan sesi) yang disaring secara otomatis demi kepatuhan regulasi perlindungan data.
                                        </p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-slate-50 dark:bg-zinc-800/40 px-6 py-4 border-t border-slate-100 dark:border-zinc-800 flex justify-end">
                    <button type="button" 
                            @click="showModal = false"
                            class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 dark:bg-zinc-700 dark:hover:bg-zinc-600 text-white text-xs font-bold rounded-xl shadow transition-colors cursor-pointer">
                        Selesai Membaca
                    </button>
                </div>
            </div>
        </div>
    </template>

    <!-- PRUNE MODAL -->
    <template x-teleport="body">
        <div x-show="showPruneModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
         
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm z-40" @click="showPruneModal = false"></div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div class="relative z-50 inline-block align-bottom bg-white dark:bg-zinc-900 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-slate-100 dark:border-zinc-800">
                
                <form action="{{ route('admin.audit-logs.prune') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    
                    <div class="p-6">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 mb-5">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-center text-slate-800 dark:text-white mb-2">Autentikasi Diperlukan</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 text-center mb-6 leading-relaxed">
                            Peringatan! Aksi ini akan menghapus log aktivitas yang umurnya lebih dari 30 hari secara <b class="text-red-500 dark:text-red-400">permanen</b>. Masukkan kata sandi Anda untuk memverifikasi hak akses.
                        </p>
                        
                        <div>
                            <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Kata Sandi (Password)</label>
                            <input type="password" name="password" id="password" required autocomplete="current-password"
                                   class="block w-full px-4 py-3 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800/50 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-500 text-sm transition-all dark:text-white placeholder-slate-400 dark:placeholder-slate-500"
                                   placeholder="Masukkan kata sandi akun Anda">
                        </div>
                    </div>
                    
                    <div class="bg-slate-50 dark:bg-zinc-800/40 px-6 py-4 border-t border-slate-100 dark:border-zinc-800 flex justify-end gap-3">
                        <button type="button" @click="showPruneModal = false"
                                class="px-5 py-2.5 bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-700 text-slate-700 dark:text-slate-300 text-sm font-semibold rounded-xl transition-colors cursor-pointer shadow-sm">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl shadow-sm transition-colors cursor-pointer flex items-center justify-center min-w-[140px]">
                            Verifikasi & Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
