<!-- Footer Section: Professional Institutional Footer -->
<footer class="bg-[#141413] pt-16 pb-12 text-[#A8A29E] border-t border-[#292524] w-full">
    <div class="w-full max-w-[1720px] 2xl:max-w-[1800px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-10 lg:gap-14 mb-16">
            
            <!-- Column 1: Brand & Description -->
            <div class="md:col-span-5 lg:col-span-4 space-y-5">
                <a href="{{ route('welcome') }}" class="flex items-center gap-3">
                    @if(isset($masterData) && $masterData->logo)
                        <div class="h-10 w-10 rounded-lg bg-white p-1 flex items-center justify-center">
                            <img src="{{ Storage::url($masterData->logo) }}" onerror="this.onerror=null; this.src='{{ asset('images/nivora-logo.png') }}';" alt="{{ $masterData->nama_lembaga ?? 'NIVORA' }}" class="h-full w-full object-contain">
                        </div>
                    @else
                        <div class="h-10 w-10 rounded-lg bg-[#E14D2A] flex items-center justify-center text-white font-black text-lg">
                            {{ substr($masterData->nama_lembaga ?? 'NIVORA', 0, 1) }}
                        </div>
                    @endif
                    <span class="font-black text-2xl text-white tracking-tight">
                        {{ $masterData->nama_lembaga ?? 'NIVORA' }}
                    </span>
                </a>

                <p class="text-sm text-stone-400 leading-relaxed max-w-sm">
                    {{ $masterData->hero_subtitle ?? 'Platform bimbingan belajar dan sistem manajemen akademik modern yang mengedepankan kualitas pembelajaran, kurikulum adaptif, dan evaluasi berkala.' }}
                </p>

                <!-- Social Media Links -->
                <div class="flex items-center gap-3 pt-2">
                    @if($masterData && $masterData->instagram_url)
                        <a href="{{ $masterData->instagram_url }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram"
                           class="w-9 h-9 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-white/10 hover:border-white/20 transition-colors">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    @endif
                    @if($masterData && $masterData->facebook_url)
                        <a href="{{ $masterData->facebook_url }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook"
                           class="w-9 h-9 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-white/10 hover:border-white/20 transition-colors">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                    @endif
                    @if($masterData && $masterData->youtube_url)
                        <a href="{{ $masterData->youtube_url }}" target="_blank" rel="noopener noreferrer" aria-label="YouTube"
                           class="w-9 h-9 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-white/10 hover:border-white/20 transition-colors">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                    @endif
                    @if($masterData && $masterData->tiktok_url)
                        <a href="{{ $masterData->tiktok_url }}" target="_blank" rel="noopener noreferrer" aria-label="TikTok"
                           class="w-9 h-9 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-white/10 hover:border-white/20 transition-colors">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.24 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Column 2: Program Tracks -->
            <div class="md:col-span-2 lg:col-span-2 space-y-3">
                <div class="text-xs font-mono uppercase tracking-widest text-white font-bold mb-4">
                    PROGRAM
                </div>
                <ul class="space-y-2.5 text-sm text-stone-400">
                    <li><a href="{{ route('paket.index') }}" class="hover:text-white transition-colors">Semua Program</a></li>
                    <li><a href="#program" class="hover:text-white transition-colors">Program Unggulan</a></li>
                    <li><a href="{{ route('daftar.step1') }}" class="hover:text-white transition-colors">Pendaftaran Siswa Baru</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Portal CBT Siswa</a></li>
                </ul>
            </div>

            <!-- Column 3: Institution Links -->
            <div class="md:col-span-2 lg:col-span-2 space-y-3">
                <div class="text-xs font-mono uppercase tracking-widest text-white font-bold mb-4">
                    INSTITUSI
                </div>
                <ul class="space-y-2.5 text-sm text-stone-400">
                    <li><a href="#tentang" class="hover:text-white transition-colors">Tentang Kami</a></li>
                    <li><a href="#keunggulan" class="hover:text-white transition-colors">Keunggulan Akademik</a></li>
                    <li><a href="#pengajar" class="hover:text-white transition-colors">Mentor & Pengajar</a></li>
                    <li><a href="#testimoni" class="hover:text-white transition-colors">Kisah Sukses Siswa</a></li>
                    <li><a href="#faq" class="hover:text-white transition-colors">Pusat Bantuan & FAQ</a></li>
                </ul>
            </div>

            <!-- Column 4: Contact & Office Info -->
            <div class="md:col-span-3 lg:col-span-4 space-y-3">
                <div class="text-xs font-mono uppercase tracking-widest text-white font-bold mb-4">
                    KONTAK & SEKRETARIAT
                </div>
                <div class="space-y-3 text-sm text-stone-400">
                    <div class="flex items-start gap-2.5">
                        <span class="text-stone-500 shrink-0 font-mono text-xs mt-0.5">ALAMAT:</span>
                        <span class="leading-relaxed">{{ ($masterData && $masterData->alamat_lembaga) ? $masterData->alamat_lembaga : 'Jl. Adi Sucipto No. 88, Sobo, Kec. Banyuwangi, Kabupaten Banyuwangi, Jawa Timur 68418' }}</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="text-stone-500 shrink-0 font-mono text-xs">EMAIL:</span>
                        @php
                            $footerEmail = ($masterData && $masterData->email_kontak) ? $masterData->email_kontak : (($masterData && $masterData->mail_from_address) ? $masterData->mail_from_address : 'sekretariat@nivora.id');
                        @endphp
                        <a href="mailto:{{ $footerEmail }}" class="hover:text-white transition-colors">
                            {{ $footerEmail }}
                        </a>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="text-stone-500 shrink-0 font-mono text-xs">TELP:</span>
                        @php
                            $footerTelp = ($masterData && $masterData->telepon_kantor) ? $masterData->telepon_kantor : (($masterData && $masterData->wa_number) ? ('+' . $masterData->wa_number) : '+62 812-3456-7890');
                            $telpDigits = preg_replace('/[^0-9+]/', '', $footerTelp);
                        @endphp
                        <a href="tel:{{ $telpDigits }}" class="hover:text-white transition-colors">
                            {{ $footerTelp }}
                        </a>
                    </div>
                    <div class="flex items-center gap-2.5 pt-1 text-xs text-stone-500 font-mono">
                        <span>LAYANAN:</span>
                        <span class="text-stone-400">{{ ($masterData && $masterData->jam_layanan) ? $masterData->jam_layanan : 'Senin – Sabtu (08.00 – 20.00 WIB)' }}</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Bottom Legal Bar -->
        <div class="pt-8 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-mono text-stone-500">
            <div>
                &copy; {{ date('Y') }} {{ $masterData->nama_lembaga ?? 'NIVORA' }}. Seluruh Hak Cipta Dilindungi.
            </div>
            <div class="flex items-center gap-6">
                <button type="button" onclick="openModal('modal-tc')" class="hover:text-stone-300 transition-colors">
                    Syarat & Ketentuan
                </button>
                <button type="button" onclick="openModal('modal-privacy')" class="hover:text-stone-300 transition-colors">
                    Kebijakan Privasi
                </button>
            </div>
        </div>

    </div>
</footer>
