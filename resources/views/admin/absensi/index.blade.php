@extends('layouts.admin')
@section('title', 'Absensi Siswa')

@push('head')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
@endpush

@section('content')
<div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Absensi Siswa</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola kehadiran siswa harian dengan notifikasi WhatsApp otomatis</p>
    </div>
    <div class="flex gap-3 items-center">
        <form method="GET" class="flex items-center gap-2">
            <input type="date" name="tanggal" value="{{ $tanggal }}"
                class="bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-xl px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-400">
            <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-500 text-white text-sm font-bold hover:bg-indigo-600 transition-colors shadow-md shadow-indigo-500/30">Filter</button>
        </form>
        <a href="{{ route('absensi.rekap') }}" class="px-4 py-2 rounded-xl bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-50 transition-colors">📊 Rekap</a>
    </div>
</div>

@if(session('success'))
<div class="mb-4 px-4 py-3 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 text-sm font-semibold">
    ✅ {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-4 px-4 py-3 rounded-xl bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-400 text-sm font-semibold">
    ❌ {{ session('error') }}
</div>
@endif

{{-- SCAN QR SECTION --}}
<div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-zinc-800 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-xs font-bold uppercase tracking-widest text-indigo-500">⚡ Scan QR Code</h3>
        <div class="flex gap-3 flex-wrap">
            {{-- Tombol minta izin kamera dulu --}}
            <button id="btn-request-permission" onclick="mintaIzinKamera()"
                class="flex items-center gap-2 px-4 py-2 rounded-xl bg-violet-50 dark:bg-violet-900/30 text-violet-600 dark:text-violet-400 text-sm font-bold hover:bg-violet-100 transition-colors border border-violet-200 dark:border-violet-800">
                🔐 Izinkan Kamera
            </button>
            <button id="btn-toggle-camera" onclick="toggleCamera()"
                class="flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-sm font-bold hover:bg-indigo-100 transition-colors border border-indigo-200 dark:border-indigo-800">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><circle cx="12" cy="13" r="3"/>
                </svg>
                📷 Buka Kamera Scan
            </button>
        </div>
    </div>

    {{-- INFO HTTPS --}}
    <div id="https-info" class="hidden mb-4 px-4 py-4 rounded-xl bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-700 text-amber-800 dark:text-amber-300 text-sm">
        <div class="flex items-start gap-3">
            <span class="text-2xl flex-shrink-0">⚠️</span>
            <div class="flex-1">
                <p class="font-bold mb-2">Kamera membutuhkan koneksi aman (HTTPS atau localhost)</p>
                <p class="text-xs opacity-80 mb-3">Browser memblokir akses kamera pada URL HTTP biasa karena alasan keamanan.</p>
                <p class="font-semibold text-xs mb-1">✅ Solusi Cepat — Chrome/Edge di HP:</p>
                <ol class="text-xs space-y-1 list-decimal list-inside mb-3 opacity-90">
                    <li>Buka <code class="bg-amber-100 dark:bg-amber-900 px-1 rounded">chrome://flags/#unsafely-treat-insecure-origin-as-secure</code></li>
                    <li>Tambahkan URL server: <code id="server-url-badge" class="bg-amber-100 dark:bg-amber-900 px-1 rounded font-mono">http://...loading</code></li>
                    <li>Pilih <strong>Enabled</strong> → Klik <strong>Relaunch</strong></li>
                </ol>
            </div>
        </div>
    </div>

    {{-- INFO IZIN DITOLAK --}}
    <div id="permission-denied-info" class="hidden mb-4 px-4 py-4 rounded-xl bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-700 text-rose-800 dark:text-rose-300 text-sm">
        <div class="flex items-start gap-3">
            <span class="text-2xl flex-shrink-0">🚫</span>
            <div class="flex-1">
                <p class="font-bold mb-2">Izin kamera ditolak oleh browser</p>
                <p class="text-xs opacity-80 mb-3">Anda pernah menolak izin kamera untuk situs ini. Ikuti langkah di bawah untuk mengaktifkan kembali:</p>
                <p class="font-semibold text-xs mb-1">🔧 Chrome Android:</p>
                <ol class="text-xs space-y-1 list-decimal list-inside mb-3 opacity-90">
                    <li>Ketuk ikon 🔒 di sebelah kiri address bar</li>
                    <li>Pilih <strong>Izin situs</strong></li>
                    <li>Ubah <strong>Kamera</strong> menjadi <strong>Izinkan</strong></li>
                    <li>Muat ulang halaman ini</li>
                </ol>
                <p class="font-semibold text-xs mb-1">🔧 Chrome Desktop:</p>
                <ol class="text-xs space-y-1 list-decimal list-inside opacity-90">
                    <li>Klik ikon 🔒 di address bar</li>
                    <li>Klik <strong>Setelan situs</strong></li>
                    <li>Ubah <strong>Kamera</strong> dari Blokir menjadi <strong>Izinkan</strong></li>
                    <li>Refresh halaman</li>
                </ol>
            </div>
        </div>
    </div>

    {{-- Mode Kamera QR --}}
    <div id="camera-section" class="hidden mb-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
            <div class="md:col-span-2">
                <div id="qr-reader" class="rounded-2xl overflow-hidden border-2 border-indigo-200 dark:border-indigo-700 bg-slate-900" style="min-height:280px;"></div>
                <p class="text-xs text-slate-400 text-center mt-2">📱 Arahkan kamera ke QR Code siswa</p>
            </div>
            <div class="flex flex-col gap-3">
                {{-- Preview --}}
                <div id="scan-preview" class="hidden rounded-2xl p-4 text-center">
                    <div class="text-3xl mb-2">✅</div>
                    <div id="scan-preview-nama" class="font-bold text-slate-800 dark:text-white text-sm"></div>
                    <div id="scan-preview-nisn" class="text-xs text-slate-500 font-mono mt-1"></div>
                    <div id="scan-preview-mode" class="text-xs font-bold mt-2 px-3 py-1 rounded-lg inline-block"></div>
                </div>
                {{-- Mode Toggle --}}
                <div class="flex flex-col gap-2">
                    <button id="btn-mode-masuk" onclick="setMode('masuk')"
                        class="w-full py-3 rounded-xl bg-emerald-500 text-white font-bold text-sm shadow-md shadow-emerald-500/30 ring-2 ring-emerald-300 ring-offset-1">
                        ✅ Mode: Absen Masuk
                    </button>
                    <button id="btn-mode-pulang" onclick="setMode('pulang')"
                        class="w-full py-3 rounded-xl bg-white dark:bg-zinc-800 text-amber-600 dark:text-amber-400 font-bold text-sm border-2 border-amber-200 dark:border-amber-700">
                        🏠 Mode: Absen Pulang
                    </button>
                </div>
                <button onclick="stopCamera()"
                    class="w-full py-2 rounded-xl bg-slate-100 dark:bg-zinc-700 text-slate-500 dark:text-slate-400 font-semibold text-sm hover:bg-slate-200 transition-colors">
                    ✕ Tutup Kamera
                </button>
            </div>
        </div>
    </div>

    {{-- Manual / Barcode Scanner Fisik --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="text-xs font-semibold text-slate-500 block mb-2">NISN / Kode Siswa (Ketik atau Barcode Scanner)</label>
            <input type="text" id="scan-input" placeholder="Scan atau ketik NISN siswa..."
                class="w-full border border-slate-200 dark:border-zinc-700 rounded-xl p-3 text-sm bg-slate-50 dark:bg-zinc-800 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-400"
                autofocus>
        </div>
        <div class="flex items-end gap-3">
            <button onclick="scanAbsen('masuk')" class="flex-1 py-3 rounded-xl bg-emerald-500 text-white font-bold text-sm hover:bg-emerald-600 transition-colors shadow-md shadow-emerald-500/30">
                ✅ Absen Masuk
            </button>
            <button onclick="scanAbsen('pulang')" class="flex-1 py-3 rounded-xl bg-amber-500 text-white font-bold text-sm hover:bg-amber-600 transition-colors shadow-md shadow-amber-500/30">
                🏠 Absen Pulang
            </button>
        </div>
    </div>

    <div id="scan-result" class="mt-3 hidden px-4 py-3 rounded-xl text-sm font-semibold"></div>
</div>

{{-- DAFTAR SISWA --}}
<div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-slate-100 dark:border-zinc-800 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 dark:border-zinc-800 flex items-center justify-between">
        <h3 class="text-sm font-bold text-slate-700 dark:text-white">Daftar Kehadiran · {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</h3>
        <span class="text-xs text-slate-400">{{ $pesertaDidiks->count() }} siswa</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-zinc-800 text-xs uppercase text-slate-500 dark:text-slate-400 tracking-wide">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Nama Siswa</th>
                    <th class="px-4 py-3 text-left">NISN</th>
                    <th class="px-4 py-3 text-center">Jam Masuk</th>
                    <th class="px-4 py-3 text-center">Jam Pulang</th>
                    <th class="px-4 py-3 text-center">WA</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                @forelse($pesertaDidiks as $p)
                @php $ab = $p->absensi_hari_ini; @endphp
                <tr class="hover:bg-slate-50 dark:hover:bg-zinc-800/50 transition-colors">
                    <td class="px-4 py-3 text-slate-500">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3 font-semibold text-slate-800 dark:text-white">{{ $p->nama_lengkap }}</td>
                    <td class="px-4 py-3 text-slate-500 font-mono text-xs">{{ $p->nisn }}</td>
                    <td class="px-4 py-3 text-center">
                        @if($ab && $ab->jam_masuk)
                            <span class="px-2 py-1 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs font-bold">{{ $ab->jam_masuk }}</span>
                        @else
                            <span class="text-slate-300">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($ab && $ab->jam_pulang)
                            <span class="px-2 py-1 rounded-lg bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-xs font-bold">{{ $ab->jam_pulang }}</span>
                        @else
                            <span class="text-slate-300">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($ab)
                            <div class="flex items-center justify-center gap-1">
                                @if($ab->wa_masuk_sent)<span class="text-emerald-500" title="WA masuk terkirim">📩</span>@endif
                                @if($ab->wa_pulang_sent)<span class="text-amber-500" title="WA pulang terkirim">📨</span>@endif
                                @if(!$ab->wa_masuk_sent && !$ab->wa_pulang_sent)<span class="text-slate-300">—</span>@endif
                            </div>
                        @else
                            <span class="text-slate-300">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-2">
                            @if(!$ab || !$ab->jam_masuk)
                            <form method="POST" action="{{ route('absensi.masuk', $p) }}">
                                @csrf
                                <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-500 text-white text-xs font-bold hover:bg-emerald-600 transition-colors shadow-sm">Masuk</button>
                            </form>
                            @endif
                            @if($ab && $ab->jam_masuk && !$ab->jam_pulang)
                            <form method="POST" action="{{ route('absensi.pulang', $p) }}">
                                @csrf
                                <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-amber-500 text-white text-xs font-bold hover:bg-amber-600 transition-colors shadow-sm">Pulang</button>
                            </form>
                            @endif
                            @if($ab && $ab->jam_pulang)
                            <span class="px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-zinc-700 text-slate-500 dark:text-slate-400 text-xs font-bold">Selesai ✓</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center text-slate-400">Tidak ada data siswa aktif.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
const csrfToken  = '{{ csrf_token() }}';
const urlMasuk   = '{{ route("absensi.scan.masuk") }}';
const urlPulang  = '{{ route("absensi.scan.pulang") }}';
let html5QrCode  = null;
let scanMode     = 'masuk';
let isProcessing = false;

// ── Mode toggle ──────────────────────────────────────────────
function setMode(mode) {
    scanMode = mode;
    const em = 'w-full py-3 rounded-xl bg-emerald-500 text-white font-bold text-sm shadow-md shadow-emerald-500/30 ring-2 ring-emerald-300 ring-offset-1';
    const eg = 'w-full py-3 rounded-xl bg-white dark:bg-zinc-800 text-emerald-600 dark:text-emerald-400 font-bold text-sm border-2 border-emerald-200 dark:border-emerald-700';
    const am = 'w-full py-3 rounded-xl bg-amber-500 text-white font-bold text-sm shadow-md shadow-amber-500/30 ring-2 ring-amber-300 ring-offset-1';
    const ag = 'w-full py-3 rounded-xl bg-white dark:bg-zinc-800 text-amber-600 dark:text-amber-400 font-bold text-sm border-2 border-amber-200 dark:border-amber-700';
    document.getElementById('btn-mode-masuk').className  = mode === 'masuk' ? em : eg;
    document.getElementById('btn-mode-pulang').className = mode === 'pulang' ? am : ag;
}

// ── Minta izin kamera eksplisit ──────────────────────────────
async function mintaIzinKamera() {
    const btn = document.getElementById('btn-request-permission');
    btn.textContent = '⏳ Meminta izin...';
    btn.disabled = true;

    // Debug: cek secure context
    console.log('isSecureContext:', window.isSecureContext);
    console.log('protocol:', location.protocol);
    console.log('hostname:', location.hostname);

    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        showResult(false, '❌ Browser/perangkat ini tidak mendukung akses kamera via getUserMedia.');
        btn.textContent = '🔐 Izinkan Kamera';
        btn.disabled = false;
        return;
    }

    try {
        // Coba kamera belakang dulu, fallback ke kamera manapun
        let stream;
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: { ideal: 'environment' } }
            });
        } catch (e) {
            // Fallback: minta kamera apapun yang tersedia
            stream = await navigator.mediaDevices.getUserMedia({ video: true });
        }
        // Izin diberikan — hentikan stream
        stream.getTracks().forEach(t => t.stop());
        btn.textContent = '✅ Kamera Diizinkan';
        btn.className = btn.className.replace('violet', 'emerald');
        document.getElementById('permission-denied-info').classList.add('hidden');
        showResult(true, 'Izin kamera berhasil! Sekarang klik "📷 Buka Kamera Scan".');
    } catch (err) {
        console.error('Camera error:', err.name, err.message);
        btn.textContent = '🔐 Izinkan Kamera';
        btn.disabled = false;
        if (err.name === 'NotAllowedError') {
            document.getElementById('permission-denied-info').classList.remove('hidden');
            showResult(false, '🚫 Izin ditolak (NotAllowedError). Cek panduan di atas. isSecureContext=' + window.isSecureContext);
        } else if (err.name === 'NotFoundError') {
            showResult(false, '📷 Kamera tidak ditemukan di perangkat.');
        } else if (err.name === 'NotReadableError') {
            showResult(false, '⚠️ Kamera sedang dipakai aplikasi lain. Tutup aplikasi kamera lain lalu coba lagi.');
        } else {
            showResult(false, 'Error: ' + err.name + ' — ' + err.message);
        }
    }
}

