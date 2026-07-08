@extends('layouts.admin')

@section('title', 'Manajemen Landing Page')

@section('content')
<div class="space-y-6" x-data="{ 
    tab: '{{ session('active_tab') }}' || localStorage.getItem('activeLandingTab') || 'general', 
    overlayOpacity: {{ ($master->hero_overlay_opacity ?? 0.5) * 100 }} 
}" x-init="$watch('tab', value => localStorage.setItem('activeLandingTab', value))">
    <!-- Header -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Manajemen Landing Page</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm max-w-2xl">
                Kustomisasi tampilan depan portal bimbingan belajar. Kelola narasi, visual, testimoni, dan galeri untuk memikat calon peserta didik.
            </p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400 ring-1 ring-indigo-100 dark:ring-indigo-900/30">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="flex flex-wrap gap-2 p-1 bg-slate-100 dark:bg-zinc-800/80 rounded-lg w-fit border border-slate-200 dark:border-zinc-700/50">
        <button @click="tab = 'general'" :class="tab === 'general' ? 'bg-white dark:bg-zinc-700 shadow-sm text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white'" class="px-5 py-2 rounded-md text-sm font-medium transition-all">
            Umum
        </button>
        <button @click="tab = 'packages'" :class="tab === 'packages' ? 'bg-white dark:bg-zinc-700 shadow-sm text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white'" class="px-5 py-2 rounded-md text-sm font-medium transition-all">
            Paket
        </button>
        <button @click="tab = 'testimonials'" :class="tab === 'testimonials' ? 'bg-white dark:bg-zinc-700 shadow-sm text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white'" class="px-5 py-2 rounded-md text-sm font-medium transition-all">
            Testimoni
        </button>
        <button @click="tab = 'faq'" :class="tab === 'faq' ? 'bg-white dark:bg-zinc-700 shadow-sm text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white'" class="px-5 py-2 rounded-md text-sm font-medium transition-all">
            FAQ
        </button>

        <button @click="tab = 'features'" :class="tab === 'features' ? 'bg-white dark:bg-zinc-700 shadow-sm text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white'" class="px-5 py-2 rounded-md text-sm font-medium transition-all">
            Keunggulan
        </button>
        <button @click="tab = 'mitra'" :class="tab === 'mitra' ? 'bg-white dark:bg-zinc-700 shadow-sm text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white'" class="px-5 py-2 rounded-md text-sm font-medium transition-all">
            Mitra
        </button>
    </div>

    
    @include('admin.landing_page.partials.general')
    @include('admin.landing_page.partials.packages')
    @include('admin.landing_page.partials.testimonials')
    @include('admin.landing_page.partials.faq')

    @include('admin.landing_page.partials.features')
    @include('admin.landing_page.partials.mitra')
</div>

@push('scripts')
<script>
function previewImage(event, previewId, placeholderId) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById(previewId);
        if(output) {
            output.src = reader.result;
            output.classList.remove('hidden');
        }
        const placeholder = document.getElementById(placeholderId);
        if(placeholder) {
            placeholder.classList.add('hidden');
            placeholder.classList.remove('flex');
        }
    };
    if(event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}
</script>
@endpush
@endsection
