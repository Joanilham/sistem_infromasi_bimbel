@extends('layouts.guru')

@section('title', 'Buat Soal Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="soalForm()">

    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 p-6">
        <a href="{{ route('guru.bank-soal.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-sm inline-flex items-center gap-1 mb-3 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Bank Soal
        </a>
        <h2 class="text-xl font-bold text-slate-800 dark:text-white">Buat Soal Baru</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Buat soal untuk bank soal Anda dengan editor teks</p>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('guru.bank-soal.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Kategori & Metadata --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 p-6">
            <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                <span class="w-6 h-6 bg-indigo-100 dark:bg-indigo-900/40 rounded-lg flex items-center justify-center text-indigo-600 dark:text-indigo-400 text-xs font-bold">1</span>
                Kategori Soal
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Mata Pelajaran --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Mata Pelajaran</label>
                    <div class="flex gap-2">
                        <select name="cbt_mapel_id" x-model="mapelId" @change="loadBabs()"
                            class="no-tomselect flex-1 border border-slate-200 dark:border-zinc-700 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent {{ isset($mapel) ? 'bg-slate-50 dark:bg-zinc-800/50 text-slate-500 dark:text-slate-400 cursor-not-allowed' : 'bg-white dark:bg-zinc-800 text-slate-800 dark:text-white' }}"
                            {{ isset($mapel) ? 'style="pointer-events:none;" tabindex="-1"' : '' }}>
                            @if(!isset($mapel))
                            <option value="">-- Pilih Mapel --</option>
                            @endif
                            @foreach($mapels as $m)
                            <option value="{{ $m->id }}" {{ (isset($mapel) || old('cbt_mapel_id') == $m->id) ? 'selected' : '' }}>{{ $m->nama }}</option>
                            @endforeach
                        </select>
                        @if(!isset($mapel))
                        <button type="button" @click="showAddMapel = true"
                            class="px-3 py-2.5 border border-dashed border-slate-300 dark:border-zinc-600 rounded-lg text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:border-indigo-300 dark:hover:border-indigo-600 transition-colors" title="Tambah Mapel">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </button>
                        @endif
                    </div>
                    @error('cbt_mapel_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Topik / Bab --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Topik / Bab</label>
                    <div class="flex gap-2">
                        <select name="cbt_bab_id" x-model="babId"
                            class="no-tomselect flex-1 border border-slate-200 dark:border-zinc-700 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">-- Pilih Topik --</option>
                            <template x-for="bab in babList" :key="bab.id">
                                <option :value="bab.id" x-text="bab.nama"></option>
                            </template>
                        </select>
                        <button type="button" @click="showAddBab = true" :disabled="!mapelId"
                            class="px-3 py-2.5 border border-dashed border-slate-300 dark:border-zinc-600 rounded-lg text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:border-indigo-300 disabled:opacity-40 disabled:cursor-not-allowed transition-colors" title="Tambah Topik">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Tipe Soal --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tipe Soal <span class="text-red-500">*</span></label>
                    <select name="tipe_soal" x-model="tipeSoal"
                        class="no-tomselect w-full border border-slate-200 dark:border-zinc-700 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="pg">Pilihan Ganda</option>
                        <option value="benar_salah">Benar / Salah</option>
                        <option value="essay">Essay</option>
                    </select>
                </div>

                {{-- Tingkat Kesulitan --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tingkat Kesulitan <span class="text-red-500">*</span></label>
                    <select name="tingkat_kesulitan"
                        class="no-tomselect w-full border border-slate-200 dark:border-zinc-700 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="easy" {{ old('tingkat_kesulitan') == 'easy' ? 'selected' : '' }}>Mudah</option>
                        <option value="medium" {{ old('tingkat_kesulitan', 'medium') == 'medium' ? 'selected' : '' }}>Sedang</option>
                        <option value="hard" {{ old('tingkat_kesulitan') == 'hard' ? 'selected' : '' }}>Sulit</option>
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

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Teks Pertanyaan <span class="text-red-500">*</span></label>
                <x-soal-editor name="pertanyaan" :value="old('pertanyaan')" placeholder="Tulis pertanyaan di sini..." />
                @error('pertanyaan') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Upload Gambar --}}
            <div class="mt-4">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Gambar Soal <span class="text-slate-400 font-normal">(Opsional, max 2MB)</span></label>
                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2 px-4 py-2.5 border border-dashed border-slate-300 dark:border-zinc-600 rounded-xl cursor-pointer hover:border-indigo-400 dark:hover:border-indigo-500 hover:bg-indigo-50/50 dark:hover:bg-indigo-900/10 transition-all text-sm text-slate-500 dark:text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Pilih Gambar</span>
                        <input type="file" name="media" accept="image/jpeg,image/png" class="hidden" @change="previewImage($event)">
                    </label>
                    <template x-if="imagePreview">
                        <div class="relative">
                            <img :src="imagePreview" class="h-16 w-auto rounded-lg border border-slate-200 dark:border-zinc-700">
                            <button type="button" @click="imagePreview = null" class="absolute -top-1.5 -right-1.5 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">✕</button>
                        </div>
                    </template>
                </div>
                @error('media') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
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
                        {{-- Radio kunci jawaban --}}
                        <div class="pt-2.5">
                            <input type="radio" name="kunci" :value="index" x-model.number="kunciJawaban"
                                class="w-4 h-4 text-emerald-600 border-slate-300 dark:border-zinc-600 focus:ring-emerald-500 cursor-pointer">
                        </div>
                        {{-- Label --}}
                        <span class="w-8 h-10 flex items-center justify-center text-sm font-bold rounded-lg shrink-0"
                            :class="kunciJawaban === index ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400' : 'bg-slate-100 dark:bg-zinc-800 text-slate-500 dark:text-slate-400'"
                            x-text="['A','B','C','D','E'][index]">
                        </span>
                        {{-- Input --}}
                        <input type="text" :name="'opsi['+index+']'" x-model="opsi.teks" placeholder="Tulis opsi jawaban..." required
                            class="flex-1 border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors"
                            :class="kunciJawaban === index ? 'border-emerald-300 dark:border-emerald-700 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-900 dark:text-emerald-100' : 'border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-800 dark:text-white'">
                        {{-- Remove button --}}
                        <button type="button" @click="removeOpsi(index)" x-show="opsiList.length > 2 && tipeSoal !== 'benar_salah'"
                            class="px-2 py-2.5 text-slate-400 hover:text-red-500 dark:hover:text-red-400 opacity-0 group-hover:opacity-100 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </template>
            </div>

            <div class="flex items-center gap-3 mt-4">
                <button type="button" @click="addOpsi()" x-show="opsiList.length < 5 && tipeSoal !== 'benar_salah'"
                    class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium flex items-center gap-1 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Opsi
                </button>
                <span class="text-xs text-slate-400 dark:text-slate-500 ml-auto">
                    <svg class="w-3.5 h-3.5 inline -mt-0.5 mr-0.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    Pilih radio untuk menandai kunci jawaban
                </span>
            </div>
        </div>

        {{-- Pembahasan --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 p-6">
            <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                <span class="w-6 h-6 bg-indigo-100 dark:bg-indigo-900/40 rounded-lg flex items-center justify-center text-indigo-600 dark:text-indigo-400 text-xs font-bold" x-text="tipeSoal === 'pg' ? '4' : '3'"></span>
                Pembahasan <span class="text-slate-400 dark:text-slate-500 font-normal text-xs ml-1">(Opsional)</span>
            </h3>
            <x-soal-editor name="pembahasan" :value="old('pembahasan')" placeholder="Penjelasan jawaban yang benar..." />
        </div>

        {{-- Submit Buttons --}}
        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-2">
            <a href="{{ route('guru.bank-soal.index') }}"
                class="px-6 py-2.5 border border-slate-200 dark:border-zinc-700 text-slate-600 dark:text-slate-300 rounded-xl text-sm font-medium hover:bg-slate-50 dark:hover:bg-zinc-800 text-center transition-colors">
                Batal
            </a>
            <button type="submit"
                class="px-8 py-2.5 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-xl text-sm font-semibold shadow-md shadow-emerald-500/25 hover:from-emerald-600 hover:to-green-700 hover:shadow-lg transition-all duration-200">
                Simpan Soal
            </button>
        </div>
    </form>

    {{-- Modal: Tambah Mapel --}}
    <div x-show="showAddMapel" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" @click.self="showAddMapel = false" style="display:none">
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-zinc-700 p-6 w-full max-w-sm" @click.stop>
            <h3 class="font-bold text-slate-800 dark:text-white mb-4">Tambah Mata Pelajaran</h3>
            <input type="text" x-model="newMapelNama" placeholder="Nama mata pelajaran..." class="w-full border border-slate-200 dark:border-zinc-700 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500 mb-4">
            <div class="flex justify-end gap-2">
                <button type="button" @click="showAddMapel = false" class="px-4 py-2 text-sm text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200">Batal</button>
                <button type="button" @click="saveMapel()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700">Simpan</button>
            </div>
        </div>
    </div>

    {{-- Modal: Tambah Bab --}}
    <div x-show="showAddBab" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" @click.self="showAddBab = false" style="display:none">
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-zinc-700 p-6 w-full max-w-sm" @click.stop>
            <h3 class="font-bold text-slate-800 dark:text-white mb-4">Tambah Topik / Bab</h3>
            <input type="text" x-model="newBabNama" placeholder="Nama topik..." class="w-full border border-slate-200 dark:border-zinc-700 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500 mb-4">
            <div class="flex justify-end gap-2">
                <button type="button" @click="showAddBab = false" class="px-4 py-2 text-sm text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200">Batal</button>
                <button type="button" @click="saveBab()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700">Simpan</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function soalForm() {
    return {
        tipeSoal: '{{ old("tipe_soal", "pg") }}',
        mapelId: '{{ old("cbt_mapel_id", isset($mapel) ? $mapel->id : "") }}',
        babId: '{{ old("cbt_bab_id", "") }}',
        babList: @json($babs),
        kunciJawaban: {{ old('kunci', 0) }},
        opsiList: [
            { teks: '{{ old("opsi.0", "") }}' },
            { teks: '{{ old("opsi.1", "") }}' },
            { teks: '{{ old("opsi.2", "") }}' },
            { teks: '{{ old("opsi.3", "") }}' },
        ],
        imagePreview: null,
        showAddMapel: false,
        showAddBab: false,
        newMapelNama: '',
        newBabNama: '',

        addOpsi() {
            if (this.opsiList.length < 5) {
                this.opsiList.push({ teks: '' });
            }
        },
        removeOpsi(index) {
            this.opsiList.splice(index, 1);
            if (this.kunciJawaban >= this.opsiList.length) {
                this.kunciJawaban = 0;
            }
        },
        previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                this.imagePreview = URL.createObjectURL(file);
            }
        },
        async loadBabs() {
            if (!this.mapelId) {
                this.babList = [];
                this.babId = '';
                return;
            }
            try {
                const res = await fetch(`{{ route('guru.bank-soal.bab') }}?mapel_id=${this.mapelId}`);
                this.babList = await res.json();
                this.babId = '';
            } catch (e) {
                console.error('Gagal load bab:', e);
            }
        },
        async saveMapel() {
            if (!this.newMapelNama.trim()) return;
            try {
                const res = await fetch('{{ route('guru.bank-soal.mapel.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ nama: this.newMapelNama }),
                });
                const mapel = await res.json();
                // Add option to the select
                const select = document.querySelector('select[name="cbt_mapel_id"]');
                const option = new Option(mapel.nama, mapel.id, true, true);
                select.add(option);
                this.mapelId = mapel.id;
                this.newMapelNama = '';
                this.showAddMapel = false;
                this.loadBabs();
            } catch (e) {
                alert('Gagal menyimpan mapel.');
            }
        },
        async saveBab() {
            if (!this.newBabNama.trim() || !this.mapelId) return;
            try {
                const res = await fetch('{{ route('guru.bank-soal.bab.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ nama: this.newBabNama, cbt_mapel_id: this.mapelId }),
                });
                const bab = await res.json();
                this.babList.push(bab);
                this.babId = bab.id;
                this.newBabNama = '';
                this.showAddBab = false;
            } catch (e) {
                alert('Gagal menyimpan topik.');
            }
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
