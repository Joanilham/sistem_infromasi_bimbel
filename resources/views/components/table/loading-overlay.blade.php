<div x-show="isLoading" class="absolute inset-0 z-50 bg-white/50 dark:bg-zinc-900/50 backdrop-blur-sm flex items-start justify-center pt-20 transition-opacity duration-300" style="display: none;">
    <div class="bg-white dark:bg-zinc-800 p-4 rounded-2xl shadow-xl border border-slate-100 dark:border-zinc-700 flex items-center gap-3">
        <svg class="animate-spin h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="text-sm font-bold text-slate-700 dark:text-slate-200">Memuat data...</span>
    </div>
</div>
