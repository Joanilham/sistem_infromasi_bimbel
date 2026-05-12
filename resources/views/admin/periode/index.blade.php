@extends('layouts.admin')

@section('title', 'Pengaturan Periode')

@section('content')
<div class="admin-table-card">
    {{-- Header --}}
    <div class="admin-table-header">
        <div>
            <div class="admin-table-title">Daftar Periode</div>
            <div class="admin-table-subtitle">Kelola master data periode tahun ajaran aktif dan arsip.</div>
        </div>
        <button @click="$dispatch('open-modal-create')" class="btn-add">
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
                    <th class="text-center">Status</th>
                    <th class="text-right" style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($periodes as $periode)
                <tr>
                    <td><span class="cell-no">{{ $loop->iteration }}</span></td>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="cell-label">{{ $periode->tahun_periode }}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        @if($periode->is_active)
                            <span class="badge badge-success">
                                <span class="badge-dot bg-emerald-500"></span>
                                AKTIF
                            </span>
                        @else
                            <span class="badge badge-secondary">
                                <span class="badge-dot bg-slate-400"></span>
                                TIDAK AKTIF
                            </span>
                        @endif
                    </td>
                    <td style="text-align:right">
                        <div class="action-group">
                            <button type="button" 
                                @click="$dispatch('open-modal-edit', { id: '{{ $periode->id }}', tahun: '{{ $periode->tahun_periode }}', active: {{ $periode->is_active ? 'true' : 'false' }} })"
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
@endsection

@push('modals')
<div x-data="{ 
    modalCreate: false, 
    modalEdit: false,
    editData: { id: '', tahun: '', active: false }
}" 
@open-modal-create.window="modalCreate = true"
@open-modal-edit.window="editData = $event.detail; modalEdit = true;"
x-cloak>
    
    <!-- Modal Create -->
    <div x-show="modalCreate" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="modalCreate" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity z-[100]" aria-hidden="true" @click="modalCreate = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="modalCreate" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative z-[110] inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100 dark:border-slate-700">
                <form action="{{ route('periode.store') }}" method="POST">
                    @csrf
                    <div class="bg-white dark:bg-slate-900 px-6 pt-6 pb-6">
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="text-xl font-bold text-slate-800 dark:text-white" id="modal-title">Tambah Periode Baru</h3>
                            <button type="button" @click="modalCreate = false" class="text-slate-400 dark:text-slate-500 hover:text-slate-500 dark:hover:text-slate-300 focus:outline-none">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label for="tahun_periode" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Tahun Periode</label>
                                <input type="text" name="tahun_periode" id="tahun_periode" required
                                    class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800 placeholder-slate-400 dark:placeholder-slate-500"
                                    placeholder="Contoh: 2025/2026">
                            </div>
                            <div class="flex items-start mt-4 bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-100 dark:border-slate-700">
                                <div class="flex items-center h-5">
                                    <input id="is_active" name="is_active" type="checkbox" value="1" class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-slate-300 dark:border-slate-600 rounded cursor-pointer dark:bg-slate-800 transition-all">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_active" class="font-bold text-slate-800 dark:text-slate-200 cursor-pointer">Set Sebagai Periode Aktif</label>
                                    <p class="text-slate-500 dark:text-slate-400 text-xs mt-0.5">Hanya satu periode yang bisa aktif dalam satu waktu.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 sm:flex sm:flex-row-reverse rounded-b-2xl border-t border-slate-100 dark:border-slate-700 gap-2">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-md px-6 py-2.5 bg-indigo-600 text-base font-bold text-white hover:bg-indigo-700 focus:outline-none sm:w-auto sm:text-sm transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                            Simpan Periode
                        </button>
                        <button type="button" @click="modalCreate = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 dark:border-slate-600 shadow-sm px-6 py-2.5 bg-white dark:bg-slate-700 text-base font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm transition-all">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div x-show="modalEdit" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="modalEdit" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity z-[100]" aria-hidden="true" @click="modalEdit = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="modalEdit" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative z-[110] inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100 dark:border-slate-700">
                <form :action="'{{ url('periode') }}/' + editData.id" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white dark:bg-slate-900 px-6 pt-6 pb-6">
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="text-xl font-bold text-slate-800 dark:text-white" id="modal-title">Edit Periode</h3>
                            <button type="button" @click="modalEdit = false" class="text-slate-400 dark:text-slate-500 hover:text-slate-500 dark:hover:text-slate-300 focus:outline-none">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label for="edit_tahun_periode" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Tahun Periode</label>
                                <input type="text" name="tahun_periode" id="edit_tahun_periode" x-model="editData.tahun" required
                                    class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800 placeholder-slate-400 dark:placeholder-slate-500">
                            </div>
                            <div class="flex items-start mt-4 bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-100 dark:border-slate-700">
                                <div class="flex items-center h-5">
                                    <input id="edit_is_active" name="is_active" type="checkbox" value="1" x-model="editData.active" class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-slate-300 dark:border-slate-600 rounded cursor-pointer dark:bg-slate-800 transition-all">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="edit_is_active" class="font-bold text-slate-800 dark:text-slate-200 cursor-pointer">Set Sebagai Periode Aktif</label>
                                    <p class="text-slate-500 dark:text-slate-400 text-xs mt-0.5">Jadikan sebagai periode utama aplikasi.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 sm:flex sm:flex-row-reverse rounded-b-2xl border-t border-slate-100 dark:border-slate-700 gap-2">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-md px-6 py-2.5 bg-indigo-600 text-base font-bold text-white hover:bg-indigo-700 focus:outline-none sm:w-auto sm:text-sm transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                            Update Periode
                        </button>
                        <button type="button" @click="modalEdit = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 dark:border-slate-600 shadow-sm px-6 py-2.5 bg-white dark:bg-slate-700 text-base font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm transition-all">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        if (!$.fn.DataTable.isDataTable('#dataTable')) {
            $('#dataTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                },
                dom: '<"flex flex-col md:flex-row justify-between items-center mb-4 gap-4"lf>rt<"flex flex-col md:flex-row justify-between items-center mt-4 gap-4"ip>',
            });
        }
    });
</script>
@endpush