// ── Kamera live scan ─────────────────────────────────────────
function toggleCamera() {
    const section = document.getElementById('camera-section');
    if (section.classList.contains('hidden')) {
        // Cek apakah halaman berjalan di konteks aman (HTTPS / localhost)
    const isSecure = window.isSecureContext
        || location.hostname === 'localhost'
        || location.hostname === '127.0.0.1'
        || location.hostname.endsWith('.test')
        || location.hostname.endsWith('.local');

        if (!isSecure) {
            // Tampilkan URL aktual di pesan info
            const urlBadge = document.getElementById('server-url-badge');
            if (urlBadge) urlBadge.textContent = location.origin;
            document.getElementById('https-info').classList.remove('hidden');
            return;
        }
        document.getElementById('https-info').classList.add('hidden');
        section.classList.remove('hidden');
        document.getElementById('btn-toggle-camera').innerHTML = '✕ Tutup Kamera';
        startCamera();
    } else {
        stopCamera();
    }
}

function startCamera() {
    html5QrCode = new Html5Qrcode('qr-reader');

    // html5-qrcode hanya terima string atau {exact:...}, bukan {ideal:...}
    html5QrCode.start(
        { facingMode: 'environment' },
        { fps: 10, qrbox: { width: 230, height: 230 } },
        onScanSuccess,
        () => {}
    ).catch(err => {
        const errStr = err ? err.toString() : '';
        console.error('QR Scanner error:', errStr);

        // Jika kamera belakang tidak ada, coba kamera depan
        if (errStr.includes('facingMode') || errStr.includes('NotFoundError') || errStr.includes('OverconstrainedError')) {
            html5QrCode.start(
                { facingMode: 'user' },
                { fps: 10, qrbox: { width: 230, height: 230 } },
                onScanSuccess,
                () => {}
            ).catch(err2 => {
                showResult(false, 'Error kamera: ' + (err2 ? err2.toString() : 'Tidak diketahui'));
                stopCamera();
            });
            return;
        }

        if (errStr.includes('NotAllowedError') || errStr.includes('Permission denied')) {
            document.getElementById('permission-denied-info').classList.remove('hidden');
            showResult(false, '🚫 Izin kamera ditolak. Klik "🔐 Izinkan Kamera" terlebih dahulu.');
        } else if (errStr.includes('NotReadableError')) {
            showResult(false, '⚠️ Kamera sedang dipakai aplikasi lain. Tutup lalu coba lagi.');
        } else {
            showResult(false, 'Error kamera: ' + errStr);
        }
        stopCamera();
    });
}

