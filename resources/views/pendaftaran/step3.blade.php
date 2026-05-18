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
.payment-info {
    background: linear-gradient(135deg, #EEF2FF, #E0E7FF);
    border: 1px solid #C7D2FE; border-radius: 14px; padding: 20px; margin-bottom: 24px;
}
.payment-info h4 { font-size: 0.9rem; font-weight: 700; color: #3730A3; margin-bottom: 6px; }
.payment-amount { font-size: 1.6rem; font-weight: 900; color: #4F46E5; }
.payment-note { font-size: 0.8rem; color: #6D28D9; margin-top: 4px; }
.rekening-list { display: grid; gap: 10px; margin-top: 16px; }
.rekening-item {
    background: white; border-radius: 10px; padding: 14px 16px;
    border: 1.5px solid #E2E8F0; display: flex; align-items: center; gap: 14px;
}
.rek-icon { width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #4F46E5, #06B6D4); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
.rek-info .rek-bank { font-size: 0.8rem; color: #64748B; font-weight: 600; }
.rek-info .rek-no { font-size: 1rem; font-weight: 800; color: #0F172A; letter-spacing: 0.05em; }
.rek-info .rek-name { font-size: 0.78rem; color: #64748B; }
.upload-area {
    border: 2px dashed #CBD5E1; border-radius: 12px; padding: 30px;
    text-align: center; cursor: pointer; transition: all 0.2s; background: #F8FAFC;
}
.upload-area:hover, .upload-area.dragover { border-color: #4F46E5; background: #EEF2FF; }
.upload-area svg { width: 40px; height: 40px; color: #94A3B8; margin-bottom: 10px; }
.upload-area p { font-size: 0.85rem; color: #64748B; }
.upload-area strong { color: #4F46E5; }
#preview-img { max-width: 100%; border-radius: 10px; margin-top: 12px; display: none; }

/* Tampilan jumlah otomatis */
.jumlah-display {
    background: linear-gradient(135deg, #ECFDF5, #D1FAE5);
    border: 1.5px solid #6EE7B7;
    border-radius: 10px;
    padding: 14px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}
.jumlah-display .jumlah-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: #065F46;
}
.jumlah-display .jumlah-value {
    font-size: 1.15rem;
    font-weight: 900;
    color: #059669;
    letter-spacing: -0.01em;
}
.jumlah-display .jumlah-badge {
    background: #ECFDF5;
    border: 1px solid #A7F3D0;
    color: #065F46;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 50px;
    white-space: nowrap;
}
.no-paket-note {
    background: #FFF7ED;
    border: 1.5px solid #FED7AA;
    border-radius: 10px;
    padding: 12px 16px;
    font-size: 0.82rem;
    color: #9A3412;
    font-weight: 500;
}
</style>
@endsection

@section('content')
<div class="card" x-data="{ 
    selectedPaketId: '{{ $paket?->id ?? '' }}',
    pakets: [
        @foreach($pakets as $p)
        { id: '{{ $p->id }}', nama: '{{ $p->nama_paket }}', nominal: {{ $p->nominal ?? 0 }}, nominalFormatted: 'Rp {{ number_format($p->nominal ?? 0, 0, ',', '.') }}' },
        @endforeach
    ],
    get currentPaket() {
        return this.pakets.find(p => p.id == this.selectedPaketId);
    }
}">
    <div class="card-title">Pembayaran Pendaftaran</div>
    <div class="card-sub">Upload bukti pembayaran untuk menyelesaikan pendaftaran Anda.</div>

    @if($errors->any())
    <div class="alert-danger">
        <strong>Mohon periksa kembali:</strong>
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <div x-show="currentPaket" class="payment-info" x-cloak>
        <h4>Rincian Pembayaran</h4>
        <div class="payment-amount" x-text="currentPaket ? currentPaket.nominalFormatted : ''"></div>
        <div class="payment-note" x-text="currentPaket ? 'Paket: ' + currentPaket.nama : ''"></div>
    </div>

    <p class="section-label">CARA PEMBAYARAN</p>
    <div class="rekening-list">
        <div class="rekening-item">
            <div class="rek-icon">🏦</div>
            <div class="rek-info">
                <div class="rek-bank">BCA</div>
                <div class="rek-no">1234 5678 9012</div>
                <div class="rek-name">a.n. Genius Education</div>
            </div>
        </div>
        <div class="rekening-item">
            <div class="rek-icon">💳</div>
            <div class="rek-info">
                <div class="rek-bank">Mandiri</div>
                <div class="rek-no">0987 6543 2100</div>
                <div class="rek-name">a.n. Genius Education</div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('daftar.step3.store') }}" enctype="multipart/form-data">
        @csrf
        <p class="section-label">UPLOAD BUKTI BAYAR</p>
        <div class="form-grid">
            <div class="form-group form-col-full">
                <label>Paket Bimbingan Belajar <span class="req">*</span></label>
                <select name="paket_bimbingan_id" class="form-control" x-model="selectedPaketId" required>
                    <option value="">— Pilih Paket Bimbingan —</option>
                    <template x-for="p in pakets" :key="p.id">
                        <option :value="p.id" x-text="p.nama + ' (' + p.nominalFormatted + ')'" :selected="p.id == selectedPaketId"></option>
                    </template>
                </select>
                @error('paket_bimbingan_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Metode Pembayaran <span class="req">*</span></label>
                <select name="metode_pembayaran" class="form-control" required>
                    <option value="">— Pilih —</option>
                    <option value="Transfer BCA">Transfer BCA</option>
                    <option value="Transfer Mandiri">Transfer Mandiri</option>
                    <option value="Transfer BNI">Transfer BNI</option>
                    <option value="Transfer BRI">Transfer BRI</option>
                    <option value="Tunai">Tunai</option>
                </select>
                @error('metode_pembayaran')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Jumlah Pembayaran</label>
                <div x-show="currentPaket" x-cloak>
                    <div class="jumlah-display">
                        <div>
                            <div class="jumlah-label">Tagihan sesuai paket</div>
                            <div class="jumlah-value" x-text="currentPaket ? currentPaket.nominalFormatted : ''"></div>
                        </div>
                        <span class="jumlah-badge">✓ Otomatis</span>
                    </div>
                </div>
                <div x-show="!currentPaket" x-cloak>
                    <div class="no-paket-note">
                        ⚠️ Silakan pilih paket bimbingan di atas.
                    </div>
                </div>
            </div>

            <div class="form-group form-col-full">
                <label>Bukti Pembayaran <span class="req">*</span></label>
                <div class="upload-area" id="upload-area" onclick="document.getElementById('bukti_file').click()">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <p><strong>Klik untuk upload</strong> atau drag & drop</p>
                    <p style="font-size:0.75rem;margin-top:4px">JPG, PNG, atau PDF · Maks. 2 MB</p>
                    <img id="preview-img" src="" alt="Preview">
                </div>
                <input type="file" id="bukti_file" name="bukti_pembayaran" accept=".jpg,.jpeg,.png,.pdf" style="display:none" required onchange="previewFile(this)">
                @error('bukti_pembayaran')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="btn-row">
            <a href="{{ route('daftar.step2') }}" class="btn btn-secondary">← Kembali</a>
            <button type="submit" class="btn btn-primary">Kirim Pendaftaran ✓</button>
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
</script>
@endsection
