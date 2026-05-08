@extends('layouts.admin')
@section('title', 'Absen Pulang')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
<style>
/* ── Layout ─────────────────────────────────────────── */
.ab-page { display: grid; grid-template-columns: 1fr 380px; gap: 1.5rem; align-items: start; }
@media (max-width: 1024px) { .ab-page { grid-template-columns: 1fr; } }

/* ── Card ───────────────────────────────────────────── */
.ab-card { background: #fff; border-radius: 16px; border: 1px solid #e8ecf0; }
.ab-card-head { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 0.75rem; }
.ab-card-body { padding: 1.5rem; }

/* ── Scanner ────────────────────────────────────────── */
.scanner-wrap { position: relative; border-radius: 14px; overflow: hidden; background: #0f172a; aspect-ratio: 4/3; display: flex; align-items: center; justify-content: center; }
.scanner-wrap video { width: 100%; height: 100%; object-fit: cover; display: block; }
.scanner-wrap canvas { display: none; }
.scanner-overlay { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; pointer-events: none; }
.scanner-frame { width: 200px; height: 200px; position: relative; }
.scanner-frame::before, .scanner-frame::after { content: ''; position: absolute; width: 36px; height: 36px; }
.scanner-frame::before { top: 0; left: 0; border-top: 3px solid #f59e0b; border-left: 3px solid #f59e0b; border-radius: 4px 0 0 0; }
.scanner-frame::after  { top: 0; right: 0; border-top: 3px solid #f59e0b; border-right: 3px solid #f59e0b; border-radius: 0 4px 0 0; }
.scanner-corner-bl { position: absolute; bottom: 0; left: 0; width: 36px; height: 36px; border-bottom: 3px solid #f59e0b; border-left: 3px solid #f59e0b; border-radius: 0 0 0 4px; }
.scanner-corner-br { position: absolute; bottom: 0; right: 0; width: 36px; height: 36px; border-bottom: 3px solid #f59e0b; border-right: 3px solid #f59e0b; border-radius: 0 0 4px 0; }
.scanner-line { position: absolute; top: 10%; left: 5%; right: 5%; height: 2px; background: linear-gradient(90deg, transparent, #f59e0b, transparent); animation: scan 2s ease-in-out infinite; }
@keyframes scan { 0%, 100% { top: 10%; } 50% { top: 88%; } }
.scanner-idle { color: #64748b; font-size: 0.875rem; text-align: center; padding: 2rem 1rem; }
.scanner-idle svg { width: 48px; height: 48px; margin: 0 auto 0.75rem; display: block; opacity: 0.3; }

/* ── Tombol ─────────────────────────────────────────── */
.btn-scan-toggle { width: 100%; padding: 0.75rem; border-radius: 10px; font-size: 0.875rem; font-weight: 600; cursor: pointer; border: none; transition: all 0.15s; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
.btn-open  { background: #f59e0b; color: #fff; }
.btn-open:hover  { background: #d97706; }
.btn-close { background: #f1f5f9; color: #475569; }
.btn-close:hover { background: #e2e8f0; }

/* ── Input manual ───────────────────────────────────── */
.ab-input-group { display: flex; gap: 0.5rem; }
.ab-input { flex: 1; border: 1px solid #e2e8f0; border-radius: 10px; padding: 0.7rem 1rem; font-size: 0.875rem; color: #1e293b; background: #f8fafc; outline: none; transition: border-color 0.15s, box-shadow 0.15s; }
.ab-input:focus { border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245,158,11,0.12); }
.ab-btn-submit { background: #f59e0b; color: #fff; border: none; border-radius: 10px; padding: 0.7rem 1.25rem; font-size: 0.875rem; font-weight: 600; cursor: pointer; white-space: nowrap; transition: background 0.15s; }
.ab-btn-submit:hover { background: #d97706; }

/* ── Log hasil scan ─────────────────────────────────── */
.scan-log { display: flex; flex-direction: column; gap: 0.5rem; max-height: 360px; overflow-y: auto; }
.log-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 10px; animation: fadeIn 0.3s ease; }
.log-item.ok  { background: #fffbeb; border: 1px solid #fde68a; }
.log-item.err { background: #fff1f2; border: 1px solid #fecdd3; }
.log-item.dup { background: #f0f9ff; border: 1px solid #bae6fd; }
.log-icon { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.875rem; }
.log-item.ok  .log-icon { background: #fef3c7; color: #b45309; }
.log-item.err .log-icon { background: #ffe4e6; color: #be185d; }
.log-item.dup .log-icon { background: #e0f2fe; color: #0369a1; }
.log-info { flex: 1; min-width: 0; }
.log-name { font-size: 0.875rem; font-weight: 600; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.log-sub  { font-size: 0.75rem; color: #64748b; margin-top: 1px; }
.log-time { font-size: 0.75rem; font-weight: 700; color: #475569; flex-shrink: 0; }
.log-empty { text-align: center; padding: 2rem 1rem; color: #94a3b8; font-size: 0.875rem; }

/* ── Stat bar ────────────────────────────────────────── */
.stat-bar { display: flex; gap: 1rem; margin-bottom: 1.25rem; }
.stat-item { flex: 1; text-align: center; padding: 0.75rem; border-radius: 10px; background: #f8fafc; border: 1px solid #f1f5f9; }
.stat-num  { font-size: 1.5rem; font-weight: 800; line-height: 1; }
.stat-lbl  { font-size: 0.7rem; color: #94a3b8; margin-top: 4px; text-transform: uppercase; letter-spacing: 0.04em; }

/* ── Dark Mode Overrides ─────────────────────────── */
.dark .ab-card { background: #111827; border-color: #1f2937; }
.dark .ab-card-head { border-bottom-color: #1f2937; }
.dark .ab-card-head span { color: #f1f5f9 !important; }
.dark .ab-input { background: #1f2937; border-color: #374151; color: #f1f5f9; }
.dark .ab-input:focus { border-color: #f59e0b; }
.dark .stat-item { background: #1f2937; border-color: #374151; }
.dark .stat-num { color: #f1f5f9 !important; }
.dark .log-name { color: #f1f5f9; }
.dark .log-sub { color: #94a3b8; }
.dark .log-time { color: #cbd5e1; }
.dark .log-item.ok { background: rgba(245, 158, 11, 0.1); border-color: rgba(245, 158, 11, 0.2); }
.dark .log-item.err { background: rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.2); }
.dark .log-item.dup { background: rgba(99, 102, 241, 0.1); border-color: rgba(99, 102, 241, 0.2); }
.dark h1 { color: #f1f5f9 !important; }
.dark .btn-close { background: #1f2937; color: #94a3b8; }

@keyframes fadeIn { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endpush

@section('content')

{{-- Header --}}
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; flex-wrap:wrap; gap:0.75rem;">
    <div>
        <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.25rem;">
            <div style="width:8px; height:8px; border-radius:50%; background:#f59e0b; animation: pulse 2s infinite;"></div>
            <span style="font-size:0.75rem; font-weight:600; color:#d97706; text-transform:uppercase; letter-spacing:0.06em;">Absensi Pulang</span>
        </div>
        <h1 style="font-size:1.5rem; font-weight:700; color:var(--text-primary); margin:0;">Scan Absen Pulang</h1>
        <p style="font-size:0.875rem; color:var(--text-muted); margin:0.25rem 0 0;">{{ now()->translatedFormat('l, d F Y') }}</p>
    </div>
    <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
        <a href="{{ route('absensi.scan.masuk.page') }}" class="btn-secondary">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h14"/></svg>
            Absen Masuk
        </a>
        <a href="{{ route('absensi.rekap') }}" class="btn-secondary">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Rekap
        </a>
    </div>
</div>

<div class="ab-page">

    {{-- Kolom Kiri: Scanner --}}
    <div>
        <div class="ab-card">
            <div class="ab-card-head">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#f59e0b" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><path stroke-linecap="round" d="M14 14h.01M18 14h.01M14 18h.01M18 18h.01M14 22h.01"/></svg>
                <span style="font-size:0.9rem; font-weight:600; color:#0f172a;">Kamera Barcode / QR</span>
                <span style="margin-left:auto; font-size:0.7rem; font-weight:700; color:#d97706; background:#fef3c7; padding:0.2rem 0.6rem; border-radius:6px; letter-spacing:0.04em;">MODE PULANG</span>
            </div>
            <div class="ab-card-body">
                {{-- Area kamera --}}
                <div class="scanner-wrap" id="scanner-wrap">
                    <div class="scanner-idle" id="scanner-idle">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9V6a3 3 0 013-3h3M3 15v3a3 3 0 003 3h3m6-18h3a3 3 0 013 3v3m0 6v3a3 3 0 01-3 3h-3"/></svg>
                        Kamera belum aktif.<br>Klik tombol di bawah untuk mulai scan.
                    </div>
                    <video id="scanner-video" playsinline muted autoplay style="display:none;"></video>
                    <canvas id="scanner-canvas"></canvas>
                    <div class="scanner-overlay" id="scanner-overlay" style="display:none;">
                        <div class="scanner-frame">
                            <div class="scanner-corner-bl"></div>
                            <div class="scanner-corner-br"></div>
                            <div class="scanner-line"></div>
                        </div>
                    </div>
                </div>

                {{-- Kontrol kamera --}}
                <div style="margin-top:0.75rem; display:flex; gap:0.5rem;">
                    <button id="btn-toggle" onclick="toggleCamera()" class="btn-scan-toggle btn-open">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.723v6.554a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
                        <span id="btn-toggle-text">Buka Kamera</span>
                    </button>
                </div>

                <div id="cam-error" style="display:none; margin-top:0.75rem; padding:0.75rem; border-radius:10px; background:#fff1f2; border:1px solid #fecdd3; font-size:0.8rem; color:#be185d;"></div>

                {{-- Divider --}}
                <div style="display:flex; align-items:center; gap:0.75rem; margin:1.25rem 0;">
                    <div style="flex:1; height:1px; background:#f1f5f9;"></div>
                    <span style="font-size:0.75rem; color:#cbd5e1; font-weight:500;">atau input manual</span>
                    <div style="flex:1; height:1px; background:#f1f5f9;"></div>
                </div>

                {{-- Input manual --}}
                <div class="ab-input-group">
                    <input id="nisn-input" type="text" class="ab-input" placeholder="Ketik / scan NISN siswa, tekan Enter..." autofocus>
                    <button onclick="submitManual()" class="ab-btn-submit">Catat</button>
                </div>

                {{-- Feedback --}}
                <div id="feedback" style="display:none; margin-top:0.75rem; padding:0.75rem 1rem; border-radius:10px; font-size:0.875rem; font-weight:600;"></div>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Log --}}
    <div style="display:flex; flex-direction:column; gap:1rem;">

        {{-- Statistik hari ini --}}
        <div class="ab-card">
            <div class="ab-card-body" style="padding:1.25rem;">
                <div style="font-size:0.75rem; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:1rem;">Hari ini</div>
                <div class="stat-bar">
                    <div class="stat-item">
                        <div class="stat-num" id="stat-pulang" style="color:#f59e0b;">0</div>
                        <div class="stat-lbl">Pulang</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-num" id="stat-gagal" style="color:#ef4444;">0</div>
                        <div class="stat-lbl">Gagal</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-num" id="stat-scan" style="color:#6366f1;">0</div>
                        <div class="stat-lbl">Total Scan</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Log scan --}}
        <div class="ab-card" style="flex:1;">
            <div class="ab-card-head">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#f59e0b" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span style="font-size:0.875rem; font-weight:600; color:#0f172a;">Log Scan</span>
                <button onclick="clearLog()" style="margin-left:auto; font-size:0.75rem; color:#94a3b8; background:none; border:none; cursor:pointer; padding:0.2rem 0.5rem;">Hapus</button>
            </div>
            <div class="ab-card-body" style="padding:1rem;">
                <div class="scan-log" id="scan-log">
                    <div class="log-empty" id="log-empty">Belum ada scan hari ini.</div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
const CSRF    = '{{ csrf_token() }}';
const URL_API = '{{ url("/absensi/scan-pulang") }}';

let stream      = null;
let scanLoop    = null;   // setInterval ID atau rAF ID
let useInterval = false;  // true jika pakai setInterval
let processing  = false;
let barcodeDetector = ('BarcodeDetector' in window)
    ? new BarcodeDetector({ formats: ['qr_code', 'code_128', 'code_39', 'ean_13'] })
    : null;

let stats = { pulang: 0, gagal: 0, scan: 0 };

function updateStats(type) {
    stats.scan++;
    if (type === 'ok' || type === 'dup') stats.pulang++;
    else stats.gagal++;
    document.getElementById('stat-pulang').textContent = stats.pulang;
    document.getElementById('stat-gagal').textContent  = stats.gagal;
    document.getElementById('stat-scan').textContent   = stats.scan;
}

async function toggleCamera() {
    if (stream) { stopCamera(); return; }
    await startCamera();
}

async function startCamera() {
    const txt = document.getElementById('btn-toggle-text');
    txt.textContent = 'Membuka...';

    // Minta resolusi lebih rendah — cukup untuk scan QR, jauh lebih ringan di CPU
    const constraints = {
        video: { facingMode: { ideal: 'environment' }, width: { ideal: 640, max: 1280 }, height: { ideal: 480, max: 720 } }
    };
    try {
        stream = await navigator.mediaDevices.getUserMedia(constraints)
            .catch(() => navigator.mediaDevices.getUserMedia({ video: { width: { ideal: 640 }, height: { ideal: 480 } } }));
    } catch (e) {
        document.getElementById('cam-error').style.display = 'block';
        document.getElementById('cam-error').textContent = 'Gagal membuka kamera: ' + e.message;
        txt.textContent = 'Buka Kamera';
        return;
    }

    const video   = document.getElementById('scanner-video');
    const idle    = document.getElementById('scanner-idle');
    const overlay = document.getElementById('scanner-overlay');
    const settings = stream.getVideoTracks()[0].getSettings();
    video.style.transform = (settings.facingMode === 'user') ? 'scaleX(-1)' : 'none';
    video.srcObject = stream;
    video.style.display = 'block';
    idle.style.display  = 'none';
    overlay.style.display = 'flex';
    video.play();

    document.getElementById('btn-toggle').className = 'btn-scan-toggle btn-close';
    txt.textContent = 'Tutup Kamera';

    video.addEventListener('loadeddata', startScanLoop, { once: true });
}

function startScanLoop() {
    const video  = document.getElementById('scanner-video');
    const canvas = document.getElementById('scanner-canvas');
    const ctx    = canvas.getContext('2d', { willReadFrequently: true });

    if (barcodeDetector) {
        // ── Optimasi: setInterval 250ms, bukan rAF per-frame ────────
        useInterval = true;
        let detecting = false;
        scanLoop = setInterval(async () => {
            if (!stream || processing || detecting || video.readyState < video.HAVE_ENOUGH_DATA) return;
            detecting = true;
            try {
                const results = await barcodeDetector.detect(video);
                if (results.length > 0 && !processing) onDetect(results[0].rawValue);
            } catch (_) {}
            detecting = false;
        }, 250);
        return;
    }

    // ── jsQR fallback: canvas diperkecil ke 480×360 ─────────────────
    const SCAN_W = 480, SCAN_H = 360;
    canvas.width = SCAN_W; canvas.height = SCAN_H;
    useInterval = false;

    function tick() {
        if (!stream) return;
        if (video.readyState >= video.HAVE_ENOUGH_DATA && !processing) {
            ctx.drawImage(video, 0, 0, SCAN_W, SCAN_H);
            const imgData = ctx.getImageData(0, 0, SCAN_W, SCAN_H);
            const code    = jsQR(imgData.data, SCAN_W, SCAN_H, { inversionAttempts: 'dontInvert' });
            if (code) onDetect(code.data);
        }
        if (stream) scanLoop = requestAnimationFrame(tick);
    }
    scanLoop = requestAnimationFrame(tick);
}

function stopCamera() {
    if (scanLoop) {
        if (useInterval) clearInterval(scanLoop);
        else cancelAnimationFrame(scanLoop);
        scanLoop = null;
    }
    if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; }
    const video = document.getElementById('scanner-video');
    video.srcObject = null;
    video.style.display = 'none';
    document.getElementById('scanner-idle').style.display    = 'block';
    document.getElementById('scanner-overlay').style.display = 'none';
    document.getElementById('btn-toggle').className = 'btn-scan-toggle btn-open';
    document.getElementById('btn-toggle-text').textContent = 'Buka Kamera';
}

let lastScanValue = '';
let lastScanTime  = 0;

function onDetect(value) {
    const now = Date.now();
    if (value === lastScanValue && now - lastScanTime < 3000) {
        return;
    }
    lastScanValue = value;
    lastScanTime  = now;

    processing = true;
    kirim(value.trim());
    // 1200ms cooldown — cukup hindari duplikat, tidak terasa lambat
    setTimeout(() => { processing = false; }, 1200);
}

function submitManual() {
    const input = document.getElementById('nisn-input');
    const nisn  = input.value.trim();
    if (!nisn) { showFeedback(false, 'Masukkan NISN terlebih dahulu.'); return; }
    input.value = '';
    kirim(nisn);
    input.focus();
}

document.getElementById('nisn-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); submitManual(); }
});

function kirim(nisn) {
    showFeedback(null, 'Memproses ' + nisn + '...');
    fetch(URL_API, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify({ nisn })
    })
    .then(r => r.ok ? r.json() : r.text().then(t => { throw new Error('HTTP ' + r.status); }))
    .then(data => {
        const type = !data.success ? 'err' : (data.sudah ? 'dup' : 'ok');
        showFeedback(data.success, data.message);
        addLog(type, data.nama || nisn, data.jam || '', data.message);
        updateStats(type);
    })
    .catch(err => {
        showFeedback(false, 'Error: ' + err.message);
        addLog('err', nisn, '', err.message);
        updateStats('err');
    });
}

function showFeedback(success, msg) {
    const el = document.getElementById('feedback');
    el.style.display = 'block';
    if (success === null) {
        el.style.background = '#f1f5f9'; el.style.color = '#475569'; el.style.border = '1px solid #e2e8f0';
    } else if (success) {
        el.style.background = '#fffbeb'; el.style.color = '#b45309'; el.style.border = '1px solid #fde68a';
    } else {
        el.style.background = '#fff1f2'; el.style.color = '#be185d'; el.style.border = '1px solid #fecdd3';
    }
    el.textContent = msg;
    clearTimeout(el._timer);
    if (success !== null) el._timer = setTimeout(() => { el.style.display = 'none'; }, 4000);
}

function addLog(type, nama, jam, msg) {
    document.getElementById('log-empty').style.display = 'none';
    const icons = { ok: '↵', err: '✕', dup: '⟳' };
    const subs  = { ok: 'Absen pulang tercatat', err: msg, dup: 'Sudah absen pulang' };
    const item  = document.createElement('div');
    item.className = 'log-item ' + type;
    item.innerHTML = `
        <div class="log-icon">${icons[type]}</div>
        <div class="log-info">
            <div class="log-name">${escHtml(nama)}</div>
            <div class="log-sub">${escHtml(subs[type])}</div>
        </div>
        ${jam ? `<div class="log-time">${escHtml(jam)}</div>` : ''}
    `;
    const log = document.getElementById('scan-log');
    log.insertBefore(item, log.firstChild);
}

function clearLog() {
    const log = document.getElementById('scan-log');
    log.innerHTML = '<div class="log-empty" id="log-empty">Belum ada scan hari ini.</div>';
    stats = { pulang: 0, gagal: 0, scan: 0 };
    ['stat-pulang','stat-gagal','stat-scan'].forEach(id => document.getElementById(id).textContent = '0');
}

function escHtml(str) {
    return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}
</script>

<style>
@keyframes pulse { 0%,100%{opacity:1}50%{opacity:.4} }
</style>

@endsection
