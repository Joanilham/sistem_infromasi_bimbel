@extends('layouts.admin')

@section('title', 'Kelompok Belajar')

@section('content')
<div class="admin-table-card">
    {{-- Header --}}
    <div class="admin-table-header">
        <div>
            <div class="admin-table-title">Daftar Kelompok Belajar</div>
            <div class="admin-table-subtitle">Kelola data kelompok belajar peserta didik.</div>
        </div>
        <button onclick="document.getElementById('modal-create').classList.remove('hidden')" class="btn-add">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Kelompok
        </button>
    </div>

    {{-- Table --}}
    <div class="admin-table-body">
        <table id="dataTable" class="admin-datatable">
            <thead>
                <tr>
                    <th style="width:50px">No</th>
                    <th>Nama Kelompok</th>
                    <th class="text-right" style="width:100px">Opsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kelompokBelajars as $kelompok)
                <tr>
                    <td><span class="cell-no">{{ $loop->iteration }}</span></td>
                    <td>
                        <span class="cell-label">{{ $kelompok->nama_kelompok }}</span>
                    </td>
                    <td style="text-align:right">
                        <div class="action-group">
                            <button type="button" onclick="editKelompok(this)"
                                data-id="{{ $kelompok->id }}"
                                data-nama="{{ $kelompok->nama_kelompok }}"
                                class="btn-icon btn-icon-edit" title="Edit">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            @include('admin.kelompok_belajar.delete')
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@include('admin.kelompok_belajar.create')
@include('admin.kelompok_belajar.edit')
@include('admin.kelompok_belajar.script')

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