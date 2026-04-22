@extends('layouts.admin')

@section('title', 'Guru Aktif')

@section('content')
<div class="admin-table-card">
    {{-- Header --}}
    <div class="admin-table-header">
        <div>
            <div class="admin-table-title">Daftar Guru Aktif</div>
            <div class="admin-table-subtitle">Kelola data guru yang sedang aktif mengajar.</div>
        </div>
        <div style="display:flex;gap:.6rem;align-items:center;">
            <a href="{{ route('guru.create') }}" class="btn-add">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                Tambah
            </a>
            <a href="{{ route('guru.export') }}" class="btn-add" style="background:linear-gradient(135deg,#059669,#047857);box-shadow:0 4px 12px rgba(5,150,105,.35);">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Download
            </a>
        </div>
    </div>

    {{-- Table --}}
    <div class="admin-table-body">
        <table id="dataTable" class="admin-datatable">
            <thead>
                <tr>
                    <th style="width:50px">No</th>
                    <th>Nama Lengkap</th>
                    <th>Alamat</th>
                    <th>NIP</th>
                    <th>Mata Pelajaran</th>
                    <th>No. Telp</th>
                    <th>Email</th>
                    <th class="text-right" style="width:100px">Opsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($gurus as $guru)
                <tr>
                    <td><span class="cell-no">{{ $loop->iteration }}</span></td>
                    <td>
                        <span class="cell-label">{{ $guru->name }}</span>
                    </td>
                    <td>{{ $guru->alamat ?? '-' }}</td>
                    <td>{{ $guru->nip ?? '-' }}</td>
                    <td>
                        <span class="badge badge-primary">
                            <span class="badge-dot" style="background:#7c3aed"></span>
                            {{ $guru->matapelajaran }}
                        </span>
                    </td>
                    <td>{{ $guru->no_telp ?? '-' }}</td>
                    <td>
                        <div class="cell-sub">{{ $guru->email }}</div>
                    </td>
                    <td style="text-align:right">
                        <div class="action-group">
                            <a href="{{ route('guru.edit', $guru->id) }}"
                                class="btn-icon btn-icon-edit" title="Edit">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection