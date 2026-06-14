<!-- Top progress bar (di atas semua konten) -->
<div id="top-progress"></div>

<!-- Page loader overlay -->
<div id="page-loader" role="status" aria-label="Memuat halaman">
    <div class="elegant-spinner"></div>
    <p class="loader-text">
        Memuat halaman<span class="loader-dots">
            <span>.</span><span>.</span><span>.</span>
        </span>
    </p>
</div>

<!-- Toast container -->
<div id="toast-container" role="region" aria-live="polite"></div>
<script defer src="{{ asset('js/app-loader.js') }}"></script>
