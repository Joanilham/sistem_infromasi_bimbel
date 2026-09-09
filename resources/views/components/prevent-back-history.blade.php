{{--
    Prevent Back History & Invalidate Back-Forward Cache (bfcache).
    Memastikan halaman yang memerlukan otentikasi tidak dapat dipulihkan dari memori peramban (bfcache)
    setelah pengguna melakukan logout.
--}}
<script>
    (function () {
        'use strict';
        window.addEventListener('pageshow', function (event) {
            const isBackForward = event.persisted || 
                (window.performance && window.performance.navigation && window.performance.navigation.type === 2) ||
                (window.performance && window.performance.getEntriesByType && window.performance.getEntriesByType('navigation')[0] && window.performance.getEntriesByType('navigation')[0].type === 'back_forward');

            if (isBackForward) {
                window.location.reload();
            }
        });
    })();
</script>
