<!-- Modal T&C -->
<div id="modal-tc" class="fixed inset-0 z-[100] hidden flex items-center justify-center px-4">
    <div class="absolute inset-0 bg-[#141413]/70 backdrop-blur-sm transition-opacity" onclick="closeModal('modal-tc')"></div>
    <div class="relative w-full max-w-2xl bg-white rounded-2xl p-6 sm:p-8 shadow-xl scale-95 opacity-0 transition-all duration-300 border border-[#E7E2D9]" id="modal-tc-content">
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-[#E7E2D9]">
            <h2 class="text-xl sm:text-2xl font-bold text-[#141413]">Syarat & Ketentuan Layanan</h2>
            <button type="button" onclick="closeModal('modal-tc')" class="w-8 h-8 flex items-center justify-center bg-[#FAF8F5] hover:bg-[#F4EFEA] text-[#78716C] rounded-lg transition-colors border border-[#E7E2D9]">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="text-[#57534E] text-xs sm:text-sm max-h-[60vh] overflow-y-auto pr-2 space-y-4 leading-relaxed font-normal">
            <p>Selamat datang di platform resmi <strong>{{ $masterData->nama_lembaga ?? 'NIVORA' }}</strong>. Dengan mengakses dan memanfaatkan layanan kami, Anda menyetujui ketentuan akademik berikut:</p>
            <div>
                <h4 class="font-bold text-[#141413] text-sm mb-1">1. Penggunaan Layanan Akademik</h4>
                <p>Sistem ini disediakan untuk menunjang kegiatan pembelajaran, pelaksanaan tryout CBT, evaluasi berkala, dan administrasi siswa. Segala bentuk kecurangan atau penyalahgunaan akun dilarang keras.</p>
            </div>
            <div>
                <h4 class="font-bold text-[#141413] text-sm mb-1">2. Kerahasiaan Akun & Kredensial</h4>
                <p>Pengguna bertanggung jawab penuh atas keamanan kata sandi dan aktivitas pada akun masing-masing. Pihak lembaga tidak bertanggung jawab atas kelalaian pihak ketiga.</p>
            </div>
            <div>
                <h4 class="font-bold text-[#141413] text-sm mb-1">3. Ketentuan Administrasi & Pembayaran</h4>
                <p>Pembayaran paket bimbingan belajar bersifat final dan mengikat sesuai invoice yang diterbitkan. Fasilitas belajar aktif segera setelah pembayaran diverifikasi oleh sistem.</p>
            </div>
        </div>
        <div class="mt-8 pt-4 border-t border-[#E7E2D9] flex justify-end">
            <button type="button" onclick="closeModal('modal-tc')" class="bg-[#E14D2A] hover:bg-[#C93B1A] text-white font-semibold px-6 py-2.5 rounded-lg text-sm transition-colors shadow-xs">
                Saya Mengerti
            </button>
        </div>
    </div>
</div>

<!-- Modal Privacy Policy -->
<div id="modal-privacy" class="fixed inset-0 z-[100] hidden flex items-center justify-center px-4">
    <div class="absolute inset-0 bg-[#141413]/70 backdrop-blur-sm transition-opacity" onclick="closeModal('modal-privacy')"></div>
    <div class="relative w-full max-w-2xl bg-white rounded-2xl p-6 sm:p-8 shadow-xl scale-95 opacity-0 transition-all duration-300 border border-[#E7E2D9]" id="modal-privacy-content">
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-[#E7E2D9]">
            <h2 class="text-xl sm:text-2xl font-bold text-[#141413]">Kebijakan Privasi Data</h2>
            <button type="button" onclick="closeModal('modal-privacy')" class="w-8 h-8 flex items-center justify-center bg-[#FAF8F5] hover:bg-[#F4EFEA] text-[#78716C] rounded-lg transition-colors border border-[#E7E2D9]">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="text-[#57534E] text-xs sm:text-sm max-h-[60vh] overflow-y-auto pr-2 space-y-4 leading-relaxed font-normal">
            <p>Privasi data siswa dan wali siswa merupakan komitmen utama bagi <strong>{{ $masterData->nama_lembaga ?? 'NIVORA' }}</strong>.</p>
            <div>
                <h4 class="font-bold text-[#141413] text-sm mb-1">1. Data yang Dihimpun</h4>
                <p>Kami mengumpulkan informasi pribadi seperti nama lengkap, NISN, kontak nomor telepon/WhatsApp, email, dan rekam jejak nilai evaluasi pembelajaran untuk keperluan operasional akademik.</p>
            </div>
            <div>
                <h4 class="font-bold text-[#141413] text-sm mb-1">2. Penggunaan & Perlindungan Data</h4>
                <p>Data yang dihimpun hanya digunakan untuk pelaporan progres studi, penerbitan sertifikat/nota, dan komunikasi resmi. Kami menjamin kerahasiaan data dan tidak akan membagikannya ke pihak luar yang tidak berwenang.</p>
            </div>
        </div>
        <div class="mt-8 pt-4 border-t border-[#E7E2D9] flex justify-end">
            <button type="button" onclick="closeModal('modal-privacy')" class="bg-[#141413] hover:bg-[#292524] text-white font-semibold px-6 py-2.5 rounded-lg text-sm transition-colors shadow-xs">
                Tutup
            </button>
        </div>
    </div>
</div>
