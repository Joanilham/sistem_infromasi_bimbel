<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>QR Absensi — {{ $peserta->nama_lengkap }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        :root {
            --primary: #388782;
            --primary-hover: #2d6e6a;
            --primary-light: #f0fdfa;
            --secondary: #e2e8f0;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --bg-body: linear-gradient(135deg, #f0fdfa 0%, #f8fafc 100%);
            --white: #ffffff;
            --danger: #ef4444;
            --success: #10b981;
            --warning: #f59e0b;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-body);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            color: var(--text-main);
        }

        /* ── Card utama ─────────────────────────────── */
        .qr-card {
            background: var(--white);
            border: 1px solid rgba(56, 135, 130, 0.1);
            border-radius: 28px;
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 380px;
            text-align: center;
            box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.08);
            position: relative;
            overflow: hidden;
        }

        /* Decorative top accent line */
        .qr-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--primary) 0%, #51b3ac 100%);
        }

        /* ── Header ─────────────────────────────────── */
        .school-name {
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--primary);
            margin-bottom: 0.75rem;
        }
        .student-name {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 0.3rem;
            line-height: 1.3;
        }
        .student-nisn {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 600;
            letter-spacing: 0.05em;
        }

        /* ── QR Box ─────────────────────────────────── */
        .qr-box {
            position: relative;
            margin: 2rem 0;
            background: #f1f5f9;
            padding: 1.5rem;
            border-radius: 24px;
            box-shadow: inset 0 2px 8px rgba(15, 23, 42, 0.05);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 250px;
        }
        .qr-wrap {
            background: var(--white);
            border-radius: 20px;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08);
        }
        .qr-wrap.expired { 
            opacity: 0.2; 
            filter: blur(4px); 
        }

        /* ── Fix layout shift/offset canvas vs img ──── */
        #qr-canvas {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 200px;
            height: 200px;
        }
        #qr-canvas canvas, #qr-canvas img {
            border-radius: 12px;
            width: 200px;
            height: 200px;
        }

        /* ── Status/Countdown ───────────────────────── */
        .status-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        .dot-live {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--success);
            animation: pulse-dot 1.5s ease-in-out infinite;
            flex-shrink: 0;
        }
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
            50%       { opacity: 0.6; transform: scale(0.8); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        }
        .status-text {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--success);
            transition: color 0.3s;
        }
        .status-text.warning { color: var(--warning); }
        .status-text.expired { color: var(--danger); }

        /* ── Countdown Progress Bar ─────────────────── */
        .countdown-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-top: 0.75rem;
            padding: 0 0.5rem;
        }
        .countdown-bar-bg {
            flex: 1;
            height: 8px;
            background: var(--secondary);
            border-radius: 99px;
            overflow: hidden;
        }
        .countdown-bar {
            height: 100%;
            border-radius: 99px;
            transition: width 1s linear, background-color 0.5s;
            background: var(--success);
        }
        .countdown-bar.warning { background: var(--warning); }
        .countdown-bar.danger  { background: var(--danger); }
        .countdown-num {
            font-size: 0.85rem;
            font-weight: 800;
            color: var(--text-muted);
            min-width: 24px;
            text-align: right;
        }

        /* ── Overlay saat refresh ───────────────────── */
        .refresh-overlay {
            position: absolute;
            inset: 0;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.9);
            display: none;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 0.75rem;
            backdrop-filter: blur(4px);
            z-index: 10;
        }
        .refresh-overlay.show { display: flex; }
        .spinner {
            width: 36px; height: 36px;
            border: 4px solid var(--secondary);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .refresh-text { font-size: 0.85rem; color: var(--text-main); font-weight: 700; }

        /* ── Info bawah ─────────────────────────────── */
        .info-strip {
            margin-top: 1.5rem;
            padding: 1rem;
            background: #fffbeb;
            border: 1px solid rgba(245, 158, 11, 0.2);
            border-radius: 16px;
            font-size: 0.8rem;
            color: #b45309;
            font-weight: 600;
            line-height: 1.5;
            text-align: left;
            display: flex; gap: 10px; align-items: flex-start;
        }
        .info-strip svg { width: 20px; height: 20px; flex-shrink: 0; color: #d97706; }

        /* ── Back button ────────────────────────────── */
        .btn-back {
            margin-top: 1.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-muted);
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 16px;
            background: #f8fafc;
            border: 1px solid var(--secondary);
            transition: all 0.2s ease;
            width: 100%;
        }
        .btn-back:hover { 
            background: var(--primary-light); 
            color: var(--primary); 
            border-color: rgba(56, 135, 130, 0.2);
            transform: translateY(-1px);
        }
        .btn-back svg {
            transition: transform 0.2s ease;
        }
        .btn-back:hover svg {
            transform: translateX(-3px);
        }

        /* ── Overlay Locked Premium ───────────────────── */
        .locked-overlay {
            position: absolute;
            inset: 0;
            border-radius: 24px;
            background: rgba(254, 243, 199, 0.95); /* Amber 50 light */
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            backdrop-filter: blur(8px);
            z-index: 20;
            border: 2px solid rgba(245, 158, 11, 0.3);
            animation: fadeIn 0.4s ease-out;
        }
        .locked-overlay.show { display: flex; }
        
        .lock-icon-wrapper {
            background: rgba(245, 158, 11, 0.1);
            padding: 1rem;
            border-radius: 50%;
            margin-bottom: 1rem;
            border: 1px solid rgba(245, 158, 11, 0.25);
        }
        
        .locked-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #78350f;
            margin-bottom: 0.5rem;
        }
        
        .locked-text {
            font-size: 0.8rem;
            color: #92400e;
            font-weight: 600;
            line-height: 1.4;
            margin-bottom: 1.25rem;
            text-align: center;
        }
        
        .btn-pay-now {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            color: #ffffff;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 14px;
            background: #d97706;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(217, 119, 6, 0.3);
        }
        .btn-pay-now:hover {
            background: #b45309;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(217, 119, 6, 0.4);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>

<div class="qr-card">
    <div class="school-name">QR Absensi Siswa</div>
    <div class="student-name">{{ $peserta->nama_lengkap }}</div>
    <div class="student-nisn">NISN: {{ $peserta->nisn ?? '—' }}</div>

    <div class="qr-box">
        {{-- QR container --}}
        <div class="qr-wrap" id="qr-wrap">
            <div id="qr-canvas"></div>
        </div>

        {{-- Overlay refresh --}}
        <div class="refresh-overlay" id="refresh-overlay">
            <div class="spinner"></div>
            <div class="refresh-text">Memperbarui QR...</div>
        </div>

        {{-- Overlay Locked --}}
        <div class="locked-overlay" id="locked-overlay">
            <div class="lock-icon-wrapper">
                <svg class="w-12 h-12 text-amber-500 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <div class="locked-title">Akses QR Terkunci</div>
            <div class="locked-text" id="locked-text">⚠️ Batas waktu jatuh tempo pembayaran telah terlewati. Silakan selesaikan pembayaran tagihan Anda.</div>
            <a href="{{ route('siswa.pembayaran.index') }}" class="btn-pay-now">
                Bayar Tagihan Sekarang
            </a>
        </div>
    </div>

    {{-- Status live --}}
    <div class="status-row">
        <div class="dot-live" id="dot-live"></div>
        <span class="status-text" id="status-text">QR Aktif · Perbarui otomatis</span>
    </div>

    {{-- Countdown bar --}}
    <div class="countdown-wrap">
        <div class="countdown-bar-bg">
            <div class="countdown-bar" id="countdown-bar" style="width:100%"></div>
        </div>
        <div class="countdown-num" id="countdown-num">30</div>
    </div>

    {{-- Peringatan anti-cheat --}}
    <div class="info-strip">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <div>
            QR berubah setiap 30 detik. <strong>Screenshot tidak akan berfungsi</strong>. Tunjukkan layar ini langsung ke petugas.
        </div>
    </div>

    <a href="{{ route('siswa.dashboard') }}" class="btn-back">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Dashboard
    </a>
</div>

<script>
const TOKEN_URL = '{{ route("siswa.qr.token") }}';
const CSRF      = '{{ csrf_token() }}';

let qrInstance  = null;
let countdown   = 30;
let totalTime   = 30;
let tickTimer   = null;

// ── Muat token & buat QR ──────────────────────────────────────
async function loadToken() {
    showRefreshing(true);
    try {
        const res  = await fetch(TOKEN_URL, {
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        });

        if (res.status === 403) {
            const data = await res.json();
            showLockedOverlay(data.message || '⚠️ Akses ditangguhkan! Silakan lunasi tagihan Anda.');
            showRefreshing(false);
            return;
        }

        if (!res.ok) {
            throw new Error('Server error');
        }

        const data = await res.json();

        buildQr(data.qr_content);
        countdown = data.expires_in;
        totalTime = 30;
        startCountdown();
        showRefreshing(false);
    } catch (e) {
        setStatus('Gagal memuat QR. Coba refresh halaman.', 'expired');
        showRefreshing(false);
    }
}

// ── Tampilkan Overlay Terkunci Premium ────────────────────────
function showLockedOverlay(msg) {
    clearInterval(tickTimer);
    document.getElementById('locked-text').textContent = msg;
    document.getElementById('locked-overlay').classList.add('show');
    document.getElementById('qr-wrap').classList.add('expired');
    
    const dot = document.getElementById('dot-live');
    if (dot) {
        dot.style.background = 'var(--danger)';
        dot.style.animation = 'none';
    }
    
    setStatus('Akses Terkunci', 'expired');
    document.getElementById('countdown-bar').style.width = '0%';
    document.getElementById('countdown-num').textContent = '—';
}

// ── Buat QR code ──────────────────────────────────────────────
function buildQr(content) {
    const el = document.getElementById('qr-canvas');
    
    if (typeof QRCode === 'undefined') {
        el.innerHTML = '';
        // Dynamic bulletproof fallback using QR Code Generator API if Cloudflare CDN is blocked
        const img = document.createElement('img');
        img.src = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&color=0f172a&bgcolor=ffffff&data=${encodeURIComponent(content)}`;
        img.alt = 'QR Absensi';
        img.className = 'w-[200px] h-[200px] rounded-xl';
        img.style.display = 'block';
        el.appendChild(img);
        qrInstance = null;
    } else {
        if (qrInstance) {
            qrInstance.clear();
            qrInstance.makeCode(content);
        } else {
            el.innerHTML = '';
            qrInstance = new QRCode(el, {
                text:           content,
                width:          200,
                height:         200,
                colorDark:      '#0f172a',
                colorLight:     '#ffffff',
                correctLevel:   QRCode.CorrectLevel.H
            });
        }
    }
}

// ── Countdown ticker ──────────────────────────────────────────
function startCountdown() {
    clearInterval(tickTimer);
    tickTimer = setInterval(() => {
        countdown--;
        updateBar();

        if (countdown <= 0) {
            clearInterval(tickTimer);
            setStatus('QR kedaluwarsa — memperbarui...', 'expired');
            document.getElementById('qr-wrap').classList.add('expired');
            loadToken(); // Auto refresh
        }
    }, 1000);
    updateBar();
}

function updateBar() {
    const pct = Math.max(0, countdown / totalTime) * 100;
    const bar = document.getElementById('countdown-bar');
    const num = document.getElementById('countdown-num');

    num.textContent = countdown;
    bar.style.width = pct + '%';

    if (countdown <= 5) {
        bar.className = 'countdown-bar danger';
        setStatus('QR hampir kedaluwarsa!', 'warning');
    } else if (countdown <= 10) {
        bar.className = 'countdown-bar warning';
        setStatus('Segera scan sekarang!', 'warning');
    } else {
        bar.className = 'countdown-bar';
        setStatus('QR Aktif · Perbarui otomatis', '');
    }
}

function setStatus(msg, type) {
    const el = document.getElementById('status-text');
    el.textContent = msg;
    el.className   = 'status-text ' + (type || '');
}

function showRefreshing(show) {
    document.getElementById('refresh-overlay').classList.toggle('show', show);
    if (!show) document.getElementById('qr-wrap').classList.remove('expired');
}

// ── Mulai ─────────────────────────────────────────────────────
loadToken();

// ── Polling Status Absensi ────────────────────────────────────
let initialJamMasuk = '{{ $absensiToday->jam_masuk ?? "" }}';
let initialJamPulang = '{{ $absensiToday->jam_pulang ?? "" }}';

setInterval(async () => {
    try {
        const res = await fetch('{{ route("siswa.qr.status") }}', {
            headers: { 'Accept': 'application/json' }
        });
        
        if (res.status === 403) {
            const data = await res.json();
            showLockedOverlay(data.message || '⚠️ Akses ditangguhkan! Silakan lunasi tagihan Anda.');
            return;
        }
        
        if (!res.ok) return;
        const data = await res.json();
        
        if (data.jam_masuk && data.jam_masuk !== initialJamMasuk) {
            initialJamMasuk = data.jam_masuk;
            Swal.fire({
                title: 'Absen Masuk Berhasil!',
                text: 'Tercatat pada jam ' + data.jam_masuk,
                icon: 'success',
                timer: 3000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = '{{ route("siswa.dashboard") }}';
            });
        }
        
        else if (data.jam_pulang && data.jam_pulang !== initialJamPulang) {
            initialJamPulang = data.jam_pulang;
            Swal.fire({
                title: 'Absen Pulang Berhasil!',
                text: 'Tercatat pada jam ' + data.jam_pulang,
                icon: 'success',
                timer: 3000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = '{{ route("siswa.dashboard") }}';
            });
        }
    } catch (err) {
        // Abaikan error koneksi sementara
    }
}, 3000); // Cek setiap 3 detik
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
