<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pembayaran - {{ $transaksi->no_kwitansi }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .nota-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo-section h1 {
            margin: 0;
            color: #2c3e50;
            font-size: 28px;
        }
        .logo-section p {
            margin: 5px 0 0;
            color: #7f8c8d;
            font-size: 14px;
        }
        .nota-title {
            text-align: right;
        }
        .nota-title h2 {
            margin: 0;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .nota-title p {
            margin: 5px 0 0;
            font-weight: bold;
            color: #e74c3c;
        }
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .info-box {
            width: 45%;
        }
        .info-box h3 {
            font-size: 12px;
            color: #7f8c8d;
            text-transform: uppercase;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .info-box p {
            margin: 5px 0;
            font-size: 14px;
        }
        .table-container {
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background-color: #f8f9fa;
            color: #2c3e50;
            font-weight: bold;
            font-size: 13px;
            text-transform: uppercase;
        }
        td {
            font-size: 14px;
        }
        .amount-col {
            text-align: right;
        }
        .total-row td {
            font-weight: bold;
            font-size: 16px;
            border-top: 2px solid #2c3e50;
            border-bottom: none;
        }
        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .notes {
            width: 60%;
            font-size: 13px;
            color: #7f8c8d;
            font-style: italic;
        }
        .signature {
            width: 30%;
            text-align: center;
        }
        .signature p {
            margin: 0 0 60px;
            font-size: 14px;
        }
        .signature strong {
            border-top: 1px solid #333;
            padding-top: 5px;
            display: block;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            background-color: #27ae60;
            color: white;
            margin-top: 10px;
        }
        .status-pending { background-color: #f39c12; }
        .status-batal { background-color: #e74c3c; }
        
        .print-btn {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-align: center;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
            text-decoration: none;
        }
        .print-btn:hover {
            background-color: #2980b9;
        }
        
        @media print {
            body {
                background-color: #fff;
                padding: 0;
            }
            .nota-container {
                box-shadow: none;
                padding: 0;
            }
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    @php
        $master = \App\Models\Master::first();
        $peserta = $transaksi->pembayaranSiswa->pesertaDidik;
    @endphp

    <button onclick="window.print()" class="print-btn">🖨️ Cetak Nota</button>

    <div class="nota-container">
        <div class="header">
            <div class="logo-section">
                <h1>{{ $master->nama_lembaga ?? 'GeniusEdu' }}</h1>
                <p>{{ $master->alamat_lembaga ?? 'Alamat belum diatur' }}</p>
                <p>WA: {{ $master->wa_number ?? '-' }}</p>
            </div>
            <div class="nota-title">
                <h2>NOTA PEMBAYARAN</h2>
                <p>{{ $transaksi->no_kwitansi }}</p>
                
                @if($transaksi->status == 'SUKSES')
                    <span class="status-badge">LUNAS / BERHASIL</span>
                @elseif($transaksi->status == 'PENDING')
                    <span class="status-badge status-pending">MENUNGGU VERIFIKASI</span>
                @else
                    <span class="status-badge status-batal">DITOLAK / BATAL</span>
                @endif
            </div>
        </div>

        <div class="info-section">
            <div class="info-box">
                <h3>Terima Dari</h3>
                <p><strong>{{ $peserta->nama_lengkap }}</strong></p>
                <p>NISN: {{ $peserta->nisn ?? '-' }}</p>
                <p>Paket: {{ $peserta->paketBimbingan->nama_paket ?? '-' }}</p>
            </div>
            <div class="info-box" style="text-align: right;">
                <h3>Detail Transaksi</h3>
                <p>Tanggal: <strong>{{ \Carbon\Carbon::parse($transaksi->tanggal)->translatedFormat('d F Y') }}</strong></p>
                <p>Metode: <strong>{{ $transaksi->tipe_pembayaran }}</strong></p>
                <p>Penerima: <strong>{{ $transaksi->penerima }}</strong></p>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Deskripsi Pembayaran</th>
                        <th class="amount-col">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            Pembayaran {{ $transaksi->tipe_pembayaran }} 
                            @if($transaksi->catatan_siswa)
                                <br><small style="color: #7f8c8d;">Catatan: {{ $transaksi->catatan_siswa }}</small>
                            @endif
                        </td>
                        <td class="amount-col">Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="total-row">
                        <td style="text-align: right;">TOTAL:</td>
                        <td class="amount-col">Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="footer">
            <div class="notes">
                <p><strong>Catatan:</strong></p>
                <p>1. Simpan nota ini sebagai bukti pembayaran yang sah.</p>
                <p>2. Pembayaran yang sudah dilakukan tidak dapat ditarik kembali kecuali ada perjanjian tertulis.</p>
            </div>
            <div class="signature">
                <p>Tanda Terima,</p>
                <br>
                <strong>{{ $transaksi->penerima }}</strong>
            </div>
        </div>
    </div>
</body>
</html>
