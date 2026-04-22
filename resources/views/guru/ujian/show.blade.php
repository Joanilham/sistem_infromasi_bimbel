@extends('layouts.guru')

@section('title', 'Detail Ujian')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <div class="flex justify-between items-start">
            <div>
                <a href="{{ route('guru.ujian.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm inline-flex items-center gap-1 mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali
                </a>
                <h2 class="text-lg font-semibold text-slate-800">{{ $ujian->judul }}</h2>
                <p class="text-sm text-slate-500">{{ $ujian->deskripsi }}</p>
                <div class="flex gap-4 mt-3">
                    @if($ujian->status === 'draft')
                    <span class="bg-slate-100 text-slate-600 text-xs font-medium px-2 py-1 rounded">Draft</span>
                    @elseif($ujian->status === 'published')
                    <span class="bg-green-100 text-green-700 text-xs font-medium px-2 py-1 rounded">Published</span>
                    @else
                    <span class="bg-red-100 text-red-700 text-xs font-medium px-2 py-1 rounded">Closed</span>
                    @endif
                    <span class="text-sm text-slate-600">Durasi: {{ $ujian->durasi ?? '-' }} menit</span>
                    <span class="text-sm text-slate-600">Bank Soal: {{ $ujian->bankSoal->judul ?? '-' }}</span>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('guru.ujian.edit', $ujian->id) }}" class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-200">Edit</a>
                @if($ujian->status === 'draft')
                <form action="{{ route('guru.ujian.publish', $ujian->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Publish</button>
                </form>
                @elseif($ujian->status === 'published')
                <form action="{{ route('guru.ujian.close', $ujian->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Tutup</button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Results -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <h3 class="font-semibold text-slate-800 mb-4">Hasil Ujian</h3>
        
        @if(session('success'))
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        @if($hasilUjian->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="text-left py-3 px-4 text-sm font-medium text-slate-600">No</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-slate-600">Siswa</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-slate-600">Skor</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-slate-600">Nilai</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-slate-600">Waktu Mulai</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-slate-600">Waktu Selesai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hasilUjian as $index => $hasil)
                    <tr class="border-b border-slate-100">
                        <td class="py-3 px-4">{{ $index + 1 }}</td>
                        <td class="py-3 px-4">{{ $hasil->pesertaDidik->name }}</td>
                        <td class="py-3 px-4">{{ $hasil->skor ?? '-' }}</td>
                        <td class="py-3 px-4">
                            @if($hasil->nilai !== null)
                            <span class="font-medium {{ $hasil->nilai >= 70 ? 'text-green-600' : 'text-red-600' }}">{{ $hasil->nilai }}</span>
                            @else
                            -
                            @endif
                        </td>
                        <td class="py-3 px-4 text-sm text-slate-600">{{ $hasil->waktu_mulai ? $hasil->waktu_mulai->format('d-m-Y H:i') : '-' }}</td>
                        <td class="py-3 px-4 text-sm text-slate-600">{{ $hasil->waktu_selesai ? $hasil->waktu_selesai->format('d-m-Y H:i') : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-8 text-slate-400">
            Belum ada siswa yang mengerjakan ujian ini.
        </div>
        @endif
    </div>
</div>
@endsection