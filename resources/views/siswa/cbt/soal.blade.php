<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Soal {{ $no }} - {{ $sesi->ujian->judul }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #F4F7FE;
            min-height: 100vh;
            margin: 0;
            user-select: none; /* Anti copy-paste */
        }

        /* Timer Bar */
        #timer-bar { position: fixed; top: 0; left: 0; right: 0; z-index: 100; }
        #timer-progress { height: 3px; background: #4318FF; transition: width 1s linear; }
        #timer-bar.danger #timer-progress { background: #EF4444; }
        #timer-bar.danger { animation: pulse-bg 1s infinite; }
        @keyframes pulse-bg {
            0%, 100% { background: transparent; }
            50% { background: rgba(239, 68, 68, 0.05); }
        }

        /* Header */
        .exam-header {
            background: white;
            border-bottom: 1px solid #E2E8F0;
            padding: 10px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 3px;
            position: sticky;
            top: 3px;
            z-index: 90;
        }
        .exam-title { font-size: 0.9rem; font-weight: 700; color: #2B3674; max-width: 40%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .timer-display {
            display: flex; align-items: center; gap: 6px;
            background: #4318FF; color: white;
            padding: 6px 12px; border-radius: 20px;
            font-size: 0.85rem; font-weight: 700;
        }
        .timer-display.danger { background: #EF4444; }
        .btn-submit {
            padding: 7px 16px; background: #EF4444; color: white;
            border: none; border-radius: 10px; font-size: 0.82rem;
            font-weight: 700; cursor: pointer; font-family: inherit;
            transition: background 0.2s;
        }
        .btn-submit:hover { background: #DC2626; }

        /* Layout */
        .exam-layout { display: flex; min-height: calc(100vh - 50px); }

        /* Sidebar navigator */
        .navigator-panel {
            width: 240px; background: white; border-right: 1px solid #E2E8F0;
            padding: 16px; display: none; flex-direction: column; gap: 12px;
            position: sticky; top: 53px; height: calc(100vh - 53px);
            overflow-y: auto;
        }
        .navigator-panel h4 { font-size: 0.8rem; font-weight: 700; color: #A3AED0; text-transform: uppercase; letter-spacing: 0.05em; }
        .nav-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 6px; }
        .nav-btn {
            width: 36px; height: 36px; border-radius: 8px; border: 2px solid #E2E8F0;
            background: white; font-size: 0.78rem; font-weight: 700; cursor: pointer;
            transition: all 0.15s; font-family: inherit; color: #2B3674;
            display: flex; justify-content: center; align-items: center; text-decoration: none;
        }
        .nav-btn:hover { border-color: #4318FF; color: #4318FF; }
        .nav-btn.active { background: #4318FF; color: white; border-color: #4318FF; }
        .nav-btn.answered { background: #D1FAE5; border-color: #10B981; color: #065F46; }
        .nav-btn.ragu { background: #FEF3C7; border-color: #F59E0B; color: #92400E; }
        .nav-btn.active.answered { background: #4318FF; color: white; }

        /* Main soal */
        .soal-main { flex: 1; padding: 24px 16px; max-width: 760px; margin: 0 auto; width: 100%; }

        /* Card soal */
        .soal-card { background: white; border-radius: 20px; border: 1px solid #E2E8F0; overflow: hidden; margin-bottom: 16px; }
        .soal-num { padding: 16px 20px 0; }
        .soal-num span { display: inline-flex; align-items: center; gap: 6px; font-size: 0.78rem; font-weight: 700; color: #A3AED0; text-transform: uppercase; letter-spacing: 0.05em; }
        .badge { padding: 2px 8px; border-radius: 20px; font-size: 0.7rem; font-weight: 700; }
        .soal-text { padding: 16px 20px 20px; font-size: 1rem; font-weight: 500; color: #2B3674; line-height: 1.7; }
        .soal-text img { max-width: 100%; border-radius: 10px; }

        /* Options */
        .options { padding: 0 16px 20px; display: flex; flex-direction: column; gap: 10px; }
        .option-label {
            display: flex; align-items: flex-start; gap: 12px; padding: 14px 16px;
            border: 2px solid #E2E8F0; border-radius: 14px; cursor: pointer;
            transition: all 0.15s; background: white;
        }
        .option-label:hover { border-color: #4318FF; background: #F4F7FE; }
        .option-label.selected { border-color: #4318FF; background: #EEF2FF; }
        .option-mark {
            flex-shrink: 0; width: 28px; height: 28px; border-radius: 50%;
            border: 2px solid #CBD5E1; display: flex; align-items: center; justify-content: center;
            font-size: 0.78rem; font-weight: 800; color: #94A3B8; transition: all 0.15s;
        }
        .option-label.selected .option-mark { background: #4318FF; border-color: #4318FF; color: white; }
        .option-label input[type="radio"], .option-label input[type="checkbox"] { display: none; }
        .option-text { font-size: 0.9rem; color: #2B3674; line-height: 1.5; padding-top: 2px; }

        /* Essay textarea */
        .essay-box {
            width: 100%; padding: 14px 16px; border: 2px solid #E2E8F0; border-radius: 14px;
            font-family: inherit; font-size: 0.9rem; color: #2B3674; resize: vertical;
            min-height: 140px; outline: none; transition: border-color 0.15s;
        }
        .essay-box:focus { border-color: #4318FF; }

        /* Bottom nav */
        .soal-nav-bottom {
            display: flex; align-items: center; justify-content: space-between; gap: 10px;
            padding: 16px; background: white; border-radius: 16px;
            border: 1px solid #E2E8F0;
        }
        .btn-nav {
            display: flex; align-items: center; gap: 6px;
            padding: 10px 18px; border-radius: 12px; font-family: inherit;
            font-size: 0.85rem; font-weight: 700; cursor: pointer; transition: all 0.15s; border: none;
        }
        .btn-prev { background: #F4F7FE; color: #4318FF; }
        .btn-prev:hover { background: #E8EDFF; }
        .btn-next { background: #4318FF; color: white; }
        .btn-next:hover { background: #3B10FF; }
        .btn-prev:disabled, .btn-next:disabled { opacity: 0.4; cursor: not-allowed; }

        /* Ragu-ragu toggle */
        .ragu-toggle { display: flex; align-items: center; gap: 8px; cursor: pointer; }
        .ragu-toggle input { width: 16px; height: 16px; cursor: pointer; }
        .ragu-toggle span { font-size: 0.8rem; font-weight: 600; color: #F59E0B; }

        @media (min-width: 900px) {
            .navigator-panel { display: flex; }
            .soal-main { padding: 24px 32px; }
        }

        /* Modal submit */
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 200; display: none; align-items: center; justify-content: center; }
        .modal-overlay.show { display: flex; }
        .modal-box { background: white; border-radius: 20px; padding: 28px; max-width: 400px; width: 90%; text-align: center; }

        /* Toast Autosave */
        #autosave-toast {
            position: fixed; bottom: 20px; right: 20px; background: #10B981; color: white;
            padding: 10px 20px; border-radius: 12px; font-size: 0.85rem; font-weight: 600;
            display: flex; align-items: center; gap: 8px; opacity: 0; transform: translateY(10px);
            transition: all 0.3s; z-index: 100;
        }
        #autosave-toast.show { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body>

<div id="autosave-toast">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    Tersimpan
</div>

{{-- Timer Progress Bar --}}
<div id="timer-bar">
    <div id="timer-progress" style="width: 100%"></div>
</div>

{{-- Exam Header --}}
<header class="exam-header">
    <div class="exam-title" title="{{ $sesi->ujian->judul }}">{{ $sesi->ujian->judul }}</div>
    <div id="timer-display" class="timer-display">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span id="timer-text">--:--</span>
    </div>
    <button class="btn-submit" onclick="showSubmitModal()">Kumpulkan</button>
</header>

{{-- Layout --}}
<div class="exam-layout">

    {{-- Navigator Sidebar --}}
    <aside class="navigator-panel">
        <h4>Navigasi Soal</h4>
        <div class="nav-grid">
            @foreach($semuaJawaban as $nav)
            @php
                $isDijawab = $nav->cbt_opsi_jawaban_id || $nav->jawaban_essay;
                $isRagu = $nav->ragu_ragu;
                $isActive = $nav->urutan == $no;
            @endphp
            <a href="{{ route('siswa.ujian.soal', [$sesi->id, $nav->urutan]) }}" 
               class="nav-btn {{ $isActive ? 'active' : '' }} {{ $isRagu ? 'ragu' : '' }} {{ $isDijawab ? 'answered' : '' }}">
                {{ $nav->urutan }}
            </a>
            @endforeach
        </div>
        <div style="margin-top: auto; font-size: 0.72rem; color: #A3AED0; line-height: 1.6;">
            <div style="display:flex;gap:6px;align-items:center;margin-bottom:4px;"><div style="width:14px;height:14px;background:#D1FAE5;border:2px solid #10B981;border-radius:4px;"></div> Dijawab</div>
            <div style="display:flex;gap:6px;align-items:center;margin-bottom:4px;"><div style="width:14px;height:14px;background:#FEF3C7;border:2px solid #F59E0B;border-radius:4px;"></div> Ragu-ragu</div>
            <div style="display:flex;gap:6px;align-items:center;"><div style="width:14px;height:14px;background:#4318FF;border-radius:4px;"></div> Sekarang</div>
        </div>
    </aside>

    {{-- Main soal area --}}
    <div class="soal-main">
        <form id="jawaban-form" onsubmit="event.preventDefault(); navigasi('next');">
            @csrf
            <input type="hidden" name="urutan" value="{{ $no }}">

            {{-- Kartu soal --}}
            <div class="soal-card">
                <div class="soal-num">
                    <span>
                        Soal {{ $no }} / {{ $totalSoal }}
                        @if($jawabanSaatIni->bankSoal->mapel)
                            &bull; <span class="badge" style="background:#EEF2FF; color:#4318FF;">{{ $jawabanSaatIni->bankSoal->mapel->nama }}</span>
                        @endif
                        <span class="badge ml-1 {{ $jawabanSaatIni->bankSoal->tingkat_kesulitan === 'hard' ? 'bg-red-100 text-red-600' : ($jawabanSaatIni->bankSoal->tingkat_kesulitan === 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                            {{ ucfirst($jawabanSaatIni->bankSoal->tingkat_kesulitan) }}
                        </span>
                    </span>
                </div>
                <div class="soal-text">{!! $jawabanSaatIni->bankSoal->pertanyaan !!}
                    @if($jawabanSaatIni->bankSoal->file_media)
                        <img src="{{ asset('storage/' . $jawabanSaatIni->bankSoal->file_media) }}" alt="Media Soal" class="mt-3 max-w-full rounded-lg">
                    @endif
                </div>

                {{-- Pilihan Jawaban --}}
                @if($jawabanSaatIni->bankSoal->tipe_soal === 'pg')
                <div class="options">
                    @php 
                        $letters = ['A','B','C','D','E']; 
                        $savedOpsi = $jawabanSaatIni->cbt_opsi_jawaban_id; 
                        
                        // Menampilkan opsi sesuai urutan acak yang disimpan atau default
                        $opsiList = $jawabanSaatIni->bankSoal->opsiJawabans;
                        if ($jawabanSaatIni->opsi_order) {
                            $orderedOpsis = collect();
                            foreach($jawabanSaatIni->opsi_order as $oid) {
                                $f = $opsiList->firstWhere('id', $oid);
                                if($f) $orderedOpsis->push($f);
                            }
                            $opsiList = $orderedOpsis;
                        }
                    @endphp

                    @foreach($opsiList as $i => $opsi)
                    <label class="option-label {{ $savedOpsi == $opsi->id ? 'selected' : '' }}" onclick="selectOption(this)">
                        <input type="radio" name="cbt_opsi_jawaban_id" value="{{ $opsi->id }}" {{ $savedOpsi == $opsi->id ? 'checked' : '' }} onchange="autoSave()">
                        <div class="option-mark">{{ $letters[$i] ?? ($i+1) }}</div>
                        <div class="option-text">{!! $opsi->teks_opsi !!}</div>
                    </label>
                    @endforeach
                </div>
                @else
                {{-- Essay --}}
                <div style="padding: 0 20px 20px;">
                    <textarea name="jawaban_essay" class="essay-box" placeholder="Tuliskan jawaban Anda di sini..." oninput="debounceAutoSave()">{{ $jawabanSaatIni->jawaban_essay ?? '' }}</textarea>
                </div>
                @endif
            </div>

            {{-- Ragu-ragu + Nav --}}
            <div class="soal-nav-bottom">
                <button type="button" class="btn-nav btn-prev" onclick="navigasi('prev')" {{ $no <= 1 ? 'disabled' : '' }}>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Sebelumnya
                </button>

                <label class="ragu-toggle">
                    <input type="hidden" name="ragu_ragu" value="0">
                    <input type="checkbox" name="ragu_ragu" value="1" id="ragu_check" {{ $jawabanSaatIni->ragu_ragu ? 'checked' : '' }} onchange="autoSave()">
                    <span>🤔 Ragu-ragu</span>
                </label>

                @if($no < $totalSoal)
                <button type="button" class="btn-nav btn-next" onclick="navigasi('next')">
                    Selanjutnya
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
                @else
                <button type="button" class="btn-nav btn-next" onclick="showSubmitModal()" style="background: #EF4444;">
                    Kumpulkan
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </button>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Submit Confirmation Modal --}}
<div id="submit-modal" class="modal-overlay">
    <div class="modal-box">
        <div style="width:60px;height:60px;background:#FEE2E2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <svg style="width:28px;height:28px;color:#EF4444;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <h3 style="font-size:1.1rem;font-weight:800;color:#2B3674;margin-bottom:8px;">Kumpulkan Ujian?</h3>
        <p style="font-size:0.875rem;color:#A3AED0;margin-bottom:20px;" id="modal-info">
            @php 
                $dijawabCount = $semuaJawaban->filter(fn($j) => $j->cbt_opsi_jawaban_id || $j->jawaban_essay)->count(); 
            @endphp
            {{ $dijawabCount }} dari {{ $totalSoal }} soal telah dijawab. Pastikan semua soal telah terisi.
        </p>
        <div style="display:flex;gap:10px;">
            <button onclick="closeSubmitModal()" style="flex:1;padding:10px;border-radius:12px;border:2px solid #E2E8F0;background:white;font-family:inherit;font-size:0.875rem;font-weight:700;color:#64748B;cursor:pointer;">Batal</button>
            <form action="{{ route('siswa.ujian.submit', $sesi->id) }}" method="POST" style="flex:1;">
                @csrf
                <button type="submit" style="width:100%;padding:10px;border-radius:12px;border:none;background:#EF4444;color:white;font-family:inherit;font-size:0.875rem;font-weight:700;cursor:pointer;">
                    Ya, Kumpulkan
                </button>
            </form>
        </div>
    </div>
</div>

<script>
// Timer Logic
let sisaWaktu = {{ $sisaWaktu }};
const totalWaktu = {{ $sesi->ujian->durasi * 60 }};
const timerText = document.getElementById('timer-text');
const timerDisplay = document.getElementById('timer-display');
const timerBar = document.getElementById('timer-bar');
const timerProgress = document.getElementById('timer-progress');

function updateTimer() {
    if (sisaWaktu <= 0) {
        document.querySelector('form[action*="submit"]').submit();
        return;
    }
    const h = Math.floor(sisaWaktu / 3600);
    const m = Math.floor((sisaWaktu % 3600) / 60);
    const s = sisaWaktu % 60;
    timerText.textContent = (h > 0 ? String(h).padStart(2,'0') + ':' : '') +
        String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');

    const pct = (sisaWaktu / totalWaktu) * 100;
    timerProgress.style.width = pct + '%';

    if (sisaWaktu <= 300) { // 5 menit terakhir
        timerDisplay.classList.add('danger');
        timerBar.classList.add('danger');
    }

    sisaWaktu--;
}
updateTimer();
setInterval(updateTimer, 1000);

// Anti Cheat: Disable Right Click & Copy Paste
document.addEventListener('contextmenu', e => e.preventDefault());
document.addEventListener('copy', e => e.preventDefault());
document.addEventListener('paste', e => e.preventDefault());

// Anti Cheat: Tab Blur Detection
document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
        fetch('{{ route("siswa.ujian.log-blur", $sesi->id) }}', {
            method: 'POST',
            headers: { 
                'X-Requested-With': 'XMLHttpRequest', 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
            }
        });
        alert('PERINGATAN: Anda terdeteksi keluar dari halaman ujian! Aktivitas ini dicatat dalam sistem.');
    }
});

// Option selection styling
function selectOption(label) {
    document.querySelectorAll('.option-label').forEach(l => l.classList.remove('selected'));
    label.classList.add('selected');
    label.querySelector('input').checked = true;
    autoSave();
}

// Navigation
function navigasi(dir) {
    let target = {{ $no }};
    if (dir === 'next') target++;
    if (dir === 'prev') target--;
    
    if (target < 1 || target > {{ $totalSoal }}) return;
    window.location.href = '{{ url("siswa/ujian/" . $sesi->id . "/soal") }}/' + target;
}

// Modal
function showSubmitModal() { document.getElementById('submit-modal').classList.add('show'); }
function closeSubmitModal() { document.getElementById('submit-modal').classList.remove('show'); }

// Auto-save via AJAX
let saveTimeout;
function debounceAutoSave() {
    clearTimeout(saveTimeout);
    saveTimeout = setTimeout(autoSave, 1000);
}

function autoSave() {
    const form = document.getElementById('jawaban-form');
    const data = new FormData(form);
    
    fetch('{{ route("siswa.ujian.jawab", $sesi->id) }}', {
        method: 'POST',
        body: data,
        headers: { 
            'X-Requested-With': 'XMLHttpRequest', 
            'Accept': 'application/json' 
        }
    }).then(res => res.json())
      .then(res => {
          if(res.status === 'saved') {
              showToast();
          }
      });
}

function showToast() {
    const toast = document.getElementById('autosave-toast');
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 2000);
}
</script>
</body>
</html>
