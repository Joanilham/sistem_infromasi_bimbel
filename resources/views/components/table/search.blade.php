@props(['search' => request('search', ''), 'placeholder' => 'Cari data...', 'alpine' => false])

<div class="relative group flex-1 sm:w-64">
    <input type="text" name="search" value="{{ $search }}"
        placeholder="{{ $placeholder }}"
        @if($alpine) @input.debounce.500ms="fetchData" @endif
        class="w-full h-[42px] bg-white dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold py-2.5 pl-10 pr-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-sm">
    <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
    </svg>
</div>
