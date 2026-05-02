<?php
$files = [
    __DIR__ . '/../resources/views/admin/absensi/masuk.blade.php' => 'urlMasuk',
    __DIR__ . '/../resources/views/admin/absensi/pulang.blade.php' => 'urlPulang',
];

foreach ($files as $f => $urlVar) {
    if (!file_exists($f)) continue;
    $c = file_get_contents($f);
    
    // Temukan bagian script
    $jsStart = strpos($c, '<script>');
    $jsEnd   = strpos($c, '</script>', $jsStart) + strlen('</script>');
    if ($jsStart === false) continue;
    
    // Tentukan badge dan pesan untuk tabel berdasarkan file
    $isMasuk = strpos($urlVar, 'Masuk') !== false;
    $badgeStyle = $isMasuk ? 'badge-success' : '';
    $badgeColor = $isMasuk ? '#047857' : '#d97706';
    $badgeBg = $isMasuk ? '#d1fae5' : '#fef3c7';
    $textSudah = $isMasuk ? 'Sudah Masuk' : 'Sudah Pulang';
    $jamCol = $isMasuk ? '5' : '6';
    $aksiCol = $isMasuk ? '6' : '7';

    $newJs = <<<JS
<script>
const csrfToken = '{{ csrf_token() }}';
const $urlVar  = '{{ url("/absensi/scan-" . ($isMasuk ? "masuk" : "pulang")) }}';
let videoStream  = null;
let scanLoop     = null;
let isProcessing = false;
let barcodeDetector = null;

if ('BarcodeDetector' in window) {
    barcodeDetector = new BarcodeDetector({ formats: ['qr_code'] });
}

// ── Search tabel ─────────────────────────────────────────────
document.getElementById('search-input-table').addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    const rows = document.querySelectorAll('.absensi-row');
    let visible = 0;
    rows.forEach(row => {
        const match = !q || (row.dataset.nama||'').includes(q) || (row.dataset.nisn||'').includes(q);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    document.getElementById('empty-search').classList.toggle('hidden', visible > 0 || !q);
    let no = 1;
    rows.forEach(row => { if (row.style.display !== 'none') { const c = row.querySelector('.row-no'); if (c) c.textContent = no++; } });
});

// ── Toggle kamera ─────────────────────────────────────────────
function toggleCamera() {
    const section = document.getElementById('camera-section');
    if (section.classList.contains('hidden')) {
        section.classList.remove('hidden');
        document.getElementById('btn-toggle-camera').textContent = 'Tutup Kamera';
        startCamera();
    } else {
        stopCamera();
    }
}

// ── Minta izin kamera ────────────────────────────────────────
async function mintaIzinKamera() {
    const btn = document.getElementById('btn-request-permission');
    btn.textContent = 'Meminta izin...'; btn.disabled = true;
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: true });
        stream.getTracks().forEach(t => t.stop());
        btn.textContent = 'Kamera Diizinkan';
        document.getElementById('permission-denied-info').classList.add('hidden');
        showResult(true, 'Izin kamera berhasil! Klik "Buka Kamera" untuk mulai scan.');
    } catch (err) {
        btn.textContent = 'Izinkan Kamera'; btn.disabled = false;
        document.getElementById('permission-denied-info').classList.remove('hidden');
        showResult(false, 'Izin ditolak: ' + err.message);
    }
}

// ── Start kamera dengan jsQR & BarcodeDetector ───────────────
async function startCamera() {
    const video  = document.getElementById('qr-video');
    const canvas = document.getElementById('qr-canvas');
    const ctx    = canvas.getContext('2d');
    const line   = document.getElementById('scan-line');

    try {
        // Coba buka kamera belakang dulu
        videoStream = await navigator.mediaDevices.getUserMedia({ 
            video: { facingMode: { ideal: 'environment' }, width: { ideal: 1280 }, height: { ideal: 720 } } 
        });
        const track = videoStream.getVideoTracks()[0];
        const settings = track.getSettings();
        // Jika kamera ternyata menghadap user (seperti laptop), buat efek cermin
        if (settings.facingMode === 'user') {
            video.style.transform = 'scaleX(-1)';
        } else {
            video.style.transform = 'none';
        }
    } catch (_) {
        try {
            // Fallback: buka kamera apa saja (biasanya webcam laptop)
            videoStream = await navigator.mediaDevices.getUserMedia({ video: true });
            // Webcam laptop biasanya harus di-mirror agar tidak membingungkan pengguna
            video.style.transform = 'scaleX(-1)'; 
        } catch (err) {
            showResult(false, 'Gagal membuka kamera: ' + err.message);
            stopCamera(); return;
        }
    }

    video.srcObject = videoStream;
    video.play();
    line.style.display = 'block';

    function tick() {
        if (!videoStream) return;
        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            
            // Prioritas 1: Gunakan Native BarcodeDetector (Sangat cepat & tahan blur/silau untuk Laptop)
            if (barcodeDetector) {
                barcodeDetector.detect(video).then(barcodes => {
                    if (barcodes.length > 0 && !isProcessing) {
                        onScanSuccess(barcodes[0].rawValue);
                    }
                }).catch(err => console.error('BarcodeDetector error:', err))
                .finally(() => {
                    if (videoStream) scanLoop = requestAnimationFrame(tick);
                });
                return; // Jangan jalankan jsQR jika BarcodeDetector sedang jalan
            }

            // Prioritas 2: Fallback ke jsQR
            canvas.width  = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            // attemptBoth membuat jsQR bisa mendeteksi meski kontras layar HP jelek
            const code    = jsQR(imgData.data, imgData.width, imgData.height, { inversionAttempts: 'attemptBoth' });
            
            if (code && !isProcessing) {
                onScanSuccess(code.data);
            }
        }
        scanLoop = requestAnimationFrame(tick);
    }
    
    video.addEventListener('loadeddata', () => { 
        scanLoop = requestAnimationFrame(tick); 
    }, { once: true });
}

