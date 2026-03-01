<form action="{{ route('paket-bimbingan.destroy', $paket->id) }}" method="POST" class="inline" id="form-delete-{{ $paket->id }}">
    @csrf
    @method('DELETE')
    <button type="button" onclick="confirmDelete('Hapus Paket Bimbingan?', 'Apakah Anda yakin ingin menghapus Paket {{ $paket->nama_paket }}?', document.getElementById('form-delete-{{ $paket->id }}'))" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 bg-red-50 dark:bg-red-900/30 px-3 py-1 rounded-lg transition-colors">Hapus</button>
</form>