function stopCamera() {
    if (html5QrCode && html5QrCode.isScanning) {
        html5QrCode.stop().then(() => html5QrCode.clear()).catch(() => {});
    }
    document.getElementById('camera-section').classList.add('hidden');
    document.getElementById('btn-toggle-camera').innerHTML = '📷 Buka Kamera Scan';
    document.getElementById('scan-preview').classList.add('hidden');
}

function onScanSuccess(decodedText) {
    if (isProcessing) return;
    isProcessing = true;

    const nisn = decodedText.trim();
    kirimAbsen(nisn, scanMode, function(data) {
        if (data.success) {
            // 1. Tampilkan preview card di samping scanner
            const prev = document.getElementById('scan-preview');
            prev.classList.remove('hidden');
            prev.className = 'rounded-2xl p-4 text-center ' +
                (scanMode === 'masuk' ? 'bg-emerald-50 border border-emerald-200' : 'bg-amber-50 border border-amber-200');
            document.getElementById('scan-preview-nama').textContent = data.nama ?? nisn;
            document.getElementById('scan-preview-nisn').textContent = 'NISN: ' + nisn;
            const modeEl = document.getElementById('scan-preview-mode');
            modeEl.textContent = scanMode === 'masuk' ? '✅ Absen Masuk' : '🏠 Absen Pulang';
            modeEl.className = 'text-xs font-bold mt-2 px-3 py-1 rounded-lg inline-block ' +
                (scanMode === 'masuk' ? 'bg-emerald-500 text-white' : 'bg-amber-500 text-white');

            // 2. Update baris tabel langsung (tanpa reload halaman)
            updateTableRow(data);

            // 3. Sembunyikan preview & izinkan scan berikutnya setelah 1.5 detik
            setTimeout(() => {
                isProcessing = false;
                prev.classList.add('hidden');
            }, 1500);
        } else {
            // Scan gagal: izinkan scan ulang setelah 1.5 detik
            setTimeout(() => { isProcessing = false; }, 1500);
        }
    });
}