// ── Stop kamera ───────────────────────────────────────────────
function stopCamera() {
    if (scanLoop)    { cancelAnimationFrame(scanLoop); scanLoop = null; }
    if (videoStream) { videoStream.getTracks().forEach(t => t.stop()); videoStream = null; }
    const video = document.getElementById('qr-video');
    if (video) video.srcObject = null;
    const line = document.getElementById('scan-line');
    if (line) line.style.display = 'none';
    document.getElementById('camera-section').classList.add('hidden');
    document.getElementById('btn-toggle-camera').textContent = 'Buka Kamera';
    document.getElementById('scan-preview').classList.add('hidden');
}

// ── Callback saat QR terbaca ──────────────────────────────────
function onScanSuccess(decodedText) {
    isProcessing = true;
    const nisn = decodedText.trim();
    kirimAbsen(nisn, function(data) {
        if (data.success) {
            const prev = document.getElementById('scan-preview');
            prev.classList.remove('hidden');
            document.getElementById('scan-preview-nama').textContent = data.nama ?? nisn;
            document.getElementById('scan-preview-nisn').textContent = 'NISN: ' + nisn;
            updateTableRow(data);
            setTimeout(() => { isProcessing = false; prev.classList.add('hidden'); }, 2000);
        } else {
            setTimeout(() => { isProcessing = false; }, 2000);
        }
    });
}

// ── Update baris tabel tanpa reload ──────────────────────────
function updateTableRow(data) {
    if (!data.nisn || !data.jam) return;
    document.querySelectorAll('.absensi-row').forEach(row => {
        if ((row.dataset.nisn || '') !== data.nisn) return;
        const jam = data.jam.substring(0, 5);
        const colJam = row.querySelector('td:nth-child($jamCol)');
        if (colJam) colJam.innerHTML = `<span class="badge" style="background:$badgeBg;color:$badgeColor;display:inline-flex;justify-content:center;">\${jam}</span>`;
        const colAksi = row.querySelector('td:nth-child($aksiCol)');
        if (colAksi) colAksi.innerHTML = `<span style="background:$badgeBg;color:$badgeColor;padding:0.3rem 0.8rem;border-radius:8px;font-size:0.75rem;font-weight:700;">$textSudah</span>`;
    });
}

// ── Input manual / barcode fisik ─────────────────────────────
function scanAbsen() {
    const nisn = document.getElementById('scan-input').value.trim();
    if (!nisn) { alert('Masukkan NISN terlebih dahulu.'); return; }
    kirimAbsen(nisn, function(data) {
        document.getElementById('scan-input').value = '';
        document.getElementById('scan-input').focus();
        if (data.success) setTimeout(() => location.reload(), 1800);
    });
}

// ── Core fetch ke server ──────────────────────────────────────
function kirimAbsen(nisn, callback) {
    showResult(true, 'Memproses: ' + nisn + '...');
    fetch($urlVar, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: JSON.stringify({ nisn })
    })
    .then(r => r.ok ? r.json() : r.text().then(t => { throw new Error('HTTP ' + r.status + ': ' + t.substring(0, 150)); }))
    .then(data => { showResult(data.success, data.message); if (callback) callback(data); })
    .catch(err => { showResult(false, 'Error: ' + err.message); if (callback) callback({ success: false }); });
}

// ── Tampilkan notifikasi ──────────────────────────────────────
function showResult(success, msg) {
    const el = document.getElementById('scan-result');
    el.classList.remove('hidden');
    el.style.backgroundColor = success ? '$badgeBg' : '#fff1f2';
    el.style.color            = success ? '$badgeColor' : '#be185d';
    el.style.border           = `1px solid \${success ? '$badgeBg' : '#ffe4e6'}`;
    el.textContent            = (success ? '✅ ' : '❌ ') + msg;
    clearTimeout(el._t);
    el._t = setTimeout(() => el.classList.add('hidden'), 4000);
}

document.getElementById('scan-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); scanAbsen(); }
});
</script>
JS;

    $before = substr($c, 0, $jsStart);
    $clean = $before . $newJs . "\n@endsection\n";
    file_put_contents($f, $clean);
}
echo "OK\n";
