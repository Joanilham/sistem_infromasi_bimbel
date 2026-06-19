@extends('layouts.admin')

@section('title', 'Edit Data Siswa')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Edit Data Siswa</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm font-medium">Ubah informasi peserta didik yang telah terdaftar.</p>
        </div>
        <a href="{{ url()->previous() == route('peserta-didik.edit', $pesertaDidik->id) ? route('peserta-didik.index') : url()->previous() }}" class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-50 dark:bg-zinc-800 hover:bg-slate-100 dark:hover:bg-zinc-700 text-slate-500 dark:text-slate-400 transition-all">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden w-full" x-data="{ status: '{{ old('status', $pesertaDidik->status) }}' }">
        <form action="{{ route('peserta-didik.update', $pesertaDidik->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="p-8 sm:p-10 space-y-10">
                
                {{-- Data Pribadi --}}
                <div class="space-y-4">
                    <h3 class="text-xs font-black uppercase tracking-widest text-indigo-500 flex items-center gap-2 border-b border-slate-100 dark:border-zinc-800 pb-2">
                        <span class="w-6 h-6 rounded bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center">1</span> Data Pribadi
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-2 sm:col-span-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_lengkap" required value="{{ old('nama_lengkap', $pesertaDidik->nama_lengkap) }}" placeholder="Nama lengkap siswa" class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">NISN <span class="text-rose-500">*</span></label>
                            <input type="text" name="nisn" required value="{{ old('nisn', $pesertaDidik->nisn) }}" placeholder="10 digit angka" inputmode="numeric" pattern="[0-9]{10}" maxlength="10" oninput="this.value=this.value.replace(/[^0-9]/g,'')" class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Jenis Kelamin <span class="text-rose-500">*</span></label>
                            <select name="jenis_kelamin" required class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                                <option value="">- Pilih Jenis Kelamin -</option>
                                <option value="L" {{ old('jenis_kelamin', $pesertaDidik->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $pesertaDidik->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $pesertaDidik->tempat_lahir) }}" placeholder="Kota kelahiran" class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $pesertaDidik->tanggal_lahir) }}" class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Agama</label>
                            <select name="agama" class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                                <option value="">- Pilih Agama -</option>
                                @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $agm)
                                    <option value="{{ $agm }}" {{ old('agama', $pesertaDidik->agama) == $agm ? 'selected' : '' }}>{{ $agm }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">No. Telepon</label>
                            <input type="tel" name="no_telepon" id="no_telepon" value="{{ old('no_telepon', $pesertaDidik->no_telepon) }}" class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                        </div>
                        <div class="space-y-2 sm:col-span-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Alamat Lengkap</label>
                            <textarea name="alamat_lengkap" rows="2" placeholder="Alamat rumah lengkap" class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white custom-scrollbar">{{ old('alamat_lengkap', $pesertaDidik->alamat_lengkap) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Data Akademik --}}
                <div class="space-y-4">
                    <h3 class="text-xs font-black uppercase tracking-widest text-emerald-500 flex items-center gap-2 border-b border-slate-100 dark:border-zinc-800 pb-2">
                        <span class="w-6 h-6 rounded bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center">2</span> Data Akademik
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-2 sm:col-span-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Asal Sekolah <span class="text-rose-500">*</span></label>
                            <input type="text" name="asal_sekolah" required value="{{ old('asal_sekolah', $pesertaDidik->asal_sekolah) }}" placeholder="Nama sekolah asal" class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Paket Program <span class="text-rose-500">*</span></label>
                            <select name="paket_bimbingan_id" required class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                                <option value="">- Pilih Paket Program -</option>
                                @foreach($paketBimbingans as $paket)
                                    <option value="{{ $paket->id }}" {{ old('paket_bimbingan_id', $pesertaDidik->paket_bimbingan_id) == $paket->id ? 'selected' : '' }}>{{ $paket->nama_paket }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Kelas/Kelompok</label>
                            <select name="kelompok_belajar_id" class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                                <option value="">- Pilih Kelas -</option>
                                @foreach($kelompokBelajars as $kb)
                                    <option value="{{ $kb->id }}" {{ old('kelompok_belajar_id', $pesertaDidik->kelompok_belajar_id) == $kb->id ? 'selected' : '' }}>{{ $kb->nama_kelompok }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Status Keanggotaan <span class="text-rose-500">*</span></label>
                            <select name="status" x-model="status" class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                                <option value="Aktif">Aktif</option>
                                <option value="Keluar">Keluar / Berhenti</option>
                                <option value="Lulus">Lulus</option>
                            </select>
                        </div>
@php
    $sumberList = ['Brosur', 'Instagram', 'Facebook', 'Tiktok', 'Teman/Keluarga', 'Guru/Sekolah', 'Website/Internet', 'Spanduk/Banner'];
    $currentSumber = old('informasi_dari', $pesertaDidik->informasi_dari);
    $isLainnya = $currentSumber !== '' && $currentSumber !== null && !in_array($currentSumber, $sumberList);
    $selectValue = $isLainnya ? 'Lainnya' : $currentSumber;
@endphp
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Sumber Informasi</label>
                            <select id="informasi_dari_select" name="{{ $isLainnya ? '' : 'informasi_dari' }}" class="w-full border bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white" onchange="toggleInformasiLainnya(this)">
                                <option value="">- Pilih Sumber Informasi -</option>
                                @foreach($sumberList as $sumber)
                                    <option value="{{ $sumber }}" {{ $selectValue == $sumber ? 'selected' : '' }}>{{ $sumber }}</option>
                                @endforeach
                                <option value="Lainnya" {{ $selectValue == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            
                            <div id="informasi_dari_lainnya_container" style="display: {{ $isLainnya ? 'block' : 'none' }}; margin-top: 10px;">
                                <input type="text" id="informasi_dari_input" name="{{ $isLainnya ? 'informasi_dari' : '' }}" class="w-full border bg-white dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white" value="{{ $isLainnya ? $currentSumber : '' }}" placeholder="Tuliskan sumber informasi...">
                            </div>
                        </div>
                    </div>
                    
                    {{-- Keluar Section --}}
                    <div x-show="status === 'Keluar'" x-collapse>
                        <div class="p-6 bg-rose-50 dark:bg-rose-900/10 border border-rose-100 dark:border-rose-900/50 rounded-2xl space-y-6 mt-4">
                            <h4 class="text-[10px] font-black text-rose-600 dark:text-rose-500 uppercase tracking-widest border-b border-rose-200/50 pb-2">Informasi Keluar</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-[10px] font-black text-rose-500 uppercase tracking-widest ml-1">Tanggal Keluar <span class="text-rose-500">*</span></label>
                                    <input type="date" name="tanggal_keluar" :required="status === 'Keluar'" value="{{ old('tanggal_keluar', $pesertaDidik->tanggal_keluar) }}" class="w-full border bg-white dark:bg-zinc-950 border-rose-200 dark:border-rose-900/50 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all text-slate-800 dark:text-white">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-[10px] font-black text-rose-500 uppercase tracking-widest ml-1">Keterangan Keluar <span class="text-rose-500">*</span></label>
                                    <textarea name="alasan_keluar" :required="status === 'Keluar'" rows="2" placeholder="Alasan..." class="w-full border bg-white dark:bg-zinc-950 border-rose-200 dark:border-rose-900/50 rounded-2xl text-sm font-bold py-3 px-4 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all text-slate-800 dark:text-white custom-scrollbar">{{ old('alasan_keluar', $pesertaDidik->alasan_keluar) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Data Wali --}}
                <div class="space-y-4">
                    <h3 class="text-xs font-black uppercase tracking-widest text-amber-500 flex items-center gap-2 border-b border-slate-100 dark:border-zinc-800 pb-2">
                        <span class="w-6 h-6 rounded bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center">3</span> Data Orang Tua / Wali
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 bg-slate-50/50 dark:bg-zinc-900/50 p-6 rounded-3xl border border-slate-100 dark:border-zinc-800">
                        {{-- Data Ayah --}}
                        <div class="space-y-4 sm:border-r border-slate-200 dark:border-zinc-800 sm:pr-6">
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Nama Ayah</label>
                                <input type="text" name="nama_ayah" value="{{ old('nama_ayah', $pesertaDidik->nama_ayah) }}" placeholder="Nama ayah kandung" class="w-full border bg-white dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold py-2.5 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Pekerjaan Ayah</label>
                                <input type="text" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah', $pesertaDidik->pekerjaan_ayah) }}" placeholder="Pekerjaan" class="w-full border bg-white dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold py-2.5 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Telepon Ayah</label>
                                <input type="tel" name="no_telepon_ayah" id="no_telepon_ayah" value="{{ old('no_telepon_ayah', $pesertaDidik->no_telepon_ayah) }}" class="w-full border bg-white dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold py-2.5 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                            </div>
                        </div>
                        {{-- Data Ibu --}}
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Nama Ibu</label>
                                <input type="text" name="nama_ibu" value="{{ old('nama_ibu', $pesertaDidik->nama_ibu) }}" placeholder="Nama ibu kandung" class="w-full border bg-white dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold py-2.5 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Pekerjaan Ibu</label>
                                <input type="text" name="pekerjaan_ibu" value="{{ old('pekerjaan_ibu', $pesertaDidik->pekerjaan_ibu) }}" placeholder="Pekerjaan" class="w-full border bg-white dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold py-2.5 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Telepon Ibu</label>
                                <input type="tel" name="no_telepon_ibu" id="no_telepon_ibu" value="{{ old('no_telepon_ibu', $pesertaDidik->no_telepon_ibu) }}" class="w-full border bg-white dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold py-2.5 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-slate-800 dark:text-white">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
            {{-- Footer --}}
            <div class="px-8 sm:px-10 py-6 bg-slate-50 dark:bg-zinc-950 border-t border-slate-200 dark:border-zinc-800 flex items-center justify-end gap-4">
                <a href="{{ url()->previous() == route('peserta-didik.edit', $pesertaDidik->id) ? route('peserta-didik.index') : url()->previous() }}" class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-white bg-rose-500 hover:bg-rose-600 rounded-2xl transition-all shadow-xl shadow-rose-500/20 active:scale-95">Batal</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-black uppercase tracking-widest px-8 py-4 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all active:scale-95">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.4/build/css/intlTelInput.css">
<style>
    .iti { width: 100%; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.4/build/js/intlTelInput.min.js"></script>
<script>
window.toggleInformasiLainnya = function(selectEl) {
    const container = document.getElementById('informasi_dari_lainnya_container');
    const input = document.getElementById('informasi_dari_input');
    if (selectEl.value === 'Lainnya') {
        container.style.display = 'block';
        input.setAttribute('name', 'informasi_dari');
        selectEl.removeAttribute('name');
        input.focus();
    } else {
        container.style.display = 'none';
        input.removeAttribute('name');
        selectEl.setAttribute('name', 'informasi_dari');
    }
};

document.addEventListener('turbo:load', function() {
    const phoneInputs = [
        document.querySelector("#no_telepon"),
        document.querySelector("#no_telepon_ayah"),
        document.querySelector("#no_telepon_ibu")
    ];
    
    const itiInstances = [];

    phoneInputs.forEach(input => {
        if(input) {
            const iti = window.intlTelInput(input, {
                initialCountry: "id",
                preferredCountries: ["id", "my", "sg", "au"],
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.4/build/js/utils.js",
                showSelectedDialCode: true,
                countrySearch: true,
                strictMode: true
            });
            itiInstances.push({ input: input, iti: iti });
            
            // Cegah input/paste teks (hanya boleh angka dan +)
            input.addEventListener('input', function() {
                this.value = this.value.replace(/[^\d+]/g, '');
            });
        }
    });

    const form = document.querySelector('form');
    if(form) {
        form.addEventListener('submit', function() {
            itiInstances.forEach(item => {
                if (item.input.value.trim() !== '') {
                    item.input.value = item.iti.getNumber().replace(/\D/g, '');
                }
            });
        });
    }
});
</script>
@endpush

