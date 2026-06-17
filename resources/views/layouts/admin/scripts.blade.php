
<!-- SweetAlert2 -->
<script defer src="{{ asset('vendor/sweetalert2/sweetalert2.min.js') }}"></script>

<!-- Global Delete Confirmation Script -->
<script>
    function confirmDelete(title, text, formElement) {
        Swal.fire({
            title: title || 'Apakah Anda yakin?',
            text: text || "Data yang dihapus tidak dapat direstore!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5', // Indigo 600
            cancelButtonColor: '#ef4444', // Red 500
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
            color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a',
            customClass: {
                popup: 'rounded-2xl border border-slate-100 dark:border-slate-700',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                formElement.submit();
            }
        })
    }
</script>

@yield('scripts')

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('ajaxTable', () => ({
            isLoading: false,
            _safetyTimer: null,
            
            fetchData(e) {
                let form = null;
                if (e && e.target && e.target.tagName === 'FORM') {
                    form = e.target;
                } else if (e && e.target && e.target.form) {
                    form = e.target.form;
                } else {
                    form = this.$el.querySelector('form');
                }

                if (!form) return;

                let url = new URL(form.action || window.location.href);
                let formData = new FormData(form);
                
                url.search = '';
                for (let [key, value] of formData.entries()) {
                    if (value) url.searchParams.append(key, value);
                }

                this.doFetch(url.toString());
            },
            
            navigate(e, urlStr) {
                if (e) e.preventDefault();
                this.doFetch(urlStr);
            },

            doFetch(urlStr) {
                // Prevent double-fetch while loading
                if (this.isLoading) return;

                this.isLoading = true;

                // Safety timeout: force-reset loading after 15 seconds
                clearTimeout(this._safetyTimer);
                this._safetyTimer = setTimeout(() => {
                    if (this.isLoading) {
                        console.warn('AJAX safety timeout: force-resetting loading state');
                        this.isLoading = false;
                        if (typeof App !== 'undefined' && App.Progress) {
                            App.Progress.fail();
                        }
                    }
                }, 15000);
                
                fetch(urlStr, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => {
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    return res.text();
                })
                .then(html => {
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(html, 'text/html');
                    
                    // Update elements safely, preserving the loading overlay
                    const updateElement = (id) => {
                        let newEl = doc.getElementById(id);
                        let oldEl = document.getElementById(id);
                        if (newEl && oldEl) {
                            // Save loading overlay (controlled by Alpine x-show)
                            // before replacing innerHTML to preserve Alpine bindings
                            const loadingOverlay = oldEl.querySelector('[x-show="isLoading"]');
                            
                            oldEl.innerHTML = newEl.innerHTML;
                            
                            // Remove the non-reactive loading overlay from new HTML
                            // and re-insert the original Alpine-bound one
                            if (loadingOverlay) {
                                const staleOverlay = oldEl.querySelector('[x-show="isLoading"]');
                                if (staleOverlay) staleOverlay.remove();
                                oldEl.insertBefore(loadingOverlay, oldEl.firstChild);
                            }
                        }
                    };

                    updateElement('ajax-summary-cards');
                    updateElement('ajax-table-body');
                    updateElement('ajax-pagination');
                    
                    // Update URL silently
                    window.history.pushState({}, '', urlStr);
                })
                .catch(error => {
                    console.error('AJAX Error:', error);
                    if (typeof App !== 'undefined' && App.Toast) {
                        App.Toast.error('Gagal Memuat', 'Terjadi kesalahan saat memuat data. Silakan coba lagi.');
                    }
                })
                .finally(() => {
                    clearTimeout(this._safetyTimer);
                    this.isLoading = false;
                    if (typeof App !== 'undefined' && App.Progress) {
                        App.Progress.done();
                    }
                });
            }
        }));
    });
</script>