// Update baris tabel secara langsung (DOM update, tanpa reload halaman)
function updateTableRow(data) {
    if (!data.nisn || !data.jam) return;

    // Cari baris tabel berdasarkan NISN
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
        const nisnCell = row.querySelector('td:nth-child(3)');
        if (!nisnCell || nisnCell.textContent.trim() !== data.nisn) return;

        const jamFormatted = data.jam.substring(0, 5); // HH:MM

        if (data.tipe === 'masuk') {
            // Update kolom Jam Masuk (kolom ke-4)
            const jamMasukCell = row.querySelector('td:nth-child(4)');
            if (jamMasukCell) {
                jamMasukCell.innerHTML = `<span class="px-2 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-xs font-bold">${jamFormatted}</span>`;
            }
            // Update kolom Aksi — tampilkan tombol Pulang
            const aksiCell = row.querySelector('td:nth-child(7)');
            if (aksiCell) {
                aksiCell.innerHTML = `
                    <div class="flex items-center justify-center gap-2">
                        <button onclick="scanAbsen('pulang')" class="px-3 py-1.5 rounded-lg bg-amber-500 text-white text-xs font-bold hover:bg-amber-600 transition-colors shadow-sm">Pulang</button>
                    </div>`;
            }
        } else if (data.tipe === 'pulang') {
            // Update kolom Jam Pulang (kolom ke-5)
            const jamPulangCell = row.querySelector('td:nth-child(5)');
            if (jamPulangCell) {
                jamPulangCell.innerHTML = `<span class="px-2 py-1 rounded-lg bg-amber-100 text-amber-700 text-xs font-bold">${jamFormatted}</span>`;
            }
            // Update kolom Aksi — tampilkan Selesai
            const aksiCell = row.querySelector('td:nth-child(7)');
            if (aksiCell) {
                aksiCell.innerHTML = `<span class="px-3 py-1.5 rounded-lg bg-slate-100 text-slate-500 text-xs font-bold">Selesai ✓</span>`;
            }
        }
    });
}


