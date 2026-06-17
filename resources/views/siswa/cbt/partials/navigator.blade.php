{{-- Navigator Sidebar --}}
    <aside id="navigator-panel" class="navigator-panel no-scrollbar">
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
