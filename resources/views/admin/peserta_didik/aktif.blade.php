@extends('layouts.admin')

@section('title', 'Peserta Didik Aktif')

@section('content')
<div class="admin-table-card">
    {{-- Header --}}
    <div class="admin-table-header">
        <div>
            <div class="admin-table-title">Daftar Peserta Didik Aktif</div>
            <div class="admin-table-subtitle">Kelola data peserta didik yang sedang aktif mengikuti bimbingan.</div>
        </div>
        <div style="display:flex;gap:.6rem;align-items:center;">
            <a href="{{ route('peserta-didik.create') }}" class="btn-add">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                Tambah
            </a>
            <a href="{{ route('peserta-didik.export') }}" class="btn-add" style="background:linear-gradient(135deg,#059669,#047857);box-shadow:0 4px 12px rgba(5,150,105,.35);">
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
                    <th>No. Induk</th>
                    <th>L/P</th>
                    <th>Asal Sekolah</th>
                    <th>Paket Bimbel</th>
                    <th>Kelompok Belajar</th>
                    <th class="text-right" style="width:100px">Opsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pesertaDidiks as $peserta)
                <tr>
                    <td><span class="cell-no">{{ $loop->iteration }}</span></td>
                    <td>
                        <span class="cell-label">{{ $peserta->nama_lengkap }}</span>
                        @if($peserta->no_telepon)
                        <div class="cell-sub">{{ $peserta->no_telepon }}</div>
                        @endif
                    </td>
                    <td><span style="font-family:monospace;font-size:.8rem;color:#6366f1;font-weight:600;">{{ $peserta->nomor_induk }}</span></td>
                    <td>
                        @if($peserta->jenis_kelamin === 'L')
                        <span class="badge badge-primary"><span class="badge-dot" style="background:#7c3aed"></span>Laki-laki</span>
                        @else
                        <span class="badge" style="background:#fdf2f8;color:#be185d;border:1px solid #fbcfe8;"><span class="badge-dot" style="background:#be185d"></span>Perempuan</span>
                        @endif
                    </td>
                    <td>{{ $peserta->asal_sekolah }}</td>
                    <td>
                        @if($peserta->paketBimbingan)
                        <span class="badge badge-emerald">{{ $peserta->paketBimbingan->nama_paket }}</span>
                        @else
                        <span class="cell-sub">-</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-secondary">{{ $peserta->kelompok_belajar }}</span>
                    </td>
                    <td style="text-align:right">
                        <div class="action-group">
                            <button type="button" onclick="editPeserta(this)"
                                data-id="{{ $peserta->id }}"
                                data-nama="{{ $peserta->nama_lengkap }}"
                                data-nomor="{{ $peserta->nomor_induk }}"
                                data-jk="{{ $peserta->jenis_kelamin }}"
                                data-tempat="{{ $peserta->tempat_lahir }}"
                                data-tgl="{{ $peserta->tanggal_lahir }}"
                                data-agama="{{ $peserta->agama }}"
                                data-alamat="{{ $peserta->alamat_lengkap }}"
                                data-sekolah="{{ $peserta->asal_sekolah }}"
                                data-telp="{{ $peserta->no_telepon }}"
                                data-ayah="{{ $peserta->nama_ayah }}"
                                data-ibu="{{ $peserta->nama_ibu }}"
                                data-pkayah="{{ $peserta->pekerjaan_ayah }}"
                                data-pkibu="{{ $peserta->pekerjaan_ibu }}"
                                data-telpayah="{{ $peserta->no_telepon_ayah }}"
                                data-telpibu="{{ $peserta->no_telepon_ibu }}"
                                data-info="{{ $peserta->informasi_dari }}"
                                data-paket="{{ $peserta->paket_bimbingan_id }}"
                                data-kelompok="{{ $peserta->kelompok_belajar }}"
                                data-status="{{ $peserta->status }}"
                                class="btn-icon btn-icon-edit" title="Edit">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            @include('admin.peserta_didik.delete')
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@include('admin.peserta_didik.edit')
@include('admin.peserta_didik.script')

@section('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"lf>rt<"flex flex-col md:flex-row justify-between items-center mt-4"ip>',
            columnDefs: [{
                orderable: false,
                targets: -1
            }],
        });
    });
</script>
@endsection
@endsection