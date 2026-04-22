@extends('layouts.admin')

@section('title', 'Pengaturan Periode')

@section('content')
<div class="admin-table-card">
    {{-- Header --}}
    <div class="admin-table-header">
        <div>
            <div class="admin-table-title">Daftar Periode</div>
            <div class="admin-table-subtitle">Kelola master data periode tahun ajaran.</div>
        </div>
        <button onclick="document.getElementById('modal-create').classList.remove('hidden')" class="btn-add">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Periode
        </button>
    </div>

    {{-- Table --}}
    <div class="admin-table-body">
        <table id="dataTable" class="admin-datatable">
            <thead>
                <tr>
                    <th style="width:50px">No</th>
                    <th>Tahun Periode</th>
                    <th class="text-right" style="width:100px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($periodes as $periode)
                <tr>
                    <td><span class="cell-no">{{ $loop->iteration }}</span></td>
                    <td><span class="cell-label">{{ $periode->tahun_periode }}</span></td>
                    <td style="text-align:right">
                        <div class="action-group">
                            <button type="button" onclick="editPeriode(this)"
                                data-id="{{ $periode->id }}"
                                data-tahun="{{ $periode->tahun_periode }}"
                                data-active="{{ $periode->is_active ? '1' : '0' }}"
                                class="btn-icon btn-icon-edit" title="Edit">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <form action="{{ route('periode.destroy', $periode->id) }}" method="POST" class="inline" id="form-delete-periode-{{ $periode->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    onclick="confirmDelete('Hapus Periode?', 'Apakah Anda yakin ingin menghapus periode {{ $periode->tahun_periode }}?', document.getElementById('form-delete-periode-{{ $periode->id }}'))"
                                    class="btn-icon btn-icon-delete" title="Hapus">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Create -->
<x-modal-form id="modal-create" title="Tambah Periode Baru" action="{{ route('periode.store') }}">
    <div>
        <label for="tahun_periode" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Tahun Periode</label>
        <input type="text" name="tahun_periode" id="tahun_periode" required
            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800 placeholder-slate-400 dark:placeholder-slate-500"
            placeholder="Contoh: 2025/2026">
    </div>
    <div class="flex items-start mt-4 bg-slate-50 dark:bg-slate-800/50 p-3 rounded-xl border border-slate-100 dark:border-slate-700">
        <div class="flex items-center h-5">
            <input id="is_active" name="is_active" type="checkbox" value="1" class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-slate-300 dark:border-slate-600 rounded cursor-pointer dark:bg-slate-800">
        </div>
        <div class="ml-3 text-sm">
            <label for="is_active" class="font-medium text-slate-800 dark:text-slate-200 cursor-pointer">Periode Aktif</label>
            <p class="text-slate-500 dark:text-slate-400">Jadikan sebagai periode yang sedang berjalan saat ini.</p>
        </div>
    </div>
</x-modal-form>

<!-- Modal Edit -->
<x-modal-form id="modal-edit" title="Edit Periode" action="">
    <x-slot name="method">
        @method('PUT')
    </x-slot>
    <div>
        <label for="edit_tahun_periode" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Tahun Periode</label>
        <input type="text" name="tahun_periode" id="edit_tahun_periode" required class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800">
    </div>
    <div class="flex items-start mt-4 bg-slate-50 dark:bg-slate-800/50 p-3 rounded-xl border border-slate-100 dark:border-slate-700">
        <div class="flex items-center h-5">
            <input id="edit_is_active" name="is_active" type="checkbox" value="1" class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-slate-300 dark:border-slate-600 rounded cursor-pointer dark:bg-slate-800">
        </div>
        <div class="ml-3 text-sm">
            <label for="edit_is_active" class="font-medium text-slate-800 dark:text-slate-200 cursor-pointer">Periode Aktif</label>
            <p class="text-slate-500 dark:text-slate-400">Jadikan sebagai periode yang sedang berjalan saat ini.</p>
        </div>
    </div>
</x-modal-form>

<script>
    function editPeriode(button) {
        const id = button.getAttribute('data-id');
        const tahun = button.getAttribute('data-tahun');
        const active = button.getAttribute('data-active') === '1';
        document.getElementById('form-modal-edit').action = '/periode/' + id;
        document.getElementById('edit_tahun_periode').value = tahun;
        document.getElementById('edit_is_active').checked = active;
        document.getElementById('modal-edit').classList.remove('hidden');
    }
</script>

@section('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"lf>rt<"flex flex-col md:flex-row justify-between items-center mt-4"ip>',
        });
    });
</script>
@endsection
@endsection