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
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800">
                    Pengaturan Halaman Depan
                </span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Manajemen Landing Page</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm max-w-2xl">
                Kelola struktur, konten narasi, visual hero, mitra, keunggulan, testimoni, dan kontak publik portal bimbingan belajar.
            </p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('welcome') }}" target="_blank" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-slate-700 dark:text-slate-200 font-semibold text-xs px-4 py-2.5 rounded-lg border border-slate-200 dark:border-zinc-700 transition-colors">
                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Lihat Landing Page
            </a>
        </div>
    </div>

    <!-- Tab Navigation (Ordered according to Landing Page Sections) -->
    <div class="overflow-x-auto pb-1">
        <div class="flex items-center gap-1.5 p-1.5 bg-slate-100 dark:bg-zinc-800/80 rounded-xl w-fit border border-slate-200 dark:border-zinc-700/50">
            <button type="button" @click="tab = 'general'" :class="tab === 'general' ? 'bg-white dark:bg-zinc-700 shadow-sm text-slate-900 dark:text-white font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium'" class="px-4 py-2 rounded-lg text-xs sm:text-sm transition-all flex items-center gap-2 whitespace-nowrap cursor-pointer">
                <span>🌟</span>
                <span>Hero &amp; Identitas</span>
            </button>
            <button type="button" @click="tab = 'mitra'" :class="tab === 'mitra' ? 'bg-white dark:bg-zinc-700 shadow-sm text-slate-900 dark:text-white font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium'" class="px-4 py-2 rounded-lg text-xs sm:text-sm transition-all flex items-center gap-2 whitespace-nowrap cursor-pointer">
                <span>🏛️</span>
                <span>Mitra &amp; Reputasi</span>
            </button>
            <button type="button" @click="tab = 'packages'" :class="tab === 'packages' ? 'bg-white dark:bg-zinc-700 shadow-sm text-slate-900 dark:text-white font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium'" class="px-4 py-2 rounded-lg text-xs sm:text-sm transition-all flex items-center gap-2 whitespace-nowrap cursor-pointer">
                <span>🎓</span>
                <span>Paket Bimbingan</span>
            </button>
            <button type="button" @click="tab = 'features'" :class="tab === 'features' ? 'bg-white dark:bg-zinc-700 shadow-sm text-slate-900 dark:text-white font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium'" class="px-4 py-2 rounded-lg text-xs sm:text-sm transition-all flex items-center gap-2 whitespace-nowrap cursor-pointer">
                <span>⚡</span>
                <span>Keunggulan</span>
            </button>
            <button type="button" @click="tab = 'about'" :class="tab === 'about' ? 'bg-white dark:bg-zinc-700 shadow-sm text-slate-900 dark:text-white font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium'" class="px-4 py-2 rounded-lg text-xs sm:text-sm transition-all flex items-center gap-2 whitespace-nowrap cursor-pointer">
                <span>📖</span>
                <span>Tentang Lembaga</span>
            </button>
            <button type="button" @click="tab = 'testimonials'" :class="tab === 'testimonials' ? 'bg-white dark:bg-zinc-700 shadow-sm text-slate-900 dark:text-white font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium'" class="px-4 py-2 rounded-lg text-xs sm:text-sm transition-all flex items-center gap-2 whitespace-nowrap cursor-pointer">
                <span>💬</span>
                <span>Testimoni</span>
            </button>
            <button type="button" @click="tab = 'faq'" :class="tab === 'faq' ? 'bg-white dark:bg-zinc-700 shadow-sm text-slate-900 dark:text-white font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium'" class="px-4 py-2 rounded-lg text-xs sm:text-sm transition-all flex items-center gap-2 whitespace-nowrap cursor-pointer">
                <span>❓</span>
                <span>FAQ</span>
            </button>
            <button type="button" @click="tab = 'contact'" :class="tab === 'contact' ? 'bg-white dark:bg-zinc-700 shadow-sm text-slate-900 dark:text-white font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium'" class="px-4 py-2 rounded-lg text-xs sm:text-sm transition-all flex items-center gap-2 whitespace-nowrap cursor-pointer">
                <span>📞</span>
                <span>Kontak &amp; Sosial</span>
            </button>
        </div>
    </div>

    <!-- Tab Contents in Order -->
    @include('admin.landing_page.partials.general')
    @include('admin.landing_page.partials.mitra')
    @include('admin.landing_page.partials.packages')
    @include('admin.landing_page.partials.features')
    @include('admin.landing_page.partials.about')
    @include('admin.landing_page.partials.testimonials')
    @include('admin.landing_page.partials.faq')
    @include('admin.landing_page.partials.contact')
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
    if(event.target.files && event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}
</script>
@endpush
@endsection
