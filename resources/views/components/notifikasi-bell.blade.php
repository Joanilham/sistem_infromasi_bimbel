<div x-data="{
    open: false,
    unreadCount: 0,
    notifications: [],
    loading: false,
    filter: 'all',
    timer: null,

    init() {
        this.fetchUnreadCount();
        this.timer = setInterval(() => {
            if (!this.open) {
                this.fetchUnreadCount();
            }
        }, 30000);
    },

    get filteredNotifications() {
        if (this.filter === 'unread') {
            return this.notifications.filter(n => !n.is_read);
        }
        return this.notifications;
    },

    fetchUnreadCount() {
        fetch('{{ route('notifikasi.lms.unread_count', [], false) }}', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json();
        })
        .then(data => {
            this.unreadCount = data.unread_count ?? 0;
        })
        .catch(err => {
            console.error('Gagal mengambil counter notifikasi:', err);
        });
    },

    toggleOpen() {
        this.open = !this.open;
        if (this.open) {
            this.fetchRecent();
        }
    },

    fetchRecent() {
        this.loading = true;
        fetch('{{ route('notifikasi.lms.recent', [], false) }}', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json();
        })
        .then(data => {
            this.notifications = Array.isArray(data.notifications) ? data.notifications : [];
            this.unreadCount = data.unread_count ?? 0;
        })
        .catch(err => {
            console.error('Gagal mengambil daftar notifikasi:', err);
        })
        .finally(() => {
            this.loading = false;
        });
    },

    markAllRead() {
        fetch('{{ route('notifikasi.lms.tandai_semua', [], false) }}', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json();
        })
        .then(data => {
            if (data.success) {
                this.unreadCount = 0;
                this.notifications = this.notifications.map(n => ({ ...n, is_read: true }));
            }
        })
        .catch(err => {
            console.error('Gagal menandai notifikasi telah dibaca:', err);
        });
    }
}" 
class="relative shrink-0" 
@click.outside="open = false" 
@keydown.escape.window="open = false">

    <!-- Tombol Lonceng Notifikasi -->
    <button type="button" 
            @click="toggleOpen()"
            class="relative inline-flex items-center justify-center p-2.5 rounded-2xl text-slate-500 hover:text-slate-800 dark:text-zinc-400 dark:hover:text-zinc-100 hover:bg-slate-100 dark:hover:bg-zinc-800 transition-all duration-200 focus:outline-none cursor-pointer"
            :aria-expanded="open"
            aria-label="Notifikasi">
        
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" 
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        <!-- Badge Counter Unread -->
        <template x-if="unreadCount > 0">
            <span class="absolute top-1.5 right-1.5 flex h-4 min-w-4 items-center justify-center pointer-events-none">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                <span class="relative inline-flex items-center justify-center h-4 min-w-4 px-1 rounded-full bg-rose-600 text-[10px] font-black text-white shadow-xs"
                      x-text="unreadCount > 99 ? '99+' : unreadCount"></span>
            </span>
        </template>
    </button>

    <!-- Popover Dropdown Notifikasi -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95 translate-y-1"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-1"
         class="absolute right-0 mt-3 w-[calc(100vw-2rem)] sm:w-96 max-w-sm rounded-3xl bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 shadow-2xl shadow-slate-300/40 dark:shadow-zinc-950/80 overflow-hidden z-50 focus:outline-none"
         x-cloak>

        <!-- Header Panel -->
        <div class="px-5 pt-4 pb-3 border-b border-slate-100 dark:border-zinc-800 bg-slate-50/70 dark:bg-zinc-900/80">
            <div class="flex items-center justify-between gap-2 mb-3">
                <div class="flex items-center gap-2 min-w-0">
                    <h3 class="text-sm font-black text-slate-800 dark:text-white truncate">Pusat Notifikasi</h3>
                    <template x-if="unreadCount > 0">
                        <span class="px-2 py-0.5 text-[10px] font-black rounded-full bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400 border border-rose-200/50 dark:border-rose-900/40"
                              x-text="unreadCount + ' baru'"></span>
                    </template>
                </div>
                
                <template x-if="unreadCount > 0">
                    <button type="button"
                            @click="markAllRead()"
                            class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors cursor-pointer shrink-0 inline-flex items-center gap-1 hover:underline">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Tandai dibaca
                    </button>
                </template>
            </div>

            <!-- Quick Filter Tabs -->
            <div class="flex items-center gap-1 p-1 rounded-xl bg-slate-200/60 dark:bg-zinc-800 text-xs font-bold">
                <button type="button"
                        @click="filter = 'all'"
                        class="flex-1 py-1 rounded-lg transition-all text-center cursor-pointer"
                        :class="filter === 'all' ? 'bg-white dark:bg-zinc-700 text-slate-900 dark:text-white shadow-xs' : 'text-slate-500 dark:text-zinc-400 hover:text-slate-800 dark:hover:text-zinc-200'">
                    Semua (<span x-text="notifications.length"></span>)
                </button>
                <button type="button"
                        @click="filter = 'unread'"
                        class="flex-1 py-1 rounded-lg transition-all text-center inline-flex items-center justify-center gap-1.5 cursor-pointer"
                        :class="filter === 'unread' ? 'bg-white dark:bg-zinc-700 text-slate-900 dark:text-white shadow-xs' : 'text-slate-500 dark:text-zinc-400 hover:text-slate-800 dark:hover:text-zinc-200'">
                    <span>Belum Dibaca</span>
                    <template x-if="unreadCount > 0">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                    </template>
                </button>
            </div>
        </div>

        <!-- Body / Notification List -->
        <div class="max-h-80 overflow-y-auto divide-y divide-slate-100 dark:divide-zinc-800/60">
            <!-- Loading Indicator -->
            <template x-if="loading">
                <div class="py-8 flex flex-col items-center justify-center text-slate-400 text-xs">
                    <svg class="animate-spin h-6 w-6 text-indigo-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>Memuat notifikasi...</span>
                </div>
            </template>

            <!-- Empty State -->
            <template x-if="!loading && filteredNotifications.length === 0">
                <div class="py-10 px-4 text-center">
                    <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 dark:bg-zinc-800 flex items-center justify-center text-slate-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <p class="mt-3 text-xs font-bold text-slate-700 dark:text-zinc-200"
                       x-text="filter === 'unread' ? 'Semua notifikasi sudah dibaca' : 'Belum ada notifikasi baru'"></p>
                    <p class="text-[11px] text-slate-400 mt-0.5"
                       x-text="filter === 'unread' ? 'Tidak ada pemberitahuan baru yang tertunda' : 'Pemberitahuan tugas dan pengumpulan akan tampil di sini'"></p>
                </div>
            </template>

            <!-- Cards -->
            <template x-if="!loading && filteredNotifications.length > 0">
                <div>
                    <template x-for="item in filteredNotifications" :key="item.id">
                        <a :href="item.url" 
                           class="group relative flex gap-3.5 p-4 transition-colors hover:bg-slate-50 dark:hover:bg-zinc-800/50"
                           :class="{'bg-indigo-50/40 dark:bg-indigo-950/20': !item.is_read}">
                            
                            <!-- Icon Indicator -->
                            <div class="shrink-0 mt-0.5">
                                <template x-if="item.type === 'tugas_baru'">
                                    <div class="w-9 h-9 rounded-2xl bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shadow-xs">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                        </svg>
                                    </div>
                                </template>
                                <template x-if="item.type === 'tugas_dikumpulkan'">
                                    <div class="w-9 h-9 rounded-2xl bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shadow-xs">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                    </div>
                                </template>
                                <template x-if="item.type === 'tugas_dinilai'">
                                    <div class="w-9 h-9 rounded-2xl bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shadow-xs">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </template>
                                <template x-if="!['tugas_baru', 'tugas_dikumpulkan', 'tugas_dinilai'].includes(item.type)">
                                    <div class="w-9 h-9 rounded-2xl bg-slate-100 dark:bg-zinc-800 text-slate-500 flex items-center justify-center shadow-xs">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </template>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-1">
                                    <h4 class="text-xs font-bold text-slate-800 dark:text-zinc-100 truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors"
                                        x-text="item.title"></h4>
                                    <span class="text-[10px] font-medium text-slate-400 shrink-0" x-text="item.time_ago"></span>
                                </div>
                                <p class="text-xs text-slate-600 dark:text-zinc-400 mt-1 line-clamp-2" x-text="item.message"></p>
                                
                                <template x-if="item.due_at">
                                    <div class="mt-2 inline-flex items-center gap-1 text-[10px] font-semibold text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/40 px-2 py-0.5 rounded-md">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span x-text="'Tenggat: ' + item.due_at"></span>
                                    </div>
                                </template>
                            </div>

                            <!-- Unread Dot -->
                            <template x-if="!item.is_read">
                                <div class="shrink-0 flex items-center">
                                    <span class="w-2 h-2 rounded-full bg-indigo-600 dark:bg-indigo-400"></span>
                                </div>
                            </template>
                        </a>
                    </template>
                </div>
            </template>
        </div>

        <!-- Footer Panel -->
        <div class="px-5 py-3 border-t border-slate-100 dark:border-zinc-800 bg-slate-50/80 dark:bg-zinc-900/80 text-center">
            <a href="{{ route('notifikasi.lms.index', [], false) }}" 
               class="text-xs font-bold text-slate-600 dark:text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                Buka Seluruh Riwayat Notifikasi &rarr;
            </a>
        </div>
    </div>
</div>
