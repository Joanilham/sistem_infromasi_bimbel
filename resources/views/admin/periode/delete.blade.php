<form action="{{ route('periode.destroy', $periode->id) }}" method="POST" class="inline" id="form-delete-periode-{{ $periode->id }}">
    @csrf
    @method('DELETE')
    <button type="button" onclick="confirmDelete('Hapus Periode?', 'Apakah Anda yakin ingin menghapus periode {{ $periode->tahun_periode }}?', document.getElementById('form-delete-periode-{{ $periode->id }}'))" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 bg-red-50 dark:bg-red-900/30 px-3 py-1 rounded-lg transition-colors">Hapus</button>
</form>