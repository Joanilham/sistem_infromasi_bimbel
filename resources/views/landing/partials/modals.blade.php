<!-- Modal T&C -->
    <div id="modal-tc" class="fixed inset-0 z-[100] hidden flex items-center justify-center px-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('modal-tc')"></div>
        <div class="relative w-full max-w-2xl bg-white rounded-3xl p-6 sm:p-8 shadow-2xl scale-95 opacity-0 transition-all duration-300" id="modal-tc-content">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl sm:text-2xl font-black text-slate-900">Syarat & Ketentuan</h2>
                <button onclick="closeModal('modal-tc')" class="w-8 h-8 flex items-center justify-center bg-slate-100 hover:bg-red-100 hover:text-red-600 rounded-full transition-colors">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="prose prose-sm text-slate-600 max-h-[60vh] overflow-y-auto pr-2">
                <p>Selamat datang di <strong>{{ $masterData->nama_lembaga ?? 'Genius Education' }}</strong>. Dengan mengakses dan menggunakan sistem kami, Anda menyetujui syarat berikut:</p>
                <h4 class="font-bold text-slate-800 mt-4">1. Penggunaan Layanan</h4>
                <p>Platform ini disediakan untuk menunjang kegiatan akademik, ujian CBT, dan pembayaran tagihan bimbingan belajar. Segala bentuk penyalahgunaan sistem akan ditindak tegas.</p>
                <h4 class="font-bold text-slate-800 mt-4">2. Keamanan Akun</h4>
                <p>Anda bertanggung jawab penuh untuk menjaga kerahasiaan kata sandi akun Anda. Kami tidak bertanggung jawab atas kerugian yang timbul akibat kelalaian pengguna.</p>
                <h4 class="font-bold text-slate-800 mt-4">3. Transaksi & Pembayaran</h4>
                <p>Pembayaran paket bimbingan bersifat final. Layanan yang sudah dibeli tidak dapat di-refund kecuali terdapat kesalahan teknis dari pihak kami yang menyebabkan layanan tidak dapat digunakan sama sekali.</p>
            </div>
            <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end">
                <button onclick="closeModal('modal-tc')" class="bg-indigo-600 text-white font-bold px-6 py-2.5 rounded-xl hover:bg-indigo-700 transition-colors text-sm">Mengerti</button>
            </div>
        </div>
    </div>

    <!-- Modal Privacy Policy -->
    <div id="modal-privacy" class="fixed inset-0 z-[100] hidden flex items-center justify-center px-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('modal-privacy')"></div>
        <div class="relative w-full max-w-2xl bg-white rounded-3xl p-6 sm:p-8 shadow-2xl scale-95 opacity-0 transition-all duration-300" id="modal-privacy-content">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl sm:text-2xl font-black text-slate-900">Kebijakan Privasi</h2>
                <button onclick="closeModal('modal-privacy')" class="w-8 h-8 flex items-center justify-center bg-slate-100 hover:bg-red-100 hover:text-red-600 rounded-full transition-colors">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="prose prose-sm text-slate-600 max-h-[60vh] overflow-y-auto pr-2">
                <p>Privasi Anda sangat penting bagi <strong>{{ $masterData->nama_lembaga ?? 'Genius Education' }}</strong>. Kebijakan ini menjelaskan bagaimana kami mengumpulkan dan melindungi data Anda.</p>
                <h4 class="font-bold text-slate-800 mt-4">1. Pengumpulan Data</h4>
                <p>Kami mengumpulkan informasi pribadi yang Anda berikan saat mendaftar, seperti nama, email, nomor telepon, dan data akademik yang diperlukan untuk proses belajar.</p>
                <h4 class="font-bold text-slate-800 mt-4">2. Penggunaan Informasi</h4>
                <p>Data Anda hanya digunakan untuk keperluan internal institusi, seperti komunikasi akademik, penilaian hasil CBT, dan riwayat tagihan.</p>
                <h4 class="font-bold text-slate-800 mt-4">3. Keamanan Data</h4>
                <p>Kami berkomitmen untuk melindungi data pribadi Anda menggunakan standar keamanan server yang memadai, dan tidak akan menjual atau membagikan data Anda kepada pihak ketiga tanpa izin resmi.</p>
            </div>
            <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end">
                <button onclick="closeModal('modal-privacy')" class="bg-indigo-600 text-white font-bold px-6 py-2.5 rounded-xl hover:bg-indigo-700 transition-colors text-sm">Tutup</button>
            </div>
        </div>
    </div>
