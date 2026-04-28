@extends('layouts.admin')
@section('title', 'Verifikasi Pendaftaran')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Verifikasi Pendaftaran Siswa</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola pengajuan pendaftaran siswa baru</p>
    </div>
</div>

{{-- Filter Tabs --}}
<div class="flex gap-2 mb-5">
    @foreach(['semua'=>'Semua','menunggu'=>'Menunggu','diverifikasi'=>'Diverifikasi','ditolak'=>'Ditolak'] as $key=>$label)
    <a href="{{ route('admin.pendaftaran.index', $key !== 'semua' ? ['status'=>$key] : []) }}"
       class="px-4 py-2 rounded-xl text-sm font-semibold transition-all
       {{ (request('status', 'semua') === $key) ? 'bg-indigo-500 text-white shadow-md' : 'bg-white dark:bg-zinc-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

<div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-slate-100 dark:border-zinc-800 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-zinc-800 text-xs uppercase text-slate-500 dark:text-slate-400 tracking-wide">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Nama Lengkap</th>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-left">Paket</th>
                    <th class="px-4 py-3 text-left">Pembayaran</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Tanggal Daftar</th>
                    <th class="px-4 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                @forelse($pendaftarans as $p)
                <tr class="hover:bg-slate-50 dark:hover:bg-zinc-800/50 transition-colors">
                    <td class="px-4 py-3 text-slate-500">{{ $pendaftarans->firstItem() + $loop->index }}</td>
                    <td class="px-4 py-3 font-semibold text-slate-800 dark:text-white">{{ $p->nama_lengkap }}</td>
                    <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $p->email }}</td>
                    <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $p->paketBimbingan?->nama_paket ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @if($p->pembayaran)
                            @php $ps = $p->pembayaran->status; @endphp
                            <span class="px-2 py-1 rounded-lg text-xs font-bold
                                {{ $ps === 'dikonfirmasi' ? 'bg-emerald-100 text-emerald-700' : ($ps === 'ditolak' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">
                                {{ ucfirst($ps) }}
                            </span>
                        @else
                            <span class="text-slate-400 text-xs">Belum upload</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @php $s = $p->status; @endphp
                        <span class="px-2 py-1 rounded-lg text-xs font-bold
                            {{ $s === 'diverifikasi' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : ($s === 'ditolak' ? 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400') }}">
                            {{ $s === 'menunggu' ? 'Menunggu' : ($s === 'diverifikasi' ? 'Diverifikasi' : 'Ditolak') }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-slate-500">{{ $p->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.pendaftaran.show', $p) }}"
                           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 text-xs font-semibold hover:bg-indigo-100 transition-colors">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-12 text-center text-slate-400">Tidak ada data pendaftaran.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pendaftarans->hasPages())
    <div class="px-4 py-3 border-t border-slate-100 dark:border-zinc-800">
        {{ $pendaftarans->links() }}
    </div>
    @endif
</div>
@endsection
