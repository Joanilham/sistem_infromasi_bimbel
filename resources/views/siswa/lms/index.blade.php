@extends('layouts.siswa')

@section('title', 'LMS Siswa')

@section('content')
<div class="space-y-6 pb-12">
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-slate-900 p-6 text-white shadow-xl sm:p-8">
        <div class="relative z-10 max-w-2xl"><p class="text-xs font-bold uppercase tracking-[0.2em] text-indigo-200">Learning Management System</p><h1 class="mt-2 text-3xl font-black">Ruang Belajar Anda</h1><p class="mt-2 text-sm leading-6 text-indigo-100">Akses materi dan tugas yang dibagikan guru untuk kelompok belajar Anda.</p></div>
        <div class="absolute -right-12 -top-16 h-56 w-56 rounded-full bg-white/10"></div>
    </div>

    @php $materials = $contents->where('type', 'materi'); $assignments = $contents->where('type', 'tugas'); @endphp
    <div class="grid gap-6 lg:grid-cols-2">
        @foreach([['Materi Pembelajaran', $materials, 'indigo'], ['Tugas', $assignments, 'amber']] as [$heading, $items, $color])
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="mb-4 flex items-center justify-between"><h2 class="text-lg font-black text-slate-900 dark:text-white">{{ $heading }}</h2><span class="rounded-full bg-{{ $color }}-100 px-2.5 py-1 text-xs font-black text-{{ $color }}-700">{{ $items->count() }}</span></div>
                <div class="space-y-3">
                    @forelse($items as $content)
                        <article class="rounded-xl border border-slate-100 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-800/60">
                            <h3 class="font-bold text-slate-900 dark:text-white">{{ $content->title }}</h3>
                            <p class="mt-1 text-xs font-semibold text-slate-500">Oleh {{ $content->guru->name }} &middot; {{ $content->created_at->format('d M Y') }}</p>
                            @if($content->description)<p class="mt-3 whitespace-pre-line text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $content->description }}</p>@endif
                            @if($content->due_at)<p class="mt-3 text-xs font-bold text-amber-700">Batas pengumpulan: {{ $content->due_at->format('d M Y, H:i') }}</p>@endif
                            @if($content->file_path)<a href="{{ route('siswa.lms.download', $content) }}" class="mt-4 inline-flex items-center gap-2 text-xs font-bold text-indigo-600 hover:text-indigo-700">Unduh lampiran &darr;</a>@endif
                        </article>
                    @empty
                        <p class="rounded-xl border border-dashed border-slate-300 p-6 text-center text-sm text-slate-500">Belum ada {{ strtolower($heading) }}.</p>
                    @endforelse
                </div>
            </section>
        @endforeach
    </div>
</div>
@endsection
