{{-- Submit Confirmation Modal --}}
<div id="submit-modal" class="modal-overlay">
    <div class="modal-box">
        <div class="w-20 h-20 bg-red-50 dark:bg-red-900/20 rounded-[2rem] flex items-center justify-center mx-auto mb-8">
            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <h3 class="text-2xl font-black text-slate-800 dark:text-slate-100 mb-2 tracking-tight">Kumpulkan Ujian?</h3>
        <p class="text-slate-400 dark:text-slate-500 mb-6 font-medium leading-relaxed" id="modal-info">
            Anda telah menjawab <span class="text-slate-800 dark:text-slate-200 font-bold"><span id="modal-answered-count">0</span> dari {{ $totalSoal }}</span> soal. Pastikan semua jawaban sudah benar sebelum mengakhiri sesi.
        </p>
        <div id="modal-unanswered-list" class="hidden mb-10 text-sm p-4 bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 rounded-xl font-medium border border-amber-200 dark:border-amber-800">
            <div class="flex items-center justify-center gap-2 mb-2 font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Belum Dijawab:
            </div>
            <div id="unanswered-numbers" class="flex flex-wrap justify-center gap-2"></div>
        </div>
        <div class="flex gap-4">
            <button onclick="closeSubmitModal()" class="flex-1 py-4 rounded-2xl bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-slate-400 font-black transition-all hover:bg-slate-200">Kembali</button>
            <form action="{{ route('siswa.ujian.submit', $sesi->id) }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" class="w-full py-4 rounded-2xl bg-red-500 text-white font-black transition-all hover:bg-red-600 shadow-xl shadow-red-500/20">Kumpulkan</button>
            </form>
        </div>
    </div>
</div>
