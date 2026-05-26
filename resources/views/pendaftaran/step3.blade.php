@extends('layouts.pendaftaran')
@section('title', 'Langkah 3 - Pembayaran')
@section('step1_class', 'done') @section('step1_label_class', 'done')
@section('line1_class', 'done')
@section('step2_class', 'done') @section('step2_label_class', 'done')
@section('line2_class', 'done')
@section('step3_class', 'active') @section('step3_label_class', 'active')
@section('step4_class', '')

@section('extra_style')
<style>
.payment-type-option { display: none; }
.payment-type-card {
    position: relative; border: 2.5px solid #E2E8F0; border-radius: 16px;
    padding: 20px 18px; cursor: pointer; transition: all 0.2s; background: #F8FAFC;
    display: flex; align-items: flex-start; gap: 14px;
}
.payment-type-card:hover { border-color: #4F46E5; background: #EEF2FF; }
.payment-type-option:checked + .payment-type-card { border-color: #4F46E5; background: #EEF2FF; box-shadow: 0 0 0 4px rgba(79,70,229,0.1); }
.payment-type-option:checked + .payment-type-card.dp-card { border-color: #D97706; background: #FFFBEB; box-shadow: 0 0 0 4px rgba(217,119,6,0.1); }
.card-radio { width: 20px; height: 20px; border: 2px solid #CBD5E1; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all 0.15s; margin-top: 2px; }
.payment-type-option:checked + .payment-type-card .card-radio { border-color: #4F46E5; background: #4F46E5; }
.payment-type-option:checked + .payment-type-card.dp-card .card-radio { border-color: #D97706; background: #D97706; }
.card-radio::after { content: ''; width: 8px; height: 8px; border-radius: 50%; background: white; opacity: 0; transition: opacity 0.15s; }
.payment-type-option:checked + .payment-type-card .card-radio::after { opacity: 1; }
.rekening-item {
    background: white; border-radius: 12px; padding: 14px 16px;
    border: 1.5px solid #E2E8F0; display: flex; align-items: center; gap: 14px;
}
.rek-icon { width: 42px; height: 42px; border-radius: 10px; background: linear-gradient(135deg, #4F46E5, #06B6D4); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
.rek-info .rek-bank { font-size: 0.78rem; color: #64748B; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; }
.rek-info .rek-no { font-size: 1rem; font-weight: 900; color: #0F172A; letter-spacing: 0.06em; }
.rek-info .rek-name { font-size: 0.78rem; color: #64748B; }
.upload-area {
    border: 2px dashed #CBD5E1; border-radius: 12px; padding: 28px;
    text-align: center; cursor: pointer; transition: all 0.2s; background: #F8FAFC;
}
.upload-area:hover, .upload-area.dragover { border-color: #4F46E5; background: #EEF2FF; }
.upload-area svg { width: 40px; height: 40px; color: #94A3B8; margin-bottom: 10px; }
#preview-img { max-width: 100%; border-radius: 10px; margin-top: 12px; display: none; }

/* Summary Box */
.summary-box {
    background: linear-gradient(135deg, #F0FDF4, #DCFCE7);
    border: 2px solid #86EFAC; border-radius: 14px; padding: 20px;
}
.summary-box.dp-mode {
    background: linear-gradient(135deg, #FFFBEB, #FEF3C7);
    border-color: #FCD34D;
}
.summary-row { display: flex; justify-content: space-between; align-items: center; gap: 12px; font-size: 0.85rem; }
.summary-label { color: #374151; font-weight: 600; line-height: 1.4; }
.summary-value { font-weight: 900; white-space: nowrap; text-align: right; }
.summary-divider { border: none; border-top: 1.5px dashed #D1FAE5; margin: 10px 0; }
.summary-divider.dp { border-top-color: #FDE68A; }
.summary-total-label { font-size: 0.95rem; font-weight: 900; color: #065F46; }
.summary-total-value { font-size: 1.35rem; font-weight: 900; color: #059669; letter-spacing: -0.02em; white-space: nowrap; text-align: right; }
.dp-mode .summary-divider { border-top-color: #FDE68A; }
.dp-mode .summary-total-label { color: #92400E; }
.dp-mode .summary-total-value { color: #D97706; }
.sisa-badge {
    background: #FFF7ED; border: 1.5px solid #FED7AA; border-radius: 12px;
    padding: 14px 16px; color: #9A3412; 
    display: flex; flex-direction: column; gap: 6px;
    margin-top: 16px;
}
.sisa-badge-title { font-size: 0.85rem; font-weight: 600; }
.sisa-badge-amount { font-size: 1.3rem; font-weight: 900; color: #c2410c; letter-spacing: -0.02em; }
.payment-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 16px;
    margin-bottom: 24px;
}
@media (min-width: 640px) {
    .payment-grid {
        grid-template-columns: 1fr 1fr;
    }
}

/* Syarat Ketentuan Card */
.terms-card {
    margin-top: 24px; margin-bottom: 24px; padding: 20px;
    border: 2px solid #FCA5A5; /* red-300 */
    border-radius: 16px; background: #FEF2F2; /* red-50 */
    cursor: pointer; user-select: none; transition: all 0.3s ease;
    display: flex; gap: 16px; align-items: flex-start;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}
.terms-card:hover { border-color: #F87171; }
.terms-card.accepted {
    background: #F0FDF4; /* green-50 */
    border-color: #22C55E; /* green-500 */
}
.terms-title { font-size: 1rem; font-weight: 800; color: #991B1B; margin-bottom: 4px; transition: color 0.3s; }
.terms-desc { font-size: 0.85rem; line-height: 1.6; color: #B91C1C; transition: color 0.3s; }
.terms-desc strong { color: #7F1D1D; font-weight: 900; }

.terms-card.accepted .terms-title { color: #166534; }
.terms-card.accepted .terms-desc { color: #15803D; }
.terms-card.accepted .terms-desc strong { color: #14532D; }

.terms-checkbox {
    width: 22px; height: 22px; flex-shrink: 0; margin-top: 2px;
    cursor: pointer; accent-color: #22C55E;
}
</style>
@endsection

@section('content')
<div class="card" x-data="{
    selectedPaketId: '{{ $paket?->id ?? '' }}',
    jenisBayar: 'full',
    
    // ALPINE REWORK: Menyimpan array paket lengkap beserta dp_persen_minimal masing-masing
    pakets: [
        @foreach($pakets as $p)
        { 
            id: '{{ $p->id }}', 
            nama: '{{ addslashes($p->nama_paket) }}', 
            nominal: {{ $p->nominal ?? 0 }},
            dpPersen: {{ $p->dp_persen_minimal ?? 10 }},
            bisaDicicil: {{ $p->bisa_dicicil ? 'true' : 'false' }},
            maxCicilan: {{ $p->max_cicilan ?? 1 }}
        },
        @endforeach
    ],
    
    // Mendapatkan objek paket yang saat ini dipilih
    get currentPaket() {
        return this.pakets.find(p => p.id == this.selectedPaketId);
    },
    
    // Mengambil nilai DP (%) dari paket yang aktif, default 10% jika kosong
    get dpPersenAktif() {
        return this.currentPaket ? this.currentPaket.dpPersen : 10;
    },
    
    get nominalFull() {
        return this.currentPaket ? this.currentPaket.nominal : 0;
    },
    
    get nominalDp() {
        return this.currentPaket ? Math.ceil(this.currentPaket.nominal * this.dpPersenAktif / 100) : 0;
    },
    
    get nominalSisa() {
        return this.nominalFull - this.nominalDp;
    },
    
    get nominalBayar() {
        return this.jenisBayar === 'dp' ? this.nominalDp : this.nominalFull;
    },
    
    termsAccepted: false,
    
    formatRp(n) {
        return 'Rp ' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    },

    init() {
        this.$watch('currentPaket', (val) => {
            if (val && !val.bisaDicicil && this.jenisBayar === 'dp') {
                this.jenisBayar = 'full';
            }
        });
    }
}">
    <div class="card-title">Pembayaran Pendaftaran</div>
    <div class="card-sub">Pilih metode pembayaran, upload bukti, dan selesaikan pendaftaran Anda.</div>

    @if($errors->any())
    <div class="alert-danger">
        <strong>Mohon periksa kembali:</strong>
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form id="formPendaftaran" method="POST" action="{{ route('daftar.step3.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- Pilih Paket --}}
        <p class="section-label">PAKET BIMBINGAN BELAJAR</p>
        <div class="form-grid">
            <div class="form-group form-col-full">
                <label>Pilih Paket Bimbingan <span class="req">*</span></label>
                <select name="paket_bimbingan_id" class="form-control" x-model="selectedPaketId" required>
                    <option value="">— Pilih Paket Bimbingan —</option>
                    <template x-for="p in pakets" :key="p.id">
                        <option :value="p.id" x-text="p.nama + ' (Rp ' + p.nominal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ')'" :selected="p.id == selectedPaketId"></option>
                    </template>
                </select>
                @error('paket_bimbingan_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
        </div>

        {{-- Pilihan Jenis Bayar --}}
        <div x-show="currentPaket" x-cloak>
            <p class="section-label" style="margin-top:20px;">PILIH METODE PEMBAYARAN</p>
            <div class="payment-grid">
                {{-- Option: Bayar Penuh --}}
                <label>
                    <input type="radio" name="jenis_bayar" value="full" class="payment-type-option" x-model="jenisBayar" checked>
                    <div class="payment-type-card">
                        <div class="card-radio shrink-0"></div>
                        <div>
                            <div style="font-weight:900;font-size:0.95rem;color:#1E293B;margin-bottom:4px;">Bayar Penuh</div>
                            <div style="font-size:0.78rem;color:#64748B;line-height:1.5;">Bayar 100% dari total biaya paket sekaligus. Status aktif langsung setelah verifikasi.</div>
                            <div style="font-size:1.05rem;font-weight:900;color:#059669;margin-top:6px;" x-text="formatRp(nominalFull)"></div>
                        </div>
                    </div>
                </label>

                {{-- Option: Bayar DP --}}
                <label x-show="currentPaket && currentPaket.bisaDicicil">
                    <input type="radio" name="jenis_bayar" value="dp" class="payment-type-option" x-model="jenisBayar">
                    <div class="payment-type-card dp-card">
                        <div class="card-radio shrink-0"></div>
                        <div>
                            <div style="font-weight:900;font-size:0.95rem;color:#1E293B;margin-bottom:4px;">Bayar DP (Bisa Dicicil)</div>
                            <div style="font-size:0.78rem;color:#64748B;line-height:1.5;">
                                Bayar minimal <strong x-text="dpPersenAktif + '%'"></strong> sekarang, sisa dibayar kemudian (Maksimal <strong x-text="currentPaket ? currentPaket.maxCicilan : 1"></strong> kali cicilan).
                            </div>
                            <div style="font-size:1.05rem;font-weight:900;color:#D97706;margin-top:6px;" x-text="formatRp(nominalDp) + ' (min. ' + dpPersenAktif + '%)'"></div>
                        </div>
                    </div>
                </label>
            </div>

            {{-- Ringkasan Pembayaran --}}
            <div class="summary-box" :class="jenisBayar === 'dp' ? 'dp-mode' : ''">
                <div class="summary-row" style="margin-bottom:8px;">
                    <span class="summary-label">Biaya Paket</span>
                    <span class="summary-value" style="color:#374151;" x-text="formatRp(nominalFull)"></span>
                </div>
                <template x-if="jenisBayar === 'dp'">
                    <div>
                        <hr class="summary-divider dp">
                        <div class="summary-row">
                            <span class="summary-label" x-text="'DP yang dibayar sekarang (' + dpPersenAktif + '%)'"></span>
                            <span class="summary-value summary-total-value" x-text="formatRp(nominalDp)"></span>
                        </div>
                        <div class="sisa-badge">
                            <span class="sisa-badge-title">Sisa yang harus dilunasi kemudian:</span>
                            <span class="sisa-badge-amount" x-text="formatRp(nominalSisa)"></span>
                        </div>
                    </div>
                </template>
                <template x-if="jenisBayar === 'full'">
                    <div>
                        <hr class="summary-divider">
                        <div class="summary-row">
                            <span class="summary-total-label">Total Dibayar Sekarang</span>
                            <span class="summary-total-value" x-text="formatRp(nominalFull)"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>
        {{-- END Pilihan Jenis Bayar --}}

        {{-- Info jika belum pilih paket --}}
        <div x-show="!currentPaket" x-cloak style="background:#FFF7ED;border:1.5px solid #FED7AA;border-radius:10px;padding:12px 16px;font-size:0.82rem;color:#9A3412;font-weight:500;margin-top:16px;">
            Silakan pilih paket bimbingan di atas untuk melihat opsi pembayaran.
        </div>

        {{-- Rekening Tujuan --}}
        @if($banks->isNotEmpty())
        <p class="section-label" style="margin-top:24px;">REKENING TUJUAN TRANSFER</p>
        <div style="display:grid;gap:10px;margin-bottom:20px;">
            @foreach($banks as $bank)
            <div class="rekening-item">
                <div class="rek-icon">🏦</div>
                <div class="rek-info">
                    <div class="rek-bank">{{ $bank->nama_bank }}</div>
                    <div class="rek-no">{{ chunk_split($bank->nomor_rekening, 4, ' ') }}</div>
                    <div class="rek-name">a.n. {{ $bank->atas_nama }}</div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <p class="section-label">UPLOAD BUKTI BAYAR</p>
        <div class="form-grid">
            <div class="form-group">
                <label>Metode Pembayaran <span class="req">*</span></label>
                <select name="metode_pembayaran" class="form-control" required>
                    <option value="">— Pilih —</option>
                    @foreach($banks as $bank)
                        <option value="Transfer {{ $bank->nama_bank }}" {{ old('metode_pembayaran') === 'Transfer '.$bank->nama_bank ? 'selected' : '' }}>Transfer {{ $bank->nama_bank }}</option>
                    @endforeach
                    <option value="Tunai" {{ old('metode_pembayaran') === 'Tunai' ? 'selected' : '' }}>Tunai (Bayar Langsung ke Kantor)</option>
                </select>
                @error('metode_pembayaran')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group form-col-full">
                <label>Bukti Pembayaran <span class="req">*</span></label>
                <div class="upload-area" id="upload-area" onclick="document.getElementById('bukti_file').click()">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <p><strong>Klik untuk upload</strong> atau drag &amp; drop</p>
                    <p style="font-size:0.75rem;margin-top:4px;">JPG, PNG, atau PDF · Maks. 5 MB</p>
                    <img id="preview-img" src="" alt="Preview">
                </div>
                <input type="file" id="bukti_file" name="bukti_pembayaran" accept=".jpg,.jpeg,.png,.pdf" style="display:none" required onchange="previewFile(this)">
                @error('bukti_pembayaran')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
        </div>

        {{-- KOTAK SYARAT & KETENTUAN --}}
        <div class="terms-card" :class="termsAccepted ? 'accepted' : ''" @click="termsAccepted = !termsAccepted">
            <input type="checkbox" name="syarat_ketentuan" x-model="termsAccepted" required class="terms-checkbox" style="pointer-events:none;">
            <div>
                <div class="terms-title">Persetujuan Pendaftaran</div>
                <div class="terms-desc">
                    Saya menyatakan bahwa data yang saya isi adalah benar. Saya memahami dan menyetujui bahwa 
                    <strong>segala bentuk kesalahan pengisian data atau transfer pembayaran adalah tanggung jawab saya pribadi.</strong>
                </div>
            </div>
        </div>

        <div class="btn-row">
            <a href="{{ route('daftar.step2') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Kirim Pendaftaran</button>
        </div>
    </form>
</div>

<script>
function previewFile(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.getElementById('preview-img');
                img.src = e.target.result;
                img.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            document.getElementById('preview-img').style.display = 'none';
            document.querySelector('.upload-area p').textContent = '✓ File PDF terpilih: ' + file.name;
        }
    }
}
const area = document.getElementById('upload-area');
area.addEventListener('dragover', e => { e.preventDefault(); area.classList.add('dragover'); });
area.addEventListener('dragleave', () => area.classList.remove('dragover'));
area.addEventListener('drop', e => {
    e.preventDefault(); area.classList.remove('dragover');
    const dt = e.dataTransfer;
    document.getElementById('bukti_file').files = dt.files;
    previewFile(document.getElementById('bukti_file'));
});

// SweetAlert2 Confirmation
document.getElementById('formPendaftaran').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    Swal.fire({
        title: 'Verifikasi Data',
        html: 'Apakah Anda yakin semua data, pilihan paket, dan nominal yang dibayar sudah benar?<br><br><span style="font-size:0.85rem;color:#EF4444;font-weight:bold;">Data tidak dapat diubah setelah konfirmasi.</span>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10B981',
        cancelButtonColor: '#64748B',
        confirmButtonText: 'Ya, Kirim Sekarang',
        cancelButtonText: 'Cek Kembali',
        reverseButtons: true,
        customClass: {
            title: 'font-bold text-xl',
            confirmButton: 'rounded-xl px-6 py-3',
            cancelButton: 'rounded-xl px-6 py-3'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Memproses...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            form.submit();
        }
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection