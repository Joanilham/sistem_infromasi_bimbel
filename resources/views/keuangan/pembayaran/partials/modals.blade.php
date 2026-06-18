@push('modals')
{{-- MODAL BUKTI TRANSFER --}}
<div id="modal-bukti" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300">
    <div class="relative max-w-2xl w-[90%] bg-white dark:bg-zinc-900 rounded-3xl p-6 shadow-2xl scale-95 transition-all duration-300" id="modal-bukti-box">
        <button type="button" onclick="closeBuktiModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 dark:bg-zinc-800 text-slate-500 hover:text-slate-800 dark:hover:text-white flex items-center justify-center transition font-bold text-lg">&times;</button>
        <div class="flex items-center gap-4 mb-4">
            <h3 class="text-lg font-black text-slate-800 dark:text-white">Bukti Pembayaran</h3>
            <div class="flex gap-2">
                <button type="button" onclick="zoomBuktiImage(0.2)" class="px-3 py-1.5 bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-lg hover:bg-slate-200 dark:hover:bg-zinc-700 transition flex items-center gap-1.5" title="Perbesar">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg>
                </button>
                <button type="button" onclick="zoomBuktiImage(-0.2)" class="px-3 py-1.5 bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-lg hover:bg-slate-200 dark:hover:bg-zinc-700 transition flex items-center gap-1.5" title="Perkecil">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7" /></svg>
                </button>
                <button type="button" onclick="rotateBuktiImage()" class="px-3 py-1.5 bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-lg hover:bg-slate-200 dark:hover:bg-zinc-700 transition flex items-center gap-1.5" title="Putar">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                </button>
            </div>
        </div>
        <div class="rounded-2xl overflow-auto border border-slate-100 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 flex items-center justify-center relative max-h-[70vh]">
            <img src="" id="bukti-img-preview" class="w-auto object-contain transition-transform duration-300 cursor-move" alt="Bukti Transfer">
        </div>
    </div>
</div>

{{-- MODAL VERIFIKASI PEMBAYARAN --}}
<div id="modal-verifikasi" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300">
    <div class="relative max-w-md w-[90%] bg-white dark:bg-zinc-900 rounded-3xl p-6 shadow-2xl scale-95 transition-all duration-300" id="modal-verifikasi-box">
        <button type="button" onclick="closeVerifikasiModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 dark:bg-zinc-800 text-slate-500 hover:text-slate-800 dark:hover:text-white flex items-center justify-center transition font-bold text-lg">&times;</button>
        <h3 class="text-lg font-black text-slate-800 dark:text-white mb-2">Verifikasi Pembayaran</h3>
        <p class="text-xs text-slate-400 mb-4 leading-relaxed">
            Periksa kembali nominal uang yang masuk di mutasi bank. Anda dapat menyesuaikan nominal di bawah ini jika terdapat perbedaan.
        </p>

        {{-- Sisa Tagihan Info --}}
        <div class="mb-4 bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/50 rounded-2xl p-4 flex items-center justify-between">
            <span class="text-xs font-bold text-amber-800 dark:text-amber-300">Sisa Tagihan Siswa</span>
            <span class="font-black text-amber-900 dark:text-amber-200 text-sm">
                Rp {{ number_format($kekurangan, 0, ',', '.') }},-
            </span>
        </div>

        <form id="form-verifikasi" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="modal-verif-nominal" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nominal Terverifikasi (Rp)</label>
                <input type="text" name="nominal" id="modal-verif-nominal" required inputmode="numeric" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-[#388782] focus:border-transparent text-sm">
            </div>
            <div>
                <label for="modal-verif-tipe" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Tipe Pembayaran</label>
                <select name="tipe_pembayaran" id="modal-verif-tipe" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-[#388782] focus:border-transparent text-sm">
                    <option value="TRANSFER">TRANSFER</option>
                    <option value="TUNAI">TUNAI</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeVerifikasiModal()" class="flex-1 py-2.5 bg-rose-500 hover:bg-rose-600 text-white rounded-xl text-xs font-bold shadow-md shadow-rose-500/20 transition">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-[#388782] hover:bg-[#206D6C] text-white rounded-xl text-xs font-bold shadow-md shadow-[#388782]/20 transition">Verifikasi & Setujui</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL TOLAK PEMBAYARAN --}}
<div id="modal-tolak" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300">
    <div class="relative max-w-md w-[90%] bg-white dark:bg-zinc-900 rounded-3xl p-6 shadow-2xl scale-95 transition-all duration-300" id="modal-tolak-box">
        <button type="button" onclick="closeTolakModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 dark:bg-zinc-800 text-slate-500 hover:text-slate-800 dark:hover:text-white flex items-center justify-center transition font-bold text-lg">&times;</button>
        <h3 class="text-lg font-black text-slate-800 dark:text-white mb-2 text-rose-600">Tolak Pengajuan Pembayaran</h3>
        <p class="text-xs text-slate-400 mb-4 leading-relaxed">
            Berikan alasan penolakan agar siswa dapat mengetahui kendala pada pengajuan pembayarannya.
        </p>
        <form id="form-tolak" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="modal-tolak-catatan" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Alasan Penolakan (Opsional)</label>
                <textarea name="catatan_penolakan" id="modal-tolak-catatan" rows="3" placeholder="Contoh: Bukti transfer buram atau tidak valid" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent text-sm"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeTolakModal()" class="flex-1 py-2.5 bg-slate-500 hover:bg-slate-600 text-white rounded-xl text-xs font-bold shadow-md shadow-slate-500/20 transition">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-md shadow-rose-900/20 transition">Tolak Pengajuan</button>
            </div>
        </form>
    </div>
