/**
 * InstantNav - Intelligent Link Prefetching & Speculation Rules
 * Mengeliminasi delay perpindahan halaman dengan melakukan background prefetch saat kursor hover (65ms intent)
 * atau saat pointer menyentuh layar pada perangkat seluler.
 */
(function() {
    'use strict';

    const isSupported = document.createElement('link').relList && 
                        document.createElement('link').relList.supports && 
                        document.createElement('link').relList.supports('prefetch');
    
    const prefetchedUrls = new Set();
    let hoverTimer = null;
    const HOVER_DELAY = 65; // ms delay untuk mendeteksi intent pengguna dan menghindari prefetch saat swipe cursor cepat

    function shouldPrefetch(anchor) {
        if (!anchor || !anchor.href) return false;
        
        // Hanya protokol http dan https
        if (!anchor.href.startsWith('http://') && !anchor.href.startsWith('https://')) return false;

        // Hanya domain yang sama (same-origin)
        if (anchor.origin !== window.location.origin) return false;

        // Bukan halaman yang sama persis atau loncat anchor (#)
        if (anchor.pathname === window.location.pathname && anchor.search === window.location.search) return false;
        if (anchor.hash && anchor.pathname === window.location.pathname) return false;

        // Hindari aksi berbahaya / mutasi state / download besar
        const path = anchor.pathname.toLowerCase();
        if (
            path.includes('/logout') ||
            path.includes('/delete') ||
            path.includes('/hapus') ||
            path.includes('/export') ||
            path.includes('/download') ||
            path.includes('/cetak') ||
            path.includes('/print') ||
            path.includes('/system/platform-verify')
        ) return false;

        // Hindari link dengan atribut target _blank, download, atau penanda opt-out
        if (anchor.target === '_blank' || 
            anchor.hasAttribute('download') || 
            anchor.hasAttribute('data-no-instant') || 
            anchor.closest('[data-no-instant]')) {
            return false;
        }

        // Hemat kuota jika browser sedang mengaktifkan Data Saver atau koneksi 2G
        const conn = navigator.connection;
        if (conn && (conn.saveData || (conn.effectiveType && conn.effectiveType.includes('2g')))) {
            return false;
        }

        // Sudah diprefetch sebelumnya
        if (prefetchedUrls.has(anchor.href)) return false;

        return true;
    }

    function prefetch(url) {
        if (prefetchedUrls.has(url)) return;
        prefetchedUrls.add(url);

        if (isSupported) {
            const link = document.createElement('link');
            link.rel = 'prefetch';
            link.href = url;
            link.as = 'document';
            document.head.appendChild(link);
        } else {
            // Fallback fetch dengan prioritas rendah
            fetch(url, { priority: 'low', credentials: 'same-origin' }).catch(() => {});
        }
    }

    function onMouseOver(e) {
        const anchor = e.target.closest('a');
        if (!shouldPrefetch(anchor)) return;

        hoverTimer = setTimeout(() => {
            prefetch(anchor.href);
        }, HOVER_DELAY);
    }

    function onMouseOut(e) {
        const anchor = e.target.closest('a');
        if (anchor && hoverTimer) {
            clearTimeout(hoverTimer);
            hoverTimer = null;
        }
    }

    function onTouchStart(e) {
        const anchor = e.target.closest('a');
        if (shouldPrefetch(anchor)) {
            prefetch(anchor.href);
        }
    }

    // Pasang listener secara pasif agar scrolling tetap 60fps tanpa lagging
    document.addEventListener('mouseover', onMouseOver, { passive: true });
    document.addEventListener('mouseout', onMouseOut, { passive: true });
    document.addEventListener('touchstart', onTouchStart, { passive: true });

    // Inject Speculation Rules API untuk Chromium 108+ (Chrome / Edge / Opera)
    if (HTMLScriptElement.supports && HTMLScriptElement.supports('speculationrules')) {
        try {
            const specScript = document.createElement('script');
            specScript.type = 'speculationrules';
            specScript.textContent = JSON.stringify({
                prefetch: [
                    {
                        source: 'document',
                        where: {
                            and: [
                                { href_matches: '/*' },
                                { not: { href_matches: '*/logout*' } },
                                { not: { href_matches: '*/delete*' } },
                                { not: { href_matches: '*/hapus*' } },
                                { not: { href_matches: '*/export*' } },
                                { not: { href_matches: '*/download*' } },
                                { not: { href_matches: '*/cetak*' } },
                                { not: { selector_matches: '[data-no-instant]' } }
                            ]
                        },
                        eagerness: 'moderate'
                    }
                ]
            });
            document.head.appendChild(specScript);
        } catch (e) {}
    }
})();
