<!-- Guru Section -->
@if(isset($featuredGurus) && $featuredGurus->count() > 0)
<section id="pengajar" class="py-20 sm:py-28 bg-slate-50/80 relative">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-14 sm:mb-20" data-aos="fade-up">
            <span class="px-3.5 py-1.5 rounded-full bg-orange-100/60 border border-orange-200/70 text-orange-600 text-xs font-bold uppercase tracking-wider mb-4 inline-block">
                Tenaga Pendidik Berdedikasi
            </span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight mb-4">
                Didampingi Mentor Berpengalaman
            </h2>
            <p class="text-slate-500 font-medium text-base sm:text-lg leading-relaxed">
                Tim pengajar profesional yang siap membimbing dan membakar semangat belajar siswa menuju prestasi optimal.
            </p>
        </div>

        <!-- Guru Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
            @foreach($featuredGurus as $guru)
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/70 hover:border-orange-200 shadow-sm hover:shadow-xl hover:shadow-orange-500/5 transition-all duration-300 text-center group" 
                 data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                
                <!-- Avatar Frame -->
                <div class="w-24 h-24 mx-auto rounded-2xl overflow-hidden mb-5 ring-4 ring-slate-100 group-hover:ring-orange-100 transition-all duration-300 shadow-md bg-slate-50 flex items-center justify-center">
                    @if($guru->foto)
                        <img src="{{ asset('storage/' . $guru->foto) }}" alt="{{ $guru->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-orange-500 to-amber-500 flex items-center justify-center text-white font-black text-2xl">
                            {{ substr($guru->name, 0, 1) }}
                        </div>
                    @endif
                </div>

                <!-- Teacher Name & Verified Badge -->
                <div class="flex items-center justify-center gap-1.5 mb-1.5">
                    <h3 class="text-lg font-bold text-slate-900 truncate max-w-[180px]" title="{{ $guru->name }}">
                        {{ $guru->name }}
                    </h3>
                    <svg class="w-4 h-4 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20" title="Terverifikasi">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>

                <!-- Subject Pill -->
                <span class="inline-block px-3 py-1 rounded-full bg-orange-50 text-orange-600 text-xs font-bold uppercase tracking-wider">
                    {{ $guru->matapelajaran ?? 'Pengajar Utama' }}
                </span>
            </div>
            @endforeach
        </div>

    </div>
</section>
@endif
