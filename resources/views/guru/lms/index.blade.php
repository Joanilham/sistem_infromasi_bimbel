@extends('layouts.guru')

@section('title', 'LMS Guru')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-indigo-600">Learning Management System</p>
            <h1 class="mt-1 text-2xl font-black text-slate-900 dark:text-white">Materi & Tugas</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Arsip {{ $subject }} milik Anda untuk setiap kelompok belajar.</p>
        </div>
        <a href="{{ route('guru.lms.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-indigo-700">
            <span class="text-lg leading-none"></span> Upload Materi & Buat Tugas
        </a>
    </div>

    <div class="mb-5 flex flex-wrap items-center gap-2">
        <span class="rounded-xl bg-indigo-50 px-3 py-2 text-xs font-bold text-indigo-700 dark:bg-indigo-950/50 dark:text-indigo-300">{{ $subject }}</span>
        <span class="rounded-xl bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $contents->count() }} konten tersimpan</span>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        @foreach([['Materi', $materials, 'indigo'], ['Tugas', $assignments, 'amber']] as [$archiveTitle, $archiveItems, $archiveColor])
        <section class="rounded-2xl border border-slate-200 bg-slate-50/50 p-4 dark:border-slate-800 dark:bg-slate-900/40">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-black text-slate-900 dark:text-white">Arsip {{ $archiveTitle }}</h2>
                <span class="rounded-full bg-{{ $archiveColor }}-100 px-2.5 py-1 text-xs font-black text-{{ $archiveColor }}-700">{{ $archiveItems->count() }}</span>
            </div>
            <div class="space-y-4">
        @forelse($archiveItems as $content)
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="rounded-lg px-2.5 py-1 text-[11px] font-black uppercase {{ $content->type === 'tugas' ? 'bg-amber-100 text-amber-700' : 'bg-indigo-100 text-indigo-700' }}">{{ $content->type_label }}</span>
                        <span class="text-xs font-semibold text-slate-500">{{ $content->kelompokBelajar?->nama_kelompok ?? 'Kelompok dihapus' }}</span>
                    </div>
                    <form method="POST" action="{{ route('guru.lms.destroy', $content) }}" onsubmit="return confirm('Hapus konten ini?')">
                        @csrf @method('DELETE')
                        <button class="text-xs font-bold text-red-600 hover:text-red-700">Hapus</button>
                    </form>
                </div>
                <h2 class="mt-4 text-lg font-black text-slate-900 dark:text-white">{{ $content->title }}</h2>
                @if($content->description)<p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $content->description }}</p>@endif
                @if($content->due_at)<p class="mt-3 text-xs font-bold text-amber-700">Batas pengumpulan: {{ $content->due_at->format('d M Y, H:i') }}</p>@endif
                @if($content->file_path)
                    <div class="mt-4 flex flex-wrap items-center gap-2">
                        <span class="mr-1 max-w-full truncate text-xs font-semibold text-slate-500">Lampiran: {{ $content->file_name }}</span>
                        <a href="{{ route('guru.lms.open', $content) }}" target="_blank" rel="noopener" class="inline-flex items-center rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-700 transition hover:bg-indigo-100 dark:bg-indigo-950/50 dark:text-indigo-300">Buka</a>
                        <a href="{{ route('guru.lms.download', $content) }}" class="inline-flex items-center rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-700 transition hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200">Download</a>
                    </div>
                @endif
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-300 p-8 text-center text-sm text-slate-500">Belum ada tugas {{ strtolower($archiveTitle) }}.</div>
        @endforelse
            </div>
        </section>
        @endforeach
    </div>
</div>
@endsection
