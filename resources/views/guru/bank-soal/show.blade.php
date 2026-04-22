@extends('layouts.guru')

@section('title', 'Bank Soal - ' . $bankSoal->judul)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <div class="flex justify-between items-start">
            <div>
                <a href="{{ route('guru.bank-soal.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm mb-2 inline-flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali
                </a>
                <h2 class="text-lg font-semibold text-slate-800">{{ $bankSoal->judul }}</h2>
                <p class="text-sm text-slate-500">{{ $bankSoal->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                <p class="text-sm text-slate-600 mt-2">Total soal: {{ $bankSoal->jumlah_soal }}</p>
            </div>
            <button onclick="document.getElementById('tambahSoalModal').showModal()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Soal
            </button>
        </div>
    </div>

    <!-- List of Questions -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <h3 class="font-semibold text-slate-800 mb-4">Daftar Soal</h3>
        
        @if(session('success'))
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        @forelse($soals as $index => $soal)
        <div class="border border-slate-200 rounded-lg p-4 mb-4">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="bg-indigo-100 text-indigo-700 text-xs font-medium px-2 py-1 rounded">No. {{ $index + 1 }}</span>
                        <span class="bg-slate-100 text-slate-600 text-xs font-medium px-2 py-1 rounded">{{ $soal->tipe === 'pilihan_ganda' ? 'Pilihan Ganda' : 'Essay' }}</span>
                        <span class="bg-emerald-100 text-emerald-700 text-xs font-medium px-2 py-1 rounded">{{ $soal->poin }} poin</span>
                    </div>
                    <p class="text-slate-800">{{ $soal->pertanyaan }}</p>
                    
                    @if($soal->tipe === 'pilihan_ganda')
                    <div class="mt-3 space-y-2">
                        @if($soal->opsi_a) <div class="text-sm text-slate-600">A. {{ $soal->opsi_a['text'] ?? '' }}</div> @endif
                        @if($soal->opsi_b) <div class="text-sm text-slate-600">B. {{ $soal->opsi_b['text'] ?? '' }}</div> @endif
                        @if($soal->opsi_c) <div class="text-sm text-slate-600">C. {{ $soal->opsi_c['text'] ?? '' }}</div> @endif
                        @if($soal->opsi_d) <div class="text-sm text-slate-600">D. {{ $soal->opsi_d['text'] ?? '' }}</div> @endif
                    </div>
                    <div class="mt-2 text-sm font-medium text-green-600">
                        Jawaban: {{ strtoupper($soal->jawaban_benar) }}
                    </div>
                    @endif
                </div>
                <form action="{{ route('guru.bank-soal.soal.destroy', [$bankSoal->id, $soal->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm" onclick="return confirm('Yakin hapus soa?')">Hapus</button>
                </form>
            </div>
        </div>
        @empty
        <div class="text-center py-8 text-slate-400">
            Belum ada soal. Tambahkan soa pertama Anda!
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Tambah Soa -->
<dialog id="tambahSoalModal" class="modal">
    <div class="modal-box max-w-2xl">
        <h3 class="font-bold text-lg mb-4">Tambah Soal Baru</h3>
        <form action="{{ route('guru.bank-soal.soal.store', $bankSoal->id) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Pertanyaan</label>
                    <textarea name="pertanyaan" required rows="3" class="w-full border border-slate-300 rounded-lg px-3 py-2"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tipe Soal</label>
                    <select name="tipe" id="tipeSoal" required class="w-full border border-slate-300 rounded-lg px-3 py-2" onchange="toggleOpsi()">
                        <option value="pilihan_ganda">Pilihan Ganda</option>
                        <option value="essay">Essay</option>
                    </select>
                </div>
                
                <div id="opsiContainer">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Opsi A</label>
                            <input type="text" name="opsi_a" class="w-full border border-slate-300 rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Opsi B</label>
                            <input type="text" name="opsi_b" class="w-full border border-slate-300 rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Opsi C</label>
                            <input type="text" name="opsi_c" class="w-full border border-slate-300 rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Opsi D</label>
                            <input type="text" name="opsi_d" class="w-full border border-slate-300 rounded-lg px-3 py-2">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Jawaban Benar</label>
                        <select name="jawaban_benar" class="w-full border border-slate-300 rounded-lg px-3 py-2">
                            <option value="a">A</option>
                            <option value="b">B</option>
                            <option value="c">C</option>
                            <option value="d">D</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Poin</label>
                    <input type="number" name="poin" value="10" min="1" class="w-full border border-slate-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Pembahasan (Opsional)</label>
                    <textarea name="pembahasan" rows="2" class="w-full border border-slate-300 rounded-lg px-3 py-2"></textarea>
                </div>
            </div>
            <div class="modal-action">
                <button type="button" onclick="document.getElementById('tambahSoalModal').close()" class="btn btn-ghost">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<script>
function toggleOpsi() {
    const tipe = document.getElementById('tipeSoal').value;
    const opsiContainer = document.getElementById('opsiContainer');
    opsiContainer.style.display = tipe === 'pilihan_ganda' ? 'block' : 'none';
}
</script>
@endsection