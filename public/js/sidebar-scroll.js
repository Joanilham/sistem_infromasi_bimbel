// Sidebar Scroll Persistence logic optimized for Turbo 8 and Normal Page Loads
(function() {
    const STORAGE_KEY = 'sidebarScrollTop';
    
    function saveScroll() {
        const sidebar = document.getElementById('sidebar-scroll-container');
        if (sidebar) {
            localStorage.setItem(STORAGE_KEY, sidebar.scrollTop);
        }
    }

    function restoreScroll() {
        const sidebar = document.getElementById('sidebar-scroll-container');
        if (sidebar) {
            const saved = localStorage.getItem(STORAGE_KEY);
            if (saved !== null) {
                const pos = parseInt(saved, 10);
                // Set immediately
                sidebar.scrollTop = pos;
                
                // Set after a brief delay in case of Alpine or rendering lag
                setTimeout(() => { if (sidebar) sidebar.scrollTop = pos; }, 50);
                setTimeout(() => { if (sidebar) sidebar.scrollTop = pos; }, 150);
                setTimeout(() => { if (sidebar) sidebar.scrollTop = pos; }, 300);
            }
        }
    }

    // Capture state before leaving
    window.addEventListener('beforeunload', saveScroll);
    document.addEventListener('turbo:before-visit', saveScroll);
    document.addEventListener('turbo:before-cache', saveScroll);

    // Restore state when entering
    document.addEventListener('DOMContentLoaded', restoreScroll);
    document.addEventListener('turbo:render', restoreScroll);
    document.addEventListener('turbo:load', restoreScroll);
    
    // Also save state periodically on scroll, debounced, to ensure we catch everything
    let scrollTimeout;
    document.addEventListener('scroll', function(e) {
        if (e.target && e.target.id === 'sidebar-scroll-container') {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(saveScroll, 100);
        }
    }, true); // use capture phase
})();
