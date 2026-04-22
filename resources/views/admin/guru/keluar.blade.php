@extends('layouts.admin')

@section('title', 'Guru Keluar')

@section('content')
<div class="admin-table-card">
    {{-- Header --}}
    <div class="admin-table-header">
        <div>
            <div class="admin-table-title">Daftar Guru Keluar</div>
            <div class="admin-table-subtitle">Kelola data guru yang sudah tidak aktif mengajar.</div>
        </div>
        <a href="{{ route('guru.keluar.export') }}" class="btn-add" style="background:linear-gradient(135deg,#dc2626,#b91c1c);box-shadow:0 4px 12px rgba(220,38,38,.35);">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Download
        </a>
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
                    <th>Tanggal Keluar</th>
                    <th>Alasan Keluar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gurus as $guru)
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
                    <td>
                        <span class="badge" style="background:#fef2f2;color:#be185d;border:1px solid #fbcfe8;">
                            <span class="badge-dot" style="background:#be185d"></span>
                            {{ $guru->tanggal_keluar ? \Carbon\Carbon::parse($guru->tanggal_keluar)->format('d-m-Y') : '-' }}
                        </span>
                    </td>
                    <td>{{ $guru->alasan_keluar ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-8 text-slate-400">Belum ada data guru keluar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection