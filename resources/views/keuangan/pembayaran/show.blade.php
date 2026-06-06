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

    // Cari pendaftaran awal untuk ambil bukti pembayaran
    $userModel = \App\Models\User::where('peserta_didik_id', $pesertaDidik->id)->first();
    $pendaftaranAwal = $userModel ? \App\Models\Pendaftaran\PendaftaranSiswa::where('email', $userModel->email)->first() : null;
    $buktiPendaftaran = $pendaftaranAwal?->pembayaran?->bukti_pembayaran;
@endphp
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('keuangan.pembayaran.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-indigo-600 mb-3 transition">
                Kembali ke Daftar Siswa
            </a>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">{{ $pesertaDidik->nama_lengkap }}</h1>
            <p class="text-slate-500 text-sm mt-0.5">No. Induk: <span class="font-mono font-bold">{{ $pesertaDidik->nomor_induk ?? '-' }}</span></p>
        </div>
        <span class="self-start sm:self-auto px-4 py-2 rounded-2xl text-sm font-black uppercase
            {{ $lunas ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400' }}">
            {{ $lunas ? '✓ Lunas' : '⚠ Belum Lunas' }}
        </span>
    </div>



    @php
        $pendingTransaksis = $pembayaran->transaksi->where('status', 'PENDING');
    @endphp

    @if($pendingTransaksis->count() > 0)
        <div class="space-y-4 mb-6">
            @foreach($pendingTransaksis as $pt)
                <div class="bg-amber-50/60 dark:bg-zinc-900 border border-amber-200 dark:border-zinc-800 rounded-[2rem] p-6 shadow-sm flex flex-col md:flex-row gap-6 items-start md:items-center justify-between">
                    <div class="flex-1 space-y-2">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-100 dark:bg-amber-900/50 text-amber-800 dark:text-amber-300 text-[10px] font-bold uppercase tracking-wider">
                            <span>⚠ Menunggu Verifikasi Pembayaran</span>
                        </div>
                        <h3 class="text-base font-black text-slate-800 dark:text-white">
                            Konfirmasi Transfer dari Siswa
                        </h3>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs mt-2">
                            <div>
                                <span class="text-slate-400 block font-semibold mb-0.5">Bank Tujuan</span>
                                <span class="font-bold text-slate-700 dark:text-slate-200">{{ $pt->bankTujuan?->nama_bank ?? 'Bank' }} - {{ $pt->bankTujuan?->nomor_rekening ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block font-semibold mb-0.5">Nominal Klaim</span>
                                <span class="font-bold text-slate-750 dark:text-slate-200">Rp {{ number_format($pt->nominal, 0, ',', '.') }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block font-semibold mb-0.5">Tanggal Transfer</span>
                                <span class="font-bold text-slate-700 dark:text-slate-200">{{ \Carbon\Carbon::parse($pt->tanggal)->format('d/m/Y') }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block font-semibold mb-0.5">Catatan Siswa</span>
                                <span class="font-bold text-slate-700 dark:text-slate-200 italic">"{{ $pt->catatan_siswa ?? '-' }}"</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-3 shrink-0 w-full md:w-auto">
                        @if($pt->bukti_pembayaran)
                            <button type="button" onclick="openBuktiModal('{{ asset('storage/' . $pt->bukti_pembayaran) }}')" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold shadow-sm hover:bg-slate-50 transition">
                                <svg class="w-4 h-4 text-[#388782]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                Bukti Transfer
                            </button>
                        @endif

                        <button type="button" onclick="openVerifikasiModal('{{ $pt->id }}', '{{ $pt->nominal }}', 'TRANSFER')" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-[#388782] hover:bg-[#206D6C] text-white rounded-xl text-xs font-bold shadow-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Verifikasi
                        </button>

                        <button type="button" onclick="openTolakModal('{{ $pt->id }}')" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Tolak
                        </button>
                    </div>
                </div>
            @endforeach
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
                {{-- Bagian Bukti Pendaftaran dipindahkan ke kolom Riwayat Transaksi --}}
            </div>
        </div>

        {{-- Card 3: Catat Pembayaran Baru --}}
        <div class="bg-white dark:bg-zinc-900 rounded-[2rem] p-6 border border-slate-100 dark:border-zinc-800 shadow-sm">
            <h2 class="font-black text-slate-900 dark:text-white mb-4 flex items-center gap-3 text-xs uppercase tracking-wider">
                <span class="w-8 h-8 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 flex items-center justify-center">Rp</span>
                Bayar Sekarang
            </h2>
            <form id="form-catat-pembayaran" action="{{ route('keuangan.pembayaran.transaksi.store', $pembayaran->id) }}" method="POST" class="space-y-3">
                @csrf
                @if(!$lunas)
                    <div class="text-[10px] text-amber-600 dark:text-amber-400 font-bold mb-1">
                        Sisa Tagihan: Rp {{ number_format($kekurangan, 0, ',', '.') }},-
                    </div>
                @endif
                <div class="relative mb-2">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Tanggal Transaksi Pembayaran</label>
                    <input type="date" name="tanggal" required value="{{ now()->format('Y-m-d') }}" class="w-full rounded-xl border-slate-200 dark:border-zinc-700 dark:bg-zinc-950 text-sm px-4 py-2 focus:ring-indigo-500">
                </div>
                <div class="relative mb-2">
                    <input type="text" name="nominal" id="catat-nominal" required inputmode="numeric" placeholder="Nominal Rp" class="w-full rounded-xl border-slate-200 dark:border-zinc-700 dark:bg-zinc-950 text-sm px-4 py-2 focus:ring-indigo-500">
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
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Batas Waktu (Jatuh Tempo)</label>
                    <input type="date" name="batas_waktu" value="{{ old('batas_waktu', $pembayaran->batas_waktu?->format('Y-m-d')) }}" class="w-full rounded-lg border-slate-200 dark:border-zinc-700 dark:bg-zinc-950 text-xs px-3 py-2">
                    <span class="block mt-1 text-[9px] text-slate-400 font-medium leading-tight">Tanggal kedaluwarsa paket atau batas akhir pelunasan tagihan.</span>
                </div>
                
                {{-- Toggle Dispensasi --}}
                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-amber-50/50 dark:bg-amber-950/20 border border-amber-100 dark:border-amber-900/30">
                    <div class="space-y-0.5">
                        <label for="dispensasi" class="block text-xs font-black text-amber-800 dark:text-amber-400">Dispensasi Akses</label>
                        <span class="block text-[9px] text-amber-600 dark:text-amber-500 font-medium">Bypass semua blokir jika overdue / belum lunas</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer select-none">
                        <input type="checkbox" name="dispensasi" id="dispensasi" value="1" {{ old('dispensasi', $pembayaran->dispensasi) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-10 h-6 bg-slate-200 dark:bg-zinc-800 rounded-full peer peer-focus:ring-2 peer-focus:ring-amber-300 dark:peer-focus:ring-amber-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-amber-500"></div>
                    </label>
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
                
                @if($buktiPendaftaran)
                <div class="px-6 py-4 border-b border-slate-100 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-800/30">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Bukti Pembayaran Pendaftaran Awal</span>
                            @php $ext = pathinfo($buktiPendaftaran, PATHINFO_EXTENSION); @endphp
                            @if(in_array(strtolower($ext), ['jpg','jpeg','png']))
                                <button type="button" onclick="openBuktiModal('{{ asset('storage/' . $buktiPendaftaran) }}')" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 underline flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    Lihat Bukti Foto
                                </button>
                            @else
                                <a href="{{ asset('storage/' . $buktiPendaftaran) }}" target="_blank" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 underline flex items-center gap-1">
                                    Lihat Dokumen PDF
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 dark:bg-zinc-800/50 text-[10px] uppercase tracking-wider text-slate-500 font-black">
                            <tr>
                                <th class="px-6 py-3 text-left">No</th>
                                <th class="px-6 py-3 text-left">Tanggal</th>
                                <th class="px-6 py-3 text-right">Nominal</th>
                                <th class="px-6 py-3 text-left">No. Kwitansi</th>
                                <th class="px-6 py-3 text-left">Tipe</th>
                                <th class="px-6 py-3 text-center">Status</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                            @forelse($pembayaran->transaksi->sortByDesc('tanggal') as $t)
                                <tr class="hover:bg-slate-50 dark:hover:bg-zinc-800/50 transition">
                                    <td class="px-6 py-3 text-slate-400 font-bold">{{ $pembayaran->transaksi->count() - $loop->index }}</td>
                                    <td class="px-6 py-3 font-medium text-slate-700 dark:text-slate-300 whitespace-nowrap">{{ $t->tanggal->format('d/m/Y') }}</td>
                                    <td class="px-6 py-3 text-right font-black text-emerald-600 whitespace-nowrap">Rp {{ number_format($t->nominal,0,',','.') }}</td>
                                    <td class="px-6 py-3 font-mono text-[10px] text-slate-500 whitespace-nowrap">
                                        {{ str_starts_with($t->no_kwitansi, 'PENDING/') ? '-' : $t->no_kwitansi }}
                                    </td>
                                    <td class="px-6 py-3">
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase {{ $t->tipe_pembayaran === 'TUNAI' ? 'bg-slate-100 text-slate-600 dark:bg-zinc-700' : 'bg-blue-100 text-blue-600 dark:bg-blue-900/30' }}">
                                            {{ $t->tipe_pembayaran }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-center">
                                        @if($t->status === 'PENDING')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-yellow-100 text-yellow-800 dark:bg-yellow-950/30 dark:text-yellow-400">Menunggu</span>
                                        @elseif($t->status === 'SUKSES')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-400">Berhasil</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 dark:bg-rose-950/30 dark:text-rose-400" title="{{ $t->catatan_siswa }}">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 text-center">
                                        <div class="flex flex-col items-center justify-center gap-1.5">
                                            @if($t->bukti_pembayaran)
                                                <button type="button" onclick="openBuktiModal('{{ asset('storage/' . $t->bukti_pembayaran) }}')" class="text-[10px] font-bold text-blue-600 hover:text-blue-700 underline whitespace-nowrap">
                                                    Lihat Bukti
                                                </button>
                                            @else
                                                <span class="text-[10px] text-slate-300">-</span>
                                            @endif
                                            
                                            @if($t->status === 'SUKSES')
                                                <a href="{{ route('keuangan.transaksi.struk', $t->id) }}" target="_blank" class="text-[10px] font-bold text-[#388782] hover:text-[#206D6C] underline whitespace-nowrap">
                                                    Cetak Struk
                                                </a>
                                            @endif
                                        </div>
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

{{-- MODAL BUKTI TRANSFER --}}
<div id="modal-bukti" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300">
    <div class="relative max-w-2xl w-[90%] bg-white dark:bg-zinc-900 rounded-3xl p-6 shadow-2xl scale-95 transition-all duration-300" id="modal-bukti-box">
        <button type="button" onclick="closeBuktiModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 dark:bg-zinc-800 text-slate-500 hover:text-slate-800 dark:hover:text-white flex items-center justify-center transition font-bold text-lg">&times;</button>
        <div class="flex items-center gap-4 mb-4">
            <h3 class="text-lg font-black text-slate-800 dark:text-white">Bukti Pembayaran</h3>
            <div class="flex gap-2">
                <button type="button" onclick="zoomBuktiImage(0.2)" class="px-3 py-1.5 bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-lg hover:bg-slate-200 dark:hover:bg-zinc-700 transition flex items-center gap-1.5" title="Perbesar">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg>
                </button>
                <button type="button" onclick="zoomBuktiImage(-0.2)" class="px-3 py-1.5 bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-lg hover:bg-slate-200 dark:hover:bg-zinc-700 transition flex items-center gap-1.5" title="Perkecil">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7" /></svg>
                </button>
                <button type="button" onclick="rotateBuktiImage()" class="px-3 py-1.5 bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-lg hover:bg-slate-200 dark:hover:bg-zinc-700 transition flex items-center gap-1.5" title="Putar">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                </button>
            </div>
        </div>
        <div class="rounded-2xl overflow-auto border border-slate-100 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 flex items-center justify-center relative max-h-[70vh]">
            <img src="" id="bukti-img-preview" class="w-auto object-contain transition-transform duration-300 cursor-move" alt="Bukti Transfer">
        </div>
    </div>
</div>

{{-- MODAL VERIFIKASI PEMBAYARAN --}}
<div id="modal-verifikasi" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300">
    <div class="relative max-w-md w-[90%] bg-white dark:bg-zinc-900 rounded-3xl p-6 shadow-2xl scale-95 transition-all duration-300" id="modal-verifikasi-box">
        <button type="button" onclick="closeVerifikasiModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 dark:bg-zinc-800 text-slate-500 hover:text-slate-800 dark:hover:text-white flex items-center justify-center transition font-bold text-lg">&times;</button>
        <h3 class="text-lg font-black text-slate-800 dark:text-white mb-2">Verifikasi Pembayaran</h3>
        <p class="text-xs text-slate-400 mb-4 leading-relaxed">
            Periksa kembali nominal uang yang masuk di mutasi bank. Anda dapat menyesuaikan nominal di bawah ini jika terdapat perbedaan.
        </p>

        {{-- Sisa Tagihan Info --}}
        <div class="mb-4 bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/50 rounded-2xl p-4 flex items-center justify-between">
            <span class="text-xs font-bold text-amber-800 dark:text-amber-300">Sisa Tagihan Siswa</span>
            <span class="font-black text-amber-900 dark:text-amber-200 text-sm">
                Rp {{ number_format($kekurangan, 0, ',', '.') }},-
            </span>
        </div>

        <form id="form-verifikasi" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="modal-verif-nominal" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nominal Terverifikasi (Rp)</label>
                <input type="text" name="nominal" id="modal-verif-nominal" required inputmode="numeric" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-[#388782] focus:border-transparent text-sm">
            </div>
            <div>
                <label for="modal-verif-tipe" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Tipe Pembayaran</label>
                <select name="tipe_pembayaran" id="modal-verif-tipe" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-[#388782] focus:border-transparent text-sm">
                    <option value="TRANSFER">TRANSFER</option>
                    <option value="TUNAI">TUNAI</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeVerifikasiModal()" class="flex-1 py-2.5 border border-slate-200 dark:border-zinc-700 text-slate-500 dark:text-slate-300 rounded-xl text-xs font-bold hover:bg-slate-50 dark:hover:bg-zinc-800 transition">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-[#388782] hover:bg-[#206D6C] text-white rounded-xl text-xs font-bold shadow-md shadow-[#388782]/20 transition">Verifikasi & Setujui</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL TOLAK PEMBAYARAN --}}
<div id="modal-tolak" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300">
    <div class="relative max-w-md w-[90%] bg-white dark:bg-zinc-900 rounded-3xl p-6 shadow-2xl scale-95 transition-all duration-300" id="modal-tolak-box">
        <button type="button" onclick="closeTolakModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 dark:bg-zinc-800 text-slate-500 hover:text-slate-800 dark:hover:text-white flex items-center justify-center transition font-bold text-lg">&times;</button>
        <h3 class="text-lg font-black text-slate-800 dark:text-white mb-2 text-rose-600">Tolak Pengajuan Pembayaran</h3>
        <p class="text-xs text-slate-400 mb-4 leading-relaxed">
            Berikan alasan penolakan agar siswa dapat mengetahui kendala pada pengajuan pembayarannya.
        </p>
        <form id="form-tolak" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="modal-tolak-catatan" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Alasan Penolakan (Opsional)</label>
                <textarea name="catatan_penolakan" id="modal-tolak-catatan" rows="3" placeholder="Contoh: Bukti transfer buram atau tidak valid" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent text-sm"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeTolakModal()" class="flex-1 py-2.5 border border-slate-200 dark:border-zinc-700 text-slate-500 dark:text-slate-300 rounded-xl text-xs font-bold hover:bg-slate-50 dark:hover:bg-zinc-800 transition">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-md shadow-rose-900/20 transition">Tolak Pengajuan</button>
            </div>
        </form>
    </div>
</div>

<script>
    const baseVerifikasiUrl = "{{ route('keuangan.transaksi.verifikasi', ':id') }}";
    const baseTolakUrl = "{{ route('keuangan.transaksi.tolak', ':id') }}";
    
    let currentRotation = 0;
    let currentScale = 1;

    function applyImageTransform() {
        const img = document.getElementById('bukti-img-preview');
        img.style.transform = `rotate(${currentRotation}deg) scale(${currentScale})`;
    }

    function rotateBuktiImage() {
        currentRotation += 90;
        if (currentRotation >= 360) currentRotation = 0;
        applyImageTransform();
    }

    function zoomBuktiImage(factor) {
        currentScale += factor;
        if (currentScale < 0.5) currentScale = 0.5;
        if (currentScale > 3) currentScale = 3;
        applyImageTransform();
    }

    function openBuktiModal(src) {
        currentRotation = 0;
        currentScale = 1;
        const m = document.getElementById('modal-bukti');
        const box = document.getElementById('modal-bukti-box');
        const img = document.getElementById('bukti-img-preview');
        applyImageTransform();
        img.src = src;
        m.classList.remove('opacity-0', 'pointer-events-none');
        box.classList.remove('scale-95');
    }
    function closeBuktiModal() {
        const m = document.getElementById('modal-bukti');
        const box = document.getElementById('modal-bukti-box');
        m.classList.add('opacity-0', 'pointer-events-none');
        box.classList.add('scale-95');
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

    function openVerifikasiModal(id, nominal, tipe) {
        const m = document.getElementById('modal-verifikasi');
        const box = document.getElementById('modal-verifikasi-box');
        const form = document.getElementById('form-verifikasi');
        const inputNominal = document.getElementById('modal-verif-nominal');
        const selectTipe = document.getElementById('modal-verif-tipe');

        form.action = baseVerifikasiUrl.replace(':id', id);
        inputNominal.value = formatRupiah(nominal.toString());
        selectTipe.value = tipe;

        m.classList.remove('opacity-0', 'pointer-events-none');
        box.classList.remove('scale-95');
    }
    function closeVerifikasiModal() {
        const m = document.getElementById('modal-verifikasi');
        const box = document.getElementById('modal-verifikasi-box');
        m.classList.add('opacity-0', 'pointer-events-none');
        box.classList.add('scale-95');
    }

    function openTolakModal(id) {
        const m = document.getElementById('modal-tolak');
        const box = document.getElementById('modal-tolak-box');
        const form = document.getElementById('form-tolak');

        form.action = baseTolakUrl.replace(':id', id);

        m.classList.remove('opacity-0', 'pointer-events-none');
        box.classList.remove('scale-95');
    }
    function closeTolakModal() {
        const m = document.getElementById('modal-tolak');
        const box = document.getElementById('modal-tolak-box');
        m.classList.add('opacity-0', 'pointer-events-none');
        box.classList.add('scale-95');
    }

    // Event listener format rupiah nominal konfirmasi keuangan (admin)
    const verifNominalInput = document.getElementById('modal-verif-nominal');
    if (verifNominalInput) {
        verifNominalInput.addEventListener('input', function(e) {
            this.value = formatRupiah(this.value);
        });
    }

    // Bersihkan titik sebelum submit form verifikasi keuangan (admin)
    const formVerif = document.getElementById('form-verifikasi');
    if (formVerif) {
        formVerif.addEventListener('submit', function(e) {
            const input = document.getElementById('modal-verif-nominal');
            if (input) {
                input.value = input.value.replace(/\./g, '');
            }
        });
    }

    // Event listener format rupiah nominal pencatatan keuangan baru
    const catatNominalInput = document.getElementById('catat-nominal');
    if (catatNominalInput) {
        catatNominalInput.addEventListener('input', function(e) {
            this.value = formatRupiah(this.value);
        });
    }

    // Bersihkan titik sebelum submit form catat pembayaran
    const formCatat = document.getElementById('form-catat-pembayaran');
    if (formCatat) {
        formCatat.addEventListener('submit', function(e) {
            const input = document.getElementById('catat-nominal');
            if (input) {
                input.value = input.value.replace(/\./g, '');
            }
        });
    }
</script>
@endsection

