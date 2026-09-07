<!-- Modal T&C -->
<div id="modal-tc" class="fixed inset-0 z-[100] hidden flex items-center justify-center px-4">
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" onclick="closeModal('modal-tc')"></div>
    <div class="relative w-full max-w-2xl bg-white rounded-3xl p-6 sm:p-8 shadow-2xl scale-95 opacity-0 transition-all duration-300 border border-slate-100" id="modal-tc-content">
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100">
            <h2 class="text-xl sm:text-2xl font-black text-slate-900">Syarat & Ketentuan</h2>
            <button type="button" onclick="closeModal('modal-tc')" class="w-9 h-9 flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-full transition-colors">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="prose prose-sm text-slate-600 max-h-[60vh] overflow-y-auto pr-2 space-y-4">
            <p>Selamat datang di platform resmi <strong>{{ $masterData->nama_lembaga ?? config('app.name') }}</strong>. Dengan mengakses dan memanfaatkan layanan kami, Anda menyetujui ketentuan berikut:</p>
            <div>
                <h4 class="font-bold text-slate-900 text-sm">1. Penggunaan Layanan Akademik</h4>
                <p class="text-xs sm:text-sm text-slate-600 mt-1">Sistem ini disediakan untuk menunjang kegiatan pembelajaran, pelaksanaan tryout CBT, evaluasi berkala, dan administrasi siswa. Segala bentuk kecurangan atau penyalahgunaan akun dilarang keras.</p>
            </div>
            <div>
                <h4 class="font-bold text-slate-900 text-sm">2. Kerahasiaan Akun & Kredensial</h4>
                <p class="text-xs sm:text-sm text-slate-600 mt-1">Pengguna bertanggung jawab penuh atas keamanan kata sandi dan aktivitas pada akun masing-masing. Pihak lembaga tidak bertanggung jawab atas kelalaian pihak ketiga.</p>
            </div>
            <div>
                <h4 class="font-bold text-slate-900 text-sm">3. Ketentuan Administrasi & Pembayaran</h4>
                <p class="text-xs sm:text-sm text-slate-600 mt-1">Pembayaran paket bimbingan belajar bersifat final dan mengikat sesuai invoice yang diterbitkan. Fasilitas belajar aktif segera setelah pembayaran diverifikasi oleh sistem.</p>
            </div>
        </div>
        <div class="mt-8 pt-5 border-t border-slate-100 flex justify-end">
            <button type="button" onclick="closeModal('modal-tc')" class="bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white font-bold px-6 py-2.5 rounded-xl shadow-md shadow-orange-500/20 text-sm transition-all active:scale-95">
                Saya Mengerti
            </button>
        </div>
    </div>
</div>

<!-- Modal Privacy Policy -->
<div id="modal-privacy" class="fixed inset-0 z-[100] hidden flex items-center justify-center px-4">
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" onclick="closeModal('modal-privacy')"></div>
    <div class="relative w-full max-w-2xl bg-white rounded-3xl p-6 sm:p-8 shadow-2xl scale-95 opacity-0 transition-all duration-300 border border-slate-100" id="modal-privacy-content">
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100">
            <h2 class="text-xl sm:text-2xl font-black text-slate-900">Kebijakan Privasi</h2>
            <button type="button" onclick="closeModal('modal-privacy')" class="w-9 h-9 flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-full transition-colors">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="prose prose-sm text-slate-600 max-h-[60vh] overflow-y-auto pr-2 space-y-4">
            <p>Privasi data siswa dan wali siswa merupakan komitmen utama bagi <strong>{{ $masterData->nama_lembaga ?? config('app.name') }}</strong>.</p>
            <div>
                <h4 class="font-bold text-slate-900 text-sm">1. Data yang Dikumpulkan</h4>
                <p class="text-xs sm:text-sm text-slate-600 mt-1">Kami mengumpulkan informasi pribadi seperti nama lengkap, NISN, kontak nomor telepon/WhatsApp, email, dan rekam jejak hasil nilai evaluasi pembelajaran untuk keperluan operasional akademik.</p>
            </div>
            <div>
                <h4 class="font-bold text-slate-900 text-sm">2. Penggunaan & Perlindungan Data</h4>
                <p class="text-xs sm:text-sm text-slate-600 mt-1">Data yang dihimpun hanya digunakan untuk pelaporan progres studi, penerbitan sertifikat/nota, dan komunikasi resmi. Kami menjamin kerahasiaan data dan tidak akan membagikannya ke pihak luar yang tidak berwenang.</p>
            </div>
        </div>
        <div class="mt-8 pt-5 border-t border-slate-100 flex justify-end">
            <button type="button" onclick="closeModal('modal-privacy')" class="bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white font-bold px-6 py-2.5 rounded-xl shadow-md shadow-orange-500/20 text-sm transition-all active:scale-95">
                Tutup
            </button>
        </div>
    </div>
</div>
