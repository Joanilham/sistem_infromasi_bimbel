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
                Kelola struktur konten, visual hero slider, mitra, keunggulan, ulasan alumni, dan kontak publik portal bimbingan belajar.
            </p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('welcome') }}" target="_blank" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-slate-700 dark:text-slate-200 font-semibold text-xs px-4 py-2.5 rounded-lg border border-slate-200 dark:border-zinc-700 transition-colors">
                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Lihat Landing Page
            </a>
        </div>
    </div>

    <!-- Tab Navigation (Clean SVG Icons) -->
    <div class="overflow-x-auto pb-1">
        <div class="flex items-center gap-1.5 p-1.5 bg-slate-100 dark:bg-zinc-800/80 rounded-xl w-fit border border-slate-200 dark:border-zinc-700/50">
            <button type="button" @click="tab = 'general'" :class="tab === 'general' ? 'bg-white dark:bg-zinc-700 shadow-sm text-slate-900 dark:text-white font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium'" class="px-3.5 py-2 rounded-lg text-xs sm:text-sm transition-all flex items-center gap-2 whitespace-nowrap cursor-pointer">
                <svg class="w-4 h-4 shrink-0 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M4 10h16M10 10v10"/></svg>
                <span>Hero &amp; Identitas</span>
            </button>
            <button type="button" @click="tab = 'mitra'" :class="tab === 'mitra' ? 'bg-white dark:bg-zinc-700 shadow-sm text-slate-900 dark:text-white font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium'" class="px-3.5 py-2 rounded-lg text-xs sm:text-sm transition-all flex items-center gap-2 whitespace-nowrap cursor-pointer">
                <svg class="w-4 h-4 shrink-0 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span>Mitra &amp; Reputasi</span>
            </button>
            <button type="button" @click="tab = 'packages'" :class="tab === 'packages' ? 'bg-white dark:bg-zinc-700 shadow-sm text-slate-900 dark:text-white font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium'" class="px-3.5 py-2 rounded-lg text-xs sm:text-sm transition-all flex items-center gap-2 whitespace-nowrap cursor-pointer">
                <svg class="w-4 h-4 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 14v7"/></svg>
                <span>Paket Bimbingan</span>
            </button>
            <button type="button" @click="tab = 'features'" :class="tab === 'features' ? 'bg-white dark:bg-zinc-700 shadow-sm text-slate-900 dark:text-white font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium'" class="px-3.5 py-2 rounded-lg text-xs sm:text-sm transition-all flex items-center gap-2 whitespace-nowrap cursor-pointer">
                <svg class="w-4 h-4 shrink-0 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span>Keunggulan</span>
            </button>
            <button type="button" @click="tab = 'about'" :class="tab === 'about' ? 'bg-white dark:bg-zinc-700 shadow-sm text-slate-900 dark:text-white font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium'" class="px-3.5 py-2 rounded-lg text-xs sm:text-sm transition-all flex items-center gap-2 whitespace-nowrap cursor-pointer">
                <svg class="w-4 h-4 shrink-0 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <span>Tentang Lembaga</span>
            </button>
            <button type="button" @click="tab = 'testimonials'" :class="tab === 'testimonials' ? 'bg-white dark:bg-zinc-700 shadow-sm text-slate-900 dark:text-white font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium'" class="px-3.5 py-2 rounded-lg text-xs sm:text-sm transition-all flex items-center gap-2 whitespace-nowrap cursor-pointer">
                <svg class="w-4 h-4 shrink-0 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                <span>Testimoni</span>
            </button>
            <button type="button" @click="tab = 'faq'" :class="tab === 'faq' ? 'bg-white dark:bg-zinc-700 shadow-sm text-slate-900 dark:text-white font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium'" class="px-3.5 py-2 rounded-lg text-xs sm:text-sm transition-all flex items-center gap-2 whitespace-nowrap cursor-pointer">
                <svg class="w-4 h-4 shrink-0 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>FAQ</span>
            </button>
            <button type="button" @click="tab = 'contact'" :class="tab === 'contact' ? 'bg-white dark:bg-zinc-700 shadow-sm text-slate-900 dark:text-white font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium'" class="px-3.5 py-2 rounded-lg text-xs sm:text-sm transition-all flex items-center gap-2 whitespace-nowrap cursor-pointer">
                <svg class="w-4 h-4 shrink-0 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
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
