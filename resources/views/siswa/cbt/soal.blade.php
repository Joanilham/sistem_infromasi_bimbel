<!DOCTYPE html>
<html lang="id" class="no-scrollbar">
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
            background: #F8FAFC;
            min-height: 100vh;
            margin: 0;
            user-select: none;
            color: #1E293B;
        }

        /* Dark Mode Support */
        @media (prefers-color-scheme: dark) {
            body { background: #09090B; color: #F1F5F9; }
            .bg-white { background-color: #18181B !important; }
            .border-slate-200 { border-color: #27272A !important; }
            .text-slate-800 { color: #F1F5F9 !important; }
            .text-slate-600 { color: #A1A1AA !important; }
        }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Timer Bar */
        #timer-bar { position: fixed; top: 0; left: 0; right: 0; z-index: 200; height: 4px; background: rgba(0,0,0,0.05); }
        #timer-progress { height: 100%; background: #388782; transition: width 1s linear; box-shadow: 0 0 10px rgba(56, 135, 130, 0.5); }
        #timer-bar.danger #timer-progress { background: #EF4444; box-shadow: 0 0 10px rgba(239, 68, 68, 0.5); }

        /* Header */
        .exam-header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-blur: 12px;
            border-bottom: 1px solid #E2E8F0;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        @media (prefers-color-scheme: dark) {
            .exam-header { background: rgba(24, 24, 27, 0.8); border-color: #27272A; }
        }

        .exam-title-wrapper { display: flex; flex-direction: column; max-width: 50%; }
        .exam-label { font-size: 10px; font-weight: 800; text-transform: uppercase; tracking-widest; color: #64748B; margin-bottom: 2px; }
        .exam-title { font-size: 0.95rem; font-weight: 800; color: #0F172A; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        @media (prefers-color-scheme: dark) { .exam-title { color: #F8FAFC; } }

        .timer-display {
            display: flex; align-items: center; gap: 8px;
            background: #F1F5F9; color: #388782;
            padding: 8px 16px; border-radius: 16px;
            font-size: 0.9rem; font-weight: 800; font-variant-numeric: tabular-nums;
            border: 1px solid #E2E8F0;
        }
        @media (prefers-color-scheme: dark) { 
            .timer-display { background: #27272A; color: #5EEAD4; border-color: #3F3F46; } 
        }
        .timer-display.danger { background: #FEF2F2; color: #EF4444; border-color: #FEE2E2; animation: pulse-danger 2s infinite; }
        @keyframes pulse-danger { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }

        .btn-submit {
            padding: 8px 20px; background: #EF4444; color: white;
            border: none; border-radius: 14px; font-size: 0.85rem;
            font-weight: 800; cursor: pointer; font-family: inherit;
            transition: all 0.2s; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
        }
        .btn-submit:hover { background: #DC2626; transform: translateY(-1px); box-shadow: 0 6px 16px rgba(239, 68, 68, 0.3); }

        /* Layout */
        .exam-layout { display: flex; min-height: calc(100vh - 65px); }

        /* Sidebar navigator */
        .navigator-panel {
            width: 300px; background: white; border-right: 1px solid #E2E8F0;
            padding: 24px; display: none; flex-direction: column; gap: 20px;
            position: sticky; top: 65px; height: calc(100vh - 65px);
            overflow-y: auto;
        }
        @media (prefers-color-scheme: dark) { 
            .navigator-panel { background: #18181B; border-color: #27272A; } 
        }
        .navigator-panel h4 { font-size: 0.75rem; font-weight: 800; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.1em; }
        .nav-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 8px; }
        .nav-btn {
            aspect-ratio: 1; border-radius: 12px; border: 2px solid #F1F5F9;
            background: #F8FAFC; font-size: 0.85rem; font-weight: 800; cursor: pointer;
            transition: all 0.2s; font-family: inherit; color: #64748B;
            display: flex; justify-content: center; align-items: center; text-decoration: none;
        }
        @media (prefers-color-scheme: dark) { 
            .nav-btn { background: #27272A; border-color: #3F3F46; color: #A1A1AA; } 
        }
        .nav-btn:hover { border-color: #388782; color: #388782; transform: scale(1.05); }
        .nav-btn.active { background: #388782; color: white; border-color: #388782; box-shadow: 0 4px 12px rgba(56, 135, 130, 0.3); }
        .nav-btn.answered { background: #ECFDF5; border-color: #10B981; color: #059669; }
        @media (prefers-color-scheme: dark) { .nav-btn.answered { background: #064E3B; border-color: #059669; color: #34D399; } }
        .nav-btn.ragu { background: #FFFBEB; border-color: #F59E0B; color: #D97706; }
        @media (prefers-color-scheme: dark) { .nav-btn.ragu { background: #78350F; border-color: #F59E0B; color: #FCD34D; } }
        .nav-btn.active.answered { background: #388782; color: white; border-color: #388782; }

        /* Main soal */
        .soal-main { flex: 1; padding: 32px 24px; max-width: 840px; margin: 0 auto; width: 100%; }

        /* Card soal */
        .soal-card { 
            background: white; border-radius: 28px; border: 1px solid #E2E8F0; 
            overflow: hidden; margin-bottom: 24px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        }
        @media (prefers-color-scheme: dark) { .soal-card { background: #18181B; border-color: #27272A; } }
        
        .soal-num-wrapper { 
            padding: 24px 32px 0; 
            display: flex; align-items: center; justify-content: space-between;
        }
        .soal-num-label { font-size: 0.8rem; font-weight: 800; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.1em; }
        .badge-mapel { px: 3; py: 1; rounded: xl; font-size: 10px; font-weight: 800; background: #F1F5F9; color: #475569; }
        @media (prefers-color-scheme: dark) { .badge-mapel { background: #27272A; color: #94A3B8; } }

        .soal-text { padding: 24px 32px 32px; font-size: 1.1rem; font-weight: 600; color: #334155; line-height: 1.7; }
        @media (prefers-color-scheme: dark) { .soal-text { color: #E2E8F0; } }
        .soal-text img { max-width: 100%; border-radius: 16px; margin-top: 16px; border: 1px solid #E2E8F0; }

        /* Options */
        .options-container { padding: 0 24px 32px; display: flex; flex-direction: column; gap: 12px; }
        .option-label {
            display: flex; align-items: center; gap: 16px; padding: 18px 24px;
            border: 2px solid #F1F5F9; border-radius: 20px; cursor: pointer;
            transition: all 0.2s; background: #F8FAFC;
        }
        @media (prefers-color-scheme: dark) { .option-label { background: #27272A; border-color: #3F3F46; } }
        
        .option-label:hover { border-color: #388782; background: white; transform: translateX(4px); }
        @media (prefers-color-scheme: dark) { .option-label:hover { background: #18181B; } }
        
        .option-label.selected { border-color: #388782; background: #F0FDFA; }
        @media (prefers-color-scheme: dark) { .option-label.selected { background: #134E4A/20; } }

        .option-mark {
            flex-shrink: 0; width: 36px; height: 36px; border-radius: 12px;
            border: 2px solid #E2E8F0; display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem; font-weight: 800; color: #94A3B8; transition: all 0.2s;
            background: white;
        }
        @media (prefers-color-scheme: dark) { .option-mark { background: #18181B; border-color: #3F3F46; } }
        
        .option-label.selected .option-mark { background: #388782; border-color: #388782; color: white; transform: scale(1.1); }
        .option-label input { display: none; }
        .option-text { font-size: 0.95rem; font-weight: 600; color: #475569; line-height: 1.5; }
        @media (prefers-color-scheme: dark) { .option-text { color: #CBD5E1; } }

        /* Essay */
        .essay-wrapper { padding: 0 32px 32px; }
        .essay-box {
            width: 100%; padding: 20px; border: 2px solid #F1F5F9; border-radius: 20px;
            font-family: inherit; font-size: 1rem; color: #334155; resize: vertical;
            min-height: 180px; outline: none; transition: all 0.2s; background: #F8FAFC;
        }
        @media (prefers-color-scheme: dark) { .essay-box { background: #27272A; border-color: #3F3F46; color: #E2E8F0; } }
        .essay-box:focus { border-color: #388782; background: white; }

        /* Bottom Nav */
        .soal-nav-bottom {
            display: flex; align-items: center; justify-content: space-between; gap: 16px;
            padding: 20px 24px; background: white; border-radius: 24px;
            border: 1px solid #E2E8F0; box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        }
        @media (prefers-color-scheme: dark) { .soal-nav-bottom { background: #18181B; border-color: #27272A; } }
        
        .btn-nav {
            display: flex; align-items: center; gap: 8px;
            padding: 12px 24px; border-radius: 16px; font-family: inherit;
            font-size: 0.9rem; font-weight: 800; cursor: pointer; transition: all 0.2s; border: none;
        }
        .btn-prev { background: #F1F5F9; color: #475569; }
        @media (prefers-color-scheme: dark) { .btn-prev { background: #27272A; color: #94A3B8; } }
        .btn-prev:hover:not(:disabled) { background: #E2E8F0; transform: translateX(-2px); }
        
        .btn-next { background: #388782; color: white; box-shadow: 0 4px 12px rgba(56, 135, 130, 0.2); }
        .btn-next:hover:not(:disabled) { background: #2D6A66; transform: translateX(2px); box-shadow: 0 6px 16px rgba(56, 135, 130, 0.3); }
        .btn-prev:disabled, .btn-next:disabled { opacity: 0.3; cursor: not-allowed; }

        /* Ragu-ragu */
        .ragu-toggle { display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 8px 16px; border-radius: 12px; transition: background 0.2s; }
        .ragu-toggle:hover { background: #FFFBEB; }
        @media (prefers-color-scheme: dark) { .ragu-toggle:hover { background: #78350F/20; } }
        .ragu-toggle input { width: 18px; height: 18px; cursor: pointer; accent-color: #F59E0B; }
        .ragu-toggle span { font-size: 0.85rem; font-weight: 800; color: #D97706; }

        @media (min-width: 1024px) {
            .navigator-panel { display: flex; }
        }

        /* Modal */
        .modal-overlay { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.8); backdrop-blur: 8px; z-index: 500; display: none; align-items: center; justify-content: center; padding: 20px; }
        .modal-overlay.show { display: flex; }
        .modal-box { background: white; border-radius: 32px; padding: 40px; max-width: 480px; width: 100%; text-align: center; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
        @media (prefers-color-scheme: dark) { .modal-box { background: #18181B; } }

        /* Toast */
        #autosave-toast {
            position: fixed; bottom: 32px; left: 50%; transform: translateX(-50%) translateY(20px); 
            background: #10B981; color: white;
            padding: 12px 24px; border-radius: 20px; font-size: 0.85rem; font-weight: 800;
            display: flex; align-items: center; gap: 10px; opacity: 0;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); z-index: 1000;
            box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.4);
        }
        #autosave-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }
    </style>
</head>
<body class="no-scrollbar">

<div id="autosave-toast">
    <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    </div>
    Progress Tersimpan Otomatis
</div>

{{-- Timer Progress Bar --}}
<div id="timer-bar">
    <div id="timer-progress" style="width: 100%"></div>
</div>

{{-- Exam Header --}}
<header class="exam-header">
    <div class="exam-title-wrapper">
        <span class="exam-label">Assessment</span>
        <div class="exam-title" title="{{ $sesi->ujian->judul }}">{{ $sesi->ujian->judul }}</div>
    </div>
    
    <div id="timer-display" class="timer-display">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span id="timer-text">--:--</span>
    </div>

    <button class="btn-submit" onclick="showSubmitModal()">Selesai</button>
</header>

{{-- Layout --}}
<div class="exam-layout">

    {{-- Navigator Sidebar --}}
    <aside class="navigator-panel no-scrollbar">
        <h4>Navigasi Soal</h4>
        <div class="nav-grid">
            @foreach($semuaJawaban as $nav)
            @php
                $isDijawab = $nav->cbt_opsi_jawaban_id || $nav->jawaban_teks;
                $isRagu = $nav->ragu_ragu;
                $isActive = $nav->urutan == $no;
            @endphp
            <a href="{{ route('siswa.ujian.soal', [$sesi->id, $nav->urutan]) }}" 
               class="nav-btn {{ $isActive ? 'active' : '' }} {{ $isRagu ? 'ragu' : '' }} {{ $isDijawab ? 'answered' : '' }}">
                {{ $nav->urutan }}
            </a>
            @endforeach
        </div>
        
        <div class="mt-auto pt-6 border-t border-slate-100 dark:border-zinc-800 space-y-3">
            <div class="flex items-center gap-3">
                <div class="w-4 h-4 rounded-md bg-[#ECFDF5] dark:bg-[#064E3B] border-2 border-[#10B981]"></div>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Terjawab</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-4 h-4 rounded-md bg-[#FFFBEB] dark:bg-[#78350F] border-2 border-[#F59E0B]"></div>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Ragu-ragu</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-4 h-4 rounded-md bg-[#388782] shadow-sm"></div>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Sekarang</span>
            </div>
        </div>
    </aside>

    {{-- Main soal area --}}
    <div class="soal-main">
        <form id="jawaban-form" onsubmit="event.preventDefault(); navigasi('next');">
            @csrf
            <input type="hidden" name="urutan" value="{{ $no }}">

            {{-- Kartu soal --}}
            <div class="soal-card">
                <div class="soal-num-wrapper">
                    <span class="soal-num-label">Pertanyaan {{ $no }} dari {{ $totalSoal }}</span>
                    @if($jawabanSaatIni->bankSoal->mapel)
                        <span class="px-3 py-1 rounded-full text-[10px] font-black bg-slate-50 dark:bg-zinc-800 text-[#388782] border border-[#388782]/20 uppercase tracking-widest">
                            {{ $jawabanSaatIni->bankSoal->mapel->nama }}
                        </span>
                    @endif
                </div>
                <div class="soal-text">{!! nl2br(e($jawabanSaatIni->bankSoal->pertanyaan)) !!}
                    @if($jawabanSaatIni->bankSoal->file_media)
                        <img src="{{ asset('storage/' . $jawabanSaatIni->bankSoal->file_media) }}" alt="Media Soal">
                    @endif
                </div>

                {{-- Pilihan Jawaban --}}
                @if($jawabanSaatIni->bankSoal->tipe_soal === 'pg')
                <div class="options-container">
                    @php 
                        $letters = ['A','B','C','D','E']; 
                        $savedOpsi = $jawabanSaatIni->cbt_opsi_jawaban_id; 
                        
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
                        <div class="option-text">{!! nl2br(e($opsi->teks_opsi)) !!}</div>
                    </label>
                    @endforeach
                </div>
                @else
                {{-- Essay --}}
                <div class="essay-wrapper">
                    <textarea name="jawaban_teks" class="essay-box" placeholder="Tuliskan jawaban Anda secara lengkap di sini..." oninput="debounceAutoSave()">{{ $jawabanSaatIni->jawaban_teks ?? '' }}</textarea>
                </div>
                @endif
            </div>

            {{-- Ragu-ragu + Nav --}}
            <div class="soal-nav-bottom">
                <button type="button" class="btn-nav btn-prev" onclick="navigasi('prev')" {{ $no <= 1 ? 'disabled' : '' }}>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    <span>Sebelumnya</span>
                </button>

                <label class="ragu-toggle">
                    <input type="hidden" name="ragu_ragu" value="0">
                    <input type="checkbox" name="ragu_ragu" value="1" id="ragu_check" {{ $jawabanSaatIni->ragu_ragu ? 'checked' : '' }} onchange="autoSave()">
                    <span>Ragu-ragu</span>
                </label>

                @if($no < $totalSoal)
                <button type="button" class="btn-nav btn-next" onclick="navigasi('next')">
                    <span>Selanjutnya</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
                @else
                <button type="button" class="btn-nav btn-next" onclick="showSubmitModal()" style="background: #EF4444;">
                    <span>Selesai</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </button>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Submit Confirmation Modal --}}
<div id="submit-modal" class="modal-overlay">
    <div class="modal-box">
        <div class="w-20 h-20 bg-red-50 dark:bg-red-900/20 rounded-[2rem] flex items-center justify-center mx-auto mb-8">
            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <h3 class="text-2xl font-black text-slate-800 dark:text-slate-100 mb-2 tracking-tight">Kumpulkan Ujian?</h3>
        <p class="text-slate-400 dark:text-slate-500 mb-10 font-medium leading-relaxed" id="modal-info">
            @php 
                $dijawabCount = $semuaJawaban->filter(fn($j) => $j->cbt_opsi_jawaban_id || $j->jawaban_teks)->count(); 
            @endphp
            Anda telah menjawab <span class="text-slate-800 dark:text-slate-200 font-bold">{{ $dijawabCount }} dari {{ $totalSoal }}</span> soal. Pastikan semua jawaban sudah benar sebelum mengakhiri sesi.
        </p>
        <div class="flex gap-4">
            <button onclick="closeSubmitModal()" class="flex-1 py-4 rounded-2xl bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-slate-400 font-black transition-all hover:bg-slate-200">Kembali</button>
            <form action="{{ route('siswa.ujian.submit', $sesi->id) }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" class="w-full py-4 rounded-2xl bg-red-500 text-white font-black transition-all hover:bg-red-600 shadow-xl shadow-red-500/20">Kumpulkan</button>
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

    if (sisaWaktu <= 300) {
        timerDisplay.classList.add('danger');
        timerBar.classList.add('danger');
    }

    sisaWaktu--;
}
updateTimer();
setInterval(updateTimer, 1000);

// Anti Cheat
document.addEventListener('contextmenu', e => e.preventDefault());
document.addEventListener('copy', e => e.preventDefault());
document.addEventListener('paste', e => e.preventDefault());

document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
        fetch('{{ route("siswa.ujian.log-blur", $sesi->id) }}', {
            method: 'POST',
            headers: { 
                'X-Requested-With': 'XMLHttpRequest', 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
            }
        });
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
