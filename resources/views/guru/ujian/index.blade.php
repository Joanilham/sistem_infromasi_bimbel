@extends('layouts.guru')

@section('title', 'Ujian')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">Manajemen Ujian</h2>
            <p class="text-sm text-slate-500">Buat dan kelola ujian untuk siswa</p>
        </div>
        <a href="{{ route('guru.ujian.create') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Ujian
        </a>
    </div>

    @if(session('success'))
    <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-200">
                    <th class="text-left py-3 px-4 text-sm font-medium text-slate-600">Judul</th>
                    <th class="text-left py-3 px-4 text-sm font-medium text-slate-600">Bank Soal</th>
                    <th class="text-left py-3 px-4 text-sm font-medium text-slate-600">Status</th>
                    <th class="text-left py-3 px-4 text-sm font-medium text-slate-600">Waktu</th>
                    <th class="text-right py-3 px-4 text-sm font-medium text-slate-600">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ujians as $ujian)
                <tr class="border-b border-slate-100 hover:bg-slate-50">
                    <td class="py-3 px-4">
                        <div class="font-medium text-slate-800">{{ $ujian->judul }}</div>
                        <div class="text-sm text-slate-500">{{ $ujian->deskripsi }}</div>
                    </td>
                    <td class="py-3 px-4 text-sm text-slate-600">
                        {{ $ujian->bankSoal->judul ?? '-' }}
                    </td>
                    <td class="py-3 px-4">
                        @if($ujian->status === 'draft')
                        <span class="bg-slate-100 text-slate-600 text-xs font-medium px-2 py-1 rounded">Draft</span>
                        @elseif($ujian->status === 'published')
                        <span class="bg-green-100 text-green-700 text-xs font-medium px-2 py-1 rounded">Published</span>
                        @else
                        <span class="bg-red-100 text-red-700 text-xs font-medium px-2 py-1 rounded">Closed</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 text-sm text-slate-600">
                        @if($ujian->durasi)
                        {{ $ujian->durasi }} menit
                        @else
                        -
                        @endif
                    </td>
                    <td class="py-3 px-4 text-right">
                        <a href="{{ route('guru.ujian.show', $ujian->id) }}" class="text-indigo-600 hover:text-indigo-800 mr-3 text-sm">Lihat</a>
                        <a href="{{ route('guru.ujian.edit', $ujian->id) }}" class="text-slate-600 hover:text-slate-800 mr-3 text-sm">Edit</a>
                        @if($ujian->status === 'draft')
                        <form action="{{ route('guru.ujian.publish', $ujian->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-green-600 hover:text-green-800 text-sm">Publish</button>
                        </form>
                        @elseif($ujian->status === 'published')
                        <form action="{{ route('guru.ujian.close', $ujian->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Tutup</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-slate-400">Belum ada ujian</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection