<!-- Conversion CTA Section: Confident & Warm Institutional Call -->
<section class="py-20 sm:py-28 bg-[#141413] text-white border-b border-[#292524] relative w-full overflow-hidden">
    <div class="w-full max-w-[1720px] 2xl:max-w-[1800px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16 relative z-10">
        <div class="max-w-3xl mx-auto text-center" data-aos="fade-up">
            
            <span class="text-xs font-mono uppercase tracking-widest text-[#A8A29E] font-semibold mb-4 inline-block">
                [ PENDAFTARAN TAHUN AKADEMIK 2026/2027 ]
            </span>

            <h2 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-tight mb-6">
                Mulai Perjalanan Belajarmu Bersama Nivora.
            </h2>

            <p class="text-stone-300 text-base sm:text-lg lg:text-xl leading-relaxed mb-10 font-normal max-w-2xl mx-auto">
                Temukan program yang sesuai dengan tujuan belajarmu dan mulai berkembang dengan bimbingan yang tepat.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('daftar.step1') }}" 
                   class="w-full sm:w-auto bg-[#E14D2A] hover:bg-[#C93B1A] text-white font-semibold px-8 py-4 rounded-lg shadow-sm transition-colors text-base text-center">
                    Daftar Sekarang
                </a>
                @if($masterData && $masterData->wa_number)
                    <a href="https://wa.me/{{ $masterData->wa_number }}?text={{ urlencode('Halo Admin Nivora, saya ingin konsultasi mengenai program bimbingan belajar.') }}" 
                       target="_blank"
                       rel="noopener noreferrer"
                       class="w-full sm:w-auto bg-white/10 hover:bg-white/15 border border-white/20 text-white font-semibold px-7 py-4 rounded-lg transition-colors text-base text-center">
                        Konsultasi dengan Admin
                    </a>
                @else
                    <a href="#tentang" 
                       class="w-full sm:w-auto bg-white/10 hover:bg-white/15 border border-white/20 text-white font-semibold px-7 py-4 rounded-lg transition-colors text-base text-center">
                        Konsultasi Informasi
                    </a>
                @endif
            </div>

            <div class="mt-12 pt-8 border-t border-white/10 flex flex-wrap items-center justify-center gap-6 text-xs font-mono text-stone-400">
                <span>✓ Kuota Kelas Terbatas</span>
                <span>•</span>
                <span>✓ Garansi Bimbingan Adaptif</span>
                <span>•</span>
                <span>✓ Evaluasi Berkala Terstandarisasi</span>
            </div>

        </div>
    </div>
</section>
