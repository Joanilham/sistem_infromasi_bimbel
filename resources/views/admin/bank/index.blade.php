@extends('layouts.admin')

@section('title', 'Manajemen Rekening Bank')

@section('content')
<div class="space-y-6" x-data="ajaxTable()">

    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Manajemen Rekening Bank</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm font-medium">Kelola rekening bank lembaga untuk menerima pembayaran siswa.</p>
        </div>
        <button onclick="document.getElementById('modal-create').classList.remove('hidden')"
           class="inline-flex items-center gap-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs px-8 py-4 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all active:scale-95 group shrink-0">
            <svg class="w-5 h-5 transition-transform group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Rekening Bank
        </button>
    </div>

    {{-- Blok pesan @if(session('success')) lokal SAYA HAPUS di sini agar tidak tumpang tindih dengan bawaan layouts.admin --}}

    {{-- Table Card --}}
    <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-100 dark:border-zinc-800 shadow-sm overflow-hidden">

        {{-- Toolbar Filter & Search --}}
        <div class="p-5 sm:p-6 border-b border-slate-100 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/50 relative">

            {{-- Loading Overlay --}}
            <div x-show="isLoading" class="absolute inset-0 z-50 bg-white/50 dark:bg-zinc-900/50 backdrop-blur-sm flex items-center justify-center transition-opacity duration-300" style="display: none;">
                <div class="bg-white dark:bg-zinc-800 p-4 rounded-2xl shadow-xl border border-slate-100 dark:border-zinc-700 flex items-center gap-3">
                    <svg class="animate-spin h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm font-bold text-slate-700 dark:text-slate-200">Memuat data...</span>
                </div>
            </div>

            <form @submit.prevent="fetchData" method="GET" action="{{ route('bank.index') }}" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full">
                
                {{-- Per Page --}}
                <div class="flex items-stretch bg-white dark:bg-zinc-950 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all overflow-hidden w-max">
                    <div class="px-3 py-2 bg-slate-50 dark:bg-zinc-900 border-r border-slate-200 dark:border-zinc-800 flex items-center justify-center">
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Lihat</span>
                    </div>
                    <select name="per_page" @change="fetchData"
                        class="no-tomselect bg-transparent border-none text-xs font-black focus:ring-0 py-2 pl-3 pr-8 text-slate-800 dark:text-white cursor-pointer h-full hover:bg-slate-50 dark:hover:bg-zinc-900/50 transition-colors">
                        @foreach([10, 25, 50, 100] as $n)
                            <option value="{{ $n }}" {{ request('per_page', 10) == $n ? 'selected' : '' }}>{{ $n }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Search & Reset --}}
                <div class="flex items-center gap-2 w-full sm:w-auto shrink-0">
                    <div class="relative group flex-1 md:w-64">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari bank, no. rek, nama…"
                            class="w-full bg-white dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold py-2.5 pl-10 pr-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-sm">
                        <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-colors shadow-sm shadow-indigo-500/30 shrink-0">
                        Filter
                    </button>
                    @if(request()->anyFilled(['search']))
                        <a href="{{ route('bank.index') }}" 
                           class="flex items-center gap-2 bg-slate-100 dark:bg-zinc-800 hover:bg-rose-100 dark:hover:bg-rose-900/40 text-slate-600 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 px-4 py-2.5 rounded-xl text-sm font-bold transition-all shrink-0"
                           title="Reset Filter">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div id="ajax-table-body" class="overflow-x-auto" @click="if($event.target.closest('th a')) { navigate($event, $event.target.closest('a').href) }">
            <table class="w-full text-sm border-collapse border border-slate-200 dark:border-zinc-800">
                <thead class="bg-indigo-600 dark:bg-indigo-900/80 text-[10px] uppercase tracking-widest text-white font-black">
                    <tr>
                        <th class="px-4 py-3 text-left w-24 border border-white/20">No</th>
                        <th class="px-4 py-3 text-left border border-white/20">Nama Bank</th>
                        <th class="px-4 py-3 text-left border border-white/20">Nomor Rekening</th>
                        <th class="px-4 py-3 text-left border border-white/20">Atas Nama</th>
                        <th class="px-4 py-3 text-center w-32 border border-white/20">Status</th>
                        <th class="px-4 py-3 text-center w-32 border border-white/20">Opsi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900">
                    @forelse($banks as $i => $bank)
                        <tr class="hover:bg-indigo-50/50 dark:hover:bg-indigo-900/10 transition-all even:bg-slate-50/50 dark:even:bg-zinc-800/30">
                            <td class="px-4 py-3 text-slate-400 font-bold text-xs font-mono border border-slate-200 dark:border-zinc-800 text-center">
                                #{{ str_pad($banks->firstItem() + $i, 3, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-4 py-3 font-bold text-slate-900 dark:text-white border border-slate-200 dark:border-zinc-800">
                                {{ $bank->nama_bank }}
                            </td>
                            <td class="px-4 py-3 font-mono font-bold text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-zinc-800">
                                {{ $bank->nomor_rekening }}
                            </td>
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-300 font-bold border border-slate-200 dark:border-zinc-800 leading-tight">
                                {{ $bank->atas_nama }}
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800 text-center">
                                @if($bank->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-400">Aktif</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-800 dark:bg-zinc-800 dark:text-slate-400">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 border border-slate-200 dark:border-zinc-800">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" onclick="editBank(this)"
                                        data-id="{{ $bank->id }}"
                                        data-nama="{{ $bank->nama_bank }}"
                                        data-rekening="{{ $bank->nomor_rekening }}"
                                        data-atas_nama="{{ $bank->atas_nama }}"
                                        data-active="{{ $bank->is_active ? '1' : '0' }}"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded bg-amber-50 dark:bg-amber-900/10 text-amber-600 dark:text-amber-400 hover:bg-amber-100 transition-all"
                                        title="Edit Bank">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    
                                    {{-- Delete button inline modal confirmation --}}
                                    <form action="{{ route('bank.destroy', $bank->id) }}" method="POST" onsubmit="event.preventDefault(); confirmDelete('Hapus Rekening?', 'Apakah Anda yakin ingin menghapus rekening bank ini?', this)" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded bg-red-50 dark:bg-red-900/10 text-red-600 dark:text-red-400 hover:bg-red-100 transition-all" title="Hapus Bank">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-24 text-center border border-slate-200 dark:border-zinc-800">
                                <div class="w-20 h-20 bg-slate-50 dark:bg-zinc-800 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-4xl shadow-inner">
                                    💳
                                </div>
                                <h3 class="font-black text-slate-900 dark:text-white text-lg">Rekening Bank Belum Terdaftar</h3>
                                <p class="text-slate-400 text-sm mt-2 font-medium">Daftar rekening penerima pembayaran akan muncul di sini setelah Anda menambahkannya.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer Pagination --}}
        <div id="ajax-pagination" class="px-8 py-6 border-t border-slate-100 dark:border-zinc-800 bg-slate-50/30 dark:bg-zinc-900/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4"
             @click="if($event.target.closest('nav[role=navigation] a')) { navigate($event, $event.target.closest('a').href) }">
            <p class="text-xs text-slate-500 dark:text-slate-400 font-bold">
                Menampilkan <span class="text-slate-900 dark:text-white">{{ $banks->firstItem() ?? 0 }}</span> – <span class="text-slate-900 dark:text-white">{{ $banks->lastItem() ?? 0 }}</span> dari <span class="text-slate-900 dark:text-white">{{ $banks->total() ?? 0 }}</span> Rekening
            </p>
            @if($banks->hasPages())
                <div class="flex justify-end">
                    {{ $banks->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

</div>

{{-- MODAL CREATE --}}
<div id="modal-create" class="fixed inset-0 z-[60] overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity z-[60]" onclick="document.getElementById('modal-create').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="relative z-[70] inline-block align-middle bg-white dark:bg-zinc-900 rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-150 dark:border-zinc-800">
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white">Tambah Rekening Bank</h3>
                    <button onclick="document.getElementById('modal-create').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-50 dark:bg-zinc-800 text-slate-500 hover:bg-slate-100 transition flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form action="{{ route('bank.store') }}" method="POST" class="space-y-5" onsubmit="this.querySelector('button[type=submit]').disabled = true; this.querySelector('button[type=submit]').innerHTML = 'Menyimpan...';">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Nama Bank / Penerbit</label>
                        <input type="text" name="nama_bank" required placeholder="Contoh: Bank BCA, Bank Mandiri"
                            class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold p-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Nomor Rekening</label>
                        <input type="text" name="nomor_rekening" required placeholder="Contoh: 1234-5678-9012-3456" inputmode="numeric" maxlength="24" oninput="formatRekening(this)"
                            class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold p-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Atas Nama Pemilik</label>
                        <input type="text" name="atas_nama" required placeholder="Masukkan nama pemilik rekening"
                            class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold p-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    </div>
                    
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-zinc-800">
                        <button type="button" onclick="document.getElementById('modal-create').classList.add('hidden')"
                            class="px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-slate-600 dark:text-slate-300 font-bold text-xs transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-750 text-white font-bold text-xs shadow-lg shadow-indigo-500/20 transition">
                            Simpan Rekening
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
<div id="modal-edit" class="fixed inset-0 z-[60] overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity z-[60]" onclick="document.getElementById('modal-edit').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="relative z-[70] inline-block align-middle bg-white dark:bg-zinc-900 rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-150 dark:border-zinc-800">
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white">Edit Rekening Bank</h3>
                    <button onclick="document.getElementById('modal-edit').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-50 dark:bg-zinc-800 text-slate-500 hover:bg-slate-100 transition flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <form id="editForm" method="POST" class="space-y-5" onsubmit="this.querySelector('button[type=submit]').disabled = true; this.querySelector('button[type=submit]').innerHTML = 'Menyimpan...';">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Nama Bank / Penerbit</label>
                        <input type="text" name="nama_bank" id="edit_nama_bank" required placeholder="Contoh: Bank BCA, Bank Mandiri"
                            class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold p-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Nomor Rekening</label>
                        <input type="text" name="nomor_rekening" id="edit_nomor_rekening" required placeholder="Contoh: 1234-5678-9012-3456" inputmode="numeric" maxlength="24" oninput="formatRekening(this)"
                            class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold p-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Atas Nama Pemilik</label>
                        <input type="text" name="atas_nama" id="edit_atas_nama" required placeholder="Masukkan nama pemilik rekening"
                            class="w-full bg-slate-50 dark:bg-zinc-950 border-slate-200 dark:border-zinc-800 rounded-xl text-sm font-bold p-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" id="edit_is_active" value="1"
                            class="w-5 h-5 text-indigo-600 border-slate-200 dark:border-zinc-800 rounded focus:ring-indigo-500/20 transition-all">
                        <label for="edit_is_active" class="text-sm font-bold text-slate-700 dark:text-slate-300 select-none">Rekening Aktif (Tampilkan untuk Siswa)</label>
                    </div>
                    
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-zinc-800">
                        <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')"
                            class="px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-slate-600 dark:text-slate-300 font-bold text-xs transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-750 text-white font-bold text-xs shadow-lg shadow-indigo-500/20 transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function editBank(btn) {
        const id = btn.getAttribute('data-id');
        const nama = btn.getAttribute('data-nama');
        const rek = btn.getAttribute('data-rekening');
        const pemilik = btn.getAttribute('data-atas_nama');
        const aktif = btn.getAttribute('data-active');

        const form = document.getElementById('editForm');
        form.action = `/bank/${id}`;

        document.getElementById('edit_nama_bank').value = nama;
        document.getElementById('edit_nomor_rekening').value = rek;
        document.getElementById('edit_atas_nama').value = pemilik;
        document.getElementById('edit_is_active').checked = aktif == '1';

        // Format the rekening number initially
        formatRekening(document.getElementById('edit_nomor_rekening'));

        document.getElementById('modal-edit').classList.remove('hidden');
    }

    function formatRekening(input) {
        // Hanya ambil angka
        let val = input.value.replace(/\D/g, '');
        
        // Format pengelompokan setiap 4 digit, dipisah dash
        let formatted = '';
        for (let i = 0; i < val.length; i++) {
            if (i > 0 && i % 4 === 0) {
                formatted += '-';
            }
            formatted += val[i];
        }
        input.value = formatted;
    }
</script>
@endsection