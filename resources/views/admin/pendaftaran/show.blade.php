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
                <div><span class="text-slate-400 block">Paket Bimbingan</span><span class="font-semibold text-slate-800 dark:text-white">{{ $pendaftaran->paketBimbingan?->nama_paket ?? '-' }}</span></div>
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
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-zinc-800">
            <h3 class="text-xs font-bold uppercase tracking-widest text-indigo-500 mb-4">Bukti Pembayaran</h3>
            @if($pendaftaran->pembayaran)
                <div class="text-sm space-y-2 mb-4">
                    <div class="flex justify-between"><span class="text-slate-400">Metode</span><span class="font-semibold">{{ $pendaftaran->pembayaran->metode_pembayaran }}</span></div>
                    @if($pendaftaran->pembayaran->jumlah)
                    <div class="flex justify-between"><span class="text-slate-400">Jumlah</span><span class="font-semibold text-emerald-600">Rp {{ number_format($pendaftaran->pembayaran->jumlah, 0, ',', '.') }}</span></div>
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

            {{-- Textarea catatan admin (shared) --}}
            <div class="mb-4">
                <label class="text-xs font-semibold text-slate-500 block mb-1">Catatan Admin (opsional)</label>
                <textarea id="catatan-shared" rows="3" placeholder="Tuliskan catatan jika perlu..."
                    class="w-full border border-slate-200 dark:border-zinc-700 rounded-xl p-3 text-sm bg-slate-50 dark:bg-zinc-800 text-slate-800 dark:text-white resize-none focus:outline-none focus:ring-2 focus:ring-indigo-400"></textarea>
            </div>

            <div class="flex gap-3">
                {{-- Tombol TOLAK → buka modal tolak --}}
                <button type="button" onclick="bukaModal('tolak')"
                    class="flex-1 py-2.5 rounded-xl bg-rose-50 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 font-bold text-sm hover:bg-rose-100 dark:hover:bg-rose-900/50 transition-colors border border-rose-200 dark:border-rose-800">
                    ❌ Tolak
                </button>
                {{-- Tombol TERIMA → buka modal terima --}}
                <button type="button" onclick="bukaModal('terima')"
                    class="flex-1 py-2.5 rounded-xl bg-emerald-500 text-white font-bold text-sm hover:bg-emerald-600 transition-colors shadow-md shadow-emerald-500/30">
                    ✅ Terima
                </button>
            </div>

            {{-- Form Terima --}}
            <form id="form-terima" method="POST"
                  action="{{ route('admin.pendaftaran.verifikasi', $pendaftaran) }}"
                  style="display:none">
                @csrf
                <input type="hidden" name="aksi" value="diverifikasi">
                <input type="hidden" name="catatan_admin" id="catatan-terima">
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
    } else {
        icon.textContent  = '❌';
        title.textContent = 'Tolak Pendaftaran?';
        desc.textContent  = 'Pendaftaran ini akan ditolak. Siswa akan mendapatkan notifikasi penolakan.';
        btn.textContent   = 'Ya, Tolak';
        btn.style.background = 'linear-gradient(135deg,#EF4444,#DC2626)';
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
        document.getElementById('catatan-terima').value = catatan;
        document.getElementById('form-terima').submit();
    } else {
        document.getElementById('catatan-tolak').value = catatan;
        document.getElementById('form-tolak').submit();
    }
});

// Tutup modal jika klik backdrop
document.getElementById('modal-konfirmasi').addEventListener('click', function(e) {
    if (e.target === this) tutupModal();
});

// Tutup dengan tombol Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') tutupModal();
});
</script>
@endsection

