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

        .timer-wrapper {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }
        @media (max-width: 640px) {
            .exam-title-wrapper { max-width: 30%; }
            .exam-title { font-size: 0.85rem; }
        }

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
            padding: 24px; display: flex; flex-direction: column; gap: 20px;
            position: sticky; top: 65px; height: calc(100vh - 65px);
            overflow-y: auto; z-index: 150;
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
            display: flex; align-items: center; justify-content: center; gap: 8px;
            padding: 12px 24px; border-radius: 16px; font-family: inherit;
            font-size: 0.9rem; font-weight: 800; cursor: pointer; transition: all 0.2s; border: none;
            white-space: nowrap;
        }
        .btn-prev { background: #F1F5F9; color: #475569; }
        @media (prefers-color-scheme: dark) { .btn-prev { background: #27272A; color: #94A3B8; } }
        .btn-prev:hover:not(:disabled) { background: #E2E8F0; transform: translateX(-2px); }
        
        .btn-next { background: #388782; color: white; box-shadow: 0 4px 12px rgba(56, 135, 130, 0.2); }
        .btn-next:hover:not(:disabled) { background: #2D6A66; transform: translateX(2px); box-shadow: 0 6px 16px rgba(56, 135, 130, 0.3); }
        .btn-prev:disabled, .btn-next:disabled { opacity: 0.3; cursor: not-allowed; }

        /* Ragu-ragu */
        .ragu-toggle { display: flex; align-items: center; justify-content: center; gap: 10px; cursor: pointer; padding: 12px 16px; border-radius: 12px; transition: background 0.2s; white-space: nowrap; }
        .ragu-toggle:hover { background: #FFFBEB; }
        @media (prefers-color-scheme: dark) { .ragu-toggle:hover { background: #78350F/20; } }
        .ragu-toggle input { width: 18px; height: 18px; cursor: pointer; accent-color: #F59E0B; }
        .ragu-toggle span { font-size: 0.85rem; font-weight: 800; color: #D97706; }

        /* Mobile specific adjustments for nav */
        .nav-toggle-btn { display: none; background: transparent; border: none; cursor: pointer; padding: 8px; margin-left: -8px; color: #64748B; }
        @media (prefers-color-scheme: dark) { .nav-toggle-btn { color: #94A3B8; } }
        
        @media (max-width: 1023px) {
            .nav-toggle-btn { display: block; }
            .navigator-panel {
                position: fixed; top: 65px; left: -100%; bottom: 0;
                transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 10px 0 25px rgba(0,0,0,0.1);
            }
            .navigator-panel.open { left: 0; }
            .nav-overlay {
                position: fixed; inset: 0; top: 65px; background: rgba(15, 23, 42, 0.5); 
                backdrop-blur: 4px; z-index: 140; 
                opacity: 0; pointer-events: none; transition: opacity 0.3s;
            }
            .nav-overlay.show { opacity: 1; pointer-events: auto; }
        }

        @media (max-width: 640px) {
            .soal-nav-bottom {
                display: grid;
                grid-template-columns: 1fr 1fr;
                grid-template-areas: 
                    "ragu ragu"
                    "prev next";
                gap: 12px;
                padding: 16px;
            }
            .ragu-toggle { grid-area: ragu; background: #FFFBEB; border: 1px solid #FDE68A; }
            @media (prefers-color-scheme: dark) { .ragu-toggle { background: #78350F/30; border-color: #92400E; } }
            .btn-prev { grid-area: prev; padding: 12px 12px; font-size: 0.8rem; }
            .btn-next { grid-area: next; padding: 12px 12px; font-size: 0.8rem; }
            .btn-nav svg { width: 18px; height: 18px; }
            .essay-wrapper { padding: 0 16px 24px; }
            .options-container { padding: 0 16px 24px; }
            .soal-card { margin-bottom: 16px; }
            .soal-main { padding: 16px; padding-bottom: 110px; } /* Prevent OS nav bar from covering bottom buttons */
        }

        /* Modal */
        .modal-overlay { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.8); backdrop-blur: 8px; z-index: 500; display: none; align-items: center; justify-content: center; padding: 20px; }
        .modal-overlay.show { display: flex; }
        .modal-box { background: white; border-radius: 32px; padding: 40px; max-width: 480px; width: 100%; text-align: center; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
        @media (prefers-color-scheme: dark) { .modal-box { background: #18181B; } }

        /* Toast */
        #autosave-toast {
            position: fixed; top: 80px; left: 50%; transform: translateX(-50%) translateY(-20px); 
            background: #10B981; color: white;
            padding: 10px 20px; border-radius: 20px; font-size: 0.8rem; font-weight: 800;
            display: flex; align-items: center; gap: 8px; opacity: 0;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); z-index: 1000;
            box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.4);
            pointer-events: none;
        }
        #autosave-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }
    </style>
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
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
<header class="exam-header" style="position: sticky;">
    <div class="flex items-center gap-3">
        <button id="nav-toggle-btn" class="nav-toggle-btn" onclick="toggleNav()" type="button" aria-label="Toggle Navigation">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <div class="exam-title-wrapper">
            <span class="exam-label">Assessment</span>
            <div class="exam-title" title="{{ $sesi->ujian->judul }}">{{ $sesi->ujian->judul }}</div>
        </div>
    </div>
    
    <div class="timer-wrapper">
        <div id="timer-display" class="timer-display">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span id="timer-text">--:--</span>
        </div>
    </div>

    <div class="flex items-center justify-end">
        <button class="btn-submit" onclick="showSubmitModal()">Selesai</button>
    </div>
</header>

{{-- Layout --}}
<div class="exam-layout">

    {{-- Mobile Overlay --}}
    <div id="nav-overlay" class="nav-overlay lg:hidden" onclick="toggleNav()"></div>

    
    @include('siswa.cbt.partials.navigator')

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
                <div class="soal-text">{!! \App\Helpers\HtmlSanitizer::clean($jawabanSaatIni->bankSoal->pertanyaan) !!}
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
                        <div class="option-text">{!! \App\Helpers\HtmlSanitizer::clean($opsi->teks_opsi) !!}</div>
                    </label>
                    @endforeach
                </div>
                @else
                {{-- Essay --}}
                <div class="essay-wrapper">
                    <textarea name="jawaban_essay" class="essay-box" placeholder="Tuliskan jawaban Anda secara lengkap di sini..." oninput="debounceAutoSave()">{{ $jawabanSaatIni->jawaban_essay ?? '' }}</textarea>
                </div>
                @endif
            </div>

            {{-- Ragu-ragu + Nav --}}
            <div class="soal-nav-bottom">
                <label class="ragu-toggle">
                    <input type="hidden" name="ragu_ragu" value="0">
                    <input type="checkbox" name="ragu_ragu" value="1" id="ragu_check" {{ $jawabanSaatIni->ragu_ragu ? 'checked' : '' }} onchange="autoSave()">
                    <span>Ragu-ragu</span>
                </label>

                <button type="button" class="btn-nav btn-prev" onclick="navigasi('prev')" {{ $no <= 1 ? 'disabled' : '' }}>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    <span>Sebelumnya</span>
                </button>

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


@include('siswa.cbt.partials.modal-submit')
@include('siswa.cbt.partials.scripts')

    @include('components.loading-overlay')
</body>
</html>
