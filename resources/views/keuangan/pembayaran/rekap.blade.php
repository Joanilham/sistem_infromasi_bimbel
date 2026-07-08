<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Pembayaran - {{ $pembayaranSiswa->pesertaDidik->nama_lengkap }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');
        
        :root {
            --primary: #4f46e5;
            --primary-light: #e0e7ff;
            --text-main: #1f2937;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
            --bg-body: #f3f4f6;
        }

        body { 
            font-family: 'Inter', sans-serif; 
            font-size: 14px; 
            margin: 0; 
            padding: 40px 20px; 
            background-color: var(--bg-body); 
            color: var(--text-main);
            -webkit-font-smoothing: antialiased;
        }
        .receipt-container { 
            width: 100%; 
            max-width: 800px; 
            background: #fff; 
            padding: 40px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.05); 
            border-radius: 16px;
            position: relative;
            overflow: hidden;
            margin: 0 auto;
        }
        .receipt-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #4f46e5, #ec4899);
        }
        
        .header { 
            display: flex;
            justify-content: flex-start;
            align-items: flex-start;
            margin-bottom: 15px; 
            text-align: left;
            gap: 20px;
        }
        .header-text {
            flex: 1;
        }
        .header-logo img {
            max-height: 60px;
            object-fit: contain;
        }
        .header h2 { 
            margin: 0 0 2px 0; 
            font-size: 20px; 
            font-weight: 800;
            letter-spacing: -0.5px;
            text-transform: uppercase;
        }
        .header p { 
            margin: 0; 
            font-size: 12px; 
            color: var(--text-muted); 
            line-height: 1.4;
        }
        
        .status-badge {
            display: inline-block;
            background: {{ $pembayaranSiswa->status == 'Lunas' ? '#dcfce7' : '#fee2e2' }};
            color: {{ $pembayaranSiswa->status == 'Lunas' ? '#166534' : '#991b1b' }};
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            margin-top: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .divider { 
            border-bottom: 1px dashed var(--border-color); 
            margin: 15px 0; 
        }
        
        .section-title {
            font-size: 11px;
            font-weight: 800;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            border-bottom: 2px solid var(--primary-light);
            padding-bottom: 5px;
        }

        .row { 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 12px; 
            align-items: center;
        }
        .row .label { 
            color: var(--text-muted); 
            font-size: 13px;
        }
        .row .value { 
            font-weight: 600; 
            text-align: right; 
            font-size: 14px;
        }
        
        .total-box {
            background: var(--bg-body);
            border-radius: 12px;
            padding: 20px;
            margin-top: 25px;
        }
        .total-row { 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            margin-bottom: 10px;
        }
        .total-row:last-child {
            margin-bottom: 0;
            padding-top: 10px;
            border-top: 1px dashed #ccc;
        }
        .total-row .label { 
            font-size: 14px; 
            font-weight: 700; 
            color: var(--text-main);
        }
        .total-row .value { 
            font-size: 18px; 
            font-weight: 800; 
            color: var(--primary);
        }
        
        .table-riwayat {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table-riwayat th, .table-riwayat td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
            font-size: 13px;
        }
        .table-riwayat th {
            font-weight: 700;
            color: var(--text-muted);
            background-color: var(--bg-body);
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 1px;
        }
        .table-riwayat td {
            font-weight: 600;
        }
        .table-riwayat .amount {
            text-align: right;
        }

        .footer { 
            text-align: center; 
            margin-top: 35px; 
            font-size: 13px; 
            color: var(--text-muted); 
            line-height: 1.6;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        .btn-print, .btn-back { 
            flex: 1;
            padding: 14px; 
            border-radius: 10px; 
            font-size: 14px; 
            font-weight: 700; 
            cursor: pointer; 
            transition: all 0.2s;
            text-align: center;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-print { 
            background: var(--primary); 
            color: white; 
            border: none; 
        }
        .btn-print:hover { background: #4338ca; }
        .btn-back { 
            background: white; 
            color: var(--text-main); 
            border: 1px solid var(--border-color); 
        }
        .btn-back:hover { background: var(--bg-body); }
        
        @page {
            size: A4 portrait;
            margin: 0;
        }
        @media print {
            body { 
                background-color: white; 
                padding: 0.5cm 1cm;
                margin: 0;
                font-size: 12px;
                color: #000;
            }
            .receipt-container { 
                box-shadow: none; 
                max-width: 100%;
                width: 100%;
                padding: 0;
                margin: 0;
                border: none;
            }
            .receipt-container::before {
                display: none;
            }
            .action-buttons { display: none; }
            
            /* Tidy up table and page breaks */
            .table-riwayat {
                page-break-inside: auto;
            }
            .table-riwayat tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            .table-riwayat thead {
                display: table-header-group;
            }
            .table-riwayat tfoot {
                display: table-footer-group;
            }
            
            /* Adjust colors for cleaner print */
            .section-title {
                color: #000;
                border-bottom: 2px solid #000;
            }
            .total-box {
                background: transparent;
                border: 1px solid #ccc;
                padding: 15px;
            }
            .total-row .value, .table-riwayat td.amount {
                color: #000 !important;
            }
            .table-riwayat th {
                background-color: transparent;
                color: #000;
                border-bottom: 2px solid #000;
            }
            .footer {
                page-break-inside: avoid;
                margin-top: 50px;
            }
        }
    </style>
</head>
<body>

    <div class="receipt-container">
        
        <div class="header">
            @if($logoUrl)
            <div class="header-logo">
                <img src="{{ $logoUrl }}" alt="Logo">
            </div>
            @endif
            <div class="header-text">
                <h2>{{ $namaLembaga }}</h2>
                @if($alamatLembaga)
                    <p>{{ $alamatLembaga }}</p>
                @endif
                @if($waNumber)
                    <p>WA: {{ $waNumber }}</p>
                @endif
                
                <div>
                    <span class="status-badge" style="{{ $pembayaranSiswa->lunas ? 'background: #dcfce7; color: #166534;' : 'background: #fee2e2; color: #991b1b;' }}">
                        {{ $pembayaranSiswa->lunas ? 'LUNAS' : 'BELUM LUNAS' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <div class="section-title">Informasi Siswa</div>
        <div class="row">
            <span class="label">Nama Siswa</span>
            <span class="value">{{ $pembayaranSiswa->pesertaDidik->nama_lengkap }}</span>
        </div>
        <div class="row">
            <span class="label">NISN</span>
            <span class="value">{{ $pembayaranSiswa->pesertaDidik->nisn ?? '-' }}</span>
        </div>
        <div class="row">
            <span class="label">Program/Kelas</span>
            <span class="value">{{ $pembayaranSiswa->pesertaDidik->paketBimbingan->nama_paket ?? '-' }}</span>
        </div>
        
        <div class="divider"></div>

        <div class="section-title">Rekap Keuangan</div>
        @php
            $biayaPaket = $pembayaranSiswa->pesertaDidik->paketBimbingan->nominal ?? 0;
            $pendaftaran = $pembayaranSiswa->biaya_pendaftaran ?? 0;
        @endphp
        <div class="row">
            <span class="label">Tagihan Program/Bimbingan</span>
            <span class="value">Rp {{ number_format($biayaPaket, 0, ',', '.') }}</span>
        </div>
        @if($pendaftaran > 0)
        <div class="row">
            <span class="label">Biaya Pendaftaran</span>
            <span class="value">Rp {{ number_format($pendaftaran, 0, ',', '.') }}</span>
        </div>
        @else
        <div class="row">
            <span class="label">Biaya Pendaftaran</span>
            <span class="value">Rp 0</span>
        </div>
        @endif
        @if($pembayaranSiswa->diskon_nominal > 0)
        <div class="row">
            <span class="label">Diskon ({{ $pembayaranSiswa->keterangan_diskon ?? 'Potongan' }})</span>
            <span class="value" style="color: #ea580c;">- Rp {{ number_format($pembayaranSiswa->diskon_nominal, 0, ',', '.') }}</span>
        </div>
        @endif
        @if($pembayaranSiswa->jumlah_cicilan > 1)
        <div class="row">
            <span class="label">Jatuh Tempo Berikutnya</span>
            <span class="value" style="color: #ea580c;">{{ $pembayaranSiswa->jatuh_tempo_berikutnya ? \Carbon\Carbon::parse($pembayaranSiswa->jatuh_tempo_berikutnya)->translatedFormat('d F Y') : '-' }}</span>
        </div>
        @endif
        
        <div class="total-box">
            <div class="total-row">
                <span class="label">Total Biaya Akhir</span>
                <span class="value" style="color: #1f2937;">Rp {{ number_format($pembayaranSiswa->total_harus_dibayar, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span class="label">Total Sudah Dibayar</span>
                <span class="value" style="color: #16a34a;">Rp {{ number_format($pembayaranSiswa->total_terbayar, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span class="label">Sisa Tagihan</span>
                <span class="value" style="color: #dc2626;">Rp {{ number_format($pembayaranSiswa->kekurangan, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="divider"></div>

        <div class="section-title">Riwayat Pembayaran</div>
        @if($pembayaranSiswa->transaksi->count() > 0)
            <table class="table-riwayat">
                <thead>
                    <tr>
                        <th>No Kwitansi</th>
                        <th>Tanggal</th>
                        <th>Metode</th>
                        <th class="amount">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pembayaranSiswa->transaksi as $transaksi)
                        @if($transaksi->status == 'SUKSES')
                        <tr>
                            <td>{{ $transaksi->no_kwitansi }}</td>
                            <td>{{ \Carbon\Carbon::parse($transaksi->tanggal)->translatedFormat('d M Y') }}</td>
                            <td>{{ $transaksi->metode_pembayaran }}</td>
                            <td class="amount text-emerald-600">Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align:center; color:var(--text-muted); margin-top:20px; font-style:italic;">Belum ada riwayat pembayaran yang valid.</p>
        @endif

        <div class="footer">
            Dicetak pada: <strong>{{ now()->translatedFormat('d F Y H:i') }}</strong><br>
            Oleh: <strong>{{ auth()->user()->name }}</strong>
        </div>

        <div class="action-buttons">
            <a href="{{ route('keuangan.pembayaran.show', $pembayaranSiswa->peserta_didik_id) }}" class="btn-back">
                Kembali
            </a>
            <button onclick="window.print()" class="btn-print">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Cetak Rekap
            </button>
        </div>

    </div>

</body>
</html>
