@extends('layouts.admin')

@section('title', 'Backup & Restore Database')

@section('content')
<div class="p-6 sm:p-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white uppercase tracking-wider">Backup & Pemulihan Data</h1>
            <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1">Kelola pencadangan basis data secara manual maupun otomatis dengan rotasi retensi penyimpanan.</p>
        </div>
    </div>

    {{-- Info Card --}}
    <div class="mb-8 p-6 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 rounded-[2rem] relative overflow-hidden">
        <div class="absolute -top-6 -right-6 w-24 h-24 bg-amber-500/10 rounded-full blur-2xl"></div>
        <div class="flex items-start gap-3 relative">
            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <div>
                <p class="text-sm font-bold text-amber-900 dark:text-amber-200 mb-1.5">Panduan Perawatan & Keamanan Backup</p>
                <ul class="text-xs text-amber-700 dark:text-amber-300 space-y-1.5 list-disc list-inside">
                    <li>Backup menyimpan <strong>seluruh data database</strong> secara privat termasuk seluruh struktur tabel, kueri ujian, serta mutasi keuangan siswa.</li>
                    <li><strong>Unduh Rutin ke Komputer Lokal</strong>: Sangat disarankan untuk mengunduh file `.sql` cadangan secara rutin ke komputer lokal Anda untuk mengantisipasi kejadian server rusak atau mati total.</li>
                    <li><strong>Lakukan Rotasi File</strong>: Simpan file backup penting di tempat penyimpanan eksternal yang aman dan terenkripsi. Hindari membiarkan terlalu banyak file menumpuk agar ruang simpan tetap bersih.</li>
                    <li><strong>Simpan Secara Privat</strong>: Jangan menyebarkan file `.sql` hasil unduhan kepada pihak yang tidak berkepentingan karena mengandung data rahasia seluruh siswa dan institusi.</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Pengaturan Backup Otomatis (Multi-select dengan deskripsi dinamis Alpine & Tombol Statis) --}}
    <div x-data="{ activeTab: 'daily' }" class="mb-8 p-6 bg-white dark:bg-zinc-900 border border-slate-100 dark:border-zinc-800 rounded-[2rem] shadow-sm">
        <h3 class="text-base font-black text-slate-900 dark:text-white mb-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Jadwal Backup Otomatis
        </h3>
        <p class="text-xs text-slate-400 dark:text-zinc-500 mb-6">Aktifkan beberapa pilihan jadwal di bawah untuk membentuk sistem perlindungan database berlapis.</p>
        
        <form action="{{ route('admin.backup.toggle') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
                <!-- KIRI: Pilihan Frekuensi & Input Waktu & Tombol Simpan -->
                <div class="lg:col-span-5 flex flex-col gap-4">
                    <div class="space-y-3">
                        <p class="text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-2">Aktifkan Jadwal Backup:</p>
                        
                        <!-- Harian -->
                        <div @click="activeTab = 'daily'" :class="activeTab === 'daily' ? 'border-indigo-500 bg-indigo-50/10 dark:bg-indigo-950/10' : 'border-slate-100 dark:border-zinc-800/80 bg-slate-50/50 dark:bg-zinc-950/50'" class="flex items-center justify-between p-3.5 border rounded-2xl cursor-pointer hover:border-indigo-500/40 transition-all">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="backup_frequencies[]" value="daily" {{ in_array('daily', $backupFrequencies) ? 'checked' : '' }} class="rounded text-indigo-600 focus:ring-indigo-500 w-4 h-4" @click.stop>
                                <div>
                                    <span class="text-xs font-black text-slate-900 dark:text-white block">Harian (Daily Backup)</span>
                                    <span class="text-[10px] text-slate-400 block mt-0.5">Jadwal: Setiap Hari Pukul 01:00 AM</span>
                                </div>
                            </div>
                            <span class="text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">Info</span>
                        </div>

                        <!-- Mingguan -->
                        <div @click="activeTab = 'weekly'" :class="activeTab === 'weekly' ? 'border-indigo-500 bg-indigo-50/10 dark:bg-indigo-950/10' : 'border-slate-100 dark:border-zinc-800/80 bg-slate-50/50 dark:bg-zinc-950/50'" class="flex items-center justify-between p-3.5 border rounded-2xl cursor-pointer hover:border-indigo-500/40 transition-all">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="backup_frequencies[]" value="weekly" {{ in_array('weekly', $backupFrequencies) ? 'checked' : '' }} class="rounded text-indigo-600 focus:ring-indigo-500 w-4 h-4" @click.stop>
                                <div>
                                    <span class="text-xs font-black text-slate-900 dark:text-white block">Mingguan (Weekly Backup)</span>
                                    <span class="text-[10px] text-slate-400 block mt-0.5">Jadwal: Hari Minggu Pukul 01:05 AM</span>
                                </div>
                            </div>
                            <span class="text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">Info</span>
                        </div>

                        <!-- Bulanan -->
                        <div @click="activeTab = 'monthly'" :class="activeTab === 'monthly' ? 'border-indigo-500 bg-indigo-50/10 dark:bg-indigo-950/10' : 'border-slate-100 dark:border-zinc-800/80 bg-slate-50/50 dark:bg-zinc-950/50'" class="flex items-center justify-between p-3.5 border rounded-2xl cursor-pointer hover:border-indigo-500/40 transition-all">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="backup_frequencies[]" value="monthly" {{ in_array('monthly', $backupFrequencies) ? 'checked' : '' }} class="rounded text-indigo-600 focus:ring-indigo-500 w-4 h-4" @click.stop>
                                <div>
                                    <span class="text-xs font-black text-slate-900 dark:text-white block">Bulanan (Monthly Backup)</span>
                                    <span class="text-[10px] text-slate-400 block mt-0.5">Jadwal: Tanggal 1 Pukul 01:10 AM</span>
                                </div>
                            </div>
                            <span class="text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">Info</span>
                        </div>
                    </div>

                    <!-- Input Jam & Tombol Simpan -->
                    <div class="pt-4 border-t border-slate-100 dark:border-zinc-800 space-y-3.5">
                        <div class="flex items-center justify-between gap-3">
                            <label for="backup_time" class="text-xs font-black text-slate-700 dark:text-zinc-400 shrink-0">Waktu Eksekusi:</label>
                            <div class="flex items-center gap-2">
                                <input type="time" id="backup_time" name="backup_time" value="{{ $backupTime }}" class="bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 rounded-xl text-xs font-semibold py-1.5 px-2.5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-slate-200">
                                <span class="text-[9px] font-medium text-slate-400 dark:text-zinc-500 shrink-0">*Stagger +5m/+10m</span>
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 dark:bg-indigo-600 dark:hover:bg-indigo-700 text-white font-black text-xs uppercase tracking-wider py-3.5 rounded-xl transition-all active:scale-95 shadow-md shrink-0">
                            Simpan Konfigurasi
                        </button>
                    </div>
                </div>

                <!-- KANAN: Panel Penjelasan Dinamis (Tinggi Stabil Tetap 310px - Bebas Layout Shift!) -->
                <div class="lg:col-span-7 bg-slate-50 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-800 p-5 sm:p-6 rounded-[1.75rem] h-[310px] overflow-y-auto flex flex-col">
                    <p class="text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-3">Detail Konsekuensi & Retensi:</p>
                    
                    <!-- Info Harian -->
                    <div x-show="activeTab === 'daily'" class="space-y-2">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-[10px] font-black uppercase tracking-wider">
                            Sangat Aman (Direkomendasikan)
                        </div>
                        <p class="text-xs text-slate-600 dark:text-zinc-400 leading-relaxed mt-2">
                            <strong>Manfaat Harian:</strong> Keamanan data maksimal! Kehilangan data maksimal hanya 24 jam jika terjadi server crash.
                        </p>
                        <p class="text-xs text-slate-600 dark:text-zinc-400 leading-relaxed">
                            🔄 <strong>Kebijakan Rotasi:</strong> Server akan secara mandiri menyimpan <strong>7 file harian terakhir</strong> saja. Backup lama di atas 7 hari akan otomatis dihapus untuk menghemat ruang disk.
                        </p>
                    </div>

                    <!-- Info Mingguan -->
                    <div x-show="activeTab === 'weekly'" class="space-y-2">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-[10px] font-black uppercase tracking-wider">
                            Perlindungan Menengah
                        </div>
                        <p class="text-xs text-slate-600 dark:text-zinc-400 leading-relaxed mt-2">
                            <strong>Manfaat Mingguan:</strong> Menyediakan histori pemulihan data mingguan terpisah untuk menganalisis rekapan mingguan.
                        </p>
                        <p class="text-xs text-slate-600 dark:text-zinc-400 leading-relaxed">
                            🔄 <strong>Kebijakan Rotasi:</strong> Server akan secara mandiri menyimpan <strong>4 file mingguan terakhir</strong> (mencakup data 1 bulan ke belakang).
                        </p>
                    </div>

                    <!-- Info Bulanan -->
                    <div x-show="activeTab === 'monthly'" class="space-y-2">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 text-[10px] font-black uppercase tracking-wider">
                            Arsip Jangka Panjang
                        </div>
                        <p class="text-xs text-slate-600 dark:text-zinc-400 leading-relaxed mt-2">
                            <strong>Manfaat Bulanan:</strong> Sangat bagus untuk arsip permanen bulanan guna mencadangkan rekapan keuangan bulanan dan periode belajar.
                        </p>
                        <p class="text-xs text-slate-600 dark:text-zinc-400 leading-relaxed">
                            🔄 <strong>Kebijakan Rotasi:</strong> Server akan secara mandiri menyimpan <strong>3 file bulanan terakhir</strong> (mencakup data 3 bulan ke belakang).
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Upload Backup Card --}}
    <div class="mb-8 p-6 bg-white dark:bg-zinc-900 border border-slate-100 dark:border-zinc-800 rounded-[2rem] shadow-sm">
        <h3 class="text-base font-black text-slate-900 dark:text-white mb-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
            </svg>
            Upload File Backup Baru (.sql)
        </h3>
        <p class="text-xs text-slate-400 dark:text-zinc-500 mb-4">Unggah file backup `.sql` dari komputer lokal Anda untuk ditambahkan ke daftar atau siap di-restore.</p>
        
        <form action="{{ route('admin.backup.upload') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-center gap-4">
            @csrf
            <div class="relative w-full">
                <input type="file" name="backup_file" accept=".sql" required
                    class="w-full bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 rounded-2xl text-xs font-semibold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-slate-200 file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-indigo-50 dark:file:bg-indigo-900/30 file:text-indigo-600 dark:file:text-indigo-400 hover:file:bg-indigo-100">
            </div>
            <button type="submit" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs px-8 py-3.5 rounded-2xl transition-all active:scale-95 shadow-md hover:shadow-lg hover:shadow-indigo-500/10 uppercase tracking-widest shrink-0">
                Upload
            </button>
        </form>
    </div>

    {{-- Backup List --}}
    <div x-data="backupManager()" class="bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-slate-100 dark:border-zinc-800">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500 flex items-center justify-center shadow-md shadow-indigo-500/30">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-slate-900 dark:text-white">Daftar Backup</h2>
                        <p class="text-xs text-slate-400">Total {{ count($allBackups) }} file backup tersedia</p>
                    </div>
                </div>

                <!-- Pindah tombol Buat Backup Sekarang (Manual) ke sini! -->
                <form action="{{ route('admin.backup.create') }}" method="POST" class="shrink-0">
                    @csrf
                    <button type="submit" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs uppercase tracking-widest px-5 py-3 rounded-xl transition-all active:scale-95 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        Buat Backup Sekarang (Manual)
                    </button>
                </form>
            </div>
        </div>

        @if(count($backups) === 0)
        <div class="p-12 text-center">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-zinc-800 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-slate-300 dark:text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" /></svg>
            </div>
            <p class="text-sm font-bold text-slate-400 dark:text-zinc-500">Belum ada backup</p>
            <p class="text-xs text-slate-300 dark:text-zinc-600 mt-1">Klik "Buat Backup Sekarang" untuk membuat backup pertama.</p>
        </div>
        @else
        <!-- Filter Tabs / Pills -->
        <div class="px-6 sm:px-8 py-3.5 bg-slate-50/50 dark:bg-zinc-950/20 border-b border-slate-100 dark:border-zinc-800/80 flex flex-wrap gap-2 items-center justify-between">
            <div class="flex flex-wrap gap-2">
                <button @click="activeFilter = 'all'; setTimeout(() => toggleSelectAll(false), 50)" :class="activeFilter === 'all' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-600 dark:text-zinc-400 hover:bg-slate-50 dark:hover:bg-zinc-800'" class="text-[10px] font-black uppercase tracking-wider px-4 py-2 rounded-xl transition-all shadow-sm">
                    Semua ({{ count($allBackups) }})
                </button>
                <button @click="activeFilter = 'daily'; setTimeout(() => toggleSelectAll(false), 50)" :class="activeFilter === 'daily' ? 'bg-emerald-600 text-white shadow-emerald-500/20' : 'bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-600 dark:text-zinc-400 hover:bg-slate-50 dark:hover:bg-zinc-800'" class="text-[10px] font-black uppercase tracking-wider px-4 py-2 rounded-xl transition-all shadow-sm">
                    Harian ({{ count(array_filter($allBackups, fn($b) => $b->type === 'daily')) }})
                </button>
                <button @click="activeFilter = 'weekly'; setTimeout(() => toggleSelectAll(false), 50)" :class="activeFilter === 'weekly' ? 'bg-amber-600 text-white shadow-amber-500/20' : 'bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-600 dark:text-zinc-400 hover:bg-slate-50 dark:hover:bg-zinc-800'" class="text-[10px] font-black uppercase tracking-wider px-4 py-2 rounded-xl transition-all shadow-sm">
                    Mingguan ({{ count(array_filter($allBackups, fn($b) => $b->type === 'weekly')) }})
                </button>
                <button @click="activeFilter = 'monthly'; setTimeout(() => toggleSelectAll(false), 50)" :class="activeFilter === 'monthly' ? 'bg-purple-600 text-white shadow-purple-500/20' : 'bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-600 dark:text-zinc-400 hover:bg-slate-50 dark:hover:bg-zinc-800'" class="text-[10px] font-black uppercase tracking-wider px-4 py-2 rounded-xl transition-all shadow-sm">
                    Bulanan ({{ count(array_filter($allBackups, fn($b) => $b->type === 'monthly')) }})
                </button>
                <button @click="activeFilter = 'manual'; setTimeout(() => toggleSelectAll(false), 50)" :class="activeFilter === 'manual' ? 'bg-slate-600 text-white shadow-slate-500/20' : 'bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-600 dark:text-zinc-400 hover:bg-slate-50 dark:hover:bg-zinc-800'" class="text-[10px] font-black uppercase tracking-wider px-4 py-2 rounded-xl transition-all shadow-sm">
                    Manual ({{ count(array_filter($allBackups, fn($b) => $b->type === 'manual')) }})
                </button>
            </div>
            <div x-show="selectedFiles.length > 0" x-transition x-cloak>
                <button @click="bulkDelete()" class="text-[10px] font-black uppercase tracking-wider px-4 py-2 rounded-xl transition-all shadow-sm bg-red-600 hover:bg-red-700 text-white flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    Hapus Terpilih (<span x-text="selectedFiles.length"></span>)
                </button>
            </div>
        </div>

        <div class="divide-y divide-slate-50 dark:divide-zinc-800">
            <div class="px-5 py-3 sm:px-6 bg-slate-50 dark:bg-zinc-900 border-b border-slate-100 dark:border-zinc-800 flex items-center gap-4">
                <input type="checkbox" x-model="selectAll" @change="toggleSelectAll()" class="rounded text-indigo-600 focus:ring-indigo-500 w-4 h-4 cursor-pointer">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pilih Semua di Halaman Ini</span>
            </div>

            @foreach($backups as $backup)
            <div x-show="activeFilter === 'all' || activeFilter === '{{ $backup->type }}'" class="backup-row p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/50 dark:hover:bg-zinc-800/50 transition-colors group">
                <div class="flex items-center gap-4 min-w-0">
                    <input type="checkbox" value="{{ str_replace('.sql', '', $backup->filename) }}" x-model="selectedFiles" class="file-checkbox w-4 h-4 text-indigo-600 border-slate-300 dark:border-zinc-700 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:bg-zinc-800 transition-colors">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-zinc-800 flex items-center justify-center shrink-0 group-hover:bg-indigo-50 dark:group-hover:bg-indigo-900/30 transition-colors">
                        <svg class="w-5 h-5 text-slate-400 dark:text-zinc-500 group-hover:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ $backup->filename }}</p>
                        <div class="flex items-center gap-3 mt-1 flex-wrap">
                            {{-- Klasifikasi Badge Tipe Backup --}}
                            @if($backup->type === 'daily')
                                <span class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400">Harian</span>
                            @elseif($backup->type === 'weekly')
                                <span class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400">Mingguan</span>
                            @elseif($backup->type === 'monthly')
                                <span class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-lg bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400">Bulanan</span>
                            @else
                                <span class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-lg bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400">Manual</span>
                            @endif

                            <span class="text-xs text-slate-400 dark:text-zinc-500 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ $backup->created_at }}
                            </span>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">{{ $backup->size }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('admin.backup.download', str_replace('.sql', '', $backup->filename)) }}" download
                        class="inline-flex items-center gap-1.5 bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 text-blue-700 dark:text-blue-400 text-xs font-bold px-4 py-2 rounded-xl transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                        Download
                    </a>
                    
                    <form action="{{ route('admin.backup.restore', str_replace('.sql', '', $backup->filename)) }}" method="POST" class="inline" id="form-restore-{{ $loop->index }}">
                        @csrf
                        <button type="button" onclick="Swal.fire({title: 'Restore Database?', text: 'PERINGATAN KRITIS: Mengembalikan database akan menimpa seluruh data saat ini secara permanen! Pastikan Anda telah mengunduh backup terkini.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d97706', cancelButtonColor: '#64748b', confirmButtonText: 'Ya, Restore!', cancelButtonText: 'Batal', background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff', color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a', customClass: {popup: 'rounded-2xl border border-slate-100 dark:border-slate-700'}}).then((result) => { if (result.isConfirmed) document.getElementById('form-restore-{{ $loop->index }}').submit(); })"
                            class="inline-flex items-center gap-1.5 bg-amber-50 dark:bg-amber-900/30 hover:bg-amber-100 dark:hover:bg-amber-900/50 text-amber-700 dark:text-amber-400 text-xs font-bold px-4 py-2 rounded-xl transition-all">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" /></svg>
                            Restore
                        </button>
                    </form>
 
                    <form action="{{ route('admin.backup.destroy', str_replace('.sql', '', $backup->filename)) }}" method="POST" class="inline" id="form-delete-{{ $loop->index }}">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="Swal.fire({title: 'Hapus Backup?', text: 'Tindakan ini tidak bisa dibatalkan.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#64748b', confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal', background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff', color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a', customClass: {popup: 'rounded-2xl border border-slate-100 dark:border-slate-700'}}).then((result) => { if (result.isConfirmed) document.getElementById('form-delete-{{ $loop->index }}').submit(); })"
                            class="inline-flex items-center gap-1.5 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/50 text-red-700 dark:text-red-400 text-xs font-bold px-4 py-2 rounded-xl transition-all">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
            @endforeach

            <!-- Empty State Filter Khusus (Alpine Dynamic) -->
            <div x-show="activeFilter === 'daily' && {{ count(array_filter($backups->items(), fn($b) => $b->type === 'daily')) }} === 0" class="p-12 text-center" x-cloak>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/20 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <p class="text-xs font-bold text-slate-400 dark:text-zinc-500">Belum ada file backup berkala Harian di halaman ini.</p>
            </div>
            <div x-show="activeFilter === 'weekly' && {{ count(array_filter($backups->items(), fn($b) => $b->type === 'weekly')) }} === 0" class="p-12 text-center" x-cloak>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/20 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <p class="text-xs font-bold text-slate-400 dark:text-zinc-500">Belum ada file backup berkala Mingguan di halaman ini.</p>
            </div>
            <div x-show="activeFilter === 'monthly' && {{ count(array_filter($backups->items(), fn($b) => $b->type === 'monthly')) }} === 0" class="p-12 text-center" x-cloak>
                <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-950/20 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <p class="text-xs font-bold text-slate-400 dark:text-zinc-500">Belum ada file backup berkala Bulanan di halaman ini.</p>
            </div>
            <div x-show="activeFilter === 'manual' && {{ count(array_filter($backups->items(), fn($b) => $b->type === 'manual')) }} === 0" class="p-12 text-center" x-cloak>
                <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-zinc-800 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-slate-400 dark:text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </div>
                <p class="text-xs font-bold text-slate-400 dark:text-zinc-500">Belum ada file backup Manual di halaman ini.</p>
            </div>
        </div>
        </div>
        @endif
        
        @if($backups->hasPages())
        <div class="p-6 border-t border-slate-100 dark:border-zinc-800">
            {{ $backups->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
    <!-- Form Hidden untuk Bulk Delete -->
    <form id="bulk-delete-form" action="{{ route('admin.backup.bulk_destroy') }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('backupManager', () => ({
            activeFilter: 'all',
            selectedFiles: [],
            selectAll: false,

            toggleSelectAll(forceState = null) {
                if (forceState !== null) this.selectAll = forceState;
                
                if (this.selectAll) {
                    this.selectedFiles = Array.from(document.querySelectorAll('input[type="checkbox"].file-checkbox'))
                        .filter(el => el.closest('.backup-row').style.display !== 'none')
                        .map(el => el.value);
                } else {
                    this.selectedFiles = [];
                }
            },
            
            bulkDelete() {
                if (this.selectedFiles.length === 0) return;
                
                Swal.fire({
                    title: 'Hapus Massal?',
                    text: 'Tindakan ini akan menghapus ' + this.selectedFiles.length + ' file secara permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus Semua!',
                    cancelButtonText: 'Batal',
                    background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                    color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('bulk-delete-form');
                        
                        // Hapus input filenames sebelumnya jika ada
                        form.querySelectorAll('.dynamic-input').forEach(e => e.remove());
                        
                        // Tambahkan yang baru
                        this.selectedFiles.forEach(filename => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'filenames[]';
                            input.value = filename;
                            input.classList.add('dynamic-input');
                            form.appendChild(input);
                        });

                        form.submit();
                    }
                });
            }
        }));
    });
</script>
@endpush
@endsection
