<!-- Trust / Social Proof: Compact Institutional Credibility Strip -->
<section class="py-8 sm:py-10 bg-[#FAF8F5] border-b border-[#E7E2D9] w-full">
    <div class="w-full max-w-[1720px] 2xl:max-w-[1800px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-6 lg:gap-10">
            
            <!-- Lead Label -->
            <div class="shrink-0 text-center lg:text-left">
                <span class="text-xs font-mono uppercase tracking-widest text-[#78716C] font-semibold block mb-1">
                    [ REPUTASI & KREDIBILITAS ]
                </span>
                <p class="text-sm font-semibold text-[#141413]">
                    Dipercaya oleh siswa yang ingin berkembang lebih jauh.
                </p>
            </div>

            <!-- Partner / School Badges (Monochrome Restrained Treatment) -->
            <div class="flex flex-wrap items-center justify-center lg:justify-end gap-3 sm:gap-4 xl:gap-6 text-xs font-mono text-[#78716C] tracking-wide">
                @if(isset($mitras) && $mitras->count() > 0)
                    @foreach($mitras as $mitra)
                        <div class="flex items-center gap-2 opacity-70 hover:opacity-100 transition-opacity grayscale">
                            @if($mitra->logo)
                                <img src="{{ asset('storage/' . $mitra->logo) }}" alt="{{ $mitra->name }}" class="h-6 sm:h-7 w-auto object-contain">
                            @else
                                <span class="font-semibold text-[#141413]">{{ $mitra->name }}</span>
                            @endif
                        </div>
                    @endforeach
                @else
                    <!-- Institutional Baseline Logos / School Affiliations -->
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded bg-white border border-[#E7E2D9]">
                        <span class="font-bold text-[#141413]">SMA Unggulan</span>
                        <span class="text-[10px] text-[#A8A29E]">Nasional</span>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded bg-white border border-[#E7E2D9]">
                        <span class="font-bold text-[#141413]">Target PTN</span>
                        <span class="text-[10px] text-[#A8A29E]">SNBT & Mandiri</span>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded bg-white border border-[#E7E2D9]">
                        <span class="font-bold text-[#141413]">Sekolah Kedinasan</span>
                        <span class="text-[10px] text-[#A8A29E]">STAN • STIS • IPDN</span>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded bg-white border border-[#E7E2D9]">
                        <span class="font-bold text-[#141413]">Standar Penilaian</span>
                        <span class="text-[10px] text-[#A8A29E]">Pusmendik BSNP</span>
                    </div>
                @endif
            </div>

        </div>
    </div>
</section>
