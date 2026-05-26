<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran - {{ $transaksiPembayaran->no_kwitansi }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; font-size: 14px; margin: 0; padding: 20px; background-color: #f4f4f4; display: flex; justify-content: center; }
        .receipt-container { width: 400px; background: #fff; padding: 30px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 20px; }
        .header p { margin: 5px 0; font-size: 12px; color: #555; }
        .divider { border-bottom: 1px dashed #333; margin: 15px 0; }
        .row { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .row .label { color: #555; }
        .row .value { font-weight: bold; text-align: right; }
        .total-row { font-size: 18px; font-weight: bold; margin-top: 15px; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #777; }
        .btn-print { display: block; width: 100%; padding: 12px; background: #4f46e5; color: white; border: none; font-size: 16px; font-weight: bold; cursor: pointer; margin-top: 20px; border-radius: 6px; }
        .btn-back { display: block; width: 100%; padding: 12px; background: #e5e7eb; color: #374151; border: none; font-size: 16px; font-weight: bold; cursor: pointer; margin-top: 10px; border-radius: 6px; text-decoration: none; text-align: center; box-sizing: border-box; }
        @media print {
            body { background-color: #fff; padding: 0; }
            .receipt-container { box-shadow: none; width: 100%; padding: 0; margin: 0; }
            .btn-print, .btn-back { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="receipt-container">
        <div class="header">
            <h2>Kwitansi Pembayaran</h2>
            <p>Bimbingan Belajar Genius Education</p>
        </div>
        
        <div class="divider"></div>
        
        <div class="row">
            <span class="label">No. Kwitansi:</span>
            <span class="value">{{ $transaksiPembayaran->no_kwitansi }}</span>
        </div>
        <div class="row">
            <span class="label">Tanggal:</span>
            <span class="value">{{ \Carbon\Carbon::parse($transaksiPembayaran->tanggal)->format('d M Y') }}</span>
        </div>
        <div class="row">
            <span class="label">Siswa:</span>
            <span class="value">{{ $transaksiPembayaran->pembayaranSiswa->pesertaDidik->nama_lengkap ?? '-' }}</span>
        </div>
        <div class="row">
            <span class="label">Penerima:</span>
            <span class="value">{{ $transaksiPembayaran->penerima }}</span>
        </div>
        <div class="row">
            <span class="label">Metode:</span>
            <span class="value">{{ $transaksiPembayaran->tipe_pembayaran }}</span>
        </div>
        
        <div class="divider"></div>
        
        <div class="row total-row">
            <span class="label">TOTAL BAYAR:</span>
            <span class="value">Rp {{ number_format($transaksiPembayaran->nominal, 0, ',', '.') }}</span>
        </div>
        
        <div class="divider"></div>
        
        <div class="footer">
            <p>Terima kasih atas pembayaran Anda.</p>
            <p>Simpan struk ini sebagai bukti pembayaran yang sah.</p>
        </div>

        <button class="btn-print" onclick="window.print()">Cetak Struk</button>
        <a href="{{ route('keuangan.pembayaran.show', $transaksiPembayaran->pembayaranSiswa->peserta_didik_id) }}" class="btn-back">Kembali ke Riwayat</a>
    </div>
</body>
</html>
