// Sidebar Scroll Persistence — waits for Alpine.js to finish rendering submenus
(function() {
    const STORAGE_KEY = 'sidebarScrollTop';

    // Save current scroll position
    function saveScroll() {
        var sidebar = document.getElementById('sidebar-scroll-container');
        if (sidebar && sidebar.scrollTop > 0) {
            localStorage.setItem(STORAGE_KEY, sidebar.scrollTop);
        }
    }

    // Restore scroll position, retrying until the sidebar is tall enough
    function restoreScroll() {
        var sidebar = document.getElementById('sidebar-scroll-container');
        if (!sidebar) return;

        var saved = localStorage.getItem(STORAGE_KEY);
        if (saved === null) return;

        var targetPos = parseInt(saved, 10);
        if (targetPos <= 0) return;

        // Attempt to set scroll immediately
        sidebar.scrollTop = targetPos;

        // Use MutationObserver to keep retrying as Alpine.js reveals hidden submenus
        // (x-show elements change from display:none to display:block, increasing scrollHeight)
        var attempts = 0;
        var maxAttempts = 30; // stop after ~3 seconds
        var observer = new MutationObserver(function() {
            attempts++;
            sidebar.scrollTop = targetPos;
            // Stop observing once we've reached the target or exhausted attempts
            if (sidebar.scrollTop >= targetPos - 5 || attempts >= maxAttempts) {
                observer.disconnect();
            }
        });

        observer.observe(sidebar, {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['style', 'class']
        });

        // Also use timed fallbacks for safety
        var intervals = [50, 100, 200, 400, 800, 1500];
        intervals.forEach(function(ms) {
            setTimeout(function() {
                sidebar.scrollTop = targetPos;
            }, ms);
        });

        // Clean up observer after 3 seconds no matter what
        setTimeout(function() {
            observer.disconnect();
        }, 3000);
    }

    // Save before leaving the page
    window.addEventListener('beforeunload', saveScroll);

    // Also save on every scroll (debounced)
    var scrollTimer;
    document.addEventListener('scroll', function(e) {
        if (e.target && e.target.id === 'sidebar-scroll-container') {
            clearTimeout(scrollTimer);
            scrollTimer = setTimeout(saveScroll, 150);
        }
    }, true);

    // Restore when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', restoreScroll);
    } else {
        restoreScroll();
    }
})();
