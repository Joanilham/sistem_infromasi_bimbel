@extends('layouts.admin')

@section('title', 'Paket Bimbingan')

@section('content')
<div class="admin-table-card">
    {{-- Header --}}
    <div class="admin-table-header">
        <div>
            <div class="admin-table-title">Daftar Paket Bimbingan</div>
            <div class="admin-table-subtitle">Kelola data paket bimbingan belajar dan nominal harga.</div>
        </div>
        <button onclick="document.getElementById('modal-create').classList.remove('hidden')" class="btn-add">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Paket
        </button>
    </div>

    {{-- Table --}}
    <div class="admin-table-body">
        <table id="dataTable" class="admin-datatable">
            <thead>
                <tr>
                    <th style="width:50px">No</th>
                    <th>Nama Paket</th>
                    <th>Durasi</th>
                    <th>Nominal</th>
                    <th>Label</th>
                    <th class="text-right" style="width:100px">Opsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($paketBimbingans as $paket)
                <tr>
                    <td><span class="cell-no">{{ $loop->iteration }}</span></td>
                    <td>
                        <div class="flex items-center gap-3">
                            @if($paket->gambar_paket)
                                <img src="{{ asset('storage/' . $paket->gambar_paket) }}" class="w-10 h-10 rounded-lg object-contain bg-slate-50 border border-slate-100">
                            @endif
                            <span class="cell-label font-bold">{{ $paket->nama_paket }}</span>
                        </div>
                    </td>
                    <td>
                        @if($paket->durasi_jumlah)
                            <span class="text-xs font-medium text-slate-600 dark:text-slate-400">
                                {{ $paket->durasi_jumlah }} {{ $paket->durasi_satuan }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <div class="flex flex-col">
                            @if($paket->harga_coret)
                                <span class="text-[10px] text-slate-400 line-through">Rp {{ number_format($paket->harga_coret, 0, ',', '.') }}</span>
                            @endif
                            <span class="badge badge-emerald">
                                <span class="badge-dot" style="background:#059669"></span>
                                Rp {{ number_format($paket->nominal, 0, ',', '.') }}
                            </span>
                        </div>
                    </td>
                    <td>
                        @if($paket->label_populer)
                            <span class="px-2 py-0.5 bg-orange-100 text-orange-600 text-[10px] font-bold rounded-full uppercase">
                                {{ $paket->label_populer }}
                            </span>
                        @endif
                    </td>
                    <td style="text-align:right">
                        <div class="action-group">
                            <button type="button" onclick="editPaket(this)"
                                data-id="{{ $paket->id }}"
                                data-nama="{{ $paket->nama_paket }}"
                                data-nominal="{{ $paket->nominal }}"
                                data-coret="{{ $paket->harga_coret }}"
                                data-durasi-jml="{{ $paket->durasi_jumlah }}"
                                data-durasi-sat="{{ $paket->durasi_satuan }}"
                                data-label="{{ $paket->label_populer }}"
                                data-deskripsi="{{ $paket->deskripsi }}"
                                data-benefits="{{ $paket->benefits }}"
                                data-featured="{{ $paket->is_featured ? '1' : '0' }}"
                                class="btn-icon btn-icon-edit" title="Edit">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            @include('admin.paket_bimbingan.delete')
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@include('admin.paket_bimbingan.create')
@include('admin.paket_bimbingan.edit')
@include('admin.paket_bimbingan.script')

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