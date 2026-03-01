<form action="{{ route('pengguna.destroy', $p->id) }}" method="POST" class="inline" id="form-delete-pengguna-{{ $p->id }}">
    @csrf
    @method('DELETE')
    <button type="button"
        onclick="confirmDelete('Hapus Pengguna?', 'Apakah Anda yakin ingin menghapus pengguna {{ $p->name }}?', document.getElementById('form-delete-pengguna-{{ $p->id }}'))"
        class="btn-icon btn-icon-delete" title="Hapus"
        {{ $p->id === Auth::id() ? 'disabled style=opacity:.4;cursor:not-allowed' : '' }}>
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
    </button>
</form>