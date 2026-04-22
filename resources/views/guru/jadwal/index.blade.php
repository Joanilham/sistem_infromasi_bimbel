@extends('layouts.guru')

@section('title', 'Jadwal Mata Pelajaran')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">Jadwal Mata Pelajaran</h2>
            <p class="text-sm text-slate-500">Kelola jadwal mengajar Anda</p>
        </div>
        <button onclick="document.getElementById('tambahJadwalModal').showModal()" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Jadwal
        </button>
    </div>

    @if(session('success'))
    <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-200">
                    <th class="text-left py-3 px-4 text-sm font-medium text-slate-600">Hari</th>
                    <th class="text-left py-3 px-4 text-sm font-medium text-slate-600">Jam</th>
                    <th class="text-left py-3 px-4 text-sm font-medium text-slate-600">Kelas</th>
                    <th class="text-left py-3 px-4 text-sm font-medium text-slate-600">Mata Pelajaran</th>
                    <th class="text-right py-3 px-4 text-sm font-medium text-slate-600">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwal as $item)
                <tr class="border-b border-slate-100 hover:bg-slate-50">
                    <td class="py-3 px-4">{{ $item->hari }}</td>
                    <td class="py-3 px-4">{{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}</td>
                    <td class="py-3 px-4">{{ $item->kelas }}</td>
                    <td class="py-3 px-4">{{ $item->mapel }}</td>
                    <td class="py-3 px-4 text-right">
                        <button onclick="editJadwal({{ $item }})" class="text-indigo-600 hover:text-indigo-800 mr-3">Edit</button>
                        <form action="{{ route('guru.jadwal.destroy', $item->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Yakin hapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-slate-400">Belum ada jadwal</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Jadwal -->
<dialog id="tambahJadwalModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Tambah Jadwal</h3>
        <form action="{{ route('guru.jadwal.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Hari</label>
                    <select name="hari" required class="w-full border border-slate-300 rounded-lg px-3 py-2">
                        <option value="">Pilih Hari</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Jam Mulai</label>
                        <input type="time" name="jam_mulai" required class="w-full border border-slate-300 rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Jam Selesai</label>
                        <input type="time" name="jam_selesai" required class="w-full border border-slate-300 rounded-lg px-3 py-2">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Kelas</label>
                    <input type="text" name="kelas" required placeholder="Contoh: Kelas 10" class="w-full border border-slate-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Mata Pelajaran</label>
                    <input type="text" name="mapel" required class="w-full border border-slate-300 rounded-lg px-3 py-2">
                </div>
            </div>
            <div class="modal-action">
                <button type="button" onclick="document.getElementById('tambahJadwalModal').close()" class="btn btn-ghost">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<!-- Modal Edit Jadwal -->
<dialog id="editJadwalModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Edit Jadwal</h3>
        <form id="editJadwalForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Hari</label>
                    <select name="hari" id="edit_hari" required class="w-full border border-slate-300 rounded-lg px-3 py-2">
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Jam Mulai</label>
                        <input type="time" name="jam_mulai" id="edit_jam_mulai" required class="w-full border border-slate-300 rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Jam Selesai</label>
                        <input type="time" name="jam_selesai" id="edit_jam_selesai" required class="w-full border border-slate-300 rounded-lg px-3 py-2">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Kelas</label>
                    <input type="text" name="kelas" id="edit_kelas" required class="w-full border border-slate-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Mata Pelajaran</label>
                    <input type="text" name="mapel" id="edit_mapel" required class="w-full border border-slate-300 rounded-lg px-3 py-2">
                </div>
            </div>
            <div class="modal-action">
                <button type="button" onclick="document.getElementById('editJadwalModal').close()" class="btn btn-ghost">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<script>
function editJadwal(jadwal) {
    document.getElementById('edit_hari').value = jadwal.hari;
    document.getElementById('edit_jam_mulai').value = jadwal.jam_mulai;
    document.getElementById('edit_jam_selesai').value = jadwal.jam_selesai;
    document.getElementById('edit_kelas').value = jadwal.kelas;
    document.getElementById('edit_mapel').value = jadwal.mapel;
    document.getElementById('editJadwalForm').action = '/guru/jadwal/' + jadwal.id;
    document.getElementById('editJadwalModal').showModal();
}
</script>
@endsection