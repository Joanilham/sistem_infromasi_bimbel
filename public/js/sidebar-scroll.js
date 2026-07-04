// Sidebar Scroll Persistence (Fixed Glitch on Manual Scroll)
(function() {
    const STORAGE_KEY = 'sidebarScrollTop';
    let latestScrollTop = null;
    let isRestoring = false;

    function saveScroll() {
        if (isRestoring) return; // Don't save while restoring
        var sidebar = document.getElementById('sidebar-scroll-container');
        if (sidebar) {
            latestScrollTop = sidebar.scrollTop;
            localStorage.setItem(STORAGE_KEY, sidebar.scrollTop);
        }
    }

    function restoreScroll() {
        var sidebar = document.getElementById('sidebar-scroll-container');
        if (!sidebar) return;

        var saved = localStorage.getItem(STORAGE_KEY);
        if (saved === null) return;

        var targetPos = parseInt(saved, 10);
        isRestoring = true;
        
        let animationFrameId;
        let timeoutIds = [];

        // Stop restoring if user manually scrolls or interacts
        function cancelRestore() {
            isRestoring = false;
            if (animationFrameId) cancelAnimationFrame(animationFrameId);
            timeoutIds.forEach(clearTimeout);
            sidebar.removeEventListener('wheel', cancelRestore);
            sidebar.removeEventListener('touchstart', cancelRestore);
            sidebar.removeEventListener('mousedown', cancelRestore);
        }

        // Listen for user interaction to cancel the forced scroll
        sidebar.addEventListener('wheel', cancelRestore, { passive: true });
        sidebar.addEventListener('touchstart', cancelRestore, { passive: true });
        sidebar.addEventListener('mousedown', cancelRestore, { passive: true });

        function attemptScroll() {
            if (!isRestoring) return true; // Abort if cancelled
            
            if (sidebar.offsetHeight > 0) {
                sidebar.scrollTop = targetPos;
                var maxScroll = sidebar.scrollHeight - sidebar.clientHeight;
                var acceptablePos = Math.min(targetPos, maxScroll);
                // Return true if we reached the target or the true max limit
                if (sidebar.scrollTop >= acceptablePos - 5 && maxScroll >= targetPos) {
                    return true;
                }
            }
            return false;
        }

        if (attemptScroll()) {
            cancelRestore();
            return;
        }

        var start = performance.now();
        function poll() {
            if (!isRestoring) return;
            var success = attemptScroll();
            if (!success && performance.now() - start < 800) {
                animationFrameId = requestAnimationFrame(poll);
            } else {
                cancelRestore();
            }
        }
        animationFrameId = requestAnimationFrame(poll);

        var intervals = [50, 150, 300];
        intervals.forEach(function(ms) {
            timeoutIds.push(setTimeout(function() {
                if (isRestoring) attemptScroll();
            }, ms));
        });
    }

    var scrollTimer;
    document.addEventListener('scroll', function(e) {
        if (e.target && e.target.id === 'sidebar-scroll-container') {
            clearTimeout(scrollTimer);
            scrollTimer = setTimeout(saveScroll, 50);
        }
    }, true);
    
    document.addEventListener('click', function(e) {
        if (e.target && e.target.closest('#sidebar-scroll-container a')) {
            saveScroll();
        }
    }, true);

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', restoreScroll);
    } else {
        restoreScroll();
    }
})();
