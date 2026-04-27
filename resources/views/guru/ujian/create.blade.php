@extends('layouts.guru')

@section('title', 'Buat Ujian')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
    <div class="mb-6">
        <a href="{{ route('guru.ujian.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm inline-flex items-center gap-1 mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
        <h2 class="text-lg font-semibold text-slate-800">Buat Ujian Baru</h2>
        <p class="text-sm text-slate-500">Buat ujian untuk siswa</p>
    </div>

    <form action="{{ route('guru.ujian.store') }}" method="POST" class="space-y-6">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Judul Ujian <span class="text-red-500">*</span></label>
            <input type="text" name="judul" required placeholder="Contoh: Ujian Tengah Semester Matematika" class="w-full border border-slate-300 rounded-lg px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
            <textarea name="deskripsi" rows="3" placeholder="Deskripsi ujian..." class="w-full border border-slate-300 rounded-lg px-3 py-2"></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Bank Soa</label>
            <select name="bank_soal_id" class="w-full border border-slate-300 rounded-lg px-3 py-2">
                <option value="">Pilih Bank Soa</option>
                @foreach($bankSoals as $bankSoal)
                <option value="{{ $bankSoal->id }}">{{ $bankSoal->judul }} ({{ $bankSoal->jumlah_soal }} soa)</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Waktu Mulai</label>
                <input type="datetime-local" name="waktu_mulai" class="w-full border border-slate-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Waktu Selesai</label>
                <input type="datetime-local" name="waktu_selesai" class="w-full border border-slate-300 rounded-lg px-3 py-2">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Durasi (menit)</label>
            <input type="number" name="durasi" min="1" placeholder="Contoh: 90" class="w-full border border-slate-300 rounded-lg px-3 py-2">
        </div>

        <div class="flex items-center gap-6">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="acak_soal" class="rounded border-slate-300">
                <span class="text-sm text-slate-700">Acak urutan soa</span>
            </label>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="tampilkan_hasil" checked class="rounded border-slate-300">
                <span class="text-sm text-slate-700">Tampilkan hasil setelah selesai</span>
            </label>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700">Simpan</button>
            <a href="{{ route('guru.ujian.index') }}" class="text-slate-600 hover:text-slate-800 px-6 py-2">Batal</a>
        </div>
    </form>
</div>
@endsection