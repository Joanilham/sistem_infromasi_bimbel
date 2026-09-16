@extends('layouts.guru')

@section('title', 'Buat Konten LMS')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">
    <div><a href="{{ route('guru.lms.index') }}" class="text-sm font-bold text-indigo-600">&larr; Kembali ke LMS</a><h1 class="mt-3 text-2xl font-black text-slate-900 dark:text-white">Buat Konten LMS</h1></div>
    @if($errors->any())<div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form method="POST" action="{{ route('guru.lms.store') }}" enctype="multipart/form-data" class="space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        @csrf
        <div class="grid gap-5 sm:grid-cols-2">
            <label class="text-sm font-bold text-slate-700 dark:text-slate-200">Jenis
                <select name="type" required class="mt-2 w-full rounded-xl border-slate-300 text-sm dark:border-slate-700 dark:bg-slate-800"><option value="materi" @selected(old('type') === 'materi')>Materi</option><option value="tugas" @selected(old('type') === 'tugas')>Tugas</option></select>
            </label>
            <label class="text-sm font-bold text-slate-700 dark:text-slate-200">Kelompok Belajar
                <select name="kelompok_belajar_id" required class="mt-2 w-full rounded-xl border-slate-300 text-sm dark:border-slate-700 dark:bg-slate-800">
                    <option value="">Pilih kelompok belajar</option>
                    @foreach($kelompokBelajars as $kelompok)
                        <option value="{{ $kelompok->id }}" @selected(old('kelompok_belajar_id') == $kelompok->id)>
                            {{ $kelompok->nama_kelompok }}{{ $kelompok->kantor ? ' - ' . $kelompok->kantor->nama_kantor : '' }}{{ $kelompok->periode ? ' (' . $kelompok->periode->tahun_periode . ')' : '' }}
                        </option>
                    @endforeach
                </select>
                @if($kelompokBelajars->isEmpty())
                    <span class="mt-2 block text-xs font-normal text-amber-600">Belum ada kelompok belajar. Silakan buat kelompok terlebih dahulu dari menu admin.</span>
                @endif
            </label>
        </div>
        <label class="text-sm font-bold text-slate-700 dark:text-slate-200">Judul<input name="title" value="{{ old('title') }}" required maxlength="255" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-indigo-500 dark:focus:ring-indigo-950"></label>
        <label class="text-sm font-bold text-slate-700 dark:text-slate-200">Deskripsi / Instruksi<textarea name="description" rows="5" maxlength="10000" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-indigo-500 dark:focus:ring-indigo-950">{{ old('description') }}</textarea></label>
        <label class="text-sm font-bold text-slate-700 dark:text-slate-200">Batas Pengumpulan <span class="font-normal text-slate-400">(opsional)</span><input type="datetime-local" name="due_at" value="{{ old('due_at') }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-indigo-500 dark:focus:ring-indigo-950"></label>
        <label class="text-sm font-bold text-slate-700 dark:text-slate-200">Lampiran <span class="font-normal text-slate-400">(maks. 10 MB)</span><input type="file" name="file" class="mt-2 block w-full rounded-xl border border-slate-300 p-2 text-sm"></label>
        <div class="flex justify-end"><button class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-indigo-700">Simpan Konten</button></div>
    </form>
</div>
@endsection
