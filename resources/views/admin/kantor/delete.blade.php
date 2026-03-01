<form action="{{ route('kantor.destroy', $kantor->id) }}" method="POST" class="inline" id="form-delete-kantor-{{ $kantor->id }}">
    @csrf
    @method('DELETE')
    <button type="button" onclick="confirmDelete('Hapus Kantor?', 'Apakah Anda yakin ingin menghapus kantor {{ $kantor->nama_kantor }}?', document.getElementById('form-delete-kantor-{{ $kantor->id }}'))" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 bg-red-50 dark:bg-red-900/30 px-3 py-1 rounded-lg transition-colors">Hapus</button>
</form>