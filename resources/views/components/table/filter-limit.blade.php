@props(['perPage' => request('per_page', 10), 'alpine' => false])

<div class="flex items-stretch bg-white dark:bg-zinc-950 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all overflow-hidden w-max shrink-0 h-[42px]">
    <div class="px-3 py-2 bg-slate-50 dark:bg-zinc-900 border-r border-slate-200 dark:border-zinc-800 flex items-center justify-center">
        <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Lihat</span>
    </div>
    <select name="per_page" 
        @if($alpine) @change="fetchData" @else onchange="this.form.submit()" @endif 
        class="no-tomselect bg-transparent border-none text-xs font-black focus:ring-0 py-2 pl-3 pr-8 text-slate-800 dark:text-white cursor-pointer h-full hover:bg-slate-50 dark:hover:bg-zinc-900/50 transition-colors w-full">
        @foreach([10, 25, 50, 100] as $n)
            <option value="{{ $n }}" {{ request('per_page', 10) == $n ? 'selected' : '' }}>{{ $n }} Data</option>
        @endforeach
    </select>
</div>
