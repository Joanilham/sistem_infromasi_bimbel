@extends('layouts.admin')
@section('title', 'Rekap Absensi')

@push('head')
<style>
    .custom-input { width: 100%; border: 1px solid #e2e8f0; border-radius: 12px; padding: 0.75rem 1rem; font-size: 0.875rem; background: #f8fafc; color: #1e293b; outline: none; transition: all 0.2s; }
    .custom-input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); }
    .dark .custom-input { background: #1e293b; border-color: #334155; color: #f8fafc; }
    .dark .custom-input:focus { border-color: #6366f1; }
</style>
@endpush

@section('content')

<div class="admin-table-card" style="margin-bottom: 1.5rem;">
    <div class="admin-table-header">
        <div>
            <div class="admin-table-title">Rekap Absensi Bulanan</div>
            <div class="admin-table-subtitle">Rekapitulasi kehadiran siswa per bulan.</div>
        </div>
        <div style="display:flex;gap:.6rem;align-items:center;flex-wrap:wrap;">
            <a href="{{ route('absensi.export.rekap', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn-add" style="background:linear-gradient(135deg,#6366f1,#4f46e5);box-shadow:0 4px 12px rgba(99,102,241,.35);">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Export Excel
            </a>
            <a href="{{ route('absensi.scan.masuk.page') }}" class="btn-add" style="background:linear-gradient(135deg,#10b981,#059669);box-shadow:0 4px 12px rgba(16,185,129,.35);">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Absen Masuk
            </a>
            <a href="{{ route('absensi.scan.pulang.page') }}" class="btn-add" style="background:linear-gradient(135deg,#d97706,#b45309);box-shadow:0 4px 12px rgba(217,119,6,.35);">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Absen Pulang
            </a>
        </div>
    </div>
</div>

<div class="admin-table-card">
    <div class="admin-table-header" style="flex-direction: column; align-items: stretch; gap: 1rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 1rem;">
        <div style="display:flex; align-items:center; gap: 0.75rem; flex-wrap: wrap;">
            <h3 style="font-size:0.875rem; font-weight:700; color:#334155;">
                Rekap Bulan {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}
            </h3>
            <span class="badge" style="background:#f1f5f9; color:#475569; font-weight:700;">{{ $pesertaDidiks->count() }} siswa</span>
        </div>
        
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap: wrap; gap: 1rem;">
            <form method="GET" style="display:flex; gap:0.5rem; align-items:center; flex-wrap: wrap;">
                <select name="bulan" class="custom-input" style="padding: 0.4rem 0.75rem; width: auto; min-width: 140px;">
                    @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}" {{ request('bulan', date('m')) == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                    @endforeach
                </select>
                <select name="tahun" class="custom-input" style="padding: 0.4rem 0.75rem; width: auto; min-width: 100px;">
                    @php $startYear = date('Y') - 2; $endYear = date('Y') + 1; @endphp
                    @for($y = $startYear; $y <= $endYear; $y++)
                    <option value="{{ $y }}" {{ request('tahun', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                <button type="submit" class="btn-add" style="background:#6366f1; padding: 0.4rem 1rem;">Tampilkan</button>
            </form>

            <div style="position:relative; width:280px; max-width: 100%;">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); width:1rem; height:1rem; color:#94a3b8;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="search-rekap" placeholder="Cari nama / NISN..." class="custom-input" style="padding-left: 2.25rem; width: 100%;">
            </div>
        </div>
    </div>

    <div class="admin-table-body">
        <table class="admin-datatable" style="width: 100%;">
            <thead>
                <tr>
                    <th style="width:50px">No</th>
                    <th>Nama Lengkap</th>
                    <th>NISN</th>
                    <th style="text-align:center">Hadir</th>
                    <th style="text-align:center">Izin</th>
                    <th style="text-align:center">Sakit</th>
                    <th style="text-align:center">Alpha</th>
                    <th style="text-align:center">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesertaDidiks as $p)
                <tr class="rekap-row" data-nama="{{ strtolower($p->nama_lengkap) }}" data-nisn="{{ $p->nisn }}">
                    <td><span class="cell-no row-no">{{ $loop->iteration }}</span></td>
                    <td><span class="cell-label">{{ $p->nama_lengkap }}</span></td>
                    <td><span style="font-family:monospace;font-size:.8rem;color:#6366f1;font-weight:600;">{{ $p->nisn }}</span></td>
                    <td style="text-align:center"><span class="badge badge-success">{{ $p->total_hadir }}</span></td>
                    <td style="text-align:center"><span class="badge" style="background:#e0f2fe;color:#0369a1;">{{ $p->total_izin }}</span></td>
                    <td style="text-align:center"><span class="badge" style="background:#fef9c3;color:#854d0e;">{{ $p->total_sakit }}</span></td>
                    <td style="text-align:center"><span class="badge" style="background:#fee2e2;color:#dc2626;">{{ $p->total_alpha }}</span></td>
                    <td style="text-align:center">
                        @php $total = $p->total_hadir + $p->total_izin + $p->total_sakit + $p->total_alpha; @endphp
                        <span style="font-weight:700; color:#334155;">{{ $total }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:3rem 1rem;color:#94a3b8;">Tidak ada data siswa.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div id="empty-rekap" class="hidden" style="text-align:center;padding:3rem 1rem;color:#94a3b8;font-size:0.875rem;">Tidak ada siswa yang cocok dengan pencarian.</div>
    </div>
</div>

<script>
document.getElementById('search-rekap').addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    const rows = document.querySelectorAll('.rekap-row');
    let visible = 0;
    rows.forEach(row => {
        const nama = row.dataset.nama || '';
        const nisn = row.dataset.nisn || '';
        const match = !q || nama.includes(q) || nisn.includes(q);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    document.getElementById('empty-rekap').classList.toggle('hidden', visible > 0 || !q);
    let no = 1;
    rows.forEach(row => {
        if (row.style.display !== 'none') {
            const noCell = row.querySelector('.row-no');
            if (noCell) noCell.textContent = no++;
        }
    });
});
</script>
@endsection