</div>
@endpush

<script>
    const baseVerifikasiUrl = "{{ route('keuangan.transaksi.verifikasi', ':id') }}";
    const baseTolakUrl = "{{ route('keuangan.transaksi.tolak', ':id') }}";
    
    let currentRotation = 0;
    let currentScale = 1;

    function applyImageTransform() {
        const img = document.getElementById('bukti-img-preview');
        img.style.transform = `rotate(${currentRotation}deg) scale(${currentScale})`;
    }

    function rotateBuktiImage() {
        currentRotation += 90;
        if (currentRotation >= 360) currentRotation = 0;
        applyImageTransform();
    }

    function zoomBuktiImage(factor) {
        currentScale += factor;
        if (currentScale < 0.5) currentScale = 0.5;
        if (currentScale > 3) currentScale = 3;
        applyImageTransform();
    }

    function openBuktiModal(src) {
        currentRotation = 0;
        currentScale = 1;
        const m = document.getElementById('modal-bukti');
        const box = document.getElementById('modal-bukti-box');
        const img = document.getElementById('bukti-img-preview');
        applyImageTransform();
        img.src = src;
        m.classList.remove('opacity-0', 'pointer-events-none');
        box.classList.remove('scale-95');
    }
    function closeBuktiModal() {
        const m = document.getElementById('modal-bukti');
        const box = document.getElementById('modal-bukti-box');
        m.classList.add('opacity-0', 'pointer-events-none');
        box.classList.add('scale-95');
    }

    // Fungsi Format Rupiah (Thousand Separator)
    function formatRupiah(angka) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            var separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return rupiah;
    }

    function openVerifikasiModal(id, nominal, tipe) {
        const m = document.getElementById('modal-verifikasi');
        const box = document.getElementById('modal-verifikasi-box');
        const form = document.getElementById('form-verifikasi');
        const inputNominal = document.getElementById('modal-verif-nominal');
        const selectTipe = document.getElementById('modal-verif-tipe');

        form.action = baseVerifikasiUrl.replace(':id', id);
        inputNominal.value = formatRupiah(nominal.toString());
        selectTipe.value = tipe;

        m.classList.remove('opacity-0', 'pointer-events-none');
        box.classList.remove('scale-95');
    }
    function closeVerifikasiModal() {
        const m = document.getElementById('modal-verifikasi');
        const box = document.getElementById('modal-verifikasi-box');
        m.classList.add('opacity-0', 'pointer-events-none');
        box.classList.add('scale-95');
    }

    function openTolakModal(id) {
        const m = document.getElementById('modal-tolak');
        const box = document.getElementById('modal-tolak-box');
        const form = document.getElementById('form-tolak');

        form.action = baseTolakUrl.replace(':id', id);

        m.classList.remove('opacity-0', 'pointer-events-none');
        box.classList.remove('scale-95');
    }
    function closeTolakModal() {
        const m = document.getElementById('modal-tolak');
        const box = document.getElementById('modal-tolak-box');
        m.classList.add('opacity-0', 'pointer-events-none');
        box.classList.add('scale-95');
    }

    // Event listener format rupiah nominal konfirmasi keuangan (admin)
    const verifNominalInput = document.getElementById('modal-verif-nominal');
    if (verifNominalInput) {
        verifNominalInput.addEventListener('input', function(e) {
            this.value = formatRupiah(this.value);
        });
    }

    // Bersihkan titik sebelum submit form verifikasi keuangan (admin)
    const formVerif = document.getElementById('form-verifikasi');
    if (formVerif) {
        formVerif.addEventListener('submit', function(e) {
            const input = document.getElementById('modal-verif-nominal');
            if (input) {
                input.value = input.value.replace(/\./g, '');
            }
        });
    }

    // Event listener format rupiah nominal pencatatan keuangan baru
    const catatNominalInput = document.getElementById('catat-nominal');
    if (catatNominalInput) {
        catatNominalInput.addEventListener('input', function(e) {
            this.value = formatRupiah(this.value);
        });
    }

    // FORMAT NOMINAL (Titik ribuan)
    document.addEventListener('DOMContentLoaded', function() {
        function formatRupiah(value) {
            let number_string = value.replace(/[^,\d]/g, '').toString(),
                split         = number_string.split(','),
                sisa          = split[0].length % 3,
                rupiah        = split[0].substr(0, sisa),
                ribuan        = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return rupiah;
        }

        const nominalInputs = document.querySelectorAll('input.nominal-format');
        nominalInputs.forEach(input => {
            if(input.value) {
                input.value = formatRupiah(input.value);
            }

            input.addEventListener('input', function(e) {
                this.value = formatRupiah(this.value);
            });
            
            const form = input.closest('form');
            if (form) {
                form.addEventListener('submit', function() {
                    input.value = input.value.replace(/\./g, '');
                });
            }
        });
    });
</script>
