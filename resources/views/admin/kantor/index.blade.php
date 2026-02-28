@extends('layouts.admin')

@section('title', 'Pengaturan Kantor')

@section('content')
<div class="bg-white dark:bg-slate-900/80 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:shadow-none sm:rounded-2xl mb-8 border border-slate-100 dark:border-transparent overflow-hidden">
    <div class="px-6 py-6 sm:px-8 flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
        <div>
            <h3 class="text-xl leading-6 font-bold text-slate-800 dark:text-white">
                Daftar Kantor
            </h3>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Kelola data kantor cabang Genius Education.</p>
        </div>
        <button onclick="document.getElementById('modal-create').classList.remove('hidden')" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-transparent text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md shadow-indigo-500/30 transition-all hover:-translate-y-0.5">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Kantor
        </button>
    </div>

    <div class="overflow-x-auto p-4 sm:p-6">
        <table id="dataTable" class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
            <thead class="bg-slate-50 dark:bg-slate-800/80">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Nama Kantor</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Alamat</th>
                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-100 dark:divide-slate-800/50">
                @foreach($kantors as $kantor)
                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $kantor->nama_kantor }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300 max-w-xs truncate">{{ $kantor->alamat ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-3">
                            <button type="button" onclick="editKantor(this)" data-id="{{ $kantor->id }}" data-nama="{{ $kantor->nama_kantor }}" data-alamat="{{ $kantor->alamat }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/30 px-3 py-1 rounded-lg transition-colors">Edit</button>
                            <form action="{{ route('kantor.destroy', $kantor->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kantor {{ $kantor->nama_kantor }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 bg-red-50 dark:bg-red-900/30 px-3 py-1 rounded-lg transition-colors">Hapus</button>
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
<div id="modal-create" class="fixed inset-0 z-[60] overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity z-[60]" aria-hidden="true" onclick="document.getElementById('modal-create').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="relative z-[70] inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100 dark:border-slate-700">
            <form action="{{ route('kantor.store') }}" method="POST">
                @csrf
                <div class="bg-white dark:bg-slate-900 px-6 pt-6 pb-6">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-xl font-bold text-slate-800 dark:text-white" id="modal-title">Tambah Kantor Baru</h3>
                        <button type="button" class="text-slate-400 dark:text-slate-500 hover:text-slate-500 dark:hover:text-slate-300 focus:outline-none" onclick="document.getElementById('modal-create').classList.add('hidden')">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label for="nama_kantor" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Kantor</label>
                            <input type="text" name="nama_kantor" id="nama_kantor" required class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800 placeholder-slate-400 dark:placeholder-slate-500" placeholder="Misal: Kantor Pusat Jakarta">
                        </div>
                        <div>
                            <label for="alamat" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Alamat</label>
                            <textarea name="alamat" id="alamat" rows="3" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800 placeholder-slate-400 dark:placeholder-slate-500" placeholder="Masukkan alamat lengkap divisi/kantor"></textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 sm:flex sm:flex-row-reverse rounded-b-2xl border-t border-slate-100 dark:border-slate-700">
                    <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-5 py-2.5 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Simpan
                    </button>
                    <button type="button" onclick="document.getElementById('modal-create').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 dark:border-slate-600 shadow-sm px-5 py-2.5 bg-white dark:bg-slate-700 text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div id="modal-edit" class="fixed inset-0 z-[60] overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity z-[60]" aria-hidden="true" onclick="document.getElementById('modal-edit').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="relative z-[70] inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100 dark:border-slate-700">
            <form id="form-edit" method="POST">
                @csrf
                @method('PUT')
                <div class="bg-white dark:bg-slate-900 px-6 pt-6 pb-6">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-xl font-bold text-slate-800 dark:text-white" id="modal-title">Edit Kantor</h3>
                        <button type="button" class="text-slate-400 dark:text-slate-500 hover:text-slate-500 dark:hover:text-slate-300 focus:outline-none" onclick="document.getElementById('modal-edit').classList.add('hidden')">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label for="edit_nama_kantor" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Kantor</label>
                            <input type="text" name="nama_kantor" id="edit_nama_kantor" required class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800">
                        </div>
                        <div>
                            <label for="edit_alamat" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Alamat</label>
                            <textarea name="alamat" id="edit_alamat" rows="3" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 dark:border-slate-700 rounded-xl py-2.5 px-3 border text-slate-900 dark:text-white bg-white dark:bg-slate-800"></textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 sm:flex sm:flex-row-reverse rounded-b-2xl border-t border-slate-100 dark:border-slate-700">
                    <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-5 py-2.5 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Simpan Perubahan
                    </button>
                    <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 dark:border-slate-600 shadow-sm px-5 py-2.5 bg-white dark:bg-slate-700 text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function editKantor(button) {
        const id = button.getAttribute('data-id');
        const nama = button.getAttribute('data-nama');
        const alamat = button.getAttribute('data-alamat');

        document.getElementById('form-edit').action = '/kantor/' + id;
        document.getElementById('edit_nama_kantor').value = nama;
        document.getElementById('edit_alamat').value = alamat;
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