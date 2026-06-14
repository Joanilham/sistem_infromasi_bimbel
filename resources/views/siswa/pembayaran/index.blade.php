@extends('layouts.siswa')
@section('title', 'Pembayaran')

@section('content')
@php
    $lunas = $pembayaran ? $pembayaran->lunas : false;
    $totalTagihan = $pembayaran ? $pembayaran->total_harus_dibayar : ($pesertaDidik->paketBimbingan?->nominal ?? 0);
    $totalTerbayar = $pembayaran ? $pembayaran->total_terbayar : 0;
    $kekurangan = $pembayaran ? $pembayaran->kekurangan : $totalTagihan;
@endphp

<div class="pb-36">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-2 tracking-tight">Pembayaran</h1>
        <div class="w-20 h-1.5 bg-[#388782] rounded-full mb-6"></div>


        @if($lunas)
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 dark:bg-emerald-900/20 dark:border-emerald-800/30 dark:text-emerald-400 p-4 rounded-xl text-base font-medium flex items-center justify-between">
                <div>
                    Tagihan sudah lunas. Terima kasih.
                </div>
                <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-800/50 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
            </div>
        @else
            <div class="bg-amber-50 border border-amber-100 text-amber-800 dark:bg-amber-900/20 dark:border-amber-800/30 dark:text-amber-400 p-4 rounded-xl text-base font-medium flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    Anda memiliki tagihan sebesar <strong class="text-amber-900 dark:text-amber-300">Rp {{ number_format($kekurangan, 0, ',', '.') }},-</strong> yang belum terbayar. Silakan lakukan pembayaran ke bagian administrasi.
                </div>
                <button type="button" onclick="document.getElementById('modal-bayar').classList.add('aktif')" class="whitespace-nowrap inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#388782] text-white rounded-xl font-bold text-sm shadow-md hover:bg-[#206D6C] hover:shadow-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    Bayar Sekarang
                </button>
            </div>
        @endif
    </div>

    <div class="space-y-10">
        {{-- DAFTAR TAGIHAN --}}
        <div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-4">Daftar Tagihan</h2>
            
            {{-- Desktop Table View --}}
            <div class="hidden md:block bg-white dark:bg-zinc-900 rounded-[1.5rem] border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 dark:bg-zinc-800/50 text-[11px] uppercase tracking-wider text-slate-500 font-bold border-b border-slate-100 dark:border-zinc-800">
                            <tr>
                                <th class="px-6 py-4 text-left">Deskripsi / Paket Bimbingan</th>
                                <th class="px-6 py-4 text-right">Nominal Biaya</th>
                                <th class="px-6 py-4 text-right">Dibayar</th>
                                <th class="px-6 py-4 text-center">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                            <tr class="hover:bg-slate-50 dark:hover:bg-zinc-800/30 transition">
                                <td class="px-6 py-4 font-semibold text-slate-700 dark:text-slate-300">
                                    {{ $pesertaDidik->paketBimbingan?->nama_paket ?? 'Paket Bimbel' }}
                                </td>
                                <td class="px-6 py-4 text-right text-slate-600 dark:text-slate-400">
                                    Rp {{ number_format($totalTagihan, 0, ',', '.') }},-
                                </td>
                                <td class="px-6 py-4 text-right text-slate-600 dark:text-slate-400">
                                    Rp {{ number_format($totalTerbayar, 0, ',', '.') }},-
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($lunas)
                                        <span class="text-emerald-600 font-semibold">Lunas</span>
                                    @else
                                        <span class="text-amber-600 font-semibold">Belum Lunas</span>
                                    @endif
                                </td>
                            </tr>
                            
                            {{-- Row Tagihan Belum Terbayar --}}
                            <tr class="bg-slate-50/50 dark:bg-zinc-800/20">
                                <td colspan="2" class="px-6 py-4 text-left text-slate-500 font-medium">
                                    Tagihan Belum Terbayar
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-slate-800 dark:text-white">
                                    Rp {{ number_format($kekurangan, 0, ',', '.') }},-
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Mobile Card View --}}
            <div class="block md:hidden space-y-4">
                <div class="bg-white dark:bg-zinc-900 border border-slate-100 dark:border-zinc-800 rounded-3xl p-5 shadow-sm space-y-4">
                    <div class="flex justify-between items-start border-b border-slate-100 dark:border-zinc-800 pb-3">
                        <div>
                            <div class="text-[10px] font-black uppercase tracking-widest text-[#388782] dark:text-[#A2D5CB] mb-1">Paket Bimbingan</div>
                            <h4 class="font-extrabold text-slate-800 dark:text-white text-base">
                                {{ $pesertaDidik->paketBimbingan?->nama_paket ?? 'Paket Bimbel' }}
                            </h4>
                        </div>
                        <div>
                            @if($lunas)
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 text-xs font-black">Lunas</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 text-xs font-black">Belum Lunas</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 text-sm pt-1">
                        <div>
                            <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-0.5">Nominal Biaya</div>
                            <div class="font-extrabold text-slate-700 dark:text-slate-300">
                                Rp {{ number_format($totalTagihan, 0, ',', '.') }},-
                            </div>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-0.5">Sudah Dibayar</div>
                            <div class="font-extrabold text-slate-700 dark:text-slate-300">
                                Rp {{ number_format($totalTerbayar, 0, ',', '.') }},-
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 dark:bg-zinc-800/40 rounded-2xl p-4 flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-550 dark:text-slate-400">Belum Terbayar</span>
                        <span class="font-black text-slate-800 dark:text-white text-base">
                            Rp {{ number_format($kekurangan, 0, ',', '.') }},-
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIWAYAT PEMBAYARAN --}}
        <div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-4">Riwayat Pembayaran</h2>
            
            {{-- Desktop Table View --}}
            <div class="hidden md:block bg-white dark:bg-zinc-900 rounded-[1.5rem] border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 dark:bg-zinc-800/50 text-[11px] uppercase tracking-wider text-slate-500 font-bold border-b border-slate-100 dark:border-zinc-800">
                            <tr>
                                <th class="px-6 py-4 text-left">Tanggal Bayar</th>
                                <th class="px-6 py-4 text-left">Jumlah</th>
                                <th class="px-6 py-4 text-left">No. Kwitansi</th>
                                <th class="px-6 py-4 text-left">Tipe</th>
                                <th class="px-6 py-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                            @if($pembayaran && $pembayaran->transaksi->count() > 0)
                                @foreach($pembayaran->transaksi as $transaksi)
                                <tr class="hover:bg-slate-50 dark:hover:bg-zinc-800/30 transition">
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-400">
                                        {{ \Carbon\Carbon::parse($transaksi->tanggal)->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-700 dark:text-slate-300">
                                        Rp {{ number_format($transaksi->nominal, 0, ',', '.') }},-
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-400 font-mono text-xs">
                                        {{ str_starts_with($transaksi->no_kwitansi, 'PENDING/') ? '-' : $transaksi->no_kwitansi }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-400">
                                        Pembayaran {{ ucfirst(strtolower($transaksi->tipe_pembayaran)) }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($transaksi->status === 'PENDING')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 dark:bg-yellow-950/30 dark:text-yellow-400">Menunggu Verifikasi</span>
                                        @elseif($transaksi->status === 'SUKSES')
                                            <div class="flex items-center justify-center gap-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-400">Berhasil</span>
                                                <a href="{{ route('siswa.pembayaran.nota', $transaksi->id) }}" target="_blank" title="Unduh Nota" class="inline-flex items-center justify-center w-7 h-7 bg-slate-100 hover:bg-[#388782] text-slate-500 hover:text-white rounded-lg transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                </a>
                                            </div>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 dark:bg-rose-950/30 dark:text-rose-400">Ditolak</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-slate-400 italic">
                                        Belum ada riwayat pembayaran.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Mobile Card List View --}}
            <div class="block md:hidden space-y-3">
                @if($pembayaran && $pembayaran->transaksi->count() > 0)
                    @foreach($pembayaran->transaksi as $transaksi)
                    <div class="bg-white dark:bg-zinc-900 border border-slate-100 dark:border-zinc-800 rounded-2xl p-4 shadow-sm flex items-center gap-4 transition hover:border-[#388782]/20">
                        <div class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-zinc-800 text-[#388782] dark:text-[#A2D5CB] flex items-center justify-center shrink-0 shadow-inner">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-xs font-bold text-slate-550 dark:text-slate-400">
                                    {{ \Carbon\Carbon::parse($transaksi->tanggal)->translatedFormat('d M Y') }}
                                </span>
                                @if($transaksi->status === 'PENDING')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-yellow-100 text-yellow-800 dark:bg-yellow-950/30 dark:text-yellow-400">Menunggu</span>
                                @elseif($transaksi->status === 'SUKSES')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-400">Berhasil</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-rose-100 text-rose-800 dark:bg-rose-950/30 dark:text-rose-400">Ditolak</span>
                                @endif
                            </div>
                            <div class="flex items-center justify-between gap-2 mt-1">
                                <div class="flex items-center gap-2">
                                    <h4 class="font-extrabold text-slate-800 dark:text-white text-sm">
                                        Rp {{ number_format($transaksi->nominal, 0, ',', '.') }},-
                                    </h4>
                                    @if($transaksi->status === 'SUKSES')
                                        <a href="{{ route('siswa.pembayaran.nota', $transaksi->id) }}" target="_blank" class="w-6 h-6 flex items-center justify-center bg-slate-100 hover:bg-[#388782] text-slate-400 hover:text-white rounded-full transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        </a>
                                    @endif
                                </div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-[#388782] dark:text-[#A2D5CB]">
                                    {{ ucfirst(strtolower($transaksi->tipe_pembayaran)) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="bg-white dark:bg-zinc-900 border border-slate-100 dark:border-zinc-800 rounded-2xl p-8 text-center text-slate-400 font-bold text-xs italic">
                        Belum ada riwayat pembayaran.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- MODAL PEMBAYARAN --}}
<style>
    .modal-overlay {
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px);
        z-index: 9999; display: flex; align-items: center; justify-content: center;
        opacity: 0; pointer-events: none; transition: all 0.3s;
    }
    .modal-overlay.aktif {
        opacity: 1; pointer-events: auto;
    }
    .modal-box {
        background: white; border-radius: 24px; width: 90%; max-width: 480px;
        padding: 24px; transform: scale(0.95) translateY(10px);
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        max-height: 90vh; overflow-y: auto;
    }
    .dark .modal-box { background: #18181b; border: 1px solid #27272a; }
    .modal-overlay.aktif .modal-box { transform: scale(1) translateY(0); }
</style>

<div class="modal-overlay" id="modal-bayar">
    <div class="modal-box relative">
        <button onclick="document.getElementById('modal-bayar').classList.remove('aktif')" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 dark:bg-zinc-800 text-slate-500 hover:bg-slate-200 dark:hover:text-white transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <div class="w-12 h-12 rounded-full bg-teal-100 text-[#388782] dark:bg-teal-900/30 flex items-center justify-center mb-4 mx-auto">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>

        <h3 class="text-lg font-black text-slate-800 dark:text-white text-center mb-1">Konfirmasi Pembayaran</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 text-center mb-5 leading-relaxed">
            Pilih rekening tujuan, isi nominal pembayaran, dan unggah foto bukti transfer Anda.
        </p>

        {{-- Sisa Tagihan Info --}}
        <div class="mb-4 bg-amber-50/50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/50 rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 transition duration-300">
            <span class="text-xs font-black text-amber-800 dark:text-amber-400 uppercase tracking-wider">Jumlah Harus Dibayar (Sisa)</span>
            <span class="font-black text-amber-900 dark:text-amber-300 text-xl tracking-tight">
                Rp {{ number_format($kekurangan, 0, ',', '.') }},-
            </span>
        </div>

        <form id="form-confirm-payment" action="{{ route('siswa.pembayaran.konfirmasi') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <div>
                <label for="bank_tujuan_id" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Pilih Rekening Bank Tujuan</label>
                <select name="bank_tujuan_id" id="bank_tujuan_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-[#388782] focus:border-transparent text-sm">
                    <option value="" disabled selected>-- Pilih Bank --</option>
                    @foreach($banks as $bank)
                        <option value="{{ $bank->id }}">{{ $bank->nama_bank }} - {{ $bank->nomor_rekening }} (a.n. {{ $bank->atas_nama }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="nominal" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Nominal Pembayaran (Rp)</label>
                <input type="text" name="nominal" id="nominal" required inputmode="numeric" value="{{ $kekurangan }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-[#388782] focus:border-transparent text-sm">
                
                {{-- Dynamic Sisa Tagihan Feedback --}}
                <div id="nominal-feedback" class="mt-2 text-xs font-semibold text-slate-550 dark:text-slate-400 flex flex-col gap-1 transition-all duration-300">
                    <div id="feedback-sisa" class="flex justify-between items-center bg-slate-50 dark:bg-zinc-800/40 p-2.5 rounded-xl border border-slate-100 dark:border-zinc-800/50">
                        <span>Sisa kekurangan setelah pembayaran:</span>
                        <span id="sisa-kekurangan-text" class="font-extrabold text-[#388782] dark:text-[#A2D5CB]">Rp 0</span>
                    </div>
                    <div id="feedback-warning" class="hidden text-rose-600 dark:text-rose-450 font-bold flex items-center gap-1.5 mt-1 bg-rose-50 dark:bg-rose-950/20 p-2.5 rounded-xl border border-rose-100 dark:border-rose-900/30">
                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span>Nominal tidak boleh melebihi sisa tagihan! Maksimal Rp {{ number_format($kekurangan, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <input type="hidden" name="tanggal" id="tanggal" value="{{ date('Y-m-d') }}">

            <div>
                <label for="catatan_siswa" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Catatan Tambahan (Opsional)</label>
                <textarea name="catatan_siswa" id="catatan_siswa" rows="2" placeholder="Contoh: Pembayaran cicilan ke-2" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-[#388782] focus:border-transparent text-sm"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Unggah Bukti Pembayaran</label>
                <div class="flex items-center justify-center w-full">
                    <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-slate-200 dark:border-zinc-700 rounded-xl cursor-pointer hover:bg-slate-50 dark:hover:bg-zinc-800/50 transition">
                        <div class="flex flex-col items-center justify-center pt-3 pb-3">
                            <svg class="w-6 h-6 text-slate-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">Klik untuk pilih gambar (Maks 2MB)</p>
                        </div>
                        <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" required class="hidden" accept="image/*" onchange="showFilePreview(this)">
                    </label>
                </div>
                <div id="file-preview-container" class="mt-2 hidden">
                    <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        <span id="file-name-preview">Bukti Pembayaran Terpilih</span>
                    </span>
                </div>
            </div>

            <button type="submit" id="btn-submit-payment" class="w-full flex items-center justify-center gap-2 bg-[#388782] hover:bg-[#206D6C] text-white rounded-xl py-3 font-bold shadow-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                Kirim Konfirmasi
            </button>
        </form>

        @php
            $waNum = $master?->wa_number ?? '6281234567890';
            $pesan = "Halo Admin, saya " . $pesertaDidik->nama_lengkap . " (NISN: " . $pesertaDidik->nisn . "), ingin mengajukan keluhan / pertanyaan mengenai pembayaran tagihan LBB GeniusEdu.";
            $waLink = "https://wa.me/" . preg_replace('/[^0-9]/', '', $waNum) . "?text=" . urlencode($pesan);
        @endphp

        <div class="mt-4 pt-3 border-t border-slate-100 dark:border-zinc-800 text-center">
            <a href="{{ $waLink }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-slate-550 dark:text-slate-400 hover:text-[#388782] transition font-bold">
                <svg class="w-4 h-4 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                Ada Pertanyaan/Keluhan? Hubungi WA Admin
            </a>
        </div>
    </div>
</div>

<script>
    function showFilePreview(input) {
        const container = document.getElementById('file-preview-container');
        const namePreview = document.getElementById('file-name-preview');
        if (input.files && input.files[0]) {
            namePreview.textContent = 'Bukti Pembayaran Terpilih: ' + input.files[0].name;
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }

    // Fungsi Format Rupiah (Thousand Separator)
    function formatRupiah(angka) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            var separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return rupiah;
    }

    // Event listener format rupiah nominal konfirmasi siswa & batasan pembayaran
    const nominalInput = document.getElementById('nominal');
    const maxKekurangan = parseInt("{{ $kekurangan }}") || 0;
    const btnSubmit = document.getElementById('btn-submit-payment');
    const feedbackSisa = document.getElementById('feedback-sisa');
    const sisaText = document.getElementById('sisa-kekurangan-text');
    const feedbackWarning = document.getElementById('feedback-warning');

    function updatePaymentFeedback() {
        if (!nominalInput) return;
        
        // Ambil nominal mentah (tanpa titik)
        const rawValue = nominalInput.value.replace(/\./g, '');
        const inputVal = parseInt(rawValue) || 0;
        
        const remaining = maxKekurangan - inputVal;
        
        if (inputVal > maxKekurangan) {
            // Lebih bayar
            feedbackWarning.classList.remove('hidden');
            feedbackSisa.classList.add('hidden');
            if (btnSubmit) {
                btnSubmit.disabled = true;
                btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
                btnSubmit.classList.remove('hover:bg-[#206D6C]');
            }
        } else {
            // Normal
            feedbackWarning.classList.add('hidden');
            feedbackSisa.classList.remove('hidden');
            if (sisaText) {
                sisaText.textContent = remaining >= 0 ? 'Rp ' + formatRupiah(remaining.toString()) : 'Rp 0';
            }
            if (btnSubmit) {
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
                btnSubmit.classList.add('hover:bg-[#206D6C]');
            }
        }
    }

    if (nominalInput) {
        nominalInput.addEventListener('input', function(e) {
            this.value = formatRupiah(this.value);
            updatePaymentFeedback();
        });
        // Format nominal awal saat halaman termuat & update feedback
        if (nominalInput.value) {
            nominalInput.value = formatRupiah(nominalInput.value);
            updatePaymentFeedback();
        }
    }

    // Bersihkan titik sebelum submit form & proteksi double submit
    const formConfirm = document.getElementById('form-confirm-payment');
    const btnSubmitReal = document.getElementById('btn-submit-payment');
    
    if (formConfirm) {
        formConfirm.addEventListener('submit', function(e) {
            // Prevent double submission
            if (btnSubmitReal.hasAttribute('data-submitted')) {
                e.preventDefault();
                return;
            }
            
            btnSubmitReal.setAttribute('data-submitted', 'true');
            btnSubmitReal.classList.add('opacity-75', 'cursor-not-allowed');
            btnSubmitReal.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...`;

            const input = document.getElementById('nominal');
            if (input) {
                input.value = input.value.replace(/\./g, '');
            }
        });
    }
</script>
@endsection
