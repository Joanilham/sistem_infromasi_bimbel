@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center gap-1.5 sm:gap-2 justify-center sm:justify-end">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="h-8 sm:h-9 px-3.5 sm:px-4 rounded-full border border-slate-200 dark:border-zinc-800 text-slate-400 dark:text-zinc-600 text-xs font-bold flex items-center gap-1.5 shrink-0 opacity-40 cursor-not-allowed select-none" aria-disabled="true">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                <span>Prev</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="h-8 sm:h-9 px-3.5 sm:px-4 rounded-full border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-zinc-800 text-xs font-bold flex items-center gap-1.5 transition-all shrink-0 active:scale-95 shadow-sm" aria-label="{{ __('pagination.previous') }}">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                <span>Prev</span>
            </a>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="h-8 sm:h-9 px-3.5 sm:px-4 rounded-full border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-zinc-800 text-xs font-bold flex items-center gap-1.5 transition-all shrink-0 active:scale-95 shadow-sm" aria-label="{{ __('pagination.next') }}">
                <span>Next</span>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </a>
        @else
            <span class="h-8 sm:h-9 px-3.5 sm:px-4 rounded-full border border-slate-200 dark:border-zinc-800 text-slate-400 dark:text-zinc-600 text-xs font-bold flex items-center gap-1.5 shrink-0 opacity-40 cursor-not-allowed select-none" aria-disabled="true">
                <span>Next</span>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </span>
        @endif
    </nav>
@endif