// ── Manual / Barcode fisik ───────────────────────────────────
function scanAbsen(tipe) {
    const nisn = document.getElementById('scan-input').value.trim();
    if (!nisn) { alert('Masukkan NISN terlebih dahulu.'); return; }
    kirimAbsen(nisn, tipe, function(data) {
        document.getElementById('scan-input').value = '';
        document.getElementById('scan-input').focus();
        if (data.success) setTimeout(() => location.reload(), 1800);
    });
}

// ── Core fetch ───────────────────────────────────────────────
function kirimAbsen(nisn, tipe, callback) {
    const url = tipe === 'masuk' ? urlMasuk : urlPulang;
    fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ nisn })
    })
    .then(r => r.json())
    .then(data => { showResult(data.success, data.message); if (callback) callback(data); })
    .catch(() => showResult(false, 'Terjadi kesalahan jaringan.'));
}

function showResult(success, msg) {
    const el = document.getElementById('scan-result');
    el.classList.remove('hidden');
    el.className = success
        ? 'mt-3 px-4 py-3 rounded-xl text-sm font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200'
        : 'mt-3 px-4 py-3 rounded-xl text-sm font-semibold bg-rose-50 text-rose-700 border border-rose-200';
    el.textContent = (success ? '✅ ' : '❌ ') + msg;
    setTimeout(() => el.classList.add('hidden'), 4000);
}

document.getElementById('scan-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); scanAbsen(scanMode); }
});
</script>
@endsection
