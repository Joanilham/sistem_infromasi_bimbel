<?php
$f = __DIR__ . '/../resources/views/admin/absensi/pulang.blade.php';
$c = file_get_contents($f);

// Cari script block lama dan ganti
$jsStart = strpos($c, '<script>');
$jsEnd   = strrpos($c, '</script>') + strlen('</script>');

$newJs = <<<'JS'
<script>
const csrfToken = '{{ csrf_token() }}';
const urlPulang  = '{{ url("/absensi/scan-pulang") }}';
let videoStream  = null;
let scanLoop     = null;
let isProcessing = false;

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

// ── Start kamera dengan jsQR ──────────────────────────────────
async function startCamera() {
    const video  = document.getElementById('qr-video');
    const canvas = document.getElementById('qr-canvas');
    const ctx    = canvas.getContext('2d');
    const line   = document.getElementById('scan-line');

    try {
        videoStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: { ideal: 'environment' }, width: { ideal: 1280 }, height: { ideal: 720 } } });
    } catch (_) {
        try {
            videoStream = await navigator.mediaDevices.getUserMedia({ video: true });
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
            canvas.width  = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const code    = jsQR(imgData.data, imgData.width, imgData.height, { inversionAttempts: 'dontInvert' });
            if (code && !isProcessing) {
                onScanSuccess(code.data);
            }
        }
        scanLoop = requestAnimationFrame(tick);
    }
    video.addEventListener('loadeddata', () => { scanLoop = requestAnimationFrame(tick); }, { once: true });
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
        const c6 = row.querySelector('td:nth-child(6)');
        if (c6) c6.innerHTML = `<span class="badge" style="background:#fef3c7;color:#d97706;display:inline-flex;justify-content:center;">${jam}</span>`;
        const c7 = row.querySelector('td:nth-child(7)');
        if (c7) c7.innerHTML = `<span style="background:#fef3c7;color:#d97706;padding:0.3rem 0.8rem;border-radius:8px;font-size:0.75rem;font-weight:700;">Sudah Pulang</span>`;
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
    fetch(urlPulang, {
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
    el.style.backgroundColor = success ? '#fffbeb' : '#fff1f2';
    el.style.color            = success ? '#b45309' : '#be185d';
    el.style.border           = `1px solid ${success ? '#fde68a' : '#ffe4e6'}`;
    el.textContent            = (success ? '✅ ' : '❌ ') + msg;
    clearTimeout(el._t);
    el._t = setTimeout(() => el.classList.add('hidden'), 4000);
}

document.getElementById('scan-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); scanAbsen(); }
});
</script>
@endsection
JS;

// Ganti dari script lama sampai @endsection
$before = substr($c, 0, $jsStart);
$clean  = $before . $newJs . "\n";
file_put_contents($f, $clean);
echo 'OK - total lines: ' . count(file($f)) . "\n";
