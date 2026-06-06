@extends('errors.layout-card')

@section('page_title', '500 — Server Error')

@section('code-color', '#DC2626')
@section('badge-bg', 'var(--bg-badge)')
@section('badge-text', '#991B1B')
@section('badge-dot', '#DC2626')

@section('icon')
    ⚠️
@endsection

@section('code', '500')

@section('badge', 'Server Error')

@section('title', 'Sistem Sedang Gangguan')

@section('message')
    Aduh, sepertinya sistem kami sedang sedikit pusing. Tim teknisi kami sudah mengetahui hal ini dan sedang memperbaikinya. Mohon kembali lagi nanti ya.
@endsection

@section('actions')
    <a href="javascript:location.reload()" class="btn btn-outline">
        Mencoba...
    </a>
    <a href="javascript:void(0)" onclick="alert('Laporan terkirim! Terima kasih.')" class="btn btn-outline">
        Laporkan
    </a>
@endsection

@section('details')
    <div class="details-row">
        <span class="details-label">Kode Error</span>
        <span class="details-value">HTTP 500</span>
    </div>
    <div class="details-row">
        <span class="details-label">Waktu</span>
        <span class="details-value" id="current-time">--.--.--</span>
    </div>
    <div class="details-row">
        <span class="details-label">Request ID</span>
        <span class="details-value">req_{{ Str::random(8) }}</span>
    </div>

    <script>
        // Update time dynamically
        function updateTime() {
            const now = new Date();
            const timeStr = now.getHours().toString().padStart(2, '0') + '.' + 
                            now.getMinutes().toString().padStart(2, '0') + '.' + 
                            now.getSeconds().toString().padStart(2, '0');
            document.getElementById('current-time').textContent = timeStr;
        }
        updateTime();
        setInterval(updateTime, 1000);
    </script>
@endsection

