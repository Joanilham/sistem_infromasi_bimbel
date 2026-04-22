@extends('layouts.admin')

@section('title', 'Daftar Peserta Didik Keluar')

@section('content')
<div class="admin-table-card">
    {{-- Header --}}
    <div class="admin-table-header">
        <div>
            <div class="admin-table-title">Daftar Peserta Didik Keluar</div>
            <div class="admin-table-subtitle">Data peserta didik yang telah keluar dari bimbingan.</div>
        </div>
        <div style="display:flex;gap:.6rem;align-items:center;">
            <a href="{{ route('peserta-didik.keluar.export') }}" class="btn-add" style="background:linear-gradient(135deg,#059669,#047857);box-shadow:0 4px 12px rgba(5,150,105,.35);">
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
                    <th>Tanggal Keluar</th>
                    <th>Keterangan</th>
                    <th class="text-right" style="width:130px">Opsi</th>
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
                    <td><span style="font-family:monospace;font-size:.8rem;color:#6366f1;font-weight:600;">{{ $peserta->nisn ?? '-' }}</span></td>
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
                        <span class="badge badge-secondary">{{ optional($peserta->kelompokBelajar)->nama_kelompok ?? '-' }}</span>
                    </td>
                    <td>
                        @if($peserta->tanggal_keluar)
                        <span style="font-size:.8rem;font-weight:600;color:#dc2626;">
                            {{ \Carbon\Carbon::parse($peserta->tanggal_keluar)->format('d/m/Y') }}
                        </span>
                        @else
                        <span class="cell-sub">-</span>
                        @endif
                    </td>
                    <td style="max-width:200px;">
                        <span style="font-size:.78rem;color:#64748b;display:-webkit-box;-webkit-line-clamp:2;line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                            {{ $peserta->alasan_keluar ?? '-' }}
                        </span>
                    </td>
                    <td style="text-align:right">
                        <div class="action-group">
                            {{-- Batalkan (kembalikan ke Aktif) --}}
                            <form action="{{ route('peserta-didik.update', $peserta->id) }}" method="POST" style="margin:0;"
                                onsubmit="return confirm('Kembalikan peserta ini ke status Aktif?')">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="nama_lengkap" value="{{ $peserta->nama_lengkap }}">
                                <input type="hidden" name="nomor_induk" value="{{ $peserta->nomor_induk }}">
                                <input type="hidden" name="jenis_kelamin" value="{{ $peserta->jenis_kelamin }}">
                                <input type="hidden" name="asal_sekolah" value="{{ $peserta->asal_sekolah }}">
                                <input type="hidden" name="paket_bimbingan_id" value="{{ $peserta->paket_bimbingan_id }}">
                                <input type="hidden" name="kelompok_belajar" value="{{ $peserta->kelompok_belajar }}">
                                <input type="hidden" name="status" value="Aktif">
                                <button type="submit"
                                    class="btn-icon"
                                    style="background:#ecfdf5;color:#059669;border-color:#a7f3d0;width:auto;padding:0 .6rem;font-size:.72rem;font-weight:700;gap:.3rem;"
                                    title="Aktifkan kembali">
                                    <svg style="width:.8rem;height:.8rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Aktifkan
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
            order: [
                [7, 'desc']
            ], // default sort: tanggal keluar terbaru
        });
    });
</script>
@endsection