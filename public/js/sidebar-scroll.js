// Sidebar Scroll Persistence — Save Only
// Restoration is handled by an inline <script> in sidebar.blade.php for instant, glitch-free restore.
(function() {
    var STORAGE_KEY = 'sidebarScrollTop';

    function saveScroll() {
        var sidebar = document.getElementById('sidebar-scroll-container');
        if (sidebar) {
            localStorage.setItem(STORAGE_KEY, sidebar.scrollTop);
        }
    }

    // Save on scroll events (debounced)
    var scrollTimer;
    document.addEventListener('scroll', function(e) {
        if (e.target && e.target.id === 'sidebar-scroll-container') {
            clearTimeout(scrollTimer);
            scrollTimer = setTimeout(saveScroll, 100);
        }
    }, true);

    // Save immediately when clicking a sidebar link (before navigation)
    document.addEventListener('click', function(e) {
        if (e.target && e.target.closest('#sidebar-scroll-container a')) {
            saveScroll();
        }
    }, true);
})();
