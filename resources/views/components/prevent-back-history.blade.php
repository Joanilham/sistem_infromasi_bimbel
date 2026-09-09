{{--
    Prevent Back History & Invalidate Back-Forward Cache (bfcache).
    Memastikan halaman yang memerlukan otentikasi tidak dapat dipulihkan dari memori peramban (bfcache)
    setelah pengguna melakukan logout.
--}}
<script>
    (function () {
        'use strict';

        // 1. Tangani pemulihan dari Back-Forward Cache (bfcache):
        // Jika halaman dipulihkan dari memori peramban, paksa peramban memvalidasi ulang ke server
        window.addEventListener('pageshow', function (event) {
            const isBackForward = event.persisted || 
                (window.performance && window.performance.navigation && window.performance.navigation.type === 2) ||
                (window.performance && window.performance.getEntriesByType && window.performance.getEntriesByType('navigation')[0] && window.performance.getEntriesByType('navigation')[0].type === 'back_forward');

            if (isBackForward) {
                window.location.reload();
            }
        });

        // 2. Di halaman Login: Kunci riwayat agar tombol Back peramban tidak kembali ke halaman sistem sebelumnya
        const currentPath = window.location.pathname;
        if (currentPath === '/login' || currentPath.endsWith('/login')) {
            if (window.history && window.history.pushState) {
                window.history.pushState(null, '', window.location.href);
                window.addEventListener('popstate', function () {
                    window.history.pushState(null, '', window.location.href);
                });
            }
        }
    })();
</script>
