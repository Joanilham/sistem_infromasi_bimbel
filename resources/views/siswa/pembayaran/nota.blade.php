<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran - {{ $transaksi->no_kwitansi }}</title>
    @php
        $master = \App\Models\MasterData\Master::first();
        $logoUrl = $master && $master->logo ? asset('storage/' . $master->logo) : null;
        $namaLembaga = $master->nama_lembaga ?? 'Bimbingan Belajar Genius Education';
        $alamatLembaga = $master->alamat_lembaga ?? 'Jl. Pendidikan No. 1, Kota Belajar';
        $waNumber = $master->wa_number ?? '';
    @endphp
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
            display: flex; 
            justify-content: center; 
            color: var(--text-main);
            -webkit-font-smoothing: antialiased;
        }
        .receipt-container { 
            width: 100%; 
            max-width: 480px; 
            background: #fff; 
            padding: 40px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.05); 
            border-radius: 16px;
            position: relative;
            overflow: hidden;
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
            text-align: center; 
            margin-bottom: 30px; 
        }
        .header img {
            max-height: 70px;
            margin-bottom: 15px;
            object-fit: contain;
        }
        .header h2 { 
            margin: 0 0 5px 0; 
            font-size: 22px; 
            font-weight: 800;
            letter-spacing: -0.5px;
            text-transform: uppercase;
        }
        .header p { 
            margin: 0; 
            font-size: 13px; 
            color: var(--text-muted); 
            line-height: 1.5;
        }
        
        .status-badge {
            display: inline-block;
            background: #dcfce7;
            color: #166534;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            margin-top: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 1px solid #bbf7d0;
        }

        .divider { 
            border-bottom: 2px dashed var(--border-color); 
            margin: 25px 0; 
        }
        
        .section-title {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
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
        }
        .total-row .label { 
            font-size: 14px; 
            font-weight: 700; 
            color: var(--text-main);
        }
        .total-row .value { 
            font-size: 24px; 
            font-weight: 800; 
            color: var(--primary);
        }
        
        .footer { 
            text-align: center; 
            margin-top: 35px; 
            font-size: 13px; 
            color: var(--text-muted); 
            line-height: 1.6;
        }
        .footer strong {
            color: var(--text-main);
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        .btn-print { 
            flex: 1;
            padding: 14px; 
            background: var(--primary); 
            color: white; 
            border: none; 
            font-size: 14px; 
            font-weight: 700; 
            cursor: pointer; 
            border-radius: 10px; 
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-print:hover {
            background: #4338ca;
            transform: translateY(-1px);
        }
        .btn-back { 
            flex: 1;
            padding: 14px; 
            background: white; 
            color: var(--text-main); 
            border: 1px solid var(--border-color); 
            font-size: 14px; 
            font-weight: 700; 
            cursor: pointer; 
            border-radius: 10px; 
            text-decoration: none; 
            text-align: center; 
            transition: all 0.2s;
        }
        .btn-back:hover {
            background: var(--bg-body);
        }
        
        /* Print Styles Optimized for Thermal Printers & A4 */
        @media print {
            @page {
                margin: 0; 
            }
            body { 
                background-color: #fff; 
                padding: 10px; 
                color: #000; 
            }
            .receipt-container { 
                box-shadow: none; 
                border: none;
                width: 100%; 
                max-width: 80mm; 
                padding: 0; 
                margin: 0 auto; 
            }
            .receipt-container::before {
                display: none;
            }
            .action-buttons { 
                display: none; 
            }
            .total-box {
                background: none;
                border-top: 1px dashed #000;
                border-bottom: 1px dashed #000;
                border-radius: 0;
                padding: 15px 0;
                margin-top: 15px;
            }
            .header h2, .total-row .value, .footer strong {
                color: #000 !important;
            }
            .header p, .row .label, .section-title, .footer {
                color: #000 !important;
            }
            .status-badge {
                border: 1px solid #000;
                background: #fff;
                color: #000;
            }
            .divider {
                border-bottom: 1px dashed #000;
            }
            .total-row .label, .total-row .value {
                color: #000 !important;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="header">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="Logo {{ $namaLembaga }}">
            @else
                <div style="font-size: 28px; font-weight: 900; color: var(--primary); margin-bottom: 5px; letter-spacing: -1px;">
                    {{ substr($namaLembaga, 0, 1) }}
                </div>
            @endif
            <h2>{{ $namaLembaga }}</h2>
            <p>{{ $alamatLembaga }}</p>
            @if($waNumber)
                <p>WA: {{ $waNumber }}</p>
            @endif
            
            <div style="margin-top: 15px;">
                @if($transaksi->status == 'SUKSES')
                    <div class="status-badge" style="background: #dcfce7; color: #166534; border: 1px solid #bbf7d0;">
                        LUNAS / BERHASIL
                    </div>
                @elseif($transaksi->status == 'PENDING')
                    <div class="status-badge" style="background: #fef08a; color: #854d0e; border: 1px solid #fde047;">
                        MENUNGGU VERIFIKASI
                    </div>
                @else
                    <div class="status-badge" style="background: #fee2e2; color: #991b1b; border: 1px solid #fecaca;">
                        DITOLAK / BATAL
                    </div>
                @endif
            </div>
        </div>
        
        <div class="section-title">Detail Transaksi</div>
        
        <div class="row">
            <span class="label">No. Kwitansi</span>
            <span class="value">{{ $transaksi->no_kwitansi }}</span>
        </div>
        <div class="row">
            <span class="label">Tanggal Bayar</span>
            <span class="value">{{ \Carbon\Carbon::parse($transaksi->tanggal)->translatedFormat('d F Y') }}</span>
        </div>
        <div class="row">
            <span class="label">Metode Pembayaran</span>
            <span class="value">{{ $transaksi->tipe_pembayaran }}</span>
        </div>
        <div class="row">
            <span class="label">Penerima</span>
            <span class="value">{{ $transaksi->penerima }}</span>
        </div>
        
        <div class="divider"></div>
        
        <div class="section-title">Informasi Siswa</div>
        
        <div class="row">
            <span class="label">Nama Siswa</span>
            <span class="value">{{ $transaksi->pembayaranSiswa->pesertaDidik->nama_lengkap ?? '-' }}</span>
        </div>
        <div class="row">
            <span class="label">Paket Bimbingan</span>
            <span class="value">{{ $transaksi->pembayaranSiswa->pesertaDidik->paketBimbingan->nama_paket ?? '-' }}</span>
        </div>
        
        <div class="total-box">
            <div class="total-row">
                <span class="label">TOTAL BAYAR</span>
                <span class="value">Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}</span>
            </div>
            
            @if($transaksi->pembayaranSiswa && $transaksi->pembayaranSiswa->kekurangan > 0)
                <div style="text-align: right; margin-top: 8px; font-size: 12px; color: #ef4444; font-weight: 600;">
                    Sisa Tagihan: Rp {{ number_format($transaksi->pembayaranSiswa->kekurangan, 0, ',', '.') }}
                </div>
            @elseif($transaksi->pembayaranSiswa && $transaksi->pembayaranSiswa->kekurangan <= 0)
                <div style="text-align: right; margin-top: 8px; font-size: 12px; color: #10b981; font-weight: 700;">
                    LUNAS
                </div>
            @endif
        </div>
        
        <div class="footer">
            <p>Terima kasih atas kepercayaan Anda.</p>
            <p>Simpan struk ini sebagai bukti pembayaran yang sah dari <strong>{{ $namaLembaga }}</strong>.</p>
        </div>

        <div class="action-buttons">
            @php
                $backUrl = route('siswa.pembayaran.index');
            @endphp
            <button onclick="if(window.history.length > 1 && !window.opener) { window.location.href = '{{ $backUrl }}'; } else { window.close(); window.location.href = '{{ $backUrl }}'; }" class="btn-back">Kembali / Tutup</button>
            <button class="btn-print" onclick="window.print()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                Cetak Struk
            </button>
        </div>
    </div>
</body>
</html>
