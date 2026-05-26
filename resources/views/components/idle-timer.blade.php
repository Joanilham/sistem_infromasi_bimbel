{{--
    Idle Timer Component — Auto Logout setelah 30 menit tidak aktif.
    Memerlukan SweetAlert2 (sudah tersedia di layouts/admin/scripts.blade.php).
--}}
<script>
(function () {
    'use strict';

    // ── Konfigurasi ──────────────────────────────────────────────────────────
    const IDLE_TIMEOUT_MS   = 29 * 60 * 1000;  // 29 menit idle → tampilkan peringatan
    const WARNING_DURATION  = 60;               // 60 detik countdown sebelum logout
    const LOGOUT_URL        = "{{ route('logout') }}";
    const CSRF_TOKEN        = "{{ csrf_token() }}";
    const PING_INTERVAL_MS  = 5 * 60 * 1000;   // Ping server tiap 5 menit untuk keep-alive
    // ─────────────────────────────────────────────────────────────────────────

    let idleTimer        = null;
    let pingTimer        = null;
    let countdownInterval = null;
    let warningShowing   = false;

    /**
     * Reset timer setiap kali ada aktivitas pengguna.
     */
    function resetIdleTimer() {
        if (warningShowing) return; // Jangan reset jika modal peringatan sedang tampil

        clearTimeout(idleTimer);
        idleTimer = setTimeout(showWarning, IDLE_TIMEOUT_MS);
    }

    /**
     * Ping server untuk menjaga session tetap hidup selama user aktif.
     */
    function startPingTimer() {
        clearInterval(pingTimer);
        pingTimer = setInterval(function () {
            if (!warningShowing) {
                fetch("{{ url('/') }}", { method: 'HEAD', credentials: 'same-origin' })
                    .catch(() => {}); // Ignore error, hanya untuk refresh session
            }
        }, PING_INTERVAL_MS);
    }

    /**
     * Tampilkan modal peringatan countdown dengan SweetAlert2.
     */
    function showWarning() {
        warningShowing = true;
        clearInterval(pingTimer); // Hentikan ping saat user idle

        let secondsLeft = WARNING_DURATION;

        const isDark = document.documentElement.classList.contains('dark');

        Swal.fire({
            title: '⚠️ Sesi Hampir Berakhir',
            html: `
                <p class="text-sm text-slate-500 mb-3">
                    Anda tidak aktif selama 30 menit. Sesi akan berakhir dalam:
                </p>
                <div id="idle-countdown" class="text-5xl font-black text-indigo-600 tabular-nums">${secondsLeft}</div>
                <p class="text-xs text-slate-400 mt-2">detik</p>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Lanjutkan Sesi',
            cancelButtonText: 'Logout Sekarang',
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#ef4444',
            allowOutsideClick: false,
            allowEscapeKey: false,
            background: isDark ? '#18181b' : '#ffffff',
            color: isDark ? '#f8fafc' : '#0f172a',
            customClass: {
                popup: 'rounded-2xl border border-slate-200 dark:border-zinc-700',
                title: 'text-lg font-black',
            },
            didOpen: function () {
                // Mulai hitung mundur
                countdownInterval = setInterval(function () {
                    secondsLeft--;
                    const el = document.getElementById('idle-countdown');
                    if (el) {
                        el.textContent = secondsLeft;
                        // Ubah warna saat mendekati 0
                        if (secondsLeft <= 10) {
                            el.classList.remove('text-indigo-600');
                            el.classList.add('text-rose-600');
                        }
                    }
                    if (secondsLeft <= 0) {
                        clearInterval(countdownInterval);
                        performLogout();
                    }
                }, 1000);
            },
            willClose: function () {
                clearInterval(countdownInterval);
            }
        }).then(function (result) {
            if (result.isConfirmed) {
                // User klik "Lanjutkan Sesi"
                warningShowing = false;
                startPingTimer();
                resetIdleTimer();
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // User klik "Logout Sekarang"
                performLogout();
            }
        });
    }

    /**
     * Kirim POST ke route logout Laravel.
     */
    function performLogout() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = LOGOUT_URL;

        const csrf = document.createElement('input');
        csrf.type  = 'hidden';
        csrf.name  = '_token';
        csrf.value = CSRF_TOKEN;

        form.appendChild(csrf);
        document.body.appendChild(form);
        form.submit();
    }

    // ── Daftarkan event listener aktivitas pengguna ───────────────────────────
    const ACTIVITY_EVENTS = [
        'mousemove', 'mousedown', 'keydown',
        'touchstart', 'touchmove', 'scroll', 'click'
    ];

    ACTIVITY_EVENTS.forEach(function (event) {
        document.addEventListener(event, resetIdleTimer, { passive: true });
    });

    // ── Inisialisasi ──────────────────────────────────────────────────────────
    resetIdleTimer();
    startPingTimer();

})();
</script>
