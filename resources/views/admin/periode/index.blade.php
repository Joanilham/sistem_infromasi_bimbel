@extends('layouts.admin')

@section('title', 'Pengaturan Periode')

@section('content')
<div class="bg-white dark:bg-slate-900/80 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:shadow-none sm:rounded-2xl mb-8 border border-slate-100 dark:border-transparent overflow-hidden">
    <div class="px-6 py-6 sm:px-8 flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
        <div>
            <h3 class="text-xl leading-6 font-bold text-slate-800 dark:text-white">
                Daftar Periode
            </h3>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Kelola master data periode tahun ajaran.</p>
        </div>
        <button onclick="document.getElementById('modal-create').classList.remove('hidden')" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-transparent text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md shadow-indigo-500/30 transition-all hover:-translate-y-0.5">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Periode
        </button>
    </div>

    <div class="overflow-x-auto p-4 sm:p-6">
        <table id="dataTable" class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
            <thead class="bg-slate-50 dark:bg-slate-800/80">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tahun Periode</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-100 dark:divide-slate-800/50">
                @foreach($periodes as $periode)
                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $periode->tahun_periode }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($periode->is_active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50">
                            <svg class="mr-1.5 h-2 w-2 text-emerald-500 dark:text-emerald-400" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            Aktif
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                            <svg class="mr-1.5 h-2 w-2 text-slate-400 dark:text-slate-500" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            Tidak Aktif
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-3">
                            <button type="button" onclick="editPeriode(this)" data-id="{{ $periode->id }}" data-tahun="{{ $periode->tahun_periode }}" data-active="{{ $periode->is_active ? '1' : '0' }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/30 px-3 py-1 rounded-lg transition-colors">Edit</button>
                            <form action="{{ route('periode.destroy', $periode->id) }}" method="POST" class="inline" id="form-delete-periode-{{ $periode->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete('Hapus Periode?', 'Apakah Anda yakin ingin menghapus periode {{ $periode->tahun_periode }}?', document.getElementById('form-delete-periode-{{ $periode->id }}'))" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 bg-red-50 dark:bg-red-900/30 px-3 py-1 rounded-lg transition-colors">Hapus</button>
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
        <input type="text" name="tahun_periode" id="tahun_periode" required class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800 placeholder-slate-400 dark:placeholder-slate-500" placeholder="Contoh: 2025-2026">
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
        const isActive = button.getAttribute('data-active') === '1';

        document.getElementById('form-modal-edit').action = '/periode/' + id;
        document.getElementById('edit_tahun_periode').value = tahun;
        document.getElementById('edit_is_active').checked = isActive;
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