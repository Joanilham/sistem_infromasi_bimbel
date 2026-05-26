@extends('layouts.guru')

@section('title', 'Edit Soal')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="editSoalForm()">

    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 p-6">
        <a href="{{ route('guru.bank-soal.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-sm inline-flex items-center gap-1 mb-3 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Bank Soal
        </a>
        <h2 class="text-xl font-bold text-slate-800 dark:text-white">Edit Soal</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Perbarui isi soal dan opsi jawaban</p>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('guru.bank-soal.update', $soal->id) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Kategori --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 p-6">
            <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                <span class="w-6 h-6 bg-indigo-100 dark:bg-indigo-900/40 rounded-lg flex items-center justify-center text-indigo-600 dark:text-indigo-400 text-xs font-bold">1</span>
                Kategori Soal
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Mata Pelajaran</label>
                    <select name="cbt_mapel_id" x-model="mapelId" @change="loadBabs()"
                        class="w-full border border-slate-200 dark:border-zinc-700 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">-- Pilih Mapel --</option>
                        @foreach($mapels as $m)
                        <option value="{{ $m->id }}" {{ old('cbt_mapel_id', $soal->cbt_mapel_id) == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Topik / Bab</label>
                    <select name="cbt_bab_id" x-model="babId"
                        class="w-full border border-slate-200 dark:border-zinc-700 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">-- Pilih Topik --</option>
                        <template x-for="bab in babList" :key="bab.id">
                            <option :value="bab.id" x-text="bab.nama" :selected="bab.id == babId"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tipe Soal <span class="text-red-500">*</span></label>
                    <select name="tipe_soal" x-model="tipeSoal"
                        class="w-full border border-slate-200 dark:border-zinc-700 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="pg">Pilihan Ganda</option>
                        <option value="benar_salah">Benar / Salah</option>
                        <option value="essay">Essay</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tingkat Kesulitan <span class="text-red-500">*</span></label>
                    <select name="tingkat_kesulitan"
                        class="w-full border border-slate-200 dark:border-zinc-700 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="easy" {{ old('tingkat_kesulitan', $soal->tingkat_kesulitan) == 'easy' ? 'selected' : '' }}>Mudah</option>
                        <option value="medium" {{ old('tingkat_kesulitan', $soal->tingkat_kesulitan) == 'medium' ? 'selected' : '' }}>Sedang</option>
                        <option value="hard" {{ old('tingkat_kesulitan', $soal->tingkat_kesulitan) == 'hard' ? 'selected' : '' }}>Sulit</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Pertanyaan --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 p-6">
            <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                <span class="w-6 h-6 bg-indigo-100 dark:bg-indigo-900/40 rounded-lg flex items-center justify-center text-indigo-600 dark:text-indigo-400 text-xs font-bold">2</span>
                Pertanyaan
            </h3>
            <x-soal-editor name="pertanyaan" :value="old('pertanyaan', $soal->pertanyaan)" placeholder="Tulis pertanyaan di sini..." />
            @error('pertanyaan') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror

            {{-- Media --}}
            <div class="mt-4">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Gambar Soal</label>
                @if($soal->file_media)
                <div class="mb-3 flex items-center gap-3">
                    <img src="{{ asset('storage/' . $soal->file_media) }}" class="h-20 rounded-lg border border-slate-200 dark:border-zinc-700">
                    <span class="text-xs text-slate-400 dark:text-slate-500">Gambar saat ini</span>
                </div>
                @endif
                <label class="flex items-center gap-2 px-4 py-2.5 border border-dashed border-slate-300 dark:border-zinc-600 rounded-xl cursor-pointer hover:border-indigo-400 transition-all text-sm text-slate-500 dark:text-slate-400 w-fit">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Ganti Gambar</span>
                    <input type="file" name="media" accept="image/jpeg,image/png" class="hidden">
                </label>
            </div>
        </div>

        {{-- Opsi Jawaban (PG & Benar/Salah) --}}
        <div x-show="['pg', 'benar_salah'].includes(tipeSoal)" x-transition
            class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 p-6">
            <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                <span class="w-6 h-6 bg-indigo-100 dark:bg-indigo-900/40 rounded-lg flex items-center justify-center text-indigo-600 dark:text-indigo-400 text-xs font-bold">3</span>
                Opsi Jawaban
            </h3>
            <div class="space-y-3">
                <template x-for="(opsi, index) in opsiList" :key="index">
                    <div class="flex items-start gap-3 group">
                        <div class="pt-2.5">
                            <input type="radio" name="kunci" :value="index" x-model.number="kunciJawaban"
                                class="w-4 h-4 text-emerald-600 border-slate-300 dark:border-zinc-600 focus:ring-emerald-500 cursor-pointer">
                        </div>
                        <span class="w-8 h-10 flex items-center justify-center text-sm font-bold rounded-lg shrink-0"
                            :class="kunciJawaban === index ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400' : 'bg-slate-100 dark:bg-zinc-800 text-slate-500 dark:text-slate-400'"
                            x-text="['A','B','C','D','E'][index]"></span>
                        <input type="text" :name="'opsi['+index+']'" x-model="opsi.teks" placeholder="Tulis opsi jawaban..." required
                            class="flex-1 border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors"
                            :class="kunciJawaban === index ? 'border-emerald-300 dark:border-emerald-700 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-900 dark:text-emerald-100' : 'border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-800 dark:text-white'">
                        <button type="button" @click="removeOpsi(index)" x-show="opsiList.length > 2 && tipeSoal !== 'benar_salah'"
                            class="px-2 py-2.5 text-slate-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </template>
            </div>
            <div class="mt-4">
                <button type="button" @click="addOpsi()" x-show="opsiList.length < 5 && tipeSoal !== 'benar_salah'"
                    class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 font-medium flex items-center gap-1 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Opsi
                </button>
            </div>
        </div>

        {{-- Pembahasan --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 p-6">
            <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                <span class="w-6 h-6 bg-indigo-100 dark:bg-indigo-900/40 rounded-lg flex items-center justify-center text-indigo-600 dark:text-indigo-400 text-xs font-bold" x-text="tipeSoal === 'pg' ? '4' : '3'"></span>
                Pembahasan
            </h3>
            <x-soal-editor name="pembahasan" :value="old('pembahasan', $soal->pembahasan?->teks_pembahasan ?? '')" placeholder="Penjelasan jawaban yang benar..." />
        </div>

        {{-- Submit --}}
        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-2">
            <a href="{{ route('guru.bank-soal.index') }}"
                class="px-6 py-2.5 border border-slate-200 dark:border-zinc-700 text-slate-600 dark:text-slate-300 rounded-xl text-sm font-medium hover:bg-slate-50 dark:hover:bg-zinc-800 text-center transition-colors">Batal</a>
            <button type="submit"
                class="px-8 py-2.5 bg-gradient-to-r from-indigo-500 to-blue-600 text-white rounded-xl text-sm font-semibold shadow-md shadow-indigo-500/25 hover:from-indigo-600 hover:to-blue-700 hover:shadow-lg transition-all duration-200">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function editSoalForm() {
    @php
        $opsiData = $soal->opsiJawabans->map(fn($o, $i) => ['teks' => $o->teks_opsi])->values();
        $kunciIdx = $soal->opsiJawabans->search(fn($o) => $o->is_benar);
        $defaultOpsi = [['teks'=>''],['teks'=>''],['teks'=>''],['teks'=>'']];
        $opsiFinal = $opsiData->count() ? $opsiData : $defaultOpsi;
    @endphp
    return {
        tipeSoal: '{{ old("tipe_soal", $soal->tipe_soal) }}',
        mapelId: '{{ old("cbt_mapel_id", $soal->cbt_mapel_id ?? "") }}',
        babId: '{{ old("cbt_bab_id", $soal->cbt_bab_id ?? "") }}',
        babList: @json($babs),
        kunciJawaban: {{ $kunciIdx !== false ? $kunciIdx : 0 }},
        opsiList: @json($opsiFinal),

        addOpsi() { if (this.opsiList.length < 5) this.opsiList.push({ teks: '' }); },
        removeOpsi(index) {
            this.opsiList.splice(index, 1);
            if (this.kunciJawaban >= this.opsiList.length) this.kunciJawaban = 0;
        },
        async loadBabs() {
            if (!this.mapelId) { this.babList = []; this.babId = ''; return; }
            const res = await fetch(`{{ route('guru.bank-soal.bab') }}?mapel_id=${this.mapelId}`);
            this.babList = await res.json();
            this.babId = '';
        },
        init() {
            this.$watch('tipeSoal', value => {
                if (value === 'benar_salah') {
                    this.opsiList = [{ teks: 'Benar' }, { teks: 'Salah' }];
                    this.kunciJawaban = 0;
                } else if (value === 'pg' && this.opsiList.length < 2) {
                    this.opsiList = [{ teks: '' }, { teks: '' }, { teks: '' }, { teks: '' }];
                }
            });
        }
    }
}
</script>
@endpush
@endsection
