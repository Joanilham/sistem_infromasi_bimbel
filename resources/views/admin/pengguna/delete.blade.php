<form action="{{ route('pengguna.destroy', $p->id) }}" method="POST" class="inline" id="form-delete-pengguna-{{ $p->id }}">
    @csrf
    @method('DELETE')
    <button type="button" onclick="confirmDelete('Hapus Pengguna?', 'Apakah Anda yakin ingin menghapus pengguna {{ $p->name }}?', document.getElementById('form-delete-pengguna-{{ $p->id }}'))" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 bg-red-50 dark:bg-red-900/30 px-3 py-1 rounded-lg transition-colors" {{ $p->id === Auth::id() ? 'disabled' : '' }}>Hapus</button>
</form>