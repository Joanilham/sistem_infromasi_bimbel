@extends('errors.layout-card')

@section('page_title', 'Tidak Ada Koneksi Internet')

@section('code-color', '#2563EB')
@section('badge-bg', '#EFF6FF')
@section('badge-text', '#1E3A8A')
@section('badge-dot', '#3B82F6')

@section('icon')
    📡
@endsection

@section('code')
    <div style="height: 80px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
        <div style="width: 80px; height: 12px; background: #3B82F6; border-radius: 99px; animation: pulse 1.5s infinite;"></div>
    </div>
@endsection

@section('badge', 'Tidak Ada Koneksi')

@section('title', 'Ups, Koneksi Terputus')

@section('message')
    Sepertinya perangkat Anda kehilangan sinyal internet. Pastikan jaringan Wi-Fi atau kuota data Anda aktif, lalu tekan tombol di bawah ya.
@endsection

@section('actions')
    <a href="javascript:location.reload()" class="btn btn-outline" style="color: #1a1a1a;">
        Muat Ulang
    </a>
@endsection

@section('details')
    <div class="details-row">
        <span class="details-label">Status</span>
        <span class="details-value">Offline</span>
    </div>
    <div class="details-row">
        <span class="details-label">Terakhir online</span>
        <span class="details-value" id="last-online">--.--.--</span>
    </div>

    <script>
        function updateOfflineTime() {
            const now = new Date();
            const timeStr = now.getHours().toString().padStart(2, '0') + '.' + 
                            now.getMinutes().toString().padStart(2, '0') + '.' + 
                            now.getSeconds().toString().padStart(2, '0');
            document.getElementById('last-online').textContent = timeStr;
        }
        updateOfflineTime();
    </script>
@endsection

@section('extra-styles')
<style>
    @keyframes pulse {
        0%, 100% { opacity: 1; width: 80px; }
        50% { opacity: 0.5; width: 40px; }
    }
    @media (prefers-color-scheme: dark) {
        :root {
            --bg-badge: rgba(59, 130, 246, 0.1);
            --text-badge: #93C5FD;
        }
    }
</style>
@endsection
