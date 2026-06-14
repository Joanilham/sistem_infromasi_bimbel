@extends('layouts.admin')
@section('title', 'Detail Pendaftaran')

@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('admin.pendaftaran.index') }}" class="p-2 rounded-xl bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-500 hover:text-slate-800 transition-colors">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Detail Pendaftaran</h1>
        <p class="text-sm text-slate-500">{{ $pendaftaran->nama_lengkap }} · {{ $pendaftaran->email }}</p>
    </div>
    <div class="ml-auto">
        @php $s = $pendaftaran->status; @endphp
        <span class="px-3 py-1.5 rounded-xl text-sm font-bold
            {{ $s === 'diverifikasi' ? 'bg-emerald-100 text-emerald-700' : ($s === 'ditolak' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">
            {{ $s === 'menunggu' ? '⏳ Menunggu Verifikasi' : ($s === 'diverifikasi' ? '✅ Diverifikasi' : '❌ Ditolak') }}
        </span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- DATA SISWA --}}
    <div class="lg:col-span-2 space-y-5">
        {{-- Data Pribadi --}}
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-zinc-800">
            <h3 class="text-xs font-bold uppercase tracking-widest text-indigo-500 mb-4">Data Pribadi</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><span class="text-slate-400 block">Nama Lengkap</span><span class="font-semibold text-slate-800 dark:text-white">{{ $pendaftaran->nama_lengkap }}</span></div>
                <div><span class="text-slate-400 block">NISN</span><span class="font-semibold text-slate-800 dark:text-white">{{ $pendaftaran->nisn ?? '-' }}</span></div>
                <div><span class="text-slate-400 block">Jenis Kelamin</span><span class="font-semibold text-slate-800 dark:text-white">{{ $pendaftaran->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span></div>
                <div><span class="text-slate-400 block">Tempat / Tgl Lahir</span><span class="font-semibold text-slate-800 dark:text-white">{{ $pendaftaran->tempat_lahir ?? '-' }}{{ $pendaftaran->tanggal_lahir ? ', ' . $pendaftaran->tanggal_lahir->format('d M Y') : '' }}</span></div>
                <div><span class="text-slate-400 block">Agama</span><span class="font-semibold text-slate-800 dark:text-white">{{ $pendaftaran->agama ?? '-' }}</span></div>
                <div><span class="text-slate-400 block">No. Telepon</span><span class="font-semibold text-slate-800 dark:text-white">{{ $pendaftaran->no_telepon ?? '-' }}</span></div>
                <div class="col-span-2"><span class="text-slate-400 block">Alamat</span><span class="font-semibold text-slate-800 dark:text-white">{{ $pendaftaran->alamat_lengkap ?? '-' }}</span></div>
            </div>
        </div>

        {{-- Data Akademik --}}
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-zinc-800">
            <h3 class="text-xs font-bold uppercase tracking-widest text-indigo-500 mb-4">Data Akademik</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><span class="text-slate-400 block">Asal Sekolah</span><span class="font-semibold text-slate-800 dark:text-white">{{ $pendaftaran->asal_sekolah }}</span></div>
                <div><span class="text-slate-400 block">Paket Bimbingan</span><span class="font-semibold text-slate-800 dark:text-white">{{ $pendaftaran->paketBimbingan?->nama_paket ?? '-' }} @if($pendaftaran->paketBimbingan?->nominal) (Rp {{ number_format($pendaftaran->paketBimbingan->nominal, 0, ',', '.') }},-) @endif</span></div>
                <div><span class="text-slate-400 block">Kelompok Belajar</span><span class="font-semibold text-slate-800 dark:text-white">{{ $pendaftaran->kelompokBelajar?->nama_kelompok ?? '-' }}</span></div>
                <div><span class="text-slate-400 block">Info dari</span><span class="font-semibold text-slate-800 dark:text-white">{{ $pendaftaran->informasi_dari ?? '-' }}</span></div>
            </div>
        </div>

        {{-- Data Orang Tua --}}
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-zinc-800">
            <h3 class="text-xs font-bold uppercase tracking-widest text-indigo-500 mb-4">Data Orang Tua / Wali</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><span class="text-slate-400 block">Nama Ayah</span><span class="font-semibold text-slate-800 dark:text-white">{{ $pendaftaran->nama_ayah ?? '-' }}</span></div>
                <div><span class="text-slate-400 block">Pekerjaan Ayah</span><span class="font-semibold text-slate-800 dark:text-white">{{ $pendaftaran->pekerjaan_ayah ?? '-' }}</span></div>
                <div><span class="text-slate-400 block">No. Telepon Ayah</span><span class="font-semibold text-slate-800 dark:text-white">{{ $pendaftaran->no_telepon_ayah ?? '-' }}</span></div>
                <div><span class="text-slate-400 block">Nama Ibu</span><span class="font-semibold text-slate-800 dark:text-white">{{ $pendaftaran->nama_ibu ?? '-' }}</span></div>
                <div><span class="text-slate-400 block">Pekerjaan Ibu</span><span class="font-semibold text-slate-800 dark:text-white">{{ $pendaftaran->pekerjaan_ibu ?? '-' }}</span></div>
                <div><span class="text-slate-400 block">No. Telepon Ibu</span><span class="font-semibold text-slate-800 dark:text-white">{{ $pendaftaran->no_telepon_ibu ?? '-' }}</span></div>
            </div>
        </div>
    </div>

    {{-- SIDEBAR KANAN --}}
    <div class="space-y-5">
        {{-- Bukti Pembayaran --}}
        <div id="bukti-pembayaran" class="bg-white dark:bg-zinc-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-zinc-800 transition-all duration-700">
            <h3 class="text-xs font-bold uppercase tracking-widest text-indigo-500 mb-4">Bukti Pembayaran</h3>
            @if($pendaftaran->pembayaran)
                <div class="text-sm space-y-2 mb-4">
                    <div class="flex justify-between"><span class="text-slate-400">Metode</span><span class="font-semibold">{{ $pendaftaran->pembayaran->metode_pembayaran }}</span></div>
                    
                    @if($pendaftaran->pembayaran->jenis_bayar === 'dp')
                        <div class="flex justify-between">
                            <span class="text-slate-400">Jenis Bayar</span>
                            <span class="px-2 py-0.5 rounded-lg text-xs font-bold bg-amber-100 text-amber-700">DP (Minimal {{ $pendaftaran->pembayaran->jumlah ? number_format($pendaftaran->pembayaran->jumlah, 0, ',', '.') : '-' }})</span>
                        </div>
                    @else
                        <div class="flex justify-between">
                            <span class="text-slate-400">Jenis Bayar</span>
                            <span class="px-2 py-0.5 rounded-lg text-xs font-bold bg-indigo-100 text-indigo-700">Lunas / Full</span>
                        </div>
                    @endif

                    @if($pendaftaran->pembayaran->jumlah)
                    <div class="flex justify-between"><span class="text-slate-400">Jumlah Dibayar</span><span class="font-semibold text-emerald-600">Rp {{ number_format($pendaftaran->pembayaran->jumlah, 0, ',', '.') }}</span></div>
                    @endif
                    <div class="flex justify-between"><span class="text-slate-400">Status</span>
                        @php $ps = $pendaftaran->pembayaran->status; @endphp
                        <span class="px-2 py-0.5 rounded-lg text-xs font-bold {{ $ps === 'dikonfirmasi' ? 'bg-emerald-100 text-emerald-700' : ($ps === 'ditolak' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">{{ ucfirst($ps) }}</span>
                    </div>
                </div>
                @if($pendaftaran->pembayaran->bukti_pembayaran)
                    @php $ext = pathinfo($pendaftaran->pembayaran->bukti_pembayaran, PATHINFO_EXTENSION); @endphp
                    @if(in_array(strtolower($ext), ['jpg','jpeg','png']))
                        <div class="relative group rounded-xl overflow-hidden border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 transition-all hover:shadow-md">
                            <a href="{{ asset('storage/' . $pendaftaran->pembayaran->bukti_pembayaran) }}" target="_blank" class="block">
                                <img src="{{ asset('storage/' . $pendaftaran->pembayaran->bukti_pembayaran) }}" 
                                     class="w-full object-cover transition-transform duration-300 group-hover:scale-105" 
                                     style="max-height: 280px;" 
                                     alt="Bukti Pembayaran"
                                     onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'flex flex-col items-center justify-center h-40 text-slate-400 text-sm gap-2\'><svg class=\'w-10 h-10\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\'/></svg>Gambar tidak tersedia</div>';">
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <span class="text-white text-sm font-semibold flex items-center gap-2 bg-black/50 px-4 py-2 rounded-full backdrop-blur-sm">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                        Perbesar
                                    </span>
                                </div>
                            </a>
                        </div>
                    @else
                        <a href="{{ asset('storage/' . $pendaftaran->pembayaran->bukti_pembayaran) }}" target="_blank" class="flex items-center gap-2 px-4 py-3 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl text-indigo-700 dark:text-indigo-400 text-sm font-semibold">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Lihat PDF
                        </a>
                    @endif
                @endif
            @else
                <p class="text-sm text-slate-400 italic">Belum ada bukti pembayaran.</p>
            @endif
        </div>

        {{-- Aksi Verifikasi --}}
        @if($pendaftaran->status === 'menunggu')
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-zinc-800">
            <h3 class="text-xs font-bold uppercase tracking-widest text-indigo-500 mb-4">Verifikasi Pendaftaran</h3>

            <div class="flex gap-4 mt-2">
                {{-- Tombol TOLAK → buka modal tolak --}}
                <button type="button" onclick="bukaModal('tolak')"
                    class="flex-1 py-3 rounded-xl bg-white dark:bg-zinc-800 text-rose-600 dark:text-rose-400 font-bold text-sm hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-all border-2 border-rose-200 dark:border-rose-800/50 hover:border-rose-300 dark:hover:border-rose-700 shadow-sm">
                    Tolak Pendaftaran
                </button>
                {{-- Tombol TERIMA → buka modal terima --}}
                <button type="button" onclick="bukaModal('terima')"
                    class="flex-1 py-3 rounded-xl bg-emerald-500 text-white font-bold text-sm hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/40 hover:-translate-y-0.5">
                    Terima Pendaftaran
                </button>
            </div>

            {{-- Form Terima --}}
            <form id="form-terima" method="POST"
                  action="{{ route('admin.pendaftaran.verifikasi', $pendaftaran) }}"
                  style="display:none">
                @csrf
                <input type="hidden" name="aksi" value="diverifikasi">
                <input type="hidden" name="catatan_admin" id="catatan-terima">
                <input type="hidden" name="batas_waktu" id="batas-waktu-terima">
                <input type="hidden" name="kelompok_belajar_id" id="kelompok-terima">
                <input type="hidden" name="nominal_paket" id="nominal-paket-terima">
                <input type="hidden" name="jumlah_cicilan" id="jumlah-cicilan-terima">
                <input type="hidden" name="jatuh_tempo_berikutnya" id="jatuh-tempo-terima">
            </form>

            {{-- Form Tolak --}}
            <form id="form-tolak" method="POST"
                  action="{{ route('admin.pendaftaran.verifikasi', $pendaftaran) }}"
                  style="display:none">
                @csrf
                <input type="hidden" name="aksi" value="ditolak">
                <input type="hidden" name="catatan_admin" id="catatan-tolak">
            </form>
        </div>
        @elseif($pendaftaran->catatan_admin)
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-zinc-800">
            <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Catatan Admin</h3>
            <p class="text-sm text-slate-700 dark:text-slate-300">{{ $pendaftaran->catatan_admin }}</p>
        </div>
        @endif
    </div>
</div>

@push('modals')
{{-- ── MODAL KONFIRMASI CUSTOM ─────────────────────────────── --}}
<div id="modal-konfirmasi"
     style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.5); backdrop-filter:blur(4px);
            display:none; align-items:center; justify-content:center; padding:16px;">
    <div id="modal-box"
         style="background:#fff; border-radius:20px; padding:28px; max-width:400px; width:100%;
                box-shadow:0 25px 60px rgba(0,0,0,0.25); animation: modalIn 0.2s ease;">
        <div id="modal-icon" style="text-align:center; font-size:2.5rem; margin-bottom:12px;"></div>
        <h3 id="modal-title" style="font-size:1.05rem; font-weight:800; color:#0F172A; text-align:center; margin-bottom:8px;"></h3>
        <p id="modal-desc" style="font-size:0.85rem; color:#64748B; text-align:center; margin-bottom:24px; line-height:1.6;"></p>
        
        {{-- Sisa / Biaya Paket Info --}}
        <div id="modal-info-pembayaran" class="mb-4 flex flex-col gap-3 text-xs text-left" style="display:none;">
            <!-- Input Nominal Paket (Custom) -->
            <div>
                <label style="display:block; font-size:0.8rem; font-weight:700; color:#64748B; margin-bottom:4px;">Total Biaya Paket (Nominal) <span style="color:#EF4444">*</span></label>
                <input type="text" id="modal-nominal-paket-input" value="{{ number_format($pendaftaran->paketBimbingan?->nominal ?? 0, 0, ',', '.') }}" style="width:100%; padding:10px 14px; border-radius:12px; border:1px solid #E2E8F0; font-size:0.9rem; focus:outline-none;" oninput="formatRupiahInput(this); hitungSisaTagihan()">
            </div>
            
            @if($pendaftaran->pembayaran && $pendaftaran->pembayaran->jumlah)
            <div class="flex justify-between items-center text-slate-550 dark:text-slate-400 pt-1.5 border-t border-slate-100 dark:border-zinc-800">
                @if($pendaftaran->pembayaran->jenis_bayar === 'dp')
                    <span>DP Dibayar:</span>
                    <span class="font-bold text-emerald-700 dark:text-emerald-400" id="dp-dibayar-text" data-dp="{{ $pendaftaran->pembayaran->jumlah }}">Rp {{ number_format($pendaftaran->pembayaran->jumlah, 0, ',', '.') }},-</span>
                @else
                    <span>Nominal Dibayar:</span>
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">✅ LUNAS</span>
                        <span class="font-bold text-emerald-700 dark:text-emerald-400" id="dp-dibayar-text" data-dp="{{ $pendaftaran->pembayaran->jumlah }}">Rp {{ number_format($pendaftaran->pembayaran->jumlah, 0, ',', '.') }},-</span>
                    </div>
                @endif
            </div>
            @endif

            @if($pendaftaran->pembayaran && $pendaftaran->pembayaran->jenis_bayar === 'dp' && ($pendaftaran->paketBimbingan?->bisa_dicicil ?? true))
            <div class="pt-2 border-t border-slate-100">
                <label style="display:block; font-size:0.8rem; font-weight:700; color:#64748B; margin-bottom:4px;">Pilih Jumlah Cicilan (Tenor) <span style="color:#EF4444">*</span></label>
                <select id="modal-jumlah-cicilan-input" style="width:100%; padding:10px 14px; border-radius:12px; border:1px solid #E2E8F0; font-size:0.9rem; focus:outline-none; background-color:#fff;" onchange="hitungSisaTagihan()">
                    <option value="">— Tidak Dicicil (Langsung Lunas) —</option>
                    @php $maxCicilan = $pendaftaran->paketBimbingan?->max_cicilan ?? 6; @endphp
                    @for($i = 1; $i <= $maxCicilan; $i++)
                        <option value="{{ $i }}">{{ $i }} Kali Cicilan</option>
                    @endfor
                </select>
                <div id="info-kalkulasi-cicilan" class="mt-2 text-indigo-600 font-bold hidden" style="display:none;"></div>
            </div>
            @endif
        </div>

        @if($pendaftaran->pembayaran && $pendaftaran->pembayaran->jenis_bayar === 'dp' && ($pendaftaran->paketBimbingan?->bisa_dicicil ?? true))
        <div id="modal-batas-waktu-container" style="display:none; margin-bottom:16px; text-align:left;">
            <label style="display:block; font-size:0.8rem; font-weight:700; color:#64748B; margin-bottom:8px;">Tanggal Jatuh Tempo Perdana <span style="color:#EF4444">*</span></label>
            <input type="date" id="modal-batas-waktu-input" style="width:100%; padding:10px 14px; border-radius:12px; border:1px solid #E2E8F0; font-size:0.9rem; focus:outline-none;" value="{{ now()->addDays(30)->format('Y-m-d') }}">
            <p style="font-size:0.7rem; color:#94A3B8; margin-top:6px;">*Tanggal sistem mulai menghitung keterlambatan cicilan selanjutnya (Default H+30).</p>
        </div>
        @endif
        
        <div id="modal-kelompok-container" style="display:none; margin-bottom:24px; text-align:left;">
            <label style="display:block; font-size:0.8rem; font-weight:700; color:#64748B; margin-bottom:8px;">Kelompok Belajar <span style="color:#EF4444">*</span></label>
            <div style="position: relative;">
                <select id="modal-kelompok-input" style="width:100%;">
                    <option value="" disabled selected>— Pilih Kelompok Belajar —</option>
                    @foreach($kelompoks as $k)
                    <option value="{{ $k->id }}">{{ $k->nama_kelompok }} ({{ $k->peserta_didiks_count ?? 0 }} Siswa)</option>
                    @endforeach
                </select>
            </div>
            <p id="error-kelompok" style="font-size:0.75rem; color:#EF4444; margin-top:6px; display:none;">Anda harus memilih kelompok belajar terlebih dahulu.</p>
        </div>

        {{-- Form Catatan / Alasan Tolak (hanya tampil saat tolak) --}}
        <div id="modal-catatan-container" style="display:none; margin-bottom:24px; text-align:left;">
            <label style="display:block; font-size:0.8rem; font-weight:700; color:#64748B; margin-bottom:8px;">Alasan Penolakan / Catatan <span style="color:#EF4444">*</span></label>
            <textarea id="catatan-shared" rows="3" placeholder="Tuliskan alasan penolakan..." style="width:100%; padding:10px 14px; border-radius:12px; border:1px solid #E2E8F0; font-size:0.9rem; focus:outline-none; background-color:#fff; resize:none;"></textarea>
            <p id="error-catatan" style="font-size:0.75rem; color:#EF4444; margin-top:6px; display:none;">Anda harus mengisi alasan penolakan untuk siswa.</p>
        </div>

        <div style="display:flex; gap:12px;">
            <button onclick="tutupModal()"
                style="flex:1; padding:11px; border-radius:12px; border:1.5px solid #E2E8F0;
                       background:#F8FAFC; color:#64748B; font-weight:700; font-size:0.9rem;
                       cursor:pointer; transition:all 0.15s;">
                Batal
            </button>
            <button id="modal-confirm-btn"
                style="flex:1; padding:11px; border-radius:12px; border:none;
                       color:white; font-weight:700; font-size:0.9rem;
                       cursor:pointer; transition:all 0.15s;">
            </button>
        </div>
    </div>
</div>

<style>
@keyframes modalIn {
    from { opacity:0; transform:scale(0.9) translateY(10px); }
    to   { opacity:1; transform:scale(1)   translateY(0);    }
}
#modal-konfirmasi.aktif { display:flex !important; }
#modal-confirm-btn:hover { opacity:0.88; }
</style>
@endpush

@push('scripts')
<script>
let aksiAktif = null;

function bukaModal(aksi) {
    aksiAktif = aksi;
    const modal = document.getElementById('modal-konfirmasi');
    const icon  = document.getElementById('modal-icon');
    const title = document.getElementById('modal-title');
    const desc  = document.getElementById('modal-desc');
    const btn   = document.getElementById('modal-confirm-btn');

    if (aksi === 'terima') {
        icon.textContent  = '✅';
        title.textContent = 'Terima Pendaftaran?';
        desc.textContent  = 'Pendaftaran ini akan diverifikasi dan akun siswa akan otomatis dibuat. Tindakan ini tidak dapat dibatalkan.';
        btn.textContent   = 'Ya, Terima';
        btn.style.background = 'linear-gradient(135deg,#10B981,#059669)';
        if (document.getElementById('modal-batas-waktu-container')) {
            document.getElementById('modal-batas-waktu-container').style.display = 'block';
        }
        document.getElementById('modal-kelompok-container').style.display = 'block';
        document.getElementById('modal-info-pembayaran').style.display = 'flex';
        document.getElementById('modal-catatan-container').style.display = 'none';
        document.getElementById('error-kelompok').style.display = 'none';
        setTimeout(() => hitungSisaTagihan(), 100);
    } else {
        icon.textContent  = '❌';
        title.textContent = 'Tolak Pendaftaran?';
        desc.textContent  = 'Pendaftaran ini akan ditolak. Siswa akan mendapatkan notifikasi penolakan beserta alasan yang Anda berikan.';
        btn.textContent   = 'Ya, Tolak';
        btn.style.background = 'linear-gradient(135deg,#EF4444,#DC2626)';
        if (document.getElementById('modal-batas-waktu-container')) {
            document.getElementById('modal-batas-waktu-container').style.display = 'none';
        }
        document.getElementById('modal-kelompok-container').style.display = 'none';
        document.getElementById('modal-info-pembayaran').style.display = 'none';
        document.getElementById('modal-catatan-container').style.display = 'block';
        document.getElementById('error-catatan').style.display = 'none';
    }

    modal.classList.add('aktif');
    document.body.style.overflow = 'hidden';
}

function tutupModal() {
    document.getElementById('modal-konfirmasi').classList.remove('aktif');
    document.body.style.overflow = '';
    aksiAktif = null;
}

document.getElementById('modal-confirm-btn').addEventListener('click', function() {
    if (!aksiAktif) return;
    const catatan = document.getElementById('catatan-shared').value;

    if (aksiAktif === 'terima') {
        const kelompokId = document.getElementById('modal-kelompok-input').value;
        if (!kelompokId) {
            document.getElementById('error-kelompok').style.display = 'block';
            return; // Cegah submit jika belum pilih kelompok
        }
        document.getElementById('kelompok-terima').value = kelompokId;
        
        document.getElementById('catatan-terima').value = catatan;
        document.getElementById('batas-waktu-terima').value = document.getElementById('modal-batas-waktu-input') ? document.getElementById('modal-batas-waktu-input').value : '';
        document.getElementById('jatuh-tempo-terima').value = document.getElementById('modal-batas-waktu-input') ? document.getElementById('modal-batas-waktu-input').value : '';
        document.getElementById('nominal-paket-terima').value = document.getElementById('modal-nominal-paket-input') ? document.getElementById('modal-nominal-paket-input').value.replace(/[^,\d]/g, '') : '';
        document.getElementById('jumlah-cicilan-terima').value = document.getElementById('modal-jumlah-cicilan-input') ? document.getElementById('modal-jumlah-cicilan-input').value : '';
        document.getElementById('form-terima').submit();
    } else {
        if (!catatan.trim()) {
            document.getElementById('error-catatan').style.display = 'block';
            return;
        }
        document.getElementById('catatan-tolak').value = catatan;
        document.getElementById('form-tolak').submit();
    }
});

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

// Format input dinamis pada form
function formatRupiahInput(input) {
    let rawValue = input.value.replace(/[^,\d]/g, '');
    input.value = formatRupiah(rawValue);
}

function hitungSisaTagihan() {
    const nominalInput = document.getElementById('modal-nominal-paket-input');
    if (!nominalInput) return;
    
    const nominal = parseInt(nominalInput.value.replace(/[^,\d]/g, '') || 0);
    const dpEl = document.getElementById('dp-dibayar-text');
    const dp = dpEl ? parseInt(dpEl.getAttribute('data-dp') || 0) : 0;
    const sisa = Math.max(0, nominal - dp);

    const cicilanEl = document.getElementById('modal-jumlah-cicilan-input');
    const infoEl = document.getElementById('info-kalkulasi-cicilan');

    if (cicilanEl && infoEl) {
        const jml = parseInt(cicilanEl.value);
        if (jml > 0) {
            const perBulan = Math.ceil(sisa / jml);
            infoEl.textContent = `Estimasi: Rp ${formatRupiah(perBulan.toString())} / bulan (Sisa: Rp ${formatRupiah(sisa.toString())})`;
            infoEl.style.display = 'block';
        } else {
            infoEl.style.display = 'none';
        }
    }
}


// Tutup modal jika klik backdrop
document.getElementById('modal-konfirmasi').addEventListener('click', function(e) {
    if (e.target === this) tutupModal();
});

// Tutup dengan tombol Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') tutupModal();
});
// Cek fragment URL untuk UX Highlight
document.addEventListener('DOMContentLoaded', function() {
    if (window.location.hash === '#bukti-pembayaran') {
        const el = document.getElementById('bukti-pembayaran');
        if (el) {
            // Scroll ke elemen
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            // Beri efek highlight
            el.classList.remove('border-slate-100', 'dark:border-zinc-800');
            el.classList.add('border-rose-400', 'ring-4', 'ring-rose-100', 'dark:ring-rose-900/30', 'scale-[1.02]', 'shadow-lg');
            
            // Hilangkan efek setelah 3 detik
            setTimeout(() => {
                el.classList.remove('border-rose-400', 'ring-4', 'ring-rose-100', 'dark:ring-rose-900/30', 'scale-[1.02]', 'shadow-lg');
                el.classList.add('border-slate-100', 'dark:border-zinc-800');
            }, 3000);
        }
    }
});
</script>
@endpush
@endsection

