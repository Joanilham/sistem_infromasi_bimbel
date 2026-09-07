@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center gap-1.5 sm:gap-2 flex-wrap justify-center sm:justify-end">
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

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center text-slate-400 dark:text-zinc-500 font-bold text-xs shrink-0 select-none">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full text-xs font-black transition-all flex items-center justify-center shrink-0 bg-indigo-600 text-white shadow-md shadow-indigo-500/30 ring-2 ring-indigo-600/20 select-none">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full text-xs font-bold transition-all flex items-center justify-center shrink-0 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-zinc-800 border border-slate-200 dark:border-zinc-700 hover:border-slate-300 dark:hover:border-zinc-600 active:scale-95 shadow-sm" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

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
