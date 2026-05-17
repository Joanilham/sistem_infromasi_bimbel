@extends('layouts.admin')
@section('title', 'Detail Pembayaran — ' . $pesertaDidik->nama_lengkap)

@section('content')
@php
    $biaya      = $pesertaDidik->paketBimbingan?->nominal ?? 0;
    $diskon     = $pembayaran->diskon_nominal;
    $pendaftar  = $pembayaran->biaya_pendaftaran;
    $total      = $pembayaran->total_harus_dibayar;
    $terbayar   = $pembayaran->total_terbayar;
    $kekurangan = $pembayaran->kekurangan;
    $lunas      = $pembayaran->lunas;
@endphp
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('keuangan.pembayaran.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-indigo-600 mb-3 transition">
                ← Kembali ke Daftar Siswa
            </a>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">{{ $pesertaDidik->nama_lengkap }}</h1>
            <p class="text-slate-500 text-sm mt-0.5">No. Induk: <span class="font-mono font-bold">{{ $pesertaDidik->nomor_induk ?? '-' }}</span></p>
        </div>
        <span class="self-start sm:self-auto px-4 py-2 rounded-2xl text-sm font-black uppercase
            {{ $lunas ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400' }}">
            {{ $lunas ? '✓ Lunas' : '⚠ Belum Lunas' }}
        </span>
    </div>

    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 text-green-800 dark:text-green-400 rounded-2xl px-6 py-4 text-sm font-bold">
            ✓ {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-2xl px-6 py-4 text-sm font-bold">
            <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Card 1: Profil Siswa --}}
        <div class="bg-white dark:bg-zinc-900 rounded-[2rem] p-6 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col justify-between">
            <div>
                <h2 class="font-black text-slate-900 dark:text-white mb-4 flex items-center gap-3 text-xs uppercase tracking-wider">
                    <span class="w-8 h-8 rounded-xl bg-blue-100 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </span>
                    Profil Siswa
                </h2>
                <div class="space-y-3">
                    <div class="group">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Nama Lengkap</label>
                        <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $pesertaDidik->nama_lengkap }}</p>
                    </div>
                    <div class="flex justify-between items-center py-2 border-y border-slate-50 dark:border-zinc-800/50">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">NISN</label>
                            <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $pesertaDidik->nisn ?? '-' }}</p>
                        </div>
                        <div class="text-right">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Asal Sekolah</label>
                            <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $pesertaDidik->asal_sekolah }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">WhatsApp Orang Tua</label>
                        <div class="flex flex-col gap-1">
                            <p class="text-xs text-slate-600 dark:text-slate-400 flex justify-between">
                                <span>Ayah:</span>
                                <span class="font-bold text-slate-800 dark:text-slate-200">{{ $pesertaDidik->no_telepon_ayah ?? '-' }}</span>
                            </p>
                            <p class="text-xs text-slate-600 dark:text-slate-400 flex justify-between">
                                <span>Ibu:</span>
                                <span class="font-bold text-slate-800 dark:text-slate-200">{{ $pesertaDidik->no_telepon_ibu ?? '-' }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @if($pesertaDidik->no_telepon)
            <div class="mt-4">
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pesertaDidik->no_telepon) }}" target="_blank" class="w-full inline-flex items-center justify-center gap-2 bg-emerald-50 text-emerald-600 dark:bg-emerald-900/20 dark:text-emerald-400 py-2 rounded-xl text-xs font-black hover:bg-emerald-100 transition">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Chat Siswa
                </a>
            </div>
            @endif
        </div>

        {{-- Card 2: Status Keuangan --}}
        <div class="bg-white dark:bg-zinc-900 rounded-[2rem] p-6 border border-slate-100 dark:border-zinc-800 shadow-sm">
            <h2 class="font-black text-slate-900 dark:text-white mb-4 flex items-center gap-3 text-xs uppercase tracking-wider">
                <span class="w-8 h-8 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </span>
                Status Keuangan
            </h2>
            <div class="space-y-2.5 text-xs">
                <div class="flex justify-between"><span class="text-slate-500">Total Biaya</span><span class="font-bold text-slate-900 dark:text-white">Rp {{ number_format($total,0,',','.') }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Terbayar</span><span class="font-bold text-emerald-600">Rp {{ number_format($terbayar,0,',','.') }}</span></div>
                <div class="pt-2 border-t border-slate-50 dark:border-zinc-800 flex justify-between items-center">
                    <span class="font-black text-slate-900 dark:text-white">Sisa Tagihan</span>
                    @if($lunas)
                        <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 font-black uppercase text-[10px]">LUNAS</span>
                    @else
                        <span class="text-red-500 font-black text-sm">Rp {{ number_format($kekurangan,0,',','.') }}</span>
                    @endif
                </div>
                <div class="flex justify-between mt-1 italic text-slate-400">
                    <span>Jatuh Tempo</span>
                    <span>{{ $pembayaran->batas_waktu?->format('d/m/Y') ?? 'Belum Diatur' }}</span>
                </div>
            </div>
        </div>

        {{-- Card 3: Catat Pembayaran Baru --}}
        <div class="bg-white dark:bg-zinc-900 rounded-[2rem] p-6 border border-slate-100 dark:border-zinc-800 shadow-sm">
            <h2 class="font-black text-slate-900 dark:text-white mb-4 flex items-center gap-3 text-xs uppercase tracking-wider">
                <span class="w-8 h-8 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 flex items-center justify-center">Rp</span>
                Bayar Sekarang
            </h2>
            <form action="{{ route('keuangan.pembayaran.transaksi.store', $pembayaran->id) }}" method="POST" class="space-y-3">
                @csrf
                <div class="relative">
                    <input type="number" name="nominal" min="1" required placeholder="Nominal Rp" class="w-full rounded-xl border-slate-200 dark:border-zinc-700 dark:bg-zinc-950 text-sm px-4 py-2 focus:ring-indigo-500">
                </div>
                <div class="flex gap-2">
                    <select name="tipe_pembayaran" class="flex-1 rounded-xl border-slate-200 dark:border-zinc-700 dark:bg-zinc-950 text-xs px-3 py-2 focus:ring-indigo-500">
                        <option value="TUNAI">TUNAI</option>
                        <option value="TRANSFER">TRANSFER</option>
                    </select>
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-black px-4 py-2 rounded-xl text-xs transition active:scale-95 shrink-0">
                        Bayar
                    </button>
                </div>
            </form>
        </div>

        {{-- Row 2: Settings (1/3) & Riwayat (2/3) --}}
        <div class="lg:col-span-1">
            <form action="{{ route('keuangan.pembayaran.update', $pembayaran->id) }}" method="POST" class="bg-white dark:bg-zinc-900 rounded-[2rem] p-6 border border-slate-100 dark:border-zinc-800 shadow-sm space-y-4">
                @csrf @method('PUT')
                <h2 class="font-black text-slate-900 dark:text-white mb-2 text-xs uppercase tracking-wider">Pengaturan Pembayaran</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Diskon (%)</label>
                        <input type="number" name="diskon_persen" value="{{ old('diskon_persen', $pembayaran->diskon_persen) }}" min="0" max="100" step="0.01" class="w-full rounded-lg border-slate-200 dark:border-zinc-700 dark:bg-zinc-950 text-xs px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Daftar (Rp)</label>
                        <input type="number" name="biaya_pendaftaran" value="{{ old('biaya_pendaftaran', $pembayaran->biaya_pendaftaran) }}" class="w-full rounded-lg border-slate-200 dark:border-zinc-700 dark:bg-zinc-950 text-xs px-3 py-2">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Batas Waktu</label>
                    <input type="date" name="batas_waktu" value="{{ old('batas_waktu', $pembayaran->batas_waktu?->format('Y-m-d')) }}" class="w-full rounded-lg border-slate-200 dark:border-zinc-700 dark:bg-zinc-950 text-xs px-3 py-2">
                </div>
                <button type="submit" class="w-full bg-slate-800 dark:bg-zinc-700 text-white font-black py-2.5 rounded-xl text-xs hover:bg-slate-900 transition">Update Data</button>
            </form>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-zinc-900 rounded-[2rem] border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden h-full">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-zinc-800 flex justify-between items-center">
                    <h2 class="font-black text-slate-900 dark:text-white text-sm">Riwayat Transaksi</h2>
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $pembayaran->transaksi->count() }} Transaksi</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 dark:bg-zinc-800/50 text-[10px] uppercase tracking-wider text-slate-500 font-black">
                            <tr>
                                <th class="px-6 py-3 text-left">No</th>
                                <th class="px-6 py-3 text-left">Tanggal</th>
                                <th class="px-6 py-3 text-right">Nominal</th>
                                <th class="px-6 py-3 text-left">No. Kwitansi</th>
                                <th class="px-6 py-3 text-left">Tipe</th>
                                <th class="px-6 py-3 text-center">Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                            @forelse($pembayaran->transaksi->sortByDesc('tanggal') as $i => $t)
                                <tr class="hover:bg-slate-50 dark:hover:bg-zinc-800/50 transition">
                                    <td class="px-6 py-3 text-slate-400 font-bold">{{ $i + 1 }}</td>
                                    <td class="px-6 py-3 font-medium text-slate-700 dark:text-slate-300">{{ $t->tanggal->format('d/m/Y') }}</td>
                                    <td class="px-6 py-3 text-right font-black text-emerald-600">Rp {{ number_format($t->nominal,0,',','.') }}</td>
                                    <td class="px-6 py-3 font-mono text-[10px] text-slate-500">{{ $t->no_kwitansi }}</td>
                                    <td class="px-6 py-3">
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase {{ $t->tipe_pembayaran === 'TUNAI' ? 'bg-slate-100 text-slate-600 dark:bg-zinc-700' : 'bg-blue-100 text-blue-600 dark:bg-blue-900/30' }}">
                                            {{ $t->tipe_pembayaran }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-center">
                                        <form action="{{ route('keuangan.transaksi.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-600">
                                                <svg class="w-4 h-4 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-6 py-10 text-center text-slate-400 text-xs">Belum ada transaksi pembayaran.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
