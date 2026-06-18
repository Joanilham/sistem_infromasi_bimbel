<!-- Programs Section -->
    <section id="program" class="py-16 sm:py-20 bg-slate-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 sm:mb-16 gap-4 sm:gap-6 text-center md:text-left">
                <div data-aos="fade-up">
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 mb-3 sm:mb-4 tracking-tight">Program Bimbingan Unggulan</h2>
                    <p class="text-slate-500 font-medium max-w-lg mx-auto md:mx-0 text-sm sm:text-base">Pilih jalur bimbingan yang sesuai dengan target dan kebutuhan akademikmu.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @forelse($pakets as $paket)
                    <div class="group bg-white rounded-3xl sm:rounded-[2.5rem] overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-slate-100 flex flex-col h-full relative" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                        <!-- Badge Diskon -->
                        @if($paket->harga_coret && $paket->harga_coret > $paket->nominal)
                            @php $diskon = round((($paket->harga_coret - $paket->nominal) / $paket->harga_coret) * 100); @endphp
                            <div class="absolute top-4 left-4 z-20">
                                <div class="bg-red-500 text-white text-[10px] font-black px-2 py-1 rounded-xl shadow-lg">
                                    {{ $diskon }}% OFF
                                </div>
                            </div>
                        @endif

                        <!-- Header Image -->
                        <div class="relative h-40 sm:h-56 overflow-hidden">
                            @if($paket->gambar_paket)
                                <img src="{{ asset('storage/' . $paket->gambar_paket) }}" alt="{{ $paket->nama_paket }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-indigo-700 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-white/20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/></svg>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60"></div>
                            @if($paket->label_populer)
                                <div class="absolute bottom-4 left-4">
                                    <span class="bg-[#F97316] text-white text-[10px] font-black px-3 py-1 rounded-full shadow-lg">✨ {{ $paket->label_populer }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="p-6 sm:p-8 flex-grow flex flex-col">
                            <h3 class="text-xl sm:text-2xl font-black text-slate-900 mb-1 group-hover:text-indigo-600 transition-colors tracking-tight line-clamp-2 break-words">{{ $paket->nama_paket }}</h3>
                            <p class="text-indigo-600 text-[9px] sm:text-[10px] uppercase tracking-[0.2em] mb-4 sm:mb-6 font-black break-words">{{ $paket->target_peserta ?? 'Semua Jenjang' }}</p>
                            
                            <div class="space-y-2.5 mb-6">
                                @php 
                                    $benefits = explode("\n", str_replace("\r", "", $paket->benefits ?? ''));
                                    $topBenefits = array_slice(array_filter($benefits), 0, 3);
                                @endphp
                                @forelse($topBenefits as $benefit)
                                    <div class="flex items-start gap-2">
                                        <div class="flex-shrink-0 w-4 h-4 rounded-full bg-yellow-100 flex items-center justify-center mt-0.5">
                                            <svg class="w-2.5 h-2.5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        </div>
                                        <span class="text-xs text-slate-600 font-bold leading-relaxed break-words line-clamp-2">{{ trim($benefit) }}</span>
                                    </div>
                                @empty
                                    <div class="text-xs text-slate-400 italic">Program intensif terstruktur.</div>
                                @endforelse
                            </div>

                            <div class="mt-auto bg-slate-50 rounded-2xl p-4 border border-slate-100">
                                @if($paket->harga_coret)
                                    <div class="text-[9px] sm:text-[10px] text-slate-400 line-through mb-0.5 font-bold">Rp {{ number_format($paket->harga_coret, 0, ',', '.') }}</div>
                                @endif
                                <div class="flex items-baseline gap-1">
                                    <span class="text-xl sm:text-2xl font-black text-indigo-600">Rp {{ number_format($paket->nominal, 0, ',', '.') }}</span>
                                    @if($paket->durasi_jumlah)
                                        <span class="text-[9px] sm:text-[10px] text-slate-500 font-black uppercase">/ {{ $paket->durasi_jumlah }} {{ $paket->durasi_satuan }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="px-6 pb-6 sm:px-8 sm:pb-8">
                            <a href="{{ route('paket.detail', $paket->id) }}" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-3 sm:py-4.5 rounded-xl sm:rounded-2xl transition-all shadow-lg shadow-indigo-100 flex items-center justify-center gap-2 active:scale-95 group/btn text-sm sm:text-base">
                                Info Lebih Lanjut
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-24 bg-white rounded-[3rem] border-2 border-dashed border-slate-200">
                        <h3 class="text-xl font-bold text-slate-900 uppercase tracking-wider">Belum Ada Program</h3>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
