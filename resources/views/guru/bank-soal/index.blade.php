@extends('layouts.guru')

@section('title', 'Bank Soal')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">Bank Soal</h2>
            <p class="text-sm text-slate-500">Kelola kumpulan soal untuk ujian</p>
        </div>
        <button onclick="document.getElementById('tambahBankSoalModal').showModal()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Bank Soal
        </button>
    </div>

    @if(session('success'))
    <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($bankSoals as $bankSoal)
        <div class="border border-slate-200 rounded-xl p-4 hover:shadow-md transition-shadow">
            <h3 class="font-semibold text-slate-800">{{ $bankSoal->judul }}</h3>
            <p class="text-sm text-slate-500 mt-1">{{ $bankSoal->deskripsi ?? 'Tidak ada deskripsi' }}</p>
            <div class="flex items-center justify-between mt-4">
                <span class="text-sm text-slate-600">{{ $bankSoal->jumlah_soal }} soal</span>
                <div class="flex gap-2">
                    <a href="{{ route('guru.bank-soal.show', $bankSoal->id) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">Lihat</a>
                    <button onclick="editBankSoal({{ $bankSoal }})" class="text-slate-600 hover:text-slate-800 text-sm">Edit</button>
                    <form action="{{ route('guru.bank-soal.destroy', $bankSoal->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-8 text-slate-400">
            Belum ada bank soal. Buat bank soal pertama Anda!
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Tambah Bank Soal -->
<dialog id="tambahBankSoalModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Buat Bank Soal Baru</h3>
        <form action="{{ route('guru.bank-soal.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Judul</label>
                    <input type="text" name="judul" required placeholder="Contoh: Soa Matematika Kelas 10" class="w-full border border-slate-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" placeholder="Deskripsi bank soal..." class="w-full border border-slate-300 rounded-lg px-3 py-2"></textarea>
                </div>
            </div>
            <div class="modal-action">
                <button type="button" onclick="document.getElementById('tambahBankSoalModal').close()" class="btn btn-ghost">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<!-- Modal Edit Bank Soal -->
<dialog id="editBankSoalModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Edit Bank Soal</h3>
        <form id="editBankSoalForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Judul</label>
                    <input type="text" name="judul" id="edit_judul" required class="w-full border border-slate-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" id="edit_deskripsi" rows="3" class="w-full border border-slate-300 rounded-lg px-3 py-2"></textarea>
                </div>
            </div>
            <div class="modal-action">
                <button type="button" onclick="document.getElementById('editBankSoalModal').close()" class="btn btn-ghost">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<script>
function editBankSoal(bankSoal) {
    document.getElementById('edit_judul').value = bankSoal.judul;
    document.getElementById('edit_deskripsi').value = bankSoal.deskripsi || '';
    document.getElementById('editBankSoalForm').action = '/guru/bank-soal/' + bankSoal.id;
    document.getElementById('editBankSoalModal').showModal();
}
</script>
@endsection