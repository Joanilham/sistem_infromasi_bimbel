// Sidebar Scroll Persistence — handles x-cloak and Alpine.js rendering
(function() {
    const STORAGE_KEY = 'sidebarScrollTop';

    // Save current scroll position
    function saveScroll() {
        var sidebar = document.getElementById('sidebar-scroll-container');
        if (sidebar && sidebar.scrollTop > 0) {
            localStorage.setItem(STORAGE_KEY, sidebar.scrollTop);
        }
    }

    // Restore scroll position
    function restoreScroll() {
        var sidebar = document.getElementById('sidebar-scroll-container');
        if (!sidebar) return;

        var saved = localStorage.getItem(STORAGE_KEY);
        if (saved === null) return;

        var targetPos = parseInt(saved, 10);
        if (targetPos <= 0) return;

        // Function to attempt setting the scroll
        function attemptScroll() {
            // Only set if the sidebar is actually visible (x-cloak removed)
            if (sidebar.offsetHeight > 0 || sidebar.clientHeight > 0) {
                sidebar.scrollTop = targetPos;
                return sidebar.scrollTop >= targetPos - 5;
            }
            return false;
        }

        // Try immediately
        attemptScroll();

        // Keep trying aggressively for the first 1 second (to catch Alpine.js removing x-cloak)
        var start = performance.now();
        function poll() {
            var success = attemptScroll();
            if (!success && performance.now() - start < 1500) {
                requestAnimationFrame(poll);
            }
        }
        requestAnimationFrame(poll);

        // Fallbacks for deeply nested/slow rendering
        var intervals = [50, 100, 200, 400, 800, 1500];
        intervals.forEach(function(ms) {
            setTimeout(attemptScroll, ms);
        });
    }

    // Save before leaving the page
    window.addEventListener('beforeunload', saveScroll);

    // Save on every scroll (debounced)
    var scrollTimer;
    document.addEventListener('scroll', function(e) {
        if (e.target && e.target.id === 'sidebar-scroll-container') {
            clearTimeout(scrollTimer);
            scrollTimer = setTimeout(saveScroll, 100);
        }
    }, true);

    // Restore when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', restoreScroll);
    } else {
        restoreScroll();
    }
